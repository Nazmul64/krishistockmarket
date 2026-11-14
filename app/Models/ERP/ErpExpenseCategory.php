<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpExpenseCategory extends Model
{
    protected $guarded = ['id'];

    public function expenses()
    {
        return $this->hasMany(ErpExpenseEntry::class, 'category_id');
    }
}

