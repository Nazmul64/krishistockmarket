<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CashDrawerTransaction extends Model
{
    use HasFactory;

    protected $table = 'cash_drawer_transactions';

    protected $fillable = [
        'shift_id',
        'type',
        'amount',
        'reason',
        'user_id',
    ];

    public function shift()
    {
        return $this->belongsTo(CashierShift::class, 'shift_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

