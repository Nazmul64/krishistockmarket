<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrmLeaveType extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function leaveRequests()
    {
        return $this->hasMany(HrmLeaveRequest::class, 'leave_type_id');
    }
}
