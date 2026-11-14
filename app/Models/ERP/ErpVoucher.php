<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpVoucher extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'voucher_date' => 'date',
    ];

    public function entries()
    {
        return $this->hasMany(ErpVoucherEntry::class, 'voucher_id');
    }

    public function ledgerEntries()
    {
        return $this->hasMany(ErpGeneralLedgerEntry::class, 'voucher_id');
    }

    public function bankAccount()
    {
        return $this->belongsTo(ErpCashBankAccount::class, 'bank_account_id');
    }

    public function bill()
    {
        return $this->belongsTo(ErpBill::class, 'reference_bill_id');
    }
}

