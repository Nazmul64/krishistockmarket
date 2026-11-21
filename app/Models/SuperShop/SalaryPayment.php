<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SalaryPayment extends Model
{
    use HasFactory;

    protected $table = 'salary_payments';
    protected $guarded = [];

    protected $casts = [
        'payment_date' => 'date',
        'basic_salary' => 'float',
        'allowance' => 'float',
        'bonus' => 'float',
        'deduction' => 'float',
        'advance_adjustment' => 'float',
        'net_salary' => 'float',
        'paid_amount' => 'float',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeProfile::class, 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
