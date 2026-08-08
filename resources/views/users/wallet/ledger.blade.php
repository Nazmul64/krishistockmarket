@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title">ওয়ালেট লেজার ও হিস্ট্রি (Wallet Ledger & History)</h4>
                    <p class="text-muted mb-0">আপনার একাউন্টের সম্পূর্ণ ওয়ালেট স্টেটমেন্ট ও লেনদেনের বিস্তারিত ইতিহাস</p>
                </div>
                <div>
                    <a href="{{ route('user.deposit.index') }}" class="btn btn-success btn-sm"><i class="fa fa-plus me-1"></i> ডিপোজিট করুন</a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <!-- Account Summary Card -->
            <div class="row mb-4">
                <div class="col-xl-4 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm bg-primary text-white h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-uppercase opacity-75 d-block">বর্তমান ওয়ালেট ব্যালেন্স</small>
                                <h2 class="fw-bold mb-0">৳{{ number_format(Auth::user()->balance, 2) }}</h2>
                            </div>
                            <i class="fa fa-wallet fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm bg-dark text-white h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-uppercase opacity-75 d-block">রেজিস্ট্রেশনকৃত কার্ড নাম্বার</small>
                                <h4 class="fw-bold mb-0" style="font-family: monospace;">{{ $user_card }}</h4>
                                <small class="opacity-75">Customer ID: #{{ Auth::id() }}</small>
                            </div>
                            <i class="fa fa-credit-card fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm bg-success text-white h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-uppercase opacity-75 d-block">মোট লেনদেন সংখ্যা</small>
                                <h2 class="fw-bold mb-0">{{ count($transactions) }} টি</h2>
                            </div>
                            <i class="fa fa-list-alt fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ledger Table -->
            <div class="row">
                <div class="col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-book me-2 text-primary"></i> সম্পূর্ণ ওয়ালেট লেজার স্টেটমেন্ট</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>SN.</th>
                                            <th>Transaction ID</th>
                                            <th>লেনদেনের ধরন (Type)</th>
                                            <th class="text-center">পূর্বের ব্যালেন্স</th>
                                            <th class="text-center">ক্রেডিট (+ ৳)</th>
                                            <th class="text-center">ডেবিট (- ৳)</th>
                                            <th class="text-center">নতুন ব্যালেন্স</th>
                                            <th>মেথড & Trx.</th>
                                            <th class="text-center">তারিখ ও সময়</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transactions as $key => $tx)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td><code class="text-dark fw-bold">{{ $tx->transaction_id }}</code></td>
                                                <td>
                                                    <span class="badge bg-secondary mb-1">{{ $tx->transaction_type }}</span>
                                                </td>
                                                <td class="text-center text-muted">৳{{ number_format($tx->previous_balance, 2) }}</td>
                                                <td class="text-center">
                                                    @if($tx->credit_amount > 0)
                                                        <span class="fw-bold text-success">+ ৳{{ number_format($tx->credit_amount, 2) }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($tx->debit_amount > 0)
                                                        <span class="fw-bold text-danger">- ৳{{ number_format($tx->debit_amount, 2) }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center fw-bold text-dark">৳{{ number_format($tx->new_balance, 2) }}</td>
                                                <td>
                                                    <small class="d-block text-dark">{{ $tx->payment_method }}</small>
                                                    <small class="text-muted">Trx: <code>{{ $tx->trx_number }}</code></small>
                                                </td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($tx->created_at)->format('d/m/Y h:i A') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-4 text-muted">কোনো ওয়ালেট লেজার হিস্ট্রি পাওয়া যায়নি।</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
