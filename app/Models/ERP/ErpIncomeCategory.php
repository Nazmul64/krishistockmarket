<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpIncomeCategory extends Model
{
    protected $guarded = ['id'];

    public function incomes()
    {
        return $this->hasMany(ErpIncomeEntry::class, 'category_id');
    }
}

