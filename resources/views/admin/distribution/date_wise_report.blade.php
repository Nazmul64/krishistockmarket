@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-calendar text-info me-2"></i>Date-wise Customer Collection & Distribution Report
                    </h3>
                    <p class="text-muted mb-0">কেন্দ্রীয় অ্যাডমিন প্যানেল: তারিখ ও কাস্টম ফিল্টার অনুযায়ী সকল পয়েন্টের গ্রাহক বিতরণ ট্রানজেকশন নিরীক্ষণ</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.distribution.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ডে ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Filter Bar -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('admin.distribution.date_wise_report') }}" class="row g-2 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label font-size-12 fw-semibold mb-1">তারিখ প্রিসেট:</label>
                            <select name="date_preset" class="form-select form-select-sm" id="presetSelect" onchange="toggleCustomDates(this.value)">
                                <option value="today" {{ $preset == 'today' ? 'selected' : '' }}>আজ (Today)</option>
                                <option value="yesterday" {{ $preset == 'yesterday' ? 'selected' : '' }}>গতকাল (Yesterday)</option>
                                <option value="last_7_days" {{ $preset == 'last_7_days' ? 'selected' : '' }}>বিগত ৭ দিন (7 Days)</option>
                                <option value="last_30_days" {{ $preset == 'last_30_days' ? 'selected' : '' }}>বিগত ৩০ দিন (30 Days)</option>
                                <option value="this_month" {{ $preset == 'this_month' ? 'selected' : '' }}>চলতি মাস (This Month)</option>
                                <option value="custom" {{ ($fromDate && $toDate) || $preset == 'custom' ? 'selected' : '' }}>কাস্টম রেঞ্জ (Custom)</option>
                            </select>
                        </div>
                        <div class="col-md-2" id="fromDateCol">
                            <label class="form-label font-size-12 fw-semibold mb-1">হতে (From):</label>
                            <input type="date" name="from_date" class="form-control form-control-sm" value="{{ $fromDate }}">
                        </div>
                        <div class="col-md-2" id="toDateCol">
                            <label class="form-label font-size-12 fw-semibold mb-1">পর্যন্ত (To):</label>
                            <input type="date" name="to_date" class="form-control form-control-sm" value="{{ $toDate }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label font-size-12 fw-semibold mb-1">এজেন্ট পয়েন্ট:</label>
                            <select name="agent_point_id" class="form-select form-select-sm">
                                <option value="">সকল পয়েন্ট (All Points)</option>
                                @foreach($agentPoints as $pt)
                                    <option value="{{ $pt->id }}" {{ request('agent_point_id') == $pt->id ? 'selected' : '' }}>{{ $pt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label font-size-12 fw-semibold mb-1">বাজারের ধরণ:</label>
                            <select name="market_type" class="form-select form-select-sm">
                                <option value="">সকল বাজার (All Types)</option>
                                <option value="monthly_market" {{ request('market_type') == 'monthly_market' ? 'selected' : '' }}>মাসিক বাজার</option>
                                <option value="weekly_market" {{ request('market_type') == 'weekly_market' ? 'selected' : '' }}>সাপ্তাহিক বাজার</option>
                                <option value="special_market" {{ request('market_type') == 'special_market' ? 'selected' : '' }}>বিশেষ বাজার</option>
                                <option value="emergency_market" {{ request('market_type') == 'emergency_market' ? 'selected' : '' }}>জরুরি বাজার</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label font-size-12 fw-semibold mb-1">স্ট্যাটাস:</label>
                            <select name="collection_status" class="form-select form-select-sm">
                                <option value="">সকল স্ট্যাটাস</option>
                                <option value="fully_collected" {{ request('collection_status') == 'fully_collected' ? 'selected' : '' }}>সম্পূর্ণ সংগৃহীত</option>
                                <option value="partially_collected" {{ request('collection_status') == 'partially_collected' ? 'selected' : '' }}>আংশিক সংগৃহীত</option>
                            </select>
                        </div>
                        <div class="col-md-9">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="ভাউচার নং, কার্ড নং, গ্রাহকের নাম বা মোবাইল নম্বর দিয়ে খুঁজুন..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                <i class="fa fa-filter me-1"></i> ফিল্টার প্রয়োগ
                            </button>
                            <a href="{{ route('admin.distribution.date_wise_report') }}" class="btn btn-light btn-sm border">
                                <i class="fa fa-refresh"></i> রিসেট
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 3 Summary Metrics -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                        <div class="text-muted font-size-11 text-uppercase fw-semibold">ফিল্টার অনুযায়ী মোট ট্রানজেকশন</div>
                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ number_format($totalCount) }} টি ভাউচার</h3>
                        <small class="text-muted">গ্রাহক বিতরণ সফলভাবে সম্পন্ন</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                        <div class="text-muted font-size-11 text-uppercase fw-semibold">মোট বিতরণ ভলিউম</div>
                        <h3 class="fw-bold text-success mb-0 mt-1">{{ number_format($totalDistributedVolume, 1) }}</h3>
                        <small class="text-muted">মোট বিতরণকৃত পণ্যের পরিমাণ</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                        <div class="text-muted font-size-11 text-uppercase fw-semibold">মোট বিতরণ মূল্য</div>
                        <h3 class="fw-bold text-primary mb-0 mt-1">৳{{ number_format($totalDistributionRevenue, 2) }}</h3>
                        <small class="text-muted">হিসাবভুক্ত মোট টাকার অংক</small>
                    </div>
                </div>
            </div>

            <!-- Collections Table -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="fa fa-list text-primary me-2"></i>গ্রাহক বিতরণ ভাউচার খতিয়ান
                    </h5>
                    <span class="badge bg-light text-dark border">রেকর্ড: {{ $collections->total() }} টি</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 font-size-13">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">তারিখ ও সময়</th>
                                    <th>ভাউচার / ট্রানজেকশন</th>
                                    <th>গ্রাহক ও কার্ড নম্বর</th>
                                    <th>এজেন্ট পয়েন্ট</th>
                                    <th>বাজারের ধরণ</th>
                                    <th class="text-center">আইটেম সংখ্যা</th>
                                    <th class="text-end">মোট পরিমাণ</th>
                                    <th class="text-end">মোট মূল্য</th>
                                    <th class="text-center">স্ট্যাটাস</th>
                                    <th class="text-center pe-3">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($collections as $col)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-semibold text-dark">{{ date('d M, Y', strtotime($col->collection_date)) }}</div>
                                            <small class="text-muted">{{ $col->collection_time ? date('h:i A', strtotime($col->collection_time)) : '' }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-primary">{{ $col->collection_no }}</div>
                                            <small class="text-muted">{{ $col->transaction_id }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $col->customer_name }}</div>
                                            <span class="badge bg-light text-dark border font-size-11">
                                                <i class="fa fa-id-card-o text-muted me-1"></i>{{ $col->card_number ?? 'N/A' }}
                                            </span>
                                            <div class="text-muted font-size-11 mt-1">{{ $col->customer_phone ?? '' }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $col->agentPoint->name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $col->agentPoint->point_code ?? '' }}</small>
                                        </td>
                                        <td>
                                            @php
                                                $types = [
                                                    'monthly_market' => ['bg' => 'bg-primary-light text-primary', 'label' => 'মাসিক বাজার'],
                                                    'weekly_market'  => ['bg' => 'bg-info-light text-info', 'label' => 'সাপ্তাহিক বাজার'],
                                                    'special_market' => ['bg' => 'bg-warning-light text-warning', 'label' => 'বিশেষ বাজার'],
                                                    'emergency_market' => ['bg' => 'bg-danger-light text-danger', 'label' => 'জরুরি বাজার'],
                                                    'additional_collection' => ['bg' => 'bg-secondary-light text-secondary', 'label' => 'অতিরিক্ত সংগ্রহ'],
                                                ];
                                                $cfg = $types[$col->market_type] ?? ['bg' => 'bg-light text-dark', 'label' => ucfirst(str_replace('_', ' ', $col->market_type))];
                                            @endphp
                                            <span class="badge {{ $cfg['bg'] }} font-size-11 fw-bold">
                                                {{ $cfg['label'] }}
                                            </span>
                                        </td>
                                        <td class="text-center fw-bold">{{ $col->total_items_count ?? $col->items->count() }} টি</td>
                                        <td class="text-end fw-bold text-success font-size-14">{{ number_format($col->total_distributed_qty, 1) }}</td>
                                        <td class="text-end fw-bold text-dark font-size-14">৳{{ number_format($col->total_amount, 2) }}</td>
                                        <td class="text-center">
                                            @if($col->collection_status == 'fully_collected')
                                                <span class="badge bg-success-light text-success fw-bold font-size-11">
                                                    <i class="fa fa-check-circle me-1"></i>সম্পূর্ণ সংগৃহীত
                                                </span>
                                            @else
                                                <span class="badge bg-warning-light text-warning fw-bold font-size-11">
                                                    <i class="fa fa-hourglass-half me-1"></i>আংশিক সংগৃহীত
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center pe-3">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.distribution.transaction_details', $col->id) }}" class="btn btn-outline-info" title="ট্রানজেকশন বিস্তারিত">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.distribution.print_receipt', $col->id) }}" target="_blank" class="btn btn-outline-secondary" title="রসিদ প্রিন্ট">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4 text-muted">
                                            নির্বাচিত ফিল্টারের সাথে কোনো বিতরণ ট্রানজেকশন পাওয়া যায়নি।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($collections->hasPages())
                        <div class="p-3 border-top d-flex justify-content-end">
                            {{ $collections->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
