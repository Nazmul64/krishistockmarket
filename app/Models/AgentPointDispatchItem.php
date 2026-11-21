<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentPointDispatchItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'dispatch_id',
        'product_name',
        'unit',
        'sent_quantity',
        'received_quantity',
        'short_quantity',
        'damaged_quantity',
        'notes',
    ];

    public function dispatch()
    {
        return $this->belongsTo(AgentPointDispatch::class, 'dispatch_id');
    }
}
