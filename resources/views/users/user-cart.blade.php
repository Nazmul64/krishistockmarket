@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Cart</h4>
                </div>

            </div>
        </div>

        <!-- Main content -->
        <section class="content">

            <!-- Step wizard -->
            <div class="box">
                <div class="box-header with-border text-center">
                    <h4 class="box-title">Your Cart</h4>


                </div>

                    @if($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="form-control-feedback" >
                                <code>{{ $error }}</code>
                            </div>
                        @endforeach
                    @endif
                <!-- /.box-header -->
                <div class="box-body wizard-content">
                    <form action="{{ route('my.cart.post') }}" method="POST" enctype="multipart/form-data" class="tab-wizard wizard-circle">

                        @csrf

                        <!-- Step 1 -->
                        <h6>Setup Product's</h6>
                        <section id="stock-table">

                            <div class="">
                                <div class="row header_row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="product" class="form-label">Product</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="product" class="form-label">PRICE</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="quantity" class="form-label">Quantiy</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="total" class="form-label">TOTAL</label>
                                        </div>
                                    </div>
                                    <div class="col-1"></div>
                                </div>


                                @foreach ($user_cart_list as $key => $item)
                                <div class="row product_row" style="margin: 10px 0;">
                                    <div class="col-md-3">
                                        <div class="form-group product_img_cart">
                                            <img src="{{ asset('upload/stock_images') }}/{{ getFirstImages($item->stock_id)->image }}"
                                                class="w-100" alt="Product">
                                            <label for="product_name" class="form-label product_name">
                                                {{ SingleStockInfo($item->stock_id)->stock_name }}
                                            </label>
                                            <input type="hidden" class="stock-id" name="product_row[{{$key}}][stock_id]" value="{{$item->stock_id}}">
                                            <input type="hidden" class="id" name="product_row[{{$key}}][cart_id]" value="{{$item->id}}">
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <div class="form-group">
                                            <label for="" class="form-label">
                                                {{ StockLastPrice($item->stock_id)->selling_price }}
                                            </label>
                                            <input type="hidden" class="price-per-unit" name="product_row[{{$key}}][price_per_unit]" value="{{ StockLastPrice($item->stock_id)->selling_price }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <div class="form-group mb-0">
                                            <div class="input-group">
                                                <input type="number" class="stock-quantity form-control" name="product_row[{{$key}}][quantity]" value="{{$item->quantity}}" onchange="calculateTotal(this)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <div class="form-group">
                                            <label for="" class="form-label">
                                                <span class="total-price" name="product_row[{{$key}}][total_price]"></span>
                                                {{-- <input type="hidden" class="total-price-input" name="product_row[{{$key}}][total_price]" value=""> --}}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-1 text-center">
                                        <label for="" class="form-label">
                                            <a href="{{ route('my.cart.remove', $item->id) }}">X</a>
                                        </label>
                                    </div>
                                </div>
                                @endforeach


                                <div class="row chart-row" style="margin: 10px 0;">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-3"></div>
                                    <div class="col-md-3"></div>
                                    <div class="col-md-2 text-center">
                                        <div class="form-group mb-0">
                                            <label for="" class="form-label">
                                                Grand Total:
                                            </label>
                                            <label for="" id="grand-total"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-1"></div>
                                </div>

                            </div>

                            {{-- <div class="mobile_design">

                                @foreach ($user_cart_list as $key => $item)
                                    <div class="row" style="margin-bottom: 15px;border-bottom:1px solid #000">
                                    <input type="hidden" name="product[{{$key}}][stock_id]" value="{{$item->stock_id}}">
                                    <input type="hidden" name="product[{{$key}}][total_price]" value="{{StockLastPrice($item->stock_id)->selling_price}}">
                                    <div class="col-md-3">
                                            <div class="form-group header_row">
                                                <label for="product" class="form-label">Product</label>
                                            </div>
                                            <div class="form-group product_img_cart text-center" style="justify-content: space-around;">
                                                <img src="{{ asset('upload/stock_images') }}/{{ getFirstImages($item->stock_id)->image }}"
                                                    class="w-100" alt="Product">
                                                <label for="product_name" class="form-label product_name">
                                                    {{ SingleStockInfo($item->stock_id)->stock_name }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group header_row">
                                                <label for="product" class="form-label">PRICE</label>
                                            </div>
                                            <div class="form-group text-center">
                                                <label for="" class="form-label">
                                                    {{ StockLastPrice($item->stock_id)->selling_price }} TK
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group header_row">
                                                <label for="quantity" class="form-label">Quantiy</label>
                                            </div>
                                            <div class="form-group text-center">
                                                <div class="form-group mb-0">
                                                    <div class="input-group">
                                                        <input id="demo4" type="number" class="form-control" name="product[{{$key}}][quantity]" placeholder="{{$item->quantity}}" data-bts-button-down-class="btn btn-secondary" data-bts-button-up-class="btn btn-secondary">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group header_row">
                                                <label for="total" class="form-label">TOTAL</label>
                                            </div>
                                            <div class="form-group text-center">
                                                <label for="" class="form-label">
                                                    {{ StockLastPrice($item->stock_id)->selling_price * $item->quantity }} TK
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <label for="" class="form-label">
                                                <a href="{{ route('my.cart.remove', $item->id) }}">X</a>
                                            </label>
                                        </div>
                                    </div>

                                @endforeach

                            </div> --}}


                        </section>




                        <!-- Step 2 -->
                        <h6>Make Payment</h6>
                        <section>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row" style="margin: 25px 0;">
                                        <label class="col-sm-3 col-form-label" style="padding-left: 0;">Select Type</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="payment_system_id" required>
                                                <option value="">Select ....</option>
                                                @php
                                                    $all_pay_s= App\Models\SitePaymentSystem::all();
                                                @endphp

                                                @foreach ($all_pay_s as $key => $item)
                                                    <option value="{{ $item->id }}">{{ $item->pay_s_name }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <label for="">Pay to:</label>
                                    @foreach ($all_pay_s as $key => $item)
                                    <div class="form-group row">

                                        <label class="col-sm-3 col-form-label"> <i style="color: red">{{ $item->pay_s_name }}</i></label>

                                        <div class="col-sm-9 col-form-label">
                                            <div class="c-inputs-stacked">
                                                <label for="checkbox_123" class="d-block">{{ $item->pay_s_number }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                        </section>
                        <!-- Step 3 -->
                        <h6>Billing Setup</h6>
                        <section>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row" style="margin: 25px 0;">
                                        <label class="col-sm-3 col-form-label" style="padding-left: 0;">Sceenshort</label>
                                        <div class="col-sm-9">
                                            <input required type="file" name="sceenshorts" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row" style="margin-bottom: 20px;">
                                        <label class="col-sm-3 col-form-label">Pay From..:</label>
                                        <div class="col-sm-9">
                                           <input required type="text" name="from_phone_number" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row" style="margin-bottom: 20px;">
                                        <label class="col-sm-3 col-form-label">Trx No.:</label>
                                        <div class="col-sm-9">
                                           <input required type="text" name="trx_id" class="form-control">
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </section>
                        <!-- Step 4 -->

                    </form>

                </div>
                <!-- /.box-body -->

            </div>
            <!-- /.box -->

        </section>
        <!-- /.content -->

    </div>

    {{-- @php
        // Convert user_cart_list array to a collection
        $userCartCollection = collect($user_cart_list);

        // Check if the collection contains any value less than zero
        $submitDisabled = $userCartCollection->contains(function ($value) {
            return $value < 0;
        });
    @endphp --}}

</div>


@endsection

@section('script')
<script type="text/javascript">


jQuery.noConflict(); // Avoid conflicts with other JavaScript libraries

(function($) { // Use jQuery code inside this function



    var rows = document.querySelectorAll(".product_row");
        for (var i = 0; i < rows.length; i++) {
        calculateTotal(rows[i].querySelector(".stock-quantity"));
    }

    function calculateTotal(input) {
        // Get the parent row of the input field
        var row = $(input).closest('.product_row');

        // Get the price per unit and quantity values for the current row
        var pricePerUnit = parseFloat(row.find(".price-per-unit").val());
        var quantity = parseFloat(row.find(".stock-quantity").val());

        // Calculate the total price for the current row and update the corresponding field
        var totalPrice = pricePerUnit * quantity;
        row.find(".total-price").html(totalPrice.toFixed(2));

        // Calculate the grand total price for all rows and update the corresponding field
        var grandTotal = 0;
        $('.product_row').each(function() {
            grandTotal += parseFloat($(this).find(".total-price").text());
        });
        $("#grand-total").html(grandTotal.toFixed(2));
    }

    $(".tab-wizard").steps({
        headerTag: "h6",
        bodyTag: "section",
        transitionEffect: "none",
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            finish: "Submit"
        },
        onFinished: function(event, currentIndex) {
            @if (count($user_cart_list) < 1)

            @else
                // Get the form element
                var form = $(".tab-wizard").closest("form");
                // Submit the form
                form.submit();
            @endif
        }
    });

    // Bind the input event for the quantity field to recalculate the total price
    $('.stock-quantity').on('input', function() {
        calculateTotal(this);
    });

})(jQuery); // End jQuery code


</script>
@endsection
