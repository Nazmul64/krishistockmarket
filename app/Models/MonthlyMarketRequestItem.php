<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyMarketRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'product_name',
        'requested_quantity',
        'unit',
        'approved_quantity',
        'allocated_quantity',
        'collected_quantity',
        'remaining_quantity',
        'unit_price',
        'total_price',
        'note',
    ];

    public function request()
    {
        return $this->belongsTo(MonthlyMarketRequest::class, 'request_id');
    }

    public function getQuantityAttribute()
    {
        return $this->requested_quantity;
    }
}
