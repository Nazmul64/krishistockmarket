@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title">সার্বিক বাজার চাহিদা ও বিতরণ রিপোর্ট (Comprehensive Distribution Reports)</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home-outline"></i> ড্যাশবোর্ড</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.monthly_market.requests') }}">বাজার রিকোয়েস্ট</a></li>
                        <li class="breadcrumb-item active">সার্বিক রিপোর্ট</li>
                    </ol>
                </div>
                <div>
                    <button onclick="window.print()" class="btn btn-primary btn-sm me-2">
                        <i class="fa fa-print me-1"></i> প্রিন্ট / PDF রিপোর্ট
                    </button>
                    <a href="{{ route('admin.monthly_market.demand_analysis') }}" class="btn btn-outline-success btn-sm">
                        <i class="fa fa-bar-chart me-1"></i> ডিমান্ড অ্যানালাইসিস
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Filter Bar -->
            <div class="box">
                <div class="box-body py-3">
                    <form method="GET" action="{{ route('admin.monthly_market.reports') }}" class="row g-3 align-items-center">
                        <div class="col-md-4 col-6">
                            <label class="form-label fw-bold mb-1">মাস (Month)</label>
                            <select name="month" class="form-select">
                                @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $m)
                                    <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-6">
                            <label class="form-label fw-bold mb-1">বছর (Year)</label>
                            <select name="year" class="form-select">
                                @foreach([date('Y'), date('Y')+1, date('Y')-1] as $y)
                                    <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-12 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-filter me-1"></i> রিপোর্ট ফিল্টার করুন
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Report 1: Area-wise Demand & Distribution Report (Section 27) -->
            <div class="box">
                <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                    <h4 class="box-title text-dark">
                        <i class="fa fa-map-marker me-2 text-danger"></i> ১. এলাকাভিত্তিক চাহিদা ও বিতরণ রিপোর্ট (Area-wise Demand & Distribution Report)
                    </h4>
                    <span class="badge bg-primary">{{ $selectedMonth }} {{ $selectedYear }}</span>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0 text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 20%; text-align: left;">এলাকা (Area)</th>
                                    <th style="width: 12%;">কার্ড হোল্ডার</th>
                                    <th style="width: 10%;">মোট রিকোয়েস্ট</th>
                                    <th style="width: 12%;">চাহিদা (Demand)</th>
                                    <th style="width: 12%;">অনুমোদিত</th>
                                    <th style="width: 12%;">বরাদ্দকৃত</th>
                                    <th style="width: 11%;">বিতরণকৃত (Distributed)</th>
                                    <th style="width: 11%;">অবশিষ্ট (Remaining)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($areaReports as $area)
                                    <tr>
                                        <td class="align-middle text-start fw-bold text-dark fs-15">
                                            <i class="fa fa-map-pin text-danger me-1"></i> {{ $area['area'] }}
                                        </td>
                                        <td class="align-middle fw-bold text-primary">{{ $area['cardholders_count'] }} জন</td>
                                        <td class="align-middle fw-bold">{{ $area['total_requests'] }} টি</td>
                                        <td class="align-middle fw-bold text-muted">{{ number_format($area['total_demand_qty'], 1) }}</td>
                                        <td class="align-middle fw-bold text-success">{{ number_format($area['approved_qty'], 1) }}</td>
                                        <td class="align-middle fw-bold text-warning text-dark">{{ number_format($area['allocated_qty'], 1) }}</td>
                                        <td class="align-middle fw-bold text-info">{{ number_format($area['distributed_qty'], 1) }}</td>
                                        <td class="align-middle fw-bold text-danger">{{ number_format($area['remaining_qty'], 1) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            কোনো ডাটা পাওয়া যায়নি।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Report 2: Agent Point-wise Distribution Report (Section 28) -->
            <div class="box">
                <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                    <h4 class="box-title text-dark">
                        <i class="fa fa-building me-2 text-success"></i> ২. এজেন্ট পয়েন্টভিত্তিক বিতরণ রিপোর্ট (Agent Point-wise Distribution Report)
                    </h4>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0 text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 25%; text-align: left;">এজেন্ট পয়েন্টের নাম</th>
                                    <th style="width: 15%;">এরিয়া</th>
                                    <th style="width: 10%;">সদস্য</th>
                                    <th style="width: 10%;">রিকোয়েস্ট</th>
                                    <th style="width: 10%;">মোট চাহিদা</th>
                                    <th style="width: 10%;">বরাদ্দকৃত</th>
                                    <th style="width: 10%;">বিতরণকৃত</th>
                                    <th style="width: 10%;">অবশিষ্ট</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($agentPointReports as $point)
                                    <tr>
                                        <td class="align-middle text-start fw-bold text-dark">
                                            {{ $point['agent_point'] }}
                                        </td>
                                        <td class="align-middle text-muted">{{ $point['area'] }}</td>
                                        <td class="align-middle fw-bold text-primary">{{ $point['cardholders_count'] }} জন</td>
                                        <td class="align-middle fw-bold">{{ $point['total_requests'] }} টি</td>
                                        <td class="align-middle fw-bold text-muted">{{ number_format($point['total_demand_qty'], 1) }}</td>
                                        <td class="align-middle fw-bold text-warning text-dark">{{ number_format($point['allocated_qty'], 1) }}</td>
                                        <td class="align-middle fw-bold text-info">{{ number_format($point['distributed_qty'], 1) }}</td>
                                        <td class="align-middle fw-bold text-danger">{{ number_format($point['remaining_qty'], 1) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            কোনো এজেন্ট পয়েন্টের ডাটা নেই।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Report 3: Product-wise Demand & Allocation Report (Section 29) -->
            <div class="box">
                <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                    <h4 class="box-title text-dark">
                        <i class="fa fa-cubes me-2 text-primary"></i> ৩. পণ্যভিত্তিক চাহিদা, বরাদ্দ ও বিতরণ সারসংক্ষেপ (Product-wise Demand & Distribution)
                    </h4>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0 text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 30%; text-align: left;">পণ্যের নাম</th>
                                    <th style="width: 10%;">একক</th>
                                    <th style="width: 13%;">মোট চাহিদা</th>
                                    <th style="width: 14%;">অনুমোদিত পরিমাণ</th>
                                    <th style="width: 14%;">মোট বিতরণকৃত</th>
                                    <th style="width: 14%;">মোট অবশিষ্ট</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productReports as $idx => $prod)
                                    <tr>
                                        <td class="align-middle fw-bold">{{ $idx + 1 }}</td>
                                        <td class="align-middle text-start fw-bold text-dark fs-15">
                                            {{ $prod->product_name }}
                                        </td>
                                        <td class="align-middle text-muted">{{ $prod->unit }}</td>
                                        <td class="align-middle fw-bold text-primary fs-15">
                                            {{ number_format($prod->requested_qty, 1) }}
                                        </td>
                                        <td class="align-middle fw-bold text-success fs-15">
                                            {{ number_format($prod->approved_qty, 1) }}
                                        </td>
                                        <td class="align-middle fw-bold text-info fs-15">
                                            {{ number_format($prod->collected_qty, 1) }}
                                        </td>
                                        <td class="align-middle fw-bold text-danger fs-15">
                                            {{ number_format($prod->remaining_qty, 1) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            কোনো পণ্যের ডাটা নেই।
                                        </td>
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
