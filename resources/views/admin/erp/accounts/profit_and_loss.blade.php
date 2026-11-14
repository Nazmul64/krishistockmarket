@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 no-print">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-line-chart text-success me-2"></i> লাভ-ক্ষতি ও সার্বিক আয়-ব্যয় বিবরণী (Profit & Loss Statement)</h4>
                    <p class="text-muted small mb-0">প্রতিষ্ঠানের সকল বাণিজ্যিক খাত, পণ্য বিক্রয়, সার্ভিস চার্জ থেকে অর্জিত রাজস্ব এবং প্রশাসনিক ও পরিচালন ব্যয়ের নেট হিসাব।</p>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-sm btn-dark rounded-pill px-3 shadow-sm">
                        <i class="fa fa-print me-1"></i> বিবরণী প্রিন্ট
                    </button>
                    <a href="{{ route('admin.erp.accounts.balance_sheet') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                        <i class="fa fa-pie-chart me-1"></i> ব্যালেন্স শীট
                    </a>
                </div>
            </div>

            <!-- KPI Metric Badges -->
            <div class="row g-3 mb-4">
                <div class="col-md-4 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-success">
                        <small class="text-muted text-uppercase fw-bold font-size-11">সর্বমোট অর্জিত রাজস্ব ও আয়</small>
                        <h4 class="fw-bold text-success my-1">৳ {{ number_format($totalIncome, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-warning">
                        <small class="text-muted text-uppercase fw-bold font-size-11">সর্বমোট প্রাতিষ্ঠানিক ব্যয়</small>
                        <h4 class="fw-bold text-warning my-1">৳ {{ number_format($totalExpenses, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 {{ $netProfit >= 0 ? 'border-primary' : 'border-danger' }}">
                        <small class="text-muted text-uppercase fw-bold font-size-11">নেট পরিচালন মুনাফা / (ক্ষতি)</small>
                        <h4 class="fw-bold my-1 {{ $netProfit >= 0 ? 'text-primary' : 'text-danger' }}">
                            ৳ {{ number_format($netProfit, 2) }}
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Structured P&L Statement -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="fa fa-file-text-o text-primary me-2"></i> সমন্বিত আয় বিবরণী (Income Statement for Period)</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-muted small text-uppercase">
                                <th style="width: 20%;" class="ps-4">হিসাব কোড</th>
                                <th style="width: 50%;">হিসাব খাতের বিবরণ (Particulars)</th>
                                <th style="width: 30%;" class="text-end pe-4">পরিমাণ (৳)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- SECTION 1: REVENUE -->
                            <tr class="table-success fw-bold">
                                <td colspan="2" class="ps-4"><i class="fa fa-arrow-down text-success me-1"></i> ১. রাজস্ব ও বাণিজ্যিক পরিচালন আয় (Revenue & Income)</td>
                                <td class="text-end pe-4">৳ {{ number_format($totalIncome, 2) }}</td>
                            </tr>
                            @foreach($incomes as $inc)
                                <tr>
                                    <td class="ps-4 font-monospace text-muted">{{ $inc->account_code }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $inc->account_name }}</div>
                                        <small class="text-muted">{{ $inc->account_name_bn ?? '' }}</small>
                                    </td>
                                    <td class="text-end pe-4 fw-bold text-success font-size-14">
                                        ৳ {{ number_format($inc->current_balance, 2) }}
                                    </td>
                                </tr>
                            @endforeach

                            <!-- SECTION 2: EXPENSES -->
                            <tr class="table-warning fw-bold">
                                <td colspan="2" class="ps-4"><i class="fa fa-arrow-up text-warning me-1"></i> ২. প্রাতিষ্ঠানিক, প্রশাসনিক ও পরিচালন ব্যয় (Operating Expenses)</td>
                                <td class="text-end pe-4">৳ {{ number_format($totalExpenses, 2) }}</td>
                            </tr>
                            @foreach($expenses as $exp)
                                <tr>
                                    <td class="ps-4 font-monospace text-muted">{{ $exp->account_code }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $exp->account_name }}</div>
                                        <small class="text-muted">{{ $exp->account_name_bn ?? '' }}</small>
                                    </td>
                                    <td class="text-end pe-4 fw-bold text-warning font-size-14">
                                        ৳ {{ number_format($exp->current_balance, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold bg-light font-size-16">
                                <td colspan="2" class="text-end ps-4">
                                    {{ $netProfit >= 0 ? 'নেট পরিচালন লাভ (Net Operating Profit):' : 'নেট পরিচালন ক্ষতি (Net Operating Loss):' }}
                                </td>
                                <td class="text-end pe-4 font-size-18 {{ $netProfit >= 0 ? 'text-primary' : 'text-danger' }}">
                                    ৳ {{ number_format($netProfit, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </section>
    </div>
</div>
@endsection

