@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-file-text-o text-primary me-2"></i> ক্রয় ও অফিস খরচ বিল ব্যবস্থাপনা (Bills & Invoices)</h4>
                    <p class="text-muted small mb-0">ভেন্ডর বিল, সাপ্লায়ার বিল, অফিস ভাড়া, বিদ্যুৎ ও ইন্টারনেট বিল দাখিল, অনুমোদন এবং ব্যাংক বা ক্যাশ থেকে পরিশোধ।</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addBillModal">
                        <i class="fa fa-plus me-1"></i> নতুন বিল দাখিল
                    </button>
                    <a href="{{ route('admin.erp.vouchers') }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                        <i class="fa fa-money me-1"></i> ভাউচার সমূহ
                    </a>
                </div>
            </div>

            <!-- KPI Metric Badges -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-primary">
                        <small class="text-muted text-uppercase fw-bold font-size-11">মোট বিল সংখ্যা</small>
                        <h4 class="fw-bold text-dark my-1">{{ number_format($totalBillsCount) }} টি</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-info">
                        <small class="text-muted text-uppercase fw-bold font-size-11">মোট বিলের পরিমাণ</small>
                        <h4 class="fw-bold text-info my-1">৳ {{ number_format($totalBillAmount, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-success">
                        <small class="text-muted text-uppercase fw-bold font-size-11">পরিশোধিত অর্থ</small>
                        <h4 class="fw-bold text-success my-1">৳ {{ number_format($totalPaidAmount, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-danger">
                        <small class="text-muted text-uppercase fw-bold font-size-11">মোট বকেয়া বিল</small>
                        <h4 class="fw-bold text-danger my-1">৳ {{ number_format($totalDueAmount, 2) }}</h4>
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

            <!-- Bills Filter Form -->
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
                <form action="{{ route('admin.erp.billing.bills') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <select name="bill_type" class="form-select form-select-sm">
                            <option value="">-- সকল বিলের ধরন --</option>
                            <option value="purchase" {{ request('bill_type') == 'purchase' ? 'selected' : '' }}>পণ্য ক্রয় বিল (Purchase Bill)</option>
                            <option value="utility" {{ request('bill_type') == 'utility' ? 'selected' : '' }}>ইউটিলিটি ও বিদ্যুৎ (Utility)</option>
                            <option value="office_expense" {{ request('bill_type') == 'office_expense' ? 'selected' : '' }}>অফিস খরচ (Office Expense)</option>
                            <option value="repair" {{ request('bill_type') == 'repair' ? 'selected' : '' }}>মেরামত ও সার্ভিসিং (Repair & Maintenance)</option>
                            <option value="rent" {{ request('bill_type') == 'rent' ? 'selected' : '' }}>অফিস ভাড়া (Office Rent)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="payment_status" class="form-select form-select-sm">
                            <option value="">-- পরিশোধের অবস্থা --</option>
                            <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>বকেয়া (Unpaid)</option>
                            <option value="partially_paid" {{ request('payment_status') == 'partially_paid' ? 'selected' : '' }}>আংশিক পরিশোধ (Partially Paid)</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>সম্পূর্ণ পরিশোধিত (Paid)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="approval_status" class="form-select form-select-sm">
                            <option value="">-- অনুমোদনের অবস্থা --</option>
                            <option value="pending_approval" {{ request('approval_status') == 'pending_approval' ? 'selected' : '' }}>অনুমোদনের অপেক্ষায় (Pending)</option>
                            <option value="approved" {{ request('approval_status') == 'approved' ? 'selected' : '' }}>অনুমোদিত (Approved)</option>
                            <option value="rejected" {{ request('approval_status') == 'rejected' ? 'selected' : '' }}>বাতিল (Rejected)</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-sm btn-dark w-100 rounded-3"><i class="fa fa-filter me-1"></i> ফিল্টার</button>
                    </div>
                </form>
            </div>

            <!-- Bills Table -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-muted small text-uppercase">
                                <th class="ps-4">বিল নম্বর ও তারিখ</th>
                                <th>ভেন্ডর / প্রাপক</th>
                                <th>বিলের ধরন</th>
                                <th>মোট পরিমাণ</th>
                                <th>পরিশোধিত</th>
                                <th>বকেয়া</th>
                                <th>পেমেন্ট অবস্থা</th>
                                <th>অনুমোদন</th>
                                <th class="pe-4 text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bills as $bill)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $bill->bill_number }}</div>
                                        <small class="text-muted">{{ $bill->bill_date ? \Carbon\Carbon::parse($bill->bill_date)->format('d M, Y') : '-' }}</small>
                                        @if($bill->reference_no)
                                            <span class="badge bg-light text-muted border font-size-10 ms-1">{{ $bill->reference_no }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark font-size-13">{{ $bill->vendor_name }}</div>
                                        <small class="text-muted">{{ $bill->department_name ?? 'সাধারণ বিভাগ' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ strtoupper(str_replace('_', ' ', $bill->bill_type)) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark font-size-14">৳ {{ number_format($bill->total_amount, 2) }}</span>
                                        @if($bill->vat_tax > 0)
                                            <div class="font-size-10 text-muted">ভ্যাট: ৳{{ number_format($bill->vat_tax, 2) }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">৳ {{ number_format($bill->paid_amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold {{ $bill->due_amount > 0 ? 'text-danger' : 'text-muted' }}">
                                            ৳ {{ number_format($bill->due_amount, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($bill->payment_status == 'paid')
                                            <span class="badge bg-success-light text-success"><i class="fa fa-check me-1"></i> পেইড</span>
                                        @elseif($bill->payment_status == 'partially_paid')
                                            <span class="badge bg-warning-light text-warning"><i class="fa fa-clock-o me-1"></i> আংশিক</span>
                                        @else
                                            <span class="badge bg-danger-light text-danger"><i class="fa fa-exclamation-circle me-1"></i> অপরিশোধিত</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($bill->approval_status == 'approved')
                                            <span class="badge bg-success font-size-11"><i class="fa fa-check-circle me-1"></i> অনুমোদিত</span>
                                        @elseif($bill->approval_status == 'rejected')
                                            <span class="badge bg-danger font-size-11"><i class="fa fa-times-circle me-1"></i> বাতিল</span>
                                        @else
                                            <span class="badge bg-warning text-dark font-size-11"><i class="fa fa-hourglass-half me-1"></i> পেন্ডিং</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="btn-group">
                                            @if($bill->approval_status == 'pending_approval')
                                                <form action="{{ route('admin.erp.billing.bills.approve', $bill->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3" title="বিল অনুমোদন করুন">
                                                        <i class="fa fa-check me-1"></i> অনুমোদন
                                                    </button>
                                                </form>
                                            @endif

                                            @if($bill->due_amount > 0)
                                                <button class="btn btn-sm btn-primary rounded-pill px-3 ms-1" data-bs-toggle="modal" data-bs-target="#payBillModal{{ $bill->id }}">
                                                    <i class="fa fa-credit-card me-1"></i> বিল পরিশোধ
                                                </button>
                                            @endif

                                            @if($bill->attachment)
                                                <a href="{{ asset($bill->attachment) }}" target="_blank" class="btn btn-sm btn-light border rounded-pill px-2 ms-1" title="সংযুক্ত ভাউচার/রিসিট">
                                                    <i class="fa fa-paperclip"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal: Pay Bill -->
                                @if($bill->due_amount > 0)
                                <div class="modal fade" id="payBillModal{{ $bill->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0">
                                            <form action="{{ route('admin.erp.billing.bills.pay', $bill->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header border-0 bg-light">
                                                    <h5 class="modal-title fw-bold"><i class="fa fa-credit-card text-primary me-2"></i> বিল পরিশোধ ({{ $bill->bill_number }})</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="alert alert-info py-2 rounded-3 mb-3">
                                                        <div class="d-flex justify-content-between">
                                                            <span>প্রাপক: <strong>{{ $bill->vendor_name }}</strong></span>
                                                            <span>বকেয়া: <strong class="text-danger">৳ {{ number_format($bill->due_amount, 2) }}</strong></span>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">পরিশোধের পরিমাণ (৳) <span class="text-danger">*</span></label>
                                                        <input type="number" step="0.01" name="pay_amount" class="form-control" value="{{ $bill->due_amount }}" max="{{ $bill->due_amount }}" min="1" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">পরিশোধের মাধ্যম (Cash / Bank Account) <span class="text-danger">*</span></label>
                                                        <select name="bank_account_id" class="form-select" required>
                                                            <option value="">-- একাউন্ট নির্বাচন করুন --</option>
                                                            @foreach($bankAccounts as $ba)
                                                                <option value="{{ $ba->id }}">
                                                                    {{ $ba->account_name }} ({{ strtoupper($ba->account_type) }}) - বর্তমান ব্যালেন্স: ৳ {{ number_format($ba->current_balance, 2) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">পেমেন্ট মেথড</label>
                                                        <select name="payment_method" class="form-select">
                                                            <option value="bank_transfer">ব্যাংক ট্রান্সফার / EFT</option>
                                                            <option value="cheque">চেক (Cheque)</option>
                                                            <option value="cash">নগদ ক্যাশ (Cash)</option>
                                                            <option value="mfs">বিকাশ / নগদ (MFS)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                                                    <button type="submit" class="btn btn-primary px-4 fw-bold">পেমেন্ট ও ভাউচার জেনারেট করুন</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif

                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="fa fa-file-text-o fa-3x mb-3 text-secondary opacity-50"></i>
                                        <p class="mb-0">কোনো বিল বা ইনভয়েস রেকর্ড পাওয়া যায়নি।</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($bills->hasPages())
                    <div class="p-3 border-top">
                        {{ $bills->links() }}
                    </div>
                @endif
            </div>

        </section>
    </div>
</div>

<!-- Modal: Add New Bill -->
<div class="modal fade" id="addBillModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <form action="{{ route('admin.erp.billing.bills.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title fw-bold"><i class="fa fa-file-text-o text-primary me-2"></i> নতুন বিল দাখিল এন্ট্রি</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ভেন্ডর / প্রাপকের নাম <span class="text-danger">*</span></label>
                            <input type="text" name="vendor_name" class="form-control" placeholder="যেমন: মেঘনা পেপার মিলস লি." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">বিলের ধরন <span class="text-danger">*</span></label>
                            <select name="bill_type" class="form-select" required>
                                <option value="purchase">পণ্য ক্রয় বিল (Purchase Bill)</option>
                                <option value="utility">ইউটিলিটি ও বিদ্যুৎ বিল (Utility)</option>
                                <option value="office_expense">অফিস খরচ বিল (Office Expense)</option>
                                <option value="repair">রক্ষণাবেক্ষণ ও মেরামত বিল (Repair & Maintenance)</option>
                                <option value="rent">অফিস ভাড়া বিল (Rent)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">বিভাগের নাম</label>
                            <input type="text" name="department_name" class="form-control" placeholder="যেমন: প্রশাসন বিভাগ">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">বিলের তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="bill_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">পরিশোধের শেষ তারিখ (Due Date)</label>
                            <input type="date" name="due_date" class="form-control" value="{{ date('Y-m-d', strtotime('+15 days')) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">সাবটোটাল (মূল বিল) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="subtotal" id="billSubtotal" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ছাড় / ডিসকাউন্ট (৳)</label>
                            <input type="number" step="0.01" name="discount" id="billDiscount" class="form-control" value="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ভ্যাট / ট্যাক্স (৳)</label>
                            <input type="number" step="0.01" name="vat_tax" id="billVat" class="form-control" value="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">সর্বমোট প্রদেয় বিল (৳)</label>
                            <input type="text" id="billTotalDisplay" class="form-control bg-light fw-bold text-primary font-size-16" readonly placeholder="৳ 0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">প্রাথমিক পরিশোধের পরিমাণ (৳)</label>
                            <input type="number" step="0.01" name="paid_amount" class="form-control" value="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ভেন্ডর চালান / রেফারেন্স নং</label>
                            <input type="text" name="reference_no" class="form-control" placeholder="CH-98745">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">বিল / চালানের স্ক্যান কপি (PDF/Image)</label>
                            <input type="file" name="attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">অনুমোদনের স্ট্যাটাস</label>
                            <select name="approval_status" class="form-select">
                                <option value="pending_approval">অনুমোদনের অপেক্ষায় (Pending)</option>
                                <option value="approved">অনুমোদিত (Approved)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">বিলের বিবরণ / নোট</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="বিলের বিস্তারিত বিবরণ লিখুন..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">বিল দাখিল করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sub = document.getElementById('billSubtotal');
    const disc = document.getElementById('billDiscount');
    const vat = document.getElementById('billVat');
    const totalDisplay = document.getElementById('billTotalDisplay');

    function calcTotal() {
        const s = parseFloat(sub.value) || 0;
        const d = parseFloat(disc.value) || 0;
        const v = parseFloat(vat.value) || 0;
        const tot = Math.max(0, (s - d) + v);
        totalDisplay.value = '৳ ' + tot.toFixed(2);
    }

    if (sub && disc && vat) {
        sub.addEventListener('input', calcTotal);
        disc.addEventListener('input', calcTotal);
        vat.addEventListener('input', calcTotal);
    }
});
</script>
@endsection

