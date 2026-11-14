<?php

namespace App\Http\Controllers\Admin\SuperShop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuperShop\Branch;
use App\Models\SuperShop\BranchInventory;
use App\Models\SuperShop\PosSale;
use App\Models\SuperShop\PosCounter;
use App\Models\SuperShop\BranchExpense;
use App\Models\SuperShop\PurchaseOrder;
use App\Models\Shop\ShopOrder;
use App\Models\Shop\ShopProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SuperShopDashboardController extends Controller
{
    /**
     * Central Super Admin & Branch Executive Dashboard
     */
    public function index(Request $request)
    {
        $selectedBranchId = $request->get('branch_id');
        $dateFilter = $request->get('date_filter', 'today');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        // Determine date bounds
        $today = Carbon::today();
        $startDate = match ($dateFilter) {
            'yesterday' => Carbon::yesterday()->startOfDay(),
            'last_7_days' => Carbon::today()->subDays(6)->startOfDay(),
            'this_month' => Carbon::today()->startOfMonth(),
            'this_year' => Carbon::today()->startOfYear(),
            'custom' => $fromDate ? Carbon::parse($fromDate)->startOfDay() : Carbon::today()->startOfDay(),
            default => Carbon::today()->startOfDay(),
        };

        $endDate = match ($dateFilter) {
            'yesterday' => Carbon::yesterday()->endOfDay(),
            'custom' => $toDate ? Carbon::parse($toDate)->endOfDay() : Carbon::today()->endOfDay(),
            default => Carbon::today()->endOfDay(),
        };

        // All Branches list for dropdown and comparison
        $branches = Branch::with(['posCounters', 'manager'])->orderBy('id', 'asc')->get();

        // 1. Overall Statistics (Central Super Admin)
        $totalBranches = $branches->count();
        $activeBranches = $branches->where('status', 'active')->count();
        $totalCounters = PosCounter::where('status', 'active')->count();
        $totalProducts = ShopProduct::where('is_active', true)->count();

        // POS Sales Query
        $posQuery = PosSale::where('sale_status', 'completed')
            ->whereBetween('sale_time', [$startDate, $endDate]);

        if ($selectedBranchId) {
            $posQuery->where('branch_id', $selectedBranchId);
        }

        $totalPosSales = (float)$posQuery->sum('grand_total');
        $totalTransactions = $posQuery->count();

        // Online E-commerce Sales Query (Channel Split)
        $onlineQuery = ShopOrder::whereNotIn('order_status', ['cancelled', 'canceled'])
            ->whereBetween('created_at', [$startDate, $endDate]);
        $totalOnlineSales = (float)$onlineQuery->sum('total_amount');
        $totalOnlineOrders = $onlineQuery->count();

        $combinedRevenue = $totalPosSales + $totalOnlineSales;

        // Inventory Statistics
        $invQuery = BranchInventory::with('product');
        if ($selectedBranchId) {
            $invQuery->where('branch_id', $selectedBranchId);
        }
        $allInventories = $invQuery->get();

        $totalStockUnits = $allInventories->sum('stock_quantity');
        $totalStockValuation = $allInventories->sum(function ($inv) {
            return $inv->stock_quantity * ($inv->purchase_price ?: ($inv->product->price * 0.8));
        });

        $lowStockCount = $allInventories->filter(function ($inv) {
            return $inv->stock_quantity <= $inv->reorder_level;
        })->count();

        // Expenses Query
        $expQuery = BranchExpense::where('status', 'paid')
            ->whereBetween('expense_date', [$startDate->toDateString(), $endDate->toDateString()]);
        if ($selectedBranchId) {
            $expQuery->where('branch_id', $selectedBranchId);
        }
        $totalExpenses = (float)$expQuery->sum('amount');

        // Approximate Gross Profit & Net Profit
        // Gross Profit = Total POS Sales - Cost of Goods Sold (estimated at ~80% or from sale items)
        $grossProfit = PosSale::where('sale_status', 'completed')
            ->whereBetween('sale_time', [$startDate, $endDate])
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->with('items')
            ->get()
            ->sum(function ($s) {
                return $s->items->sum(function ($item) {
                    return $item->total_price - ($item->cost_price * $item->quantity);
                });
            });

        if ($grossProfit <= 0 && $totalPosSales > 0) {
            $grossProfit = $totalPosSales * 0.20; // Fallback 20% margin
        }

        $netProfit = $grossProfit - $totalExpenses;

        // Branch-wise Performance Comparison Table
        $branchComparison = [];
        foreach ($branches as $br) {
            $brSalesQuery = PosSale::where('branch_id', $br->id)
                ->where('sale_status', 'completed')
                ->whereBetween('sale_time', [$startDate, $endDate]);

            $brSales = (float)$brSalesQuery->sum('grand_total');
            $brTrans = $brSalesQuery->count();

            $brInventories = BranchInventory::where('branch_id', $br->id)->get();
            $brStockUnits = $brInventories->sum('stock_quantity');
            $brLowStock = $brInventories->where('stock_quantity', '<=', 15)->count();

            $brExpenses = BranchExpense::where('branch_id', $br->id)
                ->where('status', 'paid')
                ->whereBetween('expense_date', [$startDate->toDateString(), $endDate->toDateString()])
                ->sum('amount');

            $branchComparison[] = [
                'branch' => $br,
                'sales' => $brSales,
                'transactions' => $brTrans,
                'stock_units' => $brStockUnits,
                'low_stock' => $brLowStock,
                'expenses' => $brExpenses,
            ];
        }

        // Sort by sales descending
        usort($branchComparison, fn($a, $b) => $b['sales'] <=> $a['sales']);

        // Recent POS Sales
        $recentSales = PosSale::with(['branch', 'cashier'])
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        // Low stock items preview
        $lowStockItems = BranchInventory::with(['branch', 'product'])
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->whereRaw('stock_quantity <= reorder_level')
            ->orderBy('stock_quantity', 'asc')
            ->take(8)
            ->get();

        return view('admin.supershop.dashboard.index', compact(
            'branches',
            'selectedBranchId',
            'dateFilter',
            'startDate',
            'endDate',
            'totalBranches',
            'activeBranches',
            'totalCounters',
            'totalProducts',
            'totalPosSales',
            'totalTransactions',
            'totalOnlineSales',
            'totalOnlineOrders',
            'combinedRevenue',
            'totalStockUnits',
            'totalStockValuation',
            'lowStockCount',
            'totalExpenses',
            'grossProfit',
            'netProfit',
            'branchComparison',
            'recentSales',
            'lowStockItems'
        ));
    }
}

