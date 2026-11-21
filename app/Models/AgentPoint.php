<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'agent_name',
        'company_name',
        'agent_user_id',
        'area',
        'district',
        'thana_upazila',
        'address',
        'contact_number',
        'alt_phone',
        'status',
        'capacity',
        'opening_date',
    ];

    public function agentUser()
    {
        return $this->belongsTo(User::class, 'agent_user_id');
    }

    public function inventories()
    {
        return $this->hasMany(AgentPointInventory::class, 'agent_point_id');
    }

    public function stockLedgers()
    {
        return $this->hasMany(AgentPointStockLedger::class, 'agent_point_id');
    }

    public function allocations()
    {
        return $this->hasMany(AgentPointAllocation::class, 'agent_point_id');
    }

    public function dispatches()
    {
        return $this->hasMany(AgentPointDispatch::class, 'agent_point_id');
    }

    public function getPointCodeAttribute()
    {
        return $this->code;
    }

    public function getManagerNameAttribute()
    {
        return $this->agent_name;
    }

    public function marketRequests()
    {
        return $this->hasMany(MonthlyMarketRequest::class, 'agent_point_id');
    }

    public function assignedCardHolders()
    {
        return $this->hasMany(User::class, 'assigned_agent_point_id');
    }
}
