<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_shares',
        'price_per_share',
        'total_share_capital',
        'office_expense_per_share',
        'allocated_shares',
        'registration_fee',
    ];

    protected $casts = [
        'total_shares' => 'integer',
        'allocated_shares' => 'integer',
        'price_per_share' => 'decimal:2',
        'total_share_capital' => 'decimal:2',
        'office_expense_per_share' => 'decimal:2',
        'registration_fee' => 'decimal:2',
    ];

    public static function current()
    {
        return self::first() ?? self::create([
            'total_shares' => 100,
            'price_per_share' => 5000.00,
            'total_share_capital' => 500000.00,
            'office_expense_per_share' => 3000.00,
            'allocated_shares' => 0,
            'registration_fee' => 2000.00,
        ]);
    }
}

