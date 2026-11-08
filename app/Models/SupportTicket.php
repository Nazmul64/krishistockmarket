<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'subject',
        'description',
        'priority',
        'status',
        'category',
        'assigned_to',
        'last_reply_at',
    ];

    protected $casts = [
        'last_reply_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class, 'ticket_id');
    }
}
