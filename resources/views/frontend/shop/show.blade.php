@extends('layouts.frontend.shop')

@section('title', $product->name . ' | Asmi Supershop')

@section('extra_css')
<style>
    .prod-detail-card {
        background: #ffffff;
        border: 1px solid var(--shop-border);
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 40px;
    }
    .prod-main-img-wrap {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        height: 420px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .prod-main-img-wrap img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }
    .prod-main-img-wrap:hover img {
        transform: scale(1.05);
    }

    .prod-detail-title {
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 12px;
        line-height: 1.3;
    }
    .prod-detail-price {
        font-size: 28px;
        font-weight: 800;
        color: var(--shop-teal);
        margin-bottom: 12px;
        display: flex;
        align-items: baseline;
        gap: 12px;
    }
    .prod-detail-oldprice {
        font-size: 18px;
        color: #94a3b8;
        text-decoration: line-through;
        font-weight: 400;
    }

    /* Product Card Red Discount Badge (Top-Left) */
    .prod-discount-badge {
        position: absolute;
        top: 8px;
        left: 8px;
        background: #dc2626;
        color: #ffffff;
        font-size: 11px;
        font-weight: 800;
        line-height: 1.1;
        padding: 4px 7px;
        border-radius: 4px;
        z-index: 3;
        box-shadow: 0 2px 5px rgba(220, 38, 38, 0.35);
        text-align: center;
    }

    /* Countdown Timer Strip */
    .prod-timer-strip {
        background: #005f69;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 3px;
        padding: 3px 6px;
        border-radius: 4px;
        margin: 6px 0 8px;
        font-family: inherit;
    }
    .timer-segment {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 22px;
        line-height: 1;
    }
    .timer-segment .t-val {
        font-size: 11px;
        font-weight: 800;
        color: #ffffff;
    }
    .timer-segment .t-lbl {
        font-size: 8px;
        font-weight: 700;
        opacity: 0.85;
        letter-spacing: 0.3px;
    }
    .t-sep {
        font-size: 11px;
        font-weight: 800;
        color: #99f6e4;
        margin-top: -3px;
    }

    .prod-badge-strip {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .badge-spec-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 12.5px;
    }
    .badge-spec-label {
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        margin-right: 6px;
    }
    .badge-spec-value {
        font-weight: 700;
        color: #0f172a;
    }

    .prod-action-row {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }
    .prod-qty-selector {
        display: flex;
        align-items: center;
        border: 2px solid var(--shop-teal);
        border-radius: 8px;
        overflow: hidden;
    }
    .qty-btn-sel {
        background: #ffffff;
        border: none;
        width: 40px;
        height: 42px;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.15s;
    }
    .qty-btn-sel:hover {
        background: #f1f5f9;
    }
    .qty-input-sel {
        width: 50px;
        height: 42px;
        border: none;
        text-align: center;
        font-size: 15px;
        font-weight: 700;
        outline: none;
    }

    .btn-detail-add-bag {
        background: var(--shop-teal);
        color: #ffffff;
        border: none;
        border-radius: 8px;
        padding: 10px 32px;
        font-size: 15px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(0, 126, 138, 0.2);
    }
    .btn-detail-add-bag:hover {
        background: var(--shop-teal-dark);
        color: #ffffff;
        transform: translateY(-2px);
    }

    /* Product Description Box with Read More Collapse */
    .desc-wrapper {
        border-top: 1px solid #f1f5f9;
        padding-top: 24px;
        margin-top: 20px;
    }
    .desc-head-title {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 12px;
    }
    .desc-content-box {
        font-size: 14px;
        line-height: 1.7;
        color: #475569;
        position: relative;
    }
    .desc-content-box.collapsed {
        max-height: 90px;
        overflow: hidden;
        -webkit-mask-image: linear-gradient(180deg, #000 60%, transparent);
    }
    .btn-read-more-toggle {
        background: transparent;
        border: 1px solid #cbd5e1;
        border-radius: 20px;
        padding: 4px 16px;
        font-size: 12.5px;
        font-weight: 700;
        color: var(--shop-teal);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 10px;
        transition: all 0.2s;
    }
    .btn-read-more-toggle:hover {
        background: var(--shop-teal-light);
        border-color: var(--shop-teal);
    }
    @media (max-width: 768px) {
        .prod-detail-card {
            padding: 16px;
        }
        .prod-main-img-wrap {
            height: 250px;
            padding: 16px;
        }
        .prod-detail-title {
            font-size: 20px;
        }
        .prod-detail-price {
            font-size: 22px;
            margin-bottom: 14px;
        }
        .prod-action-row {
            gap: 10px;
            margin-bottom: 20px;
        }
        .btn-detail-add-bag {
            padding: 8px 20px;
            font-size: 14px;
            flex-grow: 1;
            justify-content: center;
        }
    }
</style>
@endsection

@section('shop_content')

<!-- Breadcrumb Navigation -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-teal text-decoration-none">Home</a></li>
        @if($product->category)
            <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="text-teal text-decoration-none">{{ $product->category->name }}</a></li>
        @endif
        @if($product->subcategory)
            <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category->slug ?? '', 'subcategory' => $product->subcategory->slug]) }}" class="text-teal text-decoration-none">{{ $product->subcategory->name }}</a></li>
        @endif
        <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
    </ol>
</nav>

<!-- Main Product View Card -->
<div class="prod-detail-card shadow-sm">
    <div class="row g-4">
        <!-- Product Image (Left) -->
        <div class="col-lg-5">
            <div class="prod-main-img-wrap">
                <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=800' }}" alt="{{ $product->name }}" id="mainProductImg">
            </div>
            @if($product->images && $product->images->isNotEmpty())
                <div class="d-flex flex-wrap gap-2 mt-3 justify-content-center">
                    <div class="border rounded p-1 cursor-pointer bg-white" style="cursor: pointer; width: 64px; height: 64px;" onclick="document.getElementById('mainProductImg').src='{{ asset($product->image) }}'">
                        <img src="{{ asset($product->image) }}" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    @foreach($product->images as $gImg)
                        <div class="border rounded p-1 cursor-pointer bg-white" style="cursor: pointer; width: 64px; height: 64px;" onclick="document.getElementById('mainProductImg').src='{{ asset($gImg->image) }}'">
                            <img src="{{ asset($gImg->image) }}" style="width: 100%; height: 100%; object-fit: contain;">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Details (Right) -->
        <div class="col-lg-7">
            <div class="ps-lg-3">
                <span class="prod-delivery-badge mb-2"><i class="fa fa-clock me-1"></i>{{ $product->delivery_time }}</span>
                
                <h1 class="prod-detail-title">{{ $product->name }}</h1>

                <div class="prod-detail-price">
                    <span id="currentDisplayPrice">{{ $product->formatted_price }}</span>
                    @if($product->formatted_old_price)
                        <span class="prod-detail-oldprice" id="currentDisplayOldPrice">{{ $product->formatted_old_price }}</span>
                        <span class="badge bg-danger fs-6 fw-bold ms-2">৳{{ round($product->old_price - $product->price) }} ছাড়</span>
                    @endif
                </div>

                @if($product->offer_end_date)
                    <div class="mb-3">
                        <div class="text-danger small fw-bold mb-1"><i class="fa fa-fire me-1"></i> অফারটি শেষ হতে বাকি আছে:</div>
                        <div class="prod-timer-strip" data-end-time="{{ \Carbon\Carbon::parse($product->offer_end_date)->toIso8601String() }}" style="max-width: 280px; padding: 6px 12px; margin: 0;">
                            <div class="timer-segment"><span class="t-val t-day" style="font-size: 13px;">00</span><span class="t-lbl">DAY</span></div>
                            <span class="t-sep" style="font-size: 13px;">:</span>
                            <div class="timer-segment"><span class="t-val t-hour" style="font-size: 13px;">00</span><span class="t-lbl">HR</span></div>
                            <span class="t-sep" style="font-size: 13px;">:</span>
                            <div class="timer-segment"><span class="t-val t-min" style="font-size: 13px;">00</span><span class="t-lbl">MIN</span></div>
                            <span class="t-sep" style="font-size: 13px;">:</span>
                            <div class="timer-segment"><span class="t-val t-sec" style="font-size: 13px;">00</span><span class="t-lbl">SEC</span></div>
                        </div>
                    </div>
                @endif

                @if($product->has_variants && $product->variants && $product->variants->isNotEmpty())
                    <!-- Variant Options Selector -->
                    <div class="mb-3 p-3 bg-light rounded-3 border">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-2">উপলব্ধ ভ্যারিয়েন্ট / সাইজ নির্বাচন করুন:</label>
                        <div class="d-flex flex-wrap gap-2" id="variantSelectorGroup">
                            @foreach($product->variants as $vIdx => $v)
                                @php
                                    $vLabel = '';
                                    if (is_array($v->attribute_values)) {
                                        foreach ($v->attribute_values as $ak => $av) {
                                            $vLabel .= ($vLabel ? ' / ' : '') . $av;
                                        }
                                    } else {
                                        $vLabel = $v->sku;
                                    }
                                @endphp
                                <button type="button" 
                                        class="btn btn-sm {{ $vIdx === 0 ? 'btn-teal text-white' : 'btn-outline-secondary' }} variant-option-btn" 
                                        data-price="{{ $v->price }}"
                                        data-old-price="{{ $v->old_price }}"
                                        data-sku="{{ $v->sku }}"
                                        data-label="{{ $vLabel }}"
                                        data-stock="{{ $v->stock_quantity }}"
                                        onclick="selectVariant(this)">
                                    {{ $vLabel }} - ৳{{ number_format($v->price, 0) }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Add to Bag and Quantity Controller -->
                <div class="prod-action-row">
                    <div class="prod-qty-selector">
                        <button type="button" class="qty-btn-sel" onclick="changeQty(-1)">−</button>
                        <input type="text" id="detailQtyInput" class="qty-input-sel" value="1" readonly>
                        <button type="button" class="qty-btn-sel" onclick="changeQty(1)">+</button>
                    </div>

                    <button type="button" class="btn-detail-add-bag" onclick="addCurrentProductToBag()">
                        <i class="fa fa-plus"></i>
                        <span>Add to Bag</span>
                    </button>
                </div>

                <!-- Badges: AVAILABILITY & SKU -->
                <div class="prod-badge-strip">
                    <div class="badge-spec-box">
                        <span class="badge-spec-label">AVAILABILITY</span>
                        <span class="badge-spec-value text-success"><i class="fa fa-circle-check me-1"></i>In Stock</span>
                    </div>

                    <div class="badge-spec-box">
                        <span class="badge-spec-label">SKU</span>
                        <span class="badge-spec-value">{{ $product->sku ?: '894118400' . $product->id }}</span>
                    </div>

                    @if($product->brand)
                        <div class="badge-spec-box">
                            <span class="badge-spec-label">BRAND</span>
                            <span class="badge-spec-value">{{ $product->brand }}</span>
                        </div>
                    @endif
                </div>

                <!-- Product Description with Read More ⌄ Collapsible Toggle -->
                <div class="desc-wrapper">
                    <h5 class="desc-head-title">Product Description</h5>
                    <div class="desc-content-box collapsed" id="descContentBox">
                        {!! nl2br(e($product->description)) !!}
                        @if(empty($product->description))
                            <p>Experience authentic quality and maximum freshness with {{ $product->name }}. Carefully packaged and sourced to ensure natural taste, essential nutrition, and hygienic purity for your daily household needs.</p>
                        @endif
                    </div>
                    <button type="button" class="btn-read-more-toggle" id="btnReadMoreToggle" onclick="toggleDescription()">
                        <span id="readMoreLabel">Read More</span>
                        <i class="fa fa-chevron-down" id="readMoreChevron"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================================= RELATED PRODUCTS ================================= -->
<div class="mb-5">
    <div class="shop-section-head">
        <h3 class="shop-section-title">Related Products</h3>
        <a href="{{ route('products.index', ['category' => $product->category->slug ?? '']) }}" class="text-teal text-decoration-none fw-bold small">
            আরও দেখুন <i class="fa fa-chevron-right ms-1"></i>
        </a>
    </div>

    <div class="row row-cols-2 row-cols-md-3 row-cols-xl-6 g-2 g-md-3">
        @foreach($relatedProducts as $rel)
            <div class="col">
                <div class="shop-product-card">
                    <div>
                        @if($rel->old_price && $rel->old_price > $rel->price)
                            <span class="prod-discount-badge">৳{{ round($rel->old_price - $rel->price) }}<br><small style="font-size: 8.5px; font-weight: 900;">OFF</small></span>
                        @endif
                        <span class="prod-delivery-badge"><i class="fa fa-clock me-1"></i>{{ $rel->delivery_time }}</span>
                        <a href="{{ route('products.show', $rel->slug) }}" class="prod-img-wrap">
                            <img src="{{ $rel->image ?: 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=400' }}" alt="{{ $rel->name }}" loading="lazy">
                        </a>

                        @if($rel->offer_end_date)
                            <div class="prod-timer-strip" data-end-time="{{ \Carbon\Carbon::parse($rel->offer_end_date)->toIso8601String() }}">
                                <div class="timer-segment"><span class="t-val t-day">00</span><span class="t-lbl">DAY</span></div>
                                <span class="t-sep">:</span>
                                <div class="timer-segment"><span class="t-val t-hour">00</span><span class="t-lbl">HR</span></div>
                                <span class="t-sep">:</span>
                                <div class="timer-segment"><span class="t-val t-min">00</span><span class="t-lbl">MIN</span></div>
                                <span class="t-sep">:</span>
                                <div class="timer-segment"><span class="t-val t-sec">00</span><span class="t-lbl">SEC</span></div>
                            </div>
                        @endif

                        <a href="{{ route('products.show', $rel->slug) }}" class="prod-name" title="{{ $rel->name }}">
                            {{ $rel->name }}
                        </a>
                    </div>
                    <div>
                        <div class="prod-price-wrap">
                            <span>{{ $rel->formatted_price }}</span>
                            @if($rel->formatted_old_price)
                                <span class="prod-old-price">{{ $rel->formatted_old_price }}</span>
                            @endif
                        </div>
                        <button type="button" class="btn-add-bag" data-id="{{ $rel->id }}" data-name="{{ $rel->name }}" data-price="{{ $rel->price }}" data-image="{{ $rel->image }}" data-sku="{{ $rel->sku }}" data-delivery="{{ $rel->delivery_time }}" onclick="addCardToBag(this)">
                            <i class="fa fa-plus"></i>
                            <span>Add to Bag</span>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection

@section('extra_js')
<script>
    let currentQty = 1;
    let selectedVariantPrice = {{ (float)$product->price }};
    let selectedVariantSku = @json($product->sku);
    let selectedVariantLabel = '';

    function selectVariant(btn) {
        document.querySelectorAll('.variant-option-btn').forEach(b => {
            b.classList.remove('btn-teal', 'text-white');
            b.classList.add('btn-outline-secondary');
        });
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-teal', 'text-white');

        const price = parseFloat(btn.getAttribute('data-price')) || selectedVariantPrice;
        const oldPrice = parseFloat(btn.getAttribute('data-old-price'));
        const sku = btn.getAttribute('data-sku');
        const label = btn.getAttribute('data-label');

        selectedVariantPrice = price;
        selectedVariantSku = sku;
        selectedVariantLabel = label;

        const displayPriceEl = document.getElementById('currentDisplayPrice');
        if (displayPriceEl) {
            displayPriceEl.textContent = '৳ ' + Math.round(price);
        }
        const displayOldPriceEl = document.getElementById('currentDisplayOldPrice');
        if (displayOldPriceEl) {
            if (oldPrice && oldPrice > price) {
                displayOldPriceEl.textContent = '৳ ' + Math.round(oldPrice);
                displayOldPriceEl.style.display = 'inline';
            } else {
                displayOldPriceEl.style.display = 'none';
            }
        }
    }

    function changeQty(delta) {
        currentQty = Math.max(1, currentQty + delta);
        document.getElementById('detailQtyInput').value = currentQty;
    }

    function addCurrentProductToBag() {
        const itemName = selectedVariantLabel ? (@json($product->name) + ' (' + selectedVariantLabel + ')') : @json($product->name);
        ShopCart.addItem({
            id: {{ $product->id }},
            name: itemName,
            price: selectedVariantPrice,
            image: @json($product->image),
            sku: selectedVariantSku,
            delivery_time: @json($product->delivery_time),
            quantity: currentQty,
            variant_details: selectedVariantLabel ? { variant: selectedVariantLabel } : null
        });
    }

    function toggleDescription() {
        const descBox = document.getElementById('descContentBox');
        const label = document.getElementById('readMoreLabel');
        const chevron = document.getElementById('readMoreChevron');

        if (descBox.classList.contains('collapsed')) {
            descBox.classList.remove('collapsed');
            label.textContent = 'Read Less';
            chevron.classList.remove('fa-chevron-down');
            chevron.classList.add('fa-chevron-up');
        } else {
            descBox.classList.add('collapsed');
            label.textContent = 'Read More';
            chevron.classList.remove('fa-chevron-up');
            chevron.classList.add('fa-chevron-down');
        }
    }

    // Dynamic Live Countdown Timer for Deals & Offers
    function updateCountdowns() {
        const now = new Date().getTime();
        document.querySelectorAll('.prod-timer-strip').forEach(el => {
            const endTimeStr = el.getAttribute('data-end-time');
            if (!endTimeStr) return;
            const endTime = new Date(endTimeStr).getTime();
            const distance = endTime - now;

            if (distance < 0) {
                el.style.display = 'none';
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            const dayEl = el.querySelector('.t-day');
            const hrEl = el.querySelector('.t-hour');
            const minEl = el.querySelector('.t-min');
            const secEl = el.querySelector('.t-sec');

            if (dayEl) dayEl.textContent = String(days).padStart(2, '0');
            if (hrEl) hrEl.textContent = String(hours).padStart(2, '0');
            if (minEl) minEl.textContent = String(minutes).padStart(2, '0');
            if (secEl) secEl.textContent = String(seconds).padStart(2, '0');
        });
    }

    updateCountdowns();
    setInterval(updateCountdowns, 1000);
</script>
@endsection
