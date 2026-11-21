@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-lock text-warning me-2"></i>Daily Point Closing & Settlement (দৈনিক পয়েন্ট ক্লোজিং)
                    </h3>
                    <p class="text-muted mb-0">এজেন্ট পয়েন্ট: <strong>{{ $point->name }}</strong> ({{ $point->code }}) | দিন শেষে বিতরণ ও মজুত হিসাব ক্লোজ করুন</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('agent.distribution.daily_register') }}" class="btn btn-outline-info btn-sm">
                        <i class="fa fa-book me-1"></i> আজকের বিতরণ রেজিস্টার
                    </a>
                    <a href="{{ route('agent.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-dashboard me-1"></i> ড্যাশবোর্ড
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4">
                <!-- Left Column: Daily Summary & Submit Form -->
                <div class="col-xl-5 col-lg-6">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="card-title fw-bold text-dark mb-0 font-size-15">
                                <i class="fa fa-calendar-check-o text-warning me-2"></i>ক্লোজিং তারিখ নির্বাচন ও সামারি
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form method="GET" action="{{ route('agent.distribution.daily_closing') }}" class="mb-4">
                                <label class="form-label fw-semibold font-size-12">তারিখ পরিবর্তন করুন:</label>
                                <div class="input-group">
                                    <input type="date" name="date" class="form-control" value="{{ $date }}">
                                    <button class="btn btn-secondary" type="submit">দেখুন</button>
                                </div>
                            </form>

                            <!-- Daily Summary Metrics -->
                            <div class="bg-light p-3 rounded-3 mb-4 font-size-13">
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted">Opening Stock (শুরুর মজুত):</span>
                                    <strong class="text-dark">{{ number_format($openingStock, 1) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted">Total Stock Received (আজকে গ্রহণ):</span>
                                    <strong class="text-primary">+{{ number_format($receivedToday, 1) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted">Total Customers Served (সেবা গ্রহণকারী):</span>
                                    <strong class="text-dark">{{ number_format($customersServedToday) }} জন</strong>
                                </div>
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted">Monthly Markets (মাসিক বাজার):</span>
                                    <strong class="text-primary">{{ $monthlyCount }} টি</strong>
                                </div>
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted">Weekly Markets (সাপ্তাহিক বাজার):</span>
                                    <strong class="text-info">{{ $weeklyCount }} টি</strong>
                                </div>
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted">Total Product Distributed (বিতরণ):</span>
                                    <strong class="text-danger">-{{ number_format($productDistributed, 1) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted">Total Distribution Value (মোট মূল্য):</span>
                                    <strong class="text-purple" style="color: #6f42c1;">৳{{ number_format($distributionAmount, 2) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between py-2 font-size-14 bg-white rounded p-2 mt-2 border">
                                    <span class="fw-bold text-dark">Closing Stock (বর্তমান অবশিষ্ট মজুত):</span>
                                    <strong class="text-success font-size-15">{{ number_format($closingStock, 1) }}</strong>
                                </div>
                            </div>

                            @if($existingClosing)
                                <div class="alert alert-{{ $existingClosing->status == 'verified' ? 'success' : 'info' }} font-size-13">
                                    <i class="fa fa-check-circle me-1"></i> এই তারিখের ক্লোজিং ইতোমধ্যে সাবমিট করা হয়েছে (স্ট্যাটাস: <strong>{{ strtoupper($existingClosing->status) }}</strong>)।
                                    @if($existingClosing->verified_at)
                                        <div class="font-size-11 mt-1 text-muted">অ্যাডমিন কর্তৃক ভেরিফাইড: {{ date('d M, Y h:i A', strtotime($existingClosing->verified_at)) }}</div>
                                    @endif
                                </div>
                            @endif

                            <form action="{{ route('agent.distribution.daily_closing.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="closing_date" value="{{ $date }}">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Agent Closing Note (ক্লোজিং মন্তব্য)</label>
                                    <textarea name="agent_note" rows="3" class="form-control" 
                                              placeholder="আজকের দিনের বিতরণ ও মজুত সংক্রান্ত কোনো বিশেষ নোট থাকলে লিখুন...">{{ old('agent_note', optional($existingClosing)->agent_note) }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-warning w-100 fw-bold text-dark py-2 shadow-sm">
                                    <i class="fa fa-lock me-1"></i> {{ $existingClosing ? 'দৈনিক ক্লোজিং আপডেট করুন' : 'দৈনিক ক্লোজিং সাবমিট করুন' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Historical Daily Closings -->
                <div class="col-xl-7 col-lg-6">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="card-title fw-bold text-dark mb-0 font-size-15">
                                <i class="fa fa-history text-primary me-2"></i>বিগত দিনের ক্লোজিং হিস্ট্রি (Previous Closings)
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 font-size-13">
                                    <thead class="table-light text-muted font-size-11 text-uppercase">
                                        <tr>
                                            <th class="ps-3">Closing Date</th>
                                            <th class="text-center">Served</th>
                                            <th class="text-end">Distributed (Qty)</th>
                                            <th class="text-end">Total Value (৳)</th>
                                            <th class="text-end">Closing Stock</th>
                                            <th class="text-center pe-3">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($previousClosings as $cls)
                                        <tr>
                                            <td class="ps-3 fw-bold text-dark">
                                                {{ date('d M, Y', strtotime($cls->closing_date)) }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark">{{ $cls->total_customers_served }} জন</span>
                                            </td>
                                            <td class="text-end fw-semibold text-danger">
                                                {{ number_format($cls->total_product_distributed, 1) }}
                                            </td>
                                            <td class="text-end fw-bold text-primary">
                                                ৳{{ number_format($cls->total_distribution_amount, 2) }}
                                            </td>
                                            <td class="text-end fw-bold text-success">
                                                {{ number_format($cls->closing_stock, 1) }}
                                            </td>
                                            <td class="text-center pe-3">
                                                @if($cls->status == 'verified')
                                                    <span class="badge bg-success font-size-11">Verified</span>
                                                @else
                                                    <span class="badge bg-warning text-dark font-size-11">Submitted</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                কোনো পূর্বের ক্লোজিং রেকর্ড পাওয়া যায়নি।
                                            </td>
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
