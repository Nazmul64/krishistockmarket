<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PosSalesReturn extends Model
{
    use HasFactory;

    protected $table = 'pos_sales_returns';

    protected $fillable = [
        'return_no',
        'pos_sale_id',
        'branch_id',
        'cashier_id',
        'total_refund_amount',
        'refund_method',
        'reason',
        'status',
        'approved_by',
    ];

    public function sale()
    {
        return $this->belongsTo(PosSale::class, 'pos_sale_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items()
    {
        return $this->hasMany(PosSalesReturnItem::class, 'sales_return_id');
    }
}

