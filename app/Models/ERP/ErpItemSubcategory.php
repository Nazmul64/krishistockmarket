<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpItemSubcategory extends Model
{
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(ErpItemCategory::class, 'category_id');
    }
}

