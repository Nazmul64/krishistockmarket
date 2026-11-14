@extends('layouts.backend.app')

@section('content')
@php
    $sh = $sh ?? ($profile ?? null);
    $transactions = $transactions ?? collect([]);
    $user = $user ?? Auth::user();
@endphp
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content py-3">

            <!-- Title & Top Actions -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-pie-chart text-primary me-2"></i> শেয়ারহোল্ডার প্রোফাইল ও মূলধন ব্যবস্থাপনা</h4>
                    <p class="text-muted small mb-0">প্রতিষ্ঠানের ১০০টি মূল শেয়ারের অংশীদারিত্ব, শেয়ার মূলধন ব্যালেন্স, অফিস খরচ স্টেটমেন্ট ও লভ্যাংশ বিবরণী।</p>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                        <i class="fa fa-print me-1"></i> সার্টিফিকেট প্রিন্ট
                    </button>
                    <a href="{{ route('user.corporate.community') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                        <i class="fa fa-comments me-1"></i> শেয়ারহোল্ডার ফোরাম
                    </a>
                </div>
            </div>

            <!-- Shareholder KPI Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small d-block">বরাদ্দকৃত শেয়ার</span>
                                <h4 class="fw-bold text-dark mt-1 mb-0">{{ $sh->shares_count }} টি</h4>
                            </div>
                            <span class="badge bg-purple-subtle text-purple rounded-circle p-3" style="background: rgba(147, 51, 234, 0.1); color: #9333ea;">
                                <i class="fa fa-certificate fa-lg"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small d-block">মোট শেয়ার মূলধন (Capital)</span>
                                <h4 class="fw-bold text-primary mt-1 mb-0">{{ number_format($sh->total_investment, 2) }} ৳</h4>
                            </div>
                            <span class="badge bg-primary-subtle text-primary rounded-circle p-3"><i class="fa fa-money fa-lg"></i></span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small d-block">পরিশোধিত অফিস খরচ</span>
                                <h4 class="fw-bold text-danger mt-1 mb-0">{{ number_format($sh->office_expense_paid, 2) }} ৳</h4>
                            </div>
                            <span class="badge bg-danger-subtle text-danger rounded-circle p-3"><i class="fa fa-building fa-lg"></i></span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small d-block">মোট অর্জিত লভ্যাংশ</span>
                                <h4 class="fw-bold text-success mt-1 mb-0">{{ number_format($sh->total_dividend_earned, 2) }} ৳</h4>
                            </div>
                            <span class="badge bg-success-subtle text-success rounded-circle p-3"><i class="fa fa-line-chart fa-lg"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Official Share Certificate -->
            <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 mb-4 bg-white certificate-container position-relative overflow-hidden" 
                 style="border: 4px double #d97706 !important;">
                
                <div class="text-center mb-4">
                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold text-uppercase mb-2">OFFICIAL SHARE CERTIFICATE</span>
                    <h3 class="fw-bold text-dark font-serif mb-1">ই-কৃষি সেবা লিমিটেড</h3>
                    <p class="text-muted small mb-0">গভর্নমেন্ট অফ দ্য পিপলস রিপাবলিক অফ বাংলাদেশ অ্যাক্ট অনুযায়ী গঠিত ও নিবন্ধিত</p>
                    <div class="text-danger fw-bold font-monospace font-size-14 mt-1">
                        সার্টিফিকেট নং: {{ $sh->shareholder_code }}
                    </div>
                </div>

                <div class="my-4 px-md-5 font-serif text-dark" style="line-height: 2; font-size: 16px;">
                    এই মর্মে প্রত্যায়ন করা যাচ্ছে যে, জনাব/জনাবা <strong>{{ $sh->user->name ?? '' }}</strong>, জাতীয় পরিচয়পত্র নং <strong>{{ $sh->nid_number ?: 'যাচাইকৃত' }}</strong>, পিতা/স্বামী: <strong>{{ $sh->user->present_address ? Str::words($sh->user->present_address, 4, '') : 'নিবন্ধিত সদস্য' }}</strong>, ই-কৃষি সেবা লিমিটেডের সর্বমোট ১০০টি মূল শেয়ারের মধ্যে <strong>{{ $sh->shares_count }} ({{ $sh->shares_count == 1 ? 'একটি' : 'কয়েকটি' }})</strong> টি অর্ডিনারি শেয়ারের বৈধ ও নিবন্ধিত মালিক। প্রতিটি শেয়ারের অভিহিত মূল্য ৫,০০০ (পাঁচ হাজার) টাকা এবং প্রতি শেয়ারের নির্ধারিত অফিস ব্যবস্থাপনা খরচ ৩,০০০ টাকা পরিশোধিত হইয়াছে।
                </div>

                <div class="row align-items-end mt-5 pt-4 border-top">
                    <div class="col-4 text-center">
                        <div class="fw-bold font-monospace text-dark font-size-13">হেড অব ফাইন্যান্স</div>
                        <span class="text-muted font-size-11">হিসাব ও অডিট বিভাগ</span>
                    </div>
                    <div class="col-4 text-center">
                        <div class="d-inline-block border border-2 border-warning rounded-circle p-3 text-warning shadow-sm" style="width: 75px; height: 75px; display: inline-flex; align-items: center; justify-content: center;">
                            <i class="fa fa-certificate fa-2x"></i>
                        </div>
                        <span class="d-block font-size-10 text-muted mt-1">প্রাতিষ্ঠানিক সিলমোহর</span>
                    </div>
                    <div class="col-4 text-center">
                        <div class="fw-bold font-monospace text-dark font-size-13">ম্যানেজিং ডিরেক্টর / চেয়ারম্যান</div>
                        <span class="text-muted font-size-11">পরিচালনা পর্ষদ</span>
                    </div>
                </div>

                <!-- Legal Note -->
                <div class="mt-4 pt-3 border-top text-center text-muted font-size-11">
                    গুরুত্বপূর্ণ: লভ্যাংশ বা রিটার্ন প্রকৃত ব্যবসায়িক ফলাফল, অনুমোদিত নীতিমালা এবং প্রযোজ্য আইন অনুযায়ী হিসাব করা হয়। কোনো ফিক্সড বা নিশ্চিত মুনাফা প্রদেয় নয়।
                </div>
            </div>

            <!-- Shareholder Statement & Transactions Table -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="fa fa-list-alt text-primary me-2"></i> শেয়ার মূলধন, অফিস খরচ ও লভ্যাংশ স্টেটমেন্ট</h5>
                    <span class="badge bg-light text-muted">মোট {{ $transactions->count() }}টি রেকর্ড</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>লেনদেনের ধরন</th>
                                <th>শেয়ার সংখ্যা</th>
                                <th>মূলধন পরিমাণ</th>
                                <th>অফিস খরচ কর্তন</th>
                                <th>পেমেন্ট মেথড</th>
                                <th>তারিখ</th>
                                <th>বিবরণ</th>
                                <th>স্ট্যাটাস</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $t)
                            <tr>
                                <td>
                                    <span class="badge {{ match($t->transaction_type) {
                                        'purchase' => 'bg-primary',
                                        'dividend_declared' => 'bg-success',
                                        'dividend_payout' => 'bg-info',
                                        default => 'bg-secondary',
                                    } }} px-2 py-1">
                                        {{ match($t->transaction_type) {
                                            'purchase' => 'শেয়ার বরাদ্দ / ক্রয়',
                                            'dividend_declared' => 'ঘোষিত লভ্যাংশ',
                                            'dividend_payout' => 'লভ্যাংশ উত্তোলন',
                                            default => $t->transaction_type,
                                        } }}
                                    </span>
                                </td>
                                <td class="fw-bold">{{ $t->shares_count }} টি</td>
                                <td class="fw-bold text-dark">{{ number_format($t->amount, 2) }} ৳</td>
                                <td class="text-danger fw-semibold">{{ number_format($t->office_expense, 2) }} ৳</td>
                                <td class="text-uppercase">{{ $t->payment_method ?: 'ব্যাংক' }}</td>
                                <td>{{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y') }}</td>
                                <td class="text-muted">{{ $t->description }}</td>
                                <td><span class="badge bg-success-subtle text-success">সম্পন্ন</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">কোনো লেনদেনের তথ্য পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
    </div>
</div>
@endsection
