<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpBill extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'bill_date' => 'date',
        'due_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(ErpBillItem::class, 'bill_id');
    }

    public function vouchers()
    {
        return $this->hasMany(ErpVoucher::class, 'reference_bill_id');
    }
}
