<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SuperShop\Branch;

class EmployeeAttendanceLog extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'employee_attendance_logs';
    protected $guarded = [];

    protected $casts = [
        'punch_time' => 'datetime',
        'created_at' => 'datetime',
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
}
