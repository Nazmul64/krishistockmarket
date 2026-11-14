<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentWithdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'withdrawal_code',
        'user_id',
        'user_investment_id',
        'amount',
        'withdrawal_type',
        'payment_method',
        'account_number',
        'reason',
        'status',
        'admin_remarks',
        'transaction_reference',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function investment()
    {
        return $this->belongsTo(UserInvestment::class, 'user_investment_id');
    }
}

