<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInvestment extends Model
{
    use HasFactory;

    protected $fillable = [
        'investment_code',
        'user_id',
        'project_id',
        'sector_id',
        'amount',
        'return_earned',
        'paid_dividend',
        'pending_dividend',
        'investment_date',
        'maturity_date',
        'payment_method',
        'transaction_id',
        'payment_proof',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'return_earned' => 'decimal:2',
        'paid_dividend' => 'decimal:2',
        'pending_dividend' => 'decimal:2',
        'investment_date' => 'date',
        'maturity_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function project()
    {
        return $this->belongsTo(InvestmentProject::class, 'project_id');
    }

    public function sector()
    {
        return $this->belongsTo(InvestmentSector::class, 'sector_id');
    }

    public function dividends()
    {
        return $this->hasMany(InvestmentDividend::class, 'user_investment_id');
    }

    public function withdrawals()
    {
        return $this->hasMany(InvestmentWithdrawal::class, 'user_investment_id');
    }
}

