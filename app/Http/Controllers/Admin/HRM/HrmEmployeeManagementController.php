<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Models\EmployeeInfo;
use App\Models\HRM\HrmBranch;
use App\Models\HRM\HrmCompanyAsset;
use App\Models\HRM\HrmDepartment;
use App\Models\HRM\HrmDesignation;
use App\Models\HRM\HrmEmployeeProfile;
use App\Models\HRM\HrmLeaveRequest;
use App\Models\HRM\HrmPayrollItem;
use App\Models\HRM\HrmShift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HrmEmployeeManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'employee')->with(['employeeInfo', 'hrmProfile.branch', 'hrmProfile.department', 'hrmProfile.designation', 'hrmProfile.shift']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->whereHas('hrmProfile', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        $employees = $query->latest()->paginate(15);

        $branches = HrmBranch::where('status', 'active')->get();
        $departments = HrmDepartment::where('status', 'active')->get();
        $designations = HrmDesignation::where('status', 'active')->get();
        $shifts = HrmShift::where('status', 'active')->get();
        $managers = User::where('role', 'employee')->orWhere('role', 'admin')->get();

        return view('admin.hrm.employees.index', compact('employees', 'branches', 'departments', 'designations', 'shifts', 'managers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'number' => 'required|string|max:20|unique:users,number',
            'password' => 'required|string|min:6',
            'employee_code' => 'required|string|unique:hrm_employee_profiles,employee_code',
            'department_id' => 'nullable|exists:hrm_departments,id',
            'designation_id' => 'nullable|exists:hrm_designations,id',
            'branch_id' => 'nullable|exists:hrm_branches,id',
            'shift_id' => 'nullable|exists:hrm_shifts,id',
            'basic_salary' => 'nullable|numeric|min:0',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'number' => $request->number,
            'password' => Hash::make($request->password),
            'role' => 'employee',
            'status' => '1',
        ]);

        EmployeeInfo::create([
            'user_id' => $user->id,
            'status' => 1,
        ]);

        $basic = $request->basic_salary ?? 0;
        $houseRent = $basic * 0.4;
        $medical = $basic * 0.1;
        $transport = 1000;
        $gross = $basic + $houseRent + $medical + $transport;

        HrmEmployeeProfile::create([
            'user_id' => $user->id,
            'employee_code' => $request->employee_code,
            'branch_id' => $request->branch_id,
            'department_id' => $request->department_id,
            'designation_id' => $request->designation_id,
            'shift_id' => $request->shift_id,
            'manager_id' => $request->manager_id,
            'joining_date' => $request->joining_date ?? date('Y-m-d'),
            'employment_type' => $request->employment_type ?? 'full_time',
            'basic_salary' => $basic,
            'house_rent' => $houseRent,
            'medical_allowance' => $medical,
            'transport_allowance' => $transport,
            'gross_salary' => $gross,
            'bank_name' => $request->bank_name,
            'bank_account' => $request->bank_account,
            'nid' => $request->nid,
            'status' => 'active',
        ]);

        return back()->with('success', 'নতুন কর্মকর্তা/কর্মচারী সফলভাবে নিবন্ধন করা হয়েছে।');
    }

    public function show($id)
    {
        $user = User::with(['employeeInfo', 'hrmProfile.branch', 'hrmProfile.department', 'hrmProfile.designation', 'hrmProfile.shift', 'hrmProfile.manager'])->findOrFail($id);
        
        $assets = HrmCompanyAsset::where('assigned_user_id', $id)->get();
        $leaves = HrmLeaveRequest::with('leaveType')->where('user_id', $id)->latest()->take(10)->get();
        $payrolls = HrmPayrollItem::with('payroll')->where('user_id', $id)->latest()->take(12)->get();

        $branches = HrmBranch::where('status', 'active')->get();
        $departments = HrmDepartment::where('status', 'active')->get();
        $designations = HrmDesignation::where('status', 'active')->get();
        $shifts = HrmShift::where('status', 'active')->get();
        $managers = User::where('role', 'employee')->orWhere('role', 'admin')->get();

        return view('admin.hrm.employees.profile', compact('user', 'assets', 'leaves', 'payrolls', 'branches', 'departments', 'designations', 'shifts', 'managers'));
    }

    public function updateProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'number' => 'required|string|max:20|unique:users,number,' . $id,
            'basic_salary' => 'nullable|numeric|min:0',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'number' => $request->number,
        ]);

        $basic = $request->basic_salary ?? 0;
        $houseRent = $basic * 0.4;
        $medical = $basic * 0.1;
        $transport = 1000;
        $gross = $basic + $houseRent + $medical + $transport;

        $profileData = [
            'branch_id' => $request->branch_id,
            'department_id' => $request->department_id,
            'designation_id' => $request->designation_id,
            'shift_id' => $request->shift_id,
            'manager_id' => $request->manager_id,
            'joining_date' => $request->joining_date,
            'employment_type' => $request->employment_type ?? 'full_time',
            'basic_salary' => $basic,
            'house_rent' => $houseRent,
            'medical_allowance' => $medical,
            'transport_allowance' => $transport,
            'gross_salary' => $gross,
            'bank_name' => $request->bank_name,
            'bank_account' => $request->bank_account,
            'nid' => $request->nid,
            'status' => $request->status ?? 'active',
        ];

        HrmEmployeeProfile::updateOrCreate(
            ['user_id' => $id],
            array_merge($profileData, ['employee_code' => $request->employee_code ?? 'EMP-' . str_pad($id, 4, '0', STR_PAD_LEFT)])
        );

        return back()->with('success', 'প্রোফাইল তথ্য সফলভাবে আপডেট করা হয়েছে।');
    }
}
