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
     * Display Super Shop Business Analytics & Profit/Loss Report.
     */
    public function index(Request $request)
    {
        $branches = Branch::where('status', 'active')->get();
        $selectedBranchId = $request->get('branch_id');
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

        // POS Sales Query
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
                return $item->cost_price * $item->quantity;
            });
        });
        if ($totalCogs <= 0 && $totalNetSales > 0) {
            $totalCogs = $totalNetSales * 0.80;
        }

        $grossProfit = max(0, $totalNetSales - $totalCogs);

        // Expenses
        $expQuery = BranchExpense::where('status', 'paid')
            ->whereBetween('expense_date', [$startDate->toDateString(), $endDate->toDateString()]);
        if ($selectedBranchId) {
            $expQuery->where('branch_id', $selectedBranchId);
        }
        $totalExpenses = (float)$expQuery->sum('amount');

        $netProfit = $grossProfit - $totalExpenses;

        // E-commerce Online Sales in same period
        $onlineQuery = ShopOrder::whereNotIn('order_status', ['cancelled', 'canceled'])
            ->whereBetween('created_at', [$startDate, $endDate]);
        $totalOnlineSales = (float)$onlineQuery->sum('total_amount');
        $totalOnlineOrders = $onlineQuery->count();

        // Branch-wise Breakdown for reporting table
        $branchReports = [];
        foreach ($branches as $br) {
            $brSales = PosSale::with('items')
                ->where('branch_id', $br->id)
                ->where('sale_status', 'completed')
                ->whereBetween('sale_time', [$startDate, $endDate])
                ->get();

            $brNetSales = (float)$brSales->sum('grand_total');
            $brCogs = $brSales->sum(fn($s) => $s->items->sum(fn($i) => $i->cost_price * $i->quantity)) ?: ($brNetSales * 0.80);
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
            'totalOnlineSales',
            'totalOnlineOrders',
            'branchReports'
        ));
    }
}

