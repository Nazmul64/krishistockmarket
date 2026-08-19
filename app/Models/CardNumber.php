<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'amount',
        'card_type',
        'is_used',
        'used_by',
        'used_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'used_by');
    }
}
