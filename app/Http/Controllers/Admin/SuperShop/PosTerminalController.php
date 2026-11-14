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
        $selectedBranchId = $request->get('branch_id', $branches->first()?->id);
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

        // Initial products available in this branch
        $initialProducts = BranchInventory::with('product')
            ->where('branch_id', $selectedBranchId)
            ->where('stock_quantity', '>', 0)
            ->take(24)
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
        $branchId = $request->get('branch_id');

        if (!$code || !$branchId) {
            return response()->json(['success' => false, 'message' => 'বারকোড বা শাখা আইডি দেওয়া হয়নি।'], 400);
        }

        // Look for matching product by SKU, barcode or ID
        $product = ShopProduct::where('sku', $code)
            ->orWhere('id', $code)
            ->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'বারকোড অনুযায়ী কোনো পণ্য পাওয়া যায়নি!'], 404);
        }

        $inv = BranchInventory::where('branch_id', $branchId)
            ->where('product_id', $product->id)
            ->first();

        $stock = $inv ? $inv->stock_quantity : 0;
        $price = $inv && $inv->selling_price ? (float)$inv->selling_price : (float)$product->price;

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'barcode' => $product->sku,
                'price' => $price,
                'cost_price' => $inv ? (float)$inv->purchase_price : round($price * 0.8, 2),
                'stock' => $stock,
                'image' => $product->image ? asset($product->image) : asset('assets/images/placeholder.png'),
            ]
        ]);
    }

    /**
     * Live Product Search in POS Terminal.
     */
    public function searchProducts(Request $request)
    {
        $q = trim($request->get('q', ''));
        $branchId = $request->get('branch_id');
        $catId = $request->get('category_id');

        $query = BranchInventory::with(['product.category'])
            ->where('branch_id', $branchId);

        if ($catId) {
            $query->whereHas('product', fn($pq) => $pq->where('category_id', $catId));
        }

        if ($q !== '') {
            $query->whereHas('product', function ($pq) use ($q) {
                $pq->where('name', 'like', "%{$q}%")
                   ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        $items = $query->take(20)->get();

        return response()->json([
            'success' => true,
            'products' => $items->map(function ($inv) {
                return [
                    'id' => $inv->product->id,
                    'name' => $inv->product->name,
                    'sku' => $inv->product->sku,
                    'barcode' => $inv->product->sku,
                    'price' => $inv->selling_price ? (float)$inv->selling_price : (float)$inv->product->price,
                    'cost_price' => (float)($inv->purchase_price ?: ($inv->product->price * 0.8)),
                    'stock' => $inv->stock_quantity,
                    'shelf' => $inv->shelf_location,
                    'image' => $inv->product->image ? asset($inv->product->image) : asset('assets/images/placeholder.png'),
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

                // Deduct Branch Inventory
                $inv = BranchInventory::where('branch_id', $branchId)
                    ->where('product_id', $prodId)
                    ->lockForUpdate()
                    ->first();

                if ($inv) {
                    $inv->stock_quantity = max(0, $inv->stock_quantity - $qty);
                    $inv->save();

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

