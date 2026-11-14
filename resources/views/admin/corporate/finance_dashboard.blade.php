@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-university text-primary me-2"></i> সমন্বিত সেন্ট্রাল ফাইন্যান্সিয়াল ম্যানেজমেন্ট (Central Financial Management)</h4>
                    <p class="text-muted small mb-0">সকল ৫টি একাউন্টস শ্রেণির সারসংক্ষেপ, কেন্দ্রীয় আয়-ব্যয় লেজার ও সামগ্রিক লাভ-লোকসান (Overall Profit & Loss)।</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#newEntryModal">
                        <i class="fa fa-plus me-1"></i> নতুন আয়/ব্যয় এন্ট্রি
                    </button>
                    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                        <i class="fa fa-print me-1"></i> রিপোর্ট প্রিন্ট
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- 1. UNIFIED ACCOUNT OVERVIEW (সমন্বিত অ্যাকাউন্টস ওভারভিউ - Section 4.3) -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fa fa-sitemap text-success me-2"></i> সমন্বিত অ্যাকাউন্টস ওভারভিউ (Unified Account Overview)</h5>
                
                <div class="row g-3 text-center">
                    <!-- 1. SME Account -->
                    <div class="col-sm-6 col-lg">
                        <div class="p-3 rounded-4 border bg-light h-100">
                            <span class="badge bg-secondary font-size-11 mb-2">০১. সাধারণ সদস্য</span>
                            <h6 class="fw-bold text-dark mb-1">SME Account</h6>
                            <h4 class="fw-bold text-success mb-0">{{ $smeUsersCount }}</h4>
                            <small class="text-muted">নিবন্ধিত গ্রাহক</small>
                        </div>
                    </div>

                    <!-- 2. SME Gold Account -->
                    <div class="col-sm-6 col-lg">
                        <div class="p-3 rounded-4 border bg-light h-100">
                            <span class="badge bg-warning text-dark font-size-11 mb-2">০২. গোল্ড সদস্য</span>
                            <h6 class="fw-bold text-dark mb-1">SME Gold Account</h6>
                            <h4 class="fw-bold text-warning mb-0">{{ $goldUsersCount }}</h4>
                            <small class="text-muted">গোল্ডেন মেম্বার</small>
                        </div>
                    </div>

                    <!-- 3. Property Account -->
                    <div class="col-sm-6 col-lg">
                        <div class="p-3 rounded-4 border bg-light h-100">
                            <span class="badge bg-info text-white font-size-11 mb-2">০৩. প্রপার্টি ইনভেস্টর</span>
                            <h6 class="fw-bold text-dark mb-1">Property Account</h6>
                            <h4 class="fw-bold text-primary mb-0">{{ $propertyUsersCount }}</h4>
                            <small class="text-muted">প্রকল্প বিনিয়োগকারী</small>
                        </div>
                    </div>

                    <!-- 4. Shareholder Account -->
                    <div class="col-sm-6 col-lg">
                        <div class="p-3 rounded-4 border bg-light h-100">
                            <span class="badge bg-purple-subtle text-purple font-size-11 mb-2" style="background: rgba(147, 51, 234, 0.1); color: #9333ea;">০৪. শেয়ারহোল্ডার</span>
                            <h6 class="fw-bold text-dark mb-1">Shareholder Account</h6>
                            <h4 class="fw-bold text-dark mb-0" style="color: #9333ea !important;">{{ $shareholdersCount }}</h4>
                            <small class="text-muted">মূল শেয়ার অংশীদার</small>
                        </div>
                    </div>

                    <!-- 5. Partnership Account -->
                    <div class="col-sm-6 col-lg">
                        <div class="p-3 rounded-4 border bg-light h-100">
                            <span class="badge bg-success font-size-11 mb-2">০৫. পার্টনারশিপ</span>
                            <h6 class="fw-bold text-dark mb-1">Partnership Account</h6>
                            <h4 class="fw-bold text-success mb-0">{{ $partnershipsCount }}</h4>
                            <small class="text-muted">ব্যবসায়িক পার্টনার</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. CENTRAL FINANCIAL SUMMARY (Overall P&L) -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-white" style="background: linear-gradient(135deg, #065f46 0%, #047857 100%);">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-light opacity-90">মোট রাজস্ব ও সংগৃহীত তহবিল</span>
                            <i class="fa fa-arrow-circle-down fa-2x text-warning"></i>
                        </div>
                        <h3 class="fw-bold text-white mb-1">{{ number_format($totalIncome, 2) }} ৳</h3>
                        <small class="text-light opacity-75">বিক্রয়, সার্ভিস, মূলধন ও কালেকশন</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-white" style="background: linear-gradient(135deg, #991b1b 0%, #dc2626 100%);">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-light opacity-90">মোট ব্যয় ও পরিশোধ</span>
                            <i class="fa fa-arrow-circle-up fa-2x text-light"></i>
                        </div>
                        <h3 class="fw-bold text-white mb-1">{{ number_format($totalExpense, 2) }} ৳</h3>
                        <small class="text-light opacity-75">প্রকল্প ব্যয়, বেতন, অফিস খরচ ও উত্তোলন</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-white" style="background: {{ $netProfitOrLoss >= 0 ? 'linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%)' : 'linear-gradient(135deg, #7f1d1d, #b91c1c)' }};">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-light opacity-90">সার্বিক লাভ-লোকসান (Overall P&L)</span>
                            <i class="fa fa-balance-scale fa-2x text-warning"></i>
                        </div>
                        <h3 class="fw-bold text-white mb-1">{{ number_format($netProfitOrLoss, 2) }} ৳</h3>
                        <small class="text-light opacity-75">{{ $netProfitOrLoss >= 0 ? 'নিট উদ্বৃত্ত ও মুনাফা' : 'ঘাটতি' }}</small>
                    </div>
                </div>
            </div>

            <!-- 3. Category Breakdown & Recent Ledger -->
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <h6 class="fw-bold text-dark mb-3"><i class="fa fa-plus-circle text-success me-2"></i> আয়ের প্রধান খাতসমূহ (Income by Category)</h6>
                        <ul class="list-group list-group-flush small">
                            @forelse($incomeByCategory as $cat => $sum)
                            <li class="list-group-item d-flex justify-content-between px-0 py-2">
                                <span class="text-muted text-capitalize">{{ str_replace('_', ' ', $cat) }}</span>
                                <strong class="text-success font-size-14">{{ number_format($sum, 2) }} ৳</strong>
                            </li>
                            @empty
                            <li class="list-group-item px-0 text-muted">কোনো আয়ের রেকর্ড নেই।</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <h6 class="fw-bold text-dark mb-3"><i class="fa fa-minus-circle text-danger me-2"></i> ব্যয়ের প্রধান খাতসমূহ (Expenses by Category)</h6>
                        <ul class="list-group list-group-flush small">
                            @forelse($expenseByCategory as $cat => $sum)
                            <li class="list-group-item d-flex justify-content-between px-0 py-2">
                                <span class="text-muted text-capitalize">{{ str_replace('_', ' ', $cat) }}</span>
                                <strong class="text-danger font-size-14">{{ number_format($sum, 2) }} ৳</strong>
                            </li>
                            @empty
                            <li class="list-group-item px-0 text-muted">কোনো ব্যয়ের রেকর্ড নেই।</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Recent Ledger Transactions -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="fa fa-book text-primary me-2"></i> সাম্প্রতিক ফাইন্যান্সিয়াল লেজার ভাউচার</h5>
                    <button class="btn btn-primary btn-xs rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#newEntryModal">
                        নতুন ভাউচার
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ভাউচার কোড</th>
                                <th>ধরন</th>
                                <th>খাত / ক্যাটাগরি</th>
                                <th>পরিমাণ (টাকা)</th>
                                <th>তারিখ</th>
                                <th>পেমেন্ট মেথড</th>
                                <th>বিবরণ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $tx)
                            <tr>
                                <td class="fw-bold font-monospace">{{ $tx->transaction_code }}</td>
                                <td>
                                    <span class="badge {{ $tx->entry_type === 'income' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $tx->entry_type === 'income' ? 'আয় (Income)' : 'ব্যয় (Expense)' }}
                                    </span>
                                </td>
                                <td class="text-capitalize">{{ str_replace('_', ' ', $tx->category) }}</td>
                                <td class="fw-bold {{ $tx->entry_type === 'income' ? 'text-success' : 'text-danger' }} font-size-14">
                                    {{ $tx->entry_type === 'income' ? '+' : '-' }}{{ number_format($tx->amount, 2) }} ৳
                                </td>
                                <td>{{ \Carbon\Carbon::parse($tx->entry_date)->format('d/m/Y') }}</td>
                                <td class="text-uppercase">{{ $tx->payment_method }}</td>
                                <td class="text-muted">{{ $tx->description }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">কোনো লেনদেন পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
    </div>
</div>

<!-- New Financial Voucher Modal -->
<div class="modal fade" id="newEntryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">নতুন আর্থিক ভাউচার এন্ট্রি</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.corporate.finance.entry') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">লেনদেনের ধরন *</label>
                        <select name="entry_type" class="form-select" required>
                            <option value="income">আয় / কালেকশন (Income)</option>
                            <option value="expense">ব্যয় / পরিশোধ (Expense)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">খাত / ক্যাটাগরি *</label>
                        <select name="category" class="form-select" required>
                            <optgroup label="আয়ের খাতসমূহ">
                                <option value="product_sales">পণ্য বিক্রয় আয় (Product Sales)</option>
                                <option value="service">সার্ভিস আয় (Service Income)</option>
                                <option value="membership_fee">মেম্বারশিপ ফি কালেকশন</option>
                                <option value="investment_collection">বিনিয়োগ মূলধন কালেকশন</option>
                                <option value="share_capital">শেয়ার মূলধন কালেকশন</option>
                            </optgroup>
                            <optgroup label="ব্যয়ের খাতসমূহ">
                                <option value="project_expense">প্রকল্প বাস্তবায়ন খরচ (Project Expense)</option>
                                <option value="employee_salary">কর্মচারী বেতন ভাতা (Salary Expense)</option>
                                <option value="office_admin_expense">অফিস ও প্রশাসনিক ব্যয়</option>
                                <option value="customer_withdrawal">গ্রাহক উত্তোলন পরিশোধ</option>
                                <option value="profit_payout">লভ্যাংশ বিতরণ</option>
                                <option value="other">অন্যান্য ব্যয়</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">পরিমাণ (টাকা) *</label>
                        <input type="number" name="amount" class="form-control" placeholder="যেমন: ৫০০০০" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">তারিখ *</label>
                        <input type="date" name="entry_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">পেমেন্ট মেথড</label>
                        <select name="payment_method" class="form-select">
                            <option value="bank_transfer">ব্যাংক ট্রান্সফার (Bank Transfer)</option>
                            <option value="cash">নগদ ক্যাশ (Cash)</option>
                            <option value="bkash">বিকাশ (bKash)</option>
                            <option value="nagad">নগদ (Nagad)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">বিবরণ / নোট</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="ভাউচারের বিস্তারিত বিবরণ..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

