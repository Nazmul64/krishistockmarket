<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosCounter extends Model
{
    use HasFactory;

    protected $table = 'pos_counters';

    protected $fillable = [
        'branch_id',
        'counter_name',
        'counter_code',
        'status',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function sales()
    {
        return $this->hasMany(PosSale::class, 'pos_counter_id');
    }

    public function shifts()
    {
        return $this->hasMany(CashierShift::class, 'pos_counter_id');
    }
}

