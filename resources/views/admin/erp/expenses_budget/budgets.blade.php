@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-pie-chart text-primary me-2"></i> বিভাগভিত্তিক বাজেট ও ব্যয় নিয়ন্ত্রণ (Departmental Budgets)</h4>
                    <p class="text-muted small mb-0">প্রতিটি বিভাগ ও খাতের বার্ষিক/মাসিক বাজেট নির্ধারণ, ব্যয় ট্র্যাকিং এবং ওভার-বাজেট সতর্কতা ব্যবস্থাপনা।</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addBudgetModal">
                        <i class="fa fa-plus me-1"></i> নতুন বাজেট নির্ধারণ
                    </button>
                    <a href="{{ route('admin.erp.expenses') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                        <i class="fa fa-credit-card me-1"></i> খরচ এন্ট্রি
                    </a>
                </div>
            </div>

            <!-- KPI Metric Badges -->
            <div class="row g-3 mb-4">
                <div class="col-md-4 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-primary">
                        <small class="text-muted text-uppercase fw-bold font-size-11">মোট বরাদ্দকৃত বাজেট</small>
                        <h4 class="fw-bold text-primary my-1">৳ {{ number_format($totalBudgetAmount, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-warning">
                        <small class="text-muted text-uppercase fw-bold font-size-11">মোট ব্যবহারিত অর্থ</small>
                        <h4 class="fw-bold text-warning my-1">৳ {{ number_format($totalSpentAmount, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-success">
                        <small class="text-muted text-uppercase fw-bold font-size-11">অবশিষ্ট বাজেট তহবিল</small>
                        <h4 class="fw-bold text-success my-1">৳ {{ number_format($totalRemainingAmount, 2) }}</h4>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Budgets Table -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-muted small text-uppercase">
                                <th class="ps-4">অর্থবছর ও বিভাগ</th>
                                <th>বাজেট খাত (Budget Head)</th>
                                <th>মোট বরাদ্দকৃত বাজেট</th>
                                <th>ব্যয়িত অর্থ</th>
                                <th>অবশিষ্ট ব্যালেন্স</th>
                                <th style="width: 25%;">ব্যবহারের অগ্রগতি (% Progress)</th>
                                <th class="pe-4 text-end">অবস্থা</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($budgets as $b)
                                @php
                                    $pct = $b->budget_amount > 0 ? min(100, round(($b->spent_amount / $b->budget_amount) * 100, 1)) : 0;
                                    $barColor = 'bg-success';
                                    if ($pct >= $b->alert_threshold_percentage) {
                                        $barColor = 'bg-danger';
                                    } elseif ($pct >= 70) {
                                        $barColor = 'bg-warning';
                                    }
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <span class="badge bg-light text-dark border font-monospace">{{ $b->fiscal_year }}</span>
                                        <div class="fw-bold text-dark font-size-13 mt-1">{{ $b->department_name }}</div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-primary font-size-14">{{ $b->budget_head }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark font-size-14">৳ {{ number_format($b->budget_amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-warning font-size-14">৳ {{ number_format($b->spent_amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success font-size-14">৳ {{ number_format($b->remaining_amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-between small text-muted mb-1">
                                            <span>ব্যয়: {{ $pct }}%</span>
                                            <span>সতর্কবার্তা সীমা: {{ $b->alert_threshold_percentage }}%</span>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar {{ $barColor }}" style="width: {{ $pct }}%;"></div>
                                        </div>
                                    </td>
                                    <td class="pe-4 text-end">
                                        @if($pct >= $b->alert_threshold_percentage)
                                            <span class="badge bg-danger font-size-11"><i class="fa fa-exclamation-triangle me-1"></i> প্রায় সমাপ্ত</span>
                                        @else
                                            <span class="badge bg-success font-size-11"><i class="fa fa-check me-1"></i> স্বাভাবিক</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fa fa-pie-chart fa-3x mb-3 text-secondary opacity-50"></i>
                                        <p class="mb-0">কোনো বিভাগীয় বাজেট রেকর্ড পাওয়া যায়নি।</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
    </div>
</div>

<!-- Modal: Add New Budget -->
<div class="modal fade" id="addBudgetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <form action="{{ route('admin.erp.budgets.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title fw-bold"><i class="fa fa-pie-chart text-primary me-2"></i> নতুন বিভাগীয় বাজেট বরাদ্দ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">অর্থবছর (Fiscal Year) <span class="text-danger">*</span></label>
                            <input type="text" name="fiscal_year" class="form-control" value="2026-2027" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">বিভাগের নাম <span class="text-danger">*</span></label>
                            <input type="text" name="department_name" class="form-control" placeholder="যেমন: আইটি ও সিস্টেমস বিভাগ" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">বাজেট খাতের শিরোনাম <span class="text-danger">*</span></label>
                            <input type="text" name="budget_head" class="form-control" placeholder="যেমন: সার্ভার হোস্টিং ও সফটওয়্যার লাইসেন্স" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">বরাদ্দকৃত বাজেটের পরিমাণ (৳) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="budget_amount" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">সতর্কবার্তা নোটিফিকেশন সীমা (%)</label>
                            <input type="number" name="alert_threshold_percentage" class="form-control" value="90" min="50" max="100">
                            <small class="text-muted">বাজেটের কত শতাংশ ব্যয় হলে রেড অ্যালার্ট দেখাবে</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">বাজেট অনুমোদন করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

