@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-calculator text-primary me-2"></i> ক্যাশ মেমো ও বিক্রয় ইনভয়েস (Cash Memo / Direct Sales POS)</h4>
                    <p class="text-muted small mb-0">স্টোর ও শোরুম থেকে সরাসরি পণ্য বিক্রয়, ক্যাশ মেমো ইস্যু, স্বয়ংক্রিয় স্টক কর্তন এবং বিক্রয় রাজস্ব লেজারে সংরক্ষণ।</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addCashMemoModal">
                        <i class="fa fa-plus me-1"></i> নতুন ক্যাশ মেমো তৈরি (POS)
                    </button>
                    <a href="{{ route('admin.erp.billing.money_receipts') }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                        <i class="fa fa-ticket me-1"></i> মানি রিসিট সমূহ
                    </a>
                </div>
            </div>

            <!-- KPI Metric Badges -->
            <div class="row g-3 mb-4">
                <div class="col-md-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-primary">
                        <small class="text-muted text-uppercase fw-bold font-size-11">মোট বিক্রিত ক্যাশ মেমো</small>
                        <h4 class="fw-bold text-dark my-1">{{ number_format($totalSalesCount) }} টি</h4>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-success">
                        <small class="text-muted text-uppercase fw-bold font-size-11">সর্বমোট বিক্রয় রাজস্ব আয়</small>
                        <h4 class="fw-bold text-success my-1">৳ {{ number_format($totalSalesRevenue, 2) }}</h4>
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

            <!-- Cash Memos Table -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-muted small text-uppercase">
                                <th class="ps-4">মেমো নম্বর ও তারিখ</th>
                                <th>গ্রাহকের নাম ও ফোন</th>
                                <th>পণ্য আইটেম সংখ্যা</th>
                                <th>সাবটোটাল</th>
                                <th>ছাড় ও ভ্যাট</th>
                                <th>সর্বমোট প্রদেয়</th>
                                <th>পরিশোধিত অর্থ</th>
                                <th>জমা হওয়া ব্যাংক / ক্যাশ</th>
                                <th class="pe-4 text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($memos as $cm)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark font-monospace">{{ $cm->memo_number }}</div>
                                        <small class="text-muted">{{ $cm->memo_date ? \Carbon\Carbon::parse($cm->memo_date)->format('d M, Y') : '-' }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark font-size-13">{{ $cm->customer_name }}</div>
                                        <small class="text-muted"><i class="fa fa-phone me-1"></i>{{ $cm->customer_phone ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $cm->items->count() }} টি আইটেম</span>
                                    </td>
                                    <td>
                                        ৳ {{ number_format($cm->subtotal, 2) }}
                                    </td>
                                    <td>
                                        <small class="text-muted">ছাড়: ৳{{ number_format($cm->discount, 2) }} | ভ্যাট: ৳{{ number_format($cm->vat, 2) }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-primary font-size-15">৳ {{ number_format($cm->grand_total, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success font-size-14">৳ {{ number_format($cm->paid_amount, 2) }}</span>
                                        @if($cm->due_amount > 0)
                                            <div class="font-size-10 text-danger fw-bold">বকেয়া: ৳{{ number_format($cm->due_amount, 2) }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info-light text-info font-size-12">
                                            {{ $cm->bankAccount->account_name ?? 'ক্যাশ কাউন্টার' }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <a href="{{ route('admin.erp.billing.cash_memos.print', $cm->id) }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3" title="ক্যাশ মেমো প্রিন্ট করুন">
                                            <i class="fa fa-print me-1"></i> মেমো প্রিন্ট
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="fa fa-calculator fa-3x mb-3 text-secondary opacity-50"></i>
                                        <p class="mb-0">কোনো ক্যাশ মেমো রেকর্ড পাওয়া যায়নি।</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($memos->hasPages())
                    <div class="p-3 border-top">
                        {{ $memos->links() }}
                    </div>
                @endif
            </div>

        </section>
    </div>
</div>

<!-- Modal: Add New Cash Memo -->
<div class="modal fade" id="addCashMemoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <form action="{{ route('admin.erp.billing.cash_memos.store') }}" method="POST" id="cashMemoForm">
                @csrf
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title fw-bold"><i class="fa fa-calculator text-primary me-2"></i> বিক্রয় ক্যাশ মেমো ইস্যু (POS)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ক্রেতা / গ্রাহকের নাম <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control" placeholder="গ্রাহকের নাম" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">মোবাইল নম্বর</label>
                            <input type="text" name="customer_phone" class="form-control" placeholder="017xxxxxxxx">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">মেমো তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="memo_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ঠিকানা</label>
                            <input type="text" name="customer_address" class="form-control" placeholder="গ্রাহকের ঠিকানা">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">জমা হওয়া একাউন্ট (Cash / Bank) <span class="text-danger">*</span></label>
                            <select name="bank_account_id" class="form-select" required>
                                <option value="">-- একাউন্ট নির্বাচন করুন --</option>
                                @foreach($bankAccounts as $ba)
                                    <option value="{{ $ba->id }}">
                                        {{ $ba->account_name }} ({{ strtoupper($ba->account_type) }}) - ব্যালেন্স: ৳ {{ number_format($ba->current_balance, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Items Grid -->
                    <div class="border rounded-4 p-3 bg-light mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0 text-dark"><i class="fa fa-shopping-basket text-primary me-2"></i> পণ্য তালিকা ও স্বয়ংক্রিয় স্টক হিসাব</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" id="addMemoItemBtn">
                                <i class="fa fa-plus me-1"></i> আরো পণ্য যোগ করুন
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered bg-white align-middle mb-0" id="memoItemsTable">
                                <thead class="bg-light">
                                    <tr class="small text-muted">
                                        <th style="width: 40%;">পণ্যের নাম ও উপলব্ধ স্টক <span class="text-danger">*</span></th>
                                        <th style="width: 20%;">পরিমাণ <span class="text-danger">*</span></th>
                                        <th style="width: 20%;">একক বিক্রয় মূল্য (৳) <span class="text-danger">*</span></th>
                                        <th style="width: 15%;">মোট (৳)</th>
                                        <th style="width: 5%;" class="text-center">মুছুন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="item_ids[]" class="form-select form-select-sm memo-item-select" required>
                                                <option value="">-- পণ্য নির্বাচন করুন --</option>
                                                @foreach($items as $it)
                                                    <option value="{{ $it->id }}" data-price="{{ $it->selling_price }}" data-stock="{{ $it->current_stock }}">
                                                        {{ $it->name }} (মজুত: {{ $it->current_stock }} {{ $it->unit->name ?? '' }}) - ৳ {{ number_format($it->selling_price, 2) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="quantities[]" class="form-control form-control-sm memo-qty" value="1" min="0.01" required>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="unit_prices[]" class="form-control form-control-sm memo-price" value="0.00" min="0" required>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark memo-row-total">৳ 0.00</span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-light text-danger remove-memo-item-btn"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Bill Calculation Footer -->
                    <div class="row justify-content-end">
                        <div class="col-md-5">
                            <div class="p-3 bg-light rounded-4 border">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">সাবটোটাল (Subtotal):</span>
                                    <strong class="text-dark" id="memoSubtotalDisplay">৳ 0.00</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">ছাড় / ডিসকাউন্ট (৳):</span>
                                    <input type="number" step="0.01" name="discount" id="memoDiscount" class="form-control form-control-sm w-50 text-end" value="0.00">
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">ভ্যাট (৳):</span>
                                    <input type="number" step="0.01" name="vat" id="memoVat" class="form-control form-control-sm w-50 text-end" value="0.00">
                                </div>
                                <div class="d-flex justify-content-between border-top pt-2 mb-2">
                                    <span class="fw-bold text-dark font-size-15">সর্বমোট বিল (Grand Total):</span>
                                    <strong class="text-primary font-size-16" id="memoGrandTotalDisplay">৳ 0.00</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-success">পরিশোধ (Paid ৳):</span>
                                    <input type="number" step="0.01" name="paid_amount" id="memoPaid" class="form-control form-control-sm w-50 text-end fw-bold" value="0.00">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">
                        <i class="fa fa-check me-1"></i> মেমো সম্পন্ন ও স্টক বিক্রয়
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.querySelector('#memoItemsTable tbody');
    const addItemBtn = document.getElementById('addMemoItemBtn');
    const subtotalEl = document.getElementById('memoSubtotalDisplay');
    const grandTotalEl = document.getElementById('memoGrandTotalDisplay');
    const discountInp = document.getElementById('memoDiscount');
    const vatInp = document.getElementById('memoVat');
    const paidInp = document.getElementById('memoPaid');

    function calcMemo() {
        let subtotal = 0;
        tableBody.querySelectorAll('tr').forEach(function(row) {
            const qty = parseFloat(row.querySelector('.memo-qty').value) || 0;
            const price = parseFloat(row.querySelector('.memo-price').value) || 0;
            const total = qty * price;
            row.querySelector('.memo-row-total').textContent = '৳ ' + total.toFixed(2);
            subtotal += total;
        });

        subtotalEl.textContent = '৳ ' + subtotal.toFixed(2);
        const disc = parseFloat(discountInp.value) || 0;
        const vt = parseFloat(vatInp.value) || 0;
        const grand = Math.max(0, (subtotal - disc) + vt);
        grandTotalEl.textContent = '৳ ' + grand.toFixed(2);

        if (!paidInp.dataset.manual) {
            paidInp.value = grand.toFixed(2);
        }
    }

    paidInp.addEventListener('input', function() {
        this.dataset.manual = 'true';
    });

    tableBody.addEventListener('change', function(e) {
        if (e.target.classList.contains('memo-item-select')) {
            const opt = e.target.options[e.target.selectedIndex];
            const price = opt.getAttribute('data-price') || 0;
            const row = e.target.closest('tr');
            row.querySelector('.memo-price').value = price;
            calcMemo();
        }
    });

    tableBody.addEventListener('input', calcMemo);
    discountInp.addEventListener('input', calcMemo);
    vatInp.addEventListener('input', calcMemo);

    tableBody.addEventListener('click', function(e) {
        if (e.target.closest('.remove-memo-item-btn')) {
            const rows = tableBody.querySelectorAll('tr');
            if (rows.length > 1) {
                e.target.closest('tr').remove();
                calcMemo();
            } else {
                alert('ন্যূনতম একটি পণ্য আইটেম থাকতে হবে!');
            }
        }
    });

    addItemBtn.addEventListener('click', function() {
        const firstRow = tableBody.querySelector('tr');
        const clone = firstRow.cloneNode(true);
        clone.querySelector('.memo-qty').value = '1';
        clone.querySelector('.memo-price').value = '0.00';
        clone.querySelector('.memo-row-total').textContent = '৳ 0.00';
        tableBody.appendChild(clone);
    });
});
</script>
@endsection

