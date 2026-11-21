@extends('layouts.backend.app')

@section('title', 'মাসিক বেতন ও পে-রোল ম্যানেজমেন্ট (Monthly Payroll Management)')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content">

    <!-- Header & Action -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">
                <i class="ti-receipt text-primary me-2"></i> মাসিক কর্মচারী বেতন ও পে-রোল (Payroll) ব্যবস্থাপনা
            </h4>
            <p class="text-muted small mb-0">হাজিরা, ছুটি, ওভারটাইম, ভাতা এবং বেতন অগ্রিম সমন্বয় করে মাসিক বেতন জেনারেট ও পরিশোধ করুন।</p>
        </div>
        <div>
            <button type="button" class="btn btn-success fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#generatePayrollModal">
                <i class="ti-plus me-1"></i> মাসিক পে-রোল তৈরি করুন (Run Payroll)
            </button>
        </div>
    </div>

    <!-- Branch Filter if Admin -->
    @if($isAdmin)
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-3">
                <form action="{{ route('admin.supershop.employee_system.payroll.index') }}" method="GET" class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted mb-1">ব্রাঞ্চ নির্বাচন</label>
                        <select name="branch_id" class="form-select fw-bold" onchange="this.form.submit()">
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}" {{ $selectedBranchId == $b->id ? 'selected' : '' }}>{{ $b->code }} - {{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Payroll History List -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark"><i class="ti-list text-primary me-2"></i> মাসিক পে-রোল রান ইতিহাস</h6>
            <span class="badge bg-light text-dark border">Section 15 Specification</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">পে-রোল কোড</th>
                        <th>বেতন মাস</th>
                        <th>ব্রাঞ্চ</th>
                        <th class="text-center">স্টাফ সংখ্যা</th>
                        <th class="text-end">মোট গ্রস বেতন</th>
                        <th class="text-end">অগ্রিম কর্তন</th>
                        <th class="text-end">সর্বমোট প্রদেয়</th>
                        <th class="text-end">পরিশোধিত</th>
                        <th class="text-end">বাকি বেতন</th>
                        <th>স্ট্যাটাস</th>
                        <th class="text-center pe-4">একশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $pay)
                        <tr>
                            <td class="ps-4 font-monospace fw-bold text-dark">{{ $pay->payroll_code }}</td>
                            <td><strong class="text-primary">{{ date('F Y', strtotime($pay->salary_month . '-01')) }}</strong></td>
                            <td><span class="badge bg-light text-dark border">{{ $pay->branch->code ?? '' }}</span> {{ $pay->branch->name ?? '' }}</td>
                            <td class="text-center fw-bold">{{ $pay->total_employees }} জন</td>
                            <td class="text-end">৳{{ number_format($pay->total_gross, 2) }}</td>
                            <td class="text-end text-danger">-৳{{ number_format($pay->total_advance_deductions, 2) }}</td>
                            <td class="text-end fw-bold text-dark">৳{{ number_format($pay->total_net_payable, 2) }}</td>
                            <td class="text-end fw-bold text-success">৳{{ number_format($pay->total_paid, 2) }}</td>
                            <td class="text-end fw-bold text-danger">৳{{ number_format($pay->total_pending, 2) }}</td>
                            <td>
                                @if($pay->status === 'paid')
                                    <span class="badge bg-success">সম্পূর্ণ পরিশোধিত</span>
                                @elseif($pay->status === 'approved')
                                    <span class="badge bg-primary">অনুমোদিত</span>
                                @else
                                    <span class="badge bg-warning text-dark">ড্রাফট / জেনারেটেড</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <a href="{{ route('admin.supershop.employee_system.payroll.show', $pay->id) }}" class="btn btn-sm btn-primary">
                                    <i class="ti-eye me-1"></i> শিট ও বেতন দিন
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-5 text-muted">
                                <i class="ti-receipt fa-3x d-block mb-3 text-secondary"></i>
                                কোনো পে-রোল তৈরি করা হয়নি। নতুন মাসের পে-রোল তৈরি করতে উপরের বাটনে ক্লিক করুন।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payrolls->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $payrolls->links() }}
            </div>
        @endif
    </div>

    <!-- Generate Monthly Payroll Modal (Section 15) -->
    <div class="modal fade" id="generatePayrollModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.supershop.employee_system.payroll.generate') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h6 class="modal-title fw-bold text-white"><i class="ti-calculator me-1"></i> নতুন মাসিক পে-রোল জেনারেট করুন</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">ব্রাঞ্চ নির্বাচন <span class="text-danger">*</span></label>
                            @if($isAdmin)
                                <select name="branch_id" class="form-select fw-bold" required>
                                    @foreach($branches as $b)
                                        <option value="{{ $b->id }}">{{ $b->code }} - {{ $b->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="hidden" name="branch_id" value="{{ $userBranchId }}">
                                <input type="text" class="form-control bg-light" value="{{ $branches->first()->name ?? '' }}" readonly>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">বেতন প্রদানের মাস ও বছর <span class="text-danger">*</span></label>
                            <input type="month" name="salary_month" class="form-control fw-bold" value="{{ date('Y-m') }}" required>
                        </div>

                        <div class="alert alert-info border-0 small mb-3">
                            <i class="ti-info-alt me-1"></i> সিস্টেম স্বয়ংক্রিয়ভাবে নির্বাচিত মাসের মোট উপস্থিতি, অনুপস্থিতি কর্তন, ওভারটাইম এবং কর্মচারীদের বকেয়া অগ্রিম হতে নির্ধারিত কিস্তি কর্তন করে নিট প্রদেয় বেতন হিসাব করবে।
                        </div>

                        <div class="mb-3">
                            <label class="form-label">নোট / বিশেষ নির্দেশনা</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="ঐচ্ছিক মন্তব্য..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                        <button type="submit" class="btn btn-success fw-bold px-4">পে-রোল প্রসেস করুন</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

        </section>
    </div>
</div>
@endsection
