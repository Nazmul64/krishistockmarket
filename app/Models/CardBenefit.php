<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardBenefit extends Model
{
    use HasFactory;

    protected $table = 'card_benefits';

    protected $guarded = [];

    protected $casts = [
        'facilities' => 'array',
        'status' => 'integer',
        'order_num' => 'integer',
    ];
}
