<?php

namespace App\Models\HRM;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrmBranch extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function employeeProfiles()
    {
        return $this->hasMany(HrmEmployeeProfile::class, 'branch_id');
    }
}
