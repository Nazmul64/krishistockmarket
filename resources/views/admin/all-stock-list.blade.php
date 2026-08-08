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

                        @foreach ($all_stock as $item)

                        <!-- Single Stock -->
                        <div class="col-lg-4 col-12">
                            <div class="box pull-up" style="padding: 15px 10px;">
                                <div class="box-body">
                                    <h5 class="mb-0 d-flex align-items-center justify-content-between">
                                        <span class="text-uppercase fs-16 d-flex align-items-center product_img">
                                            <img class="w-100"
                                                src="{{ asset('upload/stock_images') }}/@if(!empty(getFirstImages($item->id)->image)){{getFirstImages($item->id)->image }}@endif"
                                                alt="Product">
                                        </span>
                                        <span class="badge badge-light">
                                            {{ \Carbon\Carbon::parse($item->published_date)->format('d/m/Y')}}
                                        </span>
                                    </h5>
                                    <br>
                                    <div class="d-flex justify-content-between">
                                        <p class="fs-16"> {{ $item->stock_name }}</p>
                                        <p class="fs-16">
                                            {{ $item->stock_quantity }} Pices
                                        </p>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <p class="fs-16">Status</p>
                                        <p class="fs-16">
                                            {{ $item->status }}
                                        </p>
                                    </div>
                                </div>
                                <div class="box-body p-0">
                                    <div id="spark{{ $item->id }}" class="stock"></div>
                                </div>
                                <a href="{{ route('admin.stock.edit', $item->id) }}"
                                    class="waves-effect waves-light btn btn-success mt-10 d-block w-p100">
                                    Edit Stock
                                </a>
                                <a href="{{ route('admin.stock.detials',$item->id) }}"
                                    class="waves-effect waves-light btn btn-success mt-10 d-block w-p100">
                                    See Details
                                </a>
                                <a href="{{ route('admin.stock.delete', $item->id) }}"
                                    onclick="return confirm('Are you sure you want to delete this stock?');"
                                    class="waves-effect waves-light btn btn-danger mt-10 d-block w-p100">
                                    Delete Stock
                                </a>
                            </div>
                        </div>
                        <!-- Single Stock -->
                        @endforeach


                    </div>


                    {{ $all_stock->links('pagination.index') }}


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

    var randomizeArray = function (arg) {
        var array = arg.slice();
        var currentIndex = array.length,
            temporaryValue, randomIndex;

        while (0 !== currentIndex) {

            randomIndex = Math.floor(Math.random() * currentIndex);
            currentIndex -= 1;

            temporaryValue = array[currentIndex];
            array[currentIndex] = array[randomIndex];
            array[randomIndex] = temporaryValue;
        }

        return array;
    }

    // window.Apex = {
    //     stroke: {
    //         width: 3
    //     },
    //     markers: {
    //         size: 0
    //     },
    //     tooltip: {
    //         fixed: {
    //             enabled: true,
    //         }
    //     }
    // };


    // @foreach ($all_stock as $item)

    //     var sellingPricesM{{$item->id}} = {!! json_encode(array_map('floatval', sellingPriceForChart($item->id))) !!};
    //     var BuyingPrice{{$item->id}} = {!! json_encode(array_map('floatval', buyingPriceForChart($item->id))) !!};
    //     var options = {
    //         series: [{
    //             name: "Selling Price",
    //             data: sellingPricesM{{$item->id}}
    //         },
    //         {
    //             name: "Buing Price",
    //             data: BuyingPrice{{$item->id}}
    //         }
    //     ],

    //         chart: {
    //             height: 150,
    //             type: 'line',
    //         zoom: {
    //             enabled: false
    //         }
    //         },
    //         dataLabels: {
    //             enabled: false
    //         },
    //         stroke: {
    //             curve: 'straight'
    //         },

    //         grid: {
    //         row: {
    //             colors: ['#f3f3f3', 'transparent'],
    //             opacity: 0.5
    //         },
    //         },
    //         xaxis: {
    //             categories: ['Previous Price', 'Previous Price', 'Previous Price', 'Previous Price', 'Previous Price', 'Last Price'],
    //         }
    //     };

    //     var chart{{ $item->id }} = new ApexCharts(document.querySelector("#spark{{ $item->id }}"), options);
    //     chart{{ $item->id }}.render();

    // @endforeach




    @foreach ($all_stock as $item)
    var sellingPrice{{$item->id}} = {!! json_encode(sellingPriceForChart($item->id)) !!};
    var buyingPrice{{$item->id}} = {!! json_encode(buyingPriceForChart($item->id)) !!};

    var sparkOptions{{$item->id}} = {
        chart: {
            type: 'area',
            height: 140,
            sparkline: {
                enabled: false
            },
            toolbar: {
                show: false
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3
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
        series: [
            {
                name: 'Selling Price',
                data: sellingPrice{{$item->id}}
            },
            {
                name: 'Buying Price',
                data: buyingPrice{{$item->id}}
            }
        ],
        colors: ['#e2bb33', '#28a745'],
        xaxis: {
            labels: { show: false },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            labels: { show: true }
        },
        tooltip: {
            theme: 'dark',
            x: { show: false }
        },
        legend: {
            show: true,
            position: 'top',
            horizontalAlign: 'right'
        }
    };
    var sparkChart{{$item->id}} = new ApexCharts(document.querySelector("#spark{{ $item->id }}"), sparkOptions{{$item->id}});
    sparkChart{{$item->id}}.render();
    @endforeach


</script>
@endsection
