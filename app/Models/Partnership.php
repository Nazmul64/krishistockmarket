<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partnership extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'partner_code',
        'partner_name',
        'business_name',
        'partnership_type',
        'contract_number',
        'agreement_file',
        'capital_contribution',
        'profit_share_percentage',
        'total_profit_shared',
        'start_date',
        'end_date',
        'status',
        'terms_and_conditions',
    ];

    protected $casts = [
        'capital_contribution' => 'decimal:2',
        'profit_share_percentage' => 'decimal:2',
        'total_profit_shared' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

