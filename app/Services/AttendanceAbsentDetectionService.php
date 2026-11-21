<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeAttendance;
use App\Models\Employee\EmployeeLeaveRequest;
use App\Models\Employee\AttendanceDevice;
use App\Models\Employee\EmployeeActivityLog;

class AttendanceAbsentDetectionService
{
    /**
     * Run Absent Detection for a specific date and optional branch.
     *
     * @param string|Carbon|null $date
     * @param int|null $branchId
     * @return array
     */
    public static function detectAndMarkAbsent($date = null, $branchId = null): array
    {
        $targetDate = $date ? Carbon::parse($date)->startOfDay() : Carbon::today()->startOfDay();
        $dateStr = $targetDate->format('Y-m-d');

        // Weekly holiday check: Friday is standard in BD (dayOfWeek == Carbon::FRIDAY)
        $isFriday = ($targetDate->dayOfWeek === Carbon::FRIDAY);

        $employeesQuery = Employee::where('is_active', true)->where('employment_status', 'active');
        if ($branchId) {
            $employeesQuery->where('branch_id', $branchId);
        }
        $employees = $employeesQuery->get();

        $processedCount = 0;
        $markedAbsentCount = 0;
        $skippedHolidayCount = 0;
        $skippedLeaveCount = 0;
        $alreadyPresentCount = 0;

        foreach ($employees as $emp) {
            $processedCount++;

            // 1. Check if an attendance record already exists for this date
            $existing = EmployeeAttendance::where('employee_id', $emp->id)
                ->where('date', $dateStr)
                ->first();

            if ($existing) {
                if (in_array($existing->status, ['present', 'late', 'half_day', 'overtime'])) {
                    $alreadyPresentCount++;
                    continue;
                }
                if ($existing->status === 'absent' || $existing->status === 'leave' || $existing->status === 'weekly_holiday') {
                    continue; // Already classified
                }
            }

            // 2. Check for Approved Leave
            $hasApprovedLeave = EmployeeLeaveRequest::where('employee_id', $emp->id)
                ->where('status', 'approved')
                ->where('start_date', '<=', $dateStr)
                ->where('end_date', '>=', $dateStr)
                ->exists();

            if ($hasApprovedLeave) {
                EmployeeAttendance::updateOrCreate(
                    ['employee_id' => $emp->id, 'date' => $dateStr],
                    [
                        'branch_id' => $emp->branch_id,
                        'status' => 'leave',
                        'verification_mode' => 'system',
                        'remarks' => 'অনুমোদিত ছুটি (Approved Leave)',
                    ]
                );
                $skippedLeaveCount++;
                continue;
            }

            // 3. Check for Weekly Holiday
            if ($isFriday) {
                EmployeeAttendance::updateOrCreate(
                    ['employee_id' => $emp->id, 'date' => $dateStr],
                    [
                        'branch_id' => $emp->branch_id,
                        'status' => 'weekly_holiday',
                        'verification_mode' => 'system',
                        'remarks' => 'সাপ্তাহিক ছুটি (Weekly Holiday)',
                    ]
                );
                $skippedHolidayCount++;
                continue;
            }

            // 4. Check device sync status - if device is offline and no manual punch, consider offline note
            $device = AttendanceDevice::where('branch_id', $emp->branch_id)->where('is_active', true)->first();
            $deviceNote = '';
            if ($device && $device->status === 'disconnected') {
                $deviceNote = ' (বায়োমেট্রিক মেশিন অফলাইন ছিল)';
            }

            // 5. Mark as Absent
            EmployeeAttendance::updateOrCreate(
                ['employee_id' => $emp->id, 'date' => $dateStr],
                [
                    'branch_id' => $emp->branch_id,
                    'status' => 'absent',
                    'check_in' => null,
                    'check_out' => null,
                    'late_minutes' => 0,
                    'working_hours' => 0,
                    'verification_mode' => 'system',
                    'remarks' => 'অনুপস্থিত হিসেবে স্বয়ংক্রিয় গণনা' . $deviceNote,
                ]
            );

            $markedAbsentCount++;
        }

        EmployeeActivityLog::log(
            'Automatic Absent Detection Executed',
            'attendance',
            $branchId ?: 1,
            null,
            "স্বয়ংক্রিয় অনুপস্থিতি নির্ণয়: {$dateStr} তারিখে {$markedAbsentCount} জন স্টাফকে অনুপস্থিত হিসেবে চিহ্নিত করা হয়েছে।"
        );

        return [
            'date' => $dateStr,
            'total_evaluated' => $processedCount,
            'marked_absent' => $markedAbsentCount,
            'skipped_holiday' => $skippedHolidayCount,
            'skipped_leave' => $skippedLeaveCount,
            'already_present' => $alreadyPresentCount,
        ];
    }
}
