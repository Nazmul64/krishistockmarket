<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Shop\ShopProduct;
use App\Models\Shop\ShopOrder;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';
    protected $guarded = [];

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

    public function shopOrders()
    {
        return $this->hasMany(ShopOrder::class, 'branch_id');
    }

    public function expenses()
    {
        return $this->hasMany(BranchExpense::class, 'branch_id');
    }

    public function shifts()
    {
        return $this->hasMany(CashierShift::class, 'branch_id');
    }

    public function financialTransactions()
    {
        return $this->hasMany(BranchFinancialTransaction::class, 'branch_id');
    }

    public function salaryStructures()
    {
        return $this->hasMany(SalaryStructure::class, 'branch_id');
    }

    public function salaryPayments()
    {
        return $this->hasMany(SalaryPayment::class, 'branch_id');
    }

    public function salaryAdvances()
    {
        return $this->hasMany(SalaryAdvance::class, 'branch_id');
    }

    public function employeeLeaves()
    {
        return $this->hasMany(EmployeeLeave::class, 'branch_id');
    }

    public function stockTransfersSent()
    {
        return $this->hasMany(StockTransfer::class, 'source_branch_id');
    }

    public function stockTransfersReceived()
    {
        return $this->hasMany(StockTransfer::class, 'destination_branch_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(BranchActivityLog::class, 'branch_id');
    }

    public function staffEmployees()
    {
        return $this->hasMany(\App\Models\Employee\Employee::class, 'branch_id');
    }

    public function staffAttendanceDevices()
    {
        return $this->hasMany(\App\Models\Employee\AttendanceDevice::class, 'branch_id');
    }

    public function staffAttendances()
    {
        return $this->hasMany(\App\Models\Employee\EmployeeAttendance::class, 'branch_id');
    }

    public function staffSalaryAdvances()
    {
        return $this->hasMany(\App\Models\Employee\EmployeeSalaryAdvance::class, 'branch_id');
    }

    public function staffPayrolls()
    {
        return $this->hasMany(\App\Models\Employee\EmployeePayroll::class, 'branch_id');
    }

    public function staffLeaveRequests()
    {
        return $this->hasMany(\App\Models\Employee\EmployeeLeaveRequest::class, 'branch_id');
    }

    public function getBranchCodeAttribute()
    {
        return $this->code;
    }
}
