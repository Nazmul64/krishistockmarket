<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyMarketRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_no',
        'user_id',
        'card_number',
        'month',
        'year',
        'request_date',
        'request_area',
        'district',
        'upazila_thana',
        'agent_point_id',
        'agent_point_name',
        'total_items',
        'total_estimated_amount',
        'status',
        'collection_status',
        'collection_instruction',
        'admin_note',
        'rejection_reason',
        'approved_by',
        'approved_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agentPoint()
    {
        return $this->belongsTo(AgentPoint::class, 'agent_point_id');
    }

    public function items()
    {
        return $this->hasMany(MonthlyMarketRequestItem::class, 'request_id');
    }

    public function collections()
    {
        return $this->hasMany(CardHolderCollection::class, 'request_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
