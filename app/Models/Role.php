<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'code',
        'guard_name',
        'portal_prefix',
        'description',
        'category',
        'data_scope',
        'is_system',
        'status',
        'created_by',
    ];

    /**
     * Get the role's slug, fallback to code.
     */
    public function getSlugAttribute($value)
    {
        return $value ?: $this->code;
    }

    /**
     * Permissions belonging to this role.
     */
    public function permissions()
    {
        return $this->belongsToMany(RbacPermission::class, 'role_permissions', 'role_id', 'permission_id');
    }

    /**
     * Users associated with this role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id');
    }

    /**
     * Direct users having this role_id column.
     */
    public function directUsers()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    /**
     * Check if this role has permission for a specific code or slug.
     */
    public function hasPermissionTo($permissionSlug): bool
    {
        $permissionSlug = strtolower(trim($permissionSlug));
        return $this->permissions->contains(function ($perm) use ($permissionSlug) {
            return strtolower($perm->slug) === $permissionSlug || strtolower($perm->code) === $permissionSlug;
        });
    }
}
