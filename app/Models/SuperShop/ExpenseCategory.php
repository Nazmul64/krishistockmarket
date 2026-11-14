<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $table = 'expense_categories';

    protected $fillable = [
        'name',
        'code',
        'is_active',
    ];

    public function expenses()
    {
        return $this->hasMany(BranchExpense::class, 'category_id');
    }
}

