<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class EmployeeProfile extends Model
{
    use HasFactory;

    protected $table = 'employee_profiles';

    protected $fillable = [
        'user_id',
        'branch_id',
        'employee_code',
        'name',
        'phone',
        'email',
        'designation',
        'salary',
        'joining_date',
        'status',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendances()
    {
        return $this->hasMany(EmployeeAttendance::class, 'employee_id');
    }
}

