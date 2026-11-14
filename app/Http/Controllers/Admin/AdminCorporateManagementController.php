<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardMember;
use App\Models\CorporateProfile;
use App\Models\CorporateActivity;
use App\Models\InvestmentSector;
use App\Models\InvestmentProject;
use App\Models\UserInvestment;
use App\Models\InvestmentDividend;
use App\Models\InvestmentWithdrawal;
use App\Models\ShareSetting;
use App\Models\ShareholderProfile;
use App\Models\ShareTransaction;
use App\Models\Partnership;
use App\Models\CentralFinance;
use App\Models\Notice;
use App\Models\Grievance;
use App\Models\AgentInventoryBalance;
use App\Models\HRM\HrmProvidentFund;
use App\Models\HRM\HrmEmployeeProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminCorporateManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin,admin,chairman,managing_director,ceo,finance_admin,investment_manager,hr_manager,marketing_manager']);
    }

    // =========================================================================
    // 1. BOARD OF DIRECTORS MANAGEMENT
    // =========================================================================
    public function boardMembersIndex()
    {
        $members = BoardMember::orderBy('order_num', 'asc')->get();
        return view('admin.corporate.board_members', compact('members'));
    }

    public function storeBoardMember(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'role_type' => 'required|string',
        ]);

        $data = $request->except(['_token', 'photo']);

        if ($request->hasFile('photo')) {
            $fileName = 'bm_' . time() . '.' . $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->move(public_path('upload/board'), $fileName);
            $data['photo'] = 'upload/board/' . $fileName;
        }

        $data['is_active'] = $request->has('is_active');
        BoardMember::create($data);

        return back()->with('success', 'পরিচালনা পর্ষদের সদস্য সফলভাবে যুক্ত হয়েছে।');
    }

    public function updateBoardMember(Request $request, $id)
    {
        $member = BoardMember::findOrFail($id);
        $data = $request->except(['_token', 'photo']);

        if ($request->hasFile('photo')) {
            $fileName = 'bm_' . time() . '.' . $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->move(public_path('upload/board'), $fileName);
            $data['photo'] = 'upload/board/' . $fileName;
        }

        $data['is_active'] = $request->has('is_active');
        $member->update($data);

        return back()->with('success', 'তথ্য সফলভাবে আপডেট করা হয়েছে।');
    }

    public function destroyBoardMember($id)
    {
        BoardMember::findOrFail($id)->delete();
        return back()->with('success', 'সদস্য তালিকা থেকে অপসারিত হয়েছে।');
    }

    // =========================================================================
    // 2. CORPORATE PROFILE CMS (History, Vision, Mission, Goals, Plans, etc.)
    // =========================================================================
    public function corporateProfileCms()
    {
        $sections = CorporateProfile::all();
        return view('admin.corporate.profile_cms', compact('sections'));
    }

    public function updateProfileSection(Request $request, $id)
    {
        $section = CorporateProfile::findOrFail($id);
        $section->update([
            'title_bn' => $request->title_bn,
            'title_en' => $request->title_en,
            'content_bn' => $request->content_bn,
            'content_en' => $request->content_en,
        ]);

        return back()->with('success', 'করপোরেট প্রোফাইল সেকশন সফলভাবে আপডেট হয়েছে।');
    }

    // =========================================================================
    // 3. CORPORATE ACTIVITIES & PROJECT IMPLEMENTATION
    // =========================================================================
    public function activitiesIndex()
    {
        $activities = CorporateActivity::latest()->paginate(15);
        return view('admin.corporate.activities', compact('activities'));
    }

    public function storeActivity(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
        ]);

        $code = 'ACT-' . strtoupper(Str::random(4)) . '-' . rand(100, 999);
        $data = $request->except(['_token', 'featured_image']);
        $data['project_code'] = $code;

        if ($request->hasFile('featured_image')) {
            $fileName = 'act_' . time() . '.' . $request->file('featured_image')->getClientOriginalExtension();
            $request->file('featured_image')->move(public_path('upload/activities'), $fileName);
            $data['featured_image'] = 'upload/activities/' . $fileName;
        }

        CorporateActivity::create($data);
        return back()->with('success', 'নতুন প্রকল্প/কার্যক্রম সফলভাবে তৈরি হয়েছে।');
    }

    public function updateActivity(Request $request, $id)
    {
        $act = CorporateActivity::findOrFail($id);
        $data = $request->except(['_token', 'featured_image']);

        if ($request->hasFile('featured_image')) {
            $fileName = 'act_' . time() . '.' . $request->file('featured_image')->getClientOriginalExtension();
            $request->file('featured_image')->move(public_path('upload/activities'), $fileName);
            $data['featured_image'] = 'upload/activities/' . $fileName;
        }

        $act->update($data);
        return back()->with('success', 'প্রকল্পের তথ্য সফলভাবে হালনাগাদ হয়েছে।');
    }

    public function destroyActivity($id)
    {
        CorporateActivity::findOrFail($id)->delete();
        return back()->with('success', 'প্রকল্প তালিকা থেকে মুছে ফেলা হয়েছে।');
    }

    // =========================================================================
    // 4. INVESTMENT SECTORS & PROJECTS (10 Sectors & Sub-projects)
    // =========================================================================
    public function sectorsIndex()
    {
        $sectors = InvestmentSector::withCount('projects')->orderBy('order_num', 'asc')->get();
        return view('admin.corporate.sectors', compact('sectors'));
    }

    public function updateSector(Request $request, $id)
    {
        $sector = InvestmentSector::findOrFail($id);
        $sector->update($request->only([
            'name_bn', 'name_en', 'investment_range', 'min_amount', 'max_amount', 'duration', 'description', 'profit_loss_policy', 'is_active'
        ]));

        return back()->with('success', 'বিনিয়োগ খাত সফলভাবে আপডেট হয়েছে।');
    }

    public function projectsIndex()
    {
        $sectors = InvestmentSector::active()->get();
        $projects = InvestmentProject::with('sector')->latest()->paginate(15);
        return view('admin.corporate.investment_projects', compact('projects', 'sectors'));
    }

    public function storeProject(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'sector_id' => 'required|exists:investment_sectors,id',
            'target_budget' => 'required|numeric',
        ]);

        $code = 'PRJ-' . strtoupper(Str::random(4)) . '-' . rand(100, 999);
        $data = $request->except(['_token', 'featured_image']);
        $data['project_id_code'] = $code;

        if ($request->hasFile('featured_image')) {
            $fileName = 'prj_' . time() . '.' . $request->file('featured_image')->getClientOriginalExtension();
            $request->file('featured_image')->move(public_path('upload/projects'), $fileName);
            $data['featured_image'] = 'upload/projects/' . $fileName;
        }

        InvestmentProject::create($data);
        return back()->with('success', 'বিনিয়োগ প্রকল্প সফলভাবে তৈরি হয়েছে।');
    }

    public function updateProject(Request $request, $id)
    {
        $project = InvestmentProject::findOrFail($id);
        $data = $request->except(['_token', 'featured_image']);

        if ($request->hasFile('featured_image')) {
            $fileName = 'prj_' . time() . '.' . $request->file('featured_image')->getClientOriginalExtension();
            $request->file('featured_image')->move(public_path('upload/projects'), $fileName);
            $data['featured_image'] = 'upload/projects/' . $fileName;
        }

        $project->update($data);
        return back()->with('success', 'প্রকল্পের তথ্য সফলভাবে আপডেট হয়েছে।');
    }

    public function destroyProject($id)
    {
        InvestmentProject::findOrFail($id)->delete();
        return back()->with('success', 'প্রকল্প মুছে ফেলা হয়েছে।');
    }

    // =========================================================================
    // 5. USER INVESTMENTS, DIVIDENDS & WITHDRAWAL MANAGEMENT
    // =========================================================================
    public function investmentsIndex()
    {
        $investments = UserInvestment::with(['user', 'project', 'sector'])->latest()->paginate(20);
        $totalInvested = UserInvestment::where('status', 'active')->sum('amount');
        $totalDividendsPaid = UserInvestment::sum('paid_dividend');
        $pendingCount = UserInvestment::where('status', 'pending')->count();

        return view('admin.corporate.investments_index', compact('investments', 'totalInvested', 'totalDividendsPaid', 'pendingCount'));
    }

    public function updateInvestmentStatus(Request $request, $id)
    {
        $inv = UserInvestment::findOrFail($id);
        $oldStatus = $inv->status;
        $inv->status = $request->status;
        $inv->admin_notes = $request->admin_notes;
        $inv->save();

        if ($request->status === 'active' && $oldStatus !== 'active') {
            // Update project total raised and investor count
            if ($inv->project_id) {
                $proj = InvestmentProject::find($inv->project_id);
                if ($proj) {
                    $proj->increment('total_raised', $inv->amount);
                    $proj->increment('investors_count');
                }
            }
            // Record Central Finance income
            CentralFinance::create([
                'transaction_code' => 'CF-INV-' . rand(10000, 99999),
                'entry_type' => 'income',
                'category' => 'investment_collection',
                'amount' => $inv->amount,
                'entry_date' => now()->toDateString(),
                'payment_method' => $inv->payment_method ?: 'bank_transfer',
                'reference_type' => 'user_investment',
                'reference_id' => $inv->id,
                'description' => 'বিনিয়োগ কোড ' . $inv->investment_code . ' বাবদ মূলধন প্রাপ্তি',
                'created_by' => Auth::id(),
            ]);
        }

        return back()->with('success', 'বিনিয়োগ স্ট্যাটাস সফলভাবে আপডেট করা হয়েছে।');
    }

    public function declareDividend(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $inv = UserInvestment::findOrFail($id);
        $inv->increment('return_earned', $request->amount);
        $inv->increment('pending_dividend', $request->amount);

        InvestmentDividend::create([
            'user_investment_id' => $inv->id,
            'user_id' => $inv->user_id,
            'amount' => $request->amount,
            'declaration_date' => now()->toDateString(),
            'status' => 'payable',
            'remarks' => $request->remarks ?: 'প্রকল্পভিত্তিক লাভ বণ্টন ঘোষণা',
        ]);

        return back()->with('success', 'লভ্যাংশ সফলভাবে ঘোষণা করা হয়েছে।');
    }

    public function markDividendPaid(Request $request, $dividendId)
    {
        $div = InvestmentDividend::findOrFail($dividendId);
        $div->update([
            'status' => 'paid',
            'payment_date' => now()->toDateString(),
            'payout_method' => $request->payout_method ?: 'wallet',
            'transaction_reference' => $request->transaction_reference ?: 'PAY-' . rand(10000, 99999),
        ]);

        // Credit user wallet or adjust
        $user = User::find($div->user_id);
        if ($user) {
            $user->increment('balance', $div->amount);
        }

        $inv = UserInvestment::find($div->user_investment_id);
        if ($inv) {
            $inv->increment('paid_dividend', $div->amount);
            $inv->decrement('pending_dividend', min($inv->pending_dividend, $div->amount));
        }

        // Record Central Finance expense
        CentralFinance::create([
            'transaction_code' => 'CF-DIV-' . rand(10000, 99999),
            'entry_type' => 'expense',
            'category' => 'profit_payout',
            'amount' => $div->amount,
            'entry_date' => now()->toDateString(),
            'payment_method' => $request->payout_method ?: 'wallet',
            'reference_type' => 'investment_dividend',
            'reference_id' => $div->id,
            'description' => 'বিনিয়োগকারী ' . ($user->name ?? '') . '-কে লভ্যাংশ পরিশোধ',
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'লভ্যাংশ সফলভাবে পরিশোধিত হিসেবে চিহ্নিত হয়েছে।');
    }

    public function withdrawalsIndex()
    {
        $withdrawals = InvestmentWithdrawal::with(['user', 'investment'])->latest()->paginate(20);
        return view('admin.corporate.withdrawals_index', compact('withdrawals'));
    }

    public function updateWithdrawalStatus(Request $request, $id)
    {
        $w = InvestmentWithdrawal::findOrFail($id);
        $w->status = $request->status;
        $w->admin_remarks = $request->admin_remarks;
        $w->transaction_reference = $request->transaction_reference;

        if ($request->status === 'approved') {
            $w->processed_at = now();

            // Record Central Finance expense
            CentralFinance::create([
                'transaction_code' => 'CF-WTH-' . rand(10000, 99999),
                'entry_type' => 'expense',
                'category' => 'customer_withdrawal',
                'amount' => $w->amount,
                'entry_date' => now()->toDateString(),
                'payment_method' => $w->payment_method,
                'reference_type' => 'investment_withdrawal',
                'reference_id' => $w->id,
                'description' => 'উত্তোলন কোড: ' . $w->withdrawal_code . ' বাবদ ফান্ড পরিশোধ',
                'created_by' => Auth::id(),
            ]);
        }

        $w->save();
        return back()->with('success', 'উত্তোলন আবেদন আপডেট করা হয়েছে।');
    }

    // =========================================================================
    // 6. SHAREHOLDER & SHARE CAPITAL MANAGEMENT (Module 4)
    // =========================================================================
    public function shareholdersIndex()
    {
        $shareSetting = ShareSetting::current();
        $shareholders = ShareholderProfile::with('user')->latest()->paginate(20);
        $totalCapital = ShareholderProfile::sum('total_investment');
        $totalOfficeFee = ShareholderProfile::sum('office_expense_paid');
        $allocatedShares = ShareholderProfile::sum('shares_count');

        // Update allocated shares in settings
        $shareSetting->allocated_shares = $allocatedShares;
        $shareSetting->save();

        return view('admin.corporate.shareholders_index', compact('shareSetting', 'shareholders', 'totalCapital', 'totalOfficeFee', 'allocatedShares'));
    }

    public function storeShareholder(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'shares_count' => 'required|integer|min:1|max:100',
        ]);

        $shareSetting = ShareSetting::current();
        $pricePerShare = $shareSetting->price_per_share;
        $officeExpensePerShare = $shareSetting->office_expense_per_share;
        $sharesCount = $request->shares_count;

        $totalInvestment = $sharesCount * $pricePerShare;
        $totalOfficeExpense = $sharesCount * $officeExpensePerShare;
        $code = 'SH-' . date('Y') . '-' . rand(10000, 99999);

        $sh = ShareholderProfile::create([
            'user_id' => $request->user_id,
            'shareholder_code' => $code,
            'shares_count' => $sharesCount,
            'total_investment' => $totalInvestment,
            'office_expense_paid' => $totalOfficeExpense,
            'registration_fee_paid' => $shareSetting->registration_fee,
            'nominee_name' => $request->nominee_name,
            'nominee_phone' => $request->nominee_phone,
            'nominee_relation' => $request->nominee_relation,
            'nid_number' => $request->nid_number,
            'status' => 'active',
        ]);

        // Update User role & customer_id
        $user = User::find($request->user_id);
        $user->update([
            'customer_id' => $user->customer_id ?: $code,
            'role' => 'shareholder',
            'membership_card_type' => 'shareholder',
        ]);

        // Record Share Transaction
        ShareTransaction::create([
            'shareholder_id' => $sh->id,
            'user_id' => $user->id,
            'transaction_type' => 'purchase',
            'shares_count' => $sharesCount,
            'amount' => $totalInvestment,
            'office_expense' => $totalOfficeExpense,
            'payment_method' => $request->payment_method ?: 'bank_transfer',
            'transaction_reference' => $request->transaction_reference,
            'status' => 'completed',
            'description' => "প্রাথমিক {$sharesCount}টি শেয়ার বরাদ্দ ও অফিস খরচ গ্রহণ",
        ]);

        // Record Central Finance Incomes
        CentralFinance::create([
            'transaction_code' => 'CF-SHR-' . rand(10000, 99999),
            'entry_type' => 'income',
            'category' => 'share_capital',
            'amount' => $totalInvestment,
            'entry_date' => now()->toDateString(),
            'payment_method' => $request->payment_method ?: 'bank_transfer',
            'description' => "শেয়ারহোল্ডার {$user->name} ({$code}) এর শেয়ার মূলধন বাবদ প্রাপ্তি",
            'created_by' => Auth::id(),
        ]);

        CentralFinance::create([
            'transaction_code' => 'CF-OFF-' . rand(10000, 99999),
            'entry_type' => 'income',
            'category' => 'service',
            'amount' => $totalOfficeExpense,
            'entry_date' => now()->toDateString(),
            'payment_method' => $request->payment_method ?: 'bank_transfer',
            'description' => "শেয়ারহোল্ডার {$user->name} ({$code}) এর অফিস ব্যবস্থাপনা খরচ বাবদ প্রাপ্তি",
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'নতুন শেয়ারহোল্ডার সফলভাবে অন্তর্ভুক্ত করা হয়েছে।');
    }

    public function declareShareDividend(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $sh = ShareholderProfile::findOrFail($id);
        $sh->increment('total_dividend_earned', $request->amount);
        $sh->increment('available_dividend_balance', $request->amount);

        ShareTransaction::create([
            'shareholder_id' => $sh->id,
            'user_id' => $sh->user_id,
            'transaction_type' => 'dividend_declared',
            'shares_count' => $sh->shares_count,
            'amount' => $request->amount,
            'status' => 'completed',
            'description' => 'বাৎসরিক নিরীক্ষিত ব্যবসায়িক নিট মুনাফা থেকে ঘোষিত লভ্যাংশ',
        ]);

        return back()->with('success', 'শেয়ারহোল্ডারকে লভ্যাংশ প্রদান সম্পন্ন হয়েছে।');
    }

    // =========================================================================
    // 7. PARTNERSHIP MANAGEMENT
    // =========================================================================
    public function partnershipsIndex()
    {
        $partnerships = Partnership::with('user')->latest()->paginate(20);
        return view('admin.corporate.partnerships_index', compact('partnerships'));
    }

    public function storePartnership(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'partner_name' => 'required|string|max:255',
            'capital_contribution' => 'required|numeric',
        ]);

        $code = 'PRT-' . date('Y') . '-' . rand(10000, 99999);
        $data = $request->except(['_token', 'agreement_file']);
        $data['partner_code'] = $code;

        if ($request->hasFile('agreement_file')) {
            $fileName = 'prt_' . time() . '.' . $request->file('agreement_file')->getClientOriginalExtension();
            $request->file('agreement_file')->move(public_path('upload/partnerships'), $fileName);
            $data['agreement_file'] = 'upload/partnerships/' . $fileName;
        }

        Partnership::create($data);

        // Update user
        $user = User::find($request->user_id);
        $user->membership_card_type = 'partnership';
        $user->save();

        return back()->with('success', 'ব্যবসায়িক পার্টনারশিপ সফলভাবে নথিভুক্ত হয়েছে।');
    }

    // =========================================================================
    // 8. UNIFIED CENTRAL FINANCIAL MANAGEMENT (সমন্বিত অ্যাকাউন্টস ওভারভিউ)
    // =========================================================================
    public function financeDashboard(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfYear()->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());

        // Overview of 5 account categories
        $smeUsersCount = User::where('membership_card_type', 'sme')->orWhere('role', 'user')->count();
        $goldUsersCount = User::where('membership_card_type', 'gold')->count();
        $propertyUsersCount = User::where('membership_card_type', 'property')->orWhere('role', 'investor')->count();
        $shareholdersCount = ShareholderProfile::count();
        $partnershipsCount = Partnership::count();

        // Financial Incomes & Expenses
        $totalIncome = CentralFinance::where('entry_type', 'income')->sum('amount');
        $totalExpense = CentralFinance::where('entry_type', 'expense')->sum('amount');
        $netProfitOrLoss = $totalIncome - $totalExpense;

        $incomeByCategory = CentralFinance::where('entry_type', 'income')
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->pluck('total', 'category');

        $expenseByCategory = CentralFinance::where('entry_type', 'expense')
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->pluck('total', 'category');

        $recentTransactions = CentralFinance::with('creator')->latest()->take(15)->get();

        return view('admin.corporate.finance_dashboard', compact(
            'smeUsersCount', 'goldUsersCount', 'propertyUsersCount', 'shareholdersCount', 'partnershipsCount',
            'totalIncome', 'totalExpense', 'netProfitOrLoss', 'incomeByCategory', 'expenseByCategory', 'recentTransactions'
        ));
    }

    public function storeFinanceEntry(Request $request)
    {
        $request->validate([
            'entry_type' => 'required|in:income,expense',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'entry_date' => 'required|date',
        ]);

        $code = 'CF-' . strtoupper(substr($request->entry_type, 0, 3)) . '-' . rand(10000, 99999);

        CentralFinance::create([
            'transaction_code' => $code,
            'entry_type' => $request->entry_type,
            'category' => $request->category,
            'amount' => $request->amount,
            'entry_date' => $request->entry_date,
            'payment_method' => $request->payment_method ?: 'cash',
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'আর্থিক লেনদেন সফলভাবে সংরক্ষণ করা হয়েছে।');
    }

    // =========================================================================
    // 9. AGENT INVENTORY & PRODUCT BALANCE MANAGEMENT (Section 5.1)
    // =========================================================================
    public function agentInventoryIndex()
    {
        $agents = User::where('role', 'agent')->orWhere('role', 'employee')->get();
        $inventories = AgentInventoryBalance::with(['agent', 'stock'])->latest()->paginate(20);
        return view('admin.corporate.agent_inventory', compact('agents', 'inventories'));
    }

    public function storeAgentInventory(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:users,id',
            'product_name' => 'required|string',
            'product_price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
        ]);

        AgentInventoryBalance::create($request->all());
        return back()->with('success', 'এজেন্ট ইনভেন্টরি সফলভাবে যুক্ত করা হয়েছে।');
    }

    // =========================================================================
    // 10. HRM PROVIDENT FUND (P/F) MANAGEMENT (Section 5.2)
    // =========================================================================
    public function providentFundIndex()
    {
        $employees = HrmEmployeeProfile::with('user')->get();
        $employeeUsers = User::whereIn('role', ['employee', 'admin', 'super_admin'])->get();
        if ($employeeUsers->isEmpty()) {
            $employeeUsers = User::take(30)->get();
        }
        $records = HrmProvidentFund::with('user')->latest()->paginate(20);
        $totalEmployeeShare = HrmProvidentFund::sum('employee_contribution');
        $totalCompanyShare = HrmProvidentFund::sum('company_contribution');
        $grandTotalPf = HrmProvidentFund::sum('total_amount');

        return view('admin.corporate.provident_fund', compact('employees', 'employeeUsers', 'records', 'totalEmployeeShare', 'totalCompanyShare', 'grandTotalPf'));
    }

    public function storeProvidentFund(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'month_year' => 'required|string',
            'employee_contribution' => 'required|numeric',
            'company_contribution' => 'required|numeric',
        ]);

        $total = $request->employee_contribution + $request->company_contribution;

        HrmProvidentFund::create([
            'user_id' => $request->user_id,
            'month_year' => $request->month_year,
            'employee_contribution' => $request->employee_contribution,
            'company_contribution' => $request->company_contribution,
            'total_amount' => $total,
            'status' => 'credited',
            'remarks' => $request->remarks ?: 'মাসিক পিএফ জমা',
        ]);

        return back()->with('success', 'প্রভিডেন্ট ফান্ড সফলভাবে জমা হয়েছে।');
    }

    // =========================================================================
    // 11. NOTICE BOARD MANAGEMENT (Section 5.3)
    // =========================================================================
    public function noticesIndex()
    {
        $notices = Notice::latest()->paginate(15);
        return view('admin.corporate.notices', compact('notices'));
    }

    public function storeNotice(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'notice_type' => 'required|string',
            'content' => 'required|string',
        ]);

        $data = $request->except(['_token', 'attachment_file']);
        $data['is_pinned'] = $request->has('is_pinned');
        $data['is_active'] = $request->has('is_active');
        $data['publish_date'] = $request->publish_date ?: now()->toDateString();

        if ($request->hasFile('attachment_file')) {
            $fileName = 'notice_' . time() . '.' . $request->file('attachment_file')->getClientOriginalExtension();
            $request->file('attachment_file')->move(public_path('upload/notices'), $fileName);
            $data['attachment_file'] = 'upload/notices/' . $fileName;
        }

        Notice::create($data);
        return back()->with('success', 'নোটিশ সফলভাবে প্রকাশিত হয়েছে।');
    }

    public function updateNotice(Request $request, $id)
    {
        $notice = Notice::findOrFail($id);
        $data = $request->except(['_token', 'attachment_file']);
        $data['is_pinned'] = $request->has('is_pinned');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('attachment_file')) {
            $fileName = 'notice_' . time() . '.' . $request->file('attachment_file')->getClientOriginalExtension();
            $request->file('attachment_file')->move(public_path('upload/notices'), $fileName);
            $data['attachment_file'] = 'upload/notices/' . $fileName;
        }

        $notice->update($data);
        return back()->with('success', 'নোটিশ সফলভাবে আপডেট হয়েছে।');
    }

    public function destroyNotice($id)
    {
        Notice::findOrFail($id)->delete();
        return back()->with('success', 'নোটিশ মুছে ফেলা হয়েছে।');
    }

    // =========================================================================
    // 12. GRIEVANCES & FEEDBACK SYSTEM (অভিযোগ ও পরামর্শ)
    // =========================================================================
    public function grievancesIndex()
    {
        $grievances = Grievance::with('user')->latest()->paginate(20);
        return view('admin.corporate.grievances', compact('grievances'));
    }

    public function respondGrievance(Request $request, $id)
    {
        $g = Grievance::findOrFail($id);
        $g->status = $request->status;
        $g->admin_response = $request->admin_response;
        if ($request->status === 'resolved') {
            $g->resolved_at = now();
        }
        $g->save();

        return back()->with('success', 'অভিযোগ/পরামর্শের উত্তর সফলভাবে পাঠানো হয়েছে।');
    }
}
