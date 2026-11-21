@extends('layouts.backend.app')

@section('title', 'কর্মচারী, হাজিরা ও শাখা সেলস রিপোর্ট (Reports Center)')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content">

    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">
                <i class="ti-bar-chart text-primary me-2"></i> কর্মচারী, হাজিরা, বেতন ও সেলস রিপোর্ট সেন্টার
            </h4>
            <p class="text-muted small mb-0">হাজিরা সামারি, বেতন পরিশোধ, অগ্রিম সমন্বয় এবং ব্রাঞ্চ সেলস প্রাইভেসি রিপোর্ট।</p>
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-outline-dark fw-bold shadow-sm">
                <i class="ti-printer me-1"></i> রিপোর্ট প্রিন্ট করুন (Print)
            </button>
        </div>
    </div>

    <!-- Filter & Report Type Selector Bar -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.supershop.employee_system.reports.index') }}" method="GET" class="row g-2 align-items-center">
                <!-- Report Type -->
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted mb-1">রিপোর্টের বিষয়বস্তু</label>
                    <select name="type" class="form-select fw-bold" onchange="this.form.submit()">
                        <option value="attendance" {{ $reportType == 'attendance' ? 'selected' : '' }}>📋 উপস্থিতি ও হাজিরা রিপোর্ট (Attendance)</option>
                        <option value="advance" {{ $reportType == 'advance' ? 'selected' : '' }}>💰 বেতন অগ্রিম রিপোর্ট (Salary Advance)</option>
                        <option value="salary" {{ $reportType == 'salary' ? 'selected' : '' }}>💵 বেতন পরিশোধ রিপোর্ট (Payroll Payments)</option>
                        <option value="sales" {{ $reportType == 'sales' ? 'selected' : '' }}>📈 শাখা সেলস রিপোর্ট (Branch Sales Privacy)</option>
                    </select>
                </div>

                <!-- Date Filter Presets (Section 13) -->
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted mb-1">সময়কাল (Date Filter)</label>
                    <select name="filter" class="form-select" onchange="this.form.submit()">
                        <option value="today" {{ $dateFilter == 'today' ? 'selected' : '' }}>আজকে (Today)</option>
                        <option value="yesterday" {{ $dateFilter == 'yesterday' ? 'selected' : '' }}>গতকাল (Yesterday)</option>
                        <option value="this_week" {{ $dateFilter == 'this_week' ? 'selected' : '' }}>এই সপ্তাহ (This Week)</option>
                        <option value="last_week" {{ $dateFilter == 'last_week' ? 'selected' : '' }}>গত সপ্তাহ (Last Week)</option>
                        <option value="this_month" {{ $dateFilter == 'this_month' ? 'selected' : '' }}>এই মাস (This Month)</option>
                        <option value="last_month" {{ $dateFilter == 'last_month' ? 'selected' : '' }}>গত মাস (Last Month)</option>
                        <option value="this_year" {{ $dateFilter == 'this_year' ? 'selected' : '' }}>চলতি বছর (This Year)</option>
                        <option value="custom" {{ $dateFilter == 'custom' ? 'selected' : '' }}>কাস্টম তারিখ (Custom Range)</option>
                    </select>
                </div>

                @if($isAdmin)
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted mb-1">ব্রাঞ্চ নির্বাচন</label>
                        <select name="branch_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- সকল ব্রাঞ্চ (All Branches) --</option>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}" {{ $selectedBranchId == $b->id ? 'selected' : '' }}>{{ $b->code }} - {{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted mb-1">হতে (From)</label>
                    <input type="date" name="from_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted mb-1">পর্যন্ত (To)</label>
                    <input type="date" name="to_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                </div>
            </form>
        </div>
    </div>

    <!-- REPORT CONTENT DISPLAY -->

    <!-- 1. Attendance Report Table (Section 13) -->
    @if($reportType === 'attendance')
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark"><i class="ti-calendar text-primary me-2"></i> কর্মচারী উপস্থিতি বিবরণী ({{ $startDate->format('d M Y') }} হতে {{ $endDate->format('d M Y') }})</h6>
                <span class="badge bg-success">Monthly Attendance Summary</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">কর্মচারী</th>
                            <th>শাখা ও পদবি</th>
                            <th class="text-center text-success">উপস্থিত দিন (Present)</th>
                            <th class="text-center text-warning">দেরি দিন (Late)</th>
                            <th class="text-center text-danger">অনুপস্থিত দিন (Absent)</th>
                            <th class="text-center text-info">ছুটি দিন (Leave)</th>
                            <th class="text-center pe-4">সর্বমোট দিন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendanceData as $row)
                            <tr>
                                <td class="ps-4">
                                    <a href="{{ route('admin.supershop.employee_system.employees.show', $row['employee']->id) }}" class="fw-bold text-dark text-decoration-none">
                                        {{ $row['employee']->name }}
                                    </a>
                                    <small class="text-muted d-block">{{ $row['employee']->employee_id }}</small>
                                </td>
                                <td>
                                    <strong>{{ $row['employee']->designation }}</strong>
                                    <div class="text-muted small">{{ $row['employee']->branch->name ?? '' }}</div>
                                </td>
                                <td class="text-center fw-bold text-success fs-6">{{ $row['present'] }}</td>
                                <td class="text-center fw-bold text-warning">{{ $row['late'] }}</td>
                                <td class="text-center fw-bold text-danger">{{ $row['absent'] }}</td>
                                <td class="text-center fw-bold text-info">{{ $row['leave'] }}</td>
                                <td class="text-center pe-4 fw-bold">{{ $row['total_working_days'] }} দিন</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-4 text-muted">কোনো উপস্থিতি ডেটা পাওয়া যায়নি।</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- 2. Advance Report Table (Section 12) -->
    @if($reportType === 'advance')
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark"><i class="ti-wallet text-warning me-2"></i> বেতন অগ্রিম বিবরণী ({{ $startDate->format('d M Y') }} হতে {{ $endDate->format('d M Y') }})</h6>
                <span class="badge bg-warning text-dark">Advance Statement</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ভাউচার</th>
                            <th>শাখা</th>
                            <th>কর্মচারী</th>
                            <th>তারিখ</th>
                            <th>মোট অগ্রিম</th>
                            <th>সমন্বয়কৃত</th>
                            <th>অবশিষ্ট বকেয়া</th>
                            <th>স্ট্যাটাস</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($advanceData as $adv)
                            <tr>
                                <td class="ps-4 font-monospace fw-bold">{{ $adv->advance_no }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $adv->branch->code ?? '' }}</span></td>
                                <td>{{ $adv->employee->name ?? 'Staff' }}</td>
                                <td>{{ date('d M Y', strtotime($adv->advance_date)) }}</td>
                                <td class="fw-bold">৳{{ number_format($adv->advance_amount, 2) }}</td>
                                <td class="text-success fw-bold">৳{{ number_format($adv->adjusted_amount, 2) }}</td>
                                <td class="text-danger fw-bold">৳{{ number_format($adv->remaining_amount, 2) }}</td>
                                <td><span class="badge bg-{{ $adv->status === 'fully_adjusted' ? 'success' : 'warning' }}">{{ ucfirst($adv->status) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4 text-muted">কোনো অগ্রিম ডেটা পাওয়া যায়নি।</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- 3. Salary Payment Report Table -->
    @if($reportType === 'salary')
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark"><i class="ti-receipt text-primary me-2"></i> বেতন পরিশোধের চালান ও রশিদ বিবরণী</h6>
                <span class="badge bg-primary">Salary Payment Vouchers</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">রশিদ নং</th>
                            <th>শাখা</th>
                            <th>কর্মচারী</th>
                            <th>বেতন মাস</th>
                            <th>মূল ও ভাতা</th>
                            <th>অগ্রিম কর্তন</th>
                            <th>পরিশোধিত টাকা</th>
                            <th>তারিখ ও মাধ্যম</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salaryData as $pay)
                            <tr>
                                <td class="ps-4 font-monospace fw-bold">{{ $pay->receipt_no ?: $pay->payment_no }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $pay->branch->code ?? '' }}</span></td>
                                <td>{{ $pay->employee->name ?? 'Staff' }}</td>
                                <td>{{ $pay->salary_month }}</td>
                                <td>৳{{ number_format($pay->basic_salary + $pay->allowance_total, 2) }}</td>
                                <td class="text-danger">-৳{{ number_format($pay->advance_adjusted, 2) }}</td>
                                <td class="fw-bold text-success fs-6">৳{{ number_format($pay->paid_amount, 2) }}</td>
                                <td>{{ date('d M Y', strtotime($pay->payment_date)) }} ({{ ucfirst($pay->payment_method) }})</td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4 text-muted">কোনো বেতন পরিশোধ রেকর্ড পাওয়া যায়নি।</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- 4. Branch Sales Privacy System (Section 3) -->
    @if($reportType === 'sales')
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="ti-stats-up text-success me-2"></i> শাখাভিত্তিক বিক্রয় নিরীক্ষা ও পারফর্ম্যান্স রিপোর্ট (Branch Sales Privacy System)
                    </h6>
                    <small class="text-muted">
                        @if($isAdmin)
                            Main Admin হিসেবে আপনি সকল শাখার বিক্রয় এবং তুলনা পর্যবেক্ষণ করতে পারছেন।
                        @else
                            প্রাইভেসি নিয়ম: আপনি শুধুমাত্র আপনার নিজের ব্রাঞ্চের বিক্রয় তথ্য ও রিপোর্ট দেখতে পাচ্ছেন।
                        @endif
                    </small>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">শাখার নাম ও কোড</th>
                            <th>অবস্থান ও ফোন</th>
                            <th class="text-center">সম্পন্ন অর্ডার সংখ্যা</th>
                            <th class="text-end pe-4">মোট বিক্রয় রাজস্ব (Revenue)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $allSalesSum = 0; $allOrdersSum = 0; @endphp
                        @foreach($salesData as $sRow)
                            @php
                                $allSalesSum += $sRow['total_sales'];
                                $allOrdersSum += $sRow['orders_count'];
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-dark me-2">{{ $sRow['branch']->code }}</span>
                                    <strong class="text-dark">{{ $sRow['branch']->name }}</strong>
                                </td>
                                <td>{{ $sRow['branch']->area ?? $sRow['branch']->address ?? 'বাংলাদেশ' }}</td>
                                <td class="text-center fw-bold">{{ $sRow['orders_count'] }} টি</td>
                                <td class="text-end pe-4 fw-bold text-success fs-6">৳{{ number_format($sRow['total_sales'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="2" class="ps-4 text-uppercase">সম্মিলিত মোট বিক্রয় (Total)</td>
                            <td class="text-center">{{ $allOrdersSum }} টি</td>
                            <td class="text-end pe-4 text-success fs-5">৳{{ number_format($allSalesSum, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endif

        </section>
    </div>
</div>
@endsection
