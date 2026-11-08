<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Blocklist;
use App\Models\FraudCase;
use App\Models\SecurityAlert;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecurityDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('portal:/security');
    }

    public function index()
    {
        $alerts = SecurityAlert::orderBy('id', 'desc')->take(10)->get();
        $fraudCases = FraudCase::with(['customer', 'assignee'])->orderBy('id', 'desc')->take(10)->get();
        $blocklists = Blocklist::with('blocker')->orderBy('id', 'desc')->take(15)->get();

        // Metrics
        $criticalAlerts = SecurityAlert::where('severity', 'critical')->where('status', 'active')->count();
        $highAlerts = SecurityAlert::where('severity', 'high')->where('status', 'active')->count();
        $openFraudCases = FraudCase::where('status', 'open')->count();
        $activeBlocks = Blocklist::where('status', 'active')->count();

        // High risk customers (Risk score > 70)
        $highRiskCases = FraudCase::where('risk_score', '>=', 70)->where('status', '!=', 'resolved')->get();

        return view('security.security-dashboard', compact(
            'alerts',
            'fraudCases',
            'blocklists',
            'criticalAlerts',
            'highAlerts',
            'openFraudCases',
            'activeBlocks',
            'highRiskCases'
        ));
    }

    public function addBlock(Request $request)
    {
        $request->validate([
            'type'   => 'required|in:ip,phone,email,account',
            'value'  => 'required|string|max:191',
            'reason' => 'required|string|max:500',
        ]);

        $block = Blocklist::create([
            'type'       => $request->type,
            'value'      => trim($request->value),
            'reason'     => $request->reason,
            'blocked_by' => Auth::id(),
            'status'     => 'active',
        ]);

        AuditLog::log('IP_ACCOUNT_BLOCKED', $block, null, $block->toArray(), "Security Analyst blocked {$block->type}: {$block->value}");

        return back()->with('success', "সফলভাবে {$block->type} '{$block->value}' ব্লকলিস্টে যুক্ত করা হয়েছে।");
    }

    public function removeBlock($id)
    {
        $block = Blocklist::findOrFail($id);
        $val = $block->value;
        $type = $block->type;
        $block->delete();

        AuditLog::log('IP_ACCOUNT_UNBLOCKED', null, ['value' => $val, 'type' => $type], null, "Security Analyst unblocked {$type}: {$val}");

        return back()->with('success', "ব্লকলিস্ট থেকে '{$val}' অবমুক্ত (Unblock) করা হয়েছে।");
    }

    public function acknowledgeAlert($id)
    {
        $alert = SecurityAlert::findOrFail($id);
        $alert->update([
            'status'      => 'acknowledged',
            'resolved_by' => Auth::id(),
            'resolved_at' => now(),
        ]);

        AuditLog::log('ALERT_ACKNOWLEDGED', $alert, null, null, "Alert #{$alert->id} marked as acknowledged");

        return back()->with('success', 'সাইবার অ্যালার্ট সফলভাবে গ্রহণ করা হয়েছে।');
    }

    public function updateFraud(Request $request, $id)
    {
        $case = FraudCase::findOrFail($id);
        $case->update([
            'status' => $request->status,
            'notes'  => $request->notes ? ($case->notes . "\n[" . now()->format('Y-m-d H:i') . ' by Security Analyst ' . Auth::user()->name . ']: ' . $request->notes) : $case->notes,
        ]);

        AuditLog::log('FRAUD_CASE_RESOLVED', $case, null, ['status' => $case->status], "Fraud Case {$case->case_number} updated by Security Analyst");

        return back()->with('success', 'ফ্রড কেস তথ্য সফলভাবে আপডেট হয়েছে।');
    }
}
