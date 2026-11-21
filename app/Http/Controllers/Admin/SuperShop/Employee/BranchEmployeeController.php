<?php

namespace App\Http\Controllers\Admin\SuperShop\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\SuperShop\Branch;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeDocument;
use App\Models\Employee\EmployeeSalaryStructure;
use App\Models\Employee\EmployeeAttendance;
use App\Models\Employee\EmployeeSalaryAdvance;
use App\Models\Employee\EmployeeSalaryPayment;
use App\Models\Employee\EmployeeLeaveRequest;
use App\Models\Employee\EmployeeShift;
use App\Models\Employee\EmployeeActivityLog;
use App\Services\BranchSecurityService;

class BranchEmployeeController extends Controller
{
    /**
     * Employee List with Filters & Branch Isolation
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = BranchSecurityService::isAdmin($user);
        $userBranchId = BranchSecurityService::getUserBranchId($user);

        $query = Employee::with(['branch', 'shift'])
            ->withCount(['attendances', 'salaryAdvances']);

        // Enforce strict branch isolation
        $query = BranchSecurityService::applyBranchScope($query, 'branch_id', $user);

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('nid_number', 'like', "%{$search}%")
                  ->orWhere('designation', 'like', "%{$search}%");
            });
        }

        // Designation Filter
        if ($request->filled('designation')) {
            $query->where('designation', $request->get('designation'));
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('employment_status', $request->get('status'));
        }

        $employees = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();

        $branches = $isAdmin ? Branch::where('status', 'active')->get() : collect();
        $designations = Employee::distinct()->pluck('designation')->filter();

        return view('admin.supershop.employees.index', [
            'employees' => $employees,
            'branches' => $branches,
            'designations' => $designations,
            'isAdmin' => $isAdmin,
            'userBranchId' => $userBranchId,
        ]);
    }

    /**
     * Show Add New Employee Form (Section 5)
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $isAdmin = BranchSecurityService::isAdmin($user);
        $userBranchId = BranchSecurityService::getUserBranchId($user);

        $branches = $isAdmin ? Branch::where('status', 'active')->get() : Branch::where('id', $userBranchId)->get();
        $shifts = EmployeeShift::where('status', 'active')->get();

        // Auto-generate next Employee ID
        $prefix = 'EMP-';
        $lastEmp = Employee::orderBy('id', 'desc')->first();
        $nextNum = $lastEmp ? ((int) preg_replace('/[^0-9]/', '', $lastEmp->employee_id) + 1) : 1001;
        $suggestedEmployeeId = $prefix . $nextNum;

        return view('admin.supershop.employees.create', [
            'branches' => $branches,
            'shifts' => $shifts,
            'isAdmin' => $isAdmin,
            'userBranchId' => $userBranchId,
            'suggestedEmployeeId' => $suggestedEmployeeId,
        ]);
    }

    /**
     * Store New Employee (Section 4 & 5)
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $isAdmin = BranchSecurityService::isAdmin($user);
        $userBranchId = BranchSecurityService::getUserBranchId($user);

        // Branch ID must be logged in manager's branch if not admin (Section 4)
        $branchId = $isAdmin ? $request->input('branch_id') : $userBranchId;
        if (!$branchId) {
            return back()->withInput()->with('error', 'ব্রাঞ্চ নির্ধারণ আবশ্যক।');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'employee_id' => 'required|string|unique:employees,employee_id',
            'phone' => 'required|string|max:30',
            'designation' => 'required|string|max:100',
            'basic_salary' => 'nullable|numeric|min:0',
            'profile_photo' => 'nullable|image|max:2048',
            'nid_front' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:3072',
            'nid_back' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:3072',
        ]);

        DB::beginTransaction();
        try {
            // Upload Profile Photo if provided
            $photoPath = null;
            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');
                $photoPath = 'emp_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/employees'), $photoPath);
            }

            // Calculate Salary
            $basicSalary = (float) $request->input('basic_salary', 0);
            $houseRent = (float) $request->input('house_rent', 0);
            $medicalAllowance = (float) $request->input('medical_allowance', 0);
            $transportAllowance = (float) $request->input('transport_allowance', 0);
            $foodAllowance = (float) $request->input('food_allowance', 0);
            $otherAllowance = (float) $request->input('other_allowance', 0);
            $bonus = (float) $request->input('bonus', 0);
            $commission = (float) $request->input('commission', 0);
            $overtimeRate = (float) $request->input('overtime_rate', 0);

            $monthlySalary = $basicSalary + $houseRent + $medicalAllowance + $transportAllowance + $foodAllowance + $otherAllowance;

            $employee = Employee::create([
                'branch_id' => $branchId,
                'employee_id' => $request->input('employee_id'),
                'name' => $request->input('name'),
                'profile_photo' => $photoPath,
                'father_name' => $request->input('father_name'),
                'mother_name' => $request->input('mother_name'),
                'date_of_birth' => $request->input('date_of_birth'),
                'gender' => $request->input('gender', 'Male'),
                'marital_status' => $request->input('marital_status', 'Single'),
                'blood_group' => $request->input('blood_group'),
                'nationality' => $request->input('nationality', 'Bangladeshi'),
                'nid_number' => $request->input('nid_number'),
                'passport_number' => $request->input('passport_number'),
                'emergency_contact' => $request->input('emergency_contact'),
                'emergency_contact_relation' => $request->input('emergency_contact_relation'),

                'phone' => $request->input('phone'),
                'alt_phone' => $request->input('alt_phone'),
                'email' => $request->input('email'),
                'present_address' => $request->input('present_address'),
                'permanent_address' => $request->input('permanent_address'),
                'district' => $request->input('district'),
                'upazila' => $request->input('upazila'),
                'post_office' => $request->input('post_office'),
                'postal_code' => $request->input('postal_code'),

                'department' => $request->input('department'),
                'designation' => $request->input('designation'),
                'job_type' => $request->input('job_type', 'Full Time'),
                'joining_date' => $request->input('joining_date') ?: Carbon::today()->format('Y-m-d'),
                'employment_status' => $request->input('employment_status', 'active'),
                'reporting_person' => $request->input('reporting_person'),
                'shift_id' => $request->input('shift_id'),
                'employee_type' => $request->input('employee_type', 'Staff'),

                'basic_salary' => $basicSalary,
                'house_rent' => $houseRent,
                'medical_allowance' => $medicalAllowance,
                'transport_allowance' => $transportAllowance,
                'food_allowance' => $foodAllowance,
                'other_allowance' => $otherAllowance,
                'commission' => $commission,
                'bonus' => $bonus,
                'overtime_rate' => $overtimeRate,
                'monthly_salary' => $monthlySalary,
                'salary_payment_method' => $request->input('salary_payment_method', 'cash'),
                'bank_name' => $request->input('bank_name'),
                'bank_account_no' => $request->input('bank_account_no'),
                'mobile_wallet_number' => $request->input('mobile_wallet_number'),
                'is_active' => true,
                'created_by' => $user->id,
            ]);

            // Save Salary Structure
            EmployeeSalaryStructure::create([
                'employee_id' => $employee->id,
                'branch_id' => $branchId,
                'basic_salary' => $basicSalary,
                'house_rent' => $houseRent,
                'medical_allowance' => $medicalAllowance,
                'transport_allowance' => $transportAllowance,
                'food_allowance' => $foodAllowance,
                'other_allowance' => $otherAllowance,
                'commission' => $commission,
                'bonus' => $bonus,
                'overtime_rate' => $overtimeRate,
                'gross_salary' => $monthlySalary,
                'net_salary' => $monthlySalary,
                'payment_method' => $request->input('salary_payment_method', 'cash'),
                'bank_name' => $request->input('bank_name'),
                'bank_account_no' => $request->input('bank_account_no'),
                'mobile_wallet_number' => $request->input('mobile_wallet_number'),
            ]);

            // Handle Private Documents Upload (NID Front, NID Back, CV, etc.)
            $this->storeDocumentFile($request, 'nid_front', $employee, 'nid_front', 'NID Front Image');
            $this->storeDocumentFile($request, 'nid_back', $employee, 'nid_back', 'NID Back Image');
            $this->storeDocumentFile($request, 'cv', $employee, 'cv', 'Curriculum Vitae (CV)');
            $this->storeDocumentFile($request, 'appointment_letter', $employee, 'appointment_letter', 'Appointment Letter');

            // Audit Activity Log
            EmployeeActivityLog::log(
                'Employee Created',
                'employee',
                $branchId,
                $employee->id,
                "নতুন কর্মচারী তৈরি: {$employee->name} (ID: {$employee->employee_id}, পদবি: {$employee->designation})"
            );

            DB::commit();

            return redirect()->route('admin.supershop.employee_system.employees.show', $employee->id)
                ->with('success', "কর্মচারী '{$employee->name}' সফলভাবে নিবন্ধিত হয়েছে!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'কর্মচারী সংরক্ষণে ত্রুটি: ' . $e->getMessage());
        }
    }

    /**
     * Employee Profile Details Page with 10 Tabs (Section 6)
     */
    public function show(Request $request, $id)
    {
        $employee = Employee::with([
            'branch',
            'shift',
            'salaryStructure',
            'documents',
            'attendances' => fn($q) => $q->orderBy('date', 'desc')->take(30),
            'leaveRequests' => fn($q) => $q->orderBy('id', 'desc'),
            'salaryAdvances' => fn($q) => $q->orderBy('id', 'desc'),
            'salaryPayments' => fn($q) => $q->orderBy('payment_date', 'desc'),
            'activityLogs' => fn($q) => $q->orderBy('id', 'desc')->take(20),
        ])->findOrFail($id);

        // Security check: Branch Manager cannot view employees of other branches
        BranchSecurityService::enforceBranchAccess($employee->branch_id);

        $user = Auth::user();
        $canViewFullNid = BranchSecurityService::canViewFullNid($employee, $user);

        // Section 6.2 Employee Overview KPIs
        $thisMonth = Carbon::today()->format('Y-m');
        $totalAbsent = $employee->attendances()->where('status', 'absent')->count();
        $totalLeave  = $employee->attendances()->where('status', 'leave')->count();
        $totalLate   = $employee->attendances()->where('status', 'late')->count();

        $totalAdvance = (float) $employee->salaryAdvances()
            ->whereIn('status', ['approved', 'disbursed', 'partially_adjusted', 'fully_adjusted'])
            ->sum('advance_amount');

        $totalAdjustedAdvance = (float) $employee->salaryAdvances()->sum('adjusted_amount');
        $advanceRemaining = (float) $employee->salaryAdvances()
            ->whereIn('status', ['approved', 'disbursed', 'partially_adjusted'])
            ->sum('remaining_amount');

        $salaryPaid = (float) $employee->salaryPayments()->sum('paid_amount');
        $salaryPending = max(0, $employee->monthly_salary - (float) $employee->salaryPayments()->where('salary_month', $thisMonth)->sum('paid_amount'));

        return view('admin.supershop.employees.show', [
            'employee' => $employee,
            'canViewFullNid' => $canViewFullNid,
            'totalAbsent' => $totalAbsent,
            'totalLeave' => $totalLeave,
            'totalLate' => $totalLate,
            'totalAdvance' => $totalAdvance,
            'totalAdjustedAdvance' => $totalAdjustedAdvance,
            'advanceRemaining' => $advanceRemaining,
            'salaryPaid' => $salaryPaid,
            'salaryPending' => $salaryPending,
            'activeTab' => $request->get('tab', 'overview'),
        ]);
    }

    /**
     * Edit Employee Form
     */
    public function edit($id)
    {
        $employee = Employee::with(['salaryStructure'])->findOrFail($id);
        BranchSecurityService::enforceBranchAccess($employee->branch_id);

        $isAdmin = BranchSecurityService::isAdmin(Auth::user());
        $branches = $isAdmin ? Branch::where('status', 'active')->get() : Branch::where('id', $employee->branch_id)->get();
        $shifts = EmployeeShift::where('status', 'active')->get();

        return view('admin.supershop.employees.edit', [
            'employee' => $employee,
            'branches' => $branches,
            'shifts' => $shifts,
            'isAdmin' => $isAdmin,
        ]);
    }

    /**
     * Update Employee Info
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        BranchSecurityService::enforceBranchAccess($employee->branch_id);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'designation' => 'required|string|max:100',
        ]);

        $prevData = $employee->toArray();

        // Handle Photo Update
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $photoPath = 'emp_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/employees'), $photoPath);
            $employee->profile_photo = $photoPath;
        }

        $basicSalary = (float) $request->input('basic_salary', $employee->basic_salary);
        $houseRent = (float) $request->input('house_rent', $employee->house_rent);
        $medical = (float) $request->input('medical_allowance', $employee->medical_allowance);
        $transport = (float) $request->input('transport_allowance', $employee->transport_allowance);
        $food = (float) $request->input('food_allowance', $employee->food_allowance);
        $other = (float) $request->input('other_allowance', $employee->other_allowance);
        $monthlySalary = $basicSalary + $houseRent + $medical + $transport + $food + $other;

        $employee->fill($request->only([
            'name', 'father_name', 'mother_name', 'date_of_birth', 'gender', 'marital_status',
            'blood_group', 'nationality', 'nid_number', 'passport_number', 'emergency_contact',
            'emergency_contact_relation', 'phone', 'alt_phone', 'email', 'present_address',
            'permanent_address', 'district', 'upazila', 'post_office', 'postal_code',
            'department', 'designation', 'job_type', 'employment_status', 'reporting_person',
            'shift_id', 'employee_type', 'salary_payment_method', 'bank_name', 'bank_account_no',
            'mobile_wallet_number'
        ]));

        $employee->basic_salary = $basicSalary;
        $employee->house_rent = $houseRent;
        $employee->medical_allowance = $medical;
        $employee->transport_allowance = $transport;
        $employee->food_allowance = $food;
        $employee->other_allowance = $other;
        $employee->monthly_salary = $monthlySalary;
        $employee->save();

        // Update Salary Structure
        if ($employee->salaryStructure) {
            $employee->salaryStructure->update([
                'basic_salary' => $basicSalary,
                'house_rent' => $houseRent,
                'medical_allowance' => $medical,
                'transport_allowance' => $transport,
                'food_allowance' => $food,
                'other_allowance' => $other,
                'gross_salary' => $monthlySalary,
                'net_salary' => $monthlySalary,
                'payment_method' => $request->input('salary_payment_method', 'cash'),
                'bank_name' => $request->input('bank_name'),
                'bank_account_no' => $request->input('bank_account_no'),
                'mobile_wallet_number' => $request->input('mobile_wallet_number'),
            ]);
        }

        // Upload any new documents
        $this->storeDocumentFile($request, 'nid_front', $employee, 'nid_front', 'NID Front Image');
        $this->storeDocumentFile($request, 'nid_back', $employee, 'nid_back', 'NID Back Image');
        $this->storeDocumentFile($request, 'cv', $employee, 'cv', 'Curriculum Vitae (CV)');

        EmployeeActivityLog::log(
            'Employee Updated',
            'employee',
            $employee->branch_id,
            $employee->id,
            "কর্মচারীর তথ্য আপডেট করা হয়েছে: {$employee->name}",
            $prevData,
            $employee->toArray()
        );

        return redirect()->route('admin.supershop.employee_system.employees.show', $employee->id)
            ->with('success', 'কর্মচারীর বায়োডাটা ও তথ্য সফলভাবে আপডেট করা হয়েছে!');
    }

    /**
     * Toggle Active / Inactive Status (Section 18: Never hard delete, deactivate instead)
     */
    public function toggleStatus(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        BranchSecurityService::enforceBranchAccess($employee->branch_id);

        $newStatus = ($employee->employment_status === 'active') ? 'inactive' : 'active';
        $employee->employment_status = $newStatus;
        $employee->is_active = ($newStatus === 'active');
        $employee->save();

        EmployeeActivityLog::log(
            'Employee Status Changed',
            'employee',
            $employee->branch_id,
            $employee->id,
            "কর্মচারীর স্ট্যাটাস পরিবর্তন: {$employee->name} -> " . ($newStatus === 'active' ? 'সক্রিয়' : 'নিষ্ক্রিয়')
        );

        return back()->with('success', "কর্মচারীর স্ট্যাটাস সফলভাবে " . ($newStatus === 'active' ? 'সক্রিয়' : 'নিষ্ক্রিয়') . " করা হয়েছে।");
    }

    /**
     * Secure Document Viewing / Streaming (Section 18)
     */
    public function viewDocument($id)
    {
        $doc = EmployeeDocument::findOrFail($id);
        BranchSecurityService::enforceBranchAccess($doc->branch_id);

        $path = storage_path('app/' . $doc->file_path);
        if (!file_exists($path)) {
            abort(404, 'ডকুমেন্ট ফাইলটি সার্ভারে পাওয়া যায়নি।');
        }

        return response()->file($path, [
            'Content-Type' => $doc->mime_type ?: 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="' . ($doc->file_name ?: 'document') . '"',
        ]);
    }

    /**
     * Secure Document Download (Section 18)
     */
    public function downloadDocument($id)
    {
        $doc = EmployeeDocument::findOrFail($id);
        BranchSecurityService::enforceBranchAccess($doc->branch_id);

        $path = storage_path('app/' . $doc->file_path);
        if (!file_exists($path)) {
            abort(404, 'ডকুমেন্ট ফাইলটি সার্ভারে পাওয়া যায়নি।');
        }

        return response()->download($path, $doc->file_name ?: 'document');
    }

    /**
     * Helper to store document in private storage
     */
    protected function storeDocumentFile(Request $request, $inputKey, Employee $employee, $docType, $title)
    {
        if (!$request->hasFile($inputKey)) {
            return;
        }

        $file = $request->file($inputKey);
        $fileName = 'emp_' . $employee->id . '_' . $docType . '_' . time() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('private/employee_documents/' . $employee->branch_id, $fileName);

        // Remove old document of same type if exists
        $oldDoc = EmployeeDocument::where('employee_id', $employee->id)->where('document_type', $docType)->first();
        if ($oldDoc) {
            Storage::delete($oldDoc->file_path);
            $oldDoc->delete();
        }

        EmployeeDocument::create([
            'employee_id' => $employee->id,
            'branch_id' => $employee->branch_id,
            'document_type' => $docType,
            'document_title' => $title,
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => round($file->getSize() / 1024, 2) . ' KB',
            'mime_type' => $file->getClientMimeType(),
            'is_private' => true,
            'uploaded_by' => Auth::id(),
        ]);
    }
}
