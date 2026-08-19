<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    /**
     * Fetch chat history for the active visitor session or logged in user.
     */
    public function fetchMessages(Request $request)
    {
        $sessionId = $request->input('session_id') ?: session()->getId();

        $messages = ChatMessage::where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'session_id' => $sessionId,
            'messages' => $messages
        ]);
    }

    /**
     * Store visitor/user message and trigger AI Bot response if applicable.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $sessionId = $request->input('session_id') ?: session()->getId();
        $user = Auth::user();
        $userId = $user ? $user->id : null;
        $userName = $user ? $user->name : ($request->input('sender_name') ?: 'Guest Visitor');
        $userRole = $user ? ($user->role ?: 'user') : 'user';

        // Save User Message
        $userMsg = ChatMessage::create([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'sender_type' => 'user',
            'sender_name' => $userName,
            'user_role' => $userRole,
            'message' => trim($request->input('message')),
            'is_read' => false,
        ]);

        // Generate AI Assistant Reply
        $aiReplyText = $this->generateAiReply(trim($request->input('message')));

        if ($aiReplyText) {
            ChatMessage::create([
                'session_id' => $sessionId,
                'user_id' => null,
                'sender_type' => 'bot',
                'sender_name' => 'কৃষি পরিবার AI Assistant',
                'user_role' => $userRole,
                'message' => $aiReplyText,
                'is_read' => true,
            ]);
        }

        $messages = ChatMessage::where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'session_id' => $sessionId,
            'messages' => $messages
        ]);
    }

    /**
     * Generate smart Bengali/English AI response based on keyword detection.
     */
    private function generateAiReply($text)
    {
        $lower = mb_strtolower($text, 'UTF-8');

        if (Str::contains($lower, ['স্টক', 'শেয়ার', 'স্টক রেট', 'দাম', 'stock', 'rate', 'price'])) {
            return "🤖 **কৃষি পরিবার স্টক মার্কেট তথ্য:**\nআমাদের প্ল্যাটফর্মে দৈনিক স্টক রেট এবং মার্কেট আপডেট পেতে উপরের 'স্টক মার্কেট' পেজটি ভিজিট করুন। আপনার কোনো নির্দিষ্ট স্টক ক্রয়/বিক্রয় সংক্রান্ত হেল্প লাগলে আমাদের এডমিন প্রতিনিধি খুব শীঘ্রই চ্যাটে যুক্ত হবেন।";
        }

        if (Str::contains($lower, ['মাসিক বাজার', 'বাজার', 'প্যাকেজ', 'monthly', 'bazaar', 'package'])) {
            return "🤖 **মাসিক বাজার ও প্যাকেজসমূহ:**\nআমাদের প্রিমিয়াম প্যাকেজসমূহ এবং মাসিক বাজারের সুবিধা জানতে হোমপেজের 'আওয়ার প্যাকেজসমূহ' সেকশন ভিজিট করুন। কোনো প্রশ্ন থাকলে সরাসরি মেসেজ লিখে পাঠান।";
        }

        if (Str::contains($lower, ['রেজিস্টার', 'একাউন্ট', 'সাইনআপ', 'খুলবো', 'register', 'account', 'signup'])) {
            return "🤖 **একাউন্ট নিবন্ধন নির্দেশিকা:**\nনতুন একাউন্ট খুলতে মেনুবারের 'রেজিস্টার' অপশনে ক্লিক করুন। গ্রাহক, এজেন্ট, সাপ্লায়ার বা এমপ্লয়ী ড্যাশবোর্ড পেতে নিবন্ধনের সময় সঠিক রোল নির্বাচন করুন।";
        }

        if (Str::contains($lower, ['এজেন্ট', 'agent', 'কমিশন'])) {
            return "🤖 **এজেন্ট সাপোর্ট:**\nনিবন্ধিত এজেন্টদের জন্য রিয়েল-টাইম কাস্টমার লাইভ হিসাব ও বিশেষ সুবিধা দেওয়া রয়েছে। এজেন্ট সংক্রান্ত তথ্যের জন্য এডমিনকে মেসেজ জানান।";
        }

        if (Str::contains($lower, ['ফোন', 'হেল্পলাইন', 'যোগাযোগ', 'ঠিকানা', 'contact', 'phone', 'number', 'address', 'help'])) {
            return "🤖 **কাস্টমার সাপোর্ট হেল্পলাইন:**\nআমাদের অফিসে যোগাযোগের ঠিকানা ও ফোন নম্বরের জন্য ওয়েবসাইটের 'যোগাযোগ' পেজ ভিজিট করুন। আপনার প্রশ্নের উত্তর দিতে আমাদের প্রতিনিধি প্রস্তুত আছে।";
        }

        if (Str::contains($lower, ['hi', 'hello', 'হাই', 'হ্যালো', 'সালাম', 'assalamu', 'salam'])) {
            return "🤖 **আসসালামু আলাইকুম!** কৃষি পরিবার লাইভ সাপোর্ট হেল্পডেস্কে আপনাকে স্বাগতম। আপনাকে কীভাবে সাহায্য করতে পারি?";
        }

        return "🤖 ধন্যবাদ আপনার বার্তার জন্য! আমাদের কৃষি পরিবার সাপোর্ট এডমিন প্রতিনিধি খুব শীঘ্রই আপনার প্রশ্নের উত্তর দেবেন। আপতত যেকোনো তথ্য পেতে 'স্টক মার্কেট', 'মাসিক বাজার' বা 'যোগাযোগ' ভিজিট করতে পারেন।";
    }

    /**
     * Admin Panel Live Chat Hub - View active chats filtered by user role (user, employee, supplier, agent).
     */
    public function adminIndex(Request $request)
    {
        $selectedRole = $request->query('role'); // 'user', 'employee', 'supplier', 'agent'

        $query = ChatMessage::select('session_id')
            ->selectRaw('MAX(created_at) as last_activity')
            ->selectRaw('COUNT(id) as total_messages')
            ->selectRaw('SUM(CASE WHEN is_read = 0 AND sender_type = "user" THEN 1 ELSE 0 END) as unread_count')
            ->groupBy('session_id');

        if (!empty($selectedRole)) {
            if ($selectedRole === 'user') {
                $query->where(function($q) {
                    $q->where('user_role', 'user')
                      ->orWhereNull('user_role')
                      ->orWhereHas('user', function($uQuery) {
                          $uQuery->where('role', 'user');
                      });
                });
            } elseif ($selectedRole === 'employee') {
                $query->where(function($q) {
                    $q->where('user_role', 'employee')
                      ->orWhereHas('user', function($uQuery) {
                          $uQuery->where('role', 'employee');
                      });
                });
            } elseif ($selectedRole === 'supplier') {
                $query->where(function($q) {
                    $q->where('user_role', 'supplier')
                      ->orWhereHas('user', function($uQuery) {
                          $uQuery->where('role', 'supplier');
                      });
                });
            } elseif ($selectedRole === 'agent') {
                $query->where(function($q) {
                    $q->whereIn('user_role', ['agent', 'employee'])
                      ->orWhereHas('user', function($uQuery) {
                          $uQuery->whereIn('role', ['agent', 'employee']);
                      });
                });
            }
        }

        $sessions = $query->orderBy('last_activity', 'desc')->get();

        foreach ($sessions as $session) {
            $session->last_message = ChatMessage::where('session_id', $session->session_id)
                ->orderBy('created_at', 'desc')
                ->first();

            $session->user_info = ChatMessage::where('session_id', $session->session_id)
                ->whereNotNull('sender_name')
                ->where('sender_type', 'user')
                ->first();

            if (!$session->user_info && $session->last_message) {
                $session->user_info = $session->last_message;
            }

            // Determine role tag for session
            $roleTag = 'user';
            if ($session->last_message && !empty($session->last_message->user_role)) {
                $roleTag = $session->last_message->user_role;
            } elseif ($session->user_info && $session->user_info->user) {
                $roleTag = $session->user_info->user->role ?: 'user';
            }
            $session->detected_role = $roleTag;
        }

        // Unread counts per role
        $counts = [
            'user' => ChatMessage::where('is_read', 0)->where('sender_type', 'user')->where(function($q){ $q->where('user_role', 'user')->orWhereNull('user_role'); })->distinct('session_id')->count('session_id'),
            'employee' => ChatMessage::where('is_read', 0)->where('sender_type', 'user')->where('user_role', 'employee')->distinct('session_id')->count('session_id'),
            'supplier' => ChatMessage::where('is_read', 0)->where('sender_type', 'user')->where('user_role', 'supplier')->distinct('session_id')->count('session_id'),
            'agent' => ChatMessage::where('is_read', 0)->where('sender_type', 'user')->whereIn('user_role', ['agent', 'employee'])->distinct('session_id')->count('session_id'),
            'all' => ChatMessage::where('is_read', 0)->where('sender_type', 'user')->distinct('session_id')->count('session_id'),
        ];

        return view('admin.chat.index', compact('sessions', 'selectedRole', 'counts'));
    }

    /**
     * Admin fetch messages for a specific session.
     */
    public function adminFetchSession($sessionId)
    {
        // Mark as read
        ChatMessage::where('session_id', $sessionId)
            ->where('sender_type', 'user')
            ->update(['is_read' => true]);

        $messages = ChatMessage::where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'session_id' => $sessionId,
            'messages' => $messages
        ]);
    }

    /**
     * Admin send reply to user session.
     */
    public function adminSendReply(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'message' => 'required|string|max:2000',
        ]);

        $sessionId = $request->input('session_id');

        // Detect user_role from existing session messages
        $existingMsg = ChatMessage::where('session_id', $sessionId)->whereNotNull('user_role')->first();
        $targetRole = $existingMsg ? $existingMsg->user_role : 'user';

        $reply = ChatMessage::create([
            'session_id' => $sessionId,
            'user_id' => Auth::id(),
            'sender_type' => 'admin',
            'sender_name' => Auth::user() ? Auth::user()->name : 'Admin Customer Support',
            'user_role' => $targetRole,
            'message' => trim($request->input('message')),
            'is_read' => true,
        ]);

        $messages = ChatMessage::where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'session_id' => $sessionId,
            'messages' => $messages
        ]);
    }
}
