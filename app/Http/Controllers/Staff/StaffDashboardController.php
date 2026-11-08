<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\MonthlyBazaarOrder;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('portal:/staff');
    }

    public function index()
    {
        $staff = Auth::user();

        // Operational Tasks Queue: Pending orders & assigned tasks
        $pendingOrders = class_exists(MonthlyBazaarOrder::class)
            ? MonthlyBazaarOrder::with(['user', 'item'])->where('status', 'pending')->take(8)->get()
            : collect();

        // Assigned customer inquiries
        $assignedTickets = SupportTicket::where('assigned_to', $staff->id)
            ->orWhere('status', 'open')
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();

        $completedTasksCount = 14; // Metric count for daily operational activities
        $pendingTasksCount = $pendingOrders->count() + $assignedTickets->count();

        return view('staff.staff-dashboard', compact(
            'staff',
            'pendingOrders',
            'assignedTickets',
            'completedTasksCount',
            'pendingTasksCount'
        ));
    }

    public function escalateTask(Request $request, $ticketId)
    {
        $ticket = SupportTicket::findOrFail($ticketId);
        $ticket->update([
            'status' => 'escalated',
        ]);

        AuditLog::log('TICKET_ESCALATED_BY_STAFF', $ticket, null, null, "Staff escalated ticket #{$ticket->ticket_number} to management");

        return back()->with('success', "টিকেট '{$ticket->ticket_number}' সফলভাবে সুপারভাইজরের কাছে এস্কেলেট (Escalated) করা হয়েছে।");
    }
}
