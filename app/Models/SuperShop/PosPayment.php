<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosPayment extends Model
{
    use HasFactory;

    protected $table = 'pos_payments';

    protected $fillable = [
        'pos_sale_id',
        'payment_method',
        'amount',
        'transaction_ref',
    ];

    public function sale()
    {
        return $this->belongsTo(PosSale::class, 'pos_sale_id');
    }
}

