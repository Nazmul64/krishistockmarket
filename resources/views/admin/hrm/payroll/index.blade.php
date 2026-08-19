@extends('layouts.backend.app')

@section('title', 'পে-রোল & বেতন ব্যবস্থাপনা')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="font-weight-bold mb-0" style="color: #2e7d32;">
                        <i class="ti-money mr-2"></i> পে-রোল & বেতন জেনারেটর (Payroll Processing)
                    </h3>
                    <p class="text-muted mb-0">মাসিক স্যালারি শিট তৈরি, কর্তন হিসাব ও পেশাদার পে-স্লিপ প্রিন্ট</p>
                </div>
                <button type="button" class="btn btn-success btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#generatePayrollModal" data-toggle="modal" data-target="#generatePayrollModal">
                    <i class="ti-reload mr-1"></i> পে-রোল জেনারেট করুন
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="ti-check-box mr-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" data-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Month Selector -->
    <div class="card border-0 shadow-sm rounded-lg mb-4">
        <div class="card-body py-3">
            <form action="{{ route('admin.hrm.payroll.index') }}" method="GET" class="row align-items-center">
                <div class="col-md-6 my-1">
                    <label class="font-weight-bold mb-0 mr-2">মাস নির্বাচন করুন:</label>
                    <input type="month" name="month_year" class="form-control d-inline-block w-auto bg-light border-0" value="{{ $selectedMonth }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-6 text-right my-1">
                    @if($payroll)
                    <span class="badge badge-success px-3 py-2">মোট কর্মকর্তা: {{ $payroll->total_employees }} জন | মোট প্রদান: ৳{{ number_format($payroll->total_net) }}</span>
                    @else
                    <span class="badge badge-warning px-3 py-2">এই মাসের পে-রোল এখনও জেনারেট করা হয়নি</span>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Payroll Items Table -->
    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>কর্মী</th>
                            <th>ডিপার্টমেন্ট & পদবী</th>
                            <th>মূল বেতন</th>
                            <th>ভাতা (Allowances)</th>
                            <th>অনুপস্থিত কর্তন</th>
                            <th>লোন/এডভান্স কর্তন</th>
                            <th>নীট প্রাপ্য (Net Salary)</th>
                            <th>স্ট্যাটাস</th>
                            <th class="text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrollItems as $item)
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $item->user->name ?? 'N/A' }}</td>
                            <td>
                                <div>{{ $item->user->hrmProfile->department->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $item->user->hrmProfile->designation->name ?? 'N/A' }}</small>
                            </td>
                            <td>৳{{ number_format($item->basic_salary) }}</td>
                            <td class="text-success">+৳{{ number_format($item->allowances) }}</td>
                            <td class="text-danger">-৳{{ number_format($item->absent_deduction) }}</td>
                            <td class="text-danger">-৳{{ number_format($item->loan_deduction + $item->advance_deduction) }}</td>
                            <td><span class="font-weight-bold text-success" style="font-size: 16px;">৳{{ number_format($item->net_salary) }}</span></td>
                            <td>
                                @if($item->status == 'paid')
                                    <span class="badge badge-success">পরিশোধিত</span>
                                @else
                                    <span class="badge badge-warning">অপরিশোধিত</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if($item->status == 'unpaid')
                                <form action="{{ route('admin.hrm.payroll.mark_paid', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 mr-1">পে করুন</button>
                                </form>
                                @endif
                                <a href="{{ route('admin.hrm.payroll.payslip', $item->id) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill">
                                    <i class="ti-printer mr-1"></i> স্লিপ
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">এই মাসের কোনো স্যালারি শিট জেনারেট করা হয়নি। উপরে "পে-রোল জেনারেট করুন" বাটনে ক্লিক করুন।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Generate Modal -->
<div class="modal fade" id="generatePayrollModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <form action="{{ route('admin.hrm.payroll.generate') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-header-title text-white mb-0"><i class="ti-reload mr-2"></i> পে-রোল স্যালারি শিট জেনারেট</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">মাস ও বছর নির্বাচন করুন <span class="text-danger">*</span></label>
                        <input type="month" name="month_year" class="form-control" value="{{ date('Y-m') }}" required>
                    </div>
                    <p class="text-muted small mb-0">
                        <i class="ti-info-alt text-info mr-1"></i> এটি স্বয়ংক্রিয়ভাবে কর্মী উপস্থিতি, অনুপস্থিতি দিবস, লোন কিস্তি ও স্যালারি এডভান্স কর্তন হিসাব করে নীট স্যালারি প্রস্তুত করবে।
                    </p>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal" data-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success rounded-pill">জেনারেট শুরু করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
