<?php

namespace App\Http\Controllers\Admin\ERP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ERP\ErpWarehouse;
use App\Models\ERP\ErpItemCategory;
use App\Models\ERP\ErpItemSubcategory;
use App\Models\ERP\ErpItemUnit;
use App\Models\ERP\ErpItem;
use App\Models\ERP\ErpStockTransaction;
use App\Models\ERP\ErpStockRequisition;
use App\Models\ERP\ErpStockRequisitionItem;
use App\Models\ERP\ErpStockTransfer;
use App\Models\ERP\ErpStockTransferItem;
use App\Models\ERP\ErpStockAdjustment;

class ErpStoreController extends Controller
{
    // ==========================================
    // 1. ITEMS & PRODUCTS INVENTORY
    // ==========================================
    public function itemsIndex(Request $request)
    {
        $query = ErpItem::with(['category', 'unit', 'defaultWarehouse']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('warehouse_id')) {
            $query->where('default_warehouse_id', $request->warehouse_id);
        }
        if ($request->filled('tracking_type')) {
            $query->where('tracking_type', $request->tracking_type);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('name_bn', 'like', "%$s%")
                  ->orWhere('code', 'like', "%$s%")
                  ->orWhere('sku', 'like', "%$s%");
            });
        }

        $items = $query->latest()->paginate(15);
        $categories = ErpItemCategory::where('is_active', true)->get();
        $warehouses = ErpWarehouse::where('is_active', true)->get();
        $units = ErpItemUnit::where('is_active', true)->get();

        $totalItemsCount = ErpItem::count();
        $totalStockQty = ErpItem::sum('current_stock');
        $totalValuation = ErpItem::selectRaw('SUM(current_stock * purchase_price) as val')->value('val') ?? 0;
        $lowStockCount = ErpItem::whereColumn('current_stock', '<=', 'reorder_level')->count();

        return view('admin.erp.store.items', compact(
            'items',
            'categories',
            'warehouses',
            'units',
            'totalItemsCount',
            'totalStockQty',
            'totalValuation',
            'lowStockCount'
        ));
    }

    public function storeItem(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:erp_item_categories,id',
            'unit_id' => 'required|exists:erp_item_units,id',
            'purchase_price' => 'required|numeric|min:0',
            'current_stock' => 'nullable|numeric|min:0',
        ]);

        $code = $request->code;
        if (empty($code)) {
            $lastId = ErpItem::max('id') ?? 0;
            $code = 'ITM-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
        }

        $item = new ErpItem();
        $item->name = $request->name;
        $item->name_bn = $request->name_bn;
        $item->code = $code;
        $item->sku = $request->sku ?? $code;
        $item->barcode = $request->barcode;
        $item->category_id = $request->category_id;
        $item->unit_id = $request->unit_id;
        $item->default_warehouse_id = $request->default_warehouse_id;
        $item->brand = $request->brand;
        $item->model = $request->model;
        $item->tracking_type = $request->tracking_type ?? 'quantity';
        $item->purchase_price = $request->purchase_price ?? 0;
        $item->selling_price = $request->selling_price ?? 0;
        $item->current_stock = $request->current_stock ?? 0;
        $item->min_stock = $request->min_stock ?? 5;
        $item->max_stock = $request->max_stock ?? 500;
        $item->reorder_level = $request->reorder_level ?? 10;
        $item->supplier_name = $request->supplier_name;
        $item->warranty_period = $request->warranty_period;
        $item->description = $request->description;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'item_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/erp/items'), $filename);
            $item->image = 'upload/erp/items/' . $filename;
        }

        $item->save();

        // If initial stock > 0, record Opening Stock Transaction
        if ($item->current_stock > 0) {
            ErpStockTransaction::create([
                'transaction_number' => 'STX-OP-' . time() . '-' . $item->id,
                'transaction_type' => 'stock_in',
                'item_id' => $item->id,
                'warehouse_id' => $item->default_warehouse_id,
                'quantity' => $item->current_stock,
                'unit_price' => $item->purchase_price,
                'total_amount' => $item->current_stock * $item->purchase_price,
                'reference_no' => 'OPENING-STOCK',
                'purpose' => 'প্রারম্ভিক স্টক এন্ট্রি (Opening Stock Entry)',
                'created_by' => auth()->id(),
            ]);
        }

        return redirect()->back()->with('success', 'আইটেম সফলভাবে যোগ করা হয়েছে।');
    }

    public function updateItem(Request $request, $id)
    {
        $item = ErpItem::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:erp_item_categories,id',
            'unit_id' => 'required|exists:erp_item_units,id',
        ]);

        $item->name = $request->name;
        $item->name_bn = $request->name_bn;
        $item->category_id = $request->category_id;
        $item->unit_id = $request->unit_id;
        $item->default_warehouse_id = $request->default_warehouse_id;
        $item->brand = $request->brand;
        $item->model = $request->model;
        $item->tracking_type = $request->tracking_type ?? $item->tracking_type;
        $item->purchase_price = $request->purchase_price ?? $item->purchase_price;
        $item->selling_price = $request->selling_price ?? $item->selling_price;
        $item->min_stock = $request->min_stock ?? $item->min_stock;
        $item->reorder_level = $request->reorder_level ?? $item->reorder_level;
        $item->supplier_name = $request->supplier_name;
        $item->warranty_period = $request->warranty_period;
        $item->description = $request->description;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'item_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/erp/items'), $filename);
            $item->image = 'upload/erp/items/' . $filename;
        }

        $item->save();

        return redirect()->back()->with('success', 'আইটেম তথ্য আপডেট করা হয়েছে।');
    }

    // ==========================================
    // 2. STOCK REQUISITIONS & ISSUE WORKFLOW
    // ==========================================
    public function requisitionsIndex(Request $request)
    {
        $query = ErpStockRequisition::with(['items.item', 'warehouse']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requisitions = $query->latest()->paginate(15);
        $warehouses = ErpWarehouse::where('is_active', true)->get();
        $items = ErpItem::where('is_active', true)->get();

        return view('admin.erp.store.requisitions', compact('requisitions', 'warehouses', 'items'));
    }

    public function storeRequisition(Request $request)
    {
        $request->validate([
            'department_name' => 'required|string',
            'requesting_employee_name' => 'required|string',
            'item_ids' => 'required|array',
            'quantities' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $reqNumber = 'REQ-' . date('Ymd') . '-' . rand(100, 999);

            $requisition = ErpStockRequisition::create([
                'requisition_number' => $reqNumber,
                'department_name' => $request->department_name,
                'requesting_employee_name' => $request->requesting_employee_name,
                'warehouse_id' => $request->warehouse_id,
                'request_date' => $request->request_date ?? now()->toDateString(),
                'required_date' => $request->required_date,
                'purpose' => $request->purpose,
                'priority' => $request->priority ?? 'medium',
                'status' => 'pending',
            ]);

            foreach ($request->item_ids as $index => $itemId) {
                $qty = $request->quantities[$index] ?? 1;
                ErpStockRequisitionItem::create([
                    'requisition_id' => $requisition->id,
                    'item_id' => $itemId,
                    'requested_qty' => $qty,
                    'approved_qty' => $qty,
                    'issued_qty' => 0,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'স্টক রিকুইজিশন সফলভাবে দাখিল করা হয়েছে (নম্বর: ' . $reqNumber . ')');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'রিকুইজিশন তৈরি করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    public function updateRequisitionStatus(Request $request, $id)
    {
        $requisition = ErpStockRequisition::with('items.item')->findOrFail($id);
        $action = $request->action; // approve, issue, reject

        if ($action === 'approve') {
            $requisition->status = 'approved';
            $requisition->approved_by = auth()->id();
            $requisition->approved_at = now();
            $requisition->admin_remarks = $request->admin_remarks;
            $requisition->save();
            return redirect()->back()->with('success', 'রিকুইজিশন অনুমোদন করা হয়েছে।');
        }

        if ($action === 'issue') {
            DB::beginTransaction();
            try {
                // Verify stock availability and deduct
                foreach ($requisition->items as $reqItem) {
                    $item = $reqItem->item;
                    $issueQty = $reqItem->approved_qty > 0 ? $reqItem->approved_qty : $reqItem->requested_qty;

                    if ($item->current_stock < $issueQty) {
                        DB::rollBack();
                        return redirect()->back()->with('error', "পর্যাপ্ত স্টক নেই: {$item->name} (মজুত: {$item->current_stock}, প্রয়োজন: {$issueQty})");
                    }

                    // Deduct stock
                    $item->decrement('current_stock', $issueQty);
                    $reqItem->update(['issued_qty' => $issueQty]);

                    // Record Stock Out Transaction
                    ErpStockTransaction::create([
                        'transaction_number' => 'STX-OUT-' . time() . '-' . $item->id,
                        'transaction_type' => 'stock_out',
                        'item_id' => $item->id,
                        'warehouse_id' => $requisition->warehouse_id,
                        'quantity' => $issueQty,
                        'unit_price' => $item->purchase_price,
                        'total_amount' => $issueQty * $item->purchase_price,
                        'reference_no' => $requisition->requisition_number,
                        'reference_type' => 'requisition_issue',
                        'purpose' => "রিকুইজিশন বিতরণ: {$requisition->department_name} ({$requisition->requesting_employee_name})",
                        'notes' => $requisition->purpose,
                        'created_by' => auth()->id(),
                    ]);
                }

                $requisition->status = 'issued';
                $requisition->save();

                DB::commit();
                return redirect()->back()->with('success', 'স্টোর থেকে সফলভাবে মালামাল বিতরণ (Stock Issue) সম্পন্ন হয়েছে।');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'বিতরণ করতে সমস্যা হয়েছে: ' . $e->getMessage());
            }
        }

        if ($action === 'reject') {
            $requisition->status = 'rejected';
            $requisition->admin_remarks = $request->admin_remarks;
            $requisition->save();
            return redirect()->back()->with('success', 'রিকুইজিশন বাতিল করা হয়েছে।');
        }

        return redirect()->back();
    }

    // ==========================================
    // 3. STOCK IN & STOCK OUT TRANSACTIONS
    // ==========================================
    public function stockInOutIndex(Request $request)
    {
        $query = ErpStockTransaction::with(['item', 'warehouse']);

        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }
        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }

        $transactions = $query->latest()->paginate(20);
        $items = ErpItem::where('is_active', true)->get();
        $warehouses = ErpWarehouse::where('is_active', true)->get();

        return view('admin.erp.store.stock_in_out', compact('transactions', 'items', 'warehouses'));
    }

    public function storeStockTransaction(Request $request)
    {
        $request->validate([
            'transaction_type' => 'required|in:stock_in,stock_out',
            'item_id' => 'required|exists:erp_items,id',
            'warehouse_id' => 'required|exists:erp_warehouses,id',
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $item = ErpItem::findOrFail($request->item_id);
        $qty = $request->quantity;
        $unitPrice = $request->unit_price ?? $item->purchase_price;

        if ($request->transaction_type === 'stock_out' && $item->current_stock < $qty) {
            return redirect()->back()->with('error', "পর্যাপ্ত স্টক নেই! বর্তমান স্টক: {$item->current_stock}");
        }

        DB::beginTransaction();
        try {
            $trxNo = 'STX-' . strtoupper($request->transaction_type) . '-' . time();

            ErpStockTransaction::create([
                'transaction_number' => $trxNo,
                'transaction_type' => $request->transaction_type,
                'item_id' => $item->id,
                'warehouse_id' => $request->warehouse_id,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'total_amount' => $qty * $unitPrice,
                'reference_no' => $request->reference_no,
                'purpose' => $request->purpose,
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            if ($request->transaction_type === 'stock_in') {
                $item->increment('current_stock', $qty);
            } else {
                $item->decrement('current_stock', $qty);
            }

            DB::commit();
            return redirect()->back()->with('success', 'স্টক ট্রানজ্যাকশন সফলভাবে রেকর্ড করা হয়েছে।');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'সমস্যা: ' . $e->getMessage());
        }
    }

    // ==========================================
    // 4. TRANSFERS & STOCK ADJUSTMENTS
    // ==========================================
    public function transfersAdjustmentsIndex()
    {
        $transfers = ErpStockTransfer::with(['fromWarehouse', 'toWarehouse', 'items.item'])->latest()->paginate(10);
        $adjustments = ErpStockAdjustment::with(['warehouse', 'item'])->latest()->paginate(10);
        $warehouses = ErpWarehouse::where('is_active', true)->get();
        $items = ErpItem::where('is_active', true)->get();

        return view('admin.erp.store.transfers_adjustments', compact('transfers', 'adjustments', 'warehouses', 'items'));
    }

    public function storeTransfer(Request $request)
    {
        $request->validate([
            'from_warehouse_id' => 'required|exists:erp_warehouses,id|different:to_warehouse_id',
            'to_warehouse_id' => 'required|exists:erp_warehouses,id',
            'item_id' => 'required|exists:erp_items,id',
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $item = ErpItem::findOrFail($request->item_id);
        if ($item->current_stock < $request->quantity) {
            return redirect()->back()->with('error', "উৎস গুদামে পর্যাপ্ত স্টক নেই! বর্তমান স্টক: {$item->current_stock}");
        }

        DB::beginTransaction();
        try {
            $transferNo = 'TRF-' . date('Ymd') . '-' . rand(100, 999);

            $transfer = ErpStockTransfer::create([
                'transfer_number' => $transferNo,
                'from_warehouse_id' => $request->from_warehouse_id,
                'to_warehouse_id' => $request->to_warehouse_id,
                'transfer_date' => $request->transfer_date ?? now()->toDateString(),
                'status' => 'completed',
                'notes' => $request->notes,
                'dispatched_by' => auth()->id(),
                'received_by' => auth()->id(),
            ]);

            ErpStockTransferItem::create([
                'transfer_id' => $transfer->id,
                'item_id' => $item->id,
                'quantity' => $request->quantity,
                'received_qty' => $request->quantity,
            ]);

            ErpStockTransaction::create([
                'transaction_number' => 'STX-TRF-' . time(),
                'transaction_type' => 'transfer',
                'item_id' => $item->id,
                'from_warehouse_id' => $request->from_warehouse_id,
                'to_warehouse_id' => $request->to_warehouse_id,
                'quantity' => $request->quantity,
                'unit_price' => $item->purchase_price,
                'total_amount' => $request->quantity * $item->purchase_price,
                'reference_no' => $transferNo,
                'purpose' => 'গুদাম স্থানান্তর (Warehouse Transfer)',
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'স্টক স্থানান্তর সফলভাবে সম্পন্ন হয়েছে।');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'স্থানান্তর ব্যর্থ: ' . $e->getMessage());
        }
    }

    public function storeAdjustment(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:erp_warehouses,id',
            'item_id' => 'required|exists:erp_items,id',
            'adjusted_qty' => 'required|numeric|min:0',
            'reason' => 'required|string',
        ]);

        $item = ErpItem::findOrFail($request->item_id);
        $previousQty = $item->current_stock;
        $adjustedQty = $request->adjusted_qty;
        $diffQty = $adjustedQty - $previousQty;
        $type = $diffQty >= 0 ? 'increase' : 'decrease';

        DB::beginTransaction();
        try {
            $adjNo = 'ADJ-' . date('Ymd') . '-' . rand(100, 999);

            ErpStockAdjustment::create([
                'adjustment_number' => $adjNo,
                'warehouse_id' => $request->warehouse_id,
                'item_id' => $item->id,
                'type' => $type,
                'previous_qty' => $previousQty,
                'adjusted_qty' => $adjustedQty,
                'diff_qty' => abs($diffQty),
                'reason' => $request->reason,
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'remarks' => $request->remarks,
                'created_by' => auth()->id(),
            ]);

            // Update item stock directly
            $item->current_stock = $adjustedQty;
            $item->save();

            // Record transaction
            ErpStockTransaction::create([
                'transaction_number' => 'STX-ADJ-' . time(),
                'transaction_type' => 'adjustment',
                'item_id' => $item->id,
                'warehouse_id' => $request->warehouse_id,
                'quantity' => abs($diffQty),
                'unit_price' => $item->purchase_price,
                'total_amount' => abs($diffQty) * $item->purchase_price,
                'reference_no' => $adjNo,
                'purpose' => "স্টক সমন্বয় ({$request->reason}): পূর্বের {$previousQty} -> নতুন {$adjustedQty}",
                'notes' => $request->remarks,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', "স্টক সমন্বয় সফলভাবে অনুমোদিত ও কার্যকর হয়েছে (পার্থক্য: {$diffQty})।");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'সমন্বয় ব্যর্থ: ' . $e->getMessage());
        }
    }
}

