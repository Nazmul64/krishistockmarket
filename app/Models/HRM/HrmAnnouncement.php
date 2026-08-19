<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrmAnnouncement extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function targetDepartment()
    {
        return $this->belongsTo(HrmDepartment::class, 'target_department_id');
    }
}
