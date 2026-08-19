<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\HrmAnnouncement;
use App\Models\HRM\HrmAttendance;
use App\Models\HRM\HrmBranch;
use App\Models\HRM\HrmDepartment;
use App\Models\HRM\HrmEmployeeProfile;
use App\Models\HRM\HrmLeaveRequest;
use App\Models\HRM\HrmPayroll;
use App\Models\User;
use Illuminate\Http\Request;

class HrmDashboardController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d');
        $currentMonth = date('Y-m');

        $totalEmployees = HrmEmployeeProfile::where('status', 'active')->count();
        $totalDepartments = HrmDepartment::where('status', 'active')->count();
        $totalBranches = HrmBranch::where('status', 'active')->count();

        $todayPresent = HrmAttendance::where('date', $today)->whereIn('status', ['present', 'late'])->count();
        $todayAbsent = HrmAttendance::where('date', $today)->where('status', 'absent')->count();
        $todayLate = HrmAttendance::where('date', $today)->where('status', 'late')->count();

        $pendingLeaves = HrmLeaveRequest::where('status', 'pending')->count();
        $latestAnnouncements = HrmAnnouncement::where('status', 'active')->latest()->take(5)->get();
        $currentMonthPayroll = HrmPayroll::where('month_year', $currentMonth)->first();

        $recentAttendances = HrmAttendance::with('user')
            ->where('date', $today)
            ->latest()
            ->take(10)
            ->get();

        return view('admin.hrm.dashboard', compact(
            'totalEmployees',
            'totalDepartments',
            'totalBranches',
            'todayPresent',
            'todayAbsent',
            'todayLate',
            'pendingLeaves',
            'latestAnnouncements',
            'currentMonthPayroll',
            'recentAttendances'
        ));
    }
}
