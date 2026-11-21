@extends('layouts.backend.app')

@section('title', 'কেন্দ্রীয় সর্ব-শাখা কর্মচারী বেতন অগ্রিম রিপোর্ট (All Branch Advance Report)')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content">

    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">
                <i class="ti-bar-chart text-warning me-2"></i> কেন্দ্রীয় সর্ব-শাখা কর্মচারী বেতন অগ্রিম রিপোর্ট
            </h4>
            <p class="text-muted small mb-0">সকল ব্রাঞ্চের মোট অগ্রিম প্রদান, মাসিক সমন্বয়, বকেয়া স্থিতি ও তুলনামূলক নিরীক্ষা রিপোর্ট।</p>
        </div>
        <div class="d-flex gap-2 mt-3 mt-md-0">
            <button onclick="window.print()" class="btn btn-outline-dark fw-bold shadow-sm">
                <i class="ti-printer me-1"></i> প্রিন্ট রিপোর্ট
            </button>
            <a href="{{ route('admin.supershop.employee_system.advances.index') }}" class="btn btn-outline-secondary">
                <i class="ti-arrow-left me-1"></i> অগ্রিম তালিকা
            </a>
        </div>
    </div>

    <!-- 12.1 Advance Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white" style="border-top: 4px solid #3b82f6 !important;">
                <span class="text-muted small fw-bold">সর্বমোট অগ্রিম প্রদান</span>
                <h4 class="fw-bold text-dark mt-1 mb-0">৳{{ number_format($totalAdvanceGiven, 0) }}</h4>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white" style="border-top: 4px solid #10b981 !important;">
                <span class="text-muted small fw-bold">আজকে প্রদত্ত অগ্রিম</span>
                <h4 class="fw-bold text-success mt-1 mb-0">৳{{ number_format($advanceGivenToday, 0) }}</h4>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white" style="border-top: 4px solid #f59e0b !important;">
                <span class="text-muted small fw-bold">চলতি মাসের অগ্রিম</span>
                <h4 class="fw-bold text-warning mt-1 mb-0">৳{{ number_format($advanceGivenThisMonth, 0) }}</h4>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white" style="border-top: 4px solid #06b6d4 !important;">
                <span class="text-muted small fw-bold">মোট সমন্বিত টাকা</span>
                <h4 class="fw-bold text-info mt-1 mb-0">৳{{ number_format($totalAdvanceAdjusted, 0) }}</h4>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white" style="border-top: 4px solid #ef4444 !important;">
                <span class="text-muted small fw-bold">মোট বকেয়া অগ্রিম</span>
                <h4 class="fw-bold text-danger mt-1 mb-0">৳{{ number_format($totalOutstandingAdvance, 0) }}</h4>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white" style="border-top: 4px solid #6366f1 !important;">
                <span class="text-muted small fw-bold">বকেয়া কর্মী সংখ্যা</span>
                <h4 class="fw-bold text-primary mt-1 mb-0">{{ $totalEmployeesWithAdvance }} জন</h4>
            </div>
        </div>
    </div>

    <!-- 12.2 Branch-wise Advance Aggregates Table -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3 border-0">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="ti-layout-grid2 text-primary me-2"></i> শাখাভিত্তিক মোট অগ্রিম তুলনা ও নিরীক্ষা (Branch-wise Advance Summary)
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">শাখার নাম</th>
                        <th class="text-center">বকেয়া কর্মী</th>
                        <th class="text-end">মোট প্রদত্ত অগ্রিম</th>
                        <th class="text-end">মোট সমন্বয়কৃত</th>
                        <th class="text-end">অবশিষ্ট বকেয়া স্থিতি</th>
                        <th class="text-center pe-4">একশন</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($branchAggregates as $bRow)
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-light text-dark border me-1">{{ $bRow['branch']->code }}</span>
                                <strong class="text-dark">{{ $bRow['branch']->name }}</strong>
                            </td>
                            <td class="text-center fw-bold">{{ $bRow['employees_count'] }} জন</td>
                            <td class="text-end fw-bold">৳{{ number_format($bRow['total_given'], 2) }}</td>
                            <td class="text-end fw-bold text-success">৳{{ number_format($bRow['adjusted'], 2) }}</td>
                            <td class="text-end fw-bold text-danger">৳{{ number_format($bRow['remaining'], 2) }}</td>
                            <td class="text-center pe-4">
                                <a href="{{ route('admin.supershop.employee_system.advances.index', ['branch_id' => $bRow['branch']->id]) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="ti-eye"></i> বিস্তারিত
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Filters & Full Advance Records List -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-0">
            <form action="{{ route('admin.supershop.employee_system.advances.central_report') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-3">
                    <select name="branch_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- সকল ব্রাঞ্চ --</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->code }} - {{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="employee_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- সকল কর্মচারী --</option>
                        @foreach($employees as $e)
                            <option value="{{ $e->id }}" {{ request('employee_id') == $e->id ? 'selected' : '' }}>{{ $e->name }} ({{ $e->employee_id }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="outstanding_only" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- সকল রেকর্ড --</option>
                        <option value="1" {{ request('outstanding_only') == '1' ? 'selected' : '' }}>শুধুমাত্র বকেয়া অগ্রিম</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="from_date" class="form-control form-control-sm" placeholder="From Date" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="to_date" class="form-control form-control-sm" placeholder="To Date" value="{{ request('to_date') }}">
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ভাউচার</th>
                        <th>শাখা</th>
                        <th>কর্মচারী</th>
                        <th>প্রদানের তারিখ</th>
                        <th>মোট অগ্রিম</th>
                        <th>সমন্বয়কৃত</th>
                        <th>অবশিষ্ট বকেয়া</th>
                        <th>স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($advances as $adv)
                        <tr>
                            <td class="ps-4 font-monospace fw-bold">{{ $adv->advance_no }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $adv->branch->code ?? '' }}</span></td>
                            <td>
                                <strong>{{ $adv->employee->name ?? 'Staff' }}</strong>
                                <small class="text-muted d-block">{{ $adv->employee->designation ?? '' }}</small>
                            </td>
                            <td>{{ date('d M Y', strtotime($adv->advance_date)) }}</td>
                            <td class="fw-bold">৳{{ number_format($adv->advance_amount, 2) }}</td>
                            <td class="text-success fw-bold">৳{{ number_format($adv->adjusted_amount, 2) }}</td>
                            <td class="text-danger fw-bold">৳{{ number_format($adv->remaining_amount, 2) }}</td>
                            <td>
                                @if($adv->status === 'fully_adjusted')
                                    <span class="badge bg-success">সম্পূর্ণ সমন্বিত</span>
                                @elseif($adv->status === 'partially_adjusted')
                                    <span class="badge bg-warning text-dark">আংশিক</span>
                                @else
                                    <span class="badge bg-danger">বকেয়া</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">কোনো রেকর্ড পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($advances->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $advances->links() }}
            </div>
        @endif
    </div>

        </section>
    </div>
</div>
@endsection
