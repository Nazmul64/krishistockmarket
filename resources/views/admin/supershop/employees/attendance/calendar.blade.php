@extends('layouts.backend.app')

@section('title', 'মাসিক উপস্থিতি ক্যালেন্ডার ভিউ (Monthly Attendance Calendar)')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content">

    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">
                <i class="ti-calendar text-primary me-2"></i> মাসিক কর্মচারী উপস্থিতি ক্যালেন্ডার
            </h4>
            <p class="text-muted small mb-0">রঙিন ভিজ্যুয়াল ক্যালেন্ডার: সবুজ=উপস্থিত, লাল=অনুপস্থিত, হলুদ=দেরি, নীল=ছুটি, ধূসর=বন্ধ।</p>
        </div>
        <div>
            <a href="{{ route('admin.supershop.employee_system.attendance.index') }}" class="btn btn-outline-secondary">
                <i class="ti-arrow-left me-1"></i> দৈনিক হাজিরা শীট
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.supershop.employee_system.attendance.calendar') }}" method="GET" class="row g-3 align-items-center">
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
                    <label class="form-label small fw-bold text-muted mb-1">মাস ও বছর নির্বাচন</label>
                    <input type="month" name="month" class="form-control fw-bold" value="{{ $monthYear }}" onchange="this.form.submit()">
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted mb-1">নির্দিষ্ট কর্মচারী</label>
                    <select name="employee_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- সকল কর্মচারী (All Staff) --</option>
                        @foreach($employees as $e)
                            <option value="{{ $e->id }}" {{ request('employee_id') == $e->id ? 'selected' : '' }}>{{ $e->name }} ({{ $e->employee_id }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">
                        <i class="ti-filter me-1"></i> ফিল্টার
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Color Legend (Section 13) -->
    <div class="d-flex flex-wrap gap-3 mb-4 p-3 bg-white rounded-3 shadow-sm border align-items-center">
        <span class="fw-bold small text-dark"><i class="ti-palette me-1"></i> নির্দেশিকা (Legend):</span>
        <span class="badge bg-success px-3 py-2"><i class="ti-check me-1"></i> সবুজ → Present (উপস্থিত)</span>
        <span class="badge bg-warning text-dark px-3 py-2"><i class="ti-alarm-clock me-1"></i> হলুদ → Late (দেরিতে উপস্থিতি)</span>
        <span class="badge bg-danger px-3 py-2"><i class="ti-close me-1"></i> লাল → Absent (অনুপস্থিত)</span>
        <span class="badge bg-info px-3 py-2"><i class="ti-time me-1"></i> নীল → Leave (ছুটি)</span>
        <span class="badge bg-secondary px-3 py-2"><i class="ti-calendar me-1"></i> ধূসর → Holiday (সাপ্তাহিক / সরকারি ছুটি)</span>
    </div>

    <!-- Calendar Matrix Table -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-0">
            <h6 class="fw-bold mb-0 text-dark">
                মাসিক হাজিরা ম্যাট্রিক্স: {{ date('F Y', strtotime($monthYear . '-01')) }}
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle text-center mb-0" style="font-size: 13px;">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3 text-start" style="min-width: 180px;">কর্মচারী</th>
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php
                                $dDate = Carbon::parse($monthYear . '-' . sprintf('%02d', $d));
                                $isFri = ($dDate->dayOfWeek === Carbon::FRIDAY);
                            @endphp
                            <th class="{{ $isFri ? 'bg-danger-subtle text-danger fw-bold' : '' }}" style="width: 32px;">
                                {{ $d }}
                                <div style="font-size: 9px;">{{ $dDate->format('D') }}</div>
                            </th>
                        @endfor
                        <th style="min-width: 90px;">উপস্থিত / মোট</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                        @php
                            $empAtts = ($attendances->get($emp->id) ?? collect())->keyBy(function($item) {
                                return Carbon::parse($item->date)->format('j');
                            });
                            $pCount = 0;
                        @endphp
                        <tr>
                            <td class="ps-3 text-start">
                                <a href="{{ route('admin.supershop.employee_system.employees.show', $emp->id) }}" class="fw-bold text-dark text-decoration-none">
                                    {{ $emp->name }}
                                </a>
                                <div class="text-muted small" style="font-size: 10px;">{{ $emp->designation }}</div>
                            </td>
                            @for($d = 1; $d <= $daysInMonth; $d++)
                                @php
                                    $dDate = Carbon::parse($monthYear . '-' . sprintf('%02d', $d));
                                    $isFri = ($dDate->dayOfWeek === Carbon::FRIDAY);
                                    $att = $empAtts->get($d);
                                @endphp
                                <td class="p-1">
                                    @if($att)
                                        @if($att->status === 'present')
                                            @php $pCount++; @endphp
                                            <span class="badge bg-success p-1 w-100" title="{{ $dDate->format('d M') }}: ইন {{ $att->check_in }} | আউট {{ $att->check_out }}">P</span>
                                        @elseif($att->status === 'late')
                                            @php $pCount++; @endphp
                                            <span class="badge bg-warning text-dark p-1 w-100" title="{{ $dDate->format('d M') }}: দেরি {{ $att->late_minutes }} মিনিট">L</span>
                                        @elseif($att->status === 'absent')
                                            <span class="badge bg-danger p-1 w-100" title="{{ $dDate->format('d M') }}: অনুপস্থিত">A</span>
                                        @elseif($att->status === 'leave')
                                            <span class="badge bg-info p-1 w-100" title="{{ $dDate->format('d M') }}: ছুটি">LV</span>
                                        @elseif($att->status === 'half_day')
                                            @php $pCount += 0.5; @endphp
                                            <span class="badge bg-primary p-1 w-100" title="{{ $dDate->format('d M') }}: অর্ধদিবস">HD</span>
                                        @elseif($att->status === 'weekly_holiday' || $att->status === 'holiday')
                                            <span class="badge bg-secondary p-1 w-100" title="{{ $dDate->format('d M') }}: বন্ধ">H</span>
                                        @endif
                                    @elseif($isFri)
                                        <span class="badge bg-secondary p-1 w-100" title="সাপ্তাহিক বন্ধ">H</span>
                                    @else
                                        <span class="text-muted" style="font-size: 10px;">—</span>
                                    @endif
                                </td>
                            @endfor
                            <td class="fw-bold text-success">{{ $pCount }} / {{ $daysInMonth }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $daysInMonth + 2 }}" class="text-center py-4 text-muted">কোনো কর্মচারী পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

        </section>
    </div>
</div>
@endsection
