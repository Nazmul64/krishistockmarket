<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyPointsLedger extends Model
{
    use HasFactory;

    protected $table = 'loyalty_points_ledger';

    protected $fillable = [
        'membership_id',
        'type',
        'points',
        'pos_sale_id',
        'notes',
    ];

    public function membership()
    {
        return $this->belongsTo(LoyaltyMembership::class, 'membership_id');
    }

    public function posSale()
    {
        return $this->belongsTo(PosSale::class, 'pos_sale_id');
    }
}

