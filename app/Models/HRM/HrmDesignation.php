<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrmDesignation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function department()
    {
        return $this->belongsTo(HrmDepartment::class, 'department_id');
    }

    public function employeeProfiles()
    {
        return $this->hasMany(HrmEmployeeProfile::class, 'designation_id');
    }
}
