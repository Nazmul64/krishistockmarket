@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content p-2 p-md-4">
            <div class="row g-2 g-md-4 mx-0">
                @forelse ($all_buy_stock as $item)
                @php
                    $imgObj = getFirstImages($item->stock_id);
                    $stockInfo = SingleStockInfo($item->stock_id);
                    $imgSrc = ($imgObj && !empty($imgObj->image)) ? asset('upload/stock_images/' . $imgObj->image) : asset('upload/images/backend/logo/' . setting('logo'));
                @endphp
                <!-- Single Stock Purchased Card (Desktop 3 per row, Mobile 1-2 per row) -->
                <div class="col-xl-4 col-lg-4 col-md-6 col-12 px-2 mb-3 mb-md-4">
                    <div class="box pull-up mb-0" style="width: 100%; border-radius: 10px; background: #ffffff; padding: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.06);">
                        
                        <!-- Product Image & Date Badge -->
                        <div class="text-center mb-2 position-relative" style="width: 100%;">
                            <span class="badge bg-light text-dark border shadow-sm" style="position: absolute; top: 8px; right: 8px; z-index: 3; font-size: 11.5px; font-weight: 600; padding: 4px 8px; border-radius: 6px;">
                                <i class="fa fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                            </span>
                            <img src="{{ $imgSrc }}" alt="{{ $stockInfo->stock_name ?? 'Product' }}" class="w-100" style="border-radius: 6px; display: block;">
                        </div>

                        <!-- Product Title & Quantity -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0 fw-bold" style="font-size: 15px; line-height: 1.3;" title="{{ $stockInfo->stock_name ?? 'Stock Package' }}">
                                {{ $stockInfo->stock_name ?? 'Stock Package' }}
                            </h5>
                            <span class="badge bg-primary" style="font-size: 12px; padding: 4px 8px; border-radius: 5px;">
                                {{ $item->buy_quantiy }} Pieces
                            </span>
                        </div>

                        <!-- Buying Price -->
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-light">
                            <span class="text-muted fw-semibold" style="font-size: 13px;">Buying Price:</span>
                            <span class="badge" style="background-color: #0284c7; color: #ffffff !important; font-size: 13px; font-weight: 700; padding: 5px 10px; border-radius: 6px;">
                                ক্রয় ৳{{ number_format($item->buyed_price, 0) }}
                            </span>
                        </div>

                        <!-- Buy Status Button/Badge -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted fw-semibold" style="font-size: 13px;">Buy Status:</span>
                            <div>
                                @if ($item->status == 'pending')
                                    <span class="badge shadow-sm fw-bold px-3 py-2" style="background-color: #f59e0b; color: #ffffff !important; font-size: 12px; border-radius: 20px; letter-spacing: 0.5px;">
                                        <i class="fa fa-clock-o me-1" style="color: #ffffff !important;"></i> PENDING
                                    </span>
                                @elseif ($item->status == 'aproved' || $item->status == 'approved')
                                    <span class="badge bg-success text-white px-3 py-2 fw-bold shadow-sm" style="font-size: 12px; border-radius: 20px; letter-spacing: 0.5px;">
                                        <i class="fa fa-check-circle me-1"></i> APPROVED
                                    </span>
                                @elseif ($item->status == 'rejected')
                                    <span class="badge bg-danger text-white px-3 py-2 fw-bold shadow-sm" style="font-size: 12px; border-radius: 20px; letter-spacing: 0.5px;">
                                        <i class="fa fa-times-circle me-1"></i> REJECTED
                                    </span>
                                @elseif ($item->status == 'sellpending')
                                    <span class="badge bg-info text-white px-3 py-2 fw-bold shadow-sm" style="font-size: 12px; border-radius: 20px; letter-spacing: 0.5px;">
                                        <i class="fa fa-hourglass-half me-1"></i> SELL PENDING
                                    </span>
                                @elseif ($item->status == 'sellaproved')
                                    <span class="badge bg-secondary text-white px-3 py-2 fw-bold shadow-sm" style="font-size: 12px; border-radius: 20px; letter-spacing: 0.5px;">
                                        <i class="fa fa-check me-1"></i> SOLD
                                    </span>
                                @else
                                    <span class="badge bg-dark text-white px-3 py-2 fw-bold" style="font-size: 12px; border-radius: 20px;">
                                        {{ strtoupper($item->status) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Sparkline ApexChart Graph -->
                        <div class="box-body p-0 mb-3" style="width: 100%;">
                            <div id="spark{{ $item->id }}" class="stock" style="min-height: 135px; width: 100%;"></div>
                        </div>

                        <!-- Action Button -->
                        <div class="pt-2 border-top" style="width: 100%;">
                            @if ($item->status == "pending")
                                <button type="button" class="btn btn-warning text-dark w-100 py-2 fw-bold shadow-sm" style="font-size: 13px; border-radius: 6px;" disabled>
                                    <i class="fa fa-clock-o me-1"></i> Wait For Approved
                                </button>
                            @elseif ($item->status == "rejected")
                                <button type="button" class="btn btn-danger w-100 py-2 fw-bold shadow-sm" style="font-size: 13px; border-radius: 6px;" disabled>
                                    <i class="fa fa-times-circle me-1"></i> Rejected
                                </button>
                            @elseif ($item->status == "sellpending")
                                <button type="button" class="btn btn-info text-white w-100 py-2 fw-bold shadow-sm" style="font-size: 13px; border-radius: 6px;" disabled>
                                    <i class="fa fa-hourglass-half me-1"></i> Pending Sell Request
                                </button>
                            @elseif($item->status == "sellaproved")
                                <button type="button" class="btn btn-secondary w-100 py-2 fw-bold shadow-sm" style="font-size: 13px; border-radius: 6px;" disabled>
                                    <i class="fa fa-check-circle me-1"></i> Sold
                                </button>
                            @else
                                <a href="{{ route('user.stock.sell.request', $item->id) }}" class="btn btn-success w-100 py-2 fw-bold d-flex align-items-center justify-content-center shadow-sm" style="font-size: 13px; background-color: #28a745; border-color: #28a745; border-radius: 6px;">
                                    <i class="fa fa-paper-plane me-2"></i> Sell Now
                                </a>
                            @endif
                        </div>

                    </div>
                </div>
                <!-- Single Stock -->
                @empty
                <div class="col-12 text-center py-5">
                    <div class="box p-5 bg-white rounded shadow-sm text-center">
                        <i class="fa fa-shopping-basket text-muted mb-3" style="font-size: 48px;"></i>
                        <h4 class="fw-bold text-dark">কোনো স্টক ক্রয় করা হয়নি</h4>
                        <p class="text-muted">আপনি এখনো কোনো স্টক কেনেননি। নতুন স্টক কেনার জন্য স্টক পেজে যান।</p>
                        <a href="{{ route('stock.index') }}" class="btn btn-success px-4 py-2 fw-bold">
                            <i class="fa fa-shopping-cart me-1"></i> স্টক ব্রাউজ করুন
                        </a>
                    </div>
                </div>
                @endforelse

            </div>
        </section>
        <!-- /.content -->
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    window.Apex = {
        stroke: {
            width: 3
        },
        markers: {
            size: 0
        },
        tooltip: {
            fixed: {
                enabled: true,
            }
        }
    };

    @foreach ($all_buy_stock as $item)
    @php
        $chartData = stockFullPriceHistory($item->stock_id);
    @endphp
    var datesM{{$item->id}} = {!! json_encode($chartData['dates']) !!};
    var sellingPricesM{{$item->id}} = {!! json_encode($chartData['sellingPrices']) !!};

    var options{{ $item->id }} = {
        series: [{
            name: "Selling Price",
            data: sellingPricesM{{$item->id}}
        }],
        chart: {
            height: 140,
            type: 'line',
            toolbar: { show: false }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        markers: {
            size: 4,
            colors: ['#28a745'],
            strokeColors: '#ffffff',
            strokeWidth: 2,
            hover: {
                size: 6
            }
        },
        colors: ['#28a745'],
        grid: {
            borderColor: '#e2e8f0',
            strokeDashArray: 4
        },
        xaxis: {
            categories: datesM{{$item->id}},
            labels: {
                style: {
                    colors: '#64748b',
                    fontSize: '11px',
                    fontWeight: 600
                }
            }
        },
        yaxis: {
            labels: {
                formatter: function (val) {
                    return '৳' + val.toFixed(0);
                }
            }
        },
        tooltip: {
            theme: 'dark',
            y: {
                formatter: function (val) {
                    return '৳' + val.toFixed(2);
                }
            }
        }
    };

    var chart{{ $item->id }} = new ApexCharts(document.querySelector("#spark{{ $item->id }}"), options{{ $item->id }});
    chart{{ $item->id }}.render();
    @endforeach
</script>
@endsection
