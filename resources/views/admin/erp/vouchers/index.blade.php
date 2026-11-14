@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-book text-success me-2"></i> কেন্দ্রীয় হিসাব ভাউচার ব্যবস্থাপনা (Voucher Management)</h4>
                    <p class="text-muted small mb-0">পেমেন্ট ভাউচার (PV), রিসিট ভাউচার (RV), জার্নাল ভাউচার (JV) ও কন্ট্রা ভাউচার (CV) সম্বলিত ডাবল-এন্ট্রি বুককিপিং।</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-success rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addVoucherModal">
                        <i class="fa fa-plus me-1"></i> নতুন ভাউচার তৈরি
                    </button>
                    <a href="{{ route('admin.erp.accounts.chart_of_accounts') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="fa fa-sitemap me-1"></i> চার্ট অব অ্যাকাউন্টস
                    </a>
                    <a href="{{ route('admin.erp.accounts.general_ledger') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                        <i class="fa fa-list-alt me-1"></i> জেনারেল লেজার
                    </a>
                </div>
            </div>

            <!-- KPI Metric Badges -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-danger">
                        <small class="text-muted text-uppercase fw-bold font-size-11">পেমেন্ট ভাউচার (PV)</small>
                        <h4 class="fw-bold text-danger my-1">৳ {{ number_format($totalPaymentVouchers, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-success">
                        <small class="text-muted text-uppercase fw-bold font-size-11">রিসিট ভাউচার (RV)</small>
                        <h4 class="fw-bold text-success my-1">৳ {{ number_format($totalReceiptVouchers, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-primary">
                        <small class="text-muted text-uppercase fw-bold font-size-11">জার্নাল ভাউচার (JV)</small>
                        <h4 class="fw-bold text-primary my-1">৳ {{ number_format($totalJournalVouchers, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-info">
                        <small class="text-muted text-uppercase fw-bold font-size-11">কন্ট্রা ভাউচার (CV)</small>
                        <h4 class="fw-bold text-info my-1">৳ {{ number_format($totalContraVouchers, 2) }}</h4>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
                    <i class="fa fa-exclamation-triangle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Voucher Filter Form -->
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
                <form action="{{ route('admin.erp.vouchers') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="ভাউচার নম্বর, প্রাপক বা বিবরণ দিয়ে খুঁজুন..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="voucher_type" class="form-select form-select-sm">
                            <option value="">-- সকল ভাউচারের ধরন --</option>
                            <option value="payment" {{ request('voucher_type') == 'payment' ? 'selected' : '' }}>পেমেন্ট ভাউচার (Payment Voucher - PV)</option>
                            <option value="receipt" {{ request('voucher_type') == 'receipt' ? 'selected' : '' }}>রিসিট ভাউচার (Receipt Voucher - RV)</option>
                            <option value="journal" {{ request('voucher_type') == 'journal' ? 'selected' : '' }}>জার্নাল ভাউচার (Journal Voucher - JV)</option>
                            <option value="contra" {{ request('voucher_type') == 'contra' ? 'selected' : '' }}>কন্ট্রা ভাউচার (Contra Voucher - CV)</option>
                            <option value="adjustment" {{ request('voucher_type') == 'adjustment' ? 'selected' : '' }}>সমন্বয় ভাউচার (Adjustment Voucher - AV)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-sm btn-dark w-100 rounded-3"><i class="fa fa-filter me-1"></i> ফিল্টার</button>
                    </div>
                </form>
            </div>

            <!-- Vouchers Table -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-muted small text-uppercase">
                                <th class="ps-4">ভাউচার নং ও তারিখ</th>
                                <th>ধরন</th>
                                <th>প্রাপক / প্রদানকারী</th>
                                <th>মোট পরিমাণ</th>
                                <th>অ্যাকাউন্টস এন্ট্রি ব্রেকডাউন (Debit / Credit)</th>
                                <th>স্ট্যাটাস</th>
                                <th>প্রস্তুতকারী</th>
                                <th class="pe-4 text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vouchers as $v)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark font-monospace">{{ $v->voucher_number }}</div>
                                        <small class="text-muted">{{ $v->voucher_date ? \Carbon\Carbon::parse($v->voucher_date)->format('d M, Y') : '-' }}</small>
                                    </td>
                                    <td>
                                        @if($v->voucher_type == 'payment')
                                            <span class="badge bg-danger-light text-danger fw-bold">PV (পেমেন্ট)</span>
                                        @elseif($v->voucher_type == 'receipt')
                                            <span class="badge bg-success-light text-success fw-bold">RV (রিসিট)</span>
                                        @elseif($v->voucher_type == 'contra')
                                            <span class="badge bg-info-light text-info fw-bold">CV (কন্ট্রা)</span>
                                        @elseif($v->voucher_type == 'journal')
                                            <span class="badge bg-primary-light text-primary fw-bold">JV (জার্নাল)</span>
                                        @else
                                            <span class="badge bg-secondary-light text-secondary fw-bold">AV (সমন্বয়)</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark font-size-13">{{ $v->payee_or_payer }}</div>
                                        <small class="text-muted">{{ Str::limit($v->description, 25) }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark font-size-15">৳ {{ number_format($v->amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <div class="font-size-11">
                                            @foreach($v->entries as $entry)
                                                <div>
                                                    <span class="text-muted">{{ $entry->account->account_name ?? 'Acc' }}:</span>
                                                    @if($entry->debit > 0)
                                                        <strong class="text-primary ms-1">Dr: ৳{{ number_format($entry->debit, 2) }}</strong>
                                                    @endif
                                                    @if($entry->credit > 0)
                                                        <strong class="text-success ms-1">Cr: ৳{{ number_format($entry->credit, 2) }}</strong>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success font-size-11"><i class="fa fa-check-circle me-1"></i> পোস্ট করা হয়েছে</span>
                                    </td>
                                    <td>
                                        <div class="small fw-bold text-dark">{{ $v->prepared_by ?? '-' }}</div>
                                        <small class="text-muted">অনুমোদন: {{ $v->approved_by ?? '-' }}</small>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <a href="{{ route('admin.erp.vouchers.print', $v->id) }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3" title="অফিসিয়াল ভাউচার প্রিন্ট করুন">
                                            <i class="fa fa-print me-1"></i> প্রিন্ট
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fa fa-book fa-3x mb-3 text-secondary opacity-50"></i>
                                        <p class="mb-0">কোনো ভাউচার রেকর্ড পাওয়া যায়নি।</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($vouchers->hasPages())
                    <div class="p-3 border-top">
                        {{ $vouchers->links() }}
                    </div>
                @endif
            </div>

        </section>
    </div>
</div>

<!-- Modal: Add New Double Entry Voucher -->
<div class="modal fade" id="addVoucherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <form action="{{ route('admin.erp.vouchers.store') }}" method="POST" id="voucherForm">
                @csrf
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title fw-bold"><i class="fa fa-book text-success me-2"></i> নতুন ডাবল-এন্ট্রি ভাউচার পোস্টিং</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">ভাউচারের ধরন <span class="text-danger">*</span></label>
                            <select name="voucher_type" class="form-select" required>
                                <option value="payment">পেমেন্ট ভাউচার (Payment - PV)</option>
                                <option value="receipt">রিসিট ভাউচার (Receipt - RV)</option>
                                <option value="journal">জার্নাল ভাউচার (Journal - JV)</option>
                                <option value="contra">কন্ট্রা ভাউচার (Contra - CV)</option>
                                <option value="adjustment">সমন্বয় ভাউচার (Adjustment - AV)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="voucher_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">প্রাপক / প্রদানকারী (Payee/Payer) <span class="text-danger">*</span></label>
                            <input type="text" name="payee_or_payer" class="form-control" placeholder="নাম লিখুন" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">ক্যাশ/ব্যাংক একাউন্ট (প্রযোজ্য ক্ষেত্রে)</label>
                            <select name="bank_account_id" class="form-select">
                                <option value="">-- একাউন্ট নির্বাচন --</option>
                                @foreach($bankAccounts as $ba)
                                    <option value="{{ $ba->id }}">{{ $ba->account_name }} (৳ {{ number_format($ba->current_balance, 2) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">পেমেন্ট মাধ্যম</label>
                            <select name="payment_method" class="form-select">
                                <option value="cash">নগদ ক্যাশ (Cash)</option>
                                <option value="bank_transfer">ব্যাংক ট্রান্সফার (Bank Transfer)</option>
                                <option value="cheque">চেক (Cheque)</option>
                                <option value="mfs">বিকাশ / নগদ (MFS)</option>
                            </select>
                        </div>
                        <div class="col-md-9">
                            <label class="form-label fw-bold">সার্বিক বিবরণ / রেফারেন্স</label>
                            <input type="text" name="description" class="form-control" placeholder="ভাউচারের কারণ বা রেফারেন্স নোট...">
                        </div>
                    </div>

                    <!-- Double Entry Rows Table -->
                    <div class="border rounded-4 p-3 bg-light mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0 text-dark"><i class="fa fa-balance-scale text-primary me-2"></i> ডাবল এন্ট্রি লেজার পোস্টিং (Debit ও Credit সর্বদা সমান হতে হবে)</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" id="addEntryRowBtn">
                                <i class="fa fa-plus me-1"></i> আরো একটি সারি যোগ করুন
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered bg-white align-middle mb-0" id="entryRowsTable">
                                <thead class="bg-light">
                                    <tr class="small text-muted">
                                        <th style="width: 35%;">লেজার অ্যাকাউন্ট <span class="text-danger">*</span></th>
                                        <th style="width: 25%;">ডেবিট (Debit ৳)</th>
                                        <th style="width: 25%;">ক্রেডিট (Credit ৳)</th>
                                        <th style="width: 15%;" class="text-center">মুছুন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="account_ids[]" class="form-select form-select-sm" required>
                                                <option value="">-- অ্যাকাউন্ট নির্বাচন করুন --</option>
                                                @foreach($accounts as $acc)
                                                    <option value="{{ $acc->id }}">[{{ $acc->account_code }}] {{ $acc->account_name }} ({{ strtoupper($acc->root_type) }})</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="debits[]" class="form-control form-control-sm debit-input" value="0.00" min="0">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="credits[]" class="form-control form-control-sm credit-input" value="0.00" min="0">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-light text-danger remove-row-btn"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="account_ids[]" class="form-select form-select-sm" required>
                                                <option value="">-- অ্যাকাউন্ট নির্বাচন করুন --</option>
                                                @foreach($accounts as $acc)
                                                    <option value="{{ $acc->id }}">[{{ $acc->account_code }}] {{ $acc->account_name }} ({{ strtoupper($acc->root_type) }})</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="debits[]" class="form-control form-control-sm debit-input" value="0.00" min="0">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="credits[]" class="form-control form-control-sm credit-input" value="0.00" min="0">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-light text-danger remove-row-btn"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold bg-light">
                                        <td class="text-end">মোট যোগফল (Total):</td>
                                        <td class="text-primary font-size-15" id="totalDebitDisplay">৳ 0.00</td>
                                        <td class="text-success font-size-15" id="totalCreditDisplay">৳ 0.00</td>
                                        <td class="text-center font-size-12" id="balanceStatusBadge">
                                            <span class="badge bg-danger">অসামঞ্জস্য (Unbalanced)</span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success px-4 fw-bold" id="submitVoucherBtn">
                        <i class="fa fa-check-circle me-1"></i> ভাউচার পোস্ট করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.querySelector('#entryRowsTable tbody');
    const addRowBtn = document.getElementById('addEntryRowBtn');
    const totalDebitEl = document.getElementById('totalDebitDisplay');
    const totalCreditEl = document.getElementById('totalCreditDisplay');
    const statusBadge = document.getElementById('balanceStatusBadge');
    const submitBtn = document.getElementById('submitVoucherBtn');

    function calculateTotals() {
        let debits = 0;
        let credits = 0;

        document.querySelectorAll('.debit-input').forEach(function(inp) {
            debits += parseFloat(inp.value) || 0;
        });

        document.querySelectorAll('.credit-input').forEach(function(inp) {
            credits += parseFloat(inp.value) || 0;
        });

        totalDebitEl.textContent = '৳ ' + debits.toFixed(2);
        totalCreditEl.textContent = '৳ ' + credits.toFixed(2);

        if (debits > 0 && Math.abs(debits - credits) < 0.01) {
            statusBadge.innerHTML = '<span class="badge bg-success"><i class="fa fa-check me-1"></i> ব্যালেন্সড</span>';
            submitBtn.disabled = false;
        } else {
            statusBadge.innerHTML = '<span class="badge bg-danger">অসামঞ্জস্যপূর্ণ (পার্থক্য: ৳' + Math.abs(debits - credits).toFixed(2) + ')</span>';
            submitBtn.disabled = true;
        }
    }

    tableBody.addEventListener('input', calculateTotals);

    tableBody.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row-btn')) {
            const rows = tableBody.querySelectorAll('tr');
            if (rows.length > 2) {
                e.target.closest('tr').remove();
                calculateTotals();
            } else {
                alert('ন্যূনতম দুইটি এন্ট্রি সারি থাকতে হবে!');
            }
        }
    });

    addRowBtn.addEventListener('click', function() {
        const firstRow = tableBody.querySelector('tr');
        const clone = firstRow.cloneNode(true);
        clone.querySelectorAll('input').forEach(function(inp) {
            inp.value = '0.00';
        });
        tableBody.appendChild(clone);
        calculateTotals();
    });

    calculateTotals();
});
</script>
@endsection

