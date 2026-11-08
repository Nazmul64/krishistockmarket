<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditorDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('portal:/audit');
    }

    public function index(Request $request)
    {
        $query = AuditLog::with('actor')->orderBy('id', 'desc');

        if ($request->filled('action')) {
            $query->where('action', 'like', "%{$request->action}%");
        }

        if ($request->filled('actor')) {
            $actor = trim($request->actor);
            $query->where(function ($q) use ($actor) {
                $q->where('actor_name', 'like', "%{$actor}%")
                  ->orWhere('actor_role', 'like', "%{$actor}%")
                  ->orWhere('ip_address', 'like', "%{$actor}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Export to CSV
        if ($request->get('export') === 'csv') {
            $logs = $query->get();
            $filename = 'system_audit_trail_' . date('Y-m-d_H-i-s') . '.csv';

            $headers = [
                'Content-Type'        => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $callback = function () use ($logs) {
                $file = fopen('php://output', 'w');
                fputs($file, "\xEF\xBB\xBF"); // UTF-8 BOM
                fputcsv($file, ['Log ID', 'Actor Name', 'Role', 'Action', 'Target Type', 'Target ID', 'Reason', 'IP Address', 'Timestamp UTC']);

                foreach ($logs as $log) {
                    fputcsv($file, [
                        $log->id,
                        $log->actor_name,
                        $log->actor_role,
                        $log->action,
                        $log->subject_type,
                        $log->subject_id,
                        $log->reason,
                        $log->ip_address,
                        $log->created_at->toIso8601String(),
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        $auditLogs = $query->paginate(20);

        // Stats
        $totalLogsCount = AuditLog::count();
        $privilegedActionsCount = AuditLog::whereIn('action', ['ROLE_CREATED', 'ROLE_UPDATED', 'USER_CREATED', 'USER_STATUS_CHANGED', 'IP_ACCOUNT_BLOCKED', 'RBAC_SYSTEM_INITIALIZED'])->count();
        $uniqueActorsCount = AuditLog::distinct('actor_id')->count('actor_id');
        $todayLogsCount = AuditLog::whereDate('created_at', today())->count();

        return view('audit.audit-dashboard', compact(
            'auditLogs',
            'totalLogsCount',
            'privilegedActionsCount',
            'uniqueActorsCount',
            'todayLogsCount'
        ));
    }
}
