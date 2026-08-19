@extends('layouts.backend.app')

@section('title', 'এইচআর রিপোর্টস & এনালিটিক্স')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="font-weight-bold mb-0" style="color: #2e7d32;">
                        <i class="ti-bar-chart mr-2"></i> এইচআর রিপোর্টস & এনালিটিক্স (HR Reports)
                    </h3>
                    <p class="text-muted mb-0">মাসিক উপস্থিতি সমারি, স্যালারি শিট রিপোর্ট ও লিভ এনালিটিক্স</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Month Filter -->
    <div class="card border-0 shadow-sm rounded-lg mb-4">
        <div class="card-body py-3">
            <form action="{{ route('admin.hrm.reports.index') }}" method="GET" class="row align-items-center">
                <div class="col-md-6 my-1">
                    <label class="font-weight-bold mb-0 mr-2">রিপোর্ট মাস নির্বাচন করুন:</label>
                    <input type="month" name="month" class="form-control d-inline-block w-auto bg-light border-0" value="{{ $month }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-6 text-right my-1">
                    <button type="button" onclick="window.print()" class="btn btn-dark btn-sm rounded-pill px-3"><i class="ti-printer mr-1"></i> রিপোর্ট প্রিন্ট করুন</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Report Tables -->
    <div class="card border-0 shadow-sm rounded-lg mb-4">
        <div class="card-header bg-white border-0 pt-3">
            <h5 class="font-weight-bold text-dark mb-0"><i class="ti-money text-success mr-2"></i> ১. স্যালারি পে-রোল রিপোর্ট ({{ $month }})</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>কর্মী</th>
                            <th>ডিপার্টমেন্ট</th>
                            <th>মূল বেতন</th>
                            <th>ভাতা</th>
                            <th>অনুপস্থিতি কর্তন</th>
                            <th>লোন/এডভান্স কর্তন</th>
                            <th>নীট বেতন</th>
                            <th>স্ট্যাটাস</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls as $pr)
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $pr->user->name ?? 'N/A' }}</td>
                            <td>{{ $pr->user->hrmProfile->department->name ?? 'N/A' }}</td>
                            <td>৳{{ number_format($pr->basic_salary) }}</td>
                            <td>৳{{ number_format($pr->allowances) }}</td>
                            <td class="text-danger">-৳{{ number_format($pr->absent_deduction) }}</td>
                            <td class="text-danger">-৳{{ number_format($pr->loan_deduction + $pr->advance_deduction) }}</td>
                            <td class="font-weight-bold text-success">৳{{ number_format($pr->net_salary) }}</td>
                            <td>
                                @if($pr->status == 'paid')
                                    <span class="badge badge-success">Paid</span>
                                @else
                                    <span class="badge badge-warning">Unpaid</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">এই মাসের পে-রোল রিপোর্ট পাওয়া যায়নি।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-header bg-white border-0 pt-3">
            <h5 class="font-weight-bold text-dark mb-0"><i class="ti-check-box text-primary mr-2"></i> ২. উপস্থিতি ডাইজেস্ট সমারি ({{ $month }})</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>কর্মী</th>
                            <th>ডিপার্টমেন্ট</th>
                            <th>উপস্থিত দিন</th>
                            <th>অনুপস্থিত দিন</th>
                            <th>দেরিতে আসা</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $emp)
                        @php
                            $userAtt = $attendanceSummary[$emp->id] ?? collect();
                            $presentCount = $userAtt->where('status', 'present')->first()->count ?? 0;
                            $absentCount = $userAtt->where('status', 'absent')->first()->count ?? 0;
                            $lateCount = $userAtt->where('status', 'late')->first()->count ?? 0;
                        @endphp
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $emp->name }}</td>
                            <td>{{ $emp->hrmProfile->department->name ?? 'N/A' }}</td>
                            <td><span class="badge badge-success px-3 py-1">{{ $presentCount }} দিন</span></td>
                            <td><span class="badge badge-danger px-3 py-1">{{ $absentCount }} দিন</span></td>
                            <td><span class="badge badge-warning px-3 py-1">{{ $lateCount }} দিন</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">কোনো কর্মীর বিবরণী পাওয়া যায়নি।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
