<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class BranchExpense extends Model
{
    use HasFactory;

    protected $table = 'branch_expenses';

    protected $fillable = [
        'branch_id',
        'category_id',
        'title',
        'amount',
        'expense_date',
        'payment_method',
        'description',
        'receipt_attachment',
        'created_by',
        'approved_by',
        'status',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}

