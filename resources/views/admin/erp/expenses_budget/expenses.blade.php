@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-credit-card text-danger me-2"></i> অফিস ও দৈনন্দিন পরিচালন ব্যয় ব্যবস্থাপনা (Expense Management)</h4>
                    <p class="text-muted small mb-0">অফিসের আপ্যায়ন, স্টেশনারি, ইউটিলিটি, টিএ/ডিএ, ভ্রমণ ও লজিস্টিক ব্যয়ের ডিজিটাল এন্ট্রি এবং বাজেট ট্র্যাকিং।</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-danger rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                        <i class="fa fa-plus me-1"></i> নতুন ব্যয় এন্ট্রি
                    </button>
                    <a href="{{ route('admin.erp.budgets') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="fa fa-pie-chart me-1"></i> বিভাগভিত্তিক বাজেট
                    </a>
                </div>
            </div>

            <!-- KPI Metric Badges -->
            <div class="row g-3 mb-4">
                <div class="col-md-4 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-primary">
                        <small class="text-muted text-uppercase fw-bold font-size-11">মোট ব্যয় এন্ট্রি</small>
                        <h4 class="fw-bold text-dark my-1">{{ number_format($totalExpensesCount) }} টি</h4>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-danger">
                        <small class="text-muted text-uppercase fw-bold font-size-11">অনুমোদিত সর্বমোট ব্যয়</small>
                        <h4 class="fw-bold text-danger my-1">৳ {{ number_format($totalExpenseAmount, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-warning">
                        <small class="text-muted text-uppercase fw-bold font-size-11">পেন্ডিং অনুমোদনের অপেক্ষায়</small>
                        <h4 class="fw-bold text-warning my-1">৳ {{ number_format($pendingApprovalAmount, 2) }}</h4>
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

            <!-- Expenses Filter Form -->
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
                <form action="{{ route('admin.erp.expenses') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <select name="category_id" class="form-select form-select-sm">
                            <option value="">-- সকল ব্যয়ের খাত (Categories) --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="approval_status" class="form-select form-select-sm">
                            <option value="">-- সকল অবস্থা --</option>
                            <option value="approved" {{ request('approval_status') == 'approved' ? 'selected' : '' }}>অনুমোদিত (Approved)</option>
                            <option value="pending" {{ request('approval_status') == 'pending' ? 'selected' : '' }}>অপেক্ষমান (Pending)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-sm btn-dark w-100 rounded-3"><i class="fa fa-filter me-1"></i> ফিল্টার</button>
                    </div>
                </form>
            </div>

            <!-- Expenses Table -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-muted small text-uppercase">
                                <th class="ps-4">ভাউচার নম্বর ও তারিখ</th>
                                <th>ব্যয়ের খাত (Category)</th>
                                <th>প্রাপক (Payee)</th>
                                <th>পরিমাণ (Amount)</th>
                                <th>পরিশোধের মাধ্যম (Account)</th>
                                <th>বিবরণ</th>
                                <th>অবস্থা</th>
                                <th class="pe-4 text-end">রিসিট</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $exp)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark font-monospace">{{ $exp->expense_number }}</div>
                                        <small class="text-muted">{{ $exp->expense_date ? \Carbon\Carbon::parse($exp->expense_date)->format('d M, Y') : '-' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $exp->category->name ?? 'অফিস ব্যয়' }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark font-size-13">{{ $exp->payee }}</div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-danger font-size-15">৳ {{ number_format($exp->amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-light text-info font-size-12">
                                            {{ $exp->cashBankAccount->account_name ?? 'ক্যাশ একাউন্ট' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted font-size-12">{{ Str::limit($exp->description, 30) }}</span>
                                    </td>
                                    <td>
                                        @if($exp->approval_status == 'approved')
                                            <span class="badge bg-success font-size-11"><i class="fa fa-check me-1"></i> অনুমোদিত</span>
                                        @else
                                            <span class="badge bg-warning text-dark font-size-11"><i class="fa fa-clock-o me-1"></i> অপেক্ষমান</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-end">
                                        @if($exp->voucher_slip_file)
                                            <a href="{{ asset($exp->voucher_slip_file) }}" target="_blank" class="btn btn-sm btn-light border rounded-pill px-3">
                                                <i class="fa fa-paperclip me-1"></i> স্লিপ
                                            </a>
                                        @else
                                            <span class="text-muted font-size-12">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fa fa-credit-card fa-3x mb-3 text-secondary opacity-50"></i>
                                        <p class="mb-0">কোনো খরচ রেকর্ড পাওয়া যায়নি।</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($expenses->hasPages())
                    <div class="p-3 border-top">
                        {{ $expenses->links() }}
                    </div>
                @endif
            </div>

        </section>
    </div>
</div>

<!-- Modal: Add New Expense -->
<div class="modal fade" id="addExpenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <form action="{{ route('admin.erp.expenses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title fw-bold"><i class="fa fa-credit-card text-danger me-2"></i> নতুন অফিস ব্যয় এন্ট্রি</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ব্যয়ের খাত (Category) <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- ব্যয়ের খাত নির্বাচন করুন --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">প্রাপক / ব্যক্তি / প্রতিষ্ঠানের নাম <span class="text-danger">*</span></label>
                            <input type="text" name="payee" class="form-control" placeholder="যেমন: করিম ব্রাদার্স" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ব্যয়ের তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="expense_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">টাকার পরিমাণ (৳) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">পরিশোধের একাউন্ট (Cash / Bank) <span class="text-danger">*</span></label>
                            <select name="cash_bank_account_id" class="form-select" required>
                                <option value="">-- একাউন্ট নির্বাচন করুন --</option>
                                @foreach($bankAccounts as $ba)
                                    <option value="{{ $ba->id }}">
                                        {{ $ba->account_name }} ({{ strtoupper($ba->account_type) }}) - বর্তমান: ৳ {{ number_format($ba->current_balance, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">পেমেন্ট মেথড</label>
                            <select name="payment_method" class="form-select">
                                <option value="cash">নগদ ক্যাশ (Cash)</option>
                                <option value="bank_transfer">ব্যাংক ট্রান্সফার (Bank)</option>
                                <option value="cheque">চেক (Cheque)</option>
                                <option value="mfs">বিকাশ / নগদ (MFS)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ভাউচার / স্লিপ ফাইল (PDF/Image)</label>
                            <input type="file" name="slip_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">বিল / মেমো রেফারেন্স নং</label>
                            <input type="text" name="reference_no" class="form-control" placeholder="REF-8945">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">ব্যয়ের বিবরণ ও কারণ</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="খরচের উদ্দেশ্য বিস্তারিত লিখুন..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-danger px-4 fw-bold">ব্যয় নিশ্চিত ও লেজারে পোস্টিং</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

