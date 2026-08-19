<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\HrmAttendance;
use App\Models\HRM\HrmEmployeeProfile;
use App\Models\User;
use Illuminate\Http\Request;

class HrmAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');

        $attendances = HrmAttendance::with(['user.hrmProfile.department', 'user.hrmProfile.designation'])
            ->where('date', $date)
            ->get();

        $employees = User::where('role', 'employee')
            ->with(['hrmProfile.department', 'hrmProfile.designation'])
            ->get();

        return view('admin.hrm.attendance.index', compact('attendances', 'employees', 'date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'status' => 'required|in:present,absent,late,half_day,leave,holiday',
            'remarks' => 'nullable|string',
        ]);

        $workingHours = 0;
        if ($request->check_in && $request->check_out) {
            $t1 = strtotime($request->check_in);
            $t2 = strtotime($request->check_out);
            if ($t2 > $t1) {
                $workingHours = round(($t2 - $t1) / 3600, 2);
            }
        }

        HrmAttendance::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'date' => $request->date,
            ],
            [
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'working_hours' => $workingHours,
                'status' => $request->status,
                'remarks' => $request->remarks,
                'ip_address' => $request->ip(),
            ]
        );

        return back()->with('success', 'উপস্থিতি রেকর্ড সফলভাবে সংরক্ষণ করা হয়েছে।');
    }
}
