@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="ti-money text-info me-2"></i>ব্রাঞ্চ খরচ ও পরিচালন ব্যয় (Branch Operating Expenses)
                    </h3>
                    <p class="text-muted mb-0">শাখাভিত্তিক ভাড়া, বিদ্যুৎ বিল, বেতন, প্যাকেজিং ও স্টোর মেইনটেন্যান্স খরচ ট্র্যাকিং</p>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-info fw-bold text-white" data-bs-toggle="modal" data-bs-target="#createExpenseModal">
                        <i class="ti-plus me-1"></i> নতুন খরচ যুক্ত করুন
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Filter Bar & Summary -->
            <div class="box shadow-sm mb-3">
                <div class="box-body py-2">
                    <form method="GET" action="{{ route('admin.supershop.expenses') }}" class="row g-2 align-items-center">
                        <div class="col-md-3 col-6">
                            <select name="branch_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">🏢 সকল ব্রাঞ্চ</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}" {{ $selectedBranchId == $b->id ? 'selected' : '' }}>
                                        {{ $b->code }} - {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 col-6">
                            <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">🏷️ সকল খরচের খাত</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $selectedCatId == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 col-12 text-md-end">
                            <span class="badge bg-danger-light text-danger fs-6 px-3 py-2">
                                সর্বমোট খরচ: <strong>৳ {{ number_format($totalExpenseAmount, 2) }}</strong>
                            </span>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Expenses Table -->
            <div class="box shadow-sm">
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>খরচের বিবরণ / টাইটেল</th>
                                    <th>ব্রাঞ্চ</th>
                                    <th>খরচের খাত (Category)</th>
                                    <th class="text-end">টাকার পরিমাণ</th>
                                    <th class="text-center">পেমেন্ট মেথড</th>
                                    <th>তারিখ</th>
                                    <th>রেকর্ডকারী</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenses as $exp)
                                    <tr>
                                        <td>
                                            <strong class="text-dark">{{ $exp->title }}</strong>
                                            @if($exp->notes)
                                                <div class="small text-muted">{{ $exp->notes }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-dark">{{ $exp->branch ? $exp->branch->code : '' }}</span>
                                            <span class="small ms-1">{{ $exp->branch ? $exp->branch->name : '' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $exp->category ? $exp->category->name : 'General' }}</span>
                                        </td>
                                        <td class="text-end fw-bold text-danger">
                                            ৳ {{ number_format($exp->amount, 2) }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-light text-info text-uppercase">{{ $exp->payment_method ?: 'cash' }}</span>
                                        </td>
                                        <td class="small text-muted">{{ $exp->expense_date }}</td>
                                        <td class="small">{{ $exp->creator ? $exp->creator->name : 'Admin' }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.supershop.expenses.destroy', $exp->id) }}" class="btn btn-xs btn-outline-danger" onclick="return confirm('আপনি কি এই খরচের এন্ট্রি মুছে ফেলতে চান?')" title="মুছুন">
                                                <i class="ti-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">কোনো খরচের এন্ট্রি পাওয়া যায়নি।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($expenses->hasPages())
                    <div class="box-footer">
                        {{ $expenses->links() }}
                    </div>
                @endif
            </div>
        </section>

        <!-- Create Expense Modal -->
        <div class="modal fade" id="createExpenseModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('admin.supershop.expenses.store') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-info text-white">
                            <h5 class="modal-title fw-bold text-white"><i class="ti-plus me-1"></i> নতুন ব্রাঞ্চ খরচ এন্ট্রি</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">ব্রাঞ্চ নির্বাচন <span class="text-danger">*</span></label>
                                <select name="branch_id" class="form-select" required>
                                    @foreach($branches as $b)
                                        <option value="{{ $b->id }}">{{ $b->code }} - {{ $b->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">খরচের খাত (Category) <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">খরচের শিরোনাম / বিবরণ <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="যেমন: মে মাসের দোকান ভাড়া, বিদ্যুৎ বিল" required>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-bold">টাকার পরিমাণ <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="amount" class="form-control fw-bold" placeholder="0.00" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold">খরচের তারিখ <span class="text-danger">*</span></label>
                                    <input type="date" name="expense_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">পেমেন্ট মেথড</label>
                                <select name="payment_method" class="form-select">
                                    <option value="cash">💵 ক্যাশ (Cash)</option>
                                    <option value="bank">🏦 ব্যাংক ট্রান্সফার (Bank)</option>
                                    <option value="bkash">📱 বিকাশ (bKash)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">অতিরিক্ত নোট / ভাউচার রেফারেন্স</label>
                                <input type="text" name="notes" class="form-control" placeholder="যেমন: ভাউচার #VN-491">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                            <button type="submit" class="btn btn-info fw-bold text-white">খরচ সংরক্ষণ করুন</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

