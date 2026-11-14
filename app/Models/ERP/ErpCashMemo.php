<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpCashMemo extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'memo_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(ErpCashMemoItem::class, 'cash_memo_id');
    }

    public function bankAccount()
    {
        return $this->belongsTo(ErpCashBankAccount::class, 'bank_account_id');
    }
}

