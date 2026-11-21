<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SuperShop\Branch;
use App\Models\User;

class EmployeeAdvanceAdjustment extends Model
{
    use HasFactory;

    protected $table = 'employee_advance_adjustments';
    protected $guarded = [];

    protected $casts = [
        'adjustment_date' => 'date',
        'adjusted_amount' => 'decimal:2',
        'remaining_balance_after' => 'decimal:2',
    ];

    public function advance()
    {
        return $this->belongsTo(EmployeeSalaryAdvance::class, 'salary_advance_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function payroll()
    {
        return $this->belongsTo(EmployeePayroll::class, 'payroll_id');
    }

    public function adjustedByUser()
    {
        return $this->belongsTo(User::class, 'adjusted_by');
    }
}
