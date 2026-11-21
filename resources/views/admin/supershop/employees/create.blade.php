@extends('layouts.backend.app')

@section('title', 'নতুন কর্মচারী নিবন্ধন ও বায়োডাটা ফরম (Add New Employee)')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h4 class="fw-bold mb-1 text-dark">
                <i class="ti-user text-success me-2"></i> নতুন কর্মচারী বায়োডাটা ও তথ্য নিবন্ধন ফরম
            </h4>
            <p class="text-muted small mb-0">শাখাভিত্তিক কর্মচারী তথ্য, এনআইডি, ছবি, বেতন কাঠামো ও ডকুমেন্টস সংরক্ষণ করুন।</p>
        </div>
        <div>
            <a href="{{ route('admin.supershop.employee_system.employees.index') }}" class="btn btn-outline-secondary">
                <i class="ti-arrow-left me-1"></i> কর্মচারীদের তালিকা
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4">
            <h6 class="fw-bold mb-2"><i class="ti-alert me-1"></i> অনুগ্রহ করে নিচের ত্রুটিগুলো সংশোধন করুন:</h6>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.supershop.employee_system.employees.store') }}" method="POST" enctype="multipart/form-data" id="employeeForm">
        @csrf

        <div class="row g-4">
            <div class="col-lg-12">
                <!-- Tab Navigation Headers with Mobile Horizontal Scroll -->
                <ul class="nav nav-pills bg-white p-2 rounded-3 shadow-sm mb-4 border flex-nowrap overflow-auto gap-1" id="employeeTab" role="tablist" style="scrollbar-width: thin;">
                    <li class="nav-item flex-shrink-0" role="presentation">
                        <button class="nav-link active fw-bold py-2 px-3 text-nowrap" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">
                            <i class="ti-id-badge me-1"></i> ১. ব্যক্তিগত তথ্য (Personal)
                        </button>
                    </li>
                    <li class="nav-item flex-shrink-0" role="presentation">
                        <button class="nav-link fw-bold py-2 px-3 text-nowrap" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab">
                            <i class="ti-mobile me-1"></i> ২. যোগাযোগের তথ্য (Contact)
                        </button>
                    </li>
                    <li class="nav-item flex-shrink-0" role="presentation">
                        <button class="nav-link fw-bold py-2 px-3 text-nowrap" id="employment-tab" data-bs-toggle="tab" data-bs-target="#employment" type="button" role="tab">
                            <i class="ti-briefcase me-1"></i> ৩. চাকরির তথ্য (Employment)
                        </button>
                    </li>
                    <li class="nav-item flex-shrink-0" role="presentation">
                        <button class="nav-link fw-bold py-2 px-3 text-nowrap" id="salary-tab" data-bs-toggle="tab" data-bs-target="#salary" type="button" role="tab">
                            <i class="ti-money me-1"></i> ৪. বেতন কাঠামো (Salary)
                        </button>
                    </li>
                    <li class="nav-item flex-shrink-0" role="presentation">
                        <button class="nav-link fw-bold py-2 px-3 text-nowrap" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">
                            <i class="ti-files me-1"></i> ৫. ডকুমেন্টস ও NID (Files)
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="employeeTabContent">

                    <!-- ========================================================================= -->
                    <!-- 5.1 Personal Information -->
                    <!-- ========================================================================= -->
                    <div class="tab-pane fade show active" id="personal" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-3 p-4">
                            <h5 class="fw-bold text-primary border-bottom pb-2 mb-4">
                                <i class="ti-id-badge me-2"></i> ৫.১ Personal Information (ব্যক্তিগত তথ্য)
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">কর্মচারীর পূর্ণ নাম <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" placeholder="যেমন: মোহাম্মদ রফিকুল ইসলাম" value="{{ old('name') }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Employee ID <span class="text-danger">*</span></label>
                                    <input type="text" name="employee_id" class="form-control font-monospace fw-bold" value="{{ old('employee_id', $suggestedEmployeeId) }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">প্রোফাইল ছবি (Photo)</label>
                                    <input type="file" name="profile_photo" class="form-control" accept="image/*">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">পিতার নাম (Father's Name)</label>
                                    <input type="text" name="father_name" class="form-control" placeholder="পিতার পূর্ণ নাম" value="{{ old('father_name') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">মাতার নাম (Mother's Name)</label>
                                    <input type="text" name="mother_name" class="form-control" placeholder="মাতার পূর্ণ নাম" value="{{ old('mother_name') }}">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">জন্ম তারিখ (Date of Birth)</label>
                                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">লিঙ্গ (Gender)</label>
                                    <select name="gender" class="form-select">
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>পুরুষ (Male)</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>মহিলা (Female)</option>
                                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>অন্যান্য (Other)</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">বৈবাহিক অবস্থা (Marital Status)</label>
                                    <select name="marital_status" class="form-select">
                                        <option value="Single" {{ old('marital_status') == 'Single' ? 'selected' : '' }}>অবিবাহিত (Single)</option>
                                        <option value="Married" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>বিবাহিত (Married)</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">রক্তের গ্রুপ (Blood Group)</label>
                                    <select name="blood_group" class="form-select">
                                        <option value="">-- নির্বাচন করুন --</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">জাতীয়তা (Nationality)</label>
                                    <input type="text" name="nationality" class="form-control" value="{{ old('nationality', 'Bangladeshi') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">জাতীয় পরিচয়পত্র নম্বর (NID Number)</label>
                                    <input type="text" name="nid_number" class="form-control font-monospace" placeholder="10/13/17 অঙ্কের NID নম্বর" value="{{ old('nid_number') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">পাসপোর্ট নম্বর (ঐচ্ছিক)</label>
                                    <input type="text" name="passport_number" class="form-control" placeholder="Passport No (if available)" value="{{ old('passport_number') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">জরুরি যোগাযোগ ব্যক্তির নাম ও সম্পর্ক</label>
                                    <input type="text" name="emergency_contact_relation" class="form-control" placeholder="যেমন: ভাই / পিতা / স্ত্রী" value="{{ old('emergency_contact_relation') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">জরুরি মোবাইল নম্বর</label>
                                    <input type="text" name="emergency_contact" class="form-control" placeholder="017XXXXXXXX" value="{{ old('emergency_contact') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================================================= -->
                    <!-- 5.2 Contact Information -->
                    <!-- ========================================================================= -->
                    <div class="tab-pane fade" id="contact" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-3 p-4">
                            <h5 class="fw-bold text-success border-bottom pb-2 mb-4">
                                <i class="ti-mobile me-2"></i> ৫.২ Contact Information (যোগাযোগ ও ঠিকানা)
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">মোবাইল নম্বর <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control" placeholder="01XXXXXXXXX" value="{{ old('phone') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">বিকল্প মোবাইল নম্বর</label>
                                    <input type="text" name="alt_phone" class="form-control" placeholder="Alternative mobile number" value="{{ old('alt_phone') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">ইমেইল অ্যাড্রেস</label>
                                    <input type="email" name="email" class="form-control" placeholder="staff@example.com" value="{{ old('email') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">বর্তমান ঠিকানা (Present Address)</label>
                                    <textarea name="present_address" class="form-control" rows="3" placeholder="বাসা নম্বর, রোড, এলাকা...">{{ old('present_address') }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">স্থায়ী ঠিকানা (Permanent Address)</label>
                                    <textarea name="permanent_address" class="form-control" rows="3" placeholder="গ্রাম, ডাকঘর, থানা...">{{ old('permanent_address') }}</textarea>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">জেলা (District)</label>
                                    <input type="text" name="district" class="form-control" placeholder="যেমন: ঢাকা" value="{{ old('district') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">উপজেলা / থানা (Upazila)</label>
                                    <input type="text" name="upazila" class="form-control" placeholder="যেমন: ধানমন্ডি" value="{{ old('upazila') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">পোস্ট অফিস (Post Office)</label>
                                    <input type="text" name="post_office" class="form-control" placeholder="যেমন: হেড পোস্ট অফিস" value="{{ old('post_office') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">পোস্টাল কোড (Postal Code)</label>
                                    <input type="text" name="postal_code" class="form-control" placeholder="যেমন: ১২০৫" value="{{ old('postal_code') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================================================= -->
                    <!-- 5.3 Employment Information -->
                    <!-- ========================================================================= -->
                    <div class="tab-pane fade" id="employment" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-3 p-4">
                            <h5 class="fw-bold text-info border-bottom pb-2 mb-4">
                                <i class="ti-briefcase me-2"></i> ৫.৩ Employment Information (চাকরি ও পদায়ন)
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">নিযুক্ত ব্রাঞ্চ (Branch) <span class="text-danger">*</span></label>
                                    @if($isAdmin)
                                        <select name="branch_id" class="form-select fw-bold" required>
                                            @foreach($branches as $b)
                                                <option value="{{ $b->id }}" {{ old('branch_id') == $b->id ? 'selected' : '' }}>
                                                    {{ $b->code }} - {{ $b->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="hidden" name="branch_id" value="{{ $userBranchId }}">
                                        <input type="text" class="form-control bg-light fw-bold text-primary" value="{{ $branches->first()->name ?? 'আমার ব্রাঞ্চ' }}" readonly>
                                        <small class="text-muted"><i class="ti-lock"></i> ব্রাঞ্চ ম্যানেজার শুধুমাত্র তার নিজের ব্রাঞ্চেই কর্মী যোগ করতে পারে (Section 4)।</small>
                                    @endif
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold">পদবি (Designation) <span class="text-danger">*</span></label>
                                    <input type="text" name="designation" class="form-control" placeholder="যেমন: Sales Executive, Cashier" value="{{ old('designation') }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">বিভাগ (Department)</label>
                                    <select name="department" class="form-select">
                                        <option value="Sales & POS">Sales & POS</option>
                                        <option value="Inventory & Warehouse">Inventory & Warehouse</option>
                                        <option value="Store Management">Store Management</option>
                                        <option value="Accounts & Cash">Accounts & Cash</option>
                                        <option value="Customer Support">Customer Support</option>
                                        <option value="Logistics">Logistics</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">চাকরির ধরন (Job Type)</label>
                                    <select name="job_type" class="form-select">
                                        <option value="Full Time">ফুল-টাইম (Full Time)</option>
                                        <option value="Part Time">পার্ট-টাইম (Part Time)</option>
                                        <option value="Contract">চুক্তিভিত্তিক (Contract)</option>
                                        <option value="Intern">ইন্টার্ন (Intern)</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">যোগদানের তারিখ (Joining Date)</label>
                                    <input type="date" name="joining_date" class="form-control" value="{{ old('joining_date', date('Y-m-d')) }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">কর্মচারী টাইপ (Employee Type)</label>
                                    <select name="employee_type" class="form-select">
                                        <option value="Staff">স্টাফ (Staff)</option>
                                        <option value="Manager">ব্রাঞ্চ ম্যানেজার (Manager)</option>
                                        <option value="Cashier">ক্যাশিয়ার (Cashier)</option>
                                        <option value="Supervisor">সুপারভাইজার (Supervisor)</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">কর্মঘণ্টার শিফট (Working Shift)</label>
                                    <select name="shift_id" class="form-select">
                                        @foreach($shifts as $s)
                                            <option value="{{ $s->id }}">{{ $s->shift_name }} ({{ date('h:i A', strtotime($s->start_time)) }} - {{ date('h:i A', strtotime($s->end_time)) }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">রিপোর্টিং ব্যক্তি (Reporting Person)</label>
                                    <input type="text" name="reporting_person" class="form-control" placeholder="যেমন: Branch Manager" value="{{ old('reporting_person', 'Branch Manager') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">চাকরির বর্তমান অবস্থা (Status)</label>
                                    <select name="employment_status" class="form-select">
                                        <option value="active">সক্রিয় (Active)</option>
                                        <option value="probation">প্রবেশন / শিক্ষানবিস (Probation)</option>
                                        <option value="inactive">নিষ্ক্রিয় (Inactive)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================================================= -->
                    <!-- 5.4 Salary Information -->
                    <!-- ========================================================================= -->
                    <div class="tab-pane fade" id="salary" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-3 p-4">
                            <h5 class="fw-bold text-warning border-bottom pb-2 mb-4">
                                <i class="ti-money me-2"></i> ৫.৪ Salary Information (বেতন ও ভাতাসমূহ)
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">মূল বেতন (Basic Salary) ৳</label>
                                    <input type="number" step="0.01" name="basic_salary" id="basic_salary" class="form-control fw-bold" placeholder="0.00" value="{{ old('basic_salary', 0) }}" oninput="calculateTotalSalary()">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">বাড়ি ভাড়া ভাতা (House Rent) ৳</label>
                                    <input type="number" step="0.01" name="house_rent" id="house_rent" class="form-control" placeholder="0.00" value="{{ old('house_rent', 0) }}" oninput="calculateTotalSalary()">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">চিকিৎসা ভাতা (Medical Allowance) ৳</label>
                                    <input type="number" step="0.01" name="medical_allowance" id="medical_allowance" class="form-control" placeholder="0.00" value="{{ old('medical_allowance', 0) }}" oninput="calculateTotalSalary()">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">যাতায়াত ভাতা (Transport Allowance) ৳</label>
                                    <input type="number" step="0.01" name="transport_allowance" id="transport_allowance" class="form-control" placeholder="0.00" value="{{ old('transport_allowance', 0) }}" oninput="calculateTotalSalary()">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">খাবার ভাতা (Food Allowance) ৳</label>
                                    <input type="number" step="0.01" name="food_allowance" id="food_allowance" class="form-control" placeholder="0.00" value="{{ old('food_allowance', 0) }}" oninput="calculateTotalSalary()">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">অন্যান্য ভাতা (Other Allowance) ৳</label>
                                    <input type="number" step="0.01" name="other_allowance" id="other_allowance" class="form-control" placeholder="0.00" value="{{ old('other_allowance', 0) }}" oninput="calculateTotalSalary()">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">ওভারটাইম রেট (টাকা/ঘণ্টা)</label>
                                    <input type="number" step="0.01" name="overtime_rate" class="form-control" placeholder="100.00" value="{{ old('overtime_rate', 150) }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold text-success">মোট মাসিক বেতন (Monthly Gross) ৳</label>
                                    <input type="text" id="total_gross_display" class="form-control fw-bold text-success fs-5 bg-light" value="৳0.00" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">বেতন পরিশোধের মাধ্যম</label>
                                    <select name="salary_payment_method" class="form-select">
                                        <option value="cash">নগদ ক্যাশ (Cash)</option>
                                        <option value="bank">ব্যাংক একাউন্ট (Bank Account)</option>
                                        <option value="mobile_banking">মোবাইল ব্যাংকিং (bKash/Nagad)</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">ব্যাংকের নাম ও শাখা</label>
                                    <input type="text" name="bank_name" class="form-control" placeholder="যেমন: Islami Bank / City Bank" value="{{ old('bank_name') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">ব্যাংক একাউন্ট নম্বর</label>
                                    <input type="text" name="bank_account_no" class="form-control font-monospace" placeholder="A/C Number" value="{{ old('bank_account_no') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">বিকাশ / নগদ নম্বর (মোবাইল ওয়ালেট)</label>
                                    <input type="text" name="mobile_wallet_number" class="form-control" placeholder="01XXXXXXXXX" value="{{ old('mobile_wallet_number') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================================================= -->
                    <!-- 5.5 Documents & NID -->
                    <!-- ========================================================================= -->
                    <div class="tab-pane fade" id="documents" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-3 p-4">
                            <h5 class="fw-bold text-secondary border-bottom pb-2 mb-4">
                                <i class="ti-files me-2"></i> ৫.৫ Documents & NID (নিরাপদ ব্যক্তিগত নথি আপলোড)
                            </h5>
                            <div class="alert alert-warning border-0 small">
                                <i class="ti-shield me-1"></i> <strong>সিকিউরিটি নোট:</strong> এনআইডি ও ব্যক্তিগত সনদপত্রসমূহ প্রাইভেট স্টোরেজে সংরক্ষিত থাকে। কোনো পাবলিক ইউআরএল দিয়ে সরাসরি এক্সেস করা যাবে না। শুধুমাত্র অনুমোদিত কর্তৃপক্ষ ভিউ ও ডাউনলোড করতে পারবে।
                            </div>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="border rounded p-3 bg-light">
                                        <label class="form-label fw-bold"><i class="ti-id-badge text-primary me-1"></i> NID কার্ডের সামনের ছবি (Front Side)</label>
                                        <input type="file" name="nid_front" class="form-control" accept="image/*,application/pdf">
                                        <small class="text-muted">JPG, PNG অথবা PDF ফাইল (সর্বোচ্চ 3MB)</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded p-3 bg-light">
                                        <label class="form-label fw-bold"><i class="ti-id-badge text-primary me-1"></i> NID কার্ডের পিছনের ছবি (Back Side)</label>
                                        <input type="file" name="nid_back" class="form-control" accept="image/*,application/pdf">
                                        <small class="text-muted">JPG, PNG অথবা PDF ফাইল (সর্বোচ্চ 3MB)</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded p-3 bg-light">
                                        <label class="form-label fw-bold"><i class="ti-file text-success me-1"></i> জীবনবৃত্তান্ত (CV / Resume)</label>
                                        <input type="file" name="cv" class="form-control" accept="application/pdf,image/*">
                                        <small class="text-muted">PDF অথবা ডকুমেন্ট ফরম্যাট</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded p-3 bg-light">
                                        <label class="form-label fw-bold"><i class="ti-clipboard text-warning me-1"></i> নিয়োগপত্র (Appointment Letter)</label>
                                        <input type="file" name="appointment_letter" class="form-control" accept="application/pdf,image/*">
                                        <small class="text-muted">PDF অথবা স্ক্যান কপি</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Submit Toolbar -->
                <div class="card border-0 shadow-sm rounded-3 mt-4 p-3 bg-white">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <span class="text-muted small"><i class="ti-info-alt text-primary"></i> সকল তথ্য যাচাই করে সংরক্ষণের জন্য নিচের বাটনে চাপ দিন।</span>
                        <div>
                            <button type="reset" class="btn btn-outline-secondary me-2">রিসেট</button>
                            <button type="submit" class="btn btn-success px-4 py-2 fw-bold shadow-sm">
                                <i class="ti-check me-1"></i> কর্মচারী বায়োডাটা ও তথ্য সংরক্ষণ করুন
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>

        </section>
    </div>
</div>

<script>
function calculateTotalSalary() {
    let basic = parseFloat(document.getElementById('basic_salary').value) || 0;
    let house = parseFloat(document.getElementById('house_rent').value) || 0;
    let medical = parseFloat(document.getElementById('medical_allowance').value) || 0;
    let transport = parseFloat(document.getElementById('transport_allowance').value) || 0;
    let food = parseFloat(document.getElementById('food_allowance').value) || 0;
    let other = parseFloat(document.getElementById('other_allowance').value) || 0;

    let total = basic + house + medical + transport + food + other;
    document.getElementById('total_gross_display').value = '৳' + total.toFixed(2);
}
document.addEventListener('DOMContentLoaded', calculateTotalSalary);
</script>
@endsection
