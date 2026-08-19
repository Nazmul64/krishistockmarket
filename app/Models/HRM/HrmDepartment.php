<?php

namespace App\Models\HRM;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrmDepartment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function head()
    {
        return $this->belongsTo(User::class, 'head_id');
    }

    public function designations()
    {
        return $this->hasMany(HrmDesignation::class, 'department_id');
    }

    public function employeeProfiles()
    {
        return $this->hasMany(HrmEmployeeProfile::class, 'department_id');
    }
}
