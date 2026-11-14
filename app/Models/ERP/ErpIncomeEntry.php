<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpIncomeEntry extends Model
{
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(ErpIncomeCategory::class, 'category_id');
    }

    public function cashBankAccount()
    {
        return $this->belongsTo(ErpCashBankAccount::class, 'cash_bank_account_id');
    }

    public function voucher()
    {
        return $this->belongsTo(ErpVoucher::class, 'voucher_id');
    }

    public function receipt()
    {
        return $this->belongsTo(ErpMoneyReceipt::class, 'receipt_id');
    }
}

