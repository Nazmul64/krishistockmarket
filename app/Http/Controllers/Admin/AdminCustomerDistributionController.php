<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentPoint;
use App\Models\AgentPointDailyClosing;
use App\Models\AgentPointInventory;
use App\Models\CardHolderCollection;
use App\Models\CardHolderCollectionItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminCustomerDistributionController extends Controller
{
    /**
     * Central Admin Customer Distribution Dashboard (Section 12).
     */
    public function dashboard(Request $request)
    {
        $today = date('Y-m-d');
        $currentMonth = date('F');
        $currentYear = (int) date('Y');

        // 11 Central KPIs
        $totalAgentPoints = AgentPoint::where('status', 'active')->count();
        $totalRegisteredCardHolders = User::whereIn('role', ['customer', 'user'])->count();
        $totalCustomersServedToday = CardHolderCollection::whereDate('collection_date', $today)->count();
        $totalCustomersServedThisMonth = CardHolderCollection::where('market_month', $currentMonth)
            ->where('market_year', $currentYear)
            ->count();
        $totalMonthlyMarketsDistributed = CardHolderCollection::where('market_type', 'monthly_market')->count();
        $totalWeeklyMarketsDistributed = CardHolderCollection::where('market_type', 'weekly_market')->count();
        $totalProductDistributed = CardHolderCollection::sum('total_distributed_qty');
        $totalDistributionValue = CardHolderCollection::sum('total_amount');
        
        $assignedCardHoldersCount = User::whereIn('role', ['customer', 'user'])->whereNotNull('assigned_agent_point_id')->count();
        $servedCardHoldersThisMonth = CardHolderCollection::where('market_month', $currentMonth)
            ->where('market_year', $currentYear)
            ->distinct('user_id')
            ->count('user_id');
        $totalPendingCustomers = max(0, $assignedCardHoldersCount - $servedCardHoldersThisMonth);

        $totalFullyCollected = CardHolderCollection::where('collection_status', 'fully_collected')->count();
        $totalPartialCollection = CardHolderCollection::where('collection_status', 'partially_collected')->count();

        // Recent Collections across all agent points
        $recentCollections = CardHolderCollection::with(['user', 'agentPoint', 'items'])
            ->latest()
            ->take(10)
            ->get();

        // Agent Point Distribution Summary Table
        $agentPointSummaries = AgentPoint::withCount('assignedCardHolders')
            ->get()
            ->map(function ($point) use ($today, $currentMonth, $currentYear) {
                $servedToday = CardHolderCollection::where('agent_point_id', $point->id)->whereDate('collection_date', $today)->count();
                $servedMonth = CardHolderCollection::where('agent_point_id', $point->id)->where('market_month', $currentMonth)->where('market_year', $currentYear)->count();
                $totalQty = CardHolderCollection::where('agent_point_id', $point->id)->sum('total_distributed_qty');
                $totalVal = CardHolderCollection::where('agent_point_id', $point->id)->sum('total_amount');

                return (object) [
                    'id'               => $point->id,
                    'code'             => $point->code,
                    'name'             => $point->name,
                    'area'             => $point->area,
                    'district'         => $point->district,
                    'total_cardholders'=> $point->assigned_card_holders_count,
                    'served_today'     => $servedToday,
                    'served_month'     => $servedMonth,
                    'total_qty'        => $totalQty,
                    'total_amount'     => $totalVal,
                ];
            });

        return view('admin.distribution.dashboard', compact(
            'totalAgentPoints',
            'totalRegisteredCardHolders',
            'totalCustomersServedToday',
            'totalCustomersServedThisMonth',
            'totalMonthlyMarketsDistributed',
            'totalWeeklyMarketsDistributed',
            'totalProductDistributed',
            'totalDistributionValue',
            'totalPendingCustomers',
            'totalFullyCollected',
            'totalPartialCollection',
            'recentCollections',
            'agentPointSummaries'
        ));
    }

    /**
     * Admin Agent Point-wise Customer Collection Summary (Section 13 & 14).
     */
    public function pointSummary($agent_point_id)
    {
        $point = AgentPoint::with(['agentUser', 'inventories'])
            ->withCount('assignedCardHolders')
            ->findOrFail($agent_point_id);

        $today = date('Y-m-d');
        $currentMonth = date('F');
        $currentYear = (int) date('Y');

        $servedToday = CardHolderCollection::where('agent_point_id', $point->id)->whereDate('collection_date', $today)->count();
        $monthlyCount = CardHolderCollection::where('agent_point_id', $point->id)->where('market_type', 'monthly_market')->count();
        $weeklyCount = CardHolderCollection::where('agent_point_id', $point->id)->where('market_type', 'weekly_market')->count();
        $totalDistributedQty = CardHolderCollection::where('agent_point_id', $point->id)->sum('total_distributed_qty');
        $totalDistributionAmount = CardHolderCollection::where('agent_point_id', $point->id)->sum('total_amount');

        $completedCount = CardHolderCollection::where('agent_point_id', $point->id)->where('collection_status', 'fully_collected')->count();
        $partialCount = CardHolderCollection::where('agent_point_id', $point->id)->where('collection_status', 'partially_collected')->count();
        $remainingCustomers = max(0, $point->assigned_card_holders_count - ($completedCount + $partialCount));

        // Customers list for this agent point
        $customers = User::where('assigned_agent_point_id', $point->id)
            ->whereIn('role', ['customer', 'user'])
            ->with(['cardHolderCollections' => function ($q) {
                $q->latest()->take(1);
            }])
            ->paginate(20);

        return view('admin.distribution.point_summary', compact(
            'point',
            'servedToday',
            'monthlyCount',
            'weeklyCount',
            'totalDistributedQty',
            'totalDistributionAmount',
            'completedCount',
            'partialCount',
            'remainingCustomers',
            'customers'
        ));
    }

    /**
     * Admin Customer List across all or selected Agent Point (Section 14).
     */
    public function customerList(Request $request)
    {
        $query = User::whereIn('role', ['customer', 'user'])
            ->with(['assignedAgentPoint', 'cardHolderCollections' => function ($q) {
                $q->latest()->take(1);
            }]);

        if ($request->filled('agent_point_id')) {
            $query->where('assigned_agent_point_id', $request->agent_point_id);
        }

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

        $customers = $query->paginate(25)->withQueryString();
        $agentPoints = AgentPoint::where('status', 'active')->orderBy('name')->get();

        return view('admin.distribution.customer_list', compact('customers', 'agentPoints'));
    }

    /**
     * Admin View Collection Transaction Details (Section 15).
     */
    public function transactionDetails($collection_id)
    {
        $collection = CardHolderCollection::with(['user', 'agentPoint', 'agentUser', 'deliverer', 'items'])
            ->findOrFail($collection_id);

        return view('admin.distribution.transaction_details', compact('collection'));
    }

    /**
     * Admin Print Receipt.
     */
    public function printReceipt($collection_id)
    {
        $collection = CardHolderCollection::with(['user', 'agentPoint', 'agentUser', 'deliverer', 'items'])
            ->findOrFail($collection_id);
        $point = $collection->agentPoint;

        return view('agent.distribution.receipt', compact('point', 'collection'));
    }

    /**
     * Admin Daily Agent Point Distribution Report (Section 16).
     */
    public function dailyAgentReport(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));

        $points = AgentPoint::where('status', 'active')->orderBy('name')->get();

        $dailyStats = $points->map(function ($point) use ($date) {
            $collections = CardHolderCollection::where('agent_point_id', $point->id)
                ->whereDate('collection_date', $date)
                ->get();

            $totalCustomer = $collections->count();
            $monthly = $collections->where('market_type', 'monthly_market')->count();
            $weekly = $collections->where('market_type', 'weekly_market')->count();
            $totalProduct = $collections->sum('total_distributed_qty');
            $amount = $collections->sum('total_amount');

            return (object) [
                'point'        => $point,
                'total_customer'=> $totalCustomer,
                'monthly'      => $monthly,
                'weekly'       => $weekly,
                'total_product'=> $totalProduct,
                'amount'       => $amount,
            ];
        });

        $grandTotalCustomers = $dailyStats->sum('total_customer');
        $grandTotalProduct = $dailyStats->sum('total_product');
        $grandTotalAmount = $dailyStats->sum('amount');

        return view('admin.distribution.daily_agent_report', compact(
            'date',
            'dailyStats',
            'grandTotalCustomers',
            'grandTotalProduct',
            'grandTotalAmount'
        ));
    }

    /**
     * Admin Product-wise Central Distribution Report (Section 17).
     */
    public function productReport(Request $request)
    {
        $fromDate = $request->input('from_date', date('Y-m-01'));
        $toDate = $request->input('to_date', date('Y-m-d'));
        $selectedProduct = $request->input('product_name');

        // Get all unique product names that have been distributed
        $productNames = CardHolderCollectionItem::distinct('product_name')->pluck('product_name');
        if (empty($selectedProduct) && $productNames->isNotEmpty()) {
            $selectedProduct = $productNames->first();
        }

        $agentDistributionBreakdown = collect();
        if ($selectedProduct) {
            $agentPoints = AgentPoint::where('status', 'active')->get();

            $agentDistributionBreakdown = $agentPoints->map(function ($point) use ($selectedProduct, $fromDate, $toDate) {
                $items = CardHolderCollectionItem::whereHas('collection', function ($q) use ($point, $fromDate, $toDate) {
                    $q->where('agent_point_id', $point->id)
                      ->whereBetween('collection_date', [$fromDate, $toDate]);
                })->where('product_name', $selectedProduct)->get();

                $customersServed = $items->count();
                $totalDistributed = $items->sum('delivered_quantity');
                $unit = $items->first()->unit ?? 'kg';
                $totalValue = $items->sum('total_amount');

                $pointStock = AgentPointInventory::where('agent_point_id', $point->id)
                    ->where('product_name', $selectedProduct)
                    ->value('available_stock') ?? 0;

                return (object) [
                    'point'            => $point,
                    'customers_served' => $customersServed,
                    'total_distributed'=> $totalDistributed,
                    'unit'             => $unit,
                    'total_value'      => $totalValue,
                    'remaining_stock'  => $pointStock,
                ];
            });
        }

        $totalServedAll = $agentDistributionBreakdown->sum('customers_served');
        $totalDistributedAll = $agentDistributionBreakdown->sum('total_distributed');
        $totalValueAll = $agentDistributionBreakdown->sum('total_value');
        $totalStockAll = $agentDistributionBreakdown->sum('remaining_stock');

        return view('admin.distribution.product_report', compact(
            'fromDate',
            'toDate',
            'productNames',
            'selectedProduct',
            'agentDistributionBreakdown',
            'totalServedAll',
            'totalDistributedAll',
            'totalValueAll',
            'totalStockAll'
        ));
    }

    /**
     * Admin Date-wise Customer Collection Report with Filters (Section 18).
     */
    public function dateWiseReport(Request $request)
    {
        $query = CardHolderCollection::with(['user', 'agentPoint', 'items']);

        // Date Presets or Custom Range
        $preset = $request->input('date_preset', 'this_month');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('collection_date', [$fromDate, $toDate]);
        } else {
            switch ($preset) {
                case 'today':
                    $query->whereDate('collection_date', date('Y-m-d'));
                    break;
                case 'yesterday':
                    $query->whereDate('collection_date', date('Y-m-d', strtotime('-1 day')));
                    break;
                case 'last_7_days':
                    $query->whereBetween('collection_date', [date('Y-m-d', strtotime('-7 days')), date('Y-m-d')]);
                    break;
                case 'last_30_days':
                    $query->whereBetween('collection_date', [date('Y-m-d', strtotime('-30 days')), date('Y-m-d')]);
                    break;
                case 'this_month':
                default:
                    $query->whereBetween('collection_date', [date('Y-m-01'), date('Y-m-t')]);
                    break;
            }
        }

        if ($request->filled('agent_point_id')) {
            $query->where('agent_point_id', $request->agent_point_id);
        }

        if ($request->filled('market_type')) {
            $query->where('market_type', $request->market_type);
        }

        if ($request->filled('collection_status')) {
            $query->where('collection_status', $request->collection_status);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('collection_no', 'like', "%{$s}%")
                  ->orWhere('card_number', 'like', "%{$s}%")
                  ->orWhere('customer_name', 'like', "%{$s}%")
                  ->orWhere('customer_phone', 'like', "%{$s}%");
            });
        }

        $collections = $query->latest('collection_date')->paginate(25)->withQueryString();
        $agentPoints = AgentPoint::where('status', 'active')->orderBy('name')->get();

        $totalCount = $query->count();
        $totalDistributedVolume = $query->sum('total_distributed_qty');
        $totalDistributionRevenue = $query->sum('total_amount');

        return view('admin.distribution.date_wise_report', compact(
            'collections',
            'agentPoints',
            'preset',
            'fromDate',
            'toDate',
            'totalCount',
            'totalDistributedVolume',
            'totalDistributionRevenue'
        ));
    }

    /**
     * Admin Agent Point Comparison Report (Section 19).
     */
    public function comparisonReport(Request $request)
    {
        $today = date('Y-m-d');
        $currentMonth = date('F');
        $currentYear = (int) date('Y');

        $agentPoints = AgentPoint::withCount('assignedCardHolders')->get();

        $comparisonData = $agentPoints->map(function ($point) use ($today, $currentMonth, $currentYear) {
            $servedToday = CardHolderCollection::where('agent_point_id', $point->id)->whereDate('collection_date', $today)->count();
            $servedThisMonth = CardHolderCollection::where('agent_point_id', $point->id)
                ->where('market_month', $currentMonth)
                ->where('market_year', $currentYear)
                ->count();
            $distributionValue = CardHolderCollection::where('agent_point_id', $point->id)
                ->where('market_month', $currentMonth)
                ->where('market_year', $currentYear)
                ->sum('total_amount');
            $pendingMembers = max(0, $point->assigned_card_holders_count - $servedThisMonth);

            return (object) [
                'point'             => $point,
                'total_members'     => $point->assigned_card_holders_count,
                'served_today'      => $servedToday,
                'served_this_month' => $servedThisMonth,
                'distribution_value'=> $distributionValue,
                'pending_members'   => $pendingMembers,
            ];
        })->sortByDesc('distribution_value');

        return view('admin.distribution.comparison_report', compact('comparisonData', 'currentMonth', 'currentYear'));
    }

    /**
     * Admin Agent Point Daily Closings Review (Section 21).
     */
    public function dailyClosings(Request $request)
    {
        $query = AgentPointDailyClosing::with(['agentPoint', 'agentUser', 'verifier']);

        if ($request->filled('agent_point_id')) {
            $query->where('agent_point_id', $request->agent_point_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('closing_date', $request->date);
        }

        $closings = $query->latest('closing_date')->paginate(20)->withQueryString();
        $agentPoints = AgentPoint::where('status', 'active')->orderBy('name')->get();

        return view('admin.distribution.daily_closings', compact('closings', 'agentPoints'));
    }

    /**
     * Admin Verifies Daily Closing (Section 21).
     */
    public function verifyDailyClosing(Request $request, $id)
    {
        $closing = AgentPointDailyClosing::findOrFail($id);

        $closing->update([
            'status'      => 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'admin_note'  => $request->admin_note,
        ]);

        return back()->with('success', "এজেন্ট পয়েন্ট [{$closing->agentPoint->name}] এর তারিখ [{$closing->closing_date}] এর ক্লোজিং সফলভাবে ভেরিফাই করা হয়েছে!");
    }
}

