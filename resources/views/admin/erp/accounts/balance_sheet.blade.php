@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 no-print">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-pie-chart text-danger me-2"></i> উদ্বৃত্তপত্র বিবরণী (Balance Sheet / Financial Position)</h4>
                    <p class="text-muted small mb-0">প্রতিষ্ঠানের মোট পুঞ্জীভূত সম্পদ, আর্থিক দায়দেনা, শেয়ার মূলধন ও সংরক্ষিত তহবিলের সমন্বিত উদ্বৃত্তপত্র।</p>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-sm btn-dark rounded-pill px-3 shadow-sm">
                        <i class="fa fa-print me-1"></i> ব্যালেন্স শীট প্রিন্ট
                    </button>
                    <a href="{{ route('admin.erp.accounts.trial_balance') }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                        <i class="fa fa-balance-scale me-1"></i> ট্রায়াল ব্যালেন্স
                    </a>
                </div>
            </div>

            <!-- Accounting Equation Verification Box -->
            @php $isBalanced = (abs($totalAssets - $grandTotalEquityLiabilities) < 0.01); @endphp
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4 border-start border-4 {{ $isBalanced ? 'border-success' : 'border-danger' }}">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <span class="badge {{ $isBalanced ? 'bg-success' : 'bg-danger' }} text-uppercase fw-bold mb-1">
                            {{ $isBalanced ? 'হিসাব সমীকরণ নিখুঁত (Equation Balanced)' : 'অসামঞ্জস্য (Unbalanced)' }}
                        </span>
                        <h4 class="fw-bold text-dark mb-0">মোট সম্পদ = মোট দায় + শেয়ারহোল্ডারদের ইকুইটি</h4>
                        <small class="text-muted">হিসাব সমাপ্তি তারিখ: {{ date('d F, Y') }}</small>
                    </div>
                    <div class="d-flex gap-4">
                        <div class="text-end">
                            <small class="text-muted text-uppercase fw-bold">সর্বমোট সম্পদ (Assets)</small>
                            <h3 class="fw-bold text-primary mb-0">৳ {{ number_format($totalAssets, 2) }}</h3>
                        </div>
                        <div class="text-end">
                            <small class="text-muted text-uppercase fw-bold">মোট দায় ও মূলধন (Liabilities & Equity)</small>
                            <h3 class="fw-bold text-success mb-0">৳ {{ number_format($grandTotalEquityLiabilities, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Left Column: Assets -->
                <div class="col-lg-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="fw-bold mb-0"><i class="fa fa-building me-2"></i> সম্পদসমূহ (ASSETS)</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr class="small text-muted">
                                        <th class="ps-4">কোড</th>
                                        <th>সম্পদের খাতের নাম</th>
                                        <th class="text-end pe-4">পরিমাণ (৳)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assets as $ast)
                                        <tr>
                                            <td class="ps-4 font-monospace text-muted">{{ $ast->account_code }}</td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $ast->account_name }}</div>
                                                <small class="text-muted">{{ $ast->account_name_bn ?? '' }}</small>
                                            </td>
                                            <td class="text-end pe-4 fw-bold text-primary font-size-14">
                                                ৳ {{ number_format($ast->current_balance, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold bg-light font-size-15">
                                        <td colspan="2" class="text-end ps-4">সর্বমোট সম্পদ (Total Assets):</td>
                                        <td class="text-end pe-4 text-primary font-size-16">৳ {{ number_format($totalAssets, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Liabilities & Equity -->
                <div class="col-lg-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100">
                        <div class="card-header bg-dark text-white py-3">
                            <h5 class="fw-bold mb-0"><i class="fa fa-shield me-2"></i> দায় ও মালিকানাস্বত্ব (LIABILITIES & EQUITY)</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr class="small text-muted">
                                        <th class="ps-4">কোড</th>
                                        <th>খাতের বিবরণ</th>
                                        <th class="text-end pe-4">পরিমাণ (৳)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- LIABILITIES -->
                                    <tr class="table-light fw-bold text-danger">
                                        <td colspan="3" class="ps-4">আর্থিক দায়সমূহ (Liabilities)</td>
                                    </tr>
                                    @foreach($liabilities as $lia)
                                        <tr>
                                            <td class="ps-4 font-monospace text-muted">{{ $lia->account_code }}</td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $lia->account_name }}</div>
                                                <small class="text-muted">{{ $lia->account_name_bn ?? '' }}</small>
                                            </td>
                                            <td class="text-end pe-4 fw-bold text-danger font-size-14">
                                                ৳ {{ number_format($lia->current_balance, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach

                                    <!-- EQUITY -->
                                    <tr class="table-light fw-bold text-purple" style="color: #8b5cf6;">
                                        <td colspan="3" class="ps-4">মালিকানাস্বত্ব ও মূলধন (Shareholders' Equity)</td>
                                    </tr>
                                    @foreach($equities as $eq)
                                        <tr>
                                            <td class="ps-4 font-monospace text-muted">{{ $eq->account_code }}</td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $eq->account_name }}</div>
                                                <small class="text-muted">{{ $eq->account_name_bn ?? '' }}</small>
                                            </td>
                                            <td class="text-end pe-4 fw-bold font-size-14" style="color: #8b5cf6;">
                                                ৳ {{ number_format($eq->current_balance, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach

                                    <!-- Retained Earnings from Current Period -->
                                    <tr>
                                        <td class="ps-4 font-monospace text-muted">RE-01</td>
                                        <td>
                                            <div class="fw-bold text-dark">চলতি মেয়াদের অবণ্টিত মুনাফা (Retained Earnings / Net Income)</div>
                                            <small class="text-muted">লাভ-ক্ষতি বিবরণী হতে স্থানান্তরিত</small>
                                        </td>
                                        <td class="text-end pe-4 fw-bold font-size-14 {{ $retainedEarnings >= 0 ? 'text-success' : 'text-danger' }}">
                                            ৳ {{ number_format($retainedEarnings, 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold bg-light font-size-15">
                                        <td colspan="2" class="text-end ps-4">সর্বমোট দায় ও মূলধন:</td>
                                        <td class="text-end pe-4 text-success font-size-16">৳ {{ number_format($grandTotalEquityLiabilities, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
</div>
@endsection

