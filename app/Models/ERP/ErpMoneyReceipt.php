<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpMoneyReceipt extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'receipt_date' => 'date',
    ];

    public function bankAccount()
    {
        return $this->belongsTo(ErpCashBankAccount::class, 'bank_account_id');
    }

    public function voucher()
    {
        return $this->belongsTo(ErpVoucher::class, 'voucher_id');
    }
}

