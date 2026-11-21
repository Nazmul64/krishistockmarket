<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SuperShop\Branch;

class AttendanceDevice extends Model
{
    use HasFactory;

    protected $table = 'attendance_devices';
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'last_sync_time' => 'datetime',
        'last_successful_sync' => 'datetime',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function logs()
    {
        return $this->hasMany(AttendanceDeviceLog::class, 'device_id');
    }
}
