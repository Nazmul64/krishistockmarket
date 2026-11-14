<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentDividend extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_investment_id',
        'user_id',
        'amount',
        'declaration_date',
        'payment_date',
        'status',
        'payout_method',
        'transaction_reference',
        'remarks',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'declaration_date' => 'date',
        'payment_date' => 'date',
    ];

    public function investment()
    {
        return $this->belongsTo(UserInvestment::class, 'user_investment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

