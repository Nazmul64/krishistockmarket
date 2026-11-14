<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentInventoryBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'stock_id',
        'product_name',
        'product_price',
        'stock_quantity',
        'payment_balance',
        'customer_balance',
        'total_sold_amount',
        'commission_earned',
        'details',
    ];

    protected $casts = [
        'product_price' => 'decimal:2',
        'payment_balance' => 'decimal:2',
        'customer_balance' => 'decimal:2',
        'total_sold_amount' => 'decimal:2',
        'commission_earned' => 'decimal:2',
        'stock_quantity' => 'integer',
    ];

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }
}

