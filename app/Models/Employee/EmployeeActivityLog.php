<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SuperShop\Branch;
use App\Models\User;

class EmployeeActivityLog extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'employee_activity_logs';
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Convenient static logger
     */
    public static function log($action, $actionType, $branchId, $employeeId = null, $desc = null, $prevData = null, $newData = null)
    {
        $user = auth()->user();
        return self::create([
            'user_id' => $user ? $user->id : null,
            'user_name' => $user ? $user->name : 'System',
            'branch_id' => $branchId,
            'employee_id' => $employeeId,
            'action' => $action,
            'action_type' => $actionType,
            'description' => $desc,
            'previous_data' => is_array($prevData) ? json_encode($prevData, JSON_UNESCAPED_UNICODE) : $prevData,
            'updated_data' => is_array($newData) ? json_encode($newData, JSON_UNESCAPED_UNICODE) : $newData,
            'ip_address' => request()->ip(),
            'user_agent' => substr(request()->userAgent() ?? '', 0, 255),
            'created_at' => now(),
        ]);
    }
}
