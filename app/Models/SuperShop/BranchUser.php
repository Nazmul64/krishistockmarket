<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class BranchUser extends Model
{
    use HasFactory;

    protected $table = 'branch_users';

    protected $fillable = [
        'branch_id',
        'user_id',
        'role',
        'is_primary',
        'status',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

