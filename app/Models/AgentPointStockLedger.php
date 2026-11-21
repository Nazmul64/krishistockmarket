<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentPointStockLedger extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_point_id',
        'product_name',
        'unit',
        'transaction_date',
        'transaction_type', // opening, received, order_delivery, damaged, adjustment, transfer
        'quantity_in',
        'quantity_out',
        'balance_after',
        'reference_no',
        'notes',
        'recorded_by',
    ];

    public function agentPoint()
    {
        return $this->belongsTo(AgentPoint::class, 'agent_point_id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
