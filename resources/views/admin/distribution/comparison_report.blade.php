@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-line-chart text-primary me-2"></i>Agent Point Distribution Performance & Comparison Report
                    </h3>
                    <p class="text-muted mb-0">কেন্দ্রীয় অ্যাডমিন প্যানেল: সকল এজেন্ট পয়েন্টের বিতরণ কর্মক্ষমতা, সেবাদানের অগ্রগতি ও তুলনামূলক র‍্যাংকিং ({{ $currentMonth }} {{ $currentYear }})</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.distribution.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ডে ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @php
                $totalPoints = $comparisonData->count();
                $totalMembersAll = $comparisonData->sum('total_members');
                $totalServedMonthAll = $comparisonData->sum('served_this_month');
                $totalValueMonthAll = $comparisonData->sum('distribution_value');
                $overallCompletionRate = $totalMembersAll > 0 ? round(($totalServedMonthAll / $totalMembersAll) * 100, 1) : 0;
            @endphp

            <!-- 4 Top KPI Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                        <div class="text-muted font-size-11 text-uppercase fw-semibold">সক্রিয় এজেন্ট পয়েন্ট (Active Points)</div>
                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ number_format($totalPoints) }} টি</h3>
                        <small class="text-muted">নেটওয়ার্ক কাভারেজ পয়েন্ট</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                        <div class="text-muted font-size-11 text-uppercase fw-semibold">মোট নির্ধারিত কার্ডহোল্ডার (Assigned)</div>
                        <h3 class="fw-bold text-info mb-0 mt-1">{{ number_format($totalMembersAll) }} জন</h3>
                        <small class="text-muted">পয়েন্টসমূহে নিবন্ধিত সদস্য</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                        <div class="text-muted font-size-11 text-uppercase fw-semibold">চলতি মাসে বাজার গ্রহণকারী (Served)</div>
                        <h3 class="fw-bold text-success mb-0 mt-1">{{ number_format($totalServedMonthAll) }} জন</h3>
                        <small class="text-muted">চলতি মাসের বিতরণ অগ্রগতি: {{ $overallCompletionRate }}%</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                        <div class="text-muted font-size-11 text-uppercase fw-semibold">মোট বিতরণ ভ্যালু (Total Value)</div>
                        <h3 class="fw-bold text-primary mb-0 mt-1">৳{{ number_format($totalValueMonthAll, 2) }}</h3>
                        <small class="text-muted">চলতি মাসের সর্বমোট বিতরণ মূল্য</small>
                    </div>
                </div>
            </div>

            <!-- Comparison Ranking Table -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="fa fa-trophy text-warning me-2"></i>এজেন্ট পয়েন্ট পারফরম্যান্স ও অগ্রগতি র‍্যাংকিং
                    </h5>
                    <span class="badge bg-light text-dark border">মাস: {{ $currentMonth }} {{ $currentYear }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 font-size-13">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3 text-center" style="width: 50px;">র‍্যাংক</th>
                                    <th>এজেন্ট পয়েন্ট ও কোড</th>
                                    <th>এলাকা / জেলা</th>
                                    <th class="text-center">মোট সদস্য</th>
                                    <th class="text-center">আজকের বিতরণ</th>
                                    <th class="text-center">চলতি মাসে বিতরণ</th>
                                    <th style="min-width: 160px;">বিতরণ অগ্রগতি (%)</th>
                                    <th class="text-end">মোট বিতরণ মূল্য</th>
                                    <th class="text-center">অবশিষ্ট বাকি সদস্য</th>
                                    <th class="text-center pe-3">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($comparisonData as $rank => $item)
                                    @php
                                        $completionRate = $item->total_members > 0 ? round(($item->served_this_month / $item->total_members) * 100, 1) : 0;
                                        $progressColor = $completionRate >= 80 ? 'bg-success' : ($completionRate >= 40 ? 'bg-primary' : 'bg-warning');
                                    @endphp
                                    <tr>
                                        <td class="ps-3 text-center">
                                            @if($rank == 0)
                                                <span class="badge bg-warning text-dark font-size-12 px-2 py-1"><i class="fa fa-trophy"></i> 1</span>
                                            @elseif($rank == 1)
                                                <span class="badge bg-secondary text-white font-size-12 px-2 py-1">2</span>
                                            @elseif($rank == 2)
                                                <span class="badge bg-dark text-white font-size-12 px-2 py-1">3</span>
                                            @else
                                                <span class="text-muted fw-bold">{{ $rank + 1 }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark font-size-14">{{ $item->point->name }}</div>
                                            <small class="text-muted">{{ $item->point->point_code }}</small>
                                        </td>
                                        <td>
                                            <div>{{ $item->point->area ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $item->point->district ?? '' }}</small>
                                        </td>
                                        <td class="text-center fw-bold font-size-14 text-dark">
                                            {{ number_format($item->total_members) }} জন
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-light text-info fw-bold font-size-12 px-2 py-1">
                                                {{ number_format($item->served_today) }} জন
                                            </span>
                                        </td>
                                        <td class="text-center fw-bold font-size-14 text-success">
                                            {{ number_format($item->served_this_month) }} জন
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <small class="fw-bold text-dark">{{ $completionRate }}%</small>
                                                <small class="text-muted">{{ $item->served_this_month }}/{{ $item->total_members }}</small>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar {{ $progressColor }}" role="progressbar" style="width: {{ min(100, $completionRate) }}%;"></div>
                                            </div>
                                        </td>
                                        <td class="text-end fw-bold text-primary font-size-14">
                                            ৳{{ number_format($item->distribution_value, 2) }}
                                        </td>
                                        <td class="text-center">
                                            @if($item->pending_members > 0)
                                                <span class="badge bg-danger-light text-danger fw-bold font-size-12 px-2 py-1">
                                                    {{ number_format($item->pending_members) }} জন বাকি
                                                </span>
                                            @else
                                                <span class="badge bg-success-light text-success fw-bold font-size-12 px-2 py-1">
                                                    <i class="fa fa-check me-1"></i>সম্পূর্ণ
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center pe-3">
                                            <a href="{{ route('admin.distribution.point_summary', $item->point->id) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fa fa-bar-chart me-1"></i> সামারি
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4 text-muted">
                                            কোনো এজেন্ট পয়েন্টের তথ্য পাওয়া যায়নি।
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
