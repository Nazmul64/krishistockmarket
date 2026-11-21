@extends('layouts.backend.app')

@section('title', 'কর্মচারী প্রোফাইল - ' . $employee->name)

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content">

    <!-- Profile Top Header Banner -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden">
        <div class="p-4 text-white" style="background: linear-gradient(135deg, #064e3b 0%, #059669 50%, #10b981 100%);">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div class="d-flex align-items-center mb-3 mb-md-0">
                    <img src="{{ $employee->profile_photo_url }}" class="rounded-circle border border-3 border-white shadow me-3 object-fit-cover" width="85" height="85" alt="{{ $employee->name }}">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h3 class="fw-bold mb-0 text-white">{{ $employee->name }}</h3>
                            <span class="badge bg-white text-dark font-monospace">{{ $employee->employee_id }}</span>
                            @if($employee->employment_status === 'active')
                                <span class="badge bg-success border border-white"><i class="ti-check"></i> সক্রিয় (Active)</span>
                            @else
                                <span class="badge bg-danger border border-white"><i class="ti-close"></i> নিষ্ক্রিয় (Inactive)</span>
                            @endif
                        </div>
                        <p class="mb-0 text-white-50 mt-1">
                            <i class="ti-briefcase me-1"></i> {{ $employee->designation }} | 
                            <i class="ti-home me-1"></i> {{ $employee->branch->name ?? '' }} ({{ $employee->branch->code ?? '' }}) |
                            <i class="ti-calendar me-1"></i> যোগদানের তারিখ: {{ optional($employee->joining_date)->format('d M Y') ?: 'N/A' }}
                        </p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.supershop.employee_system.employees.edit', $employee->id) }}" class="btn btn-light fw-bold text-dark shadow-sm">
                        <i class="ti-pencil me-1"></i> তথ্য এডিট করুন
                    </a>
                    <a href="{{ route('admin.supershop.employee_system.employees.index') }}" class="btn btn-outline-light">
                        <i class="ti-arrow-left me-1"></i> ব্যাক
                    </a>
                </div>
            </div>
        </div>

        <!-- 6.2 Employee Overview KPI Cards -->
        <div class="card-body bg-light p-3 border-top">
            <div class="row g-2 text-center">
                <div class="col-md-2 col-4">
                    <div class="bg-white p-2 rounded border">
                        <small class="text-muted d-block">বর্তমান বেতন</small>
                        <strong class="text-dark fs-6">৳{{ number_format($employee->monthly_salary, 2) }}</strong>
                    </div>
                </div>
                <div class="col-md-2 col-4">
                    <div class="bg-white p-2 rounded border">
                        <small class="text-muted d-block">মোট অনুপস্থিত</small>
                        <strong class="text-danger fs-6">{{ $totalAbsent }} দিন</strong>
                    </div>
                </div>
                <div class="col-md-2 col-4">
                    <div class="bg-white p-2 rounded border">
                        <small class="text-muted d-block">মোট ছুটি</small>
                        <strong class="text-info fs-6">{{ $totalLeave }} দিন</strong>
                    </div>
                </div>
                <div class="col-md-2 col-4">
                    <div class="bg-white p-2 rounded border">
                        <small class="text-muted d-block">মোট অগ্রিম গ্রহণ</small>
                        <strong class="text-warning fs-6">৳{{ number_format($totalAdvance, 2) }}</strong>
                    </div>
                </div>
                <div class="col-md-2 col-4">
                    <div class="bg-white p-2 rounded border">
                        <small class="text-muted d-block">বকেয়া অগ্রিম</small>
                        <strong class="text-danger fs-6">৳{{ number_format($advanceRemaining, 2) }}</strong>
                    </div>
                </div>
                <div class="col-md-2 col-4">
                    <div class="bg-white p-2 rounded border">
                        <small class="text-muted d-block">চলতি মাসে বাকি বেতন</small>
                        <strong class="text-success fs-6">৳{{ number_format($salaryPending, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 10 Tabs Navigation Bar with Horizontal Scroll for Mobile (Section 6) -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
        <ul class="nav nav-tabs px-3 pt-3 bg-white border-bottom flex-nowrap overflow-auto gap-1" id="profileTabs" role="tablist" style="scrollbar-width: thin;">
            <li class="nav-item flex-shrink-0">
                <a class="nav-link text-nowrap {{ $activeTab == 'overview' ? 'active fw-bold text-success' : 'text-dark' }}" data-bs-toggle="tab" href="#tab-overview">
                    <i class="ti-dashboard me-1"></i> সারসংক্ষেপ (Overview)
                </a>
            </li>
            <li class="nav-item flex-shrink-0">
                <a class="nav-link text-nowrap {{ $activeTab == 'personal' ? 'active fw-bold text-success' : 'text-dark' }}" data-bs-toggle="tab" href="#tab-personal">
                    <i class="ti-id-badge me-1"></i> ব্যক্তিগত তথ্য
                </a>
            </li>
            <li class="nav-item flex-shrink-0">
                <a class="nav-link text-nowrap {{ $activeTab == 'employment' ? 'active fw-bold text-success' : 'text-dark' }}" data-bs-toggle="tab" href="#tab-employment">
                    <i class="ti-briefcase me-1"></i> চাকরির তথ্য
                </a>
            </li>
            <li class="nav-item flex-shrink-0">
                <a class="nav-link text-nowrap {{ $activeTab == 'salary' ? 'active fw-bold text-success' : 'text-dark' }}" data-bs-toggle="tab" href="#tab-salary">
                    <i class="ti-money me-1"></i> বেতন কাঠামো
                </a>
            </li>
            <li class="nav-item flex-shrink-0">
                <a class="nav-link text-nowrap {{ $activeTab == 'attendance' ? 'active fw-bold text-success' : 'text-dark' }}" data-bs-toggle="tab" href="#tab-attendance">
                    <i class="ti-calendar me-1"></i> হাজিরা ইতিহাস
                </a>
            </li>
            <li class="nav-item flex-shrink-0">
                <a class="nav-link text-nowrap {{ $activeTab == 'leave' ? 'active fw-bold text-success' : 'text-dark' }}" data-bs-toggle="tab" href="#tab-leave">
                    <i class="ti-time me-1"></i> ছুটির হিসাব
                </a>
            </li>
            <li class="nav-item flex-shrink-0">
                <a class="nav-link text-nowrap {{ $activeTab == 'advance' ? 'active fw-bold text-success' : 'text-dark' }}" data-bs-toggle="tab" href="#tab-advance">
                    <i class="ti-wallet me-1"></i> বেতন অগ্রিম (Advance)
                </a>
            </li>
            <li class="nav-item flex-shrink-0">
                <a class="nav-link text-nowrap {{ $activeTab == 'payment' ? 'active fw-bold text-success' : 'text-dark' }}" data-bs-toggle="tab" href="#tab-payment">
                    <i class="ti-receipt me-1"></i> বেতন পরিশোধ ইতিহাস
                </a>
            </li>
            <li class="nav-item flex-shrink-0">
                <a class="nav-link text-nowrap {{ $activeTab == 'documents' ? 'active fw-bold text-success' : 'text-dark' }}" data-bs-toggle="tab" href="#tab-documents">
                    <i class="ti-files me-1"></i> ডকুমেন্টস ও NID
                </a>
            </li>
            <li class="nav-item flex-shrink-0">
                <a class="nav-link text-nowrap {{ $activeTab == 'activity' ? 'active fw-bold text-success' : 'text-dark' }}" data-bs-toggle="tab" href="#tab-activity">
                    <i class="ti-notepad me-1"></i> অ্যাক্টিভিটি লগ
                </a>
            </li>
        </ul>

        <div class="tab-content p-4 bg-white" id="profileTabsContent">

            <!-- 1. Tab Overview -->
            <div class="tab-pane fade {{ $activeTab == 'overview' ? 'show active' : '' }}" id="tab-overview">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100 bg-light">
                            <h6 class="fw-bold text-dark border-bottom pb-2"><i class="ti-info-alt text-primary me-2"></i> কর্মচারী সংক্ষিপ্ত বায়োডাটা</h6>
                            <table class="table table-sm table-borderless mb-0">
                                <tr><th width="40%" class="text-muted">পূর্ণ নাম:</th><td class="fw-bold">{{ $employee->name }}</td></tr>
                                <tr><th class="text-muted">Employee ID:</th><td><span class="badge bg-dark font-monospace">{{ $employee->employee_id }}</span></td></tr>
                                <tr><th class="text-muted">নিযুক্ত ব্রাঞ্চ:</th><td>{{ $employee->branch->name ?? '' }} ({{ $employee->branch->code ?? '' }})</td></tr>
                                <tr><th class="text-muted">পদবি ও বিভাগ:</th><td>{{ $employee->designation }} | {{ $employee->department ?? 'সাধারণ' }}</td></tr>
                                <tr><th class="text-muted">মোবাইল ফোন:</th><td>{{ $employee->phone }}</td></tr>
                                <tr><th class="text-muted">ইমেইল:</th><td>{{ $employee->email ?: 'N/A' }}</td></tr>
                                <tr><th class="text-muted">জাতীয়তা:</th><td>{{ $employee->nationality }}</td></tr>
                                <tr><th class="text-muted">রক্তের গ্রুপ:</th><td><span class="badge bg-danger">{{ $employee->blood_group ?: 'N/A' }}</span></td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100 bg-light">
                            <h6 class="fw-bold text-dark border-bottom pb-2"><i class="ti-shield text-success me-2"></i> নিরাপত্তা ও আর্থিক স্থিতি</h6>
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <th width="40%" class="text-muted">NID নম্বর (Masked):</th>
                                    <td>
                                        <span class="font-monospace fw-bold bg-white px-2 py-1 rounded border">{{ $employee->masked_nid }}</span>
                                    </td>
                                </tr>
                                <tr><th class="text-muted">মাসিক মোট বেতন:</th><td class="fw-bold text-success fs-6">৳{{ number_format($employee->monthly_salary, 2) }}</td></tr>
                                <tr><th class="text-muted">বেতন প্রদান মাধ্যম:</th><td>{{ ucfirst($employee->salary_payment_method) }}</td></tr>
                                <tr><th class="text-muted">মোট নেওয়া অগ্রিম:</th><td class="fw-bold text-warning">৳{{ number_format($totalAdvance, 2) }}</td></tr>
                                <tr><th class="text-muted">মোট সমন্বয়কৃত অগ্রিম:</th><td class="fw-bold text-info">৳{{ number_format($totalAdjustedAdvance, 2) }}</td></tr>
                                <tr><th class="text-muted">বকেয়া অগ্রিম স্থিতি:</th><td class="fw-bold text-danger">৳{{ number_format($advanceRemaining, 2) }}</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Tab Personal Information (Section 5.1 & 18) -->
            <div class="tab-pane fade {{ $activeTab == 'personal' ? 'show active' : '' }}" id="tab-personal">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">ব্যক্তিগত পরিচয় ও পরিবার</h6>
                        <table class="table table-bordered align-middle">
                            <tr><th width="40%" class="bg-light">পিতার নাম</th><td>{{ $employee->father_name ?: 'তথ্য নেই' }}</td></tr>
                            <tr><th class="bg-light">মাতার নাম</th><td>{{ $employee->mother_name ?: 'তথ্য নেই' }}</td></tr>
                            <tr><th class="bg-light">জন্ম তারিখ</th><td>{{ optional($employee->date_of_birth)->format('d M Y') ?: 'তথ্য নেই' }}</td></tr>
                            <tr><th class="bg-light">লিঙ্গ</th><td>{{ $employee->gender }}</td></tr>
                            <tr><th class="bg-light">বৈবাহিক অবস্থা</th><td>{{ $employee->marital_status }}</td></tr>
                            <tr><th class="bg-light">জরুরি যোগাযোগ</th><td>{{ $employee->emergency_contact }} ({{ $employee->emergency_contact_relation ?: 'সম্পর্ক উল্লেখ নেই' }})</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-success border-bottom pb-2 mb-3">যোগাযোগ ও ঠিকানা</h6>
                        <table class="table table-bordered align-middle">
                            <tr><th width="40%" class="bg-light">মোবাইল নম্বর</th><td>{{ $employee->phone }}</td></tr>
                            <tr><th class="bg-light">বিকল্প মোবাইল</th><td>{{ $employee->alt_phone ?: 'তথ্য নেই' }}</td></tr>
                            <tr><th class="bg-light">বর্তমান ঠিকানা</th><td>{{ $employee->present_address ?: 'তথ্য নেই' }}</td></tr>
                            <tr><th class="bg-light">স্থায়ী ঠিকানা</th><td>{{ $employee->permanent_address ?: 'তথ্য নেই' }}</td></tr>
                            <tr><th class="bg-light">জেলা ও থানা</th><td>{{ $employee->district }} {{ $employee->upazila ? ', ' . $employee->upazila : '' }}</td></tr>
                            <tr><th class="bg-light">পোস্টাল কোড</th><td>{{ $employee->post_office }} ({{ $employee->postal_code }})</td></tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 3. Tab Employment Information (Section 5.3) -->
            <div class="tab-pane fade {{ $activeTab == 'employment' ? 'show active' : '' }}" id="tab-employment">
                <div class="row g-4">
                    <div class="col-md-6">
                        <table class="table table-bordered align-middle">
                            <tr><th width="40%" class="bg-light">ব্রাঞ্চ</th><td class="fw-bold">{{ $employee->branch->name ?? '' }} ({{ $employee->branch->code ?? '' }})</td></tr>
                            <tr><th class="bg-light">পদবি</th><td class="fw-bold text-primary">{{ $employee->designation }}</td></tr>
                            <tr><th class="bg-light">বিভাগ</th><td>{{ $employee->department ?: 'N/A' }}</td></tr>
                            <tr><th class="bg-light">চাকরির ধরন</th><td>{{ $employee->job_type }}</td></tr>
                            <tr><th class="bg-light">কর্মচারী টাইপ</th><td>{{ $employee->employee_type }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered align-middle">
                            <tr><th width="40%" class="bg-light">যোগদানের তারিখ</th><td>{{ optional($employee->joining_date)->format('d F Y') }}</td></tr>
                            <tr><th class="bg-light">কর্মঘণ্টার শিফট</th><td>{{ $employee->shift->shift_name ?? 'সাধারণ শিফট' }}</td></tr>
                            <tr><th class="bg-light">রিপোর্টিং ব্যক্তি</th><td>{{ $employee->reporting_person ?: 'Branch Manager' }}</td></tr>
                            <tr><th class="bg-light">চাকরির অবস্থা</th><td><span class="badge bg-success">{{ $employee->employment_status }}</span></td></tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 4. Tab Salary Information (Section 5.4) -->
            <div class="tab-pane fade {{ $activeTab == 'salary' ? 'show active' : '' }}" id="tab-salary">
                <div class="row g-4">
                    <div class="col-md-7">
                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">মাসিক বেতন ও ভাতাসমূহের ব্রেকডাউন</h6>
                        <table class="table table-bordered align-middle">
                            <tr><th width="50%" class="bg-light">মূল বেতন (Basic Salary)</th><td class="fw-bold">৳{{ number_format($employee->basic_salary, 2) }}</td></tr>
                            <tr><th class="bg-light">বাড়ি ভাড়া ভাতা (House Rent)</th><td>৳{{ number_format($employee->house_rent, 2) }}</td></tr>
                            <tr><th class="bg-light">চিকিৎসা ভাতা (Medical Allowance)</th><td>৳{{ number_format($employee->medical_allowance, 2) }}</td></tr>
                            <tr><th class="bg-light">যাতায়াত ভাতা (Transport Allowance)</th><td>৳{{ number_format($employee->transport_allowance, 2) }}</td></tr>
                            <tr><th class="bg-light">খাবার ভাতা (Food Allowance)</th><td>৳{{ number_format($employee->food_allowance, 2) }}</td></tr>
                            <tr><th class="bg-light">অন্যান্য ভাতা (Other Allowance)</th><td>৳{{ number_format($employee->other_allowance, 2) }}</td></tr>
                            <tr class="table-success"><th class="fw-bold">সর্বমোট মাসিক বেতন (Gross)</th><td class="fw-bold fs-6">৳{{ number_format($employee->monthly_salary, 2) }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-5">
                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">ব্যাংক ও পরিশোধের তথ্য</h6>
                        <table class="table table-bordered align-middle">
                            <tr><th width="45%" class="bg-light">পদ্ধতি</th><td>{{ ucfirst($employee->salary_payment_method) }}</td></tr>
                            <tr><th class="bg-light">ব্যাংকের নাম</th><td>{{ $employee->bank_name ?: 'তথ্য নেই' }}</td></tr>
                            <tr><th class="bg-light">হিসাব নম্বর</th><td class="font-monospace">{{ $employee->bank_account_no ?: 'তথ্য নেই' }}</td></tr>
                            <tr><th class="bg-light">মোবাইল ওয়ালেট</th><td>{{ $employee->mobile_wallet_number ?: 'তথ্য নেই' }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 5. Tab Attendance History (Section 7) -->
            <div class="tab-pane fade {{ $activeTab == 'attendance' ? 'show active' : '' }}" id="tab-attendance">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="ti-calendar text-primary me-2"></i> সাম্প্রতিক ৩০ দিনের উপস্থিতি লগ</h6>
                    <a href="{{ route('admin.supershop.employee_system.attendance.calendar', ['employee_id' => $employee->id]) }}" class="btn btn-sm btn-outline-primary">
                        <i class="ti-calendar me-1"></i> ক্যালেন্ডার ভিউ
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>তারিখ</th>
                                <th>চেক-ইন</th>
                                <th>চেক-আউট</th>
                                <th>কাজের সময়</th>
                                <th>দেরি (মিনিট)</th>
                                <th>ওভারটাইম</th>
                                <th>হাজিরা পদ্ধতি</th>
                                <th>স্ট্যাটাস</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->attendances as $att)
                                <tr>
                                    <td class="fw-bold">{{ date('d M Y (D)', strtotime($att->date)) }}</td>
                                    <td>{{ $att->check_in ? date('h:i A', strtotime($att->check_in)) : '—' }}</td>
                                    <td>{{ $att->check_out ? date('h:i A', strtotime($att->check_out)) : '—' }}</td>
                                    <td>{{ $att->working_hours > 0 ? $att->working_hours . ' ঘণ্টা' : '—' }}</td>
                                    <td>{{ $att->late_minutes > 0 ? $att->late_minutes . ' মি.' : '০' }}</td>
                                    <td>{{ $att->overtime_minutes > 0 ? $att->overtime_minutes . ' মি.' : '০' }}</td>
                                    <td>
                                        @if($att->verification_mode == 'machine')
                                            <span class="badge bg-secondary"><i class="ti-thumb-up"></i> মেশিন</span>
                                        @else
                                            <span class="badge bg-light text-dark border">ম্যানুয়াল</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($att->status == 'present')
                                            <span class="badge bg-success">উপস্থিত</span>
                                        @elseif($att->status == 'late')
                                            <span class="badge bg-warning text-dark">দেরি</span>
                                        @elseif($att->status == 'absent')
                                            <span class="badge bg-danger">অনুপস্থিত</span>
                                        @elseif($att->status == 'leave')
                                            <span class="badge bg-info">ছুটি</span>
                                        @elseif($att->status == 'weekly_holiday')
                                            <span class="badge bg-secondary">সাপ্তাহিক ছুটি</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center py-4 text-muted">কোনো উপস্থিতি রেকর্ড নেই।</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 6. Tab Leave History (Section 16) -->
            <div class="tab-pane fade {{ $activeTab == 'leave' ? 'show active' : '' }}" id="tab-leave">
                <h6 class="fw-bold text-dark mb-3"><i class="ti-time text-primary me-2"></i> ছুটির আবেদন ও মঞ্জুরি ইতিহাস</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ছুটির ধরন</th>
                                <th>শুরুর তারিখ</th>
                                <th>শেষ তারিখ</th>
                                <th>মোট দিন</th>
                                <th>কারণ</th>
                                <th>স্ট্যাটাস</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->leaveRequests as $lv)
                                <tr>
                                    <td><span class="badge bg-primary">{{ ucfirst($lv->leave_type) }} Leave</span></td>
                                    <td>{{ date('d M Y', strtotime($lv->start_date)) }}</td>
                                    <td>{{ date('d M Y', strtotime($lv->end_date)) }}</td>
                                    <td class="fw-bold">{{ $lv->total_days }} দিন</td>
                                    <td>{{ $lv->reason }}</td>
                                    <td>
                                        <span class="badge bg-{{ $lv->status === 'approved' ? 'success' : ($lv->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($lv->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-4 text-muted">ছুটির কোনো রেকর্ড নেই।</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 7. Tab Salary Advance (Section 10.3) -->
            <div class="tab-pane fade {{ $activeTab == 'advance' ? 'show active' : '' }}" id="tab-advance">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold text-dark mb-0"><i class="ti-wallet text-warning me-2"></i> বেতন অগ্রিম গ্রহণের বিবরণ ও সমন্বয় ইতিহাস</h6>
                        <small class="text-muted">হিসাব সূত্র: অবশিষ্ট অগ্রিম = মোট অগ্রিম − মোট সমন্বয় (Remaining = Total − Adjusted)</small>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ভাউচার আইডি</th>
                                <th>তারিখ</th>
                                <th>অগ্রিম পরিমাণ</th>
                                <th>কিস্তি পরিমাণ</th>
                                <th>সমন্বয়কৃত টাকা</th>
                                <th>অবশিষ্ট বকেয়া</th>
                                <th>স্ট্যাটাস</th>
                                <th>কারণ ও মন্তব্য</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->salaryAdvances as $adv)
                                <tr>
                                    <td class="font-monospace fw-bold">{{ $adv->advance_no }}</td>
                                    <td>{{ date('d M Y', strtotime($adv->advance_date)) }}</td>
                                    <td class="fw-bold text-dark">৳{{ number_format($adv->advance_amount, 2) }}</td>
                                    <td>৳{{ number_format($adv->installment_amount, 2) }}</td>
                                    <td class="fw-bold text-success">৳{{ number_format($adv->adjusted_amount, 2) }}</td>
                                    <td class="fw-bold text-danger">৳{{ number_format($adv->remaining_amount, 2) }}</td>
                                    <td>
                                        @if($adv->status == 'fully_adjusted')
                                            <span class="badge bg-success">সম্পূর্ণ সমন্বিত</span>
                                        @elseif($adv->status == 'partially_adjusted')
                                            <span class="badge bg-warning text-dark">আংশিক সমন্বিত</span>
                                        @else
                                            <span class="badge bg-danger">বকেয়া (Disbursed)</span>
                                        @endif
                                    </td>
                                    <td>{{ $adv->reason }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center py-4 text-muted">কোনো বেতন অগ্রিম রেকর্ড নেই।</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 8. Tab Payment History (Section 15) -->
            <div class="tab-pane fade {{ $activeTab == 'payment' ? 'show active' : '' }}" id="tab-payment">
                <h6 class="fw-bold text-dark mb-3"><i class="ti-receipt text-primary me-2"></i> বেতন পরিশোধ ও রসিদ ইতিহাস</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>রশিদ নং</th>
                                <th>বেতন মাস</th>
                                <th>মূল ও ভাতা</th>
                                <th>ওভারটাইম</th>
                                <th>অগ্রিম কর্তন</th>
                                <th>মোট পরিশোধিত</th>
                                <th>তারিখ ও মাধ্যম</th>
                                <th>স্ট্যাটাস</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->salaryPayments as $pay)
                                <tr>
                                    <td class="font-monospace fw-bold">{{ $pay->receipt_no ?: $pay->payment_no }}</td>
                                    <td>{{ $pay->salary_month }}</td>
                                    <td>৳{{ number_format($pay->basic_salary + $pay->allowance_total, 2) }}</td>
                                    <td>৳{{ number_format($pay->overtime_amount, 2) }}</td>
                                    <td class="text-danger">-৳{{ number_format($pay->advance_adjusted, 2) }}</td>
                                    <td class="fw-bold text-success">৳{{ number_format($pay->paid_amount, 2) }}</td>
                                    <td>
                                        {{ date('d M Y', strtotime($pay->payment_date)) }}
                                        <small class="text-muted d-block">{{ ucfirst($pay->payment_method) }}</small>
                                    </td>
                                    <td><span class="badge bg-success">Paid</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center py-4 text-muted">বেতন পরিশোধের কোনো রেকর্ড নেই।</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 9. Tab Documents & NID (Section 5.5 & 18) -->
            <div class="tab-pane fade {{ $activeTab == 'documents' ? 'show active' : '' }}" id="tab-documents">
                <div class="alert alert-info border-0 rounded-3 mb-4">
                    <i class="ti-lock me-1"></i> সকল ব্যক্তিগত ফাইল ও এনআইডি সরাসরি এনক্রিপ্ট/প্রাইভেট ফোল্ডারে সংরক্ষিত। অনুমতিহীন কোনো ব্রাউজার ইউআরএল থেকে এটি দেখা সম্ভব নয়।
                </div>
                <div class="row g-4">
                    @forelse($employee->documents as $doc)
                        <div class="col-md-4">
                            <div class="card border rounded-3 p-3 bg-light h-100">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="ti-file fa-2x text-primary me-3"></i>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">{{ $doc->document_title }}</h6>
                                        <small class="text-muted">{{ $doc->document_type }} ({{ $doc->file_size }})</small>
                                    </div>
                                </div>
                                <div class="mt-auto d-flex gap-2">
                                    <a href="{{ route('admin.supershop.employee_system.employees.document.view', $doc->id) }}" target="_blank" class="btn btn-sm btn-outline-primary w-50">
                                        <i class="ti-eye me-1"></i> ভিউ
                                    </a>
                                    <a href="{{ route('admin.supershop.employee_system.employees.document.download', $doc->id) }}" class="btn btn-sm btn-outline-success w-50">
                                        <i class="ti-download me-1"></i> ডাউনলোড
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 text-muted">
                            <i class="ti-folder fa-3x d-block mb-2 text-secondary"></i>
                            এই কর্মচারীর কোনো স্ক্যান ডকুমেন্ট বা এনআইডি কপি আপলোড করা হয়নি।
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- 10. Tab Activity Log (Section 19) -->
            <div class="tab-pane fade {{ $activeTab == 'activity' ? 'show active' : '' }}" id="tab-activity">
                <h6 class="fw-bold text-dark mb-3"><i class="ti-notepad text-primary me-2"></i> কর্মচারী সংক্রান্ত নিরীক্ষা ও পরিবর্তন লগ (Audit Trail)</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>কার্যক্রম (Action)</th>
                                <th>ব্যবহারকারী</th>
                                <th>বিবরণ</th>
                                <th>সময় ও আইপি</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->activityLogs as $log)
                                <tr>
                                    <td><span class="badge bg-secondary">{{ $log->action }}</span></td>
                                    <td><strong>{{ $log->user_name ?: 'System' }}</strong></td>
                                    <td>{{ $log->description }}</td>
                                    <td>
                                        {{ date('d M Y, h:i A', strtotime($log->created_at)) }}
                                        <small class="text-muted d-block">{{ $log->ip_address }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted">কোনো অ্যাক্টিভিটি লগ নেই।</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

        </section>
    </div>
</div>
@endsection
