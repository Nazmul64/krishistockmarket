@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content">
            <!-- Customer Welcome Card -->
            <div class="box bg-gradient-primary text-white shadow-sm mb-4">
                <div class="box-body p-4 d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <h3 class="fw-bold mb-1">স্বাগতম, {{ Auth::user()->name }}!</h3>
                        <p class="mb-0 text-white-50"><i class="fa fa-phone me-1"></i>মোবাইল: {{ Auth::user()->phone ?? Auth::user()->username }}</p>
                    </div>
                    <div class="text-end bg-white text-dark p-3 rounded shadow-sm mt-3 mt-md-0">
                        <small class="text-muted d-block font-weight-bold">CUSTOMER CARD DETAILS</small>
                        <h4 class="fw-bold mb-0 text-primary" style="font-family: monospace;">
                            <i class="fa fa-credit-card me-1"></i>{{ GetUserCardNumber(Auth::id()) }}
                        </h4>
                        <small class="badge bg-secondary">Customer ID: #{{ Auth::id() }}</small>
                    </div>
                </div>
            </div>

            <!-- Main Quick Actions -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa fa-flash me-2 text-warning"></i> দ্রুত সেবা (Quick Actions)</h5>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <a href="{{ route('user.deposit.index') }}" class="btn btn-success w-100 py-3 text-start shadow-sm h-100">
                        <i class="fa fa-plus-circle fa-2x d-block mb-2"></i>
                        <strong class="d-block fs-6">Deposit Money</strong>
                        <small class="opacity-75">টাকা ডিপোজিট করুন</small>
                    </a>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <a href="{{ route('stock.index') }}" class="btn btn-primary w-100 py-3 text-start shadow-sm h-100">
                        <i class="fa fa-line-chart fa-2x d-block mb-2"></i>
                        <strong class="d-block fs-6">Buy Stock</strong>
                        <small class="opacity-75">স্টক প্যাকেজ কিনুন</small>
                    </a>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <a href="{{ route('user.monthly_bazaar.index') }}" class="btn btn-warning text-dark w-100 py-3 text-start shadow-sm h-100">
                        <i class="fa fa-shopping-basket fa-2x d-block mb-2"></i>
                        <strong class="d-block fs-6">Monthly Market</strong>
                        <small class="opacity-75">মাসিক বাজারের খাদ্যপণ্য</small>
                    </a>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <a href="{{ route('user.wallet.ledger') }}" class="btn btn-info w-100 py-3 text-start shadow-sm h-100">
                        <i class="fa fa-list-alt fa-2x d-block mb-2"></i>
                        <strong class="d-block fs-6">Wallet Ledger</strong>
                        <small class="opacity-75">ওয়ালেট হিস্ট্রি দেখুন</small>
                    </a>
                </div>
            </div>

            <!-- Wallet & Financial Stat Cards -->
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm bg-success text-white h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-uppercase opacity-75 d-block">বর্তমান ওয়ালেট ব্যালেন্স</small>
                                <h2 class="fw-bold mb-0">৳{{ number_format(Auth::user()->balance, 2) }}</h2>
                            </div>
                            <i class="fa fa-wallet fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm bg-primary text-white h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-uppercase opacity-75 d-block">মোট স্টক ক্রয় (Total Buy)</small>
                                <h2 class="fw-bold mb-0">৳{{ number_format($all_buy, 2) }}</h2>
                            </div>
                            <i class="fa fa-cubes fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm bg-info text-white h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-uppercase opacity-75 d-block">মোট বিক্রি (Total Sell)</small>
                                <h2 class="fw-bold mb-0">৳{{ number_format($all_sell, 2) }}</h2>
                            </div>
                            <i class="fa fa-shopping-cart fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm bg-dark text-white h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-uppercase opacity-75 d-block">মোট প্রফিট/লাভ (Profit)</small>
                                <h2 class="fw-bold mb-0">৳{{ number_format($user_profit, 2) }}</h2>
                            </div>
                            <i class="fa fa-trophy fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
</div>
@endsection
