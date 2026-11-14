<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpExpenseEntry extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'expense_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(ErpExpenseCategory::class, 'category_id');
    }

    public function cashBankAccount()
    {
        return $this->belongsTo(ErpCashBankAccount::class, 'cash_bank_account_id');
    }

    public function voucher()
    {
        return $this->belongsTo(ErpVoucher::class, 'voucher_id');
    }
}

