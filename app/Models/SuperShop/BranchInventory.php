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
}

