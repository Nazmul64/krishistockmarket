<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CashierShift extends Model
{
    use HasFactory;

    protected $table = 'cashier_shifts';

    protected $fillable = [
        'branch_id',
        'pos_counter_id',
        'cashier_id',
        'opened_at',
        'closed_at',
        'opening_cash',
        'expected_cash',
        'actual_cash',
        'cash_difference',
        'total_cash_sales',
        'total_digital_sales',
        'total_sales_amount',
        'total_transactions',
        'closing_notes',
        'verified_by',
        'status',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function counter()
    {
        return $this->belongsTo(PosCounter::class, 'pos_counter_id');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function drawerTransactions()
    {
        return $this->hasMany(CashDrawerTransaction::class, 'shift_id');
    }

    public function sales()
    {
        return $this->hasMany(PosSale::class, 'shift_id');
    }
}

