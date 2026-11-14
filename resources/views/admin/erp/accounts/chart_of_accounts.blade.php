@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-sitemap text-primary me-2"></i> চার্ট অব অ্যাকাউন্টস (Chart of Accounts - COA)</h4>
                    <p class="text-muted small mb-0">প্রতিষ্ঠানের সকল সম্পদ, দায়, মূলধন, রাজস্ব ও ব্যয়ের কেন্দ্রীয় অ্যাকাউন্টিং কোড ও শ্রেণিবিন্যাস কাঠামো।</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                        <i class="fa fa-plus me-1"></i> নতুন অ্যাকাউন্ট যুক্ত করুন
                    </button>
                    <a href="{{ route('admin.erp.accounts.general_ledger') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                        <i class="fa fa-list-alt me-1"></i> লেজার খতিয়ান
                    </a>
                    <a href="{{ route('admin.erp.accounts.trial_balance') }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                        <i class="fa fa-balance-scale me-1"></i> ট্রায়াল ব্যালেন্স
                    </a>
                    <a href="{{ route('admin.erp.accounts.profit_and_loss') }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                        <i class="fa fa-line-chart me-1"></i> লাভ-ক্ষতি (P&L)
                    </a>
                    <a href="{{ route('admin.erp.accounts.balance_sheet') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                        <i class="fa fa-pie-chart me-1"></i> ব্যালেন্স শীট
                    </a>
                </div>
            </div>

            <!-- KPI Metric Badges (5 Accounting Heads) -->
            <div class="row g-3 mb-4">
                <div class="col-xl col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-primary">
                        <small class="text-muted text-uppercase fw-bold font-size-11">সম্পদ (Assets 1xxx)</small>
                        <h4 class="fw-bold text-primary my-1">৳ {{ number_format($totalAssets, 2) }}</h4>
                    </div>
                </div>
                <div class="col-xl col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-danger">
                        <small class="text-muted text-uppercase fw-bold font-size-11">দায় (Liabilities 2xxx)</small>
                        <h4 class="fw-bold text-danger my-1">৳ {{ number_format($totalLiabilities, 2) }}</h4>
                    </div>
                </div>
                <div class="col-xl col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-purple" style="border-left-color: #8b5cf6 !important;">
                        <small class="text-muted text-uppercase fw-bold font-size-11">মূলধন (Equity 3xxx)</small>
                        <h4 class="fw-bold my-1" style="color: #8b5cf6;">৳ {{ number_format($totalEquity, 2) }}</h4>
                    </div>
                </div>
                <div class="col-xl col-md-6 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-success">
                        <small class="text-muted text-uppercase fw-bold font-size-11">আয় (Income 4xxx)</small>
                        <h4 class="fw-bold text-success my-1">৳ {{ number_format($totalIncome, 2) }}</h4>
                    </div>
                </div>
                <div class="col-xl col-md-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-warning">
                        <small class="text-muted text-uppercase fw-bold font-size-11">ব্যয় (Expense 5xxx)</small>
                        <h4 class="fw-bold text-warning my-1">৳ {{ number_format($totalExpenses, 2) }}</h4>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Tabs by Root Type -->
            <ul class="nav nav-pills mb-3 gap-2" id="coaTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active rounded-pill px-4 fw-bold" id="all-tab" data-bs-toggle="pill" data-bs-target="#all-pane" type="button" role="tab">
                        সকল অ্যাকাউন্ট ({{ $accounts->count() }})
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link rounded-pill px-4 fw-bold" id="asset-tab" data-bs-toggle="pill" data-bs-target="#asset-pane" type="button" role="tab">
                        সম্পদ (Assets)
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link rounded-pill px-4 fw-bold" id="liability-tab" data-bs-toggle="pill" data-bs-target="#liability-pane" type="button" role="tab">
                        দায় (Liabilities)
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link rounded-pill px-4 fw-bold" id="equity-tab" data-bs-toggle="pill" data-bs-target="#equity-pane" type="button" role="tab">
                        মূলধন (Equity)
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link rounded-pill px-4 fw-bold" id="income-tab" data-bs-toggle="pill" data-bs-target="#income-pane" type="button" role="tab">
                        রাজস্ব আয় (Income)
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link rounded-pill px-4 fw-bold" id="expense-tab" data-bs-toggle="pill" data-bs-target="#expense-pane" type="button" role="tab">
                        ব্যয় (Expenses)
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-pane" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr class="text-muted small text-uppercase">
                                        <th class="ps-4">কোড (Code)</th>
                                        <th>অ্যাকাউন্টের নাম (বাংলা ও ইংরেজি)</th>
                                        <th>মূল শ্রেণি (Root Type)</th>
                                        <th>স্বাভাবিক ব্যালেন্স</th>
                                        <th>বর্তমান ব্যালেন্স (৳)</th>
                                        <th>সিস্টেম শ্রেণি</th>
                                        <th class="pe-4 text-end">অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($accounts as $acc)
                                        <tr>
                                            <td class="ps-4 font-monospace fw-bold text-primary">
                                                {{ $acc->account_code }}
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $acc->account_name }}</div>
                                                <small class="text-muted">{{ $acc->account_name_bn ?? '-' }}</small>
                                            </td>
                                            <td>
                                                @if($acc->root_type == 'asset')
                                                    <span class="badge bg-primary-light text-primary">ASSET</span>
                                                @elseif($acc->root_type == 'liability')
                                                    <span class="badge bg-danger-light text-danger">LIABILITY</span>
                                                @elseif($acc->root_type == 'equity')
                                                    <span class="badge bg-purple-light text-purple" style="color: #8b5cf6;">EQUITY</span>
                                                @elseif($acc->root_type == 'income')
                                                    <span class="badge bg-success-light text-success">INCOME</span>
                                                @else
                                                    <span class="badge bg-warning-light text-warning">EXPENSE</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border">{{ strtoupper($acc->normal_balance) }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold font-size-14 {{ $acc->current_balance < 0 ? 'text-danger' : 'text-dark' }}">
                                                    ৳ {{ number_format($acc->current_balance, 2) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($acc->is_system)
                                                    <span class="badge bg-secondary-light text-secondary"><i class="fa fa-lock me-1"></i> সিস্টেম সংরক্ষিত</span>
                                                @else
                                                    <span class="badge bg-light text-muted">সাধারণ</span>
                                                @endif
                                            </td>
                                            <td class="pe-4 text-end">
                                                <a href="{{ route('admin.erp.accounts.general_ledger', ['account_id' => $acc->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                    <i class="fa fa-search me-1"></i> লেজার দেখুন
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @foreach(['asset', 'liability', 'equity', 'income', 'expense'] as $rtype)
                <div class="tab-pane fade" id="{{ $rtype }}-pane" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr class="text-muted small text-uppercase">
                                        <th class="ps-4">কোড</th>
                                        <th>অ্যাকাউন্টের নাম</th>
                                        <th>স্বাভাবিক ব্যালেন্স</th>
                                        <th>বর্তমান ব্যালেন্স (৳)</th>
                                        <th class="pe-4 text-end">লেজার</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($accounts->where('root_type', $rtype) as $acc)
                                        <tr>
                                            <td class="ps-4 font-monospace fw-bold text-primary">{{ $acc->account_code }}</td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $acc->account_name }}</div>
                                                <small class="text-muted">{{ $acc->account_name_bn ?? '-' }}</small>
                                            </td>
                                            <td><span class="badge bg-light text-dark border">{{ strtoupper($acc->normal_balance) }}</span></td>
                                            <td class="fw-bold text-dark font-size-14">৳ {{ number_format($acc->current_balance, 2) }}</td>
                                            <td class="pe-4 text-end">
                                                <a href="{{ route('admin.erp.accounts.general_ledger', ['account_id' => $acc->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                    <i class="fa fa-search me-1"></i> খতিয়ান
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </section>
    </div>
</div>

<!-- Modal: Add New Account to COA -->
<div class="modal fade" id="addAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <form action="{{ route('admin.erp.accounts.chart_of_accounts.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title fw-bold"><i class="fa fa-sitemap text-primary me-2"></i> চার্ট অব অ্যাকাউন্টসে নতুন লেজার এন্ট্রি</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">অ্যাকাউন্ট কোড <span class="text-danger">*</span></label>
                            <input type="text" name="account_code" class="form-control" placeholder="যেমন: 1130 / 5210" required>
                            <small class="text-muted">1xxx=Asset, 2xxx=Liability, 3xxx=Equity, 4xxx=Income, 5xxx=Expense</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">মূল শ্রেণি (Root Type) <span class="text-danger">*</span></label>
                            <select name="root_type" class="form-select" required id="rootTypeSelect">
                                <option value="asset">সম্পদ (Asset)</option>
                                <option value="liability">দায় (Liability)</option>
                                <option value="equity">মূলধন (Equity)</option>
                                <option value="income">রাজস্ব আয় (Income)</option>
                                <option value="expense">ব্যয় (Expense)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">অ্যাকাউন্টের নাম (English) <span class="text-danger">*</span></label>
                            <input type="text" name="account_name" class="form-control" placeholder="Office Equipment Maintenance" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">অ্যাকাউন্টের নাম (বাংলা)</label>
                            <input type="text" name="account_name_bn" class="form-control" placeholder="অফিস সরঞ্জাম মেরামত ব্যয়">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">স্বাভাবিক ব্যালেন্স (Normal Balance) <span class="text-danger">*</span></label>
                            <select name="normal_balance" class="form-select" required id="normalBalanceSelect">
                                <option value="debit">ডেবিট (Debit - সাধারণত Asset & Expense)</option>
                                <option value="credit">ক্রেডিট (Credit - সাধারণত Liability, Equity & Income)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">প্যারেন্ট / গ্রুপ অ্যাকাউন্ট (ঐচ্ছিক)</label>
                            <select name="parent_id" class="form-select">
                                <option value="">-- শীর্ষ অ্যাকাউন্ট / রুট --</option>
                                @foreach($parentAccounts as $p)
                                    <option value="{{ $p->id }}">[{{ $p->account_code }}] {{ $p->account_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">প্রারম্ভিক জের (Opening Balance ৳)</label>
                            <input type="number" step="0.01" name="opening_balance" class="form-control" value="0.00">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">অ্যাকাউন্ট যুক্ত করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rootSelect = document.getElementById('rootTypeSelect');
    const normSelect = document.getElementById('normalBalanceSelect');
    if (rootSelect && normSelect) {
        rootSelect.addEventListener('change', function() {
            if (this.value === 'asset' || this.value === 'expense') {
                normSelect.value = 'debit';
            } else {
                normSelect.value = 'credit';
            }
        });
    }
});
</script>
@endsection

