<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttendance extends Model
{
    use HasFactory;

    protected $table = 'employee_attendance';

    protected $fillable = [
        'employee_id',
        'branch_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'notes',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeProfile::class, 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}

