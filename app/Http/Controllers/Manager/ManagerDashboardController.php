<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\HRM\HrmAttendance;
use App\Models\HRM\HrmEmployeeProfile;
use App\Models\HRM\HrmLeaveRequest;
use App\Models\SuperShop\Branch;
use App\Models\SuperShop\PosSale;
use App\Models\Shop\ShopProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('portal:/manager');
    }

    public function index()
    {
        $manager = Auth::user();

        // 1. Find Assigned Branch for this Manager
        $branch = Branch::where('id', $manager->branch_id)
            ->orWhere('manager_id', $manager->id)
            ->first();

        if (!$branch) {
            $branch = Branch::where('status', 'active')->first();
        }

        // 2. Real-time Branch Retail & POS Metrics
        $branchTodaySales = 0;
        $branchTotalSales = 0;
        $branchTodayInvoices = 0;
        $branchTotalInvoices = 0;
        $recentBranchSales = collect();
        $branchInventoryCount = ShopProduct::where('is_active', true)->count();

        if ($branch) {
            $branchTodaySales = (float) PosSale::where('branch_id', $branch->id)
                ->whereDate('sale_time', today())
                ->where('sale_status', 'completed')
                ->sum('grand_total');

            $branchTotalSales = (float) PosSale::where('branch_id', $branch->id)
                ->where('sale_status', 'completed')
                ->sum('grand_total');

            $branchTodayInvoices = PosSale::where('branch_id', $branch->id)
                ->whereDate('sale_time', today())
                ->where('sale_status', 'completed')
                ->count();

            $branchTotalInvoices = PosSale::where('branch_id', $branch->id)
                ->where('sale_status', 'completed')
                ->count();

            $recentBranchSales = PosSale::where('branch_id', $branch->id)
                ->orderBy('id', 'desc')
                ->take(6)
                ->get();
        }

        // 3. HRM & Team Oversight
        $teamQuery = User::where(function ($q) use ($manager, $branch) {
            $q->where('manager_id', $manager->id)
              ->orWhere('department_id', $manager->department_id)
              ->orWhere('role', 'employee')
              ->orWhere('role', 'staff');
            if ($branch) {
                $q->orWhere('branch_id', $branch->id);
            }
        });

        $teamMembers = $teamQuery->take(10)->get();
        $teamCount = $teamQuery->count();

        // Team attendance today
        $todayAttendance = class_exists(HrmAttendance::class)
            ? HrmAttendance::whereDate('date', today())->count()
            : 0;

        // Pending Leave Requests
        $pendingLeaves = class_exists(HrmLeaveRequest::class)
            ? HrmLeaveRequest::where('status', 'pending')->take(5)->get()
            : collect();

        $activeEmployeesCount = User::where('role', 'employee')->where('status', 'active')->count();
        $activeStaffCount = User::where('role', 'staff')->where('status', 'active')->count();

        return view('manager.manager-dashboard', compact(
            'manager',
            'branch',
            'branchTodaySales',
            'branchTotalSales',
            'branchTodayInvoices',
            'branchTotalInvoices',
            'branchInventoryCount',
            'recentBranchSales',
            'teamMembers',
            'teamCount',
            'todayAttendance',
            'pendingLeaves',
            'activeEmployeesCount',
            'activeStaffCount'
        ));
    }

    public function approveLeave($id)
    {
        if (class_exists(HrmLeaveRequest::class)) {
            $leave = HrmLeaveRequest::findOrFail($id);
            $leave->update([
                'status'      => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            AuditLog::log('LEAVE_APPROVED_BY_MANAGER', $leave, ['status' => 'pending'], ['status' => 'approved'], "Manager approved leave request #{$id}");
            return back()->with('success', 'ছুটির আবেদন সফলভাবে অনুমোদন (Approved) করা হয়েছে।');
        }
        return back();
    }
}
