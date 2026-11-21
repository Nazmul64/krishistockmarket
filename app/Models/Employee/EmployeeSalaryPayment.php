<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SuperShop\Branch;
use App\Models\User;

class EmployeeSalaryPayment extends Model
{
    use HasFactory;

    protected $table = 'employee_salary_payments';
    protected $guarded = [];

    protected $casts = [
        'payment_date' => 'date',
        'basic_salary' => 'decimal:2',
        'allowance_total' => 'decimal:2',
        'bonus' => 'decimal:2',
        'overtime_amount' => 'decimal:2',
        'advance_adjusted' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

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

    public function paidByUser()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }
}
