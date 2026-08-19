<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrmJobPost extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function department()
    {
        return $this->belongsTo(HrmDepartment::class, 'department_id');
    }

    public function designation()
    {
        return $this->belongsTo(HrmDesignation::class, 'designation_id');
    }

    public function applicants()
    {
        return $this->hasMany(HrmApplicant::class, 'job_post_id');
    }
}
