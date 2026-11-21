<?php

namespace App\Http\Controllers\Admin\SuperShop\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\SuperShop\Branch;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeAttendance;
use App\Models\Employee\EmployeeActivityLog;
use App\Models\Employee\AttendanceDevice;
use App\Services\BranchSecurityService;
use App\Services\AttendanceAbsentDetectionService;

class BranchAttendanceController extends Controller
{
    /**
     * Daily Manual Attendance Sheet (Section 7.1)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = BranchSecurityService::isAdmin($user);
        $userBranchId = BranchSecurityService::getUserBranchId($user);

        $selectedBranchId = $isAdmin ? ($request->get('branch_id') ?: Branch::where('status', 'active')->value('id')) : $userBranchId;
        BranchSecurityService::enforceBranchAccess($selectedBranchId);

        $selectedDate = $request->get('date') ?: Carbon::today()->format('Y-m-d');

        $employees = Employee::where('branch_id', $selectedBranchId)
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();

        // Fetch existing attendance records for the date
        $attendances = EmployeeAttendance::where('branch_id', $selectedBranchId)
            ->where('date', $selectedDate)
            ->get()
            ->keyBy('employee_id');

        $branches = $isAdmin ? Branch::where('status', 'active')->get() : collect();
        $device = AttendanceDevice::where('branch_id', $selectedBranchId)->where('is_active', true)->first();

        // Summary counts for this date
        $summary = [
            'present' => $attendances->where('status', 'present')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'half_day' => $attendances->where('status', 'half_day')->count(),
            'leave' => $attendances->where('status', 'leave')->count(),
            'holiday' => $attendances->whereIn('status', ['weekly_holiday', 'holiday'])->count(),
        ];

        return view('admin.supershop.employees.attendance.index', [
            'employees' => $employees,
            'attendances' => $attendances,
            'selectedBranchId' => $selectedBranchId,
            'selectedDate' => $selectedDate,
            'branches' => $branches,
            'isAdmin' => $isAdmin,
            'device' => $device,
            'summary' => $summary,
        ]);
    }

    /**
     * Store / Bulk Update Manual Attendance (Section 7.1)
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $isAdmin = BranchSecurityService::isAdmin($user);
        $userBranchId = BranchSecurityService::getUserBranchId($user);

        $branchId = $isAdmin ? ($request->input('branch_id') ?: $userBranchId) : $userBranchId;
        BranchSecurityService::enforceBranchAccess($branchId);

        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $attendanceData = $request->input('attendance', []);

        DB::beginTransaction();
        try {
            $updatedCount = 0;
            foreach ($attendanceData as $empId => $data) {
                $status = $data['status'] ?? 'present';
                $checkIn = !empty($data['check_in']) ? $data['check_in'] : null;
                $checkOut = !empty($data['check_out']) ? $data['check_out'] : null;
                $remarks = $data['remarks'] ?? null;

                $lateMinutes = 0;
                if ($status === 'late' && $checkIn) {
                    $lateMinutes = max(0, Carbon::parse("{$date} {$checkIn}")->diffInMinutes(Carbon::parse("{$date} 09:00:00")));
                }

                $workingHours = 0;
                if ($checkIn && $checkOut) {
                    $in = Carbon::parse("{$date} {$checkIn}");
                    $out = Carbon::parse("{$date} {$checkOut}");
                    $workingHours = round($in->diffInMinutes($out) / 60, 2);
                }

                EmployeeAttendance::updateOrCreate(
                    [
                        'employee_id' => $empId,
                        'date' => $date,
                    ],
                    [
                        'branch_id' => $branchId,
                        'check_in' => $checkIn,
                        'check_out' => $checkOut,
                        'status' => $status,
                        'late_minutes' => $lateMinutes,
                        'working_hours' => $workingHours,
                        'verification_mode' => 'manual',
                        'attendance_taken_by' => $user->id,
                        'remarks' => $remarks,
                    ]
                );
                $updatedCount++;
            }

            EmployeeActivityLog::log(
                'Manual Attendance Recorded',
                'attendance',
                $branchId,
                null,
                "তারিখ {$date}-এর ম্যানুয়াল হাজিরা সংরক্ষণ করা হয়েছে ({$updatedCount} জন কর্মচারীর)।"
            );

            DB::commit();

            return back()->with('success', "তারিখ {$date}-এর হাজিরা সফলভাবে ডাটাবেজে সংরক্ষণ করা হয়েছে!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'হাজিরা সংরক্ষণে ত্রুটি: ' . $e->getMessage());
        }
    }

    /**
     * Attendance Correction (Section 7.1)
     */
    public function correction(Request $request, $id)
    {
        $attendance = EmployeeAttendance::findOrFail($id);
        BranchSecurityService::enforceBranchAccess($attendance->branch_id);

        $request->validate([
            'status' => 'required|string',
            'correction_reason' => 'required|string|max:255',
        ]);

        $prevStatus = $attendance->status;

        $attendance->update([
            'status' => $request->input('status'),
            'check_in' => $request->input('check_in') ?: $attendance->check_in,
            'check_out' => $request->input('check_out') ?: $attendance->check_out,
            'is_corrected' => true,
            'corrected_by' => Auth::id(),
            'correction_reason' => $request->input('correction_reason'),
        ]);

        EmployeeActivityLog::log(
            'Attendance Correction',
            'attendance',
            $attendance->branch_id,
            $attendance->employee_id,
            "হাজিরা সংশোধন: {$attendance->date} তারিখে স্ট্যাটাস {$prevStatus} থেকে পরিবর্তিত হয়ে {$attendance->status} হয়েছে। কারণ: " . $request->input('correction_reason')
        );

        return back()->with('success', 'হাজিরা সংশোধন সফলভাবে সম্পন্ন হয়েছে!');
    }

    /**
     * Monthly Visual Attendance Calendar (Section 13)
     */
    public function calendar(Request $request)
    {
        $user = Auth::user();
        $isAdmin = BranchSecurityService::isAdmin($user);
        $userBranchId = BranchSecurityService::getUserBranchId($user);

        $selectedBranchId = $isAdmin ? ($request->get('branch_id') ?: Branch::where('status', 'active')->value('id')) : $userBranchId;
        BranchSecurityService::enforceBranchAccess($selectedBranchId);

        $monthYear = $request->get('month', Carbon::today()->format('Y-m'));
        $startOfMonth = Carbon::parse($monthYear . '-01')->startOfMonth();
        $endOfMonth = Carbon::parse($monthYear . '-01')->endOfMonth();
        $daysInMonth = $startOfMonth->daysInMonth;

        $employeesQuery = Employee::where('branch_id', $selectedBranchId)->where('is_active', true);
        if ($request->filled('employee_id')) {
            $employeesQuery->where('id', $request->get('employee_id'));
        }
        $employees = $employeesQuery->orderBy('name', 'asc')->get();

        // Get all attendances for the month
        $attendances = EmployeeAttendance::where('branch_id', $selectedBranchId)
            ->whereBetween('date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
            ->get()
            ->groupBy('employee_id');

        $branches = $isAdmin ? Branch::where('status', 'active')->get() : collect();

        return view('admin.supershop.employees.attendance.calendar', [
            'employees' => $employees,
            'attendances' => $attendances,
            'monthYear' => $monthYear,
            'daysInMonth' => $daysInMonth,
            'startOfMonth' => $startOfMonth,
            'selectedBranchId' => $selectedBranchId,
            'branches' => $branches,
            'isAdmin' => $isAdmin,
        ]);
    }

    /**
     * Trigger Automatic Absent Detection (Section 9)
     */
    public function triggerAbsentDetection(Request $request)
    {
        $user = Auth::user();
        $isAdmin = BranchSecurityService::isAdmin($user);
        $userBranchId = BranchSecurityService::getUserBranchId($user);

        $branchId = $isAdmin ? $request->input('branch_id') : $userBranchId;
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));

        $res = AttendanceAbsentDetectionService::detectAndMarkAbsent($date, $branchId);

        return back()->with('success', "স্বয়ংক্রিয় অনুপস্থিতি যাচাই সম্পন্ন! তারিখ: {$res['date']} | অনুপস্থিত চিহ্নিত: {$res['marked_absent']} জন | সাপ্তাহিক ছুটি বাদ: {$res['skipped_holiday']} | অনুমোদিত ছুটি: {$res['skipped_leave']}");
    }
}
