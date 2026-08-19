<?php

namespace App\Models\HRM;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrmEmployeeProfile extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function branch()
    {
        return $this->belongsTo(HrmBranch::class, 'branch_id');
    }

    public function department()
    {
        return $this->belongsTo(HrmDepartment::class, 'department_id');
    }

    public function designation()
    {
        return $this->belongsTo(HrmDesignation::class, 'designation_id');
    }

    public function shift()
    {
        return $this->belongsTo(HrmShift::class, 'shift_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
