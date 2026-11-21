<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\AgentPoint;
use App\Models\AgentPointDailyClosing;
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
use Illuminate\Support\Str;

class AgentCustomerDistributionController extends Controller
{
    /**
     * Resolve active Agent Point with strict isolation.
     */
    protected function getAgentPoint()
    {
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Unauthorized.');
        }

        $point = AgentPoint::where('agent_user_id', $user->id)->first();
        if (!$point && $user->assigned_agent_point_id) {
            $point = AgentPoint::find($user->assigned_agent_point_id);
        }

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
     * Customer Identification & Live Verification (Section 2 & 2.1).
     * Accepts Card Number, Customer ID, QR Code or Phone.
     */
    public function verifyCustomer(Request $request)
    {
        $point = $this->getAgentPoint();
        $query = trim($request->input('search'));

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'অনুগ্রহ করে কার্ড নম্বর, কাস্টমার আইডি অথবা ফোন নম্বর দিন।'
            ], 422);
        }

        // Search customer strictly by card_number, id, phone or email
        $customer = User::where(function ($q) use ($query) {
            $q->where('card_number', $query)
              ->orWhere('id', $query)
              ->orWhere('customer_id', $query)
              ->orWhere('phone', $query)
              ->orWhere('email', $query);
        })->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'কোনো গ্রাহক/কার্ডহোল্ডার পাওয়া যায়নি।'
            ], 404);
        }

        // Privacy & Assignment Verification: Must belong to this Agent Point
        if ($customer->assigned_agent_point_id != $point->id) {
            $assignedPointName = optional($customer->assignedAgentPoint)->name ?? 'অন্য পয়েন্ট';
            return response()->json([
                'success' => false,
                'message' => "এই কার্ডহোল্ডার আপনার পয়েন্টে নির্ধারিত নন! তার নির্ধারিত পয়েন্ট: [{$assignedPointName}]। শুধুমাত্র নির্ধারিত পয়েন্ট থেকেই বাজার বিতরণ করা যাবে।"
            ], 403);
        }

        // Check Membership Status
        $isActive = ($customer->status === 'active' || $customer->is_active);

        // Current Month Market Collections
        $currentMonth = date('F');
        $currentYear = (int) date('Y');

        $monthlyCollections = CardHolderCollection::where('user_id', $customer->id)
            ->where('market_type', 'monthly_market')
            ->where('market_month', $currentMonth)
            ->where('market_year', $currentYear)
            ->with('items')
            ->get();

        $monthlyStatus = 'Not Collected';
        if ($monthlyCollections->isNotEmpty()) {
            $hasPartial = $monthlyCollections->contains('collection_status', 'partially_collected');
            $hasFull = $monthlyCollections->contains('collection_status', 'fully_collected');
            $monthlyStatus = $hasFull ? 'Fully Collected' : ($hasPartial ? 'Partially Collected' : 'Collected');
        }

        // Fetch any approved MonthlyMarketRequest for this month
        $marketRequest = MonthlyMarketRequest::where('user_id', $customer->id)
            ->where('agent_point_id', $point->id)
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->with('items')
            ->first();

        // Calculate product items breakdown (Approved, Previously Delivered, Remaining)
        $suggestedItems = [];
        if ($marketRequest && $marketRequest->items->isNotEmpty()) {
            foreach ($marketRequest->items as $reqItem) {
                // Previously delivered quantity for this item in this month
                $previouslyDelivered = CardHolderCollectionItem::whereHas('collection', function ($cq) use ($customer, $currentMonth, $currentYear) {
                    $cq->where('user_id', $customer->id)
                       ->where('market_month', $currentMonth)
                       ->where('market_year', $currentYear)
                       ->where('market_type', 'monthly_market');
                })->where('product_name', $reqItem->product_name)->sum('delivered_quantity');

                $approvedQty = (float) ($reqItem->approved_quantity ?: $reqItem->requested_quantity);
                $remainingQty = max(0, $approvedQty - $previouslyDelivered);

                // Check local point stock
                $pointStock = AgentPointInventory::where('agent_point_id', $point->id)
                    ->where('product_name', $reqItem->product_name)
                    ->value('available_stock') ?? 0;

                $suggestedItems[] = [
                    'request_item_id'    => $reqItem->id,
                    'product_name'       => $reqItem->product_name,
                    'category'           => 'General Grocery',
                    'unit'               => $reqItem->unit ?? 'kg',
                    'approved_quantity'  => $approvedQty,
                    'previously_delivered' => (float) $previouslyDelivered,
                    'remaining_quantity' => (float) $remainingQty,
                    'delivered_quantity' => (float) $remainingQty,
                    'available_stock'    => (float) $pointStock,
                    'unit_rate'          => (float) ($reqItem->unit_price ?? 0),
                    'total_amount'       => (float) (($reqItem->unit_price ?? 0) * $remainingQty),
                ];
            }
        }

        // Historical summary
        $totalCollectionsCount = CardHolderCollection::where('user_id', $customer->id)->count();
        $lastCollection = CardHolderCollection::where('user_id', $customer->id)->latest('collection_date')->first();

        return response()->json([
            'success' => true,
            'customer' => [
                'id'              => $customer->id,
                'name'            => $customer->name,
                'card_number'     => $customer->card_number ?? ('CH-' . $customer->id),
                'customer_id'     => $customer->customer_id ?? ('CUST-' . $customer->id),
                'phone'           => $customer->phone ?? 'N/A',
                'photo'           => $customer->avatar ? asset($customer->avatar) : asset('frontend/images/default-avatar.png'),
                'address'         => $customer->present_address ?? $customer->address ?? 'N/A',
                'area'            => $customer->area ?? 'General Area',
                'agent_point_name'=> $point->name,
                'membership_status' => $isActive ? 'Active' : 'Inactive',
                'current_month_status' => $monthlyStatus,
                'total_collections_count' => $totalCollectionsCount,
                'last_collection_date' => $lastCollection ? date('d M, Y', strtotime($lastCollection->collection_date)) : 'None',
            ],
            'market_request' => $marketRequest ? [
                'id' => $marketRequest->id,
                'request_no' => $marketRequest->request_no,
                'status' => $marketRequest->status,
            ] : null,
            'items' => $suggestedItems,
        ]);
    }

    /**
     * Show New Customer Collection & Distribution Form (Section 3).
     */
    public function collectionEntryView(Request $request)
    {
        $point = $this->getAgentPoint();
        $preloadedCard = $request->query('card_number');

        // Available Point Inventories
        $inventories = AgentPointInventory::where('agent_point_id', $point->id)
            ->where('available_stock', '>', 0)
            ->orderBy('product_name')
            ->get();

        return view('agent.distribution.entry', compact('point', 'preloadedCard', 'inventories'));
    }

    /**
     * Store Customer Market Collection Transaction (Section 3 & 4).
     */
    public function storeCollection(Request $request)
    {
        $point = $this->getAgentPoint();

        $request->validate([
            'user_id'             => 'required|exists:users,id',
            'market_type'         => 'required|in:monthly_market,weekly_market,special_market,emergency_market,additional_collection',
            'collection_date'     => 'required|date',
            'collection_time'     => 'nullable',
            'verification_method' => 'required|in:physical_card,qr_code,otp_sms,cardholder_signature',
            'collected_by'        => 'required|string|max:255',
            'collector_phone'     => 'nullable|string|max:50',
            'delivery_type'       => 'required|in:subsidized,free,paid,due',
            'items'               => 'required|array|min:1',
            'items.*.product_name'=> 'required|string|max:255',
            'items.*.unit'        => 'required|string|max:50',
            'items.*.delivered_qty'=> 'required|numeric|min:0.01',
            'items.*.unit_rate'   => 'nullable|numeric|min:0',
        ]);

        $customer = User::findOrFail($request->user_id);

        // Strict Isolation: Customer must belong to this Agent Point
        if ($customer->assigned_agent_point_id != $point->id) {
            return back()->withInput()->with('error', 'এই গ্রাহক আপনার এজেন্ট পয়েন্টের আওতাভুক্ত নন!');
        }

        $currentMonth = date('F', strtotime($request->collection_date));
        $currentYear = (int) date('Y', strtotime($request->collection_date));

        // Duplicate Check for Monthly Market
        if ($request->market_type === 'monthly_market') {
            $alreadyFullyCollected = CardHolderCollection::where('user_id', $customer->id)
                ->where('market_type', 'monthly_market')
                ->where('market_month', $currentMonth)
                ->where('market_year', $currentYear)
                ->where('collection_status', 'fully_collected')
                ->exists();

            if ($alreadyFullyCollected && !$request->has('force_allow_duplicate')) {
                return back()->withInput()->with('error', "এই গ্রাহক {$currentMonth} {$currentYear} এর মাসিক বাজার ইতোমধ্যে সম্পূর্ণ সংগ্রহ করেছেন!");
            }
        }

        DB::beginTransaction();
        try {
            $collectionCount = CardHolderCollection::count() + 1;
            $collectionNo = 'COL-' . date('Ym') . '-' . str_pad($collectionCount, 4, '0', STR_PAD_LEFT);
            $txnId = 'TXN-' . strtoupper(Str::random(10));

            $totalItems = 0;
            $totalQty = 0;
            $grandTotalAmount = 0;

            // Pre-calculate totals
            foreach ($request->items as $item) {
                $qty = (float) ($item['delivered_qty'] ?? 0);
                $rate = (float) ($item['unit_rate'] ?? 0);
                if ($qty > 0) {
                    $totalItems++;
                    $totalQty += $qty;
                    $grandTotalAmount += ($qty * $rate);
                }
            }

            // Create Master Collection Record
            $collection = CardHolderCollection::create([
                'collection_no'       => $collectionNo,
                'transaction_id'      => $txnId,
                'request_id'          => $request->request_id ?? null,
                'user_id'             => $customer->id,
                'card_number'         => $customer->card_number ?? ('CH-' . $customer->id),
                'customer_name'       => $customer->name,
                'customer_phone'      => $customer->phone,
                'agent_point_id'      => $point->id,
                'agent_user_id'       => Auth::id(),
                'market_type'         => $request->market_type,
                'market_month'        => $currentMonth,
                'market_year'         => $currentYear,
                'market_week'         => $request->market_week ?? null,
                'collection_date'     => $request->collection_date,
                'collection_time'     => $request->collection_time ?: date('H:i:s'),
                'total_items_count'   => $totalItems,
                'total_distributed_qty'=> $totalQty,
                'total_amount'        => $grandTotalAmount,
                'paid_amount'         => (float) ($request->paid_amount ?? $grandTotalAmount),
                'due_amount'          => (float) ($request->due_amount ?? 0),
                'delivery_type'       => $request->delivery_type,
                'collection_status'   => $request->is_partial ? 'partially_collected' : 'fully_collected',
                'delivered_by'        => Auth::id(),
                'collected_by'        => $request->collected_by,
                'collector_phone'     => $request->collector_phone ?? $customer->phone,
                'verification_method' => $request->verification_method,
                'status'              => 'completed',
                'delivery_note'       => $request->delivery_note,
                'remarks'             => $request->remarks,
            ]);

            // Save Items & Decrement Inventory
            foreach ($request->items as $itemData) {
                $pName = trim($itemData['product_name']);
                $dQty = (float) ($itemData['delivered_qty'] ?? 0);
                $rate = (float) ($itemData['unit_rate'] ?? 0);
                $lineTotal = $dQty * $rate;
                $remQty = (float) ($itemData['remaining_quantity'] ?? 0);

                if ($dQty > 0) {
                    CardHolderCollectionItem::create([
                        'collection_id'      => $collection->id,
                        'request_item_id'    => $itemData['request_item_id'] ?? null,
                        'product_name'       => $pName,
                        'category'           => $itemData['category'] ?? 'General',
                        'unit'               => $itemData['unit'] ?? 'kg',
                        'requested_quantity' => (float) ($itemData['requested_quantity'] ?? $dQty),
                        'approved_quantity'  => (float) ($itemData['approved_quantity'] ?? $dQty),
                        'delivered_quantity' => $dQty,
                        'remaining_quantity' => $remQty,
                        'unit_rate'          => $rate,
                        'total_amount'       => $lineTotal,
                        'notes'              => $itemData['notes'] ?? null,
                    ]);

                    // Deduct from Agent Point Inventory
                    $inv = AgentPointInventory::where('agent_point_id', $point->id)
                        ->where('product_name', $pName)
                        ->first();

                    if ($inv) {
                        $inv->available_stock = max(0, $inv->available_stock - $dQty);
                        $inv->distributed_stock += $dQty;
                        $inv->save();
                        $balanceAfter = $inv->available_stock;
                    } else {
                        $balanceAfter = 0;
                    }

                    // Stock Ledger Entry
                    AgentPointStockLedger::create([
                        'agent_point_id'   => $point->id,
                        'product_name'     => $pName,
                        'unit'             => $itemData['unit'] ?? 'kg',
                        'transaction_date' => $request->collection_date,
                        'transaction_type' => 'delivered_to_cardholder',
                        'quantity_in'      => 0,
                        'quantity_out'     => $dQty,
                        'balance_after'    => $balanceAfter,
                        'reference_no'     => $collectionNo,
                        'notes'            => "গ্রাহক বিতরণ: {$customer->name} (কার্ড: {$customer->card_number})",
                        'recorded_by'      => Auth::id(),
                    ]);
                }
            }

            // Update linked Market Request if present
            if ($request->request_id) {
                $mr = MonthlyMarketRequest::where('agent_point_id', $point->id)->find($request->request_id);
                if ($mr) {
                    $mr->collection_status = $request->is_partial ? 'partially_collected' : 'collected';
                    if (!$request->is_partial) {
                        $mr->status = 'completed';
                    }
                    $mr->save();
                }
            }

            DB::commit();

            return redirect()->route('agent.distribution.receipt', $collection->id)
                ->with('success', "গ্রাহক [{$customer->name}] এর বাজার সফলভাবে বিতরণ ও সংরক্ষণ সম্পন্ন হয়েছে! রসিদ প্রিন্ট করুন।");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'বিতরণ এন্ট্রি সংরক্ষণে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * Customer Distribution Printable Receipt / Voucher (Section 22).
     */
    public function printReceipt($id)
    {
        $point = $this->getAgentPoint();

        $collection = CardHolderCollection::with(['user', 'items', 'agentPoint', 'deliverer'])
            ->where('agent_point_id', $point->id)
            ->findOrFail($id);

        return view('agent.distribution.receipt', compact('point', 'collection'));
    }

    /**
     * Daily Customer Distribution Register (Section 7).
     */
    public function dailyRegister(Request $request)
    {
        $point = $this->getAgentPoint();
        $date = $request->input('date', date('Y-m-d'));

        $query = CardHolderCollection::with(['user', 'items'])
            ->where('agent_point_id', $point->id)
            ->whereDate('collection_date', $date);

        if ($request->filled('market_type')) {
            $query->where('market_type', $request->market_type);
        }

        $collections = $query->latest('collection_time')->paginate(25)->withQueryString();

        // Today's summary totals
        $todayServedCount = CardHolderCollection::where('agent_point_id', $point->id)->whereDate('collection_date', $date)->count();
        $todayMonthlyCount = CardHolderCollection::where('agent_point_id', $point->id)->whereDate('collection_date', $date)->where('market_type', 'monthly_market')->count();
        $todayWeeklyCount = CardHolderCollection::where('agent_point_id', $point->id)->whereDate('collection_date', $date)->where('market_type', 'weekly_market')->count();
        $todayTotalQty = CardHolderCollection::where('agent_point_id', $point->id)->whereDate('collection_date', $date)->sum('total_distributed_qty');
        $todayTotalValue = CardHolderCollection::where('agent_point_id', $point->id)->whereDate('collection_date', $date)->sum('total_amount');

        return view('agent.distribution.daily_register', compact(
            'point',
            'date',
            'collections',
            'todayServedCount',
            'todayMonthlyCount',
            'todayWeeklyCount',
            'todayTotalQty',
            'todayTotalValue'
        ));
    }

    /**
     * Product-wise Distribution Report (Section 9).
     */
    public function productDistributionReport(Request $request)
    {
        $point = $this->getAgentPoint();
        $fromDate = $request->input('from_date', date('Y-m-d'));
        $toDate = $request->input('to_date', date('Y-m-d'));

        // Query distributed products in the date range
        $distributedItems = CardHolderCollectionItem::whereHas('collection', function ($q) use ($point, $fromDate, $toDate) {
            $q->where('agent_point_id', $point->id)
              ->whereBetween('collection_date', [$fromDate, $toDate]);
        })
        ->select(
            'product_name',
            'unit',
            DB::raw('SUM(delivered_quantity) as total_distributed_quantity'),
            DB::raw('SUM(total_amount) as total_distributed_amount'),
            DB::raw('COUNT(DISTINCT collection_id) as total_customers_count')
        )
        ->groupBy('product_name', 'unit')
        ->orderByDesc('total_distributed_quantity')
        ->get();

        // Get current point stock map
        $stockMap = AgentPointInventory::where('agent_point_id', $point->id)
            ->pluck('available_stock', 'product_name')
            ->toArray();

        $totalVolume = $distributedItems->sum('total_distributed_quantity');
        $totalValue = $distributedItems->sum('total_distributed_amount');

        return view('agent.distribution.product_report', compact(
            'point',
            'fromDate',
            'toDate',
            'distributedItems',
            'stockMap',
            'totalVolume',
            'totalValue'
        ));
    }

    /**
     * Customer List strictly assigned to this Agent Point (Section 11).
     */
    public function customerList(Request $request)
    {
        $point = $this->getAgentPoint();

        $query = User::where('assigned_agent_point_id', $point->id)
            ->whereIn('role', ['customer', 'user']);

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('card_number', 'like', "%{$s}%")
                  ->orWhere('customer_id', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('area', 'like', "%{$s}%");
            });
        }

        $customers = $query->latest()->paginate(20)->withQueryString();

        // Calculate this month's status for each customer
        $currentMonth = date('F');
        $currentYear = (int) date('Y');

        $collectedUserIds = CardHolderCollection::where('agent_point_id', $point->id)
            ->where('market_month', $currentMonth)
            ->where('market_year', $currentYear)
            ->where('market_type', 'monthly_market')
            ->pluck('user_id')
            ->toArray();

        return view('agent.distribution.customers', compact('point', 'customers', 'collectedUserIds', 'currentMonth', 'currentYear'));
    }

    /**
     * Customer-wise Market Collection History (Section 10).
     */
    public function customerHistory($user_id)
    {
        $point = $this->getAgentPoint();

        $customer = User::where('assigned_agent_point_id', $point->id)->findOrFail($user_id);

        $collections = CardHolderCollection::with('items')
            ->where('agent_point_id', $point->id)
            ->where('user_id', $customer->id)
            ->latest('collection_date')
            ->paginate(15);

        $totalMonthly = CardHolderCollection::where('agent_point_id', $point->id)->where('user_id', $customer->id)->where('market_type', 'monthly_market')->count();
        $totalWeekly = CardHolderCollection::where('agent_point_id', $point->id)->where('user_id', $customer->id)->where('market_type', 'weekly_market')->count();
        $totalAmount = CardHolderCollection::where('agent_point_id', $point->id)->where('user_id', $customer->id)->sum('total_amount');
        $totalQty = CardHolderCollection::where('agent_point_id', $point->id)->where('user_id', $customer->id)->sum('total_distributed_qty');

        return view('agent.distribution.customer_history', compact(
            'point',
            'customer',
            'collections',
            'totalMonthly',
            'totalWeekly',
            'totalAmount',
            'totalQty'
        ));
    }

    /**
     * Agent Point Daily Closing View (Section 21).
     */
    public function dailyClosing(Request $request)
    {
        $point = $this->getAgentPoint();
        $date = $request->input('date', date('Y-m-d'));

        // Today's summary stats
        $customersServedToday = CardHolderCollection::where('agent_point_id', $point->id)->whereDate('collection_date', $date)->count();
        $monthlyCount = CardHolderCollection::where('agent_point_id', $point->id)->whereDate('collection_date', $date)->where('market_type', 'monthly_market')->count();
        $weeklyCount = CardHolderCollection::where('agent_point_id', $point->id)->whereDate('collection_date', $date)->where('market_type', 'weekly_market')->count();
        $productDistributed = CardHolderCollection::where('agent_point_id', $point->id)->whereDate('collection_date', $date)->sum('total_distributed_qty');
        $distributionAmount = CardHolderCollection::where('agent_point_id', $point->id)->whereDate('collection_date', $date)->sum('total_amount');

        $openingStock = AgentPointInventory::where('agent_point_id', $point->id)->sum('opening_stock');
        $closingStock = AgentPointInventory::where('agent_point_id', $point->id)->sum('available_stock');
        $receivedToday = AgentPointInventory::where('agent_point_id', $point->id)->sum('received_stock');

        $existingClosing = AgentPointDailyClosing::where('agent_point_id', $point->id)
            ->whereDate('closing_date', $date)
            ->first();

        $previousClosings = AgentPointDailyClosing::where('agent_point_id', $point->id)
            ->latest('closing_date')
            ->take(10)
            ->get();

        return view('agent.distribution.daily_closing', compact(
            'point',
            'date',
            'customersServedToday',
            'monthlyCount',
            'weeklyCount',
            'productDistributed',
            'distributionAmount',
            'openingStock',
            'closingStock',
            'receivedToday',
            'existingClosing',
            'previousClosings'
        ));
    }

    /**
     * Submit Daily Closing (Section 21).
     */
    public function storeDailyClosing(Request $request)
    {
        $point = $this->getAgentPoint();

        $request->validate([
            'closing_date' => 'required|date',
            'agent_note'   => 'nullable|string',
        ]);

        $date = $request->closing_date;

        $customersServedToday = CardHolderCollection::where('agent_point_id', $point->id)->whereDate('collection_date', $date)->count();
        $monthlyCount = CardHolderCollection::where('agent_point_id', $point->id)->whereDate('collection_date', $date)->where('market_type', 'monthly_market')->count();
        $weeklyCount = CardHolderCollection::where('agent_point_id', $point->id)->whereDate('collection_date', $date)->where('market_type', 'weekly_market')->count();
        $productDistributed = CardHolderCollection::where('agent_point_id', $point->id)->whereDate('collection_date', $date)->sum('total_distributed_qty');
        $distributionAmount = CardHolderCollection::where('agent_point_id', $point->id)->whereDate('collection_date', $date)->sum('total_amount');

        $openingStock = AgentPointInventory::where('agent_point_id', $point->id)->sum('opening_stock');
        $closingStock = AgentPointInventory::where('agent_point_id', $point->id)->sum('available_stock');
        $receivedToday = AgentPointInventory::where('agent_point_id', $point->id)->sum('received_stock');

        AgentPointDailyClosing::updateOrCreate(
            [
                'agent_point_id' => $point->id,
                'closing_date'   => $date,
            ],
            [
                'agent_user_id'              => Auth::id(),
                'opening_stock'              => $openingStock,
                'total_received_today'       => $receivedToday,
                'total_customers_served'     => $customersServedToday,
                'total_monthly_market_count' => $monthlyCount,
                'total_weekly_market_count'  => $weeklyCount,
                'total_product_distributed'  => $productDistributed,
                'total_distribution_amount'  => $distributionAmount,
                'closing_stock'              => $closingStock,
                'agent_note'                 => $request->agent_note,
                'status'                     => 'submitted',
            ]
        );

        return back()->with('success', "তারিখ [{$date}] এর দৈনিক ক্লোজিং সফলভাবে সাবমিট করা হয়েছে!");
    }
}

