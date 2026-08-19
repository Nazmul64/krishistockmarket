<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrmShift extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function employeeProfiles()
    {
        return $this->hasMany(HrmEmployeeProfile::class, 'shift_id');
    }
}
