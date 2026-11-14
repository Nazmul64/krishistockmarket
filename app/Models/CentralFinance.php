<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentralFinance extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'entry_type',
        'category',
        'amount',
        'entry_date',
        'payment_method',
        'reference_type',
        'reference_id',
        'description',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'entry_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

