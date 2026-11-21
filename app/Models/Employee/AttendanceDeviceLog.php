<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SuperShop\Branch;

class AttendanceDeviceLog extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'attendance_device_logs';
    protected $guarded = [];

    protected $casts = [
        'synced_at' => 'datetime',
    ];

    public function device()
    {
        return $this->belongsTo(AttendanceDevice::class, 'device_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
