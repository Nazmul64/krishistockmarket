<?php

namespace App\Models\HRM;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrmProvidentFund extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'month_year',
        'employee_contribution',
        'company_contribution',
        'total_amount',
        'status',
        'remarks',
    ];

    protected $casts = [
        'employee_contribution' => 'decimal:2',
        'company_contribution' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

