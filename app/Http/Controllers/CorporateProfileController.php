<?php

namespace App\Http\Controllers;

use App\Models\BoardMember;
use App\Models\CorporateProfile;
use App\Models\CorporateActivity;
use App\Models\InvestmentSector;
use App\Models\InvestmentProject;
use App\Models\CardBenefit;
use App\Models\Notice;
use App\Models\CommunityPost;
use App\Models\CommunityComment;
use App\Models\Grievance;
use App\Models\ShareSetting;
use App\Models\ShareholderProfile;
use App\Models\HRM\HrmDepartment;
use App\Models\HRM\HrmDesignation;
use App\Models\HRM\HrmEmployeeProfile;
use App\Models\User;
use App\Models\UserInvestment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CorporateProfileController extends Controller
{
    /**
     * 1.1 Board of Directors (পরিচালনা পর্ষদ)
     */
    public function boardOfDirectors()
    {
        $chairman = BoardMember::where('role_type', 'chairman')->where('is_active', true)->first();
        $md = BoardMember::where('role_type', 'md')->where('is_active', true)->first();
        $ceo = BoardMember::where('role_type', 'ceo')->where('is_active', true)->first();
        $members = BoardMember::where('is_active', true)
            ->whereNotIn('role_type', ['chairman'])
            ->orderBy('order_num', 'asc')
            ->get();

        return view('frontend.board_directors', compact('chairman', 'md', 'ceo', 'members'));
    }

    /**
     * 1.1 Employee Directory (কর্মকর্তা-কর্মচারী তালিকা)
     */
    public function employeeDirectory(Request $request)
    {
        $departmentId = $request->get('department_id');
        $search = $request->get('search');

        $departments = HrmDepartment::where('status', 'active')->get();
        $designations = HrmDesignation::where('status', 'active')->get();

        $query = HrmEmployeeProfile::with(['user', 'department', 'designation', 'branch'])
            ->where('status', 'active');

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $employees = $query->paginate(12)->withQueryString();

        return view('frontend.employee_directory', compact('employees', 'departments', 'designations', 'departmentId', 'search'));
    }

    /**
     * 1.2 Homepage & Corporate Profile (প্রতিষ্ঠানের পরিচিতি)
     */
    public function corporateProfile()
    {
        $history = CorporateProfile::getSection('history');
        $visionMission = CorporateProfile::getSection('vision_mission');
        $goals = CorporateProfile::getSection('goals_objectives');
        $futurePlans = CorporateProfile::getSection('future_plans');
        $achievements = CorporateProfile::getSection('achievements');

        return view('frontend.corporate_profile', compact('history', 'visionMission', 'goals', 'futurePlans', 'achievements'));
    }

    /**
     * 1.2 Leadership Messages (নেতৃবৃন্দের বাণী)
     */
    public function leadershipMessages()
    {
        $messages = BoardMember::where('is_active', true)
            ->whereNotNull('speech_message')
            ->where('speech_message', '!=', '')
            ->orderBy('order_num', 'asc')
            ->get();

        return view('frontend.leadership_messages', compact('messages'));
    }

    /**
     * 1.3 Activities & Project Implementation (প্রাতিষ্ঠানিক কার্যক্রম ও প্রকল্প বাস্তবায়ন)
     */
    public function activitiesAndProjects(Request $request)
    {
        $category = $request->get('category', 'all');

        $query = CorporateActivity::where('status', '!=', 'cancelled')->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        $activities = $query->paginate(9)->withQueryString();

        $ongoingCount = CorporateActivity::where('category', 'ongoing')->count();
        $completedCount = CorporateActivity::where('category', 'completed')->count();
        $plannedCount = CorporateActivity::where('category', 'planned')->count();

        return view('frontend.activities_projects', compact('activities', 'category', 'ongoingCount', 'completedCount', 'plannedCount'));
    }

    /**
     * 3.2 Investment Sectors (১০টি বিনিয়োগ খাত)
     */
    public function investmentSectors()
    {
        $sectors = InvestmentSector::active()->with(['projects' => function ($q) {
            $q->where('status', 'running')->orWhere('status', 'open');
        }])->get();

        $featuredProjects = InvestmentProject::with('sector')->whereIn('status', ['running', 'open'])->latest()->take(6)->get();

        return view('frontend.investment_sectors', compact('sectors', 'featuredProjects'));
    }

    /**
     * Project Details & Investment application form
     */
    public function projectDetails($id)
    {
        $project = InvestmentProject::with('sector')->findOrFail($id);
        $relatedProjects = InvestmentProject::where('sector_id', $project->sector_id)->where('id', '!=', $project->id)->take(3)->get();

        return view('frontend.project_details', compact('project', 'relatedProjects'));
    }

    /**
     * 5.3 Application Guidelines
     */
    public function applicationGuidelines()
    {
        return view('frontend.application_guidelines');
    }

    /**
     * 5.3 Notice Board
     */
    public function noticeBoard(Request $request)
    {
        $type = $request->get('type', 'all');
        $query = Notice::active();

        if ($type !== 'all') {
            $query->where('notice_type', $type);
        }

        $notices = $query->paginate(10)->withQueryString();

        return view('frontend.notices', compact('notices', 'type'));
    }

    public function noticeDetail($id)
    {
        $notice = Notice::findOrFail($id);
        $recentNotices = Notice::where('id', '!=', $id)->active()->take(5)->get();

        return view('frontend.notice_detail', compact('notice', 'recentNotices'));
    }

    /**
     * 5.3 Online Services Hub
     */
    public function onlineServices()
    {
        $cardBenefits = CardBenefit::where('status', 1)->orderBy('order_num', 'asc')->get();
        return view('frontend.online_services', compact('cardBenefits'));
    }

    /**
     * 5.3 Investment Policy & Legal Disclaimer
     */
    public function investmentPolicy()
    {
        return view('frontend.investment_policy');
    }

    /**
     * 2.1 & 2.2 Online Membership Card Application Form
     */
    public function applyCard(Request $request)
    {
        $type = $request->get('type', 'sme');
        $cardBenefits = CardBenefit::where('status', 1)->orderBy('order_num', 'asc')->get();
        $shareSetting = ShareSetting::current();

        return view('frontend.apply_card', compact('type', 'cardBenefits', 'shareSetting'));
    }

    public function storeCardApplication(Request $request)
    {
        $request->validate([
            'card_type' => 'required|string',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'present_address' => 'required|string',
            'nid_number' => 'nullable|string|max:30',
            'payment_method' => 'required|string',
            'transaction_id' => 'required|string',
        ]);

        $prefix = match($request->card_type) {
            'sme' => 'SME',
            'gold' => 'GLD',
            'property' => 'PRP',
            'shareholder' => 'SH',
            'partner' => 'PRT',
            'agent' => 'AGT',
            default => 'MBR',
        };

        $customerId = $prefix . '-' . date('Y') . '-' . rand(10000, 99999);

        // Check if user is logged in or create pending account
        if (Auth::check()) {
            $user = Auth::user();
            $user->update([
                'customer_id' => $user->customer_id ?: $customerId,
                'membership_card_type' => $request->card_type,
                'nid_number' => $request->nid_number ?: $user->nid_number,
                'present_address' => $request->present_address ?: $user->present_address,
                'permanent_address' => $request->permanent_address ?: $user->permanent_address,
                'nominee_name' => $request->nominee_name ?: $user->nominee_name,
                'nominee_phone' => $request->nominee_phone ?: $user->nominee_phone,
                'nominee_relation' => $request->nominee_relation ?: $user->nominee_relation,
            ]);
        } else {
            // Auto register or create user record
            $username = 'usr_' . Str::random(6);
            $user = User::create([
                'name' => $request->name,
                'username' => $username,
                'customer_id' => $customerId,
                'phone' => $request->phone,
                'email' => $request->email ?: $username . '@ekrishisheba.com',
                'password' => bcrypt($request->phone),
                'role' => ($request->card_type === 'shareholder') ? 'shareholder' : (($request->card_type === 'property') ? 'investor' : 'user'),
                'membership_card_type' => $request->card_type,
                'nid_number' => $request->nid_number,
                'present_address' => $request->present_address,
                'permanent_address' => $request->permanent_address,
                'nominee_name' => $request->nominee_name,
                'nominee_phone' => $request->nominee_phone,
                'nominee_relation' => $request->nominee_relation,
                'is_active' => true,
                'status' => 'active',
            ]);
        }

        // Handle Photo/NID upload
        if ($request->hasFile('photo')) {
            $photoName = 'user_' . time() . '.' . $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->move(public_path('upload/users'), $photoName);
            $user->avatar = 'upload/users/' . $photoName;
            $user->save();
        }

        // Record Deposit / Payment Request
        DB::table('deposits')->insert([
            'user_id' => $user->id,
            'amount' => $request->amount ?: 300,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('verify.card', ['card_number' => $customerId])
            ->with('success', 'আপনার মেম্বারশিপ কার্ড আবেদন সফলভাবে গৃহীত হয়েছে! আপনার ইউনিক কাস্টমার আইডি: ' . $customerId);
    }

    /**
     * 7. Live Digital Membership Card Verification (কিউআর কোড ও অনলাইন ভেরিফিকেশন)
     */
    public function verifyCard(Request $request, $card_number = null)
    {
        $code = $card_number ?: $request->get('code');
        $card = null;
        $user = null;

        if ($code) {
            $user = User::where('customer_id', $code)
                ->orWhere('username', $code)
                ->orWhere('phone', $code)
                ->first();
        }

        return view('frontend.verify_card', compact('user', 'code'));
    }

    /**
     * Grievance & Feedback Form (অভিযোগ ও পরামর্শ)
     */
    public function grievanceForm()
    {
        return view('frontend.grievance');
    }

    public function submitGrievance(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'type' => 'required|string',
            'category' => 'required|string',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $trackingCode = 'GRV-' . strtoupper(Str::random(6)) . '-' . rand(100, 999);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $fileName = 'grv_' . time() . '.' . $request->file('attachment')->getClientOriginalExtension();
            $request->file('attachment')->move(public_path('upload/grievances'), $fileName);
            $attachmentPath = 'upload/grievances/' . $fileName;
        }

        Grievance::create([
            'tracking_code' => $trackingCode,
            'user_id' => Auth::id(),
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'type' => $request->type,
            'category' => $request->category,
            'subject' => $request->subject,
            'description' => $request->description,
            'attachment' => $attachmentPath,
            'status' => 'pending',
        ]);

        return redirect()->route('grievance.track', ['code' => $trackingCode])
            ->with('success', 'আপনার আবেদন/অভিযোগটি সফলভাবে নথিভুক্ত হয়েছে! ট্র্যাকিং কোড: ' . $trackingCode);
    }

    public function trackGrievance(Request $request)
    {
        $code = $request->get('code');
        $grievance = null;
        if ($code) {
            $grievance = Grievance::where('tracking_code', $code)->first();
        }

        return view('frontend.grievance_track', compact('grievance', 'code'));
    }

    /**
     * Apply for investment in a project
     */
    public function investInProject(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'প্রকল্পে বিনিয়োগ করতে অনুগ্রহ করে লগইন করুন।');
        }

        $project = InvestmentProject::findOrFail($id);

        $request->validate([
            'amount' => "required|numeric|min:{$project->min_investment}",
            'payment_method' => 'required|string',
            'transaction_id' => 'required|string',
        ]);

        $invCode = 'INV-' . date('Y') . '-' . rand(10000, 99999);

        UserInvestment::create([
            'investment_code' => $invCode,
            'user_id' => Auth::id(),
            'project_id' => $project->id,
            'sector_id' => $project->sector_id,
            'amount' => $request->amount,
            'investment_date' => now()->toDateString(),
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'status' => 'pending',
            'admin_notes' => 'অনলাইন আবেদন। যাচাই ও অনুমোদনের অপেক্ষায়।',
        ]);

        return redirect()->route('user.investments.my')->with('success', 'আপনার বিনিয়োগ আবেদন সফলভাবে জমা হয়েছে। কোড: ' . $invCode);
    }
}

