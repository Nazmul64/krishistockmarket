<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentPoint;
use App\Models\AgentPointAllocation;
use App\Models\AgentPointDispatch;
use App\Models\AgentPointDispatchItem;
use App\Models\CardHolderCollection;
use App\Models\CardHolderCollectionItem;
use App\Models\MonthlyBazaarItem;
use App\Models\MonthlyMarketRequest;
use App\Models\MonthlyMarketRequestItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminMonthlyMarketRequestController extends Controller
{
    // ==========================================
    // 1. MONTHLY MARKET REQUESTS (Section 13, 16, 17)
    // ==========================================
    public function index(Request $request)
    {
        $query = MonthlyMarketRequest::with(['user', 'agentPoint', 'items']);

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        if ($request->filled('request_area')) {
            $query->where('request_area', $request->request_area);
        }
        if ($request->filled('agent_point_id')) {
            $query->where('agent_point_id', $request->agent_point_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->latest()->paginate(25);

        // Stats
        $allRequests = MonthlyMarketRequest::all();
        $totalRequestsCount = $allRequests->count();
        $pendingCount = $allRequests->where('status', 'pending')->count();
        $approvedCount = $allRequests->where('status', 'approved')->count();
        $distributedCount = $allRequests->whereIn('status', ['distributed', 'completed'])->count();

        $areas = MonthlyMarketRequest::whereNotNull('request_area')->distinct()->pluck('request_area');
        $agentPoints = AgentPoint::where('status', 'active')->get();

        return view('admin.monthly_market.requests_index', compact(
            'requests',
            'totalRequestsCount',
            'pendingCount',
            'approvedCount',
            'distributedCount',
            'areas',
            'agentPoints'
        ));
    }

    public function show($id)
    {
        $marketRequest = MonthlyMarketRequest::with(['user', 'agentPoint', 'items'])->findOrFail($id);
        return view('admin.monthly_market.request_details', compact('marketRequest'));
    }

    public function approve($id)
    {
        $marketRequest = MonthlyMarketRequest::with('items')->findOrFail($id);
        
        DB::transaction(function () use ($marketRequest) {
            $marketRequest->update([
                'status' => 'approved',
                'collection_status' => 'pending_collection',
                'approved_by' => Auth::id(),
                'approved_at' => Carbon::now(),
            ]);

            // Set approved_quantity = requested_quantity for all items if not set
            foreach ($marketRequest->items as $item) {
                if ($item->approved_quantity <= 0) {
                    $item->update([
                        'approved_quantity' => $item->requested_quantity,
                        'remaining_quantity' => $item->requested_quantity,
                    ]);
                }
            }
        });

        return redirect()->back()->with('success', 'মাসিক বাজার চাহিদা রিকোয়েস্ট সফলভাবে অনুমোদন করা হয়েছে!');
    }

    public function reject(Request $request, $id)
    {
        $marketRequest = MonthlyMarketRequest::findOrFail($id);
        $marketRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason ?? 'প্রয়োজনীয় শর্তাবলী পূরণ না হওয়ায় প্রত্যাখান করা হয়েছে',
            'approved_by' => Auth::id(),
            'approved_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('error', 'মাসিক বাজার চাহিদা রিকোয়েস্ট প্রত্যাখান করা হয়েছে!');
    }

    public function updateItems(Request $request, $id)
    {
        $marketRequest = MonthlyMarketRequest::findOrFail($id);

        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $itemId => $itemData) {
                $item = MonthlyMarketRequestItem::where('request_id', $marketRequest->id)->find($itemId);
                if ($item) {
                    $appQty = floatval($itemData['approved_quantity'] ?? $item->approved_quantity);
                    $item->update([
                        'approved_quantity' => $appQty,
                        'allocated_quantity' => floatval($itemData['allocated_quantity'] ?? $item->allocated_quantity),
                        'remaining_quantity' => $appQty - $item->collected_quantity,
                        'note' => $itemData['note'] ?? $item->note,
                    ]);
                }
            }
        }

        if ($request->filled('collection_instruction')) {
            $marketRequest->update([
                'collection_instruction' => $request->collection_instruction,
                'status' => $request->status ?? $marketRequest->status,
                'collection_status' => $request->collection_status ?? $marketRequest->collection_status,
            ]);
        }

        return redirect()->back()->with('success', 'রিকোয়েস্ট আইটেমস ও সংগ্রহের নির্দেশনা সফলভাবে আপডেট হয়েছে!');
    }

    // ==========================================
    // 2. CENTRAL PRODUCT DEMAND ANALYSIS (Section 18 & 19)
    // ==========================================
    public function demandAnalysis(Request $request)
    {
        $selectedMonth = $request->input('month', Carbon::now()->format('F'));
        $selectedYear = $request->input('year', Carbon::now()->format('Y'));

        $requestsQuery = MonthlyMarketRequest::where('month', $selectedMonth)
            ->where('year', $selectedYear);

        if ($request->filled('request_area')) {
            $requestsQuery->where('request_area', $request->request_area);
        }

        $allRequests = $requestsQuery->with('items')->get();
        $totalCardHolders = $allRequests->pluck('user_id')->unique()->count();
        $totalSubmittedRequests = $allRequests->count();
        $approvedDemandCount = $allRequests->where('status', 'approved')->count();
        $pendingDemandCount = $allRequests->where('status', 'pending')->count();

        // Product-wise Total Demand Aggregation
        $allItemIds = $allRequests->pluck('id');
        $productDemands = MonthlyMarketRequestItem::whereIn('request_id', $allItemIds)
            ->select('product_name', 'unit',
                DB::raw('SUM(requested_quantity) as total_requested'),
                DB::raw('SUM(approved_quantity) as total_approved'),
                DB::raw('SUM(allocated_quantity) as total_allocated'),
                DB::raw('SUM(collected_quantity) as total_collected')
            )
            ->groupBy('product_name', 'unit')
            ->get();

        // Area-wise Demand Matrix
        $areaDemands = $allRequests->groupBy('request_area')->map(function ($areaReqs, $areaName) {
            $reqIds = $areaReqs->pluck('id');
            $items = MonthlyMarketRequestItem::whereIn('request_id', $reqIds)->get();

            return [
                'area' => $areaName ?: 'সাধারণ',
                'cardholders_count' => $areaReqs->pluck('user_id')->unique()->count(),
                'requests_count' => $areaReqs->count(),
                'products' => $items->groupBy('product_name')->map(function ($prods) {
                    return [
                        'quantity' => $prods->sum('requested_quantity'),
                        'unit' => $prods->first()->unit ?? 'KG',
                    ];
                }),
            ];
        });

        // Agent Point Demand Summary
        $agentPointDemands = $allRequests->groupBy('agent_point_id')->map(function ($agentReqs, $pointId) {
            $point = AgentPoint::find($pointId);
            $reqIds = $agentReqs->pluck('id');
            $items = MonthlyMarketRequestItem::whereIn('request_id', $reqIds)->get();

            return [
                'agent_point_name' => $point ? $point->name : 'অনির্ধারিত পয়েন্ট',
                'area' => $point ? $point->area : '-',
                'total_cardholders' => $agentReqs->pluck('user_id')->unique()->count(),
                'total_requests' => $agentReqs->count(),
                'products' => $items->groupBy('product_name')->map(function ($prods) {
                    return [
                        'requested' => $prods->sum('requested_quantity'),
                        'approved' => $prods->sum('approved_quantity'),
                        'unit' => $prods->first()->unit ?? 'KG',
                    ];
                }),
            ];
        });

        $areas = MonthlyMarketRequest::whereNotNull('request_area')->distinct()->pluck('request_area');

        return view('admin.monthly_market.demand_analysis', compact(
            'selectedMonth',
            'selectedYear',
            'totalCardHolders',
            'totalSubmittedRequests',
            'approvedDemandCount',
            'pendingDemandCount',
            'productDemands',
            'areaDemands',
            'agentPointDemands',
            'areas'
        ));
    }

    // ==========================================
    // 3. AGENT POINT DISTRIBUTION ALLOCATION (Section 20)
    // ==========================================
    public function allocations(Request $request)
    {
        $selectedMonth = $request->input('month', Carbon::now()->format('F'));
        $selectedYear = $request->input('year', Carbon::now()->format('Y'));
        $selectedAgentPointId = $request->input('agent_point_id');

        $agentPoints = AgentPoint::where('status', 'active')->get();

        $allocationsQuery = AgentPointAllocation::where('month', $selectedMonth)
            ->where('year', $selectedYear);

        if ($selectedAgentPointId) {
            $allocationsQuery->where('agent_point_id', $selectedAgentPointId);
        }

        $allocations = $allocationsQuery->with('agentPoint')->get();

        return view('admin.monthly_market.allocations', compact(
            'selectedMonth',
            'selectedYear',
            'selectedAgentPointId',
            'agentPoints',
            'allocations'
        ));
    }

    public function storeAllocation(Request $request)
    {
        $request->validate([
            'agent_point_id' => 'required|exists:agent_points,id',
            'month' => 'required|string',
            'year' => 'required|string',
            'product_name' => 'required|string|max:255',
            'unit' => 'required|string|max:30',
            'allocated_quantity' => 'required|numeric|min:0',
        ]);

        AgentPointAllocation::updateOrCreate(
            [
                'agent_point_id' => $request->agent_point_id,
                'month' => $request->month,
                'year' => $request->year,
                'product_name' => $request->product_name,
            ],
            [
                'unit' => $request->unit,
                'requested_quantity' => $request->requested_quantity ?? $request->allocated_quantity,
                'allocated_quantity' => $request->allocated_quantity,
                'remaining_quantity' => $request->allocated_quantity,
                'notes' => $request->notes,
                'allocation_reason' => $request->allocation_reason,
            ]
        );

        return redirect()->back()->with('success', 'এজেন্ট পয়েন্ট পণ্য বরাদ্দ সফলভাবে সংরক্ষিত হয়েছে!');
    }

    // ==========================================
    // 4. CENTRAL DISPATCH MANAGEMENT (Section 21)
    // ==========================================
    public function dispatches(Request $request)
    {
        $dispatches = AgentPointDispatch::with(['agentPoint', 'items', 'dispatcher'])
            ->orderBy('dispatch_date', 'desc')
            ->paginate(20);

        $agentPoints = AgentPoint::where('status', 'active')->get();
        $availableProducts = MonthlyBazaarItem::where('status', 'active')->get();

        return view('admin.monthly_market.dispatches', compact('dispatches', 'agentPoints', 'availableProducts'));
    }

    public function storeDispatch(Request $request)
    {
        $request->validate([
            'agent_point_id' => 'required|exists:agent_points,id',
            'distribution_month' => 'required|string',
            'distribution_year' => 'required|string',
            'dispatch_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'required|string',
            'items.*.sent_quantity' => 'required|numeric|min:0.01',
        ]);

        $agentPoint = AgentPoint::findOrFail($request->agent_point_id);
        $dispatchNo = 'DSP-' . date('ym') . '-' . strtoupper(Str::random(5));

        DB::transaction(function () use ($request, $agentPoint, $dispatchNo) {
            $dispatch = AgentPointDispatch::create([
                'dispatch_no' => $dispatchNo,
                'distribution_month' => $request->distribution_month,
                'distribution_year' => $request->distribution_year,
                'agent_point_id' => $agentPoint->id,
                'area' => $agentPoint->area,
                'vehicle_info' => $request->vehicle_info,
                'driver_info' => $request->driver_info,
                'delivery_note' => $request->delivery_note,
                'dispatch_note' => $request->dispatch_note,
                'dispatch_date' => $request->dispatch_date,
                'dispatched_by' => Auth::id(),
                'status' => 'in_transit',
            ]);

            foreach ($request->items as $item) {
                if (empty($item['product_name']) || empty($item['sent_quantity'])) {
                    continue;
                }

                $sentQty = floatval($item['sent_quantity']);
                $unit = $item['unit'] ?? 'KG';

                AgentPointDispatchItem::create([
                    'dispatch_id' => $dispatch->id,
                    'product_name' => $item['product_name'],
                    'unit' => $unit,
                    'sent_quantity' => $sentQty,
                    'received_quantity' => 0,
                    'short_quantity' => 0,
                    'damaged_quantity' => 0,
                    'notes' => $item['notes'] ?? null,
                ]);

                // Inventory Rule #1862: Decrement Central Warehouse Stock upon Dispatch!
                $stockItem = MonthlyBazaarItem::where('title', 'like', '%' . $item['product_name'] . '%')
                    ->orWhere('package_name', 'like', '%' . $item['product_name'] . '%')
                    ->first();

                if ($stockItem && $stockItem->quantity >= $sentQty) {
                    $stockItem->decrement('quantity', $sentQty);
                }

                // Update Agent Point Allocation dispatched quantity
                $allocation = AgentPointAllocation::where('agent_point_id', $agentPoint->id)
                    ->where('month', $request->distribution_month)
                    ->where('year', $request->distribution_year)
                    ->where('product_name', $item['product_name'])
                    ->first();

                if ($allocation) {
                    $allocation->increment('dispatched_quantity', $sentQty);
                }
            }
        });

        return redirect()->route('admin.monthly_market.dispatches')->with('success', 'সেন্ট্রাল স্টক থেকে এজেন্ট পয়েন্টে সফলভাবে ডিসপ্যাচ করা হয়েছে! চালান নং: ' . $dispatchNo);
    }

    public function printDispatch($id)
    {
        $dispatch = AgentPointDispatch::with(['agentPoint', 'items', 'dispatcher'])->findOrFail($id);
        return view('admin.monthly_market.print_dispatch', compact('dispatch'));
    }

    // ==========================================
    // 5. AGENT RECEIVE CONFIRMATION (Section 22)
    // ==========================================
    public function receiveDispatch(Request $request, $id)
    {
        $dispatch = AgentPointDispatch::with('items')->findOrFail($id);

        $request->validate([
            'received_by' => 'required|string',
            'received_date' => 'required|date',
        ]);

        DB::transaction(function () use ($request, $dispatch) {
            foreach ($dispatch->items as $item) {
                $recKey = 'received_qty_' . $item->id;
                $dmgKey = 'damaged_qty_' . $item->id;

                $receivedQty = floatval($request->input($recKey, $item->sent_quantity));
                $damagedQty = floatval($request->input($dmgKey, 0));
                $shortQty = max(0, $item->sent_quantity - ($receivedQty + $damagedQty));

                $item->update([
                    'received_quantity' => $receivedQty,
                    'damaged_quantity' => $damagedQty,
                    'short_quantity' => $shortQty,
                ]);

                // Update Agent Point Allocation received quantity
                $allocation = AgentPointAllocation::where('agent_point_id', $dispatch->agent_point_id)
                    ->where('month', $dispatch->distribution_month)
                    ->where('year', $dispatch->distribution_year)
                    ->where('product_name', $item->product_name)
                    ->first();

                if ($allocation) {
                    $allocation->increment('received_quantity', $receivedQty);
                }
            }

            $dispatch->update([
                'status' => 'received',
                'received_date' => $request->received_date,
                'received_by' => $request->received_by,
                'receive_note' => $request->receive_note,
            ]);
        });

        return redirect()->back()->with('success', 'এজেন্ট পয়েন্ট পণ্য গ্রহণ নিশ্চিত করা হয়েছে!');
    }

    // ==========================================
    // 6. CARD HOLDER COLLECTION & DELIVERY (Section 24 & 25)
    // ==========================================
    public function confirmCollection(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:monthly_market_requests,id',
            'collection_date' => 'required|date',
            'delivered_by' => 'required|string',
            'items' => 'required|array|min:1',
        ]);

        $marketRequest = MonthlyMarketRequest::with('items')->findOrFail($request->request_id);
        $collectionNo = 'COL-' . date('ym') . '-' . strtoupper(Str::random(5));

        DB::transaction(function () use ($request, $marketRequest, $collectionNo) {
            $isFullyCollected = true;

            $collection = CardHolderCollection::create([
                'collection_no' => $collectionNo,
                'request_id' => $marketRequest->id,
                'user_id' => $marketRequest->user_id,
                'agent_point_id' => $marketRequest->agent_point_id,
                'collection_date' => $request->collection_date,
                'collection_status' => 'fully_collected',
                'delivered_by' => $request->delivered_by,
                'received_by' => $request->received_by ?? $marketRequest->user->name,
                'otp_or_qr_code' => $request->otp_or_qr_code,
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $itemId => $itemData) {
                $reqItem = MonthlyMarketRequestItem::where('request_id', $marketRequest->id)->find($itemId);
                if (!$reqItem) continue;

                $delQty = floatval($itemData['delivered_quantity'] ?? 0);
                $newCollected = $reqItem->collected_quantity + $delQty;
                $remaining = max(0, $reqItem->approved_quantity - $newCollected);

                $reqItem->update([
                    'collected_quantity' => $newCollected,
                    'remaining_quantity' => $remaining,
                ]);

                CardHolderCollectionItem::create([
                    'collection_id' => $collection->id,
                    'request_item_id' => $reqItem->id,
                    'product_name' => $reqItem->product_name,
                    'unit' => $reqItem->unit,
                    'allocated_quantity' => $reqItem->approved_quantity,
                    'delivered_quantity' => $delQty,
                    'remaining_quantity' => $remaining,
                ]);

                if ($remaining > 0) {
                    $isFullyCollected = false;
                }
            }

            $finalCollectionStatus = $isFullyCollected ? 'fully_collected' : 'partially_collected';
            $finalRequestStatus = $isFullyCollected ? 'completed' : 'distributed';

            $collection->update(['collection_status' => $finalCollectionStatus]);
            $marketRequest->update([
                'status' => $finalRequestStatus,
                'collection_status' => $finalCollectionStatus,
            ]);
        });

        return redirect()->back()->with('success', 'সদস্যের পণ্য বিতরণ সফলভাবে সম্পন্ন হয়েছে! ভাউচার নং: ' . $collectionNo);
    }

    // ==========================================
    // 7. COMPREHENSIVE REPORTS (Section 27, 28, 29)
    // ==========================================
    public function comprehensiveReports(Request $request)
    {
        $selectedMonth = $request->input('month', Carbon::now()->format('F'));
        $selectedYear = $request->input('year', Carbon::now()->format('Y'));

        // 1. Area-wise Report
        $allRequests = MonthlyMarketRequest::where('month', $selectedMonth)
            ->where('year', $selectedYear)
            ->with(['items', 'agentPoint'])
            ->get();

        $areaReports = $allRequests->groupBy('request_area')->map(function ($areaReqs, $areaName) {
            $reqIds = $areaReqs->pluck('id');
            $items = MonthlyMarketRequestItem::whereIn('request_id', $reqIds)->get();

            return [
                'area' => $areaName ?: 'সাধারণ',
                'cardholders_count' => $areaReqs->pluck('user_id')->unique()->count(),
                'total_requests' => $areaReqs->count(),
                'total_demand_qty' => $items->sum('requested_quantity'),
                'approved_qty' => $items->sum('approved_quantity'),
                'allocated_qty' => $items->sum('allocated_quantity'),
                'distributed_qty' => $items->sum('collected_quantity'),
                'remaining_qty' => $items->sum('remaining_quantity'),
            ];
        });

        // 2. Agent Point-wise Report
        $agentPointReports = $allRequests->groupBy('agent_point_id')->map(function ($agentReqs, $pointId) {
            $point = AgentPoint::find($pointId);
            $reqIds = $agentReqs->pluck('id');
            $items = MonthlyMarketRequestItem::whereIn('request_id', $reqIds)->get();

            return [
                'agent_point' => $point ? $point->name : 'অনির্ধারিত পয়েন্ট',
                'area' => $point ? $point->area : '-',
                'cardholders_count' => $agentReqs->pluck('user_id')->unique()->count(),
                'total_requests' => $agentReqs->count(),
                'total_demand_qty' => $items->sum('requested_quantity'),
                'approved_qty' => $items->sum('approved_quantity'),
                'allocated_qty' => $items->sum('allocated_quantity'),
                'distributed_qty' => $items->sum('collected_quantity'),
                'remaining_qty' => $items->sum('remaining_quantity'),
            ];
        });

        // 3. Product-wise Supplier vs Card Holder Comparison
        $reqIds = $allRequests->pluck('id');
        $productReports = MonthlyMarketRequestItem::whereIn('request_id', $reqIds)
            ->select('product_name', 'unit',
                DB::raw('SUM(requested_quantity) as requested_qty'),
                DB::raw('SUM(approved_quantity) as approved_qty'),
                DB::raw('SUM(allocated_quantity) as allocated_qty'),
                DB::raw('SUM(collected_quantity) as collected_qty'),
                DB::raw('SUM(remaining_quantity) as remaining_qty')
            )
            ->groupBy('product_name', 'unit')
            ->get();

        return view('admin.monthly_market.comprehensive_reports', compact(
            'selectedMonth',
            'selectedYear',
            'areaReports',
            'agentPointReports',
            'productReports'
        ));
    }
}
