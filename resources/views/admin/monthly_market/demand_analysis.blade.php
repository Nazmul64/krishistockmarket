@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title">সেন্ট্রাল প্রোডাক্ট ডিমান্ড অ্যানালাইসিস (Central Product Demand Analysis)</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.monthly_market.requests') }}">বাজার রিকোয়েস্ট</a></li>
                        <li class="breadcrumb-item active">ডিমান্ড অ্যানালাইসিস</li>
                    </ol>
                </div>
                <div>
                    <button onclick="window.print()" class="btn btn-outline-primary btn-sm me-2">
                        <i class="fa fa-print me-1"></i> প্রিন্ট রিপোর্ট
                    </button>
                    <a href="{{ route('admin.monthly_market.allocations') }}" class="btn btn-success btn-sm">
                        <i class="fa fa-th-list me-1"></i> এজেন্ট পয়েন্ট বরাদ্দ
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Filter Bar -->
            <div class="box">
                <div class="box-body py-3">
                    <form method="GET" action="{{ route('admin.monthly_market.demand_analysis') }}" class="row g-3 align-items-center">
                        <div class="col-md-3 col-6">
                            <label class="form-label fw-bold mb-1">মাস (Select Month)</label>
                            <select name="month" class="form-select">
                                @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $m)
                                    <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label fw-bold mb-1">বছর (Year)</label>
                            <select name="year" class="form-select">
                                @foreach([date('Y'), date('Y')+1, date('Y')-1] as $y)
                                    <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label fw-bold mb-1">এলাকা (Filter by Area)</label>
                            <select name="request_area" class="form-select">
                                <option value="">সকল এলাকা</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area }}" {{ request('request_area') == $area ? 'selected' : '' }}>{{ $area }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-search me-1"></i> ডিমান্ড অ্যানালাইসিস দেখুন
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Top Demand Summary KPI Cards -->
            <div class="row">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box bg-primary-light">
                        <div class="box-body d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-primary fw-bold mb-0">{{ $totalCardHolders }}</h3>
                                <p class="text-muted mb-0">মোট আবেদনকারী কার্ড সদস্য</p>
                            </div>
                            <div class="bg-primary text-white p-3 rounded-circle">
                                <i class="fa fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box bg-info-light">
                        <div class="box-body d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-info fw-bold mb-0">{{ $totalSubmittedRequests }}</h3>
                                <p class="text-muted mb-0">মোট জমাকৃত আবেদন</p>
                            </div>
                            <div class="bg-info text-white p-3 rounded-circle">
                                <i class="fa fa-file-text-o fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box bg-success-light">
                        <div class="box-body d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-success fw-bold mb-0">{{ $approvedDemandCount }}</h3>
                                <p class="text-muted mb-0">অনুমোদিত চাহিদা</p>
                            </div>
                            <div class="bg-success text-white p-3 rounded-circle">
                                <i class="fa fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box bg-warning-light">
                        <div class="box-body d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-warning fw-bold mb-0">{{ $pendingDemandCount }}</h3>
                                <p class="text-muted mb-0">পেন্ডিং যাচাইকরণ</p>
                            </div>
                            <div class="bg-warning text-white p-3 rounded-circle">
                                <i class="fa fa-hourglass-half fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product-wise Central Demand Table (Section 18) -->
            <div class="box">
                <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                    <h4 class="box-title text-dark"><i class="fa fa-cubes me-2 text-primary"></i> পণ্যভিত্তিক সেন্ট্রাল মোট বাজার চাহিদা ({{ $selectedMonth }} {{ $selectedYear }})</h4>
                    <span class="badge bg-success">{{ $productDemands->count() }} টি পণ্য চাহিদাভুক্ত</span>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0 text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 30%; text-align: left;">পণ্যের নাম (Product Name)</th>
                                    <th style="width: 15%;">একক (Unit)</th>
                                    <th style="width: 15%;">মোট চাহিদাকৃত পরিমাণ</th>
                                    <th style="width: 15%;">অনুমোদিত চাহিদা</th>
                                    <th style="width: 15%;">বরাদ্দকৃত পরিমাণ</th>
                                    <th style="width: 15%;">বিতরণ সম্পন্ন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productDemands as $idx => $prod)
                                    <tr>
                                        <td class="align-middle fw-bold">{{ $idx + 1 }}</td>
                                        <td class="align-middle text-start fw-bold text-dark fs-15">
                                            {{ $prod->product_name }}
                                        </td>
                                        <td class="align-middle text-muted">{{ $prod->unit }}</td>
                                        <td class="align-middle fs-16 fw-bold text-primary">
                                            {{ number_format($prod->total_requested, 1) }}
                                        </td>
                                        <td class="align-middle fs-16 fw-bold text-success">
                                            {{ number_format($prod->total_approved, 1) }}
                                        </td>
                                        <td class="align-middle fs-16 fw-bold text-warning text-dark">
                                            {{ number_format($prod->total_allocated, 1) }}
                                        </td>
                                        <td class="align-middle fs-16 fw-bold text-info">
                                            {{ number_format($prod->total_collected, 1) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            {{ $selectedMonth }} {{ $selectedYear }} মাসের জন্য কোনো পণ্যের চাহিদা জমা পড়েনি।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Area-wise Product Demand Breakdown (Section 19) -->
            <div class="box">
                <div class="box-header with-border bg-light">
                    <h4 class="box-title text-dark"><i class="fa fa-map-marker me-2 text-danger"></i> এরিয়াভিত্তিক পণ্য চাহিদা সংক্ষেপ (Area-wise Product Demand Matrix)</h4>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0 text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 20%; text-align: left;">এলাকা (Area)</th>
                                    <th style="width: 15%;">কার্ড হোল্ডার সংখ্যা</th>
                                    <th style="width: 15%;">মোট রিকোয়েস্ট</th>
                                    <th style="width: 50%; text-align: left;">পণ্যের চাহিদা বিবরণ (Product Demands)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($areaDemands as $area)
                                    <tr>
                                        <td class="align-middle text-start fw-bold text-dark fs-15">
                                            <i class="fa fa-map-pin text-danger me-1"></i> {{ $area['area'] }}
                                        </td>
                                        <td class="align-middle fw-bold text-primary fs-15">
                                            {{ $area['cardholders_count'] }} জন
                                        </td>
                                        <td class="align-middle fw-bold text-info fs-15">
                                            {{ $area['requests_count'] }} টি
                                        </td>
                                        <td class="align-middle text-start">
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($area['products'] as $pName => $pData)
                                                    <span class="badge bg-light text-dark border p-2">
                                                        <strong>{{ $pName }}:</strong> {{ number_format($pData['quantity'], 1) }} {{ $pData['unit'] }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            কোনো এরিয়ার চাহিদা তথ্য নেই।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Agent Point-wise Demand Breakdown -->
            <div class="box">
                <div class="box-header with-border bg-light">
                    <h4 class="box-title text-dark"><i class="fa fa-building-o me-2 text-success"></i> এজেন্ট পয়েন্টভিত্তিক চাহিদা সারসংক্ষেপ</h4>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0 text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 25%; text-align: left;">এজেন্ট পয়েন্টের নাম</th>
                                    <th style="width: 15%;">এরিয়া</th>
                                    <th style="width: 12%;">সদস্য সংখ্যা</th>
                                    <th style="width: 12%;">মোট রিকোয়েস্ট</th>
                                    <th style="width: 36%; text-align: left;">পণ্যের চাহিদা বন্টন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($agentPointDemands as $point)
                                    <tr>
                                        <td class="align-middle text-start fw-bold text-dark">
                                            {{ $point['agent_point_name'] }}
                                        </td>
                                        <td class="align-middle text-muted">{{ $point['area'] }}</td>
                                        <td class="align-middle fw-bold text-primary">{{ $point['total_cardholders'] }} জন</td>
                                        <td class="align-middle fw-bold">{{ $point['total_requests'] }} টি</td>
                                        <td class="align-middle text-start">
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($point['products'] as $pName => $pData)
                                                    <span class="badge bg-success-light text-dark border">
                                                        {{ $pName }}: <strong>{{ number_format($pData['requested'], 1) }} {{ $pData['unit'] }}</strong>
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            কোনো এজেন্ট পয়েন্টের ডাটা নেই।
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
