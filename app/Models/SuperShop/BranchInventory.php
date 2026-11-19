<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop\ShopProduct;

class BranchInventory extends Model
{
    use HasFactory;

    protected $table = 'branch_inventories';

    protected $fillable = [
        'branch_id',
        'product_id',
        'stock_quantity',
        'reserved_stock',
        'reorder_level',
        'safety_stock',
        'purchase_price',
        'selling_price',
        'shelf_location',
        'is_active',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function product()
    {
        return $this->belongsTo(ShopProduct::class, 'product_id');
    }

    public function batches()
    {
        return $this->hasMany(InventoryBatch::class, 'product_id', 'product_id')
                    ->where('branch_id', $this->branch_id);
    }

    public function getEffectiveSellingPriceAttribute()
    {
        return $this->selling_price !== null ? (float)$this->selling_price : (float)($this->product->price ?? 0);
    }

    public function getIsLowStockAttribute()
    {
        return $this->stock_quantity <= $this->reorder_level;
    }

    public function getAvailableStockAttribute()
    {
        return max(0, $this->stock_quantity - $this->reserved_stock);
    }

    /**
     * Auto-sync all active ShopProducts into a given branch.
     * Ensures every product uploaded in the E-Commerce module automatically exists in the branch inventory.
     */
    public static function syncBranchProducts($branchId): int
    {
        if (!$branchId) {
            return 0;
        }

        $activeProducts = ShopProduct::where('is_active', true)->get(['id', 'price', 'purchase_price', 'stock_quantity']);
        if ($activeProducts->isEmpty()) {
            return 0;
        }

        $existingProductIds = self::where('branch_id', $branchId)->pluck('product_id')->toArray();
        $missingProducts = $activeProducts->whereNotIn('id', $existingProductIds);

        $insertedCount = 0;
        $now = now();
        $batch = [];

        foreach ($missingProducts as $p) {
            $batch[] = [
                'branch_id' => $branchId,
                'product_id' => $p->id,
                'stock_quantity' => (int)($p->stock_quantity ?: 0),
                'reserved_stock' => 0,
                'reorder_level' => 10,
                'safety_stock' => 5,
                'purchase_price' => $p->purchase_price ?: round((float)$p->price * 0.8, 2),
                'selling_price' => (float)$p->price,
                'shelf_location' => null,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($batch) >= 100) {
                self::insert($batch);
                $insertedCount += count($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            self::insert($batch);
            $insertedCount += count($batch);
        }

        return $insertedCount;
    }

    /**
     * Auto-sync a newly created or updated product to all active branches.
     */
    public static function syncNewProductToBranches(ShopProduct $product): void
    {
        if (!$product || !$product->id) {
            return;
        }

        $branches = Branch::where('status', 'active')->get();
        $now = now();

        foreach ($branches as $branch) {
            self::firstOrCreate(
                [
                    'branch_id' => $branch->id,
                    'product_id' => $product->id,
                ],
                [
                    'stock_quantity' => (int)($product->stock_quantity ?: 0),
                    'reserved_stock' => 0,
                    'reorder_level' => 10,
                    'safety_stock' => 5,
                    'purchase_price' => $product->purchase_price ?: round((float)$product->price * 0.8, 2),
                    'selling_price' => (float)$product->price,
                    'shelf_location' => null,
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }

    /**
     * Synchronize all branches with the central catalog.
     */
    public static function syncAllBranches(): int
    {
        $branches = Branch::where('status', 'active')->get();
        $totalSynced = 0;
        foreach ($branches as $b) {
            $totalSynced += self::syncBranchProducts($b->id);
        }
        return $totalSynced;
    }
}

