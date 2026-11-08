<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ChatMessage;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('portal:/support');
    }

    /**
     * Common Support Dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('support_lead')) {
            return $this->leadDashboard($request);
        }

        return $this->agentDashboard($request);
    }

    /**
     * Dedicated Support Lead Dashboard
     */
    public function leadDashboard(Request $request)
    {
        $user = Auth::user();

        $query = SupportTicket::with(['replies', 'assignee'])->orderBy('id', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('ticket_number', 'like', "%{$s}%")
                  ->orWhere('customer_name', 'like', "%{$s}%")
                  ->orWhere('customer_phone', 'like', "%{$s}%")
                  ->orWhere('subject', 'like', "%{$s}%");
            });
        }

        $tickets = $query->paginate(12);

        // Stats
        $openCount = SupportTicket::where('status', 'open')->count();
        $inProgressCount = SupportTicket::where('status', 'in_progress')->count();
        $resolvedCount = SupportTicket::where('status', 'resolved')->count();
        $urgentCount = SupportTicket::where('priority', 'urgent')->where('status', '!=', 'resolved')->count();

        // Support team members for assignment
        $supportAgents = User::whereHas('roleRelation', function ($q) {
            $q->whereIn('code', ['support_agent', 'support_lead']);
        })->orWhereIn('role', ['support_agent', 'support_lead'])->get();

        // Active chat sessions
        $activeChatCount = ChatMessage::where('sender_type', 'user')->where('is_read', false)->distinct('session_id')->count('session_id');

        $verifiedCustomer = null;
        if ($request->filled('verify_customer')) {
            $verifiedCustomer = User::where('phone', $request->verify_customer)
                ->orWhere('email', $request->verify_customer)
                ->orWhere('username', $request->verify_customer)
                ->first();
        }

        $isLead = true;

        return view('support.support-dashboard', compact(
            'tickets',
            'openCount',
            'inProgressCount',
            'resolvedCount',
            'urgentCount',
            'verifiedCustomer',
            'supportAgents',
            'activeChatCount',
            'isLead'
        ));
    }

    /**
     * Dedicated Support Agent Dashboard
     */
    public function agentDashboard(Request $request)
    {
        $user = Auth::user();

        $query = SupportTicket::with(['replies', 'assignee'])->orderBy('id', 'desc');

        // Agent queue: assigned to me or unassigned open
        $query->where(function ($q) use ($user) {
            $q->where('assigned_to', $user->id)
              ->orWhereNull('assigned_to')
              ->orWhere('status', 'open');
        });

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('ticket_number', 'like', "%{$s}%")
                  ->orWhere('customer_name', 'like', "%{$s}%")
                  ->orWhere('customer_phone', 'like', "%{$s}%")
                  ->orWhere('subject', 'like', "%{$s}%");
            });
        }

        $tickets = $query->paginate(12);

        // Stats
        $openCount = SupportTicket::where('status', 'open')->count();
        $inProgressCount = SupportTicket::where('status', 'in_progress')->where('assigned_to', $user->id)->count();
        $resolvedCount = SupportTicket::where('status', 'resolved')->where('assigned_to', $user->id)->count();
        $urgentCount = SupportTicket::where('priority', 'urgent')->where('status', '!=', 'resolved')->count();

        $supportAgents = collect();
        $activeChatCount = ChatMessage::where('sender_type', 'user')->where('is_read', false)->distinct('session_id')->count('session_id');

        $verifiedCustomer = null;
        if ($request->filled('verify_customer')) {
            $verifiedCustomer = User::where('phone', $request->verify_customer)
                ->orWhere('email', $request->verify_customer)
                ->orWhere('username', $request->verify_customer)
                ->first();
        }

        $isLead = false;

        return view('support.support-dashboard', compact(
            'tickets',
            'openCount',
            'inProgressCount',
            'resolvedCount',
            'urgentCount',
            'verifiedCustomer',
            'supportAgents',
            'activeChatCount',
            'isLead'
        ));
    }

    /**
     * Reply to a ticket.
     */
    public function reply(Request $request, $ticketId)
    {
        $user = Auth::user();
        if (!$user->hasPermission('chat.reply') && !$user->hasPermission('tickets.reply')) {
            return back()->with('error', "Action Forbidden: 'chat.reply' বা 'tickets.reply' পারমিশন নেই।");
        }

        $request->validate([
            'message' => 'required|string',
            'status'  => 'nullable|string|in:open,in_progress,resolved,closed,escalated',
        ]);

        $ticket = SupportTicket::findOrFail($ticketId);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id'   => $user->id,
            'user_name' => $user->name,
            'message'   => $request->message,
            'is_staff'  => true,
        ]);

        $updateData = ['last_reply_at' => now()];
        if ($request->filled('status')) {
            $updateData['status'] = $request->status;
        } elseif ($ticket->status === 'open') {
            $updateData['status'] = 'in_progress';
        }

        $ticket->update($updateData);

        AuditLog::log('TICKET_REPLIED', $ticket, null, null, "Support {$user->name} replied to ticket #{$ticket->ticket_number}");

        return back()->with('success', 'টিকেট বার্তা সফলভাবে প্রেরণ করা হয়েছে।');
    }

    /**
     * Assign ticket to an agent (Support Lead / Admin only).
     */
    public function assignTicket(Request $request, $ticketId)
    {
        $user = Auth::user();
        if (!$user->hasPermission('tickets.assign')) {
            return back()->with('error', "Action Forbidden: 'tickets.assign' পারমিশন নেই।");
        }

        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $ticket = SupportTicket::findOrFail($ticketId);
        $agent = User::findOrFail($request->assigned_to);

        $prevAssignee = $ticket->assigned_to;
        $ticket->update([
            'assigned_to' => $agent->id,
            'status'      => ($ticket->status === 'open') ? 'in_progress' : $ticket->status,
        ]);

        AuditLog::log(
            'TICKET_ASSIGNED',
            $ticket,
            ['assigned_to' => $prevAssignee],
            ['assigned_to' => $agent->id, 'agent_name' => $agent->name],
            "Ticket #{$ticket->ticket_number} assigned to {$agent->name}"
        );

        return back()->with('success', "টিকেটটি সফলভাবে '{$agent->name}'-এর নিকট হস্তান্তর করা হয়েছে।");
    }

    /**
     * Update ticket status.
     */
    public function updateStatus(Request $request, $ticketId)
    {
        $ticket = SupportTicket::findOrFail($ticketId);
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed,escalated',
        ]);

        $prevStatus = $ticket->status;
        $ticket->update(['status' => $request->status]);

        AuditLog::log(
            'TICKET_STATUS_UPDATED',
            $ticket,
            ['status' => $prevStatus],
            ['status' => $request->status],
            "Ticket #{$ticket->ticket_number} status changed to {$request->status}"
        );

        return back()->with('success', "টিকেট স্ট্যাটাস সফলভাবে '{$request->status}' করা হয়েছে।");
    }
}
