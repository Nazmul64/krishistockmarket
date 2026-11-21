<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SuperShop\Branch;
use App\Models\User;

class EmployeeAttendance extends Model
{
    use HasFactory;

    protected $table = 'employee_attendances';
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'is_corrected' => 'boolean',
        'working_hours' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function device()
    {
        return $this->belongsTo(AttendanceDevice::class, 'device_id');
    }

    public function takenBy()
    {
        return $this->belongsTo(User::class, 'attendance_taken_by');
    }

    public function correctedBy()
    {
        return $this->belongsTo(User::class, 'corrected_by');
    }
}
