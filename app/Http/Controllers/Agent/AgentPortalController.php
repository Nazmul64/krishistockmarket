<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\AgentPoint;
use App\Models\AgentPointDispatch;
use App\Models\AgentPointDispatchItem;
use App\Models\AgentPointInventory;
use App\Models\AgentPointStockLedger;
use App\Models\CardHolderCollection;
use App\Models\CardHolderCollectionItem;
use App\Models\MonthlyMarketRequest;
use App\Models\MonthlyMarketRequestItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AgentPortalController extends Controller
{
    /**
     * Resolve the active Agent Point for the authenticated Agent user.
     * Enforces strict data isolation.
     */
    protected function getAgentPoint()
    {
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Unauthorized.');
        }

        // Try direct point ownership
        $point = AgentPoint::where('agent_user_id', $user->id)->first();

        // Fallback: assigned agent point
        if (!$point && $user->assigned_agent_point_id) {
            $point = AgentPoint::find($user->assigned_agent_point_id);
        }

        // If still null, create default point for this agent to prevent breaking
        if (!$point) {
            $point = AgentPoint::create([
                'code'           => 'AG-' . str_pad(AgentPoint::count() + 1, 3, '0', STR_PAD_LEFT),
                'name'           => $user->name . ' - Agent Hub',
                'agent_name'     => $user->name,
                'agent_user_id'  => $user->id,
                'area'           => $user->area ?? 'General Area',
                'district'       => $user->district ?? 'Central',
                'contact_number' => $user->phone ?? '01700000000',
                'status'         => 'active',
                'capacity'       => 500,
                'opening_date'   => now()->toDateString(),
            ]);

            $user->assigned_agent_point_id = $point->id;
            $user->save();
        }

        return $point;
    }

    /**
     * Agent Dashboard Overview with 9 Core KPIs matching SRD Section 2.
     */
    public function dashboard()
    {
        $point = $this->getAgentPoint();

        // 1. Total Assigned Card Holders
        $totalCardHolders = User::where('assigned_agent_point_id', $point->id)->count();

        // 2. Total Monthly Orders
        $totalOrders = MonthlyMarketRequest::where('agent_point_id', $point->id)->count();

        // 3. Total Pending Orders
        $pendingOrders = MonthlyMarketRequest::where('agent_point_id', $point->id)
            ->whereIn('status', ['submitted', 'pending'])
            ->count();

        // 4. Total Approved Orders
        $approvedOrders = MonthlyMarketRequest::where('agent_point_id', $point->id)
            ->whereIn('status', ['approved', 'processing'])
            ->count();

        // 5. Total Received Stock (Units / Kg)
        $totalReceivedStock = AgentPointInventory::where('agent_point_id', $point->id)->sum('received_stock');

        // 6. Total Available Stock
        $availableStock = AgentPointInventory::where('agent_point_id', $point->id)->sum('available_stock');

        // 7. Total Distributed Stock
        $distributedStock = AgentPointInventory::where('agent_point_id', $point->id)->sum('distributed_stock');

        // 8. Remaining Stock
        $remainingStock = $availableStock;

        // 9. Pending Delivery (Orders ready for collection but not yet delivered)
        $pendingDelivery = MonthlyMarketRequest::where('agent_point_id', $point->id)
            ->where('status', 'ready_for_collection')
            ->count();

        // Recent Orders
        $recentOrders = MonthlyMarketRequest::with(['user', 'items'])
            ->where('agent_point_id', $point->id)
            ->latest()
            ->take(8)
            ->get();

        // Recent Stock Dispatches / Receives
        $recentDispatches = AgentPointDispatch::with(['supplier', 'items'])
            ->where('agent_point_id', $point->id)
            ->latest()
            ->take(8)
            ->get();

        // Local Inventory items
        $inventories = AgentPointInventory::where('agent_point_id', $point->id)
            ->orderBy('product_name')
            ->take(10)
            ->get();

        return view('agent.dashboard', compact(
            'point',
            'totalCardHolders',
            'totalOrders',
            'pendingOrders',
            'approvedOrders',
            'totalReceivedStock',
            'availableStock',
            'distributedStock',
            'remainingStock',
            'pendingDelivery',
            'recentOrders',
            'recentDispatches',
            'inventories'
        ));
    }

    /**
     * View Incoming Product Dispatches for this Agent Point.
     */
    public function incomingStock(Request $request)
    {
        $point = $this->getAgentPoint();

        $query = AgentPointDispatch::with(['supplier', 'items'])
            ->where('agent_point_id', $point->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $dispatches = $query->latest()->paginate(15)->withQueryString();

        return view('agent.stock.incoming', compact('point', 'dispatches'));
    }

    /**
     * Agent Confirms Receipt of Goods from Dispatch.
     * Automatically increments Agent Point stock and creates stock ledger entry.
     */
    public function confirmReceive(Request $request, $dispatch_id)
    {
        $point = $this->getAgentPoint();

        $dispatch = AgentPointDispatch::with('items')
            ->where('agent_point_id', $point->id)
            ->findOrFail($dispatch_id);

        $request->validate([
            'receive_date'       => 'required|date',
            'received_by'        => 'required|string|max:255',
            'notes'              => 'nullable|string',
            'receive_attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'items'              => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $attachmentPath = null;
            if ($request->hasFile('receive_attachment')) {
                $attachmentPath = $request->file('receive_attachment')->store('dispatch_receives', 'public');
            }

            $allReceived = true;
            $hasShortage = false;

            foreach ($dispatch->items as $dispatchItem) {
                $input = $request->items[$dispatchItem->id] ?? null;
                if ($input) {
                    $receivedQty = (float) ($input['received_quantity'] ?? 0);
                    $damagedQty  = (float) ($input['damaged_quantity'] ?? 0);
                    $shortQty    = (float) ($input['short_quantity'] ?? 0);
                    $itemNotes   = $input['notes'] ?? null;

                    // If short qty not explicitly sent, calculate: sent - (received + damaged)
                    if ($shortQty <= 0 && ($dispatchItem->sent_quantity > ($receivedQty + $damagedQty))) {
                        $shortQty = $dispatchItem->sent_quantity - ($receivedQty + $damagedQty);
                    }

                    if ($shortQty > 0 || $damagedQty > 0) {
                        $hasShortage = true;
                    }

                    $dispatchItem->update([
                        'received_quantity' => $receivedQty,
                        'damaged_quantity'  => $damagedQty,
                        'short_quantity'    => $shortQty,
                        'notes'             => $itemNotes,
                    ]);

                    // Update Agent Point Inventory (Local Stock Increment)
                    $inventory = AgentPointInventory::firstOrCreate(
                        [
                            'agent_point_id' => $point->id,
                            'product_name'   => $dispatchItem->product_name,
                        ],
                        [
                            'unit'              => $dispatchItem->unit,
                            'opening_stock'     => 0,
                            'received_stock'    => 0,
                            'distributed_stock' => 0,
                            'damaged_stock'     => 0,
                            'available_stock'   => 0,
                        ]
                    );

                    $inventory->received_stock  += $receivedQty;
                    $inventory->available_stock += $receivedQty;
                    $inventory->damaged_stock   += $damagedQty;
                    $inventory->save();

                    // Record in Agent Stock Ledger
                    $txType = ($dispatch->supply_flow === 'direct_agent_supply')
                        ? 'received_from_supplier'
                        : 'received_from_warehouse';

                    AgentPointStockLedger::create([
                        'agent_point_id'   => $point->id,
                        'product_name'     => $dispatchItem->product_name,
                        'unit'             => $dispatchItem->unit,
                        'transaction_date' => $request->receive_date,
                        'transaction_type' => $txType,
                        'quantity_in'      => $receivedQty,
                        'quantity_out'     => 0,
                        'balance_after'    => $inventory->available_stock,
                        'reference_no'     => $dispatch->dispatch_no,
                        'notes'            => "চালান রিসিভ কনফার্মেশন: {$receivedQty} {$dispatchItem->unit} গ্রহণ করা হয়েছে।" . ($damagedQty > 0 ? " (ত্রুটি/নষ্ট: {$damagedQty})" : ""),
                        'recorded_by'      => Auth::id(),
                    ]);
                }
            }

            // Update Dispatch Status
            $dispatchStatus = $hasShortage ? 'partially_received' : 'received';
            $dispatch->update([
                'status'             => $dispatchStatus,
                'received_date'      => $request->receive_date,
                'received_by'        => Auth::id(),
                'receive_note'       => $request->notes,
                'receive_attachment' => $attachmentPath ?? $dispatch->receive_attachment,
            ]);

            DB::commit();

            return redirect()->route('agent.stock.incoming')
                ->with('success', "চালান নং [{$dispatch->dispatch_no}] সফলভাবে গ্রহণ করা হয়েছে এবং লোকাল স্টকে যোগ হয়েছে!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'পণ্য গ্রহণ কনফার্মেশনে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * View Agent Point Live Inventory Stock.
     */
    public function inventory(Request $request)
    {
        $point = $this->getAgentPoint();

        $query = AgentPointInventory::where('agent_point_id', $point->id);

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where('product_name', 'like', "%{$s}%");
        }

        $inventories = $query->orderBy('product_name')->paginate(20)->withQueryString();

        $totalAvailable = AgentPointInventory::where('agent_point_id', $point->id)->sum('available_stock');
        $totalDistributed = AgentPointInventory::where('agent_point_id', $point->id)->sum('distributed_stock');
        $totalDamaged = AgentPointInventory::where('agent_point_id', $point->id)->sum('damaged_stock');

        return view('agent.inventory.index', compact(
            'point',
            'inventories',
            'totalAvailable',
            'totalDistributed',
            'totalDamaged'
        ));
    }

    /**
     * View Chronological Stock Ledger & Transaction History for this Point.
     */
    public function stockLedger(Request $request)
    {
        $point = $this->getAgentPoint();

        $query = AgentPointStockLedger::where('agent_point_id', $point->id);

        if ($request->filled('product_name')) {
            $query->where('product_name', 'like', "%{$request->product_name}%");
        }

        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('transaction_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('transaction_date', '<=', $request->to_date);
        }

        $ledgers = $query->latest('transaction_date')->latest('id')->paginate(25)->withQueryString();

        $products = AgentPointInventory::where('agent_point_id', $point->id)
            ->pluck('product_name');

        return view('agent.inventory.ledger', compact('point', 'ledgers', 'products'));
    }

    /**
     * View Cardholder Monthly Market Orders mapped to this Agent Point.
     */
    public function orders(Request $request)
    {
        $point = $this->getAgentPoint();

        $query = MonthlyMarketRequest::with(['user', 'items'])
            ->where('agent_point_id', $point->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('request_no', 'like', "%{$s}%")
                  ->orWhere('card_number', 'like', "%{$s}%")
                  ->orWhereHas('user', function ($uq) use ($s) {
                      $uq->where('name', 'like', "%{$s}%")
                         ->orWhere('phone', 'like', "%{$s}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        // Get local inventory stock map to check stock availability for each item
        $stockMap = AgentPointInventory::where('agent_point_id', $point->id)
            ->pluck('available_stock', 'product_name')
            ->toArray();

        return view('agent.orders.index', compact('point', 'orders', 'stockMap'));
    }

    /**
     * Process/Approve Cardholder Order & Set Status (e.g. Ready for Collection).
     */
    public function processOrder(Request $request, $order_id)
    {
        $point = $this->getAgentPoint();

        $order = MonthlyMarketRequest::where('agent_point_id', $point->id)
            ->findOrFail($order_id);

        $request->validate([
            'status'                 => 'required|in:approved,processing,ready_for_collection,cancelled',
            'collection_instruction' => 'nullable|string',
            'admin_note'             => 'nullable|string',
        ]);

        $order->status = $request->status;
        if ($request->filled('collection_instruction')) {
            $order->collection_instruction = $request->collection_instruction;
        } elseif ($request->status === 'ready_for_collection') {
            $order->collection_instruction = "আপনার মাসিক বাজার প্রস্তুত রয়েছে। অনুগ্রহ করে {$point->name} পয়েন্ট থেকে কার্ড নিয়ে আপনার পণ্য সংগ্রহ করুন।";
        }

        if ($request->filled('admin_note')) {
            $order->admin_note = $request->admin_note;
        }

        if ($request->status === 'approved' && !$order->approved_at) {
            $order->approved_at = now();
            $order->approved_by = Auth::user()->name;
        }

        $order->save();

        return back()->with('success', "অর্ডার নং [{$order->request_no}] এর স্ট্যাটাস '{$request->status}' হিসেবে আপডেট করা হয়েছে!");
    }

    /**
     * Confirm Product Delivery to Cardholder.
     * Decrements Agent Point inventory, logs stock out in ledger, updates order.
     */
    public function confirmDelivery(Request $request, $order_id)
    {
        $point = $this->getAgentPoint();

        $order = MonthlyMarketRequest::with('items')
            ->where('agent_point_id', $point->id)
            ->findOrFail($order_id);

        $request->validate([
            'verification_method' => 'required|string',
            'collected_by'        => 'required|string|max:255',
            'remarks'             => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Deduct stock for each order item
            foreach ($order->items as $item) {
                $productName = $item->product_name;
                $quantity = (float) $item->quantity;

                $inventory = AgentPointInventory::where('agent_point_id', $point->id)
                    ->where('product_name', $productName)
                    ->first();

                if ($inventory) {
                    $inventory->available_stock = max(0, $inventory->available_stock - $quantity);
                    $inventory->distributed_stock += $quantity;
                    $inventory->save();

                    $balanceAfter = $inventory->available_stock;
                } else {
                    $balanceAfter = 0;
                }

                // Log into Agent Point Stock Ledger
                AgentPointStockLedger::create([
                    'agent_point_id'   => $point->id,
                    'product_name'     => $productName,
                    'unit'             => $item->unit ?? 'kg',
                    'transaction_date' => now()->toDateString(),
                    'transaction_type' => 'delivered_to_cardholder',
                    'quantity_in'      => 0,
                    'quantity_out'     => $quantity,
                    'balance_after'    => $balanceAfter,
                    'reference_no'     => $order->request_no,
                    'notes'            => "কার্ডহোল্ডার ডেলিভারি: {$order->card_number} ({$request->collected_by})",
                    'recorded_by'      => Auth::id(),
                ]);
            }

            // Create CardHolderCollection history
            $collection = CardHolderCollection::create([
                'collection_no'       => 'COL-' . date('Ym') . '-' . str_pad(CardHolderCollection::count() + 1, 4, '0', STR_PAD_LEFT),
                'market_request_id'   => $order->id,
                'user_id'             => $order->user_id,
                'card_number'         => $order->card_number,
                'agent_point_id'      => $point->id,
                'collection_date'     => now()->toDateString(),
                'collected_by'        => $request->collected_by,
                'collector_phone'     => $order->user->phone ?? null,
                'verification_method' => $request->verification_method,
                'status'              => 'completed',
                'remarks'             => $request->remarks,
            ]);

            foreach ($order->items as $item) {
                CardHolderCollectionItem::create([
                    'collection_id'   => $collection->id,
                    'product_name'    => $item->product_name,
                    'unit'            => $item->unit ?? 'kg',
                    'requested_qty'   => $item->quantity,
                    'delivered_qty'   => $item->quantity,
                    'remaining_qty'   => 0,
                ]);
            }

            // Complete Order
            $order->status = 'completed';
            $order->collection_status = 'collected';
            $order->save();

            DB::commit();

            return redirect()->route('agent.orders')
                ->with('success', "অর্ডার নং [{$order->request_no}] সফলভাবে কার্ডহোল্ডারকে ডেলিভারি করা হয়েছে এবং স্টক সমন্বয় সম্পন্ন হয়েছে!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'ডেলিভারি প্রক্রিয়াকরণে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }
}
