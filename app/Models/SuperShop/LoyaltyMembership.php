<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class LoyaltyMembership extends Model
{
    use HasFactory;

    protected $table = 'loyalty_memberships';

    protected $fillable = [
        'customer_id',
        'customer_phone',
        'customer_name',
        'membership_number',
        'tier',
        'total_points',
        'redeemed_points',
        'available_points',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function ledger()
    {
        return $this->hasMany(LoyaltyPointsLedger::class, 'membership_id');
    }
}

