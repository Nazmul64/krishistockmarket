<?php

namespace App\Http\Controllers\Admin\SuperShop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuperShop\Branch;
use App\Models\SuperShop\StockTransfer;
use App\Models\SuperShop\StockTransferItem;
use App\Models\SuperShop\BranchInventory;
use App\Models\SuperShop\StockMovement;
use App\Models\Shop\ShopProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockTransferController extends Controller
{
    /**
     * Display Stock Transfers list.
     */
    public function index(Request $request)
    {
        $branches = Branch::where('status', 'active')->get();
        $products = ShopProduct::where('is_active', true)->select('id', 'name', 'sku')->get();

        $transfers = StockTransfer::with(['sourceBranch', 'destinationBranch', 'items.product', 'requester', 'approver'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('admin.supershop.transfers.index', compact('transfers', 'branches', 'products'));
    }

    /**
     * Store new Branch-to-Branch Transfer Request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'source_branch_id' => 'required|different:destination_branch_id|exists:branches,id',
            'destination_branch_id' => 'required|exists:branches,id',
            'product_id' => 'required|array|min:1',
            'requested_qty' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $trNumber = 'TR-' . date('ymd') . '-' . rand(1000, 9999);

            $transfer = StockTransfer::create([
                'transfer_number' => $trNumber,
                'source_branch_id' => $request->source_branch_id,
                'destination_branch_id' => $request->destination_branch_id,
                'request_date' => now()->toDateString(),
                'status' => 'pending_approval',
                'requested_by' => Auth::id(),
                'notes' => $request->notes,
            ]);

            foreach ($request->product_id as $index => $prodId) {
                $qty = max(1, (int)($request->requested_qty[$index] ?? 1));

                StockTransferItem::create([
                    'transfer_id' => $transfer->id,
                    'product_id' => $prodId,
                    'requested_qty' => $qty,
                    'dispatched_qty' => $qty,
                ]);
            }

            DB::commit();
            return back()->with('success', "স্টক ট্রান্সফার রিকুয়েস্ট #{$trNumber} সফলভাবে জমা দেওয়া হয়েছে!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'ট্রান্সফার রিকুয়েস্ট করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * Approve & Dispatch Transfer (Deducts stock from Source Branch).
     */
    public function dispatchTransfer($id)
    {
        $transfer = StockTransfer::with('items')->findOrFail($id);

        if ($transfer->status !== 'pending_approval' && $transfer->status !== 'approved') {
            return back()->with('error', 'এই ট্রান্সফারটি বর্তমানে ডিসপ্যাচ করার উপযুক্ত অবস্থায় নেই।');
        }

        DB::beginTransaction();
        try {
            foreach ($transfer->items as $item) {
                $sourceInv = BranchInventory::where('branch_id', $transfer->source_branch_id)
                    ->where('product_id', $item->product_id)
                    ->lockForUpdate()
                    ->first();

                if (!$sourceInv || $sourceInv->stock_quantity < $item->dispatched_qty) {
                    throw new \Exception("উৎস শাখায় পর্যাপ্ত স্টক নেই! (পণ্য আইডি: {$item->product_id})");
                }

                $sourceInv->stock_quantity -= $item->dispatched_qty;
                $sourceInv->save();

                // Log movement
                StockMovement::create([
                    'branch_id' => $transfer->source_branch_id,
                    'product_id' => $item->product_id,
                    'type' => 'transfer_out',
                    'quantity' => -$item->dispatched_qty,
                    'balance_after' => $sourceInv->stock_quantity,
                    'reference_no' => $transfer->transfer_number,
                    'user_id' => Auth::id(),
                    'notes' => "শাখা ট্রান্সফার আউট (গন্তব্য: {$transfer->destinationBranch->name})",
                ]);
            }

            $transfer->update([
                'status' => 'in_transit',
                'dispatch_date' => now()->toDateString(),
                'dispatched_by' => Auth::id(),
                'approved_by' => Auth::id(),
            ]);

            DB::commit();
            return back()->with('success', "ট্রান্সফার #{$transfer->transfer_number} সফলভাবে ডিসপ্যাচ করা হয়েছে এবং স্টক পাঠানো হয়েছে (In Transit)!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'ডিসপ্যাচ করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * Receive Transfer (Adds stock to Destination Branch).
     */
    public function receiveTransfer(Request $request, $id)
    {
        $transfer = StockTransfer::with('items')->findOrFail($id);

        if ($transfer->status !== 'in_transit') {
            return back()->with('error', 'শুধুমাত্র ইন-ট্রানজিট অবস্থায় থাকা পণ্য রিসিভ করা যাবে।');
        }

        DB::beginTransaction();
        try {
            foreach ($transfer->items as $item) {
                $destInv = BranchInventory::firstOrCreate(
                    [
                        'branch_id' => $transfer->destination_branch_id,
                        'product_id' => $item->product_id,
                    ],
                    [
                        'stock_quantity' => 0,
                        'reorder_level' => 10,
                        'safety_stock' => 5,
                        'is_active' => true,
                    ]
                );

                $destInv->stock_quantity += $item->dispatched_qty;
                $destInv->save();

                $item->received_qty = $item->dispatched_qty;
                $item->save();

                // Log movement
                StockMovement::create([
                    'branch_id' => $transfer->destination_branch_id,
                    'product_id' => $item->product_id,
                    'type' => 'transfer_in',
                    'quantity' => $item->dispatched_qty,
                    'balance_after' => $destInv->stock_quantity,
                    'reference_no' => $transfer->transfer_number,
                    'user_id' => Auth::id(),
                    'notes' => "শাখা ট্রান্সফার ইন বুঝে পাওয়া গেছে (উৎস: {$transfer->sourceBranch->name})",
                ]);
            }

            $transfer->update([
                'status' => 'received',
                'received_date' => now()->toDateString(),
                'received_by' => Auth::id(),
            ]);

            DB::commit();
            return back()->with('success', "ট্রান্সফার #{$transfer->transfer_number} সফলভাবে রিসিভ করা হয়েছে এবং গন্তব্য শাখার স্টকে যোগ হয়েছে!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'পণ্য রিসিভ করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }
}

