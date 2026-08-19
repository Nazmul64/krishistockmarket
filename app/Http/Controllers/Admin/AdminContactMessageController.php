<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class AdminContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $messages = $query->orderBy('created_at', 'desc')->paginate(15);
        $unreadCount = ContactMessage::where('status', 'unread')->count();

        return view('admin.contact-messages.index', compact('messages', 'unreadCount'));
    }

    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);
        if ($message->status === 'unread') {
            $message->update(['status' => 'read']);
        }

        return response()->json([
            'status' => 'success',
            'data' => $message
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'status' => 'required|in:unread,read,replied',
        ]);

        $message = ContactMessage::findOrFail($id);
        $message->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => $request->status,
        ]);

        return back()->with('success', 'কন্টাক্ট বার্তাটি সফলভাবে আপডেট করা হয়েছে!');
    }

    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return back()->with('success', 'কন্টাক্ট বার্তাটি সফলভাবে মুছে ফেলা হয়েছে!');
    }
}
