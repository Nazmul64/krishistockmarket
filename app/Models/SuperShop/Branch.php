<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Shop\ShopProduct;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';

    protected $fillable = [
        'name',
        'code',
        'type',
        'manager_id',
        'phone',
        'email',
        'address',
        'division',
        'district',
        'area',
        'latitude',
        'longitude',
        'opening_time',
        'closing_time',
        'logo',
        'status',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function users()
    {
        return $this->hasMany(BranchUser::class, 'branch_id');
    }

    public function employees()
    {
        return $this->hasMany(EmployeeProfile::class, 'branch_id');
    }

    public function inventories()
    {
        return $this->hasMany(BranchInventory::class, 'branch_id');
    }

    public function products()
    {
        return $this->belongsToMany(ShopProduct::class, 'branch_inventories', 'branch_id', 'product_id')
                    ->withPivot('stock_quantity', 'reserved_stock', 'reorder_level', 'safety_stock', 'purchase_price', 'selling_price', 'shelf_location', 'is_active')
                    ->withTimestamps();
    }

    public function posCounters()
    {
        return $this->hasMany(PosCounter::class, 'branch_id');
    }

    public function posSales()
    {
        return $this->hasMany(PosSale::class, 'branch_id');
    }

    public function expenses()
    {
        return $this->hasMany(BranchExpense::class, 'branch_id');
    }

    public function shifts()
    {
        return $this->hasMany(CashierShift::class, 'branch_id');
    }

    public function stockTransfersSent()
    {
        return $this->hasMany(StockTransfer::class, 'source_branch_id');
    }

    public function stockTransfersReceived()
    {
        return $this->hasMany(StockTransfer::class, 'destination_branch_id');
    }

    public function getBranchCodeAttribute()
    {
        return $this->code;
    }
}
