@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Cart (শপিং কার্ট)</h4>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <!-- Step wizard -->
            <div class="box shadow-sm">
                <div class="box-header with-border text-center py-3">
                    <h4 class="box-title fw-bold">Your Cart Checkout</h4>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger mx-4 mt-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger mx-4 mt-3">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- /.box-header -->
                <div class="box-body wizard-content p-4">
                    <form action="{{ route('my.cart.post') }}" method="POST" enctype="multipart/form-data" class="tab-wizard wizard-circle" id="cart-checkout-form">
                        @csrf

                        <!-- Step 1 -->
                        <h6>Setup Product's</h6>
                        <section id="stock-table" class="py-2">
                            @if(count($user_cart_list) > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle text-center mb-3">
                                        <thead class="bg-light">
                                            <tr>
                                                <th style="min-width: 220px;" class="text-start ps-3">পণ্য (Product)</th>
                                                <th style="min-width: 110px;">মূল্য (Price)</th>
                                                <th style="min-width: 150px;">পরিমাণ (Quantity)</th>
                                                <th style="min-width: 120px;">মোট (Total)</th>
                                                <th style="width: 60px;">মুছুন</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($user_cart_list as $key => $item)
                                                @php
                                                    $stock = SingleStockInfo($item->stock_id);
                                                    $lastPrice = StockLastPrice($item->stock_id);
                                                    $price = $lastPrice ? $lastPrice->selling_price : 0;
                                                    $imageObj = getFirstImages($item->stock_id);
                                                    $imgSrc = $imageObj && $imageObj->image ? asset('upload/stock_images/' . $imageObj->image) : asset('frontend/assets/images/no-image.png');
                                                @endphp
                                                <tr class="product_row">
                                                    <td class="text-start ps-3">
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $imgSrc }}" class="rounded me-3 border" style="width: 50px; height: 50px; object-fit: cover;" alt="Product">
                                                            <div>
                                                                <strong class="d-block text-dark">{{ $stock ? $stock->stock_name : 'Product #'.$item->stock_id }}</strong>
                                                                <small class="text-muted">ID: #{{ $item->stock_id }}</small>
                                                            </div>
                                                            <input type="hidden" class="stock-id" name="product_row[{{$key}}][stock_id]" value="{{$item->stock_id}}">
                                                            <input type="hidden" class="id" name="product_row[{{$key}}][cart_id]" value="{{$item->id}}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="fw-bold text-dark">৳{{ number_format($price, 0) }}</span>
                                                        <input type="hidden" class="price-per-unit" name="product_row[{{$key}}][price_per_unit]" value="{{ $price }}">
                                                    </td>
                                                    <td>
                                                        <div class="d-inline-flex align-items-center border rounded bg-white shadow-sm" style="overflow: hidden;">
                                                            <button type="button" class="btn btn-sm btn-light border-0 px-2 btn-qty-minus text-secondary" style="font-weight: bold; width: 34px; height: 34px; font-size: 16px; line-height: 1;">-</button>
                                                            <input type="number" class="stock-quantity form-control border-0 text-center p-0 fw-bold" name="product_row[{{$key}}][quantity]" value="{{$item->quantity}}" min="1" style="width: 50px; height: 34px; box-shadow: none; font-size: 14px;">
                                                            <button type="button" class="btn btn-sm btn-light border-0 px-2 btn-qty-plus text-secondary" style="font-weight: bold; width: 34px; height: 34px; font-size: 16px; line-height: 1;">+</button>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="fw-bold text-success fs-15">৳ <span class="total-price">{{ number_format($price * $item->quantity, 2) }}</span></span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('my.cart.remove', $item->id) }}" class="btn btn-sm btn-outline-danger border-0 rounded-circle" style="width: 30px; height: 30px; line-height: 28px; padding: 0;" title="রিমুভ করুন">
                                                            <i class="fa fa-times"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-light">
                                            <tr>
                                                <td colspan="3" class="text-end fw-bold fs-16 pe-3">Grand Total (সর্বমোট):</td>
                                                <td colspan="2" class="text-start ps-3 fw-bold text-success fs-18">
                                                    ৳ <span id="grand-total">0.00</span>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fa fa-shopping-cart text-muted mb-3" style="font-size: 48px;"></i>
                                    <h5 class="text-muted">আপনার কার্ট বর্তমানে খালি আছে</h5>
                                    <a href="{{ route('stock.index') }}" class="btn btn-success mt-2">
                                        <i class="fa fa-shopping-bag me-1"></i> প্রোডাক্ট দেখুন ও কার্টে যোগ করুন
                                    </a>
                                </div>
                            @endif
                        </section>

                        <!-- Step 2 -->
                        <h6>Make Payment</h6>
                        <section class="py-3">
                            <div class="row justify-content-center">
                                <div class="col-md-8 col-lg-6">
                                    <div class="card border shadow-sm p-4">
                                        <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">পেমেন্ট মেথড নির্বাচন করুন</h5>
                                        
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">Select Type (পেমেন্ট মাধ্যম): <span class="text-danger">*</span></label>
                                            <select class="form-control form-select" name="payment_system_id" id="payment_system_select" required>
                                                <option value="">-- মেথড সিলেক্ট করুন --</option>
                                                @php
                                                    $all_pay_s = App\Models\SitePaymentSystem::all();
                                                @endphp

                                                @foreach ($all_pay_s as $key => $item)
                                                    <option value="{{ $item->id }}" data-name="{{ $item->pay_s_name }}" data-number="{{ $item->pay_s_number }}">
                                                        {{ $item->pay_s_name }} ({{ $item->pay_s_number }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Dynamic Selected Payment Info Box -->
                                        <div id="payment_info_card" class="p-3 rounded border border-success bg-light mt-2" style="display: none;">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3 p-2 bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                                    <i class="fa fa-money fs-18"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block fw-semibold" id="pay_method_label">পেমেন্ট নম্বর:</small>
                                                    <span class="fs-16 fw-bold text-dark" id="pay_method_number"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Step 3 -->
                        <h6>Billing Setup</h6>
                        <section class="py-3">
                            <div class="row justify-content-center">
                                <div class="col-md-8 col-lg-6">
                                    <div class="card border shadow-sm p-4">
                                        <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">বিলিং ও লেনদেনের তথ্য</h5>

                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">Screenshot (পেমেন্টের স্ক্রিনশট): <span class="text-danger">*</span></label>
                                            <input required type="file" id="screenshot_input" name="sceenshorts" class="form-control" accept="image/*">
                                            
                                            <!-- Live Image Preview -->
                                            <div id="screenshot_preview_box" class="mt-2 text-center p-2 border rounded bg-light" style="display: none;">
                                                <p class="small text-muted mb-1 fw-bold">স্ক্রিনশট প্রিভিউ:</p>
                                                <img id="screenshot_preview_img" src="" alt="Screenshot Preview" class="img-fluid rounded border shadow-sm" style="max-height: 180px; object-fit: contain;">
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">Pay From.. (যে নম্বর থেকে পাঠিয়েছেন): <span class="text-danger">*</span></label>
                                            <input required type="text" id="from_phone_input" name="from_phone_number" class="form-control" placeholder="যেমন: 017XXXXXXXX">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">Trx No. (ট্রানজেকশন আইডি): <span class="text-danger">*</span></label>
                                            <input required type="text" id="trx_id_input" name="trx_id" class="form-control" placeholder="যেমন: 9J7K6L5M4N">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </section>
        <!-- /.content -->
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
jQuery.noConflict();

(function($) {
    // Initial calculation on page load
    calculateAllTotals();

    function calculateRowTotal(row) {
        var pricePerUnit = parseFloat(row.find(".price-per-unit").val()) || 0;
        var quantity = parseInt(row.find(".stock-quantity").val()) || 1;
        if (quantity < 1) {
            quantity = 1;
            row.find(".stock-quantity").val(1);
        }
        var totalPrice = pricePerUnit * quantity;
        row.find(".total-price").text(totalPrice.toFixed(2));
    }

    function calculateAllTotals() {
        var grandTotal = 0;
        $('.product_row').each(function() {
            calculateRowTotal($(this));
            grandTotal += parseFloat($(this).find(".total-price").text()) || 0;
        });
        $("#grand-total").text(grandTotal.toFixed(2));
    }

    // Plus (+) Button Click
    $(document).on('click', '.btn-qty-plus', function() {
        var row = $(this).closest('.product_row');
        var input = row.find('.stock-quantity');
        var currentVal = parseInt(input.val()) || 1;
        input.val(currentVal + 1);
        calculateAllTotals();
    });

    // Minus (-) Button Click
    $(document).on('click', '.btn-qty-minus', function() {
        var row = $(this).closest('.product_row');
        var input = row.find('.stock-quantity');
        var currentVal = parseInt(input.val()) || 1;
        if (currentVal > 1) {
            input.val(currentVal - 1);
            calculateAllTotals();
        }
    });

    // Quantity Input Change / Type
    $(document).on('input change', '.stock-quantity', function() {
        var val = parseInt($(this).val());
        if (isNaN(val) || val < 1) {
            $(this).val(1);
        }
        calculateAllTotals();
    });

    // Step 2 Payment Selection Dynamic Display
    $('#payment_system_select').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var number = selectedOption.data('number');
        var name = selectedOption.data('name');

        if (number && name) {
            $('#pay_method_label').text(name + ' পেমেন্ট নম্বর:');
            $('#pay_method_number').text(number);
            $('#payment_info_card').slideDown(200);
        } else {
            $('#payment_info_card').slideUp(200);
        }
    });

    // Step 3 Screenshot Image Live Preview
    $('#screenshot_input').on('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(event) {
                $('#screenshot_preview_img').attr('src', event.target.result);
                $('#screenshot_preview_box').slideDown(200);
            };
            reader.readAsDataURL(file);
        } else {
            $('#screenshot_preview_box').slideUp(200);
        }
    });

    // Steps Wizard Initialization with Toastr validations
    var wizard = $(".tab-wizard").steps({
        headerTag: "h6",
        bodyTag: "section",
        transitionEffect: "none",
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            next: "Next",
            previous: "Previous",
            finish: "Submit"
        },
        onStepChanging: function(event, currentIndex, newIndex) {
            // Allow moving backwards without validation
            if (currentIndex > newIndex) {
                return true;
            }

            // Step 1 -> Step 2
            if (currentIndex === 0) {
                var productCount = $('.product_row').length;
                if (productCount < 1) {
                    if (typeof toastr !== 'undefined') {
                        toastr.warning('আপনার কার্টে কোনো পণ্য নেই!');
                    } else {
                        alert('আপনার কার্টে কোনো পণ্য নেই!');
                    }
                    return false;
                }
                return true;
            }

            // Step 2 -> Step 3
            if (currentIndex === 1) {
                var paymentId = $('#payment_system_select').val();
                if (!paymentId) {
                    if (typeof toastr !== 'undefined') {
                        toastr.error('দয়া করে একটি পেমেন্ট মাধ্যম সিলেক্ট করুন!');
                    } else {
                        alert('দয়া করে একটি পেমেন্ট মাধ্যম সিলেক্ট করুন!');
                    }
                    return false;
                }
                return true;
            }

            return true;
        },
        onFinishing: function(event, currentIndex) {
            // Step 3 Validation before Finish
            var fileInput = document.getElementById('screenshot_input');
            var fromPhone = $('#from_phone_input').val().trim();
            var trxId = $('#trx_id_input').val().trim();

            if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                if (typeof toastr !== 'undefined') {
                    toastr.error('দয়া করে পেমেন্টের স্ক্রিনশট আপলোড করুন!');
                } else {
                    alert('দয়া করে পেমেন্টের স্ক্রিনশট আপলোড করুন!');
                }
                return false;
            }

            if (!fromPhone) {
                if (typeof toastr !== 'undefined') {
                    toastr.error('দয়া করে যে নম্বর থেকে টাকা পাঠিয়েছেন (Pay From) তা লিখুন!');
                } else {
                    alert('দয়া করে যে নম্বর থেকে টাকা পাঠিয়েছেন তা লিখুন!');
                }
                $('#from_phone_input').focus();
                return false;
            }

            if (!trxId) {
                if (typeof toastr !== 'undefined') {
                    toastr.error('দয়া করে ট্রানজেকশন আইডি (Trx No.) লিখুন!');
                } else {
                    alert('দয়া করে ট্রানজেকশন আইডি লিখুন!');
                }
                $('#trx_id_input').focus();
                return false;
            }

            return true;
        },
        onFinished: function(event, currentIndex) {
            var form = $("#cart-checkout-form");
            form.submit();
        }
    });

})(jQuery);
</script>
@endsection
