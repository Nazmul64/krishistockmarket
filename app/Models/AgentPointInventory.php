<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentPointInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_point_id',
        'product_name',
        'unit',
        'opening_stock',
        'received_stock',
        'distributed_stock',
        'damaged_stock',
        'adjusted_stock',
        'available_stock',
        'reserved_stock',
        'notes',
    ];

    public function agentPoint()
    {
        return $this->belongsTo(AgentPoint::class, 'agent_point_id');
    }
}
