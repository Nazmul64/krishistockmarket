<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardHolderCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_no',
        'transaction_id',
        'request_id',
        'user_id',
        'card_number',
        'customer_name',
        'customer_phone',
        'agent_point_id',
        'agent_user_id',
        'market_type', // monthly_market, weekly_market, special_market, emergency_market, additional_collection
        'market_month',
        'market_year',
        'market_week',
        'collection_date',
        'collection_time',
        'total_items_count',
        'total_distributed_qty',
        'total_amount',
        'paid_amount',
        'due_amount',
        'delivery_type', // subsidized, free, paid, due
        'collection_status', // partially_collected, fully_collected
        'delivered_by',
        'collected_by',
        'collector_phone',
        'received_by',
        'otp_or_qr_code',
        'verification_method', // physical_card, qr_code, otp_sms, cardholder_signature
        'status', // completed, partial, cancelled, returned
        'delivery_note',
        'remarks',
        'notes',
    ];

    public function request()
    {
        return $this->belongsTo(MonthlyMarketRequest::class, 'request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agentPoint()
    {
        return $this->belongsTo(AgentPoint::class, 'agent_point_id');
    }

    public function agentUser()
    {
        return $this->belongsTo(User::class, 'agent_user_id');
    }

    public function deliverer()
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }

    public function items()
    {
        return $this->hasMany(CardHolderCollectionItem::class, 'collection_id');
    }
}
