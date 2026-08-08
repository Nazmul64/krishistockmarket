
@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="col-xl-12 col-12">
                    <div class="row">

                        @foreach ($all_buy_stock as $item)
                        <!-- Single Stock -->
                        <div class="col-lg-4 col-12">
                            <div class="box pull-up" style="padding: 15px 10px;">
                                <div class="box-body">
                                    <h5 class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-uppercase fs-16 d-flex align-items-center product_img">
                                            <img class="w-100"
                                                src="{{ asset('upload/stock_images') }}/{{ getFirstImages($item->stock_id)->image }}"
                                                alt="Product">
                                        </span>
                                        <span class="badge badge-light">
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y')}}
                                        </span>
                                    </h5>
                                    <br>
                                    <div class="d-flex justify-content-between">
                                        <p class="fs-16"> {{ SingleStockInfo($item->stock_id)->stock_name }}</p>
                                        <p class="fs-16">
                                            {{ $item->buy_quantiy }} Pices
                                        </p>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <p class="fs-16"> Buying Price</p>
                                        <p class="fs-16">
                                            {{ $item->buyed_price }} TK

                                        </p>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <p class="fs-16"> Buy Status</p>
                                        <p class="fs-16">
                                            @php
                                               echo strtoupper("$item->status");
                                            @endphp
                                        </p>
                                    </div>
                                </div>
                                <div class="box-body p-0">
                                    <div id="spark{{ $item->id }}" class="stock"></div>
                                </div>
                                @if ($item->status == "pending")
                                    <a href="#" class="waves-effect waves-light btn btn-danger mt-10 d-block w-p100">Wait For Aproved</a>
                                @elseif ($item->status == "rejected")
                                    <a href="#" class="waves-effect waves-light btn btn-danger mt-10 d-block w-p100">Rejected</a>
                                @elseif ($item->status == "sellpending")
                                    <a href="#" class="waves-effect waves-light btn btn-danger mt-10 d-block w-p100">Pending Sell Request</a>
                                @elseif($item->status == "sellaproved")
                                    <a href="#" class="waves-effect waves-light btn btn-success mt-10 d-block w-p100">Seled</a>
                                @else
                                    <a href="{{ route('user.stock.sell.request', $item->id) }}" class="waves-effect waves-light btn btn-info mt-10 d-block w-p100">Sell Now</a>
                                @endif
                            </div>
                        </div>
                        <!-- Single Stock -->
                        @endforeach

                    </div>


                </div>

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

    var sellingPricesM{{$item->id}} = {!! json_encode(array_map('floatval', sellingPriceForChart($item->stock_id))) !!};

    var options = {
          series: [{
            name: "Selling Price",
            data: sellingPricesM{{$item->id}}
        }],
          chart: {
          height: 150,
          type: 'line',
          zoom: {
            enabled: false
          }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'straight'
        },

        grid: {
          row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
          },
        },
        xaxis: {
          categories: ['Previous Price', 'Previous Price', 'Previous Price', 'Previous Price', 'Previous Price', 'Last Price'],
        }
        };

        var chart{{ $item->id }} = new ApexCharts(document.querySelector("#spark{{ $item->id }}"), options);
        chart{{ $item->id }}.render();


    @endforeach

</script>
@endsection
