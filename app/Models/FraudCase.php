<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FraudCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_number',
        'customer_id',
        'customer_name',
        'title',
        'risk_score',
        'status',
        'notes',
        'assigned_to',
    ];

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
