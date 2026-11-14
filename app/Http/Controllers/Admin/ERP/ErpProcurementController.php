<?php

namespace App\Http\Controllers\Admin\ERP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ERP\ErpSupplier;
use App\Models\ERP\ErpPurchaseOrder;
use App\Models\ERP\ErpPurchaseOrderItem;
use App\Models\ERP\ErpGoodsReceivedNote;
use App\Models\ERP\ErpWarehouse;
use App\Models\ERP\ErpItem;
use App\Models\ERP\ErpStockTransaction;

class ErpProcurementController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = ErpSupplier::latest()->paginate(10);
        $purchaseOrders = ErpPurchaseOrder::with(['supplier', 'items.item'])->latest()->paginate(10);
        $grns = ErpGoodsReceivedNote::with(['purchaseOrder', 'supplier', 'warehouse'])->latest()->paginate(10);

        $warehouses = ErpWarehouse::where('is_active', true)->get();
        $items = ErpItem::where('is_active', true)->get();

        $totalSuppliers = ErpSupplier::count();
        $totalPayables = ErpSupplier::sum('current_balance');
        $totalOrders = ErpPurchaseOrder::count();

        return view('admin.erp.procurement.index', compact(
            'suppliers',
            'purchaseOrders',
            'grns',
            'warehouses',
            'items',
            'totalSuppliers',
            'totalPayables',
            'totalOrders'
        ));
    }

    public function storeSupplier(Request $request)
    {
        $request->validate([
            'supplier_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
        ]);

        $code = $request->supplier_code;
        if (empty($code)) {
            $code = 'SUP-' . rand(1000, 9999);
        }

        ErpSupplier::create([
            'supplier_name' => $request->supplier_name,
            'supplier_code' => $code,
            'contact_person' => $request->contact_person,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'tax_tin' => $request->tax_tin,
            'opening_balance' => $request->opening_balance ?? 0,
            'current_balance' => $request->opening_balance ?? 0,
            'payment_terms' => $request->payment_terms ?? 'Net 30',
        ]);

        return redirect()->back()->with('success', 'সরবরাহকারী (Supplier) সফলভাবে যোগ করা হয়েছে।');
    }

    public function storePurchaseOrder(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:erp_suppliers,id',
            'po_date' => 'required|date',
            'item_ids' => 'required|array',
            'quantities' => 'required|array',
            'unit_prices' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $poNo = 'PO-' . date('Ymd') . '-' . rand(100, 999);
            $subtotal = 0;

            foreach ($request->item_ids as $index => $itemId) {
                $qty = $request->quantities[$index] ?? 1;
                $price = $request->unit_prices[$index] ?? 0;
                $subtotal += ($qty * $price);
            }

            $discount = $request->discount ?? 0;
            $vatTax = $request->vat_tax ?? 0;
            $shipping = $request->shipping_cost ?? 0;
            $total = ($subtotal - $discount) + $vatTax + $shipping;

            $po = ErpPurchaseOrder::create([
                'po_number' => $poNo,
                'supplier_id' => $request->supplier_id,
                'po_date' => $request->po_date,
                'delivery_due_date' => $request->delivery_due_date,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'vat_tax' => $vatTax,
                'shipping_cost' => $shipping,
                'total_amount' => $total,
                'payment_terms' => $request->payment_terms ?? 'Net 30',
                'status' => 'approved',
                'terms_conditions' => $request->terms_conditions,
                'approved_by' => auth()->id(),
            ]);

            foreach ($request->item_ids as $index => $itemId) {
                $qty = $request->quantities[$index] ?? 1;
                $price = $request->unit_prices[$index] ?? 0;
                ErpPurchaseOrderItem::create([
                    'po_id' => $po->id,
                    'item_id' => $itemId,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'total_price' => $qty * $price,
                    'received_qty' => 0,
                ]);
            }

            // Update supplier balance
            $supplier = ErpSupplier::find($request->supplier_id);
            if ($supplier) {
                $supplier->increment('current_balance', $total);
            }

            DB::commit();
            return redirect()->back()->with('success', "পারচেজ অর্ডার (PO: {$poNo}) সফলভাবে তৈরি ও অনুমোদিত হয়েছে।");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'PO তৈরিতে ব্যর্থ: ' . $e->getMessage());
        }
    }

    public function storeGrn(Request $request)
    {
        $request->validate([
            'po_id' => 'required|exists:erp_purchase_orders,id',
            'warehouse_id' => 'required|exists:erp_warehouses,id',
            'received_date' => 'required|date',
        ]);

        $po = ErpPurchaseOrder::with(['items.item', 'supplier'])->findOrFail($request->po_id);

        DB::beginTransaction();
        try {
            $grnNo = 'GRN-' . date('Ymd') . '-' . rand(100, 999);

            $grn = ErpGoodsReceivedNote::create([
                'grn_number' => $grnNo,
                'po_id' => $po->id,
                'supplier_id' => $po->supplier_id,
                'warehouse_id' => $request->warehouse_id,
                'challan_number' => $request->challan_number,
                'received_date' => $request->received_date,
                'quality_status' => $request->quality_status ?? 'inspected_passed',
                'inspection_notes' => $request->inspection_notes,
                'received_by' => auth()->id(),
            ]);

            // If quality passed, auto stock in the items!
            if ($grn->quality_status === 'inspected_passed') {
                foreach ($po->items as $poItem) {
                    $item = $poItem->item;
                    $item->increment('current_stock', $poItem->quantity);
                    $poItem->update(['received_qty' => $poItem->quantity]);

                    // Record Stock In Transaction
                    ErpStockTransaction::create([
                        'transaction_number' => 'STX-GRN-' . time() . '-' . $item->id,
                        'transaction_type' => 'purchase_receive',
                        'item_id' => $item->id,
                        'warehouse_id' => $request->warehouse_id,
                        'quantity' => $poItem->quantity,
                        'unit_price' => $poItem->unit_price,
                        'total_amount' => $poItem->quantity * $poItem->unit_price,
                        'reference_no' => $grnNo,
                        'reference_type' => 'goods_received_note',
                        'purpose' => "ক্রয়কৃত মালামাল রিসিভ (PO: {$po->po_number})",
                        'notes' => "চালান নং: {$request->challan_number}",
                        'created_by' => auth()->id(),
                    ]);
                }
                $po->update(['status' => 'received']);
            }

            DB::commit();
            return redirect()->back()->with('success', "গুডস রিসিভ নোট (GRN: {$grnNo}) সফলভাবে রেকর্ড এবং ইনভেন্টরিতে স্টক যুক্ত হয়েছে।");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'GRN তৈরিতে ব্যর্থ: ' . $e->getMessage());
        }
    }
}

