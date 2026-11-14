<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'shareholder_id',
        'user_id',
        'transaction_type',
        'shares_count',
        'amount',
        'office_expense',
        'payment_method',
        'transaction_reference',
        'status',
        'description',
    ];

    protected $casts = [
        'shares_count' => 'integer',
        'amount' => 'decimal:2',
        'office_expense' => 'decimal:2',
    ];

    public function shareholder()
    {
        return $this->belongsTo(ShareholderProfile::class, 'shareholder_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

