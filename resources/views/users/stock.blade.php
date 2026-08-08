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
                        <div class="col-lg-6 col-md-6 col-12 mb-4">
                            <div class="box pull-up shadow-sm" style="padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; background: #fff;">
                                <div class="box-body p-0 mb-3">
                                    <div class="position-relative overflow-hidden mb-3" style="border-radius: 8px; background: #f8fafc;">
                                        @php
                                            $first_img = getFirstImages($item->id);
                                            $img_src = !empty($first_img->image) ? asset('upload/stock_images/' . $first_img->image) : asset('upload/images/backend/logo/' . setting('logo'));
                                        @endphp
                                        <img src="{{ $img_src }}" alt="{{ $item->stock_name }}" style="width: 100%; height: 230px; object-fit: contain; border-radius: 8px; padding: 10px;">
                                    </div>
                                     <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 18px; font-weight: 700; color: #1e293b;">{{ $item->stock_name }}</h5>
                                        @php
                                            $lastPrice = StockLastPrice($item->id);
                                        @endphp
                                        @if($lastPrice)
                                            <span class="badge bg-success" style="font-size: 15px; font-weight: 700; padding: 6px 14px; border-radius: 8px;">
                                                ৳{{ number_format($lastPrice->selling_price, 2) }}
                                            </span>
                                        @endif
                                     </div>
                                     <div class="d-flex justify-content-between align-items-center">
                                         <span class="badge bg-light text-primary border" style="font-size: 13px; font-weight: 600; padding: 6px 14px; border-radius: 20px; color: #1b88ce !important;">
                                             <i class="fa fa-cubes me-1"></i> Available Stock: {{ $item->stock_quantity }} Pices
                                         </span>
                                     </div>
                                </div>
                                <div class="box-body p-0 mb-3">
                                    <div id="spark{{ $item->id }}" class="stock"></div>
                                </div>

                                <div class="row g-2 mt-2">
                                    <div class="col-6">
                                        @if ($item->stock_quantity == "0")
                                            <button class="btn btn-secondary disabled w-100 py-2.5 fw-bold" style="border-radius: 6px; font-size: 14px;">
                                                Out Of Stock
                                            </button>
                                        @else
                                            <a href="{{ route('my.cart.add', $item->id) }}" class="btn btn-success w-100 py-2.5 fw-bold d-block text-center" style="border-radius: 6px; font-size: 14px; background: #28a745; border: none;">
                                                <i class="fa fa-shopping-cart me-1"></i> Add to cart
                                            </a>
                                        @endif
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('stock.detials', $item->id) }}" class="btn btn-primary w-100 py-2.5 fw-bold d-block text-center" style="border-radius: 6px; font-size: 14px; background: #1b88ce; border: none;">
                                            <i class="fa fa-eye me-1"></i> See Details
                                        </a>
                                    </div>
                                </div>
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
