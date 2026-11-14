@extends('layouts.frontend.shop')

@section('title', 'চেকআউট ও ডেলিভারি | Asmi Supershop')

@section('extra_css')
<style>
    .checkout-card {
        background: #ffffff;
        border: 1px solid var(--shop-border);
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
    }
    .checkout-card-title {
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 18px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .checkout-card-title i {
        color: var(--shop-teal);
    }

    .form-label-custom {
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
    }
    .form-control-custom {
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 14px;
        transition: all 0.2s;
    }
    .form-control-custom:focus {
        border-color: var(--shop-teal);
        box-shadow: 0 0 0 3px rgba(0, 126, 138, 0.15);
    }

    /* Shipping Option Radio Cards */
    .shipping-option-card {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .shipping-option-card:hover {
        border-color: var(--shop-teal);
        background: #f0fdfa;
    }
    .shipping-option-card.selected {
        border-color: var(--shop-teal);
        background: #f0fdfa;
        box-shadow: 0 2px 8px rgba(0, 126, 138, 0.12);
    }

    /* Payment Option Radio Cards */
    .payment-option-card {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .payment-option-card:hover {
        border-color: var(--shop-teal);
        background: #f0fdfa;
    }
    .payment-option-card.selected {
        border-color: var(--shop-teal);
        background: #f0fdfa;
        box-shadow: 0 2px 8px rgba(0, 126, 138, 0.12);
    }

    /* Order Summary Sidebar */
    .summary-card {
        background: #ffffff;
        border: 2px solid var(--shop-border);
        border-radius: 12px;
        padding: 24px;
        position: sticky;
        top: 90px;
    }
    .summary-item-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
    }
    .summary-item-img {
        width: 44px;
        height: 44px;
        object-fit: contain;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        padding: 2px;
    }

    .btn-confirm-order {
        background: var(--shop-teal);
        color: #ffffff;
        font-size: 16px;
        font-weight: 800;
        border: none;
        border-radius: 8px;
        padding: 14px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 15px rgba(0, 126, 138, 0.25);
    }
    .btn-confirm-order:hover {
        background: var(--shop-teal-dark);
        color: #ffffff;
        transform: translateY(-2px);
    }
    @media (max-width: 768px) {
        .checkout-card {
            padding: 16px;
        }
        .summary-card {
            padding: 18px;
            position: static;
        }
        .shipping-option-card {
            padding: 12px;
            font-size: 13px;
        }
        .payment-option-card {
            padding: 12px;
            font-size: 13px;
        }
    }
</style>
@endsection

@section('shop_content')

<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small mb-2">
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-teal text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Checkout</li>
        </ol>
    </nav>
    <h2 class="fw-bold text-dark"><i class="fa fa-credit-card me-2 text-teal"></i>চেকআউট ও ডেলিভারি বিবরণ</h2>
    <p class="text-muted small">অনুগ্রহ করে আপনার সঠিক ঠিকানা এবং তথ্য দিয়ে অর্ডারটি সম্পন্ন করুন।</p>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa fa-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form action="{{ route('shop.checkout.submit') }}" method="POST" id="shopCheckoutForm">
    @csrf
    <input type="hidden" name="items" id="formItemsJson">

    <div class="row g-4">
        <!-- Left: Customer Information & Delivery Details -->
        <div class="col-lg-7">
            <!-- 1. Customer Information (First Name, Last Name, Phone, Email) -->
            <div class="checkout-card shadow-sm">
                <h5 class="checkout-card-title">
                    <i class="fa fa-user-circle"></i>
                    <span>১. কাস্টমার তথ্য (Customer Information)</span>
                </h5>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-custom">ফার্স্ট নেম (First Name) <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control form-control-custom" value="{{ old('first_name', Auth::check() ? explode(' ', Auth::user()->name)[0] : '') }}" required placeholder="আপনার নামের প্রথম অংশ">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">লাস্ট নেম (Last Name)</label>
                        <input type="text" name="last_name" class="form-control form-control-custom" value="{{ old('last_name', Auth::check() && count(explode(' ', Auth::user()->name)) > 1 ? explode(' ', Auth::user()->name)[1] : '') }}" placeholder="আপনার নামের শেষ অংশ">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">মোবাইল ফোন নম্বর <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" class="form-control form-control-custom" value="{{ old('phone', Auth::check() ? Auth::user()->phone : '') }}" required placeholder="017XXXXXXXX">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">ইমেইল (ঐচ্ছিক)</label>
                        <input type="email" name="email" class="form-control form-control-custom" value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}" placeholder="example@gmail.com">
                    </div>
                </div>
            </div>

            <!-- 2. Delivery Address -->
            <div class="checkout-card shadow-sm">
                <h5 class="checkout-card-title">
                    <i class="fa fa-map-location-dot"></i>
                    <span>২. ডেলিভারি ঠিকানা (Delivery Address)</span>
                </h5>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label-custom">পূর্ণাঙ্গ ঠিকানা (বাসা/ফ্ল্যাট নং, রোড, এলাকা) <span class="text-danger">*</span></label>
                        <textarea name="address" rows="2" class="form-control form-control-custom" required placeholder="উদাহরণ: বাসা #১২, রোড #০৫, ব্লক-সি, বনানী, ঢাকা">{{ old('address') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">শহর / জেলা (City)</label>
                        <input type="text" name="city" id="inputCity" class="form-control form-control-custom" value="{{ old('city', 'Dhaka') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">অর্ডারের বিশেষ নোট (ঐচ্ছিক)</label>
                        <input type="text" name="notes" class="form-control form-control-custom" value="{{ old('notes') }}" placeholder="যেমন: বিকাল ৫ টার পর ডেলিভারি দিন">
                    </div>
                </div>
            </div>

            <!-- 3. Shipping Method Selection (Dynamic from Admin) -->
            <div class="checkout-card shadow-sm">
                <h5 class="checkout-card-title">
                    <i class="fa fa-truck-fast"></i>
                    <span>৩. শিপিং মেথড (Shipping Method)</span>
                </h5>

                @if(isset($shippingZones) && $shippingZones->isNotEmpty())
                    @foreach($shippingZones as $sIdx => $zone)
                        <label class="shipping-option-card {{ $sIdx === 0 ? 'selected' : '' }}" id="shipCard_{{ $zone->id }}" onclick="selectShippingZone({{ $zone->id }}, {{ (float)$zone->charge }})">
                            <div class="d-flex align-items-center gap-3">
                                <input type="radio" name="shipping_zone_id" value="{{ $zone->id }}" id="shipRadio_{{ $zone->id }}" {{ $sIdx === 0 ? 'checked' : '' }} class="form-check-input mt-0">
                                <div>
                                    <strong class="d-block text-dark">{{ $zone->name }}</strong>
                                    <span class="small text-muted">{{ $zone->estimated_time ?: '১-২ কার্যদিবস' }}</span>
                                </div>
                            </div>
                            <span class="badge {{ $zone->charge == 0 ? 'bg-success' : 'bg-light text-dark border' }} fs-6">
                                {{ $zone->charge == 0 ? '৳ 0 (ফ্রি)' : ('৳ ' . number_format($zone->charge, 0)) }}
                            </span>
                        </label>
                    @endforeach
                @else
                    <!-- Fallback options if none in DB -->
                    <label class="shipping-option-card selected" id="shipCard_dhaka_free" onclick="selectShipping('dhaka_free', 0)">
                        <div class="d-flex align-items-center gap-3">
                            <input type="radio" name="shipping_method" value="dhaka_free" id="shipRadio_dhaka_free" checked class="form-check-input mt-0">
                            <div>
                                <strong class="d-block text-dark">Free Shipping (Dhaka city only)</strong>
                                <span class="small text-muted">ঢাকা সিটি কর্পোরেশন এলাকার মধ্যে ফ্রি ডেলিভারি (১-২ ঘণ্টা)</span>
                            </div>
                        </div>
                        <span class="badge bg-success fs-6">৳ 0 (ফ্রি)</span>
                    </label>
                    <label class="shipping-option-card" id="shipCard_inside_dhaka" onclick="selectShipping('inside_dhaka', 60)">
                        <div class="d-flex align-items-center gap-3">
                            <input type="radio" name="shipping_method" value="inside_dhaka" id="shipRadio_inside_dhaka" class="form-check-input mt-0">
                            <div>
                                <strong class="d-block text-dark">Inside Dhaka (সাভার / কেরানীগঞ্জ / টঙ্গী)</strong>
                                <span class="small text-muted">গ্রেটার ঢাকা শহরতলী এলাকা ডেলিভারি চার্জ</span>
                            </div>
                        </div>
                        <span class="badge bg-light text-dark border fs-6">৳ 60</span>
                    </label>
                    <label class="shipping-option-card" id="shipCard_outside_dhaka" onclick="selectShipping('outside_dhaka', 120)">
                        <div class="d-flex align-items-center gap-3">
                            <input type="radio" name="shipping_method" value="outside_dhaka" id="shipRadio_outside_dhaka" class="form-check-input mt-0">
                            <div>
                                <strong class="d-block text-dark">Outside Dhaka (ঢাকার বাইরে সারা বাংলাদেশে)</strong>
                                <span class="small text-muted">কুরিয়ার সার্ভিসের মাধ্যমে দেশের যেকোনো জেলা বা উপজেলায়</span>
                            </div>
                        </div>
                        <span class="badge bg-light text-dark border fs-6">৳ 120</span>
                    </label>
                @endif
            </div>

            <!-- 4. Payment Method Selection (Dynamic from Admin) -->
            <div class="checkout-card shadow-sm">
                <h5 class="checkout-card-title">
                    <i class="fa fa-wallet"></i>
                    <span>৪. পেমেন্ট পদ্ধতি (Payment Method)</span>
                </h5>

                @if(isset($paymentMethods) && $paymentMethods->isNotEmpty())
                    @foreach($paymentMethods as $pIdx => $pm)
                        <label class="payment-option-card {{ $pIdx === 0 ? 'selected' : '' }}" id="payCard_{{ $pm->code }}" onclick="selectPayment('{{ $pm->code }}')">
                            <input type="radio" name="payment_method" value="{{ $pm->code }}" id="payRadio_{{ $pm->code }}" {{ $pIdx === 0 ? 'checked' : '' }} class="form-check-input mt-0">
                            @if($pm->code === 'bkash')
                                <i class="fa-solid fa-mobile-screen fs-3 text-danger"></i>
                            @elseif($pm->code === 'nagad')
                                <i class="fa-solid fa-mobile-screen fs-3 text-warning"></i>
                            @elseif($pm->code === 'rocket')
                                <i class="fa-solid fa-mobile-screen fs-3" style="color: #8b5cf6;"></i>
                            @elseif($pm->code === 'cod')
                                <i class="fa-solid fa-hand-holding-dollar fs-3 text-success"></i>
                            @else
                                <i class="fa-solid fa-credit-card fs-3 text-primary"></i>
                            @endif
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong class="d-block text-dark">{{ $pm->name }}</strong>
                                    @if($pm->merchant_number)
                                        <span class="badge bg-light text-dark border small">{{ $pm->merchant_number }}</span>
                                    @endif
                                </div>
                                @if($pm->instructions)
                                    <span class="small text-muted d-block">{{ $pm->instructions }}</span>
                                @endif
                            </div>
                        </label>
                    @endforeach
                @else
                    <!-- Fallback COD -->
                    <label class="payment-option-card selected" id="payCard_cod" onclick="selectPayment('cod')">
                        <input type="radio" name="payment_method" value="cod" id="payRadio_cod" checked class="form-check-input mt-0">
                        <i class="fa-solid fa-hand-holding-dollar fs-3 text-success"></i>
                        <div>
                            <strong class="d-block text-dark">ক্যাশ অন ডেলিভারি (Cash on Delivery)</strong>
                            <span class="small text-muted">পণ্য হাতে পেয়ে চেক করে ডেলিভারিম্যানকে মূল্য পরিশোধ করুন</span>
                        </div>
                    </label>
                @endif
            </div>
        </div>

        <!-- Right: Order Summary Sidebar -->
        <div class="col-lg-5">
            <div class="summary-card shadow-sm">
                <h5 class="fw-bold mb-3 pb-2 border-bottom text-dark d-flex justify-content-between align-items-center">
                    <span><i class="fa fa-shopping-bag text-teal me-2"></i>অর্ডার সামারি</span>
                    <span class="badge bg-teal text-white small rounded-pill px-3 py-1" id="checkoutBadgeCount">0 টি পণ্য</span>
                </h5>

                <div id="checkoutCartItemsList" style="max-height: 280px; overflow-y: auto;">
                    <!-- Injected via JavaScript -->
                </div>

                <!-- Coupon / Promo Code Card -->
                <div class="mt-3 p-3 bg-light rounded-3 border">
                    <label class="form-label small fw-bold text-muted mb-1"><i class="fa fa-ticket text-teal me-1"></i>প্রমো বা কুপন কোড (Promo / Coupon)</label>
                    <div class="input-group">
                        <input type="text" id="couponCodeInput" class="form-control text-uppercase font-monospace" placeholder="e.g. ASMI10, SAVE50">
                        <button type="button" class="btn btn-teal text-white" id="btnApplyCoupon" onclick="applyCouponCode()">প্রয়োগ</button>
                    </div>
                    <div id="couponMessage" class="small mt-2" style="display: none;"></div>
                </div>

                <div class="pt-3 mt-3 border-top">
                    <div class="d-flex justify-content-between mb-2 text-muted small">
                        <span>পণ্যের মোট মূল্য (Subtotal):</span>
                        <strong class="text-dark" id="checkoutSubtotal">৳ 0</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2 text-muted small">
                        <span>শিপিং চার্জ (Shipping Fee):</span>
                        <strong class="text-success" id="checkoutShipping">৳ 0</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2 text-success small" id="discountRow" style="display: none;">
                        <span>কুপন ছাড় (Discount):</span>
                        <strong id="checkoutDiscount">-৳ 0</strong>
                    </div>

                    <div class="d-flex justify-content-between pt-2 mt-2 border-top fs-5 fw-bold text-dark">
                        <span>সর্বমোট প্রদেয় (Total):</span>
                        <span class="text-teal" id="checkoutGrandTotal">৳ 0</span>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn-confirm-order" id="btnSubmitOrder">
                        <i class="fa fa-check-circle"></i>
                        <span>অর্ডার কনফার্ম করুন (Confirm Order)</span>
                    </button>
                    <div class="text-center mt-2 small text-muted">
                        <i class="fa fa-lock me-1 text-success"></i> আপনার সকল লেনদেন সম্পূর্ণ নিরাপদ ও সুরক্ষিত
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@section('extra_js')
<script>
    let currentShippingCost = 0;
    let currentDiscount = 0;
    let appliedCouponCode = '';

    function selectShippingZone(zoneId, cost) {
        document.querySelectorAll('.shipping-option-card').forEach(c => c.classList.remove('selected'));
        const target = document.getElementById('shipCard_' + zoneId);
        if (target) target.classList.add('selected');
        const radio = document.getElementById('shipRadio_' + zoneId);
        if (radio) radio.checked = true;

        currentShippingCost = cost;
        renderCheckoutSummary();
    }

    function selectShipping(method, cost) {
        document.querySelectorAll('.shipping-option-card').forEach(c => c.classList.remove('selected'));
        const target = document.getElementById('shipCard_' + method);
        if (target) target.classList.add('selected');
        const radio = document.getElementById('shipRadio_' + method);
        if (radio) radio.checked = true;

        currentShippingCost = cost;
        renderCheckoutSummary();
    }

    function selectPayment(method) {
        document.querySelectorAll('.payment-option-card').forEach(c => c.classList.remove('selected'));
        const target = document.getElementById('payCard_' + method);
        if (target) target.classList.add('selected');
        const radio = document.getElementById('payRadio_' + method);
        if (radio) radio.checked = true;
    }

    function applyCouponCode() {
        const codeInput = document.getElementById('couponCodeInput');
        const msgEl = document.getElementById('couponMessage');
        const code = codeInput ? codeInput.value.trim() : '';

        if (!code) {
            msgEl.textContent = 'দয়া করে একটি কুপন কোড লিখুন';
            msgEl.className = 'small mt-2 text-danger';
            msgEl.style.display = 'block';
            return;
        }

        const items = ShopCart.getItems();
        const subtotal = items.reduce((sum, i) => sum + (i.price * (i.quantity || 1)), 0);

        msgEl.textContent = 'যাচাই করা হচ্ছে...';
        msgEl.className = 'small mt-2 text-muted';
        msgEl.style.display = 'block';

        fetch('{{ route("shop.apply_coupon") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ code: code, subtotal: subtotal })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                currentDiscount = data.discount;
                appliedCouponCode = data.code;
                msgEl.textContent = data.message;
                msgEl.className = 'small mt-2 text-success fw-bold';
                
                // Add hidden input to form
                let hiddenCoupon = document.getElementById('formCouponCode');
                if (!hiddenCoupon) {
                    hiddenCoupon = document.createElement('input');
                    hiddenCoupon.type = 'hidden';
                    hiddenCoupon.name = 'coupon_code';
                    hiddenCoupon.id = 'formCouponCode';
                    document.getElementById('shopCheckoutForm').appendChild(hiddenCoupon);
                }
                hiddenCoupon.value = appliedCouponCode;

                renderCheckoutSummary();
            } else {
                currentDiscount = 0;
                appliedCouponCode = '';
                msgEl.textContent = data.message || 'কুপন প্রযোজ্য নয়';
                msgEl.className = 'small mt-2 text-danger';
                renderCheckoutSummary();
            }
        })
        .catch(err => {
            msgEl.textContent = 'কুপন যাচাইয়ে সমস্যা হয়েছে';
            msgEl.className = 'small mt-2 text-danger';
        });
    }

    function renderCheckoutSummary() {
        const items = ShopCart.getItems();
        const container = document.getElementById('checkoutCartItemsList');
        const badgeCount = document.getElementById('checkoutBadgeCount');
        const subtotalEl = document.getElementById('checkoutSubtotal');
        const shippingEl = document.getElementById('checkoutShipping');
        const discountRow = document.getElementById('discountRow');
        const discountEl = document.getElementById('checkoutDiscount');
        const grandTotalEl = document.getElementById('checkoutGrandTotal');
        const jsonInput = document.getElementById('formItemsJson');
        const btnSubmit = document.getElementById('btnSubmitOrder');

        if (!items || items.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4 text-muted">
                    <i class="fa fa-basket-shopping fs-2 mb-2 text-muted"></i>
                    <p class="small mb-2">আপনার ব্যাগে কোনো পণ্য নেই!</p>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-teal text-white" style="background: var(--shop-teal);">
                        শপিং করতে যান
                    </a>
                </div>
            `;
            if (badgeCount) badgeCount.textContent = '0 টি পণ্য';
            if (subtotalEl) subtotalEl.textContent = '৳ 0';
            if (shippingEl) shippingEl.textContent = '৳ 0';
            if (grandTotalEl) grandTotalEl.textContent = '৳ 0';
            if (jsonInput) jsonInput.value = '[]';
            if (btnSubmit) btnSubmit.disabled = true;
            return;
        }

        if (btnSubmit) btnSubmit.disabled = false;
        if (jsonInput) jsonInput.value = JSON.stringify(items);

        const totalItemsCount = items.reduce((sum, i) => sum + (i.quantity || 1), 0);
        const subtotal = items.reduce((sum, i) => sum + (i.price * (i.quantity || 1)), 0);
        const grandTotal = Math.max(0, subtotal - currentDiscount) + currentShippingCost;

        if (badgeCount) badgeCount.textContent = totalItemsCount + ' টি পণ্য';
        if (subtotalEl) subtotalEl.textContent = '৳ ' + Math.round(subtotal);
        if (shippingEl) {
            shippingEl.textContent = currentShippingCost === 0 ? 'ফ্রি (৳ 0)' : ('৳ ' + currentShippingCost);
        }

        if (discountRow && discountEl) {
            if (currentDiscount > 0) {
                discountRow.style.display = 'flex';
                discountEl.textContent = '-৳ ' + Math.round(currentDiscount);
            } else {
                discountRow.style.display = 'none';
            }
        }

        if (grandTotalEl) grandTotalEl.textContent = '৳ ' + Math.round(grandTotal);

        let html = '';
        items.forEach(item => {
            const lineTotal = Math.round(item.price * item.quantity);
            html += `
                <div class="summary-item-row">
                    <img src="${item.image || 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=200'}" alt="${item.name}" class="summary-item-img">
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-bold text-truncate" title="${item.name}">${item.name}</div>
                        <div class="text-muted small">৳ ${Math.round(item.price)} × ${item.quantity}</div>
                    </div>
                    <div class="fw-bold text-teal">৳ ${lineTotal}</div>
                </div>
            `;
        });
        container.innerHTML = html;
    }

    // Intercept form submit to clear cart on successful placement
    document.getElementById('shopCheckoutForm').addEventListener('submit', function(e) {
        const items = ShopCart.getItems();
        if (!items || items.length === 0) {
            e.preventDefault();
            alert('আপনার ব্যাগে কোনো পণ্য নেই! অনুগ্রহ করে পণ্য যোগ করুন।');
            return false;
        }
        document.getElementById('formItemsJson').value = JSON.stringify(items);
        setTimeout(() => {
            ShopCart.clearCart();
        }, 500);
    });

    document.addEventListener('DOMContentLoaded', () => {
        // Initialize default shipping zone cost from checked radio
        const checkedShipping = document.querySelector('input[name="shipping_zone_id"]:checked');
        if (checkedShipping) {
            const labelCard = document.getElementById('shipCard_' + checkedShipping.value);
            if (labelCard && labelCard.getAttribute('onclick')) {
                // Already bound
            }
        }
        renderCheckoutSummary();
    });

    window.addEventListener('shop_cart_updated', () => {
        renderCheckoutSummary();
    });
</script>
@endsection

