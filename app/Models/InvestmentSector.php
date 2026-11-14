<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentSector extends Model
{
    use HasFactory;

    protected $fillable = [
        'sector_no',
        'name_bn',
        'name_en',
        'investment_range',
        'min_amount',
        'max_amount',
        'duration',
        'icon',
        'image',
        'description',
        'profit_loss_policy',
        'is_active',
        'order_num',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'order_num' => 'integer',
    ];

    public function projects()
    {
        return $this->hasMany(InvestmentProject::class, 'sector_id');
    }

    public function userInvestments()
    {
        return $this->hasMany(UserInvestment::class, 'sector_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order_num', 'asc');
    }
}

