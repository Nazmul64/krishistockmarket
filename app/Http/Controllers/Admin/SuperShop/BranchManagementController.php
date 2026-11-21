<?php

namespace App\Http\Controllers\Admin\SuperShop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuperShop\Branch;
use App\Models\SuperShop\BranchUser;
use App\Models\SuperShop\BranchInventory;
use App\Models\SuperShop\PosCounter;
use App\Models\SuperShop\PosSale;
use App\Models\SuperShop\PosSaleItem;
use App\Models\SuperShop\BranchExpense;
use App\Models\SuperShop\ExpenseCategory;
use App\Models\SuperShop\CashierShift;
use App\Models\SuperShop\CashDrawerTransaction;
use App\Models\SuperShop\EmployeeProfile;
use App\Models\SuperShop\EmployeeAttendance;
use App\Models\SuperShop\EmployeeLeave;
use App\Models\SuperShop\SalaryStructure;
use App\Models\SuperShop\SalaryPayment;
use App\Models\SuperShop\SalaryAdvance;
use App\Models\SuperShop\BranchFinancialTransaction;
use App\Models\SuperShop\StockMovement;
use App\Models\SuperShop\StockTransfer;
use App\Models\SuperShop\StockTransferItem;
use App\Models\SuperShop\BranchActivityLog;
use App\Models\Shop\ShopProduct;
use App\Models\Shop\ShopOrder;
use App\Models\Shop\ShopOrderItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BranchManagementController extends Controller
{
    /**
     * Section 2: Admin Dashboard - All Branches Overview & Combined KPI Cards
     */
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        if ($currentUser && $currentUser->role === 'manager') {
            $bId = $currentUser->branch_id ?: Branch::where('manager_id', $currentUser->id)->value('id');
            if ($bId) {
                return redirect()->route('admin.supershop.branches.show', $bId);
            }
        }

        $search = $request->get('search');
        $statusFilter = $request->get('status');

        $branchesQuery = Branch::with(['manager', 'posCounters', 'employees'])
            ->withCount(['inventories', 'posSales', 'employees']);

        if ($search) {
            $branchesQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($statusFilter) {
            $branchesQuery->where('status', $statusFilter);
        }

        $branches = $branchesQuery->orderBy('id', 'asc')->get();

        $today = Carbon::today();
        $thisMonthStart = Carbon::today()->startOfMonth();

        // Calculate per-branch real-time metrics for each branch card (Section 2.1)
        foreach ($branches as $branch) {
            // Today's total completed sales revenue
            $branch->today_sales = (float) PosSale::where('branch_id', $branch->id)
                ->whereDate('sale_time', $today)
                ->where('sale_status', 'completed')
                ->sum('grand_total');

            // Today's orders count
            $branch->today_orders = PosSale::where('branch_id', $branch->id)
                ->whereDate('sale_time', $today)
                ->where('sale_status', 'completed')
                ->count();

            // Today's expenses
            $branch->today_expenses = (float) BranchExpense::where('branch_id', $branch->id)
                ->whereDate('expense_date', $today)
                ->where('status', 'paid')
                ->sum('amount');

            // Today's cash collection (cash sales + cash transactions in)
            $branch->today_cash = (float) PosSale::where('branch_id', $branch->id)
                ->whereDate('sale_time', $today)
                ->where('sale_status', 'completed')
                ->where('payment_method', 'cash')
                ->sum('paid_amount');

            // Today's due sales amount
            $branch->today_due = (float) PosSale::where('branch_id', $branch->id)
                ->whereDate('sale_time', $today)
                ->where('payment_status', 'due')
                ->sum('grand_total');

            // Monthly sales & expenses
            $branch->monthly_sales = (float) PosSale::where('branch_id', $branch->id)
                ->whereBetween('sale_time', [$thisMonthStart, Carbon::now()])
                ->where('sale_status', 'completed')
                ->sum('grand_total');

            $branch->monthly_expenses = (float) BranchExpense::where('branch_id', $branch->id)
                ->whereBetween('expense_date', [$thisMonthStart->toDateString(), Carbon::now()->toDateString()])
                ->where('status', 'paid')
                ->sum('amount');

            // Lifetime total sales
            $branch->total_sales = (float) PosSale::where('branch_id', $branch->id)
                ->where('sale_status', 'completed')
                ->sum('grand_total');

            // Stock metrics
            $inventories = BranchInventory::where('branch_id', $branch->id)->get();
            $branch->total_stock_units = $inventories->sum('stock_quantity');
            $branch->total_products_assigned = $inventories->count();
            $branch->low_stock_count = $inventories->where('stock_quantity', '<=', $branch->low_stock_threshold ?: 10)->count();

            // Monthly payroll committed
            $branch->monthly_payroll = (float) EmployeeProfile::where('branch_id', $branch->id)
                ->where('status', 'active')
                ->sum('salary');
        }

        // Section 2.2: Central Combined KPIs across all branches
        $totalBranches = $branches->count();
        $activeBranches = $branches->where('status', 'active')->count();
        $todayAllBranchesSales = (float) PosSale::whereDate('sale_time', $today)
            ->where('sale_status', 'completed')
            ->sum('grand_total');
        $monthAllBranchesSales = (float) PosSale::whereBetween('sale_time', [$thisMonthStart, Carbon::now()])
            ->where('sale_status', 'completed')
            ->sum('grand_total');
        $todayAllOrders = PosSale::whereDate('sale_time', $today)
            ->where('sale_status', 'completed')
            ->count();
        $todayQtySold = (int) PosSaleItem::whereHas('sale', function($q) use ($today) {
                $q->whereDate('sale_time', $today)->where('sale_status', 'completed');
            })->sum('quantity');
        $totalAllExpenses = (float) BranchExpense::where('status', 'paid')
            ->whereBetween('expense_date', [$thisMonthStart->toDateString(), Carbon::now()->toDateString()])
            ->sum('amount');
        $totalCashReceived = (float) PosSale::where('sale_status', 'completed')
            ->where('payment_method', 'cash')
            ->sum('paid_amount');
        $totalDue = (float) PosSale::where('payment_status', 'due')->sum('grand_total');
        $totalStaff = (int) EmployeeProfile::where('status', 'active')->count();
        $monthlyPayroll = (float) EmployeeProfile::where('status', 'active')->sum('salary');

        // Central Net Profit across all branches
        $allCostOfGoods = (float) PosSaleItem::whereHas('sale', function($q) use ($thisMonthStart) {
                $q->whereBetween('sale_time', [$thisMonthStart, Carbon::now()])->where('sale_status', 'completed');
            })->sum(DB::raw('cost_price * quantity'));
        if ($allCostOfGoods <= 0 && $monthAllBranchesSales > 0) {
            $allCostOfGoods = $monthAllBranchesSales * 0.75;
        }
        $grossProfit = $monthAllBranchesSales - $allCostOfGoods;
        $netProfit = $grossProfit - ($totalAllExpenses + $monthlyPayroll);

        $users = User::orderBy('name', 'asc')->get();
        $totalEcomProducts = ShopProduct::where('is_active', true)->count();

        return view('admin.supershop.branches.index', compact(
            'branches',
            'users',
            'totalBranches',
            'activeBranches',
            'todayAllBranchesSales',
            'monthAllBranchesSales',
            'todayAllOrders',
            'todayQtySold',
            'totalAllExpenses',
            'totalCashReceived',
            'totalDue',
            'totalStaff',
            'monthlyPayroll',
            'netProfit',
            'totalEcomProducts'
        ));
    }

    /**
     * Section 3 - 16, 20, 21: Dedicated Comprehensive Branch Details Dashboard
     */
    public function show(Request $request, $id)
    {
        $branch = Branch::with(['manager', 'posCounters', 'employees'])
            ->findOrFail($id);

        // Security check for branch managers
        $currentUser = Auth::user();
        if ($currentUser && $currentUser->role === 'manager') {
            $userBranchId = $currentUser->branch_id ?: Branch::where('manager_id', $currentUser->id)->value('id');
            if ($userBranchId && $userBranchId != $branch->id) {
                return redirect()->route('admin.supershop.branches.show', $userBranchId)
                    ->with('error', 'নিরাপত্তা এক্সেস কন্ট্রোল: আপনি শুধুমাত্র আপনার নিজ ব্রাঞ্চের ডেটা ও ড্যাশবোর্ড পরিচালনা করতে পারবেন।');
            }
        }

        // Global Date Filter System (Section 23)
        $dateFilter = $request->get('date_filter', 'this_month');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        $now = Carbon::now();
        $startDate = match ($dateFilter) {
            'today' => Carbon::today()->startOfDay(),
            'yesterday' => Carbon::yesterday()->startOfDay(),
            'last_7_days' => Carbon::today()->subDays(6)->startOfDay(),
            'last_30_days' => Carbon::today()->subDays(29)->startOfDay(),
            'this_week' => Carbon::today()->startOfWeek(),
            'last_week' => Carbon::today()->subWeek()->startOfWeek(),
            'this_month' => Carbon::today()->startOfMonth(),
            'last_month' => Carbon::today()->subMonth()->startOfMonth(),
            'this_year' => Carbon::today()->startOfYear(),
            'last_year' => Carbon::today()->subYear()->startOfYear(),
            'custom' => $fromDate ? Carbon::parse($fromDate)->startOfDay() : Carbon::today()->startOfMonth(),
            default => Carbon::today()->startOfMonth(),
        };

        $endDate = match ($dateFilter) {
            'yesterday' => Carbon::yesterday()->endOfDay(),
            'last_week' => Carbon::today()->subWeek()->endOfWeek(),
            'last_month' => Carbon::today()->subMonth()->endOfMonth(),
            'last_year' => Carbon::today()->subYear()->endOfYear(),
            'custom' => $toDate ? Carbon::parse($toDate)->endOfDay() : Carbon::today()->endOfDay(),
            default => Carbon::today()->endOfDay(),
        };

        // 1. Header & Summary KPIs
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();

        $todaySales = (float) PosSale::where('branch_id', $branch->id)
            ->whereBetween('sale_time', [$todayStart, $todayEnd])
            ->where('sale_status', 'completed')
            ->sum('grand_total');

        $todayExpenses = (float) BranchExpense::where('branch_id', $branch->id)
            ->whereBetween('expense_date', [$todayStart->toDateString(), $todayEnd->toDateString()])
            ->where('status', 'paid')
            ->sum('amount');

        $todayProfit = $todaySales * 0.25 - $todayExpenses;

        $monthlySales = (float) PosSale::where('branch_id', $branch->id)
            ->whereBetween('sale_time', [Carbon::today()->startOfMonth(), Carbon::now()])
            ->where('sale_status', 'completed')
            ->sum('grand_total');

        $monthlyExpenses = (float) BranchExpense::where('branch_id', $branch->id)
            ->whereBetween('expense_date', [Carbon::today()->startOfMonth()->toDateString(), Carbon::now()->toDateString()])
            ->where('status', 'paid')
            ->sum('amount');

        $inventories = BranchInventory::with('product')
            ->where('branch_id', $branch->id)
            ->get();

        $totalProductsCount = $inventories->count();
        $totalStockUnits = $inventories->sum('stock_quantity');
        $lowStockThreshold = $branch->low_stock_threshold ?: 15;
        $lowStockCount = $inventories->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', $lowStockThreshold)->count();
        $outOfStockCount = $inventories->where('stock_quantity', '<=', 0)->count();

        $totalStaffCount = EmployeeProfile::where('branch_id', $branch->id)->where('status', 'active')->count();
        $monthlySalaryExpense = (float) EmployeeProfile::where('branch_id', $branch->id)->where('status', 'active')->sum('salary');

        // 2. Sales Tab Data (Filtered by Selected Date Range)
        $salesQuery = PosSale::with(['cashier', 'items.product'])
            ->where('branch_id', $branch->id)
            ->whereBetween('sale_time', [$startDate, $endDate])
            ->orderBy('sale_time', 'desc');

        $filteredSalesTotal = (float) (clone $salesQuery)->where('sale_status', 'completed')->sum('grand_total');
        $filteredOrdersCount = (clone $salesQuery)->where('sale_status', 'completed')->count();
        $cashSales = (float) (clone $salesQuery)->where('sale_status', 'completed')->where('payment_method', 'cash')->sum('paid_amount');
        $digitalSales = (float) (clone $salesQuery)->where('sale_status', 'completed')->whereIn('payment_method', ['bkash', 'nagad', 'card', 'rocket'])->sum('paid_amount');
        $dueSales = (float) (clone $salesQuery)->where('payment_status', 'due')->sum('grand_total');

        $salesList = $salesQuery->paginate(15, ['*'], 'sales_page');

        // 3. Online E-Commerce Orders assigned to this branch
        $shopOrders = ShopOrder::with(['items.product', 'user'])
            ->where('branch_id', $branch->id)
            ->orderBy('id', 'desc')
            ->take(20)
            ->get();

        // 4. Products & Stock (Integrated central products)
        $branchProducts = BranchInventory::with('product')
            ->where('branch_id', $branch->id)
            ->paginate(15, ['*'], 'products_page');

        // 5. Stock Movement History
        $stockMovements = StockMovement::with(['product', 'user'])
            ->where('branch_id', $branch->id)
            ->orderBy('id', 'desc')
            ->take(30)
            ->get();

        // 6. Stock Transfers (Sent & Received)
        $transfersSent = StockTransfer::with(['destinationBranch', 'items.product'])
            ->where('source_branch_id', $branch->id)
            ->orderBy('id', 'desc')
            ->take(15)
            ->get();

        $transfersReceived = StockTransfer::with(['sourceBranch', 'items.product'])
            ->where('destination_branch_id', $branch->id)
            ->orderBy('id', 'desc')
            ->take(15)
            ->get();

        $allOtherBranches = Branch::where('id', '!=', $branch->id)->where('status', 'active')->get();

        // 7. Branch Expenses
        $expenseCategories = ExpenseCategory::where('is_active', true)->get();
        $expenses = BranchExpense::with('category')
            ->where('branch_id', $branch->id)
            ->whereBetween('expense_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('expense_date', 'desc')
            ->get();
        $filteredTotalExpenses = $expenses->where('status', 'paid')->sum('amount');

        // 8. Financial Transactions Ledger & Cash Sessions
        $transactions = BranchFinancialTransaction::with(['user', 'approver'])
            ->where('branch_id', $branch->id)
            ->whereBetween('transaction_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('id', 'desc')
            ->take(30)
            ->get();

        $cashSessions = CashierShift::with(['cashier', 'counter'])
            ->where('branch_id', $branch->id)
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        // 9. Staff Management & Salary Structure
        $staffMembers = EmployeeProfile::with(['salaryStructure', 'salaryAdvances', 'leaves'])
            ->where('branch_id', $branch->id)
            ->get();

        // 10. Salary Payments & Payroll
        $salaryPayments = SalaryPayment::with('employee')
            ->where('branch_id', $branch->id)
            ->orderBy('payment_date', 'desc')
            ->take(20)
            ->get();

        $salaryAdvances = SalaryAdvance::with('employee')
            ->where('branch_id', $branch->id)
            ->orderBy('date', 'desc')
            ->take(20)
            ->get();

        // 11. Attendance & Leaves
        $attendances = EmployeeAttendance::with('employee')
            ->where('branch_id', $branch->id)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();

        $leaves = EmployeeLeave::with('employee')
            ->where('branch_id', $branch->id)
            ->orderBy('id', 'desc')
            ->take(20)
            ->get();

        // 12. Profit & Loss Financial Calculation (Section 15 & Screenshot 4 matching Bangladeshi Taka)
        $costOfSoldGoods = (float) PosSaleItem::whereHas('sale', function($q) use ($branch, $startDate, $endDate) {
            $q->where('branch_id', $branch->id)
              ->whereBetween('sale_time', [$startDate, $endDate])
              ->where('sale_status', 'completed');
        })->sum(DB::raw('cost_price * quantity'));

        if ($costOfSoldGoods <= 0 && $filteredSalesTotal > 0) {
            $costOfSoldGoods = $filteredSalesTotal * 0.75; // 75% standard retail COGS
        }

        $grossProfit = $filteredSalesTotal - $costOfSoldGoods;
        $netProfit = $grossProfit - ($filteredTotalExpenses);
        $profitMargin = $filteredSalesTotal > 0 ? round(($netProfit / $filteredSalesTotal) * 100, 1) : 0;
        $avgOrderValue = $filteredOrdersCount > 0 ? round($filteredSalesTotal / $filteredOrdersCount, 2) : 0;

        // Daily Trend for Revenue vs Expenses Chart (Matching Screenshot 4)
        $dailyTrends = [];
        $periodDays = min(31, $startDate->diffInDays($endDate) + 1);
        for ($i = 0; $i < $periodDays; $i++) {
            $curDate = (clone $startDate)->addDays($i);
            $dayStr = $curDate->format('d M');
            $dayDate = $curDate->toDateString();

            $daySales = (float) PosSale::where('branch_id', $branch->id)
                ->whereDate('sale_time', $dayDate)
                ->where('sale_status', 'completed')
                ->sum('grand_total');

            $dayExp = (float) BranchExpense::where('branch_id', $branch->id)
                ->whereDate('expense_date', $dayDate)
                ->where('status', 'paid')
                ->sum('amount');

            $dayOrders = PosSale::where('branch_id', $branch->id)
                ->whereDate('sale_time', $dayDate)
                ->where('sale_status', 'completed')
                ->count();

            $dailyTrends[] = [
                'day' => $dayStr,
                'revenue' => $daySales,
                'expense' => $dayExp,
                'orders' => $dayOrders
            ];
        }

        // Expense breakdown by category
        $expenseCategoriesBreakdown = [];
        foreach ($expenses->where('status', 'paid')->groupBy('category_id') as $catId => $catExpenses) {
            $catName = $catExpenses->first()->category ? $catExpenses->first()->category->name : 'General';
            $catSum = $catExpenses->sum('amount');
            $pct = $filteredTotalExpenses > 0 ? round(($catSum / $filteredTotalExpenses) * 100, 1) : 0;
            $expenseCategoriesBreakdown[] = [
                'name' => $catName,
                'amount' => $catSum,
                'percentage' => $pct
            ];
        }

        // 13. Top Selling Products in this branch
        $topProducts = PosSaleItem::whereHas('sale', function($q) use ($branch, $startDate, $endDate) {
                $q->where('branch_id', $branch->id)->whereBetween('sale_time', [$startDate, $endDate])->where('sale_status', 'completed');
            })
            ->select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total_price) as total_rev'), DB::raw('SUM(cost_price * quantity) as total_cost'))
            ->groupBy('product_name')
            ->orderBy('total_qty', 'desc')
            ->take(7)
            ->get();

        // 14. Activity Logs
        $activityLogs = BranchActivityLog::with('user')
            ->where('branch_id', $branch->id)
            ->orderBy('id', 'desc')
            ->take(30)
            ->get();

        $activeTab = $request->get('tab', 'overview');

        return view('admin.supershop.branches.show', compact(
            'branch',
            'dateFilter',
            'startDate',
            'endDate',
            'todaySales',
            'todayExpenses',
            'todayProfit',
            'monthlySales',
            'monthlyExpenses',
            'totalProductsCount',
            'totalStockUnits',
            'lowStockThreshold',
            'lowStockCount',
            'outOfStockCount',
            'totalStaffCount',
            'monthlySalaryExpense',
            'filteredSalesTotal',
            'filteredOrdersCount',
            'cashSales',
            'digitalSales',
            'dueSales',
            'salesList',
            'shopOrders',
            'branchProducts',
            'stockMovements',
            'transfersSent',
            'transfersReceived',
            'allOtherBranches',
            'expenseCategories',
            'expenses',
            'filteredTotalExpenses',
            'transactions',
            'cashSessions',
            'staffMembers',
            'salaryPayments',
            'salaryAdvances',
            'attendances',
            'leaves',
            'costOfSoldGoods',
            'grossProfit',
            'netProfit',
            'profitMargin',
            'avgOrderValue',
            'dailyTrends',
            'expenseCategoriesBreakdown',
            'topProducts',
            'activityLogs',
            'activeTab'
        ));
    }

    /**
     * Section 17: Branch Comparison Dashboard (Matching StoresAce, Franchise Financial & HashMicro)
     */
    public function comparison(Request $request)
    {
        $currentUser = Auth::user();
        if ($currentUser && $currentUser->role === 'manager') {
            $bId = $currentUser->branch_id ?: Branch::where('manager_id', $currentUser->id)->value('id');
            if ($bId) {
                return redirect()->route('admin.supershop.branches.show', $bId)
                    ->with('error', 'ব্রাঞ্চ পারফর্ম্যান্স তুলনা শুধুমাত্র কেন্দ্রীয় সুপার অ্যাডমিনদের জন্য সংরক্ষিত।');
            }
        }

        $dateFilter = $request->get('date_filter', 'this_month');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        $startDate = match ($dateFilter) {
            'today' => Carbon::today()->startOfDay(),
            'yesterday' => Carbon::yesterday()->startOfDay(),
            'this_week' => Carbon::today()->startOfWeek(),
            'this_month' => Carbon::today()->startOfMonth(),
            'this_year' => Carbon::today()->startOfYear(),
            'custom' => $fromDate ? Carbon::parse($fromDate)->startOfDay() : Carbon::today()->startOfMonth(),
            default => Carbon::today()->startOfMonth(),
        };

        $endDate = match ($dateFilter) {
            'yesterday' => Carbon::yesterday()->endOfDay(),
            'custom' => $toDate ? Carbon::parse($toDate)->endOfDay() : Carbon::today()->endOfDay(),
            default => Carbon::today()->endOfDay(),
        };

        $branches = Branch::with(['manager', 'employees'])->where('status', 'active')->get();

        $comparisonData = [];
        $totalNetworkSales = 0;
        $totalNetworkExpenses = 0;
        $totalNetworkNetProfit = 0;
        $totalNetworkOrders = 0;

        foreach ($branches as $b) {
            $sales = (float) PosSale::where('branch_id', $b->id)
                ->whereBetween('sale_time', [$startDate, $endDate])
                ->where('sale_status', 'completed')
                ->sum('grand_total');

            $orders = PosSale::where('branch_id', $b->id)
                ->whereBetween('sale_time', [$startDate, $endDate])
                ->where('sale_status', 'completed')
                ->count();

            $cogs = (float) PosSaleItem::whereHas('sale', function($q) use ($b, $startDate, $endDate) {
                $q->where('branch_id', $b->id)->whereBetween('sale_time', [$startDate, $endDate])->where('sale_status', 'completed');
            })->sum(DB::raw('cost_price * quantity'));

            if ($cogs <= 0 && $sales > 0) {
                $cogs = $sales * 0.75;
            }

            $expenses = (float) BranchExpense::where('branch_id', $b->id)
                ->whereBetween('expense_date', [$startDate->toDateString(), $endDate->toDateString()])
                ->where('status', 'paid')
                ->sum('amount');

            $staff = EmployeeProfile::where('branch_id', $b->id)->where('status', 'active')->count();
            $salary = (float) EmployeeProfile::where('branch_id', $b->id)->where('status', 'active')->sum('salary');

            $grossProfit = $sales - $cogs;
            $netProfit = $grossProfit - ($expenses);
            $margin = $sales > 0 ? round(($netProfit / $sales) * 100, 1) : 0;
            $avgTicket = $orders > 0 ? round($sales / $orders, 2) : 0;

            // Yearly sales for StoresAce style metric
            $yearlySales = (float) PosSale::where('branch_id', $b->id)
                ->whereBetween('sale_time', [Carbon::today()->startOfYear(), Carbon::now()])
                ->where('sale_status', 'completed')
                ->sum('grand_total');

            // Daily sales
            $dailySales = (float) PosSale::where('branch_id', $b->id)
                ->whereDate('sale_time', Carbon::today())
                ->where('sale_status', 'completed')
                ->sum('grand_total');

            $comparisonData[] = [
                'branch' => $b,
                'sales' => $sales,
                'orders' => $orders,
                'expenses' => $expenses,
                'cogs' => $cogs,
                'gross_profit' => $grossProfit,
                'net_profit' => $netProfit,
                'margin' => $margin,
                'avg_ticket' => $avgTicket,
                'staff' => $staff,
                'yearly_sales' => $yearlySales,
                'daily_sales' => $dailySales,
            ];

            $totalNetworkSales += $sales;
            $totalNetworkExpenses += $expenses;
            $totalNetworkNetProfit += $netProfit;
            $totalNetworkOrders += $orders;
        }

        // Sort by sales descending
        usort($comparisonData, fn($a, $b) => $b['sales'] <=> $a['sales']);

        return view('admin.supershop.branches.comparison', compact(
            'branches',
            'comparisonData',
            'dateFilter',
            'startDate',
            'endDate',
            'totalNetworkSales',
            'totalNetworkExpenses',
            'totalNetworkNetProfit',
            'totalNetworkOrders'
        ));
    }

    /**
     * Store new branch (Section 19)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'code' => 'required|string|max:50|unique:branches,code',
            'type' => 'required|string',
            'phone' => 'nullable|string|max:30',
            'address' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:3072',
            'manager_email' => 'nullable|email|unique:users,email',
            'manager_username' => 'nullable|string|max:100|unique:users,username',
            'manager_password' => 'nullable|string|min:6',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'branch_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $destination = public_path('upload/images/branches');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $filename);
            $logoPath = 'upload/images/branches/' . $filename;
        }

        $branch = Branch::create([
            'name' => $request->name,
            'code' => strtoupper(trim($request->code)),
            'type' => $request->type ?: 'super_shop',
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'division' => $request->division ?: 'Dhaka',
            'district' => $request->district ?: 'Dhaka',
            'area' => $request->area,
            'opening_time' => $request->opening_time ?: '08:00:00',
            'closing_time' => $request->closing_time ?: '22:30:00',
            'opening_cash' => $request->opening_cash ?: 10000,
            'low_stock_threshold' => $request->low_stock_threshold ?: 10,
            'expense_approval_limit' => $request->expense_approval_limit ?: 15000,
            'logo' => $logoPath,
            'status' => $request->status ?: 'active',
        ]);

        // Manager account creation with Login Email & Password
        $managerId = $request->manager_id;
        $managerEmail = trim($request->manager_email ?? '');
        $managerPassword = $request->manager_password;
        $managerUsername = trim($request->manager_username ?? '');

        if (($managerEmail !== '' || $managerUsername !== '') && !empty($managerPassword)) {
            if ($managerEmail === '') {
                $managerEmail = $managerUsername . '@ekrishisheba.com';
            }
            if ($managerUsername === '') {
                $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', explode('@', $managerEmail)[0]));
                if (empty($baseUsername)) {
                    $baseUsername = strtolower($branch->code) . '_mgr';
                }
                $managerUsername = $baseUsername;
                $counter = 1;
                while (User::where('username', $managerUsername)->exists()) {
                    $managerUsername = $baseUsername . $counter;
                    $counter++;
                }
            }

            $managerUser = User::create([
                'name' => $request->manager_name ?: ($branch->name . ' Manager'),
                'username' => $managerUsername,
                'email' => $managerEmail,
                'phone' => $request->manager_phone ?: $branch->phone,
                'password' => Hash::make($managerPassword),
                'role' => 'manager',
                'user_type' => 'internal',
                'status' => 'active',
                'is_active' => true,
                'branch_id' => $branch->id,
            ]);
            $managerId = $managerUser->id;
        } elseif ($managerId) {
            User::where('id', $managerId)->update(['branch_id' => $branch->id, 'role' => 'manager']);
        }

        if ($managerId) {
            $branch->manager_id = $managerId;
            $branch->save();
        }

        // Create Counter 01
        PosCounter::create([
            'branch_id' => $branch->id,
            'counter_name' => 'Counter 01',
            'counter_code' => $branch->code . '-C01',
            'status' => 'active',
        ]);

        // Auto map central products with default stock
        if ($request->has('assign_all_products')) {
            $products = ShopProduct::take(100)->get();
            foreach ($products as $p) {
                BranchInventory::create([
                    'branch_id' => $branch->id,
                    'product_id' => $p->id,
                    'stock_quantity' => 50,
                    'reorder_level' => 10,
                    'safety_stock' => 5,
                    'purchase_price' => round($p->price * 0.75, 2),
                    'is_active' => true,
                ]);
            }
        }

        BranchActivityLog::record($branch->id, 'branch_created', 'Branch', $branch->id, "শাখা '{$branch->name}' নতুনভাবে তৈরি করা হয়েছে।");

        return back()->with('success', "নতুন শাখা '{$branch->name}' ({$branch->code}) সফলভাবে তৈরি করা হয়েছে!");
    }

    /**
     * Update branch settings and operational limits (Section 21)
     */
    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:200',
            'code' => 'required|string|max:50|unique:branches,code,' . $branch->id,
            'phone' => 'nullable|string|max:30',
            'address' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:3072',
        ]);

        $updateData = [
            'name' => $request->name,
            'code' => strtoupper(trim($request->code)),
            'type' => $request->type ?: $branch->type,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'division' => $request->division,
            'district' => $request->district,
            'area' => $request->area,
            'opening_time' => $request->opening_time,
            'closing_time' => $request->closing_time,
            'opening_cash' => $request->opening_cash ?? $branch->opening_cash,
            'low_stock_threshold' => $request->low_stock_threshold ?? $branch->low_stock_threshold,
            'expense_approval_limit' => $request->expense_approval_limit ?? $branch->expense_approval_limit,
            'operating_hours' => $request->operating_hours ?? $branch->operating_hours,
            'status' => $request->status ?: 'active',
        ];

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'branch_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $destination = public_path('upload/images/branches');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $filename);
            $updateData['logo'] = 'upload/images/branches/' . $filename;
        }

        if ($request->filled('manager_id')) {
            $updateData['manager_id'] = $request->manager_id;
            User::where('id', $request->manager_id)->update(['branch_id' => $branch->id, 'role' => 'manager']);
        }

        $branch->update($updateData);

        BranchActivityLog::record($branch->id, 'branch_updated', 'Branch', $branch->id, "শাখার সাধারণ ও অপারেশনাল সেটিংস আপডেট করা হয়েছে।");

        return back()->with('success', "শাখা '{$branch->name}' এর তথ্য সফলভাবে আপডেট করা হয়েছে!");
    }

    /**
     * Stock Adjustment (Section 6.4)
     */
    public function adjustStock(Request $request, $id)
    {
        $request->validate([
            'inventory_id' => 'required|exists:branch_inventories,id',
            'type' => 'required|string', // stock_in, damage_out, adjustment, correction
            'quantity' => 'required|integer',
            'reason' => 'required|string',
        ]);

        $inventory = BranchInventory::with('product')->findOrFail($request->inventory_id);
        $oldStock = $inventory->stock_quantity;
        $qty = abs($request->quantity);

        if (in_array($request->type, ['stock_in', 'purchase_in', 'return_in'])) {
            $newStock = $oldStock + $qty;
            $movementQty = $qty;
        } else {
            if ($oldStock < $qty && $request->type != 'correction') {
                return back()->with('error', 'স্টকে পর্যাপ্ত পরিমাণ পণ্য নেই!');
            }
            $newStock = max(0, $oldStock - $qty);
            $movementQty = -$qty;
        }

        if ($request->type == 'correction') {
            $newStock = $qty;
            $movementQty = $qty - $oldStock;
        }

        $inventory->stock_quantity = $newStock;
        $inventory->save();

        // Record Stock Movement audit trail
        StockMovement::create([
            'branch_id' => $inventory->branch_id,
            'product_id' => $inventory->product_id,
            'type' => $request->type,
            'quantity' => $movementQty,
            'balance_after' => $newStock,
            'reference_no' => 'ADJ-' . time(),
            'user_id' => Auth::id(),
            'notes' => $request->reason,
        ]);

        BranchActivityLog::record($inventory->branch_id, 'stock_adjusted', 'Product', $inventory->product_id, "প্রোডাক্ট '{$inventory->product->name}' এর স্টক অ্যাডজাস্ট করা হয়েছে (পূর্ববর্তী: {$oldStock}, নতুন: {$newStock})। কারণ: {$request->reason}");

        return back()->with('success', "প্রোডাক্টের স্টক সফলভাবে সমন্বয় করা হয়েছে!");
    }

    /**
     * Create Stock Transfer (Section 7)
     */
    public function storeTransfer(Request $request)
    {
        $request->validate([
            'source_branch_id' => 'required|exists:branches,id',
            'destination_branch_id' => 'required|exists:branches,id|different:source_branch_id',
            'product_id' => 'required|exists:shop_products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        // Check source branch stock
        $sourceInv = BranchInventory::where('branch_id', $request->source_branch_id)
            ->where('product_id', $request->product_id)
            ->first();

        if (!$sourceInv || $sourceInv->stock_quantity < $request->quantity) {
            return back()->with('error', 'উৎস ব্রাঞ্চে (Source Branch) পর্যাপ্ত স্টক নেই!');
        }

        $transferNo = 'TR-' . Carbon::now()->format('ymd') . '-' . sprintf('%04d', rand(100, 9999));

        $transfer = StockTransfer::create([
            'transfer_number' => $transferNo,
            'source_branch_id' => $request->source_branch_id,
            'destination_branch_id' => $request->destination_branch_id,
            'request_date' => Carbon::now()->toDateString(),
            'status' => 'pending_approval',
            'requested_by' => Auth::id(),
            'notes' => $request->notes,
        ]);

        StockTransferItem::create([
            'transfer_id' => $transfer->id,
            'product_id' => $request->product_id,
            'requested_qty' => $request->quantity,
            'dispatched_qty' => $request->quantity,
            'received_qty' => 0,
        ]);

        BranchActivityLog::record($request->source_branch_id, 'transfer_requested', 'StockTransfer', $transfer->id, "ব্রাঞ্চ স্টক ট্রান্সফার রিকোয়েস্ট #{$transferNo} তৈরি করা হয়েছে। পরিমাণ: {$request->quantity} টি।");

        return back()->with('success', "স্টক ট্রান্সফার রিকোয়েস্ট #{$transferNo} সফলভাবে তৈরি করা হয়েছে!");
    }

    /**
     * Approve and Dispatch Stock Transfer (Section 7)
     */
    public function dispatchTransfer(Request $request, $id)
    {
        $transfer = StockTransfer::with('items')->findOrFail($id);

        if ($transfer->status !== 'pending_approval') {
            return back()->with('error', 'এই ট্রান্সফারটি ইতিমধ্যে প্রসেস করা হয়েছে।');
        }

        // Deduct from source branch
        foreach ($transfer->items as $item) {
            $sourceInv = BranchInventory::where('branch_id', $transfer->source_branch_id)
                ->where('product_id', $item->product_id)
                ->first();

            if ($sourceInv) {
                $sourceInv->stock_quantity = max(0, $sourceInv->stock_quantity - $item->dispatched_qty);
                $sourceInv->save();

                StockMovement::create([
                    'branch_id' => $transfer->source_branch_id,
                    'product_id' => $item->product_id,
                    'type' => 'transfer_out',
                    'quantity' => -$item->dispatched_qty,
                    'balance_after' => $sourceInv->stock_quantity,
                    'reference_no' => $transfer->transfer_number,
                    'user_id' => Auth::id(),
                    'notes' => "Dispatched to Destination Branch",
                ]);
            }
        }

        $transfer->status = 'in_transit';
        $transfer->dispatch_date = Carbon::now()->toDateString();
        $transfer->dispatched_by = Auth::id();
        $transfer->save();

        BranchActivityLog::record($transfer->source_branch_id, 'transfer_dispatched', 'StockTransfer', $transfer->id, "স্টক ট্রান্সফার #{$transfer->transfer_number} অনুমোদন ও ডিসপ্যাচ করা হয়েছে (ইন-ট্রানজিট)।");

        return back()->with('success', "ট্রান্সফার #{$transfer->transfer_number} সফলভাবে ডিসপ্যাচ করা হয়েছে!");
    }

    /**
     * Receive Stock Transfer (Section 7)
     */
    public function receiveTransfer(Request $request, $id)
    {
        $transfer = StockTransfer::with('items.product')->findOrFail($id);

        if ($transfer->status !== 'in_transit') {
            return back()->with('error', 'শুধুমাত্র ইন-ট্রানজিট থাকা ট্রান্সফার রিসিভ করা সম্ভব।');
        }

        // Add to destination branch
        foreach ($transfer->items as $item) {
            $destInv = BranchInventory::firstOrCreate(
                ['branch_id' => $transfer->destination_branch_id, 'product_id' => $item->product_id],
                [
                    'stock_quantity' => 0,
                    'reorder_level' => 10,
                    'safety_stock' => 5,
                    'purchase_price' => round($item->product->price * 0.75, 2),
                    'is_active' => true,
                ]
            );

            $destInv->stock_quantity += $item->dispatched_qty;
            $destInv->save();

            $item->received_qty = $item->dispatched_qty;
            $item->save();

            StockMovement::create([
                'branch_id' => $transfer->destination_branch_id,
                'product_id' => $item->product_id,
                'type' => 'transfer_in',
                'quantity' => $item->dispatched_qty,
                'balance_after' => $destInv->stock_quantity,
                'reference_no' => $transfer->transfer_number,
                'user_id' => Auth::id(),
                'notes' => "Received from Source Branch",
            ]);
        }

        $transfer->status = 'received';
        $transfer->received_date = Carbon::now()->toDateString();
        $transfer->received_by = Auth::id();
        $transfer->save();

        BranchActivityLog::record($transfer->destination_branch_id, 'transfer_received', 'StockTransfer', $transfer->id, "স্টক ট্রান্সফার #{$transfer->transfer_number} সফলভাবে রিসিভ করা হয়েছে ও স্টক ইন হয়েছে।");

        return back()->with('success', "ট্রান্সফার #{$transfer->transfer_number} সফলভাবে রিসিভ ও স্টক সমন্বয় সম্পন্ন হয়েছে!");
    }

    /**
     * Create Branch Expense (Section 8)
     */
    public function storeExpense(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'category_id' => 'required|exists:expense_categories,id',
            'title' => 'required|string|max:200',
            'amount' => 'required|numeric|min:1',
            'expense_date' => 'required|date',
            'payment_method' => 'required|string',
            'receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096',
            'description' => 'nullable|string',
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $filename = 'exp_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $destination = public_path('upload/expenses');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $filename);
            $receiptPath = 'upload/expenses/' . $filename;
        }

        $branch = Branch::findOrFail($request->branch_id);
        $approvalLimit = $branch->expense_approval_limit ?: 15000;
        $status = ($request->amount > $approvalLimit && Auth::user()->role !== 'super_admin') ? 'pending' : 'paid';

        $expense = BranchExpense::create([
            'branch_id' => $request->branch_id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'payment_method' => $request->payment_method,
            'description' => $request->description,
            'receipt_attachment' => $receiptPath,
            'created_by' => Auth::id(),
            'approved_by' => ($status === 'paid') ? Auth::id() : null,
            'status' => $status,
        ]);

        if ($status === 'paid') {
            BranchFinancialTransaction::create([
                'branch_id' => $request->branch_id,
                'transaction_date' => $request->expense_date,
                'transaction_type' => 'expense',
                'in_amount' => 0,
                'out_amount' => $request->amount,
                'balance' => -$request->amount,
                'payment_method' => $request->payment_method,
                'reference_no' => 'EXP-' . $expense->id,
                'reference_type' => 'BranchExpense',
                'reference_id' => $expense->id,
                'user_id' => Auth::id(),
                'description' => "Expense: {$request->title}",
            ]);
        }

        BranchActivityLog::record($request->branch_id, 'expense_created', 'BranchExpense', $expense->id, "শাখার খরচ '{$request->title}' (৳ {$request->amount}) এন্ট্রি করা হয়েছে। স্ট্যাটাস: {$status}");

        return back()->with('success', "খরচের এন্ট্রি সফলভাবে সম্পন্ন হয়েছে!");
    }

    /**
     * Cash Management Ledger Transaction (Section 9.1 & 9.2)
     */
    public function storeTransaction(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'transaction_type' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'description' => 'required|string',
        ]);

        $isIn = in_array($request->transaction_type, ['opening_cash', 'cash_sale', 'cash_deposit', 'adjustment']);
        $inAmount = $isIn ? $request->amount : 0;
        $outAmount = !$isIn ? $request->amount : 0;

        BranchFinancialTransaction::create([
            'branch_id' => $request->branch_id,
            'transaction_date' => Carbon::now()->toDateString(),
            'transaction_type' => $request->transaction_type,
            'in_amount' => $inAmount,
            'out_amount' => $outAmount,
            'balance' => $inAmount - $outAmount,
            'payment_method' => $request->payment_method,
            'reference_no' => 'TXN-' . time(),
            'reference_type' => 'Manual',
            'user_id' => Auth::id(),
            'description' => $request->description,
        ]);

        BranchActivityLog::record($request->branch_id, 'cash_transaction', 'Transaction', null, "ক্যাশ লেনদেন সম্পন্ন: {$request->transaction_type} (৳ {$request->amount})। বিবরণ: {$request->description}");

        return back()->with('success', "আর্থিক লেনদেন সফলভাবে লেজারে যুক্ত হয়েছে!");
    }

    /**
     * Daily Cash Session Closing (Section 9.3)
     */
    public function closeCashSession(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'actual_cash' => 'required|numeric|min:0',
            'closing_notes' => 'nullable|string',
        ]);

        $branch = Branch::findOrFail($request->branch_id);
        $today = Carbon::today();

        $todayCashSales = (float) PosSale::where('branch_id', $branch->id)
            ->whereDate('sale_time', $today)
            ->where('sale_status', 'completed')
            ->where('payment_method', 'cash')
            ->sum('paid_amount');

        $todayCashExpenses = (float) BranchExpense::where('branch_id', $branch->id)
            ->whereDate('expense_date', $today)
            ->where('status', 'paid')
            ->where('payment_method', 'cash')
            ->sum('amount');

        $openingCash = $branch->opening_cash ?: 10000;
        $expectedCash = $openingCash + $todayCashSales - $todayCashExpenses;
        $actualCash = (float) $request->actual_cash;
        $difference = $actualCash - $expectedCash;

        CashierShift::create([
            'branch_id' => $branch->id,
            'cashier_id' => Auth::id(),
            'opened_at' => Carbon::today()->startOfDay(),
            'closed_at' => Carbon::now(),
            'opening_cash' => $openingCash,
            'expected_cash' => $expectedCash,
            'actual_cash' => $actualCash,
            'cash_difference' => $difference,
            'total_cash_sales' => $todayCashSales,
            'total_sales_amount' => $todayCashSales,
            'closing_notes' => $request->closing_notes . ($difference != 0 ? " [ক্যাশ পার্থক্য: ৳ {$difference}]" : ""),
            'verified_by' => Auth::id(),
            'status' => 'closed',
        ]);

        BranchActivityLog::record($branch->id, 'cash_closed', 'CashierShift', null, "দৈনিক ক্যাশ ক্লোজিং সম্পন্ন। প্রত্যাশিত: ৳ {$expectedCash}, বাস্তব ক্যাশ: ৳ {$actualCash}, পার্থক্য: ৳ {$difference}");

        return back()->with('success', "দৈনিক ব্রাঞ্চ ক্যাশ ক্লোজিং সফলভাবে সম্পন্ন হয়েছে!");
    }

    /**
     * Staff Management (Section 10)
     */
    public function storeStaff(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:150',
            'employee_code' => 'required|string|max:50|unique:employee_profiles,employee_code',
            'designation' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'salary' => 'required|numeric|min:0',
            'joining_date' => 'nullable|date',
        ]);

        $emp = EmployeeProfile::create([
            'branch_id' => $request->branch_id,
            'employee_code' => strtoupper(trim($request->employee_code)),
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'designation' => $request->designation,
            'salary' => $request->salary,
            'joining_date' => $request->joining_date ?: Carbon::now()->toDateString(),
            'status' => 'active',
        ]);

        // Auto create standard salary structure
        SalaryStructure::create([
            'employee_id' => $emp->id,
            'branch_id' => $request->branch_id,
            'basic_salary' => round($request->salary * 0.65, 2),
            'house_allowance' => round($request->salary * 0.20, 2),
            'transport_allowance' => round($request->salary * 0.08, 2),
            'food_allowance' => round($request->salary * 0.07, 2),
            'medical_allowance' => 1000,
            'bonus' => 0,
            'deduction' => 0,
            'net_salary' => $request->salary + 1000,
        ]);

        BranchActivityLog::record($request->branch_id, 'staff_created', 'EmployeeProfile', $emp->id, "নতুন কর্মচারী '{$emp->name}' ({$emp->employee_code}) - {$emp->designation} যুক্ত করা হয়েছে।");

        return back()->with('success', "কর্মচারী '{$emp->name}' সফলভাবে যুক্ত হয়েছে!");
    }

    /**
     * Salary Payment (Section 11.3)
     */
    public function paySalary(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employee_profiles,id',
            'salary_month' => 'required|string',
            'paid_amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $emp = EmployeeProfile::findOrFail($request->employee_id);
        $struct = SalaryStructure::where('employee_id', $emp->id)->first();

        $basic = $struct ? $struct->basic_salary : round($emp->salary * 0.65, 2);
        $allowance = $struct ? ($struct->house_allowance + $struct->transport_allowance + $struct->food_allowance + $struct->medical_allowance) : round($emp->salary * 0.35, 2);

        $payment = SalaryPayment::create([
            'employee_id' => $emp->id,
            'branch_id' => $emp->branch_id,
            'salary_month' => $request->salary_month,
            'basic_salary' => $basic,
            'allowance' => $allowance,
            'bonus' => 0,
            'deduction' => 0,
            'advance_adjustment' => 0,
            'net_salary' => $emp->salary,
            'paid_amount' => $request->paid_amount,
            'payment_date' => Carbon::now()->toDateString(),
            'payment_method' => $request->payment_method,
            'paid_by' => Auth::id(),
            'approved_by' => Auth::id(),
            'status' => 'paid',
            'remarks' => $request->remarks ?: "Salary payment for month {$request->salary_month}",
        ]);

        // Record as financial transaction
        BranchFinancialTransaction::create([
            'branch_id' => $emp->branch_id,
            'transaction_date' => Carbon::now()->toDateString(),
            'transaction_type' => 'salary_payment',
            'in_amount' => 0,
            'out_amount' => $request->paid_amount,
            'balance' => -$request->paid_amount,
            'payment_method' => $request->payment_method,
            'reference_no' => 'SAL-' . $payment->id,
            'reference_type' => 'SalaryPayment',
            'reference_id' => $payment->id,
            'user_id' => Auth::id(),
            'description' => "Salary paid to {$emp->name} ({$request->salary_month})",
        ]);

        BranchActivityLog::record($emp->branch_id, 'salary_paid', 'SalaryPayment', $payment->id, "কর্মচারী '{$emp->name}' এর {$request->salary_month} মাসের বেতন ৳ {$request->paid_amount} পরিশোধ করা হয়েছে।");

        return back()->with('success', "বেতন সফলভাবে পরিশোধ করা হয়েছে!");
    }

    /**
     * Salary Advance (Section 11.5)
     */
    public function storeSalaryAdvance(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employee_profiles,id',
            'advance_amount' => 'required|numeric|min:1',
            'reason' => 'required|string',
            'deduction_month' => 'required|string',
        ]);

        $emp = EmployeeProfile::findOrFail($request->employee_id);

        $advance = SalaryAdvance::create([
            'employee_id' => $emp->id,
            'branch_id' => $emp->branch_id,
            'advance_amount' => $request->advance_amount,
            'advance_balance' => $request->advance_amount,
            'date' => Carbon::now()->toDateString(),
            'reason' => $request->reason,
            'deduction_month' => $request->deduction_month,
            'approved_by' => Auth::id(),
            'status' => 'approved',
        ]);

        // Financial transaction out
        BranchFinancialTransaction::create([
            'branch_id' => $emp->branch_id,
            'transaction_date' => Carbon::now()->toDateString(),
            'transaction_type' => 'salary_advance',
            'in_amount' => 0,
            'out_amount' => $request->advance_amount,
            'balance' => -$request->advance_amount,
            'payment_method' => 'cash',
            'reference_no' => 'ADV-' . $advance->id,
            'reference_type' => 'SalaryAdvance',
            'reference_id' => $advance->id,
            'user_id' => Auth::id(),
            'description' => "Salary Advance for {$emp->name}",
        ]);

        BranchActivityLog::record($emp->branch_id, 'salary_advance', 'SalaryAdvance', $advance->id, "কর্মচারী '{$emp->name}' কে বেতন অগ্রিম ৳ {$request->advance_amount} প্রদান করা হয়েছে।");

        return back()->with('success', "বেতন অগ্রিম সফলভাবে অনুমোদন ও প্রদান করা হয়েছে!");
    }

    /**
     * Branch Order Assignment (Section 13)
     */
    public function assignOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:shop_orders,id',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $order = ShopOrder::with('items')->findOrFail($request->order_id);
        $branch = Branch::findOrFail($request->branch_id);

        $order->branch_id = $branch->id;
        $order->assigned_by = Auth::id();
        $order->assigned_at = Carbon::now();
        $order->branch_fulfillment_status = 'assigned';
        $order->save();

        BranchActivityLog::record($branch->id, 'order_assigned', 'ShopOrder', $order->id, "ই-কমার্স অর্ডার #{$order->order_number} (৳ {$order->total_amount}) এই ব্রাঞ্চে ফুলফিলমেন্টের জন্য বরাদ্দ করা হয়েছে।");

        return back()->with('success', "অর্ডার #{$order->order_number} সফলভাবে '{$branch->name}' ব্রাঞ্চে অ্যাসাইন করা হয়েছে!");
    }

    /**
     * Reset Manager Password
     */
    public function resetManagerPassword(Request $request, $id)
    {
        $branch = Branch::with('manager')->findOrFail($id);

        $request->validate([
            'new_password' => 'required|string|min:6',
        ]);

        if (!$branch->manager) {
            return back()->with('error', "এই শাখায় কোনো ম্যানেজার অ্যাসাইন করা নেই।");
        }

        $branch->manager->password = Hash::make($request->new_password);
        $branch->manager->save();

        BranchActivityLog::record($branch->id, 'password_reset', 'User', $branch->manager_id, "ব্রাঞ্চ ম্যানেজার পাসওয়ার্ড রিসেট করা হয়েছে।");

        return back()->with('success', "ম্যানেজার '{$branch->manager->username}' এর পাসওয়ার্ড সফলভাবে রিসেট করা হয়েছে!");
    }

    /**
     * Destroy / Archive Branch
     */
    public function destroy($id)
    {
        $currentUser = Auth::user();
        if ($currentUser && $currentUser->role === 'manager') {
            return back()->with('error', 'ব্রাঞ্চ ডিলিট করার অনুমতি শুধুমাত্র কেন্দ্রীয় অ্যাডমিনের রয়েছে।');
        }

        $branch = Branch::withCount('posSales')->findOrFail($id);

        if ($branch->pos_sales_count > 0) {
            $branch->status = 'inactive';
            $branch->save();
            return back()->with('warning', "শাখাটির বিক্রয় রেকর্ড থাকায় স্থায়ীভাবে ডিলিট না করে সফলভাবে নিষ্ক্রিয় (Inactive/Archived) করা হয়েছে।");
        }

        $branch->delete();
        return back()->with('success', 'শাখাটি সফলভাবে মুছে ফেলা হয়েছে!');
    }
}
