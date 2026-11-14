<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class StockTransfer extends Model
{
    use HasFactory;

    protected $table = 'stock_transfers';

    protected $fillable = [
        'transfer_number',
        'source_branch_id',
        'destination_branch_id',
        'request_date',
        'dispatch_date',
        'received_date',
        'status',
        'requested_by',
        'approved_by',
        'dispatched_by',
        'received_by',
        'notes',
    ];

    public function sourceBranch()
    {
        return $this->belongsTo(Branch::class, 'source_branch_id');
    }

    public function destinationBranch()
    {
        return $this->belongsTo(Branch::class, 'destination_branch_id');
    }

    public function items()
    {
        return $this->hasMany(StockTransferItem::class, 'transfer_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}

