<?php

namespace App\Models\HRM;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrmPayrollItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function payroll()
    {
        return $this->belongsTo(HrmPayroll::class, 'payroll_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
