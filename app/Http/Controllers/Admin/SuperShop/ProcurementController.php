<?php

namespace App\Http\Controllers\Admin\SuperShop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuperShop\Supplier;
use App\Models\SuperShop\PurchaseOrder;
use App\Models\SuperShop\PurchaseOrderItem;
use App\Models\SuperShop\GoodsReceiptNote;
use App\Models\SuperShop\GoodsReceiptNoteItem;
use App\Models\SuperShop\Branch;
use App\Models\SuperShop\BranchInventory;
use App\Models\SuperShop\StockMovement;
use App\Models\Shop\ShopProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProcurementController extends Controller
{
    /**
     * Display Procurement Dashboard (Suppliers, POs, GRNs).
     */
    public function index(Request $request)
    {
        $suppliers = Supplier::withCount('purchaseOrders')->orderBy('name', 'asc')->get();
        $branches = Branch::where('status', 'active')->get();
        $products = ShopProduct::where('is_active', true)->select('id', 'name', 'sku', 'price')->get();

        $purchaseOrders = PurchaseOrder::with(['supplier', 'items.product', 'creator'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        $grns = GoodsReceiptNote::with(['supplier', 'purchaseOrder', 'items.product', 'receiver'])
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        return view('admin.supershop.procurement.index', compact('suppliers', 'branches', 'products', 'purchaseOrders', 'grns'));
    }

    /**
     * Store new Supplier.
     */
    public function supplierStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'phone' => 'required|string|max:30',
        ]);

        Supplier::create([
            'name' => $request->name,
            'company_name' => $request->company_name,
            'contact_person' => $request->contact_person,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'tax_number' => $request->tax_number,
            'trade_license' => $request->trade_license,
            'payment_terms' => $request->payment_terms ?: '30 Days Credit',
            'status' => 'active',
        ]);

        return back()->with('success', 'নতুন সাপ্লায়ার সফলভাবে যুক্ত করা হয়েছে!');
    }

    /**
     * Store new Purchase Order (PO).
     */
    public function poStore(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'destination_id' => 'required|exists:branches,id',
            'order_date' => 'required|date',
            'product_id' => 'required|array|min:1',
            'quantity' => 'required|array|min:1',
            'purchase_price' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $poNumber = 'PO-' . date('ymd') . '-' . rand(1000, 9999);
            $subtotal = 0;

            $po = PurchaseOrder::create([
                'po_number' => $poNumber,
                'supplier_id' => $request->supplier_id,
                'destination_type' => 'branch',
                'destination_id' => $request->destination_id,
                'order_date' => $request->order_date,
                'expected_delivery_date' => $request->expected_delivery_date,
                'notes' => $request->notes,
                'created_by' => Auth::id(),
                'status' => 'approved',
            ]);

            foreach ($request->product_id as $index => $prodId) {
                $qty = (int)($request->quantity[$index] ?? 1);
                $price = (float)($request->purchase_price[$index] ?? 0);
                $lineTotal = $qty * $price;
                $subtotal += $lineTotal;

                $product = ShopProduct::find($prodId);

                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $prodId,
                    'sku' => $product?->sku,
                    'quantity' => $qty,
                    'unit' => 'pcs',
                    'purchase_price' => $price,
                    'total_price' => $lineTotal,
                ]);
            }

            $po->subtotal = $subtotal;
            $po->total_amount = $subtotal;
            $po->save();

            DB::commit();
            return back()->with('success', "পারচেজ অর্ডার (PO #{$poNumber}) সফলভাবে তৈরি করা হয়েছে!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'পারচেজ অর্ডার তৈরিতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * Create & Approve Goods Receipt Note (GRN) - Increments Branch Stock.
     */
    public function grnStore(Request $request)
    {
        $request->validate([
            'po_id' => 'required|exists:purchase_orders,id',
            'received_qty' => 'required|array',
        ]);

        $po = PurchaseOrder::with('items')->findOrFail($request->po_id);

        DB::beginTransaction();
        try {
            $grnNumber = 'GRN-' . date('ymd') . '-' . rand(1000, 9999);

            $grn = GoodsReceiptNote::create([
                'grn_number' => $grnNumber,
                'purchase_order_id' => $po->id,
                'supplier_id' => $po->supplier_id,
                'destination_type' => $po->destination_type,
                'destination_id' => $po->destination_id,
                'received_date' => now()->toDateString(),
                'received_by' => Auth::id(),
                'status' => 'approved',
                'notes' => $request->notes ?: 'পণ্য বুঝে পেয়ে স্টক ইনভেন্টরিতে বৃদ্ধি করা হলো।',
            ]);

            foreach ($po->items as $item) {
                $recQty = (int)($request->received_qty[$item->id] ?? $item->quantity);
                $damagedQty = (int)($request->damaged_qty[$item->id] ?? 0);
                $acceptedQty = max(0, $recQty - $damagedQty);

                GoodsReceiptNoteItem::create([
                    'grn_id' => $grn->id,
                    'product_id' => $item->product_id,
                    'ordered_qty' => $item->quantity,
                    'received_qty' => $recQty,
                    'accepted_qty' => $acceptedQty,
                    'damaged_qty' => $damagedQty,
                    'batch_number' => 'BAT-' . date('ymd') . '-' . rand(10, 99),
                ]);

                // Increment Branch Stock
                $inv = BranchInventory::firstOrCreate(
                    [
                        'branch_id' => $po->destination_id,
                        'product_id' => $item->product_id,
                    ],
                    [
                        'stock_quantity' => 0,
                        'reorder_level' => 10,
                        'safety_stock' => 5,
                        'purchase_price' => $item->purchase_price,
                        'is_active' => true,
                    ]
                );

                $inv->stock_quantity += $acceptedQty;
                $inv->purchase_price = $item->purchase_price;
                $inv->save();

                // Audit Movement Log
                StockMovement::create([
                    'branch_id' => $po->destination_id,
                    'product_id' => $item->product_id,
                    'type' => 'purchase_in',
                    'quantity' => $acceptedQty,
                    'balance_after' => $inv->stock_quantity,
                    'reference_no' => $grnNumber,
                    'user_id' => Auth::id(),
                    'notes' => "GRN #{$grnNumber} এর মাধ্যমে স্টক ইন। (PO #{$po->po_number})",
                ]);
            }

            $po->status = 'received';
            $po->save();

            DB::commit();
            return back()->with('success', "GRN #{$grnNumber} সফলভাবে গৃহীত হয়েছে এবং সংশ্লিষ্ট শাখার স্টকে যুক্ত হয়েছে!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'GRN তৈরিতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }
}

