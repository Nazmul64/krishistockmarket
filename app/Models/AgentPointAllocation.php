<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentPointAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_point_id',
        'month',
        'year',
        'product_name',
        'unit',
        'requested_quantity',
        'allocated_quantity',
        'dispatched_quantity',
        'received_quantity',
        'distributed_quantity',
        'remaining_quantity',
        'status',
        'notes',
        'allocation_reason',
    ];

    public function agentPoint()
    {
        return $this->belongsTo(AgentPoint::class, 'agent_point_id');
    }
}
