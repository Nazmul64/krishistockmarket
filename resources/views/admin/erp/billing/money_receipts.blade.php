@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-ticket text-success me-2"></i> মানি রিসিট ব্যবস্থাপনা (Official Money Receipts)</h4>
                    <p class="text-muted small mb-0">গ্রাহক, সদস্য ও ভেন্ডরদের থেকে প্রাপ্ত যেকোনো নগদ, চেক বা ব্যাংক জমার অফিসিয়াল মানি রিসিট ইস্যু ও হিসাবভুক্তকরণ।</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-success rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addReceiptModal">
                        <i class="fa fa-plus me-1"></i> নতুন মানি রিসিট ইস্যু
                    </button>
                    <a href="{{ route('admin.erp.billing.cash_memos') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="fa fa-calculator me-1"></i> ক্যাশ মেমো (POS)
                    </a>
                </div>
            </div>

            <!-- KPI Metric Badges -->
            <div class="row g-3 mb-4">
                <div class="col-md-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-primary">
                        <small class="text-muted text-uppercase fw-bold font-size-11">মোট ইস্যুকৃত মানি রিসিট</small>
                        <h4 class="fw-bold text-dark my-1">{{ number_format($totalReceiptsCount) }} টি</h4>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-success">
                        <small class="text-muted text-uppercase fw-bold font-size-11">মোট আদায়কৃত রাজস্ব ও তহবিল</small>
                        <h4 class="fw-bold text-success my-1">৳ {{ number_format($totalCollectedAmount, 2) }}</h4>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Receipts Table -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-muted small text-uppercase">
                                <th class="ps-4">রিসিট নম্বর ও তারিখ</th>
                                <th>গ্রাহক / সদস্য</th>
                                <th>প্রদানের উদ্দেশ্য / খাত</th>
                                <th>আদায়ের মাধ্যম</th>
                                <th>জমা হওয়া ব্যাংক / ক্যাশ</th>
                                <th>আদায়কৃত অর্থ</th>
                                <th>গ্রহীতা</th>
                                <th class="pe-4 text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($receipts as $mr)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark font-monospace">{{ $mr->receipt_number }}</div>
                                        <small class="text-muted">{{ $mr->receipt_date ? \Carbon\Carbon::parse($mr->receipt_date)->format('d M, Y') : '-' }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark font-size-13">{{ $mr->customer_name }}</div>
                                        <small class="text-muted"><i class="fa fa-phone me-1"></i>{{ $mr->customer_phone ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border font-size-12">{{ $mr->payment_purpose }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-light text-info">{{ strtoupper($mr->payment_method) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark font-size-13">{{ $mr->bankAccount->account_name ?? 'ক্যাশ কাউন্টার' }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success font-size-15">৳ {{ number_format($mr->received_amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <div class="small fw-bold text-dark">{{ $mr->received_by ?? '-' }}</div>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <a href="{{ route('admin.erp.billing.money_receipts.print', $mr->id) }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3" title="মানি রিসিট প্রিন্ট করুন">
                                            <i class="fa fa-print me-1"></i> রিসিট প্রিন্ট
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fa fa-ticket fa-3x mb-3 text-secondary opacity-50"></i>
                                        <p class="mb-0">কোনো মানি রিসিট রেকর্ড পাওয়া যায়নি।</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($receipts->hasPages())
                    <div class="p-3 border-top">
                        {{ $receipts->links() }}
                    </div>
                @endif
            </div>

        </section>
    </div>
</div>

<!-- Modal: Add New Money Receipt -->
<div class="modal fade" id="addReceiptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <form action="{{ route('admin.erp.billing.money_receipts.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title fw-bold"><i class="fa fa-ticket text-success me-2"></i> অফিসিয়াল মানি রিসিট ইস্যু</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">গ্রাহক / সদস্যের নাম <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control" placeholder="গ্রাহকের পুরো নাম" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">মোবাইল নম্বর</label>
                            <input type="text" name="customer_phone" class="form-control" placeholder="017xxxxxxxx">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">ঠিকানা</label>
                            <input type="text" name="customer_address" class="form-control" placeholder="বাড়ি নং, রোড, এলাকা...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">টাকা গ্রহণের উদ্দেশ্য / খাত <span class="text-danger">*</span></label>
                            <input type="text" name="payment_purpose" class="form-control" placeholder="যেমন: বুকিং মানি / শেয়ার কিস্তি / সেবা ফি" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">রিসিটের তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="receipt_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">প্রাপ্ত অর্থ (৳) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="received_amount" id="receiptAmount" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">জমা হওয়া একাউন্ট (Cash / Bank) <span class="text-danger">*</span></label>
                            <select name="bank_account_id" class="form-select" required>
                                <option value="">-- একাউন্ট নির্বাচন করুন --</option>
                                @foreach($bankAccounts as $ba)
                                    <option value="{{ $ba->id }}">
                                        {{ $ba->account_name }} ({{ strtoupper($ba->account_type) }}) - বর্তমান: ৳ {{ number_format($ba->current_balance, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">পেমেন্ট মাধ্যম</label>
                            <select name="payment_method" class="form-select">
                                <option value="cash">নগদ ক্যাশ (Cash)</option>
                                <option value="bank_transfer">ব্যাংক ডিপোজিট / অনলাইন (Bank)</option>
                                <option value="cheque">চেক (Cheque)</option>
                                <option value="mfs">বিকাশ / নগদ (MFS)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">পূর্ববর্তী বকেয়া (যদি থাকে)</label>
                            <input type="number" step="0.01" name="previous_due" class="form-control" value="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">বর্তমান অবশিষ্ট বকেয়া</label>
                            <input type="number" step="0.01" name="remaining_due" class="form-control" value="0.00">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">কথায় (Amount in Words)</label>
                            <input type="text" name="amount_in_words" class="form-control" placeholder="কথায় লিখুন...">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">বিশেষ মন্তব্য / নোট</label>
                            <textarea name="remarks" class="form-control" rows="2" placeholder="অতিরিক্ত বিবরণ বা রেফারেন্স..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success px-4 fw-bold">মানি রিসিট ইস্যু ও লেজারে পোস্ট</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

