@extends('layouts.backend.app')

@section('title', 'দৈনিক কর্মচারী উপস্থিতি ব্যবস্থাপনা (Daily Attendance)')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content">

    <!-- Header & Action Buttons -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">
                <i class="ti-calendar text-primary me-2"></i> দৈনিক কর্মচারী উপস্থিতি ও হাজিরা ব্যবস্থাপনা
            </h4>
            <p class="text-muted small mb-0">ম্যানুয়াল হাজিরা গ্রহণ, ইন/আউট সময় নির্ধারণ ও স্বয়ংক্রিয় অনুপস্থিতি গণনা করুন।</p>
        </div>
        <div class="d-flex gap-2 mt-3 mt-md-0">
            <!-- Absent Detection Trigger Button (Section 9) -->
            <form action="{{ route('admin.supershop.employee_system.attendance.detect_absent') }}" method="POST" onsubmit="return confirm('আপনি কি এই তারিখের জন্য স্বয়ংক্রিয় অনুপস্থিতি (Automatic Absent Detection) চালাতে চান?');">
                @csrf
                <input type="hidden" name="date" value="{{ $selectedDate }}">
                <input type="hidden" name="branch_id" value="{{ $selectedBranchId }}">
                <button type="submit" class="btn btn-warning text-dark fw-bold shadow-sm">
                    <i class="ti-alarm-clock me-1"></i> স্বয়ংক্রিয় অনুপস্থিতি গণনা করুন
                </button>
            </form>
            <a href="{{ route('admin.supershop.employee_system.attendance.calendar', ['branch_id' => $selectedBranchId]) }}" class="btn btn-primary fw-bold shadow-sm">
                <i class="ti-calendar me-1"></i> মাসিক ক্যালেন্ডার ভিউ
            </a>
            <a href="{{ route('admin.supershop.employee_system.devices.index') }}" class="btn btn-secondary fw-bold shadow-sm">
                <i class="ti-thumb-up me-1"></i> বায়োমেট্রিক মেশিন
            </a>
        </div>
    </div>

    <!-- Date & Branch Filter Bar -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.supershop.employee_system.attendance.index') }}" method="GET" class="row g-3 align-items-center">
                @if($isAdmin)
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted mb-1">ব্রাঞ্চ নির্বাচন</label>
                        <select name="branch_id" class="form-select fw-bold" onchange="this.form.submit()">
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}" {{ $selectedBranchId == $b->id ? 'selected' : '' }}>
                                    {{ $b->code }} - {{ $b->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted mb-1">হাজিরার তারিখ</label>
                    <input type="date" name="date" class="form-control fw-bold" value="{{ $selectedDate }}" onchange="this.form.submit()">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">
                        <i class="ti-search me-1"></i> লোড করুন
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary KPI Badges for Selected Date (Section 7.1) -->
    <div class="row g-2 mb-4">
        <div class="col-md-2 col-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 text-center" style="border-left: 4px solid #10b981 !important;">
                <span class="text-muted small fw-bold">উপস্থিত</span>
                <h4 class="fw-bold text-success mb-0 mt-1">{{ $summary['present'] }} জন</h4>
            </div>
        </div>
        <div class="col-md-2 col-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 text-center" style="border-left: 4px solid #f59e0b !important;">
                <span class="text-muted small fw-bold">দেরিতে উপস্থিতি</span>
                <h4 class="fw-bold text-warning mb-0 mt-1">{{ $summary['late'] }} জন</h4>
            </div>
        </div>
        <div class="col-md-2 col-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 text-center" style="border-left: 4px solid #ef4444 !important;">
                <span class="text-muted small fw-bold">অনুপস্থিত</span>
                <h4 class="fw-bold text-danger mb-0 mt-1">{{ $summary['absent'] }} জন</h4>
            </div>
        </div>
        <div class="col-md-2 col-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 text-center" style="border-left: 4px solid #6366f1 !important;">
                <span class="text-muted small fw-bold">অর্ধদিবস (Half Day)</span>
                <h4 class="fw-bold text-primary mb-0 mt-1">{{ $summary['half_day'] }} জন</h4>
            </div>
        </div>
        <div class="col-md-2 col-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 text-center" style="border-left: 4px solid #06b6d4 !important;">
                <span class="text-muted small fw-bold">ছুটিতে (Leave)</span>
                <h4 class="fw-bold text-info mb-0 mt-1">{{ $summary['leave'] }} জন</h4>
            </div>
        </div>
        <div class="col-md-2 col-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 text-center" style="border-left: 4px solid #6b7280 !important;">
                <span class="text-muted small fw-bold">সাপ্তাহিক / সরকারি ছুটি</span>
                <h4 class="fw-bold text-secondary mb-0 mt-1">{{ $summary['holiday'] }} জন</h4>
            </div>
        </div>
    </div>

    <!-- Attendance Form & Sheet -->
    <form action="{{ route('admin.supershop.employee_system.attendance.store') }}" method="POST">
        @csrf
        <input type="hidden" name="branch_id" value="{{ $selectedBranchId }}">
        <input type="hidden" name="date" value="{{ $selectedDate }}">

        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="ti-list-ol text-primary me-2"></i> হাজিরা তালিকা: তারিখ {{ date('d M Y (l)', strtotime($selectedDate)) }}
                    </h6>
                    <small class="text-muted">একসঙ্গে সকল কর্মচারীর হাজিরা স্ট্যাটাস ও ইন/আউট সময় নির্বাচন করে সংরক্ষণ করুন।</small>
                </div>
                <div>
                    <button type="submit" class="btn btn-success fw-bold px-4 shadow-sm">
                        <i class="ti-save me-1"></i> হাজিরা সংরক্ষণ করুন
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">কর্মচারী</th>
                            <th>পদবি ও আইডি</th>
                            <th width="180">স্ট্যাটাস (Status)</th>
                            <th width="150">ইন টাইম (Check-in)</th>
                            <th width="150">আউট টাইম (Check-out)</th>
                            <th>কাজের সময়</th>
                            <th>মন্তব্য / নোট</th>
                            <th class="text-center pe-4">একশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $emp)
                            @php
                                $att = $attendances->get($emp->id);
                                $currentStatus = $att ? $att->status : 'present';
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $emp->profile_photo_url }}" class="rounded-circle me-3 border" width="40" height="40" alt="">
                                        <div>
                                            <a href="{{ route('admin.supershop.employee_system.employees.show', $emp->id) }}" class="fw-bold text-dark text-decoration-none">
                                                {{ $emp->name }}
                                            </a>
                                            <small class="text-muted d-block">{{ $emp->phone }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong class="text-primary">{{ $emp->designation }}</strong>
                                    <div class="text-muted small font-monospace">{{ $emp->employee_id }}</div>
                                </td>
                                <td>
                                    <select name="attendance[{{ $emp->id }}][status]" class="form-select form-select-sm fw-bold {{ $currentStatus == 'present' ? 'text-success' : ($currentStatus == 'absent' ? 'text-danger' : 'text-warning') }}">
                                        <option value="present" {{ $currentStatus == 'present' ? 'selected' : '' }}>✅ Present (উপস্থিত)</option>
                                        <option value="late" {{ $currentStatus == 'late' ? 'selected' : '' }}>⚠️ Late (দেরিতে উপস্থিতি)</option>
                                        <option value="absent" {{ $currentStatus == 'absent' ? 'selected' : '' }}>❌ Absent (অনুপস্থিত)</option>
                                        <option value="half_day" {{ $currentStatus == 'half_day' ? 'selected' : '' }}>🌓 Half Day (অর্ধদিবস)</option>
                                        <option value="leave" {{ $currentStatus == 'leave' ? 'selected' : '' }}>🏖️ Leave (ছুটিতে)</option>
                                        <option value="weekly_holiday" {{ $currentStatus == 'weekly_holiday' ? 'selected' : '' }}>🗓️ Weekly Holiday (সাপ্তাহিক ছুটি)</option>
                                        <option value="holiday" {{ $currentStatus == 'holiday' ? 'selected' : '' }}>🎉 Holiday (সরকারি ছুটি)</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="time" name="attendance[{{ $emp->id }}][check_in]" class="form-control form-control-sm" value="{{ $att && $att->check_in ? date('H:i', strtotime($att->check_in)) : '09:00' }}">
                                </td>
                                <td>
                                    <input type="time" name="attendance[{{ $emp->id }}][check_out]" class="form-control form-control-sm" value="{{ $att && $att->check_out ? date('H:i', strtotime($att->check_out)) : '18:00' }}">
                                </td>
                                <td>
                                    @if($att && $att->working_hours > 0)
                                        <span class="badge bg-light text-dark border">{{ $att->working_hours }} ঘণ্টা</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <input type="text" name="attendance[{{ $emp->id }}][remarks]" class="form-control form-control-sm" placeholder="মন্তব্য (ঐচ্ছিক)" value="{{ $att->remarks ?? '' }}">
                                </td>
                                <td class="text-center pe-4">
                                    @if($att)
                                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#correctModal{{ $att->id }}" title="হাজিরা সংশোধন">
                                            <i class="ti-pencil"></i> সংশোধন
                                        </button>
                                    @else
                                        <span class="text-muted small">রেকর্ড নেই</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">এই ব্রাঞ্চে কোনো সক্রিয় কর্মচারী নেই।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white py-3 border-0 text-end">
                <button type="submit" class="btn btn-success fw-bold px-4 shadow-sm">
                    <i class="ti-save me-1"></i> হাজিরা সংরক্ষণ করুন
                </button>
            </div>
        </div>
    </form>

    <!-- Correction Modals -->
    @foreach($employees as $emp)
        @php $att = $attendances->get($emp->id); @endphp
        @if($att)
            <div class="modal fade" id="correctModal{{ $att->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('admin.supershop.employee_system.attendance.correction', $att->id) }}" method="POST">
                            @csrf
                            <div class="modal-header bg-warning-subtle">
                                <h6 class="modal-title fw-bold text-dark"><i class="ti-pencil me-1"></i> হাজিরা সংশোধন: {{ $emp->name }}</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="small text-muted mb-3">তারিখ: <strong>{{ date('d M Y', strtotime($att->date)) }}</strong> | পূর্বের স্ট্যাটাস: <strong>{{ $att->status }}</strong></p>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">সংশোধিত স্ট্যাটাস</label>
                                    <select name="status" class="form-select" required>
                                        <option value="present" {{ $att->status == 'present' ? 'selected' : '' }}>Present (উপস্থিত)</option>
                                        <option value="late" {{ $att->status == 'late' ? 'selected' : '' }}>Late (দেরিতে উপস্থিতি)</option>
                                        <option value="half_day" {{ $att->status == 'half_day' ? 'selected' : '' }}>Half Day (অর্ধদিবস)</option>
                                        <option value="absent" {{ $att->status == 'absent' ? 'selected' : '' }}>Absent (অনুপস্থিত)</option>
                                        <option value="leave" {{ $att->status == 'leave' ? 'selected' : '' }}>Leave (ছুটিতে)</option>
                                    </select>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label">ইন টাইম</label>
                                        <input type="time" name="check_in" class="form-control" value="{{ $att->check_in ? date('H:i', strtotime($att->check_in)) : '' }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">আউট টাইম</label>
                                        <input type="time" name="check_out" class="form-control" value="{{ $att->check_out ? date('H:i', strtotime($att->check_out)) : '' }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-danger">সংশোধনের কারণ (আবশ্যক Activity Log-এর জন্য) <span class="text-danger">*</span></label>
                                    <textarea name="correction_reason" class="form-control" rows="2" placeholder="কেন সংশোধন করা হচ্ছে লিখুন..." required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                                <button type="submit" class="btn btn-warning fw-bold">সংশোধন নিশ্চিত করুন</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

        </section>
    </div>
</div>
@endsection
