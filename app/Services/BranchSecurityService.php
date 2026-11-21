<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\SuperShop\Branch;
use App\Models\SuperShop\BranchUser;
use App\Models\Employee\Employee;

class BranchSecurityService
{
    /**
     * Determine if user is Main Admin / Super Admin.
     */
    public static function isAdmin($user = null): bool
    {
        $user = $user ?: Auth::user();
        if (!$user) return false;

        $adminRoles = ['super_admin', 'admin', 'chairman', 'managing_director', 'ceo'];
        if (in_array(strtolower($user->role ?? ''), $adminRoles)) {
            return true;
        }

        if (method_exists($user, 'hasAnyRole') && $user->hasAnyRole($adminRoles)) {
            return true;
        }

        return false;
    }

    /**
     * Determine if user is a Branch Manager.
     */
    public static function isBranchManager($user = null): bool
    {
        $user = $user ?: Auth::user();
        if (!$user) return false;

        if (self::isAdmin($user)) return false;

        if (strtolower($user->role ?? '') === 'manager') {
            return true;
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('manager')) {
            return true;
        }

        return false;
    }

    /**
     * Get the active branch ID for the logged-in user.
     */
    public static function getUserBranchId($user = null): ?int
    {
        $user = $user ?: Auth::user();
        if (!$user) return null;

        if (!empty($user->branch_id)) {
            return (int) $user->branch_id;
        }

        $mgrBranchId = Branch::where('manager_id', $user->id)->value('id');
        if ($mgrBranchId) {
            return (int) $mgrBranchId;
        }

        $buBranchId = BranchUser::where('user_id', $user->id)->where('status', 'active')->value('branch_id');
        if ($buBranchId) {
            return (int) $buBranchId;
        }

        return null;
    }

    /**
     * Check if current user can access a specific branch.
     */
    public static function canAccessBranch($branchId, $user = null): bool
    {
        $user = $user ?: Auth::user();
        if (!$user) return false;

        if (self::isAdmin($user)) {
            return true;
        }

        $userBranchId = self::getUserBranchId($user);
        return ($userBranchId && (int)$userBranchId === (int)$branchId);
    }

    /**
     * Strict Authorization Check: Throws 403 or redirects if unauthorized.
     */
    public static function enforceBranchAccess($branchId, $user = null)
    {
        if (!self::canAccessBranch($branchId, $user)) {
            abort(403, 'নিরাপত্তা এক্সেস কন্ট্রোল: আপনি শুধুমাত্র আপনার নিজ ব্রাঞ্চের ডেটা ও ড্যাশবোর্ড পরিচালনা করতে পারবেন। অন্য কোনো ব্রাঞ্চের ডেটা এক্সেস সম্পূর্ণ নিষিদ্ধ।');
        }
    }

    /**
     * Apply strict branch data isolation to any Eloquent query.
     */
    public static function applyBranchScope($query, $branchColumn = 'branch_id', $user = null)
    {
        $user = $user ?: Auth::user();
        if (!$user) {
            return $query->whereRaw('1 = 0'); // Block all if unauthenticated
        }

        if (self::isBranchManager($user)) {
            $userBranchId = self::getUserBranchId($user);
            return $query->where($branchColumn, $userBranchId ?: 0);
        }

        // For Main Admin, allow optional filtering by branch from request
        if (request()->filled('branch_id')) {
            $bId = (int) request('branch_id');
            if ($bId > 0) {
                return $query->where($branchColumn, $bId);
            }
        }

        return $query;
    }

    /**
     * Check if user can view full NID / sensitive documents of an employee.
     */
    public static function canViewFullNid(Employee $employee, $user = null): bool
    {
        $user = $user ?: Auth::user();
        if (!$user) return false;

        if (self::isAdmin($user)) {
            return true;
        }

        return self::canAccessBranch($employee->branch_id, $user);
    }
}
