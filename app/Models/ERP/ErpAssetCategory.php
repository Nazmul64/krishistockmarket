<?php

namespace App\Models\ERP;

use Illuminate\Database\Eloquent\Model;

class ErpAssetCategory extends Model
{
    protected $guarded = ['id'];

    public function assets()
    {
        return $this->hasMany(ErpAsset::class, 'category_id');
    }
}

