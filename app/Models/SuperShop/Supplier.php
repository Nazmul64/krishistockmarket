<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'name',
        'company_name',
        'contact_person',
        'phone',
        'email',
        'address',
        'tax_number',
        'trade_license',
        'payment_terms',
        'opening_balance',
        'current_balance',
        'status',
    ];

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'supplier_id');
    }

    public function goodsReceiptNotes()
    {
        return $this->hasMany(GoodsReceiptNote::class, 'supplier_id');
    }
}

