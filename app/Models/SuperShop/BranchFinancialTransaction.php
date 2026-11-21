<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class BranchFinancialTransaction extends Model
{
    use HasFactory;

    protected $table = 'branch_financial_transactions';
    protected $guarded = [];

    protected $casts = [
        'transaction_date' => 'date',
        'in_amount' => 'float',
        'out_amount' => 'float',
        'balance' => 'float',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
