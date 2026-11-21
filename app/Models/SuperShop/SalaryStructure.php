<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryStructure extends Model
{
    use HasFactory;

    protected $table = 'salary_structures';
    protected $guarded = [];

    protected $casts = [
        'basic_salary' => 'float',
        'house_allowance' => 'float',
        'transport_allowance' => 'float',
        'food_allowance' => 'float',
        'medical_allowance' => 'float',
        'bonus' => 'float',
        'overtime_rate' => 'float',
        'commission_rate' => 'float',
        'deduction' => 'float',
        'net_salary' => 'float',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeProfile::class, 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function calculateNet()
    {
        return ($this->basic_salary + $this->house_allowance + $this->transport_allowance + $this->food_allowance + $this->medical_allowance + $this->bonus) - $this->deduction;
    }
}
