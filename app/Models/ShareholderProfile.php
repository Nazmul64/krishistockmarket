<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareholderProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shareholder_code',
        'shares_count',
        'total_investment',
        'office_expense_paid',
        'registration_fee_paid',
        'total_dividend_earned',
        'total_dividend_withdrawn',
        'available_dividend_balance',
        'nominee_name',
        'nominee_phone',
        'nominee_relation',
        'nominee_nid',
        'nid_number',
        'status',
    ];

    protected $casts = [
        'shares_count' => 'integer',
        'total_investment' => 'decimal:2',
        'office_expense_paid' => 'decimal:2',
        'registration_fee_paid' => 'decimal:2',
        'total_dividend_earned' => 'decimal:2',
        'total_dividend_withdrawn' => 'decimal:2',
        'available_dividend_balance' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(ShareTransaction::class, 'shareholder_id');
    }
}

