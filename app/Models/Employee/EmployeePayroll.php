<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SuperShop\Branch;
use App\Models\User;

class EmployeePayroll extends Model
{
    use HasFactory;

    protected $table = 'employee_payrolls';
    protected $guarded = [];

    protected $casts = [
        'total_gross' => 'decimal:2',
        'total_allowances' => 'decimal:2',
        'total_overtime' => 'decimal:2',
        'total_advance_deductions' => 'decimal:2',
        'total_other_deductions' => 'decimal:2',
        'total_net_payable' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'total_pending' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function items()
    {
        return $this->hasMany(EmployeePayrollItem::class, 'payroll_id');
    }

    public function processedByUser()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
