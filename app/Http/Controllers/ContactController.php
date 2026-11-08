<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $name = $request->name ?? $request->input('contact-name');
        $phone = $request->phone ?? $request->input('contact-phone');
        $email = $request->email ?? $request->input('contact-email');
        $subject = $request->subject ?? $request->input('contact-subject');
        $message = $request->message ?? $request->input('textarea-message');

        $request->merge([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'subject' => $subject,
            'message' => $message,
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ], [
            'name.required' => 'আপনার নাম দেয়া আবশ্যক',
            'phone.required' => 'আপনার ফোন নম্বর দেয়া আবশ্যক',
            'message.required' => 'বার্তা প্রদান করা আবশ্যক',
        ]);

        ContactMessage::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'unread',
        ]);

        $successMsg = 'ধন্যবাদ! আপনার বার্তাটি আমাদের কাছে পৌঁছেছে। আমরা দ্রুতই আপনার সাথে যোগাযোগ করব।';

        if ($request->wantsJson() || $request->ajax() || $request->header('Accept') === 'application/json') {
            return response()->json([
                'success' => true,
                'message' => $successMsg
            ], 200);
        }

        return back()->with('success', $successMsg);
    }
}
