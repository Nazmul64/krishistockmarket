<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PosSale extends Model
{
    use HasFactory;

    protected $table = 'pos_sales';

    protected $fillable = [
        'invoice_no',
        'branch_id',
        'pos_counter_id',
        'shift_id',
        'cashier_id',
        'customer_id',
        'customer_name',
        'customer_phone',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'grand_total',
        'paid_amount',
        'change_amount',
        'payment_method',
        'payment_status',
        'sale_status',
        'notes',
        'sale_time',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function counter()
    {
        return $this->belongsTo(PosCounter::class, 'pos_counter_id');
    }

    public function shift()
    {
        return $this->belongsTo(CashierShift::class, 'shift_id');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(PosSaleItem::class, 'pos_sale_id');
    }

    public function payments()
    {
        return $this->hasMany(PosPayment::class, 'pos_sale_id');
    }

    public function returns()
    {
        return $this->hasMany(PosSalesReturn::class, 'pos_sale_id');
    }

    public function getInvoiceNumberAttribute()
    {
        return $this->invoice_no;
    }

    public function getTotalItemsAttribute()
    {
        return $this->items()->count();
    }
}
