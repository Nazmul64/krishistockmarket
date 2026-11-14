<?php

namespace App\Http\Controllers\Admin\SuperShop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuperShop\Branch;
use App\Models\SuperShop\BranchInventory;
use App\Models\SuperShop\StockMovement;
use App\Models\Shop\ShopProduct;
use App\Models\Shop\ShopCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BranchInventoryController extends Controller
{
    /**
     * Display branch inventory list.
     */
    public function index(Request $request)
    {
        $branches = Branch::where('status', 'active')->orderBy('id', 'asc')->get();
        $categories = ShopCategory::where('is_active', true)->orderBy('name', 'asc')->get();

        $selectedBranchId = $request->get('branch_id', $branches->first()?->id);
        $selectedCatId = $request->get('category_id');
        $lowStockOnly = $request->has('low_stock');
        $search = trim($request->get('search', ''));

        $query = BranchInventory::with(['branch', 'product.category'])
            ->where('branch_id', $selectedBranchId);

        if ($selectedCatId) {
            $query->whereHas('product', function ($q) use ($selectedCatId) {
                $q->where('category_id', $selectedCatId);
            });
        }

        if ($lowStockOnly) {
            $query->whereRaw('stock_quantity <= reorder_level');
        }

        if ($search !== '') {
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $inventories = $query->orderBy('stock_quantity', 'asc')->paginate(25)->withQueryString();

        // Calculate summary for this branch
        $allBranchInv = BranchInventory::where('branch_id', $selectedBranchId)->get();
        $totalItems = $allBranchInv->count();
        $totalStockUnits = $allBranchInv->sum('stock_quantity');
        $totalLowStock = $allBranchInv->where('stock_quantity', '<=', 15)->count();
        $outOfStock = $allBranchInv->where('stock_quantity', '<=', 0)->count();

        // Available products for assignment modal (products not yet assigned to this branch)
        $assignedProductIds = $allBranchInv->pluck('product_id')->toArray();
        $unassignedProducts = ShopProduct::whereNotIn('id', $assignedProductIds)->where('is_active', true)->get();

        return view('admin.supershop.inventory.index', compact(
            'branches',
            'categories',
            'selectedBranchId',
            'selectedCatId',
            'lowStockOnly',
            'search',
            'inventories',
            'totalItems',
            'totalStockUnits',
            'totalLowStock',
            'outOfStock',
            'unassignedProducts'
        ));
    }

    /**
     * Manual Stock Adjustment with Audit Movement Log.
     */
    public function adjustStock(Request $request, $id)
    {
        $request->validate([
            'adjustment_type' => 'required|in:add,subtract,set',
            'quantity' => 'required|numeric|min:0',
            'reason' => 'required|string',
        ]);

        $inv = BranchInventory::with('product')->findOrFail($id);
        $oldStock = $inv->stock_quantity;
        $qty = (int)$request->quantity;

        DB::beginTransaction();
        try {
            if ($request->adjustment_type === 'add') {
                $inv->stock_quantity += $qty;
                $movementQty = $qty;
            } elseif ($request->adjustment_type === 'subtract') {
                $inv->stock_quantity = max(0, $inv->stock_quantity - $qty);
                $movementQty = -$qty;
            } else {
                $movementQty = $qty - $oldStock;
                $inv->stock_quantity = $qty;
            }

            if ($request->filled('shelf_location')) {
                $inv->shelf_location = $request->shelf_location;
            }
            if ($request->filled('reorder_level')) {
                $inv->reorder_level = (int)$request->reorder_level;
            }
            if ($request->filled('selling_price')) {
                $inv->selling_price = (float)$request->selling_price ?: null;
            }

            $inv->save();

            // Record in audit log
            StockMovement::create([
                'branch_id' => $inv->branch_id,
                'product_id' => $inv->product_id,
                'type' => 'adjustment',
                'quantity' => $movementQty,
                'balance_after' => $inv->stock_quantity,
                'reference_no' => 'ADJ-' . date('ymd') . '-' . rand(100, 999),
                'user_id' => Auth::id(),
                'notes' => $request->reason . " (আগের স্টক: {$oldStock}, নতুন স্টক: {$inv->stock_quantity})",
            ]);

            DB::commit();
            return back()->with('success', "পণ্য '{$inv->product->name}' এর স্টক সফলভাবে অ্যাডজাস্ট করা হয়েছে! (বর্তমান স্টক: {$inv->stock_quantity})");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'স্টক অ্যাডজাস্ট করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * Assign product from Central Catalog to Branch.
     */
    public function assignProduct(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'product_id' => 'required|exists:shop_products,id',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $product = ShopProduct::findOrFail($request->product_id);

        $inv = BranchInventory::updateOrCreate(
            [
                'branch_id' => $request->branch_id,
                'product_id' => $request->product_id,
            ],
            [
                'stock_quantity' => (int)$request->stock_quantity,
                'reorder_level' => (int)($request->reorder_level ?: 10),
                'safety_stock' => (int)($request->safety_stock ?: 5),
                'shelf_location' => $request->shelf_location,
                'purchase_price' => round($product->price * 0.8, 2),
                'selling_price' => $request->selling_price ? (float)$request->selling_price : null,
                'is_active' => true,
            ]
        );

        StockMovement::create([
            'branch_id' => $request->branch_id,
            'product_id' => $product->id,
            'type' => 'purchase_in',
            'quantity' => (int)$request->stock_quantity,
            'balance_after' => $inv->stock_quantity,
            'reference_no' => 'ASSIGN-' . date('ymd'),
            'user_id' => Auth::id(),
            'notes' => 'সেন্ট্রাল ক্যাটালগ থেকে শাখায় পণ্য ও প্রাথমিক স্টক নির্ধারণ।',
        ]);

        return back()->with('success', "পণ্য '{$product->name}' সফলভাবে শাখায় অ্যাসাইন করা হয়েছে!");
    }

    /**
     * Stock Movement Audit Trail History.
     */
    public function movements(Request $request)
    {
        $branches = Branch::where('status', 'active')->get();
        $selectedBranchId = $request->get('branch_id');

        $query = StockMovement::with(['branch', 'product', 'user'])->orderBy('id', 'desc');

        if ($selectedBranchId) {
            $query->where('branch_id', $selectedBranchId);
        }

        $movements = $query->paginate(25)->withQueryString();

        return view('admin.supershop.inventory.movements', compact('movements', 'branches', 'selectedBranchId'));
    }
}

