<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\UserInvestment;
use App\Models\InvestmentWithdrawal;
use App\Models\InvestmentProject;
use App\Models\ShareholderProfile;
use App\Models\ShareTransaction;
use App\Models\Partnership;
use App\Models\CommunityPost;
use App\Models\CommunityComment;
use App\Models\Grievance;
use App\Models\AgentInventoryBalance;
use App\Models\HRM\HrmProvidentFund;
use App\Models\CardBenefit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserCorporatePortalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 2.1 & 2.2 Digital Membership Card View & Download
     */
    public function myCard()
    {
        $user = Auth::user();
        $cardType = $user->membership_card_type ?: 'sme';
        $benefit = CardBenefit::where('card_type', $cardType)->first();

        return view('users.corporate.digital_card', compact('user', 'cardType', 'benefit'));
    }

    /**
     * 3.1 Property Card & Investment Dashboard
     */
    public function myInvestments()
    {
        $user = Auth::user();
        $investments = UserInvestment::where('user_id', $user->id)->with(['project', 'sector', 'dividends'])->latest()->get();
        $totalInvested = $investments->where('status', 'active')->sum('amount');
        $totalReturns = $investments->sum('return_earned');
        $paidDividends = $investments->sum('paid_dividend');
        $pendingDividends = $investments->sum('pending_dividend');

        $withdrawals = InvestmentWithdrawal::where('user_id', $user->id)->latest()->get();

        return view('users.corporate.investments', compact(
            'investments', 'totalInvested', 'totalReturns', 'paidDividends', 'pendingDividends', 'withdrawals'
        ));
    }

    /**
     * 3.1 Fund Withdrawal Request
     */
    public function requestWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:500',
            'payment_method' => 'required|string',
            'account_number' => 'required|string',
            'reason' => 'required|string',
        ]);

        $user = Auth::user();

        $code = 'WTH-' . date('Y') . '-' . rand(10000, 99999);

        InvestmentWithdrawal::create([
            'withdrawal_code' => $code,
            'user_id' => $user->id,
            'amount' => $request->amount,
            'withdrawal_type' => $request->withdrawal_type ?: 'profit',
            'payment_method' => $request->payment_method,
            'account_number' => $request->account_number,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return back()->with('success', 'আপনার ফান্ড উত্তোলনের আবেদন সফলভাবে গৃহীত হয়েছে। কোড: ' . $code);
    }

    /**
     * 4.1 & 4.2 Shareholder Profile & Statement
     */
    public function myShareholderProfile()
    {
        $user = Auth::user();
        $sh = ShareholderProfile::where('user_id', $user->id)->first();

        if (!$sh) {
            return redirect()->route('apply.card', ['type' => 'shareholder'])
                ->with('info', 'আপনি এখনো শেয়ারহোল্ডার হিসেবে নিবন্ধিত নন। এখানে আবেদন করতে পারেন।');
        }

        $transactions = ShareTransaction::where('shareholder_id', $sh->id)->latest()->get();

        return view('users.corporate.shareholder_profile', compact('sh', 'transactions'));
    }

    /**
     * 4.3 Partnership Profile
     */
    public function myPartnershipProfile()
    {
        $user = Auth::user();
        $partnerships = Partnership::where('user_id', $user->id)->latest()->get();

        return view('users.corporate.partnership_profile', compact('partnerships'));
    }

    /**
     * 4.3 Interactive Community Forum
     */
    public function communityForum(Request $request)
    {
        $category = $request->get('category', 'all');
        $query = CommunityPost::with(['user', 'comments.user'])->where('status', 'approved')->orderBy('is_pinned', 'desc')->latest();

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        $posts = $query->paginate(10)->withQueryString();

        return view('users.corporate.community_forum', compact('posts', 'category'));
    }

    public function storeCommunityPost(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $fileName = 'post_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('upload/community'), $fileName);
            $imagePath = 'upload/community/' . $fileName;
        }

        CommunityPost::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'category' => $request->category,
            'content' => $request->content,
            'image' => $imagePath,
            'status' => 'approved',
        ]);

        return back()->with('success', 'আপনার পোস্টটি সফলভাবে ফোরামে প্রকাশিত হয়েছে।');
    }

    public function storeCommunityComment(Request $request, $postId)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        CommunityComment::create([
            'post_id' => $postId,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
            'is_approved' => true,
        ]);

        CommunityPost::find($postId)?->increment('comments_count');

        return back()->with('success', 'আপনার মন্তব্য যুক্ত হয়েছে।');
    }

    /**
     * 5.1 Agent Shop Inventory & Balances
     */
    public function agentInventory()
    {
        $user = Auth::user();
        $inventories = AgentInventoryBalance::where('agent_id', $user->id)->latest()->get();
        $totalSold = $inventories->sum('total_sold_amount');
        $totalCommission = $inventories->sum('commission_earned');
        $totalCustomerBalance = $inventories->sum('customer_balance');

        return view('users.corporate.agent_inventory', compact('inventories', 'totalSold', 'totalCommission', 'totalCustomerBalance'));
    }

    /**
     * 5.2 Employee Provident Fund Statement
     */
    public function myProvidentFund()
    {
        $user = Auth::user();
        $records = HrmProvidentFund::where('user_id', $user->id)->latest()->get();
        $totalEmployeeShare = $records->sum('employee_contribution');
        $totalCompanyShare = $records->sum('company_contribution');
        $grandTotalPf = $records->sum('total_amount');

        return view('users.corporate.provident_fund', compact('records', 'totalEmployeeShare', 'totalCompanyShare', 'grandTotalPf'));
    }
}

