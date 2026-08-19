<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\HrmApplicant;
use App\Models\HRM\HrmDepartment;
use App\Models\HRM\HrmDesignation;
use App\Models\HRM\HrmJobPost;
use Illuminate\Http\Request;

class HrmRecruitmentController extends Controller
{
    public function index()
    {
        $jobPosts = HrmJobPost::with(['department', 'designation', 'applicants'])->latest()->get();
        $departments = HrmDepartment::where('status', 'active')->get();
        $designations = HrmDesignation::where('status', 'active')->get();

        return view('admin.hrm.recruitment.jobs', compact('jobPosts', 'departments', 'designations'));
    }

    public function storeJob(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'nullable|exists:hrm_departments,id',
            'designation_id' => 'nullable|exists:hrm_designations,id',
            'vacancy' => 'required|numeric|min:1',
            'salary_range' => 'nullable|string',
            'deadline' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        HrmJobPost::create([
            'title' => $request->title,
            'department_id' => $request->department_id,
            'designation_id' => $request->designation_id,
            'vacancy' => $request->vacancy,
            'employment_type' => $request->employment_type ?? 'full_time',
            'salary_range' => $request->salary_range,
            'deadline' => $request->deadline,
            'description' => $request->description,
            'status' => 'active',
        ]);

        return back()->with('success', 'নতুন নিয়োগ সার্কুলার প্রকাশ করা হয়েছে।');
    }

    public function applicants($jobId)
    {
        $job = HrmJobPost::with('applicants')->findOrFail($jobId);
        $applicants = HrmApplicant::where('job_post_id', $jobId)->latest()->get();

        return view('admin.hrm.recruitment.applicants', compact('job', 'applicants'));
    }

    public function updateApplicantStatus(Request $request, $applicantId)
    {
        $request->validate([
            'status' => 'required|in:applied,shortlisted,interview,selected,rejected,hired',
        ]);

        $applicant = HrmApplicant::findOrFail($applicantId);
        $applicant->update(['status' => $request->status]);

        return back()->with('success', 'আবেদনকারীর স্ট্যাটাস আপডেট করা হয়েছে।');
    }
}
