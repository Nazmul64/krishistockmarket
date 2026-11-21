<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SuperShop\Branch;
use App\Models\User;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $guarded = [];

    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date' => 'date',
        'is_active' => 'boolean',
        'basic_salary' => 'decimal:2',
        'house_rent' => 'decimal:2',
        'medical_allowance' => 'decimal:2',
        'transport_allowance' => 'decimal:2',
        'food_allowance' => 'decimal:2',
        'other_allowance' => 'decimal:2',
        'commission' => 'decimal:2',
        'bonus' => 'decimal:2',
        'overtime_rate' => 'decimal:2',
        'monthly_salary' => 'decimal:2',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function shift()
    {
        return $this->belongsTo(EmployeeShift::class, 'shift_id');
    }

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class, 'employee_id');
    }

    public function salaryStructure()
    {
        return $this->hasOne(EmployeeSalaryStructure::class, 'employee_id');
    }

    public function attendances()
    {
        return $this->hasMany(EmployeeAttendance::class, 'employee_id');
    }

    public function attendanceLogs()
    {
        return $this->hasMany(EmployeeAttendanceLog::class, 'employee_id');
    }

    public function leaveRequests()
    {
        return $this->hasMany(EmployeeLeaveRequest::class, 'employee_id');
    }

    public function salaryAdvances()
    {
        return $this->hasMany(EmployeeSalaryAdvance::class, 'employee_id');
    }

    public function advanceAdjustments()
    {
        return $this->hasMany(EmployeeAdvanceAdjustment::class, 'employee_id');
    }

    public function salaryPayments()
    {
        return $this->hasMany(EmployeeSalaryPayment::class, 'employee_id');
    }

    public function payrollItems()
    {
        return $this->hasMany(EmployeePayrollItem::class, 'employee_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(EmployeeActivityLog::class, 'employee_id');
    }

    /**
     * Mask NID format: 1999-XXXX-XXXX-1234
     */
    public function getMaskedNidAttribute()
    {
        if (empty($this->nid_number)) {
            return '—';
        }

        $nid = preg_replace('/[^0-9]/', '', (string)$this->nid_number);
        $len = strlen($nid);

        if ($len <= 4) {
            return str_repeat('X', $len);
        }

        $first4 = substr($nid, 0, 4);
        $last4  = substr($nid, -4);
        $middleMask = 'XXXX-XXXX';

        return "{$first4}-{$middleMask}-{$last4}";
    }

    public function getTotalAdvanceAttribute()
    {
        return (float) $this->salaryAdvances()->whereIn('status', ['approved', 'disbursed', 'partially_adjusted', 'fully_adjusted'])->sum('advance_amount');
    }

    public function getTotalAdjustedAdvanceAttribute()
    {
        return (float) $this->salaryAdvances()->sum('adjusted_amount');
    }

    public function getRemainingAdvanceAttribute()
    {
        return (float) $this->salaryAdvances()->whereIn('status', ['approved', 'disbursed', 'partially_adjusted'])->sum('remaining_amount');
    }

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo && file_exists(public_path('uploads/employees/' . $this->profile_photo))) {
            return asset('uploads/employees/' . $this->profile_photo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=059669&color=fff&size=150';
    }
}
