@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content p-0 p-md-4">
            <div class="row g-1 g-md-4 mx-0">
                @foreach ($all_stock as $item)
                @php
                    $first_img = getFirstImages($item->id);
                    $img_src = !empty($first_img->image) ? asset('upload/stock_images/' . $first_img->image) : asset('upload/images/backend/logo/' . setting('logo'));
                    $lastPrice = StockLastPrice($item->id);
                @endphp

                <!-- Stock Product Card: 3 cards per row on Desktop (col-lg-4 col-xl-4), 2 cards per row on Mobile (col-6) -->
                <div class="col-xl-4 col-lg-4 col-md-6 col-6 px-1 px-md-3 mb-2 mb-md-4">
                    <div class="box pull-up mb-0" style="width: 100%; border-radius: 10px; background: #ffffff; padding: 8px 8px 12px 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.06);">
                        
                        <!-- Product Image (Clickable to details page, Natural Size w-100) with Top-Left Selling Price & Top-Right Wishlist -->
                        <div class="text-center mb-2 position-relative" style="width: 100%;">
                            <!-- Top-Left: Selling Price (বিক্রয় মূল্য) -->
                            @if($lastPrice)
                                <span class="badge bg-success" style="position: absolute; top: 6px; left: 6px; z-index: 3; font-size: 11px; font-weight: 700; padding: 4px 7px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.15);">
                                    বিক্রয় ৳{{ number_format($lastPrice->selling_price, 0) }}
                                </span>
                            @endif

                            <!-- Top-Right: Wishlist Heart Button (উইশলিস্ট বাটন) -->
                            <button type="button" class="btn btn-sm btn-wishlist-toggle" data-stock-id="{{ $item->id }}" data-stock-name="{{ $item->stock_name }}" style="position: absolute; top: 6px; right: 6px; z-index: 3; width: 30px; height: 30px; padding: 0; border-radius: 50%; background: #ffffff; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 4px rgba(0,0,0,0.1);" title="উইশলিস্ট">
                                <i class="fa fa-heart-o text-muted wishlist-heart-icon-{{ $item->id }}" style="font-size: 14px;"></i>
                            </button>

                            <a href="{{ route('stock.detials', $item->id) }}" class="d-block" title="Click to view details">
                                <img src="{{ $img_src }}" alt="{{ $item->stock_name }}" class="w-100" style="border-radius: 6px; display: block;">
                            </a>
                        </div>

                        <!-- Product Name (Clickable to details page - Line 1) -->
                        <div class="mb-1">
                            <h5 class="mb-0 fw-bold" style="font-size: 14px; line-height: 1.3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $item->stock_name }}">
                                <a href="{{ route('stock.detials', $item->id) }}" class="text-dark text-decoration-none">
                                    {{ $item->stock_name }}
                                </a>
                            </h5>
                        </div>

                        <!-- Buying Price & Stock Quantity Badge (Line 2 below title) -->
                        <div class="d-flex justify-content-between align-items-center mb-2 flex-nowrap gap-1" style="width: 100%;">
                            @if($lastPrice)
                                <span class="badge shadow-sm" style="background-color: #0284c7; color: #ffffff !important; font-size: 11.5px; font-weight: 700; padding: 4px 8px; border-radius: 6px; white-space: nowrap;" title="Buying Price (ক্রয় মূল্য)">
                                    ক্রয়: ৳{{ number_format($lastPrice->buying_price, 0) }}
                                </span>
                            @else
                                <span></span>
                            @endif

                            @if($item->is_unlimited)
                                <span class="badge bg-light text-success border border-success" style="font-size: 11px; font-weight: 600; padding: 4px 8px; border-radius: 6px; color: #16a34a !important; white-space: nowrap;" title="In Stock">
                                    <i class="fa fa-check-circle me-1"></i> In Stock
                                </span>
                            @else
                                <span class="badge bg-light text-primary border border-primary" style="font-size: 11px; font-weight: 600; padding: 4px 8px; border-radius: 6px; color: #0284c7 !important; white-space: nowrap;" title="In Stock Quantity">
                                    <i class="fa fa-cubes me-1"></i> {{ $item->stock_quantity }} In Stock
                                </span>
                            @endif
                        </div>

                        <!-- Price Trend ApexChart Container (High contrast, crystal clear graph) -->
                        <div class="box-body p-0 mb-2" style="width: 100%;">
                            <div id="spark{{ $item->id }}" class="stock" style="min-height: 135px; width: 100%;"></div>
                        </div>

                        <!-- Card Action Buttons -->
                        <div class="pt-2 border-top" style="width: 100%;">
                            <!-- MOBILE ONLY BUTTON: Full Width "Add to Cart" -->
                            <div class="d-block d-md-none">
                                @if (!$item->is_unlimited && $item->stock_quantity == "0")
                                    <button class="btn btn-secondary disabled w-100 py-2 fw-bold" style="font-size: 12px; border-radius: 6px;">
                                        Out Stock
                                    </button>
                                @else
                                    <button type="button" class="btn btn-success w-100 py-2 fw-bold d-flex align-items-center justify-content-center btn-ajax-add-cart" data-stock-id="{{ $item->id }}" data-url="{{ route('my.cart.add', $item->id) }}" style="font-size: 12px; background-color: #28a745; border-color: #28a745; border-radius: 6px;">
                                        <i class="fa fa-shopping-cart me-2"></i> Add to Cart
                                    </button>
                                @endif
                            </div>

                            <!-- DESKTOP BUTTONS: "Add to Cart" & "See Details" Side by Side -->
                            <div class="d-none d-md-block">
                                <div class="row g-2 align-items-center">
                                    <div class="col-6 pe-1">
                                        @if (!$item->is_unlimited && $item->stock_quantity == "0")
                                            <button class="btn btn-secondary disabled w-100 py-2 fw-bold" style="font-size: 11px; white-space: nowrap; border-radius: 6px;">
                                                Out Stock
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-success w-100 py-2 fw-bold d-inline-flex align-items-center justify-content-center btn-ajax-add-cart" data-stock-id="{{ $item->id }}" data-url="{{ route('my.cart.add', $item->id) }}" style="font-size: 11px; white-space: nowrap; background-color: #28a745; border-color: #28a745; border-radius: 6px; padding: 6px 4px;">
                                                <i class="fa fa-shopping-cart me-1"></i> Add to Cart
                                            </button>
                                        @endif
                                    </div>
                                    <div class="col-6 ps-1">
                                        <a href="{{ route('stock.detials', $item->id) }}" class="btn btn-primary w-100 py-2 fw-bold d-inline-flex align-items-center justify-content-center" style="font-size: 11px; white-space: nowrap; background-color: #1b88ce; border-color: #1b88ce; border-radius: 6px; padding: 6px 4px;">
                                            <i class="fa fa-eye me-1"></i> See Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /Single Stock Card -->
                @endforeach
            </div>
        </section>
        <!-- /.content -->
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    @foreach ($all_stock as $item)
    @php
        $chartData = stockFullPriceHistory($item->id);
    @endphp
    var dates{{$item->id}} = {!! json_encode($chartData['dates']) !!};
    var sellingPrice{{$item->id}} = {!! json_encode($chartData['sellingPrices']) !!};
    var buyingPrice{{$item->id}} = {!! json_encode($chartData['buyingPrices']) !!};

    var sparkOptions{{$item->id}} = {
        chart: {
            type: 'line',
            height: 135,
            toolbar: { show: false },
            sparkline: { enabled: false }
        },
        stroke: {
            curve: 'smooth',
            width: 2.5
        },
        markers: {
            size: 3.5,
            colors: ['#28a745', '#e2bb33'],
            strokeColors: '#ffffff',
            strokeWidth: 1.5,
            hover: {
                size: 5
            }
        },
        series: [
            {
                name: 'Selling Price (বিক্রয়)',
                data: sellingPrice{{$item->id}}
            },
            {
                name: 'Buying Price (ক্রয়)',
                data: buyingPrice{{$item->id}}
            }
        ],
        colors: ['#28a745', '#e2bb33'],
        grid: {
            show: true,
            borderColor: '#e2e8f0',
            strokeDashArray: 3
        },
        xaxis: {
            categories: dates{{$item->id}},
            labels: {
                show: true,
                style: {
                    colors: '#334155',
                    fontSize: '9px',
                    fontWeight: 700
                }
            },
            axisBorder: { show: true, color: '#cbd5e1' },
            axisTicks: { show: false }
        },
        yaxis: {
            tickAmount: 2,
            labels: {
                show: true,
                style: {
                    colors: '#334155',
                    fontSize: '9px',
                    fontWeight: 700
                },
                formatter: function (val) {
                    return '৳' + Math.round(val);
                }
            }
        },
        tooltip: {
            theme: 'dark',
            shared: true,
            intersect: false,
            y: {
                formatter: function (val) {
                    return '৳' + Math.round(val);
                }
            }
        },
        legend: {
            show: true,
            position: 'top',
            horizontalAlign: 'right',
            fontSize: '9px',
            fontWeight: 700,
            markers: {
                width: 7,
                height: 7
            }
        }
    };
    var sparkChart{{$item->id}} = new ApexCharts(document.querySelector("#spark{{ $item->id }}"), sparkOptions{{$item->id}});
    sparkChart{{$item->id}}.render();
    @endforeach

    // Real-time AJAX Add to Cart & Badge Updater
    document.addEventListener('DOMContentLoaded', function () {
        $(document).on('click', '.btn-ajax-add-cart', function (e) {
            e.preventDefault();
            var $btn = $(this);
            var url = $btn.data('url');
            var originalHtml = $btn.html();

            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> যোগ হচ্ছে...');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (response) {
                    if (typeof window.updateCartBadgeCount === 'function' && response.cart_count !== undefined) {
                        window.updateCartBadgeCount(response.cart_count);
                    }

                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message || 'পণ্যটি কার্টে যুক্ত হয়েছে!');
                    }

                    $btn.html('<i class="fa fa-check me-1"></i> যুক্ত হয়েছে');
                    $btn.removeClass('btn-success').addClass('btn-dark');

                    setTimeout(function () {
                        $btn.prop('disabled', false).removeClass('btn-dark').addClass('btn-success').html(originalHtml);
                    }, 1400);
                },
                error: function (xhr) {
                    $btn.prop('disabled', false).html(originalHtml);
                    var msg = 'কার্টে যোগ করতে সমস্যা হয়েছে!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    if (typeof toastr !== 'undefined') {
                        toastr.error(msg);
                    }
                }
            });
        });

        // Wishlist Toggle with LocalStorage & Toast Notification
        function getWishlist() {
            try {
                return JSON.parse(localStorage.getItem('user_wishlist_items')) || [];
            } catch (e) {
                return [];
            }
        }

        function saveWishlist(items) {
            localStorage.setItem('user_wishlist_items', JSON.stringify(items));
        }

        function updateWishlistUI() {
            var wishlist = getWishlist();
            $('.btn-wishlist-toggle').each(function () {
                var stockId = $(this).data('stock-id').toString();
                var $icon = $(this).find('i');
                if (wishlist.includes(stockId)) {
                    $icon.removeClass('fa-heart-o text-muted').addClass('fa-heart text-danger');
                    $(this).attr('title', 'উইশলিস্ট থেকে মুছুন');
                } else {
                    $icon.removeClass('fa-heart text-danger').addClass('fa-heart-o text-muted');
                    $(this).attr('title', 'উইশলিস্টে যুক্ত করুন');
                }
            });
        }

        updateWishlistUI();

        $(document).on('click', '.btn-wishlist-toggle', function (e) {
            e.preventDefault();
            var stockId = $(this).data('stock-id').toString();
            var stockName = $(this).data('stock-name') || 'পণ্যটি';
            var wishlist = getWishlist();
            var $icon = $(this).find('i');

            if (wishlist.includes(stockId)) {
                wishlist = wishlist.filter(function (id) { return id !== stockId; });
                saveWishlist(wishlist);
                $icon.removeClass('fa-heart text-danger').addClass('fa-heart-o text-muted');
                if (typeof toastr !== 'undefined') {
                    toastr.info(stockName + ' উইশলিস্ট থেকে সরানো হয়েছে!');
                }
            } else {
                wishlist.push(stockId);
                saveWishlist(wishlist);
                $icon.removeClass('fa-heart-o text-muted').addClass('fa-heart text-danger');
                if (typeof toastr !== 'undefined') {
                    toastr.success(stockName + ' উইশলিস্টে যোগ করা হয়েছে!');
                }
            }
        });
    });
</script>
@endsection
