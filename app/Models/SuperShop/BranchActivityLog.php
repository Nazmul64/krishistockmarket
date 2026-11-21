<?php

namespace App\Models\SuperShop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class BranchActivityLog extends Model
{
    use HasFactory;

    protected $table = 'branch_activity_logs';
    protected $guarded = [];

    protected $casts = [
        'previous_value' => 'array',
        'new_value' => 'array',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Helper to log branch activity quickly
     */
    public static function record($branchId, $action, $entityType = null, $entityId = null, $desc = null, $prev = null, $new = null)
    {
        try {
            return self::create([
                'branch_id' => $branchId,
                'user_id' => auth()->id() ?? null,
                'action' => $action,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'previous_value' => $prev,
                'new_value' => $new,
                'description' => $desc,
                'ip_address' => request()->ip() ?? null,
            ]);
        } catch (\Exception $e) {
            \Log::error('BranchActivityLog record failed: ' . $e->getMessage());
            return null;
        }
    }
}
