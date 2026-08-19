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
        'district_thana',
        'address',
        'opening_balance',
        'opening_date',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
