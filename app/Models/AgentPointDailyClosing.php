<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentPointDailyClosing extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_point_id',
        'agent_user_id',
        'closing_date',
        'opening_stock',
        'total_received_today',
        'total_customers_served',
        'total_monthly_market_count',
        'total_weekly_market_count',
        'total_product_distributed',
        'total_distribution_amount',
        'total_return_quantity',
        'damaged_quantity',
        'stock_adjustment_quantity',
        'closing_stock',
        'pending_orders_count',
        'completed_collections_count',
        'partial_collections_count',
        'agent_note',
        'status', // submitted, verified, adjusted
        'verified_by',
        'verified_at',
        'admin_note',
    ];

    public function agentPoint()
    {
        return $this->belongsTo(AgentPoint::class, 'agent_point_id');
    }

    public function agentUser()
    {
        return $this->belongsTo(User::class, 'agent_user_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
