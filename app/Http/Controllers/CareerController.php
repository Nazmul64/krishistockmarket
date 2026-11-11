<?php

namespace App\Http\Controllers;

use App\Models\CareerApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CareerController extends Controller
{
    public function index()
    {
        return view('frontend.career');
    }

    public function apply(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:150',
            'address' => 'required|string|max:500',
            'resume' => 'required|file|mimes:pdf|max:10240', // Max 10MB PDF
            'cover_letter' => 'nullable|string|max:3000',
            'position_applied' => 'nullable|string|max:150',
        ], [
            'first_name.required' => 'আপনার ফার্স্ট নেম প্রদান করুন।',
            'last_name.required' => 'আপনার লাস্ট নেম প্রদান করুন।',
            'phone.required' => 'আপনার ফোন নম্বর প্রদান করুন।',
            'address.required' => 'আপনার বর্তমান ঠিকানা প্রদান করুন।',
            'resume.required' => 'আপনার সিভি / রেজুমে (PDF ফাইল) আপলোড করুন।',
            'resume.mimes' => 'শুধুমাত্র PDF ফাইল আপলোড করা যাবে।',
            'resume.max' => 'পিডিএফ ফাইলের সর্বোচ্চ আকার ১০ মেগাবাইট।',
        ]);

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('upload/resumes');
            
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }

            $file->move($destinationPath, $filename);
            $resumePath = 'upload/resumes/' . $filename;
        }

        CareerApplication::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'resume' => $resumePath,
            'cover_letter' => $request->cover_letter,
            'position_applied' => $request->position_applied ?: 'General Career Application',
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'ধন্যবাদ! আপনার ক্যারিয়ার আবেদনটি সফলভাবে জমা হয়েছে। আমাদের এইচআর টিম আপনার সাথে যোগাযোগ করবে।');
    }

    public function apiApply(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:150',
            'address' => 'required|string|max:500',
            'resume' => 'required|file|mimes:pdf|max:10240',
            'cover_letter' => 'nullable|string|max:3000',
            'position_applied' => 'nullable|string|max:150',
        ]);

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('upload/resumes');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }

            $file->move($destinationPath, $filename);
            $resumePath = 'upload/resumes/' . $filename;
        }

        $application = CareerApplication::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'resume' => $resumePath,
            'cover_letter' => $request->cover_letter,
            'position_applied' => $request->position_applied ?: 'Mobile App Career Submission',
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'আপনার ক্যারিয়ার আবেদনটি সফলভাবে জমা হয়েছে!',
            'data' => [
                'id' => $application->id,
                'name' => $application->full_name,
                'resume_url' => asset($application->resume),
            ]
        ], 201);
    }

    public function apiCareerInfo()
    {
        return response()->json([
            'title' => 'ই-কৃষি সেবা ক্যারিয়ার পাথ (Career Path)',
            'description' => 'কৃষি ও ডিজিটাল প্রযুক্তির সমন্বয়ে আমাদের প্রতিষ্ঠানে ক্যারিয়ার গড়ে তুলুন।',
            'instructions' => [
                'ফার্স্ট নেম, লাস্ট নেম, ফোন নম্বর ও সঠিক ঠিকানা লিখুন।',
                'আপনার হালনাগাদ সিভি / রেজুমে (PDF ফরম্যাটে) আপলোড করুন।',
                'কভার লেটার ঐচ্ছিক (Optional), তবে লিখলে আপনার প্রোফাইল অগ্রাধিকার পাবে।',
            ],
            'available_roles' => [
                'ফিল্ড এগ্রো অফিসার (Field Agro Officer)',
                'মার্কেটিং & ডিস্ট্রিবিউশন এক্সিকিউটিভ (Marketing & Distribution)',
                'কাস্টমার সাপোর্ট রিপ্রেজেনটেটিভ (Support Representative)',
                'শাখা পরিচালক / ব্রাঞ্চ কো-অর্ডিনেটর (Branch Coordinator)',
                'জেনারেল অ্যাপ্লিকেশন (Open Application)',
            ]
        ]);
    }
}

