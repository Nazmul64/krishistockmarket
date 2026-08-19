@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title">Area-wise Demand & Distribution Report (এলাকা ও এজেন্টে পয়েন্ট চাহিদা ও বিতরণ রিপোর্ট)</h4>
                    <p class="text-muted mb-0">এলাকাভিত্তিক Card Holder সংখ্যা, পণ্যের মোট চাহিদা, বরাদ্দ ও বিতরণকৃত হিসাব রিপোর্ট</p>
                </div>
                <div>
                    <button onclick="window.print()" class="btn btn-secondary btn-sm me-2"><i class="fa fa-print me-1"></i> প্রিন্ট / PDF</button>
                    <a href="{{ route('admin.monthly_bazaar.orders') }}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left me-1"></i> রিকোয়েস্ট তালিকায় ফিরে যান</a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <!-- Filter Bar (No print) -->
            <div class="row mb-4 no-print">
                <div class="col-12">
                    <div class="card border shadow-sm">
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.monthly_bazaar.distribution_reports') }}" class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">এলাকা (Area):</label>
                                    <select name="request_area" class="form-select">
                                        <option value="">-- সকল এলাকা (All Areas) --</option>
                                        @foreach($areas as $ar)
                                            <option value="{{ $ar }}" {{ request('request_area') == $ar ? 'selected' : '' }}>{{ $ar }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">শুরুর তারিখ:</label>
                                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">শেষের তারিখ:</label>
                                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-search me-1"></i> ফিল্টার রিপোর্ট</button>
                                    <a href="{{ route('admin.monthly_bazaar.distribution_reports') }}" class="btn btn-outline-secondary"><i class="fa fa-refresh"></i> রিসেট</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold"><i class="fa fa-file-text-o me-2"></i> এলাকা ও Agent Point চাহিদা ও বিতরণ সারসংক্ষেপ (Area-wise Demand & Distribution Summary)</h5>
                            <span class="badge bg-light text-dark">{{ count($reportData) }} Regions Listed</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped align-middle mb-0">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th>#</th>
                                            <th>রিকোয়েস্ট এলাকা (Request Area)</th>
                                            <th>সংগ্রহের Agent Point</th>
                                            <th class="text-center">মোট Card Holder (সংখ্যার সদস্য)</th>
                                            <th class="text-center">মোট রিকোয়েস্ট সংখ্যা</th>
                                            <th class="text-center">মোট পণ্য চাহিদা (Package Demand Qty)</th>
                                            <th class="text-center">বরাদ্দকৃত পরিমাণ (Allocated Qty)</th>
                                            <th class="text-center">বিতরণকৃত পরিমাণ (Distributed Qty)</th>
                                            <th class="text-center">অপেক্ষমাণ (Pending Qty)</th>
                                            <th class="text-end">মোট মূল্য (Total Amount ৳)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalCardholders = 0;
                                            $totalRequests = 0;
                                            $totalDemandQty = 0;
                                            $totalAllocatedQty = 0;
                                            $totalDistributedQty = 0;
                                            $totalPendingQty = 0;
                                            $grandTotalAmount = 0;
                                        @endphp
                                        @forelse($reportData as $index => $row)
                                            @php
                                                $totalCardholders += $row['total_cardholders'];
                                                $totalRequests += $row['total_requests'];
                                                $totalDemandQty += $row['total_demand_qty'];
                                                $totalAllocatedQty += $row['allocated_qty'];
                                                $totalDistributedQty += $row['distributed_qty'];
                                                $totalPendingQty += $row['pending_qty'];
                                                $grandTotalAmount += $row['total_amount'];
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><strong class="text-primary fs-15"><i class="fa fa-map-marker me-1"></i> {{ $row['area'] }}</strong></td>
                                                <td><span class="badge bg-dark"><i class="fa fa-building me-1"></i> {{ $row['agent_point'] }}</span></td>
                                                <td class="text-center fw-bold">{{ number_format($row['total_cardholders']) }} জন</td>
                                                <td class="text-center"><span class="badge bg-info fs-13">{{ $row['total_requests'] }} টি</span></td>
                                                <td class="text-center text-primary fw-bold fs-14">{{ $row['total_demand_qty'] }} টি</td>
                                                <td class="text-center text-success fw-bold fs-14">{{ $row['allocated_qty'] }} টি</td>
                                                <td class="text-center text-secondary fw-bold">{{ $row['distributed_qty'] }} টি</td>
                                                <td class="text-center text-warning fw-bold">{{ $row['pending_qty'] }} টি</td>
                                                <td class="text-end fw-bold text-success fs-14">৳{{ number_format($row['total_amount'], 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center py-4 text-muted">কোনো Area-wise চাহিদার তথ্য পাওয়া যায়নি।</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="bg-light">
                                        <tr class="fw-bold fs-15">
                                            <td colspan="3" class="text-end text-dark">সর্বমোট (Grand Total):</td>
                                            <td class="text-center text-primary">{{ number_format($totalCardholders) }} জন</td>
                                            <td class="text-center text-info">{{ number_format($totalRequests) }} টি</td>
                                            <td class="text-center text-primary">{{ number_format($totalDemandQty) }} টি</td>
                                            <td class="text-center text-success">{{ number_format($totalAllocatedQty) }} টি</td>
                                            <td class="text-center text-secondary">{{ number_format($totalDistributedQty) }} টি</td>
                                            <td class="text-center text-warning">{{ number_format($totalPendingQty) }} টি</td>
                                            <td class="text-end text-success">৳{{ number_format($grandTotalAmount, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<style>
@media print {
    .no-print, .content-header, header, sidebar, footer { display: none !important; }
    .content-wrapper { margin-left: 0 !important; padding: 0 !important; }
    body { background: white; font-size: 12px; }
}
</style>
@endsection
