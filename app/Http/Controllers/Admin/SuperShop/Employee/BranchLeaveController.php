<?php

namespace App\Http\Controllers\Admin\SuperShop\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\SuperShop\Branch;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeLeaveRequest;
use App\Models\Employee\EmployeeAttendance;
use App\Models\Employee\EmployeeActivityLog;
use App\Services\BranchSecurityService;

class BranchLeaveController extends Controller
{
    /**
     * Leave Requests List (Section 16)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = BranchSecurityService::isAdmin($user);
        $userBranchId = BranchSecurityService::getUserBranchId($user);

        $query = EmployeeLeaveRequest::with(['employee', 'branch', 'approver'])
            ->orderBy('id', 'desc');

        $query = BranchSecurityService::applyBranchScope($query, 'branch_id', $user);

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $leaves = $query->paginate(20)->withQueryString();

        $employeesQuery = Employee::where('is_active', true);
        $employeesQuery = BranchSecurityService::applyBranchScope($employeesQuery, 'branch_id', $user);
        $employees = $employeesQuery->orderBy('name', 'asc')->get();

        $branches = $isAdmin ? Branch::where('status', 'active')->get() : collect();

        return view('admin.supershop.employees.leaves.index', [
            'leaves' => $leaves,
            'employees' => $employees,
            'branches' => $branches,
            'isAdmin' => $isAdmin,
            'userBranchId' => $userBranchId,
        ]);
    }

    /**
     * Submit Leave Request (Section 16)
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);

        $employee = Employee::findOrFail($request->input('employee_id'));
        BranchSecurityService::enforceBranchAccess($employee->branch_id);

        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));
        $totalDays = $startDate->diffInDays($endDate) + 1;

        $leave = EmployeeLeaveRequest::create([
            'employee_id' => $employee->id,
            'branch_id' => $employee->branch_id,
            'leave_type' => $request->input('leave_type', 'casual'),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'total_days' => $totalDays,
            'reason' => $request->input('reason'),
            'status' => 'approved', // Auto-approved by manager/admin when submitted from dashboard
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'remarks' => $request->input('remarks', 'কর্তৃপক্ষ কর্তৃক সরাসরি অনুমোদিত'),
        ]);

        // Link with attendance (Section 16): mark days as 'leave'
        $curDate = $startDate->copy();
        while ($curDate->lessThanOrEqualTo($endDate)) {
            $dStr = $curDate->format('Y-m-d');
            EmployeeAttendance::updateOrCreate(
                ['employee_id' => $employee->id, 'date' => $dStr],
                [
                    'branch_id' => $employee->branch_id,
                    'status' => 'leave',
                    'verification_mode' => 'system',
                    'remarks' => "অনুমোদিত ছুটি: {$leave->leave_type} ({$leave->reason})",
                ]
            );
            $curDate->addDay();
        }

        EmployeeActivityLog::log(
            'Leave Approved',
            'leave',
            $employee->branch_id,
            $employee->id,
            "ছুটি প্রদান ও অনুমোদন: {$employee->name}-কে {$startDate->format('d M')} থেকে {$endDate->format('d M Y')} পর্যন্ত ({$totalDays} দিন) {$leave->leave_type} ছুটি প্রদান করা হয়েছে।"
        );

        return back()->with('success', "কর্মচারী '{$employee->name}'-এর জন্য {$totalDays} দিনের ছুটি সফলভাবে রেকর্ড ও অনুমোদন করা হয়েছে!");
    }

    /**
     * Approve or Reject Leave Request
     */
    public function updateStatus(Request $request, $id)
    {
        $leave = EmployeeLeaveRequest::with('employee')->findOrFail($id);
        BranchSecurityService::enforceBranchAccess($leave->branch_id);

        $action = $request->input('status'); // approved, rejected

        $leave->status = $action;
        $leave->approved_by = Auth::id();
        $leave->approved_at = now();
        $leave->remarks = $request->input('remarks');
        $leave->save();

        if ($action === 'approved') {
            $startDate = Carbon::parse($leave->start_date);
            $endDate = Carbon::parse($leave->end_date);
            $cur = $startDate->copy();
            while ($cur->lessThanOrEqualTo($endDate)) {
                $dStr = $cur->format('Y-m-d');
                EmployeeAttendance::updateOrCreate(
                    ['employee_id' => $leave->employee_id, 'date' => $dStr],
                    [
                        'branch_id' => $leave->branch_id,
                        'status' => 'leave',
                        'verification_mode' => 'system',
                        'remarks' => "ছুটি অনুমোদিত: {$leave->leave_type}",
                    ]
                );
                $cur->addDay();
            }
        }

        EmployeeActivityLog::log(
            'Leave Status Updated',
            'leave',
            $leave->branch_id,
            $leave->employee_id,
            "ছুটি স্ট্যাটাস পরিবর্তন: {$leave->employee->name}-এর আবেদন " . ($action === 'approved' ? 'অনুমোদিত' : 'প্রত্যাখ্যাত') . ' হয়েছে।'
        );

        return back()->with('success', "ছুটির আবেদন সফলভাবে " . ($action === 'approved' ? 'অনুমোদিত' : 'প্রত্যাখ্যাত') . " হয়েছে!");
    }
}
