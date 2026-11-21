<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'supplier_code',
        'company_name',
        'representative_name',
        'alt_phone',
        'district_thana',
        'district',
        'thana',
        'address',
        'opening_balance',
        'opening_date',
        'business_type',
        'nid_trade_license',
        'status',
        'logo',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
