<?php

namespace App\Http\Controllers\Admin\SuperShop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuperShop\Branch;
use App\Models\SuperShop\PosCounter;
use App\Models\SuperShop\CashierShift;
use App\Models\SuperShop\BranchInventory;
use App\Models\SuperShop\PosSale;
use App\Models\SuperShop\PosSaleItem;
use App\Models\SuperShop\PosPayment;
use App\Models\SuperShop\StockMovement;
use App\Models\SuperShop\LoyaltyMembership;
use App\Models\SuperShop\LoyaltyPointsLedger;
use App\Models\Shop\ShopProduct;
use App\Models\Shop\ShopCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PosTerminalController extends Controller
{
    /**
     * Launch POS Terminal.
     */
    public function terminal(Request $request)
    {
        $branches = Branch::where('status', 'active')->orderBy('id', 'asc')->get();
        if ($branches->isEmpty()) {
            $defaultBranch = Branch::create([
                'name' => 'প্রধান শাখা / সুপার শপ',
                'code' => 'BR-01',
                'type' => 'super_shop',
                'phone' => '01700000000',
                'address' => 'ঢাকা, বাংলাদেশ',
                'status' => 'active',
            ]);
            $branches = collect([$defaultBranch]);
        }

        $selectedBranchId = (int)$request->get('branch_id', $branches->first()?->id);
        $selectedBranch = Branch::with('posCounters')->find($selectedBranchId);

        $counters = $selectedBranch ? $selectedBranch->posCounters->where('status', 'active') : collect();
        $selectedCounterId = $request->get('counter_id', $counters->first()?->id);

        // Check for active shift
        $activeShift = CashierShift::where('branch_id', $selectedBranchId)
            ->where('cashier_id', Auth::id() ?: 1)
            ->where('status', 'open')
            ->first();

        // Categories for visual quick filter in POS
        $categories = ShopCategory::where('is_active', true)->orderBy('name', 'asc')->get();

        // Initial products available in this branch (ordered by latest from all 4,890 active e-commerce products)
        $initialProducts = ShopProduct::with(['category', 'brand'])
            ->leftJoin('branch_inventories', function ($join) use ($selectedBranchId) {
                $join->on('shop_products.id', '=', 'branch_inventories.product_id')
                     ->where('branch_inventories.branch_id', '=', $selectedBranchId);
            })
            ->select(
                'shop_products.*',
                'branch_inventories.shelf_location',
                'branch_inventories.purchase_price as branch_purchase_price',
                'branch_inventories.selling_price as branch_selling_price',
                DB::raw('COALESCE(branch_inventories.stock_quantity, shop_products.stock_quantity) as branch_stock')
            )
            ->where('shop_products.is_active', true)
            ->orderBy('shop_products.id', 'desc')
            ->take(48)
            ->get();

        return view('admin.supershop.pos.terminal', compact(
            'branches',
            'selectedBranch',
            'selectedBranchId',
            'counters',
            'selectedCounterId',
            'activeShift',
            'categories',
            'initialProducts'
        ));
    }

    /**
     * Fast Barcode Scanner Search API (HID Scanner friendly).
     */
    public function barcodeSearch(Request $request)
    {
        $code = trim($request->get('barcode', ''));
        $branchId = (int)$request->get('branch_id');

        if (!$code || !$branchId) {
            return response()->json(['success' => false, 'message' => 'বারকোড বা শাখা আইডি দেওয়া হয়নি।'], 400);
        }

        // Look for matching product in central catalog with branch stock
        $product = ShopProduct::with(['category', 'brand'])
            ->leftJoin('branch_inventories', function ($join) use ($branchId) {
                $join->on('shop_products.id', '=', 'branch_inventories.product_id')
                     ->where('branch_inventories.branch_id', '=', $branchId);
            })
            ->select(
                'shop_products.*',
                'branch_inventories.shelf_location',
                'branch_inventories.purchase_price as branch_purchase_price',
                'branch_inventories.selling_price as branch_selling_price',
                DB::raw('COALESCE(branch_inventories.stock_quantity, shop_products.stock_quantity) as branch_stock')
            )
            ->where('shop_products.is_active', true)
            ->where(function ($q) use ($code) {
                $q->where('shop_products.sku', $code)
                  ->orWhere('shop_products.id', $code)
                  ->orWhere('shop_products.name', $code)
                  ->orWhere('shop_products.name_bn', $code);
            })
            ->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'বারকোড বা কোড অনুযায়ী কোনো পণ্য পাওয়া যায়নি!'], 404);
        }

        $stock = (int)$product->branch_stock;
        $price = $product->branch_selling_price ? (float)$product->branch_selling_price : (float)$product->price;
        $cost = $product->branch_purchase_price ? (float)$product->branch_purchase_price : round($price * 0.8, 2);
        $img = $product->image ?: $product->thumbnail;

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'name_bn' => $product->name_bn,
                'sku' => $product->sku,
                'barcode' => $product->sku,
                'price' => $price,
                'cost_price' => $cost,
                'stock' => $stock,
                'image' => $img ? asset($img) : asset('assets/images/placeholder.png'),
            ]
        ]);
    }

    /**
     * Live Product Search in POS Terminal across all 4,890 e-commerce products.
     */
    public function searchProducts(Request $request)
    {
        $q = trim($request->get('q', ''));
        $branchId = (int)$request->get('branch_id');
        $catId = $request->get('category_id');

        $query = ShopProduct::with(['category', 'brand'])
            ->leftJoin('branch_inventories', function ($join) use ($branchId) {
                $join->on('shop_products.id', '=', 'branch_inventories.product_id')
                     ->where('branch_inventories.branch_id', '=', $branchId);
            })
            ->select(
                'shop_products.*',
                'branch_inventories.shelf_location',
                'branch_inventories.purchase_price as branch_purchase_price',
                'branch_inventories.selling_price as branch_selling_price',
                DB::raw('COALESCE(branch_inventories.stock_quantity, shop_products.stock_quantity) as branch_stock')
            )
            ->where('shop_products.is_active', true);

        if ($catId) {
            $query->where('shop_products.category_id', $catId);
        }

        if ($q !== '') {
            $query->where(function ($pq) use ($q) {
                $pq->where('shop_products.name', 'like', "%{$q}%")
                   ->orWhere('shop_products.name_bn', 'like', "%{$q}%")
                   ->orWhere('shop_products.sku', 'like', "%{$q}%");
            });
        }

        $items = $query->orderBy('shop_products.id', 'desc')->take(48)->get();

        return response()->json([
            'success' => true,
            'products' => $items->map(function ($prod) {
                $img = $prod->image ?: $prod->thumbnail;
                $price = $prod->branch_selling_price ? (float)$prod->branch_selling_price : (float)$prod->price;
                $cost = (float)($prod->branch_purchase_price ?: ($prod->purchase_price ?: round($price * 0.8, 2)));

                return [
                    'id' => $prod->id,
                    'name' => $prod->name,
                    'name_bn' => $prod->name_bn,
                    'sku' => $prod->sku,
                    'barcode' => $prod->sku,
                    'category' => $prod->category?->name ?? 'General',
                    'price' => $price,
                    'cost_price' => $cost,
                    'stock' => (int)$prod->branch_stock,
                    'shelf' => $prod->shelf_location,
                    'image' => $img ? asset($img) : asset('assets/images/placeholder.png'),
                ];
            })
        ]);
    }

    /**
     * Customer Phone Lookup for Loyalty Points.
     */
    public function customerLookup(Request $request)
    {
        $phone = trim($request->get('phone', ''));
        $membership = LoyaltyMembership::where('customer_phone', $phone)->first();

        if ($membership) {
            return response()->json([
                'found' => true,
                'name' => $membership->customer_name,
                'phone' => $membership->customer_phone,
                'tier' => strtoupper($membership->tier),
                'available_points' => $membership->available_points,
                'point_discount_value' => round($membership->available_points * 0.5, 2), // 2 points = 1 Taka
            ]);
        }

        return response()->json(['found' => false]);
    }

    /**
     * Complete POS Sale (Checkout).
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'items' => 'required|array|min:1',
            'grand_total' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        $branchId = $request->branch_id;
        $counterId = $request->counter_id;
        $itemsData = $request->items;
        $grandTotal = (float)$request->grand_total;
        $paidAmount = (float)$request->paid_amount;
        $changeAmount = max(0, $paidAmount - $grandTotal);
        $paymentMethod = $request->payment_method ?: 'cash';

        $branch = Branch::findOrFail($branchId);
        $cashierId = Auth::id() ?: 1;

        // Find or check open shift
        $shift = CashierShift::where('branch_id', $branchId)
            ->where('cashier_id', $cashierId)
            ->where('status', 'open')
            ->first();

        DB::beginTransaction();
        try {
            // Generate Invoice Number: e.g. POS-BR01-2611-4821
            $invoiceNo = 'POS-' . $branch->code . '-' . date('ymd') . '-' . rand(1000, 9999);

            $sale = PosSale::create([
                'invoice_no' => $invoiceNo,
                'branch_id' => $branchId,
                'pos_counter_id' => $counterId,
                'shift_id' => $shift?->id,
                'cashier_id' => $cashierId,
                'customer_name' => $request->customer_name ?: 'Walk-in Customer',
                'customer_phone' => $request->customer_phone,
                'subtotal' => (float)$request->subtotal,
                'discount_amount' => (float)($request->discount_amount ?: 0),
                'tax_amount' => (float)($request->tax_amount ?: 0),
                'grand_total' => $grandTotal,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'payment_method' => $paymentMethod,
                'payment_status' => 'paid',
                'sale_status' => 'completed',
                'notes' => $request->notes,
                'sale_time' => now(),
            ]);

            foreach ($itemsData as $item) {
                $prodId = $item['id'];
                $qty = (int)$item['qty'];
                $unitPrice = (float)$item['price'];
                $costPrice = (float)($item['cost_price'] ?? ($unitPrice * 0.8));
                $lineTotal = $unitPrice * $qty;

                // Create Sale Item
                PosSaleItem::create([
                    'pos_sale_id' => $sale->id,
                    'product_id' => $prodId,
                    'product_name' => $item['name'],
                    'sku' => $item['sku'] ?? null,
                    'barcode' => $item['barcode'] ?? null,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'cost_price' => $costPrice,
                    'total_price' => $lineTotal,
                ]);

                // Deduct Branch Inventory & Central Product Stock
                $inv = BranchInventory::firstOrNew([
                    'branch_id' => $branchId,
                    'product_id' => $prodId,
                ]);
                $masterProd = ShopProduct::find($prodId);
                $oldInvStock = $inv->exists ? (int)$inv->stock_quantity : ($masterProd ? (int)$masterProd->stock_quantity : 0);
                $inv->stock_quantity = max(0, $oldInvStock - $qty);
                $inv->save();

                if ($masterProd) {
                    $masterProd->decrement('stock_quantity', min($masterProd->stock_quantity, $qty));
                }

                // Log Stock Movement Audit
                StockMovement::create([
                    'branch_id' => $branchId,
                    'product_id' => $prodId,
                    'type' => 'pos_sale',
                    'quantity' => -$qty,
                    'balance_after' => $inv->stock_quantity,
                    'reference_no' => $sale->invoice_no,
                    'user_id' => $cashierId,
                    'notes' => "POS কাউন্টারে বিক্রয় (ইনভয়েস: {$sale->invoice_no})",
                ]);
            }

            // Create Payment Record (Supports split payments if provided)
            if ($request->has('split_payments') && is_array($request->split_payments)) {
                foreach ($request->split_payments as $sp) {
                    PosPayment::create([
                        'pos_sale_id' => $sale->id,
                        'payment_method' => $sp['method'],
                        'amount' => (float)$sp['amount'],
                        'transaction_ref' => $sp['ref'] ?? null,
                    ]);
                }
            } else {
                PosPayment::create([
                    'pos_sale_id' => $sale->id,
                    'payment_method' => $paymentMethod,
                    'amount' => $grandTotal,
                    'transaction_ref' => $request->payment_ref,
                ]);
            }

            // Update Shift Statistics
            if ($shift) {
                if ($paymentMethod === 'cash') {
                    $shift->total_cash_sales += $grandTotal;
                    $shift->expected_cash += $grandTotal;
                } else {
                    $shift->total_digital_sales += $grandTotal;
                }
                $shift->total_sales_amount += $grandTotal;
                $shift->total_transactions += 1;
                $shift->save();
            }

            // Loyalty Points Handling
            if ($request->filled('customer_phone')) {
                $phone = trim($request->customer_phone);
                $membership = LoyaltyMembership::firstOrCreate(
                    ['customer_phone' => $phone],
                    [
                        'customer_name' => $request->customer_name ?: 'Valued Customer',
                        'membership_number' => 'SHW-' . substr($phone, -4) . '-' . rand(100, 999),
                        'tier' => 'regular',
                        'total_points' => 0,
                        'redeemed_points' => 0,
                        'available_points' => 0,
                    ]
                );

                // Earn 1 point per ৳100 spent
                $earnedPoints = (int)floor($grandTotal / 100);
                if ($earnedPoints > 0) {
                    $membership->total_points += $earnedPoints;
                    $membership->available_points += $earnedPoints;
                    $membership->save();

                    LoyaltyPointsLedger::create([
                        'membership_id' => $membership->id,
                        'type' => 'earned',
                        'points' => $earnedPoints,
                        'pos_sale_id' => $sale->id,
                        'notes' => "POS বিক্রয় ইনভয়েস #{$sale->invoice_no} হতে অর্জিত পয়েন্ট।",
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "অর্ডার সফলভাবে সম্পন্ন হয়েছে! ইনভয়েস: {$sale->invoice_no}",
                'invoice_no' => $sale->invoice_no,
                'sale_id' => $sale->id,
                'receipt_url' => route('admin.supershop.pos.receipt', $sale->id),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'বিক্রয় সম্পন্ন করতে ত্রুটি হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Thermal Receipt Printable View (58mm / 80mm format).
     */
    public function receipt($id)
    {
        $sale = PosSale::with(['branch', 'cashier', 'items.product', 'payments'])->findOrFail($id);
        return view('admin.supershop.pos.receipt', compact('sale'));
    }
}

