@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Stock Details</h4>
                </div>

            </div>
        </div>


        <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="col-lg-6">
                    <div class="box">
                        <div class="box-header">
                            <h4 class="mb-0 fw-500">Product Details</h4>
                        </div>

                        <div class="box-body">
                            <div class="row mb-30">
                                <div class="col-12 col-lg-12">
                                    <h5 class="p-15 mb-0 justify-content-between d-flex" >
                                        <strong>Name:</strong>
                                        <span class="text-success">{{ $stock_info->stock_name }}</span>
                                    </h5>
                                </div>
                                <div class="col-12 col-lg-12">
                                    <h5 class="p-15 mb-0 justify-content-between d-flex" >
                                        <strong>Published Date:</strong>
                                        <span class="text-success">
                                            {{ \Carbon\Carbon::parse($stock_info->published_date)->format('d/m/Y')}}
                                        </span>
                                    </h5>
                                </div>

                                <div class="col-12 col-lg-12">
                                    <h5 class="p-15 mb-0 justify-content-between d-flex" >
                                        <strong>Current Buying Price:</strong>
                                        <span class="text-success">{{ StockLastPricing($stock_info->id)->buying_price }}</span>
                                    </h5>
                                </div>
                                <div class="col-12 col-lg-12">
                                    <h5 class="p-15 mb-0 justify-content-between d-flex" >
                                        <strong>Current Salling Price:</strong>
                                        <span class="text-success">{{ StockLastPricing($stock_info->id)->selling_price }}</span>
                                    </h5>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                Product Description
                            </h4>
                        </div>
                        <div class="box-body">
                            <div>
                                {!! $stock_info->description !!}
                            </div>

                            <div class="box-header">
                                <h4 class="mb-0 fw-500">View Images</h4>
                            </div>
                            <div class="popup-gallery p-15">
                                @foreach ($images as $image)
                                <a href="{{ asset('upload/stock_images') }}/{{ $image->image }}"
                                    title="{{ $stock_info->stock_name }}">
                                            <img src="{{ asset('upload/stock_images') }}/{{ $image->image }}" class="w-p20" alt="" />
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interactive Price History Chart -->
                <div class="col-12 mt-20">
                    <div class="box">
                        <div class="box-header with-border d-flex justify-content-between align-items-center">
                            <h4 class="box-title"><i class="fa fa-line-chart text-success me-2"></i> Price History & Market Trend (মূল্য ওঠানামার গ্রাফ চার্ট)</h4>
                            <span class="badge badge-success p-2">Interactive Stock Chart</span>
                        </div>
                        <div class="box-body">
                            <div id="stock-detail-chart-{{ $stock_info->id }}" style="min-height: 380px;"></div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
        <!-- /.content -->

    </div>
</div>
<!-- /.content-wrapper -->
@endsection

@section('script')
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        @php
            $chartData = stockFullPriceHistory($stock_info->id);
        @endphp

        var dates = {!! json_encode($chartData['dates']) !!};
        var sellingPrices = {!! json_encode($chartData['sellingPrices']) !!};
        var buyingPrices = {!! json_encode($chartData['buyingPrices']) !!};

        var options = {
            series: [
                {
                    name: 'Selling Price (বিক্রয় মূল্য)',
                    data: sellingPrices
                },
                {
                    name: 'Buying Price (ক্রয় মূল্য)',
                    data: buyingPrices
                }
            ],
            chart: {
                type: 'area',
                height: 400,
                zoom: {
                    enabled: true,
                    type: 'x',
                    autoScaleYaxis: true
                },
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: true,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: true
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3.5
            },
            markers: {
                size: 6,
                colors: ['#28a745', '#e2bb33'],
                strokeColors: '#ffffff',
                strokeWidth: 2,
                hover: {
                    size: 9
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            colors: ['#28a745', '#e2bb33'],
            xaxis: {
                categories: dates,
                title: {
                    text: 'Date',
                    style: { color: '#64748b', fontWeight: 600 }
                },
                labels: {
                    style: { color: '#64748b', fontWeight: 600 }
                }
            },
            yaxis: {
                title: {
                    text: 'Price (BDT)',
                    style: { color: '#64748b', fontWeight: 600 }
                },
                labels: {
                    style: { color: '#64748b', fontWeight: 600 },
                    formatter: function (val) {
                        return '৳' + val.toFixed(2);
                    }
                }
            },
            tooltip: {
                shared: true,
                intersect: false,
                theme: 'dark',
                y: {
                    formatter: function (val) {
                        return '৳' + val.toFixed(2);
                    }
                }
            },
            grid: {
                borderColor: '#e2e8f0',
                strokeDashArray: 4
            },
            legend: {
                position: 'top',
                horizontalAlign: 'center',
                fontSize: '15px',
                fontWeight: 600
            }
        };

        var chart = new ApexCharts(document.querySelector("#stock-detail-chart-{{ $stock_info->id }}"), options);
        chart.render();
    });
</script>
@endsection
