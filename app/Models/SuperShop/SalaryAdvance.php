<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SalaryAdvance extends Model
{
    use HasFactory;

    protected $table = 'salary_advances';
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'advance_amount' => 'float',
        'advance_balance' => 'float',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeProfile::class, 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
