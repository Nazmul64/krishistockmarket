<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouses';

    protected $fillable = [
        'name',
        'code',
        'location',
        'manager_id',
        'phone',
        'is_central',
        'status',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}

