<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'actor_id',
        'actor_name',
        'actor_role',
        'action',
        'subject_type',
        'subject_id',
        'before_state',
        'after_state',
        'reason',
        'ip_address',
        'user_agent',
        'request_id',
    ];

    protected $casts = [
        'before_state' => 'array',
        'after_state' => 'array',
    ];

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public static function log($action, $subject = null, $before = null, $after = null, $reason = null)
    {
        $actor = Auth::user();
        return self::create([
            'actor_id'     => $actor ? $actor->id : null,
            'actor_name'   => $actor ? $actor->name : 'System/Guest',
            'actor_role'   => $actor ? $actor->role : 'system',
            'action'       => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id'   => $subject ? $subject->id : null,
            'before_state' => is_array($before) ? json_encode($before) : $before,
            'after_state'  => is_array($after) ? json_encode($after) : $after,
            'reason'       => $reason,
            'ip_address'   => Request::ip(),
            'user_agent'   => substr(Request::userAgent() ?? '', 0, 255),
            'request_id'   => Request::header('X-Request-ID') ?? uniqid('req_'),
        ]);
    }
}
