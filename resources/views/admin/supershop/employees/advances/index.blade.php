@extends('layouts.backend.app')

@section('title', 'কর্মচারী বেতন অগ্রিম ব্যবস্থাপনা (Salary Advance Management)')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content">

    <!-- Header & Action Buttons -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">
                <i class="ti-wallet text-warning me-2"></i> কর্মচারী বেতন অগ্রিম (Salary Advance) ব্যবস্থাপনা
            </h4>
            <p class="text-muted small mb-0">শাখাভিত্তিক বেতন অগ্রিম প্রদান, কিস্তি নির্ধারণ এবং বেতনের সাথে সমন্বয় হিসাব।</p>
        </div>
        <div class="d-flex gap-2 mt-3 mt-md-0">
            <button type="button" class="btn btn-warning text-dark fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#newAdvanceModal">
                <i class="ti-plus me-1"></i> নতুন অগ্রিম প্রদান (New Advance)
            </button>
            @if($isAdmin)
                <a href="{{ route('admin.supershop.employee_system.advances.central_report') }}" class="btn btn-outline-dark fw-bold">
                    <i class="ti-bar-chart me-1"></i> কেন্দ্রীয় অল-ব্রাঞ্চ অগ্রিম রিপোর্ট
                </a>
            @endif
        </div>
    </div>

    <!-- 10.3 Advance Summary Cards with Formula Display -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white" style="border-left: 5px solid #f59e0b !important;">
                <span class="text-muted small fw-bold">মোট অগ্রিম প্রদান (Total Given)</span>
                <h3 class="fw-bold text-dark mt-1 mb-0">৳{{ number_format($totalGiven, 2) }}</h3>
                <small class="text-muted">অনুমোদিত ও প্রদানকৃত সর্বমোট</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white" style="border-left: 5px solid #10b981 !important;">
                <span class="text-muted small fw-bold">মোট সমন্বয়কৃত টাকা (Total Adjusted)</span>
                <h3 class="fw-bold text-success mt-1 mb-0">৳{{ number_format($totalAdjusted, 2) }}</h3>
                <small class="text-muted">মাসিক বেতনের মাধ্যমে কর্তনকৃত</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white" style="border-left: 5px solid #ef4444 !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold">বকেয়া অগ্রিম (Remaining Advance)</span>
                        <h3 class="fw-bold text-danger mt-1 mb-0">৳{{ number_format($totalRemaining, 2) }}</h3>
                        <small class="text-muted font-monospace">Remaining = Total − Adjusted</small>
                    </div>
                    <div class="p-3 bg-light rounded-circle text-danger">
                        <i class="ti-pie-chart fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advances Table -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark"><i class="ti-list text-primary me-2"></i> বেতন অগ্রিম রেকর্ড তালিকা</h6>
            @if($isAdmin)
                <span class="badge bg-primary">মেইন এডমিন ভিউ</span>
            @else
                <span class="badge bg-success">নিজ ব্রাঞ্চ ভিউ (Strict Privacy)</span>
            @endif
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ভাউচার নং ও তারিখ</th>
                        <th>কর্মচারী ও ব্রাঞ্চ</th>
                        <th>অগ্রিম টাকা</th>
                        <th>সমন্বয় কিস্তি</th>
                        <th>সমন্বিত টাকা</th>
                        <th>বকেয়া টাকা</th>
                        <th>স্ট্যাটাস</th>
                        <th class="text-center pe-4">একশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($advances as $adv)
                        <tr>
                            <td class="ps-4">
                                <span class="font-monospace fw-bold text-dark">{{ $adv->advance_no }}</span>
                                <small class="text-muted d-block">{{ date('d M Y', strtotime($adv->advance_date)) }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.supershop.employee_system.employees.show', $adv->employee_id) }}" class="fw-bold text-dark text-decoration-none">
                                    {{ $adv->employee->name ?? 'Staff' }}
                                </a>
                                <small class="text-muted d-block">{{ $adv->branch->name ?? '' }} ({{ $adv->employee->designation ?? '' }})</small>
                            </td>
                            <td><strong class="text-dark">৳{{ number_format($adv->advance_amount, 2) }}</strong></td>
                            <td>৳{{ number_format($adv->installment_amount, 2) }}</td>
                            <td><span class="fw-bold text-success">৳{{ number_format($adv->adjusted_amount, 2) }}</span></td>
                            <td><span class="fw-bold text-danger">৳{{ number_format($adv->remaining_amount, 2) }}</span></td>
                            <td>
                                @if($adv->status === 'fully_adjusted')
                                    <span class="badge bg-success-subtle text-success fw-bold">সম্পূর্ণ সমন্বিত</span>
                                @elseif($adv->status === 'partially_adjusted')
                                    <span class="badge bg-warning-subtle text-warning fw-bold">আংশিক সমন্বিত</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger fw-bold">বকেয়া (Disbursed)</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                @if($adv->remaining_amount > 0)
                                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#adjustModal{{ $adv->id }}" title="হাতে হাতে সমন্বয়">
                                        <i class="ti-check"></i> সমন্বয়
                                    </button>
                                @else
                                    <span class="badge bg-light text-muted border">পরিশোধিত</span>
                                @endif
                            </td>
                        </tr>

                        <!-- Manual Adjustment Modal -->
                        <div class="modal fade" id="adjustModal{{ $adv->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.supershop.employee_system.advances.adjust', $adv->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header bg-success-subtle">
                                            <h6 class="modal-title fw-bold text-dark"><i class="ti-wallet me-1"></i> অগ্রিম টাকা সমন্বয়: {{ $adv->advance_no }}</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="small text-muted mb-3">
                                                কর্মচারী: <strong>{{ $adv->employee->name ?? '' }}</strong> | বকেয়া অগ্রিম: <strong class="text-danger">৳{{ number_format($adv->remaining_amount, 2) }}</strong>
                                            </p>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">সমন্বয়ের পরিমাণ (টাকা) <span class="text-danger">*</span></label>
                                                <input type="number" step="0.01" name="adjusted_amount" class="form-control fw-bold" max="{{ $adv->remaining_amount }}" value="{{ min($adv->installment_amount, $adv->remaining_amount) }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">সমন্বয়ের তারিখ</label>
                                                <input type="date" name="adjustment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">মন্তব্য</label>
                                                <input type="text" name="remarks" class="form-control" placeholder="যেমন: ক্যাশে ফেরত / বিশেষ সমন্বয়">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                                            <button type="submit" class="btn btn-success fw-bold">সমন্বয় সম্পন্ন করুন</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">কোনো বেতন অগ্রিম রেকর্ড নেই।</td>
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

    <!-- New Advance Modal (Section 10.1) -->
    <div class="modal fade" id="newAdvanceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.supershop.employee_system.advances.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-warning text-dark">
                        <h6 class="modal-title fw-bold"><i class="ti-plus me-1"></i> নতুন বেতন অগ্রিম এন্ট্রি ফরম</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">কর্মচারী নির্বাচন করুন <span class="text-danger">*</span></label>
                                <select name="employee_id" class="form-select fw-bold" required>
                                    <option value="">-- কর্মচারী নির্বাচন করুন --</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}">
                                            {{ $emp->name }} ({{ $emp->employee_id }}) - {{ $emp->designation }} [বেতন: ৳{{ number_format($emp->monthly_salary, 0) }}]
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">অগ্রিম টাকার পরিমাণ (৳) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="advance_amount" class="form-control fw-bold fs-5 text-danger" placeholder="0.00" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">অগ্রিম নেওয়ার তারিখ</label>
                                <input type="date" name="advance_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">পরিশোধের মাধ্যম</label>
                                <select name="payment_method" class="form-select">
                                    <option value="cash">নগদ ক্যাশ (Cash)</option>
                                    <option value="bank">ব্যাংক ট্রান্সফার (Bank)</option>
                                    <option value="mobile_banking">মোবাইল ব্যাংকিং (bKash/Nagad)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">যে মাসে প্রথম কিস্তি সমন্বয় হবে</label>
                                <input type="month" name="adjustment_month" class="form-control" value="{{ date('Y-m') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">মাসিক কিস্তির পরিমাণ (টাকা)</label>
                                <input type="number" step="0.01" name="installment_amount" class="form-control" placeholder="যেমন: ৫০০০ (যদি কিস্তিতে সমন্বয় হয়)">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">অগ্রিম নেওয়ার কারণ (Reason)</label>
                                <input type="text" name="reason" class="form-control" placeholder="যেমন: পারিবারিক ও জরুরি প্রয়োজন">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">অতিরিক্ত মন্তব্য / নোট</label>
                                <textarea name="remarks" class="form-control" rows="2" placeholder="ম্যানেজারের অনুমোদন সংক্রান্ত মন্তব্য..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                        <button type="submit" class="btn btn-warning text-dark fw-bold px-4">অগ্রিম প্রদান নিশ্চিত করুন</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

        </section>
    </div>
</div>
@endsection
