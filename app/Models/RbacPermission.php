<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RbacPermission extends Model
{
    use HasFactory;

    protected $table = 'rbac_permissions';

    protected $fillable = [
        'name',
        'slug',
        'code',
        'module',
        'action',
        'guard_name',
        'description',
    ];

    /**
     * Get permission slug attribute, fallback to code.
     */
    public function getSlugAttribute($value)
    {
        return $value ?: $this->code;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permission_id', 'role_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_direct_permissions', 'permission_id', 'user_id');
    }
}
