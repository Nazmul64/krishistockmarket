<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpCashMemoItem extends Model
{
    protected $guarded = ['id'];

    public function cashMemo()
    {
        return $this->belongsTo(ErpCashMemo::class, 'cash_memo_id');
    }

    public function item()
    {
        return $this->belongsTo(ErpItem::class, 'item_id');
    }
}

