@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="ti-bar-chart text-success me-2"></i>ব্যবসায়িক অ্যানালিটিক্স ও লাভ-ক্ষতি প্রতিবেদন (Profit & Loss Analytics)
                    </h3>
                    <p class="text-muted mb-0">বিক্রয়, পণ্যের ক্রয়মূল্য (COGS), গ্রস মার্জিন, অপারেটিং খরচ ও ব্রাঞ্চভিত্তিক নিট লাভ বিশ্লেষণ</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                        <i class="ti-printer me-1"></i> রিপোর্ট প্রিন্ট
                    </button>
                    <a href="{{ route('admin.supershop.dashboard') }}" class="btn btn-sm btn-primary">
                        <i class="ti-arrow-left me-1"></i> ড্যাশবোর্ড
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <!-- Filter Bar -->
            <div class="box shadow-sm mb-4">
                <div class="box-body py-2">
                    <form method="GET" action="{{ route('admin.supershop.reports') }}" class="row g-2 align-items-center">
                        <div class="col-md-3 col-12">
                            <select name="branch_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">🏢 সকল ব্রাঞ্চ সমন্বিত (Consolidated)</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}" {{ $selectedBranchId == $b->id ? 'selected' : '' }}>
                                        {{ $b->code }} - {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 col-6">
                            <select name="period" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>আজকের দিন (Daily)</option>
                                <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>গত ৭ দিন (Weekly)</option>
                                <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>চলতি মাস (Monthly)</option>
                                <option value="yearly" {{ $period == 'yearly' ? 'selected' : '' }}>চলতি বছর (Yearly)</option>
                                <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>কাস্টম রেঞ্জ (Custom)</option>
                            </select>
                        </div>

                        <div class="col-md-2 col-6">
                            <input type="date" name="from_date" class="form-control form-control-sm" value="{{ $startDate->toDateString() }}">
                        </div>

                        <div class="col-md-2 col-6">
                            <input type="date" name="to_date" class="form-control form-control-sm" value="{{ $endDate->toDateString() }}">
                        </div>

                        <div class="col-md-2 col-6 text-end">
                            <button type="submit" class="btn btn-sm btn-primary w-100"><i class="ti-filter me-1"></i> ফিল্টার প্রয়োগ</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Financial Profit & Loss Statement (P&L) Box -->
            <div class="box shadow-sm mb-4 border-start border-4 border-success">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title fw-bold text-dark mb-0">
                        <i class="ti-money text-success me-2"></i>আয় বিবরণী ও নিট লাভ (Income Statement / P&L)
                    </h4>
                    <span class="badge bg-light text-muted">
                        সময়কাল: {{ $startDate->format('d M Y') }} হতে {{ $endDate->format('d M Y') }}
                    </span>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 align-middle">
                            <tbody>
                                <tr>
                                    <td class="fw-bold fs-6">১. মোট গ্রস রিটেল বিক্রয় (Gross Retail Sales)</td>
                                    <td class="text-end fw-bold fs-6">৳ {{ number_format($totalGrossSales, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-4">(-) বিক্রয় ডিসকাউন্ট ও অফার রিবেট</td>
                                    <td class="text-end text-danger">- ৳ {{ number_format($totalDiscounts, 2) }}</td>
                                </tr>
                                <tr class="table-light">
                                    <td class="fw-bold">২. নিট রিটেল বিক্রয় আয় (Net Retail Revenue)</td>
                                    <td class="text-end fw-bold text-primary fs-5">৳ {{ number_format($totalNetSales, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-danger ps-4">(-) বিক্রিত পণ্যের মোট ক্রয়মূল্য (Cost of Goods Sold - COGS)</td>
                                    <td class="text-end text-danger">- ৳ {{ number_format($totalCogs, 2) }}</td>
                                </tr>
                                <tr class="table-success">
                                    <td class="fw-bold fs-5 text-success">৩. গ্রস প্রফিট / মোট লাভ (Gross Profit)</td>
                                    <td class="text-end fw-bold fs-5 text-success">
                                        ৳ {{ number_format($grossProfit, 2) }}
                                        <span class="small text-muted ms-2">({{ $totalNetSales > 0 ? round(($grossProfit / $totalNetSales) * 100, 1) : 0 }}% Margin)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted ps-4">(-) শাখা পরিচালন ও অপারেটিং ব্যয় (Operating Expenses: Rent, Utilities, Wages)</td>
                                    <td class="text-end text-danger">- ৳ {{ number_format($totalExpenses, 2) }}</td>
                                </tr>
                                <tr class="{{ $netProfit >= 0 ? 'bg-gradient-success text-white' : 'bg-gradient-danger text-white' }}">
                                    <td class="fw-bold fs-4 text-white">৪. চূড়ান্ত নিট অপারেটিং লাভ (Net Operating Profit)</td>
                                    <td class="text-end fw-bold fs-4 text-white">
                                        ৳ {{ number_format($netProfit, 2) }}
                                        <span class="small text-white-50 ms-2">({{ $totalNetSales > 0 ? round(($netProfit / $totalNetSales) * 100, 1) : 0 }}% Net Margin)</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Branch Performance Financial Matrix -->
            <div class="box shadow-sm">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title fw-bold text-dark mb-0">
                        <i class="ti-layout-grid2 text-primary me-2"></i>শাখাভিত্তিক আর্থিক পারফরম্যান্স ম্যাট্রিক্স (Branch Matrix)
                    </h4>
                    <span class="badge bg-primary-light text-primary">প্রত্যেক ব্রাঞ্চের পৃথক P&L অডিট</span>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ব্রাঞ্চ কোড ও নাম</th>
                                    <th class="text-center">লেনদেন সংখ্যা</th>
                                    <th class="text-end">নিট বিক্রয়</th>
                                    <th class="text-end">COGS (ক্রয়মূল্য)</th>
                                    <th class="text-end">গ্রস প্রফিট</th>
                                    <th class="text-end">পরিচালন খরচ</th>
                                    <th class="text-end">নিট লাভ (Net Profit)</th>
                                    <th class="text-center">মার্জিন (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($branchReports as $bRep)
                                    <tr>
                                        <td>
                                            <span class="badge bg-dark">{{ $bRep['branch']->code }}</span>
                                            <strong class="ms-1 text-dark">{{ $bRep['branch']->name }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border">{{ $bRep['transactions'] }}</span>
                                        </td>
                                        <td class="text-end fw-bold text-dark">
                                            ৳ {{ number_format($bRep['net_sales'], 2) }}
                                        </td>
                                        <td class="text-end text-muted">
                                            ৳ {{ number_format($bRep['cogs'], 2) }}
                                        </td>
                                        <td class="text-end fw-bold text-success">
                                            ৳ {{ number_format($bRep['gross_profit'], 2) }}
                                        </td>
                                        <td class="text-end text-danger">
                                            ৳ {{ number_format($bRep['expenses'], 2) }}
                                        </td>
                                        <td class="text-end fw-bold {{ $bRep['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                            ৳ {{ number_format($bRep['net_profit'], 2) }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $bRep['profit_margin'] >= 10 ? 'bg-success' : ($bRep['profit_margin'] >= 0 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                                {{ $bRep['profit_margin'] }}%
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">কোনো ব্রাঞ্চ পারফরম্যান্স তথ্য পাওয়া যায়নি।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

