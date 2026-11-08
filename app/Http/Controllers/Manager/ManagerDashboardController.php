<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\HRM\HrmAttendance;
use App\Models\HRM\HrmEmployeeProfile;
use App\Models\HRM\HrmLeaveRequest;
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

        // Scope: Own Team / Branch
        $teamQuery = User::where(function ($q) use ($manager) {
            $q->where('manager_id', $manager->id)
              ->orWhere('department_id', $manager->department_id)
              ->orWhere('role', 'employee')
              ->orWhere('role', 'staff');
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
