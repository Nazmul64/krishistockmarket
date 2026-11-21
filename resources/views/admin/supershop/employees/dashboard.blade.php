@extends('layouts.backend.app')

@section('title', $isAdmin ? 'কেন্দ্রীয় কর্মচারী ও শাখা মনিটরিং ড্যাশবোর্ড' : 'ব্রাঞ্চ কর্মচারী ও উপস্থিতি ড্যাশবোর্ড - ' . ($currentBranch->name ?? ''))

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content">

    <!-- Header Section with Mode Indicator -->
    <div class="row align-items-center mb-4">
        <div class="col-md-7">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 text-white me-3 shadow-sm" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                    <i class="ti-user fa-2x"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-1 text-dark">
                        @if($isAdmin)
                            <span class="badge bg-danger me-2"><i class="ti-shield"></i> MAIN ADMIN</span> কেন্দ্রীয় কর্মচারী ও শাখা পর্যবেক্ষণ ড্যাশবোর্ড
                        @else
                            <span class="badge bg-success me-2"><i class="ti-home"></i> {{ $currentBranch->code ?? 'BR' }}</span> {{ $currentBranch->name ?? 'ব্রাঞ্চ ড্যাশবোর্ড' }}
                        @endif
                    </h3>
                    <p class="text-muted mb-0 small">
                        @if($isAdmin)
                            সকল ব্রাঞ্চের কর্মচারী, বায়োডাটা, উপস্থিতি, বেতন অগ্রিম ও আর্থিক বিক্রয় কেন্দ্রীয়ভাবে পর্যবেক্ষণ করুন।
                        @else
                            আপনার নিজ ব্রাঞ্চের স্টাফ বায়োডাটা, দৈনিক হাজিরা, ছুটি, বেতন অগ্রিম এবং পরিশোধের হিসাব পরিচালনা করুন।
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <div class="btn-group shadow-sm">
                <a href="{{ route('admin.supershop.employee_system.employees.create') }}" class="btn btn-success fw-bold">
                    <i class="ti-plus me-1"></i> নতুন কর্মচারী যোগ
                </a>
                <a href="{{ route('admin.supershop.employee_system.attendance.index') }}" class="btn btn-primary fw-bold">
                    <i class="ti-calendar me-1"></i> দৈনিক হাজিরা
                </a>
                @if($isAdmin)
                    <a href="{{ route('admin.supershop.employee_system.advances.central_report') }}" class="btn btn-warning text-dark fw-bold">
                        <i class="ti-wallet me-1"></i> অল-ব্রাঞ্চ অগ্রিম রিপোর্ট
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Alert / Notice Box: Branch Privacy Assurance (Section 2 & 3) -->
    <div class="alert alert-info border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center justify-content-between p-3" style="background: #e0f2fe; border-left: 5px solid #0284c7 !important;">
        <div class="d-flex align-items-center">
            <i class="ti-lock text-primary fa-2x me-3"></i>
            <div>
                <strong class="text-primary d-block">ব্রাঞ্চভিত্তিক ডেটা ও সেলস প্রাইভেসি সিকিউরিটি সিস্টেম (Zero Trust Isolation Active)</strong>
                <span class="text-dark small">
                    @if($isAdmin)
                        মেইন এডমিন হিসেবে আপনি সকল ব্রাঞ্চের কর্মচারী, উপস্থিতি, বেতন, অগ্রিম ও কেন্দ্রীয় বিক্রয় ডেটা দেখার পূর্ণ ক্ষমতা প্রাপ্ত।
                    @else
                        নিরাপত্তা প্রটোকল সক্রিয়: আপনি শুধুমাত্র <strong>{{ $currentBranch->name ?? '' }}</strong> ব্রাঞ্চের ডেটা দেখতে পাচ্ছেন। অন্য ব্রাঞ্চের কর্মচারী, বেতন, অগ্রিম বা বিক্রয় তথ্য আপনার জন্য সম্পূর্ণ গোপন ও ব্লকড।
                    @endif
                </span>
            </div>
        </div>
        <div class="text-end d-none d-lg-block">
            <span class="badge bg-primary px-3 py-2"><i class="ti-check"></i> Privacy Enforced</span>
        </div>
    </div>

    @if($isAdmin)
        <!-- ========================================================================= -->
        <!-- 11.1 MAIN ADMIN CENTRAL DASHBOARD KPIs -->
        <!-- ========================================================================= -->
        <div class="row g-3 mb-4">
            <!-- Total Branches -->
            <div class="col-xl-2 col-md-4 col-6">
                <div class="card border-0 shadow-sm rounded-3 h-100 p-3" style="border-top: 4px solid #3b82f6 !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold">মোট ব্রাঞ্চ</span>
                            <h3 class="fw-bold text-dark mt-1 mb-0">{{ $totalBranches }}</h3>
                        </div>
                        <div class="p-2 rounded bg-light text-primary">
                            <i class="ti-home fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Employees -->
            <div class="col-xl-2 col-md-4 col-6">
                <div class="card border-0 shadow-sm rounded-3 h-100 p-3" style="border-top: 4px solid #10b981 !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold">মোট কর্মচারী</span>
                            <h3 class="fw-bold text-dark mt-1 mb-0">{{ $totalEmployees }}</h3>
                            <span class="badge bg-success-subtle text-success small">সক্রিয়: {{ $activeEmployees }}</span>
                        </div>
                        <div class="p-2 rounded bg-light text-success">
                            <i class="ti-id-badge fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today Attendance (Present / Late / Absent) -->
            <div class="col-xl-3 col-md-6 col-12">
                <div class="card border-0 shadow-sm rounded-3 h-100 p-3" style="border-top: 4px solid #6366f1 !important;">
                    <span class="text-muted small fw-bold mb-2 d-block">আজকের উপস্থিতি (সকল ব্রাঞ্চ)</span>
                    <div class="d-flex justify-content-between text-center">
                        <div>
                            <h5 class="fw-bold text-success mb-0">{{ $presentToday }}</h5>
                            <span class="text-muted small">উপস্থিত</span>
                        </div>
                        <div class="border-start ps-3">
                            <h5 class="fw-bold text-warning mb-0">{{ $lateToday }}</h5>
                            <span class="text-muted small">দেরি</span>
                        </div>
                        <div class="border-start ps-3">
                            <h5 class="fw-bold text-danger mb-0">{{ $absentToday }}</h5>
                            <span class="text-muted small">অনুপস্থিত</span>
                        </div>
                        <div class="border-start ps-3">
                            <h5 class="fw-bold text-info mb-0">{{ $onLeaveToday }}</h5>
                            <span class="text-muted small">ছুটিতে</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Payroll Summary -->
            <div class="col-xl-3 col-md-6 col-12">
                <div class="card border-0 shadow-sm rounded-3 h-100 p-3" style="border-top: 4px solid #059669 !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold">মোট মাসিক বেতন বাজেট</span>
                            <h4 class="fw-bold text-dark mt-1 mb-0">৳{{ number_format($totalMonthlyPayroll, 0) }}</h4>
                            <span class="text-muted small">পরিশোধ: ৳{{ number_format($salaryPaid, 0) }} | বাকি: ৳{{ number_format($salaryPending, 0) }}</span>
                        </div>
                        <div class="p-2 rounded bg-light text-success">
                            <i class="ti-money fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Advances & Devices -->
            <div class="col-xl-2 col-md-4 col-12">
                <div class="card border-0 shadow-sm rounded-3 h-100 p-3" style="border-top: 4px solid #f59e0b !important;">
                    <div>
                        <span class="text-muted small fw-bold">বকেয়া অগ্রিম (Advance)</span>
                        <h4 class="fw-bold text-warning mt-1 mb-0">৳{{ number_format($totalAdvanceOutstanding, 0) }}</h4>
                        <span class="text-muted small">মোট প্রদান: ৳{{ number_format($totalAdvanceGiven, 0) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Central Branch Sales & Privacy Dashboard Card -->
        <div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-0 text-dark"><i class="ti-stats-up text-success me-2"></i> আজকের কেন্দ্রীয় শাখাভিত্তিক বিক্রয় (Centralized Sales Overview)</h5>
                    <small class="text-muted">এই হিসাবটি শুধুমাত্র Main Admin দেখতে পারছে। Branch Manager এই তুলনামূলক সেলস দেখতে পারে না।</small>
                </div>
                <div>
                    <span class="badge bg-success p-2 fs-6">
                        আজকের মোট সেলস: ৳{{ number_format($allBranchesTotalSalesToday, 2) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- 11.2 Branch-wise Employee Summary Table with Click Functionality (Section 11.2 & 11.3) -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="ti-layout-grid2 text-primary me-2"></i> শাখাভিত্তিক সারসংক্ষেপ ও ড্রিল-ডাউন (Branch-wise Employee Summary)
                </h5>
                <small class="text-muted">ব্রাঞ্চে ক্লিক করে বিস্তারিত বায়োডাটা, উপস্থিতি ও বেতন দেখুন</small>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ব্রাঞ্চের নাম ও কোড</th>
                            <th class="text-center">মোট স্টাফ</th>
                            <th class="text-center">আজ উপস্থিত</th>
                            <th class="text-center">আজ অনুপস্থিত</th>
                            <th class="text-center">আজ দেরি</th>
                            <th class="text-end">মাসিক মোট বেতন</th>
                            <th class="text-end">বকেয়া অগ্রিম</th>
                            <th class="text-end">আজকের বিক্রয়</th>
                            <th class="text-center pe-4">একশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($branchSummaries as $row)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-light p-2 text-primary fw-bold me-2 border">
                                            {{ $row['branch']->code }}
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.supershop.employee_system.employees.index', ['branch_id' => $row['branch']->id]) }}" class="fw-bold text-dark text-decoration-none">
                                                {{ $row['branch']->name }}
                                            </a>
                                            <div class="text-muted small"><i class="ti-location-pin"></i> {{ $row['branch']->area ?? $row['branch']->district ?? 'বাংলাদেশ' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center fw-bold">{{ $row['employees'] }} জন</td>
                                <td class="text-center">
                                    <span class="badge bg-success-subtle text-success fw-bold px-2 py-1">{{ $row['present'] }}</span>
                                </td>
                                <td class="text-center">
                                    @if($row['absent'] > 0)
                                        <span class="badge bg-danger-subtle text-danger fw-bold px-2 py-1">{{ $row['absent'] }}</span>
                                    @else
                                        <span class="text-muted">০</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($row['late'] > 0)
                                        <span class="badge bg-warning-subtle text-warning fw-bold px-2 py-1">{{ $row['late'] }}</span>
                                    @else
                                        <span class="text-muted">০</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold">৳{{ number_format($row['monthly_salary'], 0) }}</td>
                                <td class="text-end fw-bold text-warning">৳{{ number_format($row['advance'], 0) }}</td>
                                <td class="text-end fw-bold text-success">৳{{ number_format($row['today_sales'], 0) }}</td>
                                <td class="text-center pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.supershop.employee_system.employees.index', ['branch_id' => $row['branch']->id]) }}" class="btn btn-outline-primary" title="কর্মচারী তালিকা">
                                            <i class="ti-user"></i> স্টাফ
                                        </a>
                                        <a href="{{ route('admin.supershop.employee_system.attendance.index', ['branch_id' => $row['branch']->id]) }}" class="btn btn-outline-success" title="হাজিরা">
                                            <i class="ti-check"></i> হাজিরা
                                        </a>
                                        <a href="{{ route('admin.supershop.employee_system.advances.index', ['branch_id' => $row['branch']->id]) }}" class="btn btn-outline-warning" title="অগ্রিম">
                                            <i class="ti-wallet"></i> অগ্রিম
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @else
        <!-- ========================================================================= -->
        <!-- 7.0 BRANCH MANAGER DEDICATED DASHBOARD (OWN BRANCH ONLY) -->
        <!-- ========================================================================= -->
        <div class="row g-3 mb-4">
            <!-- Total Employees -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-3 p-3" style="border-left: 5px solid #10b981 !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold">আমার ব্রাঞ্চের মোট স্টাফ</span>
                            <h3 class="fw-bold text-dark mt-1 mb-0">{{ $totalEmployees }} জন</h3>
                            <span class="text-success small fw-bold">সক্রিয়: {{ $activeEmployees }} জন</span>
                        </div>
                        <div class="p-3 bg-light rounded-circle text-success">
                            <i class="ti-id-badge fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Attendance -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-3 p-3" style="border-left: 5px solid #3b82f6 !important;">
                    <span class="text-muted small fw-bold mb-1 d-block">আজকের হাজিরা বিবরণ</span>
                    <div class="d-flex justify-content-between text-center mt-2">
                        <div>
                            <h4 class="fw-bold text-success mb-0">{{ $presentToday }}</h4>
                            <small class="text-muted">উপস্থিত</small>
                        </div>
                        <div class="border-start ps-2">
                            <h4 class="fw-bold text-warning mb-0">{{ $lateToday }}</h4>
                            <small class="text-muted">দেরি</small>
                        </div>
                        <div class="border-start ps-2">
                            <h4 class="fw-bold text-danger mb-0">{{ $absentToday }}</h4>
                            <small class="text-muted">অনুপস্থিত</small>
                        </div>
                        <div class="border-start ps-2">
                            <h4 class="fw-bold text-info mb-0">{{ $onLeaveToday }}</h4>
                            <small class="text-muted">ছুটি</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advance Outstanding -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-3 p-3" style="border-left: 5px solid #f59e0b !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold">ব্রাঞ্চ কর্মীদের বকেয়া অগ্রিম</span>
                            <h3 class="fw-bold text-warning mt-1 mb-0">৳{{ number_format($advanceOutstanding, 0) }}</h3>
                            <span class="text-muted small">মোট সমন্বিত: ৳{{ number_format($totalAdvance - $advanceOutstanding, 0) }}</span>
                        </div>
                        <div class="p-3 bg-light rounded-circle text-warning">
                            <i class="ti-wallet fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Own Branch Sales (Privacy Enforced) -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-3 p-3" style="border-left: 5px solid #059669 !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold">আমার ব্রাঞ্চের আজকের সেলস</span>
                            <h3 class="fw-bold text-success mt-1 mb-0">৳{{ number_format($branchTodaySales, 0) }}</h3>
                            <span class="badge bg-light text-muted small border">শুধুমাত্র নিজ ব্রাঞ্চ</span>
                        </div>
                        <div class="p-3 bg-light rounded-circle text-success">
                            <i class="ti-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Access Menus for Branch Manager (Section 14) -->
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
                <a href="{{ route('admin.supershop.employee_system.employees.index') }}" class="card border-0 shadow-sm text-decoration-none p-3 h-100 hover-shadow text-center">
                    <i class="ti-user fa-2x text-primary mb-2"></i>
                    <h6 class="fw-bold text-dark mb-1">স্টাফ তালিকা ও বায়োডাটা</h6>
                    <small class="text-muted">সকল কর্মচারীর প্রোফাইল ও নথি</small>
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="{{ route('admin.supershop.employee_system.attendance.index') }}" class="card border-0 shadow-sm text-decoration-none p-3 h-100 hover-shadow text-center">
                    <i class="ti-check-box fa-2x text-success mb-2"></i>
                    <h6 class="fw-bold text-dark mb-1">দৈনিক হাজিরা গ্রহণ</h6>
                    <small class="text-muted">ম্যানুয়াল ও মেশিন হাজিরা</small>
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="{{ route('admin.supershop.employee_system.advances.index') }}" class="card border-0 shadow-sm text-decoration-none p-3 h-100 hover-shadow text-center">
                    <i class="ti-money fa-2x text-warning mb-2"></i>
                    <h6 class="fw-bold text-dark mb-1">বেতন অগ্রিম ও সমন্বয়</h6>
                    <small class="text-muted">অগ্রিম এন্ট্রি ও কিস্তি</small>
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="{{ route('admin.supershop.employee_system.payroll.index') }}" class="card border-0 shadow-sm text-decoration-none p-3 h-100 hover-shadow text-center">
                    <i class="ti-receipt fa-2x text-info mb-2"></i>
                    <h6 class="fw-bold text-dark mb-1">মাসিক পে-রোল ও বেতন</h6>
                    <small class="text-muted">বেতন প্রদান ও রসিদ</small>
                </a>
            </div>
        </div>

        <!-- Recent Branch Activity & Attendance Table -->
        <div class="row g-4 mb-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark"><i class="ti-time text-primary me-2"></i> আজকের হাজিরা লগ (Today's Logs)</h6>
                        <a href="{{ route('admin.supershop.employee_system.attendance.index') }}" class="btn btn-sm btn-outline-primary">সব দেখুন</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small">
                                <tr>
                                    <th>স্টাফ</th>
                                    <th>ইন টাইম</th>
                                    <th>আউট টাইম</th>
                                    <th>স্ট্যাটাস</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentAttendances as $att)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $att->employee->profile_photo_url ?? '' }}" class="rounded-circle me-2" width="30" height="30" alt="">
                                                <div>
                                                    <span class="fw-bold d-block">{{ $att->employee->name ?? 'স্টাফ' }}</span>
                                                    <small class="text-muted">{{ $att->employee->designation ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $att->check_in ? date('h:i A', strtotime($att->check_in)) : '—' }}</td>
                                        <td>{{ $att->check_out ? date('h:i A', strtotime($att->check_out)) : '—' }}</td>
                                        <td>
                                            @if($att->status == 'present')
                                                <span class="badge bg-success-subtle text-success">উপস্থিত</span>
                                            @elseif($att->status == 'late')
                                                <span class="badge bg-warning-subtle text-warning">দেরি ({{ $att->late_minutes }} মি.)</span>
                                            @elseif($att->status == 'absent')
                                                <span class="badge bg-danger-subtle text-danger">অনুপস্থিত</span>
                                            @elseif($att->status == 'leave')
                                                <span class="badge bg-info-subtle text-info">ছুটি</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">আজ এখনো কোনো হাজিরা রেকর্ড তৈরি হয়নি।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark"><i class="ti-wallet text-warning me-2"></i> সাম্প্রতিক বেতন অগ্রিম (Recent Advances)</h6>
                        <a href="{{ route('admin.supershop.employee_system.advances.index') }}" class="btn btn-sm btn-outline-warning">সব দেখুন</a>
                    </div>
                    <div class="p-3">
                        @forelse($recentAdvances as $adv)
                            <div class="d-flex justify-content-between align-items-center p-2 mb-2 rounded bg-light border">
                                <div>
                                    <strong class="d-block">{{ $adv->employee->name ?? 'স্টাফ' }}</strong>
                                    <small class="text-muted">{{ date('d M Y', strtotime($adv->advance_date)) }} | {{ $adv->advance_no }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold text-danger d-block">৳{{ number_format($adv->advance_amount, 2) }}</span>
                                    <span class="badge bg-warning text-dark small">বাকি: ৳{{ number_format($adv->remaining_amount, 0) }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-4 mb-0">সাম্প্রতিক কোনো বেতন অগ্রিম নেই।</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    @endif

        </section>
    </div>
</div>
@endsection
