<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'role',
        'balance',
        'locked_balance',
        'membership_card_type',
        'referral_id',
        'avatar',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function supplierProfile()
    {
        return $this->hasOne(SupplierProfile::class, 'user_id');
    }

    public function supplies()
    {
        return $this->hasMany(SupplierSupply::class, 'supplier_id');
    }

    public function supplierPayments()
    {
        return $this->hasMany(SupplierPayment::class, 'supplier_id');
    }

    public function permission()
    {
        return $this->hasOne(Permission::class, 'user_id');
    }

    public function employeeInfo()
    {
        return $this->hasOne(EmployeeInfo::class, 'user_id');
    }

    public function hrmProfile()
    {
        return $this->hasOne(\App\Models\HRM\HrmEmployeeProfile::class, 'user_id');
    }

    /**
     * Check if user has permission for a specific module key.
     */
    public function hasPermission($permissionKey)
    {
        // Admin has full access to all modules
        if ($this->role === 'admin') {
            return true;
        }

        // Employee access control based on permissions table
        if ($this->role === 'employee') {
            $perm = $this->permission;
            if (!$perm || empty($perm->permission_list)) {
                return false;
            }

            $list = json_decode($perm->permission_list, true);
            if (!is_array($list)) {
                $list = array_map('trim', explode(',', $perm->permission_list));
            }

            return in_array($permissionKey, $list);
        }

        return false;
    }
}

