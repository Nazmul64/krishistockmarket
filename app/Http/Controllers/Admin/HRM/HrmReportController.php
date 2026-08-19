<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\HrmAttendance;
use App\Models\HRM\HrmLeaveRequest;
use App\Models\HRM\HrmPayrollItem;
use App\Models\User;
use Illuminate\Http\Request;

class HrmReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month ?? date('Y-m');

        $employees = User::where('role', 'employee')
            ->with(['hrmProfile.department', 'hrmProfile.designation'])
            ->get();

        $attendanceSummary = HrmAttendance::where('date', 'like', "{$month}%")
            ->selectRaw('user_id, status, count(*) as count')
            ->groupBy('user_id', 'status')
            ->get()
            ->groupBy('user_id');

        $payrolls = HrmPayrollItem::with('user')
            ->whereHas('payroll', function ($q) use ($month) {
                $q->where('month_year', $month);
            })
            ->get();

        return view('admin.hrm.reports.index', compact('employees', 'attendanceSummary', 'payrolls', 'month'));
    }
}
