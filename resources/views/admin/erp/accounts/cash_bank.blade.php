@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-university text-info me-2"></i> ক্যাশ বুক ও ব্যাংক বুক (Cash & Bank Book)</h4>
                    <p class="text-muted small mb-0">প্রতিষ্ঠানের সেন্ট্রাল ক্যাশ ভল্ট, পেটি ক্যাশ, বাণিজ্যিক ব্যাংক ও MFS (বিকাশ/নগদ) অ্যাকাউন্টের ব্যালেন্স ও লেনদেন ট্র্যাকিং।</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addBankAccountModal">
                        <i class="fa fa-plus me-1"></i> নতুন একাউন্ট যুক্ত করুন
                    </button>
                    <a href="{{ route('admin.erp.vouchers') }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                        <i class="fa fa-money me-1"></i> ভাউচার তৈরি
                    </a>
                </div>
            </div>

            <!-- KPI Metric Badges -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-success">
                        <small class="text-muted text-uppercase fw-bold font-size-11">নগদ ক্যাশ তহবিল</small>
                        <h4 class="fw-bold text-success my-1">৳ {{ number_format($totalCash, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-primary">
                        <small class="text-muted text-uppercase fw-bold font-size-11">বাণিজ্যিক ব্যাংক তহবিল</small>
                        <h4 class="fw-bold text-primary my-1">৳ {{ number_format($totalBank, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-info">
                        <small class="text-muted text-uppercase fw-bold font-size-11">মোবাইল ফিন্যান্স (MFS)</small>
                        <h4 class="fw-bold text-info my-1">৳ {{ number_format($totalMfs, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-warning">
                        <small class="text-muted text-uppercase fw-bold font-size-11">মোট সার্বিক তারল্য রিজার্ভ</small>
                        <h4 class="fw-bold text-dark my-1">৳ {{ number_format($grandTotalFunds, 2) }}</h4>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Bank Accounts Cards Grid -->
            <div class="row g-3 mb-4">
                @foreach($bankAccounts as $ba)
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 position-relative overflow-hidden">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="badge {{ $ba->account_type == 'cash' ? 'bg-success-light text-success' : ($ba->account_type == 'mfs' ? 'bg-info-light text-info' : 'bg-primary-light text-primary') }} text-uppercase fw-bold mb-1">
                                        {{ $ba->account_type }}
                                    </span>
                                    <h5 class="fw-bold text-dark mb-0 font-size-15">{{ $ba->account_name }}</h5>
                                    @if($ba->bank_name)
                                        <small class="text-muted">{{ $ba->bank_name }} ({{ $ba->branch_name ?? 'Branch' }})</small>
                                    @endif
                                </div>
                                <div class="avatar avatar-sm bg-light text-dark rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fa {{ $ba->account_type == 'cash' ? 'fa-money' : ($ba->account_type == 'mfs' ? 'fa-mobile' : 'fa-university') }}"></i>
                                </div>
                            </div>
                            <div class="mt-3 pt-2 border-top">
                                <div class="font-monospace text-muted small">একাউন্ট নং: {{ $ba->account_number ?? 'Main Cash' }}</div>
                                <div class="fw-bold font-size-18 mt-1 {{ $ba->current_balance < 0 ? 'text-danger' : 'text-success' }}">
                                    ৳ {{ number_format($ba->current_balance, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Recent Cash & Bank Transactions Table -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0"><i class="fa fa-history text-primary me-2"></i> সাম্প্রতিক ক্যাশ ও ব্যাংক লেনদেন বিবরণী</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-muted small text-uppercase">
                                <th class="ps-4">ভাউচার নম্বর ও তারিখ</th>
                                <th>ধরন</th>
                                <th>অ্যাকাউন্ট (Account)</th>
                                <th>প্রাপক / প্রদানকারী</th>
                                <th>পেমেন্ট মেথড</th>
                                <th>লেনদেনের পরিমাণ</th>
                                <th>স্ট্যাটাস</th>
                                <th class="pe-4 text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentVouchers as $v)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark font-monospace">{{ $v->voucher_number }}</div>
                                        <small class="text-muted">{{ $v->voucher_date ? \Carbon\Carbon::parse($v->voucher_date)->format('d M, Y') : '-' }}</small>
                                    </td>
                                    <td>
                                        @if($v->voucher_type == 'payment')
                                            <span class="badge bg-danger-light text-danger fw-bold">PV (টাকা প্রদান)</span>
                                        @elseif($v->voucher_type == 'receipt')
                                            <span class="badge bg-success-light text-success fw-bold">RV (টাকা গ্রহণ)</span>
                                        @else
                                            <span class="badge bg-info-light text-info fw-bold">CV (কন্ট্রা ট্রান্সফার)</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark font-size-13">{{ $v->bankAccount->account_name ?? 'ক্যাশ একাউন্ট' }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark font-size-13">{{ $v->payee_or_payer }}</div>
                                        <small class="text-muted">{{ Str::limit($v->description, 25) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ strtoupper($v->payment_method ?? 'CASH') }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold font-size-14 {{ $v->voucher_type == 'payment' ? 'text-danger' : 'text-success' }}">
                                            {{ $v->voucher_type == 'payment' ? '-' : '+' }} ৳ {{ number_format($v->amount, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success font-size-11"><i class="fa fa-check me-1"></i> পোস্ট করা</span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <a href="{{ route('admin.erp.vouchers.print', $v->id) }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                                            <i class="fa fa-print me-1"></i> ভাউচার
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fa fa-university fa-3x mb-3 text-secondary opacity-50"></i>
                                        <p class="mb-0">কোনো ব্যাংক বা ক্যাশ লেনদেন পাওয়া যায়নি।</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($recentVouchers->hasPages())
                    <div class="p-3 border-top">
                        {{ $recentVouchers->links() }}
                    </div>
                @endif
            </div>

        </section>
    </div>
</div>

<!-- Modal: Add New Cash or Bank Account -->
<div class="modal fade" id="addBankAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <form action="{{ route('admin.erp.accounts.cash_bank.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title fw-bold"><i class="fa fa-university text-primary me-2"></i> নতুন ক্যাশ বা ব্যাংক একাউন্ট সংযোজন</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">একাউন্টের নাম <span class="text-danger">*</span></label>
                            <input type="text" name="account_name" class="form-control" placeholder="যেমন: ডাচ-বাংলা ব্যাংক কারেন্ট একাউন্ট" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">একাউন্টের ধরন <span class="text-danger">*</span></label>
                            <select name="account_type" class="form-select" required>
                                <option value="bank">বাণিজ্যিক ব্যাংক (Bank Account)</option>
                                <option value="cash">নগদ ক্যাশ / পেটি ক্যাশ (Cash)</option>
                                <option value="mfs">মোবাইল ফিন্যান্স (bKash / Nagad / Rocket)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ব্যাংকের নাম</label>
                            <input type="text" name="bank_name" class="form-control" placeholder="Dutch-Bangla Bank Limited">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">হিসাব নম্বর (Account Number)</label>
                            <input type="text" name="account_number" class="form-control" placeholder="105-120-xxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">শাখা (Branch Name)</label>
                            <input type="text" name="branch_name" class="form-control" placeholder="Dhanmondi Branch">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">রাউটিং নম্বর</label>
                            <input type="text" name="routing_number" class="form-control" placeholder="09027xxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">প্রারম্ভিক ব্যালেন্স (Opening Balance ৳)</label>
                            <input type="number" step="0.01" name="opening_balance" class="form-control" value="0.00">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">একাউন্ট সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

