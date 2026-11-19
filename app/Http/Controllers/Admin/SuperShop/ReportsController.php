<?php

namespace App\Http\Controllers\Admin\SuperShop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuperShop\Branch;
use App\Models\SuperShop\PosSale;
use App\Models\SuperShop\PosSaleItem;
use App\Models\SuperShop\BranchExpense;
use App\Models\SuperShop\BranchInventory;
use App\Models\Shop\ShopOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * Display Super Shop Business Analytics, Product-wise Sales, Monthly Trends & Profit/Loss Report.
     */
    public function index(Request $request)
    {
        $branches = Branch::where('status', 'active')->get();
        $selectedBranchId = $request->get('branch_id');
        if (auth()->check() && auth()->user()->role === 'manager' && auth()->user()->branch_id && !$selectedBranchId) {
            $selectedBranchId = auth()->user()->branch_id;
        }
        $period = $request->get('period', 'monthly'); // daily, weekly, monthly, yearly, custom
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        // Determine date range
        $startDate = match ($period) {
            'daily' => Carbon::today()->startOfDay(),
            'weekly' => Carbon::today()->subDays(6)->startOfDay(),
            'yearly' => Carbon::today()->startOfYear(),
            'custom' => $fromDate ? Carbon::parse($fromDate)->startOfDay() : Carbon::today()->startOfMonth(),
            default => Carbon::today()->startOfMonth(),
        };

        $endDate = match ($period) {
            'daily' => Carbon::today()->endOfDay(),
            'custom' => $toDate ? Carbon::parse($toDate)->endOfDay() : Carbon::today()->endOfDay(),
            default => Carbon::today()->endOfDay(),
        };

        // 1. POS Sales Query (Completed)
        $salesQuery = PosSale::with('items')
            ->where('sale_status', 'completed')
            ->whereBetween('sale_time', [$startDate, $endDate]);

        if ($selectedBranchId) {
            $salesQuery->where('branch_id', $selectedBranchId);
        }

        $allSales = $salesQuery->get();
        $totalGrossSales = (float)$allSales->sum('subtotal');
        $totalDiscounts = (float)$allSales->sum('discount_amount');
        $totalNetSales = (float)$allSales->sum('grand_total');
        $totalTransactions = $allSales->count();

        // Total Cost of Goods Sold (COGS)
        $totalCogs = $allSales->sum(function ($s) {
            return $s->items->sum(function ($item) {
                return ($item->cost_price ?: ($item->unit_price * 0.8)) * $item->quantity;
            });
        });
        if ($totalCogs <= 0 && $totalNetSales > 0) {
            $totalCogs = $totalNetSales * 0.80;
        }

        $grossProfit = max(0, $totalNetSales - $totalCogs);

        // 2. Branch Operating Expenses (Electricity bills, rent, salaries, utilities)
        $expQuery = BranchExpense::with('category')
            ->where('status', 'paid')
            ->whereBetween('expense_date', [$startDate->toDateString(), $endDate->toDateString()]);
        if ($selectedBranchId) {
            $expQuery->where('branch_id', $selectedBranchId);
        }
        $expensesList = $expQuery->get();
        $totalExpenses = (float)$expensesList->sum('amount');

        // Expenses breakdown by category (Rent, Electricity, Salary, etc.)
        $expensesByCategory = $expensesList->groupBy(fn($e) => $e->category->name ?? 'বিবিধ')->map->sum('amount');

        $netProfit = $grossProfit - $totalExpenses;

        // 3. Cancelled & Returned Sales
        $cancelledCount = PosSale::where('sale_status', 'cancelled')
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->whereBetween('sale_time', [$startDate, $endDate])
            ->count();
        $cancelledAmount = (float) PosSale::where('sale_status', 'cancelled')
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->whereBetween('sale_time', [$startDate, $endDate])
            ->sum('grand_total');

        // 4. Product-wise Sales Ranking (কোন প্রোডাক্ট কত পিস ও কত টাকার বিক্রি হয়েছে)
        $topSellingProducts = PosSaleItem::select(
                'product_id',
                'product_name',
                'sku',
                DB::raw('SUM(quantity) as total_sold_qty'),
                DB::raw('SUM(total_price) as total_revenue'),
                DB::raw('SUM(COALESCE(cost_price, unit_price * 0.8) * quantity) as total_cost')
            )
            ->whereHas('sale', function ($q) use ($selectedBranchId, $startDate, $endDate) {
                $q->where('sale_status', 'completed')
                  ->whereBetween('sale_time', [$startDate, $endDate]);
                if ($selectedBranchId) {
                    $q->where('branch_id', $selectedBranchId);
                }
            })
            ->groupBy('product_id', 'product_name', 'sku')
            ->orderByDesc('total_revenue')
            ->take(25)
            ->get();

        // 5. Monthly Sales Trend (মাসভিত্তিক সামগ্রিক বিক্রয় ট্রেন্ড)
        $monthlySalesTrend = PosSale::select(
                DB::raw("DATE_FORMAT(sale_time, '%Y-%m') as ym"),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(grand_total) as total_sales'),
                DB::raw('SUM(discount_amount) as total_discounts')
            )
            ->where('sale_status', 'completed')
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->groupBy('ym')
            ->orderByDesc('ym')
            ->take(12)
            ->get();

        // 6. Daily Sales Trend for selected date range
        $dailySalesTrend = PosSale::select(
                DB::raw("DATE(sale_time) as sale_date"),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(grand_total) as total_sales')
            )
            ->where('sale_status', 'completed')
            ->whereBetween('sale_time', [$startDate, $endDate])
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->groupBy('sale_date')
            ->orderByDesc('sale_date')
            ->take(31)
            ->get();

        // 7. Payment Methods Breakdown
        $paymentMethodBreakdown = PosSale::select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(grand_total) as total'))
            ->where('sale_status', 'completed')
            ->whereBetween('sale_time', [$startDate, $endDate])
            ->when($selectedBranchId, fn($q) => $q->where('branch_id', $selectedBranchId))
            ->groupBy('payment_method')
            ->get();

        // 8. E-commerce Online Sales in same period
        $onlineQuery = ShopOrder::whereNotIn('order_status', ['cancelled', 'canceled'])
            ->whereBetween('created_at', [$startDate, $endDate]);
        $totalOnlineSales = (float)$onlineQuery->sum('total_amount');
        $totalOnlineOrders = $onlineQuery->count();

        // 9. Branch-wise Matrix
        $branchReports = [];
        foreach ($branches as $br) {
            $brSales = PosSale::with('items')
                ->where('branch_id', $br->id)
                ->where('sale_status', 'completed')
                ->whereBetween('sale_time', [$startDate, $endDate])
                ->get();

            $brNetSales = (float)$brSales->sum('grand_total');
            $brCogs = $brSales->sum(fn($s) => $s->items->sum(fn($i) => ($i->cost_price ?: ($i->unit_price * 0.8)) * $i->quantity)) ?: ($brNetSales * 0.80);
            $brGrossProfit = max(0, $brNetSales - $brCogs);

            $brExp = BranchExpense::where('branch_id', $br->id)
                ->where('status', 'paid')
                ->whereBetween('expense_date', [$startDate->toDateString(), $endDate->toDateString()])
                ->sum('amount');

            $brNetProfit = $brGrossProfit - $brExp;

            $branchReports[] = [
                'branch' => $br,
                'transactions' => $brSales->count(),
                'net_sales' => $brNetSales,
                'cogs' => $brCogs,
                'gross_profit' => $brGrossProfit,
                'expenses' => $brExp,
                'net_profit' => $brNetProfit,
                'profit_margin' => $brNetSales > 0 ? round(($brNetProfit / $brNetSales) * 100, 1) : 0,
            ];
        }

        return view('admin.supershop.reports.index', compact(
            'branches',
            'selectedBranchId',
            'period',
            'startDate',
            'endDate',
            'totalGrossSales',
            'totalDiscounts',
            'totalNetSales',
            'totalTransactions',
            'totalCogs',
            'grossProfit',
            'totalExpenses',
            'netProfit',
            'expensesByCategory',
            'cancelledCount',
            'cancelledAmount',
            'topSellingProducts',
            'monthlySalesTrend',
            'dailySalesTrend',
            'paymentMethodBreakdown',
            'totalOnlineSales',
            'totalOnlineOrders',
            'branchReports'
        ));
    }
}
