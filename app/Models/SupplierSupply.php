<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierSupply extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'invoice_no',
        'product_name',
        'category',
        'quantity',
        'unit',
        'rate',
        'total_amount',
        'supply_date',
        'invoice_file',
        'note',
        'status',
        'approved_by',
        'approved_at',
    ];

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
