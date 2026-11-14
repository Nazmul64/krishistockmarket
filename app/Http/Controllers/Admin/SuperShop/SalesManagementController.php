<?php

namespace App\Http\Controllers\Admin\SuperShop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuperShop\Branch;
use App\Models\SuperShop\PosSale;
use App\Models\SuperShop\PosSaleItem;
use App\Models\SuperShop\PosSalesReturn;
use App\Models\SuperShop\PosSalesReturnItem;
use App\Models\SuperShop\BranchInventory;
use App\Models\SuperShop\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalesManagementController extends Controller
{
    /**
     * All POS Sales history.
     */
    public function index(Request $request)
    {
        $branches = Branch::where('status', 'active')->get();
        $selectedBranchId = $request->get('branch_id');
        $search = trim($request->get('search', ''));
        $paymentMethod = $request->get('payment_method');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        $query = PosSale::with(['branch', 'cashier', 'items.product', 'payments'])
            ->orderBy('id', 'desc');

        if ($selectedBranchId) {
            $query->where('branch_id', $selectedBranchId);
        }

        if ($paymentMethod) {
            $query->where('payment_method', $paymentMethod);
        }

        if ($fromDate) {
            $query->whereDate('sale_time', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('sale_time', '<=', $toDate);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        $sales = $query->paginate(20)->withQueryString();

        $totalSalesAmount = (float)$query->sum('grand_total');
        $totalSalesCount = $query->count();

        return view('admin.supershop.sales.index', compact(
            'sales',
            'branches',
            'selectedBranchId',
            'search',
            'paymentMethod',
            'fromDate',
            'toDate',
            'totalSalesAmount',
            'totalSalesCount'
        ));
    }

    /**
     * Show Sale Invoice Details.
     */
    public function show($id)
    {
        $sale = PosSale::with(['branch', 'cashier', 'items.product', 'payments', 'returns.items'])->findOrFail($id);
        return view('admin.supershop.sales.show', compact('sale'));
    }

    /**
     * Process Sales Return & Refund.
     */
    public function returnStore(Request $request)
    {
        $request->validate([
            'pos_sale_id' => 'required|exists:pos_sales,id',
            'item_id' => 'required|array|min:1',
            'return_qty' => 'required|array|min:1',
            'reason' => 'required|string',
        ]);

        $sale = PosSale::with('items')->findOrFail($request->pos_sale_id);

        DB::beginTransaction();
        try {
            $returnNo = 'RET-' . date('ymd') . '-' . rand(1000, 9999);
            $totalRefund = 0;

            $salesReturn = PosSalesReturn::create([
                'return_no' => $returnNo,
                'pos_sale_id' => $sale->id,
                'branch_id' => $sale->branch_id,
                'cashier_id' => Auth::id() ?: 1,
                'total_refund_amount' => 0,
                'refund_method' => $request->refund_method ?: 'cash',
                'reason' => $request->reason,
                'status' => 'approved',
                'approved_by' => Auth::id(),
            ]);

            foreach ($request->item_id as $index => $itemId) {
                $qty = (int)($request->return_qty[$index] ?? 0);
                if ($qty <= 0) continue;

                $saleItem = PosSaleItem::where('pos_sale_id', $sale->id)->where('id', $itemId)->first();
                if (!$saleItem) continue;

                $itemRefund = $saleItem->unit_price * $qty;
                $totalRefund += $itemRefund;

                $condition = $request->condition[$index] ?? 'sellable';
                $restored = ($condition === 'sellable');

                PosSalesReturnItem::create([
                    'sales_return_id' => $salesReturn->id,
                    'product_id' => $saleItem->product_id,
                    'quantity' => $qty,
                    'unit_price' => $saleItem->unit_price,
                    'refund_amount' => $itemRefund,
                    'condition' => $condition,
                    'restored_to_stock' => $restored,
                ]);

                // If sellable, restore stock to Branch Inventory
                if ($restored) {
                    $inv = BranchInventory::where('branch_id', $sale->branch_id)
                        ->where('product_id', $saleItem->product_id)
                        ->first();

                    if ($inv) {
                        $inv->stock_quantity += $qty;
                        $inv->save();

                        StockMovement::create([
                            'branch_id' => $sale->branch_id,
                            'product_id' => $saleItem->product_id,
                            'type' => 'return_in',
                            'quantity' => $qty,
                            'balance_after' => $inv->stock_quantity,
                            'reference_no' => $returnNo,
                            'user_id' => Auth::id(),
                            'notes' => "কাস্টমার রিটার্ন ইন (ইনভয়েস #{$sale->invoice_no})",
                        ]);
                    }
                }
            }

            $salesReturn->total_refund_amount = $totalRefund;
            $salesReturn->save();

            DB::commit();
            return back()->with('success', "রিটার্ন #{$returnNo} সফলভাবে গৃহীত হয়েছে এবং ৳{$totalRefund} রিফান্ড করা হয়েছে!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'রিটার্ন প্রসেস করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }
}

