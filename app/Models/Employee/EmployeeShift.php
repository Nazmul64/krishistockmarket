<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SuperShop\Branch;

class EmployeeShift extends Model
{
    use HasFactory;

    protected $table = 'employee_shifts';
    protected $guarded = [];

    protected $casts = [
        'is_night_shift' => 'boolean',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'shift_id');
    }
}
