<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentPointDispatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'dispatch_no',
        'distribution_month',
        'distribution_year',
        'agent_point_id',
        'supplier_id',
        'supplier_supply_id',
        'supply_flow', // central_warehouse, direct_agent_supply
        'area',
        'vehicle_info',
        'driver_info',
        'driver_mobile',
        'delivery_note',
        'dispatch_note',
        'dispatch_attachment',
        'dispatch_date',
        'dispatched_by',
        'status', // pending, in_transit, received, completed
        'received_date',
        'received_by',
        'receive_note',
        'receive_attachment',
    ];

    public function agentPoint()
    {
        return $this->belongsTo(AgentPoint::class, 'agent_point_id');
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function supplierSupply()
    {
        return $this->belongsTo(SupplierSupply::class, 'supplier_supply_id');
    }

    public function items()
    {
        return $this->hasMany(AgentPointDispatchItem::class, 'dispatch_id');
    }

    public function dispatcher()
    {
        return $this->belongsTo(User::class, 'dispatched_by');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
