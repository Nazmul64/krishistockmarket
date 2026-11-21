@extends('layouts.frontend.shop')

@section('title', $product->name . ' | e-Krishi Sheba - ই-কৃষি সেবা')

@section('extra_css')
<style>
    .prod-detail-card {
        background: #ffffff;
        border: 1px solid var(--shop-border);
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 40px;
    }

    /* Product Image Outer & Zoom Container */
    .prod-img-outer {
        position: relative;
    }
    .prod-main-img-wrap {
        position: relative;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0 !important;
        text-align: center;
        height: 440px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        cursor: crosshair;
    }
    .prod-main-img-wrap img {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
        object-fit: contain !important;
        user-select: none;
        pointer-events: none;
    }

    /* Interactive Zoom Lens */
    .zoom-lens {
        position: absolute;
        width: 140px;
        height: 140px;
        border: 2px solid #008080;
        background: rgba(0, 128, 128, 0.15);
        border-radius: 6px;
        pointer-events: none;
        display: none;
        z-index: 10;
        box-shadow: 0 0 10px rgba(0, 128, 128, 0.2);
    }

    /* Flyout Zoom Preview Window */
    .zoom-flyout-window {
        position: absolute;
        top: 0;
        left: 103%;
        width: 480px;
        height: 440px;
        background: #ffffff;
        border: 2px solid #cbd5e1;
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.18);
        z-index: 1050;
        display: none;
        background-repeat: no-repeat;
        overflow: hidden;
        pointer-events: none;
    }

    /* Multiple Pictures / Gallery Thumbnails Slider */
    .thumb-slider-container {
        display: flex;
        align-items: center;
        gap: 6px;
        width: 100%;
        margin-top: 12px;
    }
    .thumb-slider-track {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        scroll-behavior: smooth;
        padding: 4px 2px;
        scrollbar-width: thin;
        flex-grow: 1;
    }
    .thumb-slider-track::-webkit-scrollbar {
        height: 4px;
    }
    .thumb-slider-track::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .thumb-card {
        flex: 0 0 68px;
        height: 76px;
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 3px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .thumb-card img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain !important;
        border-radius: 5px;
    }
    .thumb-card.active, .thumb-card:hover {
        border-color: #ea580c;
        box-shadow: 0 2px 8px rgba(234, 88, 12, 0.25);
        transform: scale(1.02);
    }
    .thumb-nav-btn {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        cursor: pointer;
        transition: all 0.15s;
        color: #475569;
        flex-shrink: 0;
    }
    .thumb-nav-btn:hover {
        background: #008080;
        color: #ffffff;
        border-color: #008080;
    }

    /* Product Title & Code */
    .prod-detail-title {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
        line-height: 1.35;
    }
    .prod-code-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 3px 12px;
        font-size: 12px;
        color: #475569;
        margin-bottom: 12px;
    }
    .btn-copy-code {
        background: transparent;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        padding: 1px 8px;
        font-size: 10.5px;
        font-weight: 700;
        color: #334155;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s;
    }
    .btn-copy-code:hover {
        background: #008080;
        color: #ffffff;
        border-color: #008080;
    }

    /* Price styling */
    .prod-detail-price {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 16px;
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

    /* Quantity and Action Buttons */
    .prod-action-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }
    .prod-qty-selector {
        display: flex;
        align-items: center;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        overflow: hidden;
        background: #ffffff;
    }
    .qty-btn-sel {
        background: #f8fafc;
        border: none;
        width: 36px;
        height: 38px;
        font-size: 16px;
        font-weight: 700;
        color: #334155;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.15s;
    }
    .qty-btn-sel:hover {
        background: #e2e8f0;
    }
    .qty-input-sel {
        width: 44px;
        height: 38px;
        border: none;
        text-align: center;
        font-size: 14px;
        font-weight: 700;
        outline: none;
    }

    /* Action Buttons Matching Admin Appearance & Theme */
    .btn-buy-now {
        background: var(--btn-theme-color, #007e8a) !important;
        color: var(--btn-theme-text-color, #ffffff) !important;
        font-weight: 800;
        font-size: var(--product-btn-size, 15px) !important;
        padding: 10px 24px;
        border-radius: var(--btn-theme-radius, 8px) !important;
        border: 1px solid var(--btn-theme-color, #007e8a) !important;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.25s;
        box-shadow: 0 4px 14px rgba(0, 126, 138, 0.3);
    }
    .btn-buy-now:hover {
        background: var(--btn-theme-hover, #005f69) !important;
        border-color: var(--btn-theme-hover, #005f69) !important;
        color: var(--btn-theme-text-color, #ffffff) !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 126, 138, 0.45);
    }

    .btn-add-cart-outline {
        background: #ffffff;
        color: #0f172a;
        font-weight: 800;
        font-size: 15px;
        padding: 10px 24px;
        border-radius: 8px;
        border: 2px solid #0f172a;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-add-cart-outline:hover {
        background: #0f172a;
        color: #ffffff;
        transform: translateY(-2px);
    }

    .btn-whatsapp-order {
        background: #ecfdf5;
        color: #059669;
        font-weight: 700;
        font-size: 13.5px;
        padding: 8px 18px;
        border-radius: 8px;
        border: 1px solid #a7f3d0;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-whatsapp-order:hover {
        background: #059669;
        color: #ffffff;
        border-color: #059669;
    }

    .btn-call-order {
        background: #eff6ff;
        color: #2563eb;
        font-weight: 700;
        font-size: 13.5px;
        padding: 8px 18px;
        border-radius: 8px;
        border: 1px solid #bfdbfe;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-call-order:hover {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
    }

    /* Product Video Card (Right Column) */
    .product-video-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        position: sticky;
        top: 90px;
    }
    .video-card-title {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
    }
    .video-embed-wrapper {
        position: relative;
        width: 100%;
        border-radius: 10px;
        overflow: hidden;
        background: #0f172a;
        aspect-ratio: 9/16; /* Vertical video / Shorts style */
        max-height: 480px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.12);
    }
    .video-embed-wrapper iframe, .video-embed-wrapper video {
        width: 100%;
        height: 100%;
        border: none;
        object-fit: cover;
    }

    /* Product Description Box with Read More Collapse */
    .desc-wrapper {
        border-top: 1px solid #f1f5f9;
        padding-top: 20px;
        margin-top: 20px;
    }
    .desc-head-title {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 10px;
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

    @media (max-width: 991px) {
        .zoom-flyout-window {
            display: none !important;
        }
        .zoom-lens {
            display: none !important;
        }
        .prod-main-img-wrap {
            height: 320px;
            cursor: default;
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

@php
    // Collect all unique images for multiple image slider
    $allImages = $product->all_images;
    if (empty($allImages) && !empty($product->image)) {
        $allImages = [$product->image];
    }
    $hasVideo = !empty($product->video_url);
@endphp

<!-- Main Product View Card -->
<div class="prod-detail-card shadow-sm">
    <div class="row g-4">
        
        <!-- =================== 1. Product Image & Multiple Pictures Slider (Left) =================== -->
        <div class="{{ $hasVideo ? 'col-lg-4 col-md-5' : 'col-lg-5 col-md-6' }}">
            <div class="prod-img-outer" id="prodImgContainer">
                <div class="prod-main-img-wrap" id="mainImgWrap">
                    <img src="{{ asset($product->image ?: 'uploads/products/default.png') }}" onerror="this.onerror=null;this.src='{{ asset('uploads/products/default.png') }}';" alt="{{ $product->name }}" id="mainProductImg">
                    <!-- Zoom Lens -->
                    <div id="zoomLens" class="zoom-lens"></div>
                </div>

                <!-- Flyout Zoom Box (Opens on right when hovered) -->
                <div id="zoomFlyoutWindow" class="zoom-flyout-window"></div>
            </div>

            <!-- Multiple Pictures Thumbnails Slider (Below Main Image) -->
            @if(count($allImages) > 1)
                <div class="thumb-slider-container">
                    <button type="button" class="thumb-nav-btn prev-btn" onclick="scrollThumbs(-1)" aria-label="Previous">
                        <i class="fa fa-chevron-left"></i>
                    </button>
                    <div class="thumb-slider-track" id="thumbSliderTrack">
                        @foreach($allImages as $idx => $tImg)
                            <div class="thumb-card {{ $idx === 0 ? 'active' : '' }}" 
                                 onclick="changeMainImage('{{ asset($tImg) }}', this)"
                                 onmouseenter="changeMainImage('{{ asset($tImg) }}', this)">
                                <img src="{{ asset($tImg) }}" alt="{{ $product->name }} view {{ $idx + 1 }}" onerror="this.onerror=null;this.src='{{ asset('uploads/products/default.png') }}';">
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="thumb-nav-btn next-btn" onclick="scrollThumbs(1)" aria-label="Next">
                        <i class="fa fa-chevron-right"></i>
                    </button>
                </div>
            @endif
        </div>

        <!-- =================== 2. Product Details & Action Buttons (Center) =================== -->
        <div class="{{ $hasVideo ? 'col-lg-5 col-md-7' : 'col-lg-7 col-md-6' }}">
            <div class="ps-lg-2">
                <h1 class="prod-detail-title">{{ $product->name }}</h1>

                <!-- Product Code (Pill display) -->
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="prod-code-badge">
                        <i class="fa fa-barcode text-muted"></i>
                        <span>Code: <strong class="text-dark">{{ $product->sku ?: 'SKU-' . $product->id }}</strong></span>
                    </div>

                    @if($product->delivery_time)
                        <span class="badge bg-light text-muted border px-2 py-1 small"><i class="fa fa-clock text-info me-1"></i>{{ $product->delivery_time }}</span>
                    @endif
                </div>

                <!-- Price & Discount -->
                <div class="prod-detail-price">
                    <span id="currentDisplayPrice">{{ $product->formatted_price }}</span>
                    @if($product->formatted_old_price)
                        <span class="prod-detail-oldprice" id="currentDisplayOldPrice">{{ $product->formatted_old_price }}</span>
                        <span class="badge bg-danger fs-6 fw-bold ms-2">৳{{ round($product->old_price - $product->price) }} ছাড়</span>
                    @endif
                </div>

                <!-- Countdown Timer (if promo offer) -->
                @if($product->offer_end_date && \Carbon\Carbon::parse($product->offer_end_date)->isFuture())
                    <div class="mb-3">
                        <div class="text-danger small fw-bold mb-1"><i class="fa fa-fire me-1"></i> অফারটি শেষ হতে বাকি আছে:</div>
                        <div class="prod-timer-strip" data-end-time="{{ \Carbon\Carbon::parse($product->offer_end_date)->toIso8601String() }}" style="max-width: 260px; padding: 5px 10px; margin: 0;">
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

                <!-- Variant Options (if any) -->
                @if($product->has_variants && $product->variants && $product->variants->isNotEmpty())
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

                <!-- Quantity Selector -->
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="small fw-bold text-muted text-uppercase">Quantity:</span>
                    <div class="prod-qty-selector">
                        <button type="button" class="qty-btn-sel" onclick="changeQty(-1)">−</button>
                        <input type="text" id="detailQtyInput" class="qty-input-sel" value="1" readonly>
                        <button type="button" class="qty-btn-sel" onclick="changeQty(1)">+</button>
                    </div>
                </div>

                <!-- Primary Action Buttons (অর্ডার করুন & কার্টে রাখুন) -->
                @php
                    $dynOrderText = \App\Models\Ui\UiComponentSetting::get('product_order_btn_text', 'অর্ডার করুন');
                    $dynBtnAnim = \App\Models\Ui\UiComponentSetting::get('global_button_animation', true);
                @endphp
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <button type="button" class="btn btn-buy-now {{ $dynBtnAnim ? 'btn-animated-pulse' : '' }}" onclick="buyNowCurrentProduct()">
                        <i class="fa fa-bolt"></i>
                        <span>{{ $dynOrderText }}</span>
                    </button>
                    <button type="button" class="btn btn-add-cart-outline" onclick="addCurrentProductToBag()">
                        <i class="fa fa-shopping-bag"></i>
                        <span>কার্টে রাখুন</span>
                    </button>
                </div>

                <!-- Contact & Call for Order Buttons -->
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <a href="https://wa.me/8801805020340?text={{ urlencode('আমি এই পণ্যটি কিনতে আগ্রহী: ' . $product->name . ' (Code: ' . ($product->sku ?: 'SKU-' . $product->id) . ')') }}" target="_blank" class="btn btn-whatsapp-order">
                        <i class="fab fa-whatsapp fs-5"></i>
                        <span>01805-020340</span>
                    </a>
                    <a href="tel:01805020340" class="btn btn-call-order">
                        <i class="fa fa-phone-alt"></i>
                        <span>Call For Order</span>
                    </a>
                </div>

                <!-- Product Description with Show More Collapse -->
                <div class="desc-wrapper">
                    <h5 class="desc-head-title">Description</h5>
                    <div class="desc-content-box collapsed" id="descContentBox">
                        <p class="fw-bold mb-1">{{ $product->name }}</p>
                        {!! nl2br(e($product->description)) !!}
                        @if(empty($product->description))
                            <p>Add a fresh touch to your daily lifestyle with {{ $product->name }}. Carefully sourced and verified for authentic quality, durability, and complete customer satisfaction.</p>
                        @endif
                    </div>
                    <button type="button" class="btn-read-more-toggle" id="btnReadMoreToggle" onclick="toggleDescription()">
                        <span id="readMoreLabel">Show more</span>
                        <i class="fa fa-chevron-down" id="readMoreChevron"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- =================== 3. Dedicated Product Video Card (Right Column) =================== -->
        @if($hasVideo)
            <div class="col-lg-3 col-md-12">
                <div class="product-video-card shadow-sm">
                    <h6 class="video-card-title">
                        <i class="fa fa-play-circle text-danger me-2 fs-5"></i> Product Video
                    </h6>
                    <div class="video-embed-wrapper">
                        @if(strpos($product->video_url, 'youtube.com') !== false || strpos($product->video_url, 'youtu.be') !== false)
                            @php
                                $embedUrl = $product->video_url;
                                if (preg_match('/(?:shorts\/|v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $product->video_url, $vMatch)) {
                                    $embedUrl = 'https://www.youtube.com/embed/' . $vMatch[1] . '?autoplay=0&rel=0';
                                }
                            @endphp
                            <iframe src="{{ $embedUrl }}" title="{{ $product->name }} video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        @else
                            <video controls poster="{{ asset('uploads/products/three_piece_video_poster.jpg') }}" playsinline class="w-100 h-100">
                                <source src="{{ $product->video_url }}" type="video/mp4">
                                আপনার ব্রাউজার ভিডিও প্লেব্যাক সমর্থন করে না।
                            </video>
                        @endif
                    </div>
                </div>
            </div>
        @endif

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
                        <a href="{{ route('products.show', $rel->slug) }}" class="prod-img-wrap">
                            <img src="{{ asset($rel->image ?: 'uploads/products/default.png') }}" onerror="this.onerror=null;this.src='{{ asset('uploads/products/default.png') }}';" alt="{{ $rel->name }}" loading="lazy">
                        </a>

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
                        <button type="button" class="btn-add-bag" data-id="{{ $rel->id }}" data-name="{{ $rel->name }}" data-price="{{ $rel->price }}" data-image="{{ asset($rel->image ?: 'uploads/products/default.png') }}" data-sku="{{ $rel->sku }}" data-delivery="{{ $rel->delivery_time }}" onclick="addCardToBag(this)">
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

    // Multiple Image Switching
    function changeMainImage(imgUrl, thumbEl) {
        const mainImg = document.getElementById('mainProductImg');
        const flyout = document.getElementById('zoomFlyoutWindow');
        if (mainImg) {
            mainImg.src = imgUrl;
        }
        if (flyout) {
            flyout.style.backgroundImage = `url("${imgUrl}")`;
        }
        document.querySelectorAll('.thumb-card').forEach(c => c.classList.remove('active'));
        if (thumbEl) {
            thumbEl.classList.add('active');
        }
    }

    // Thumbnails horizontal scroll
    function scrollThumbs(direction) {
        const track = document.getElementById('thumbSliderTrack');
        if (track) {
            track.scrollBy({ left: direction * 150, behavior: 'smooth' });
        }
    }

    // Copy Product Code
    function copyProductCode(code, btn) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(code).then(() => {
                const lbl = btn.querySelector('.copy-lbl') || btn;
                const orig = lbl.textContent;
                lbl.textContent = 'Copied!';
                btn.style.borderColor = '#008080';
                btn.style.color = '#008080';
                setTimeout(() => {
                    lbl.textContent = orig;
                    btn.style.borderColor = '';
                    btn.style.color = '';
                }, 2000);
            });
        }
    }

    // Variant Selection
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

    // Quantity Selector
    function changeQty(delta) {
        currentQty = Math.max(1, currentQty + delta);
        document.getElementById('detailQtyInput').value = currentQty;
    }

    // Add to Cart
    function addCurrentProductToBag() {
        const itemName = selectedVariantLabel ? (@json($product->name) + ' (' + selectedVariantLabel + ')') : @json($product->name);
        ShopCart.addItem({
            id: {{ $product->id }},
            name: itemName,
            price: selectedVariantPrice,
            image: document.getElementById('mainProductImg') ? document.getElementById('mainProductImg').src : @json(asset($product->image ?: 'uploads/products/default.png')),
            sku: selectedVariantSku,
            delivery_time: @json($product->delivery_time),
            quantity: currentQty,
            variant_details: selectedVariantLabel ? { variant: selectedVariantLabel } : null
        });
    }

    // Buy Now (Instant checkout)
    function buyNowCurrentProduct() {
        addCurrentProductToBag();
        window.location.href = "{{ route('shop.checkout') }}";
    }

    // Description Read More Toggle
    function toggleDescription() {
        const descBox = document.getElementById('descContentBox');
        const label = document.getElementById('readMoreLabel');
        const chevron = document.getElementById('readMoreChevron');

        if (descBox.classList.contains('collapsed')) {
            descBox.classList.remove('collapsed');
            label.textContent = 'Show less';
            chevron.classList.remove('fa-chevron-down');
            chevron.classList.add('fa-chevron-up');
        } else {
            descBox.classList.add('collapsed');
            label.textContent = 'Show more';
            chevron.classList.remove('fa-chevron-up');
            chevron.classList.add('fa-chevron-down');
        }
    }

    // Interactive Hover Lens & Side Flyout Zoom Window
    document.addEventListener('DOMContentLoaded', function() {
        const imgWrap = document.getElementById('mainImgWrap');
        const mainImg = document.getElementById('mainProductImg');
        const lens = document.getElementById('zoomLens');
        const flyout = document.getElementById('zoomFlyoutWindow');

        if (!imgWrap || !mainImg || !lens || !flyout) return;

        function updateZoomBg() {
            flyout.style.backgroundImage = `url("${mainImg.src}")`;
        }
        updateZoomBg();

        imgWrap.addEventListener('mouseenter', function() {
            if (window.innerWidth < 992) return;
            updateZoomBg();
            lens.style.display = 'block';
            flyout.style.display = 'block';
        });

        imgWrap.addEventListener('mouseleave', function() {
            lens.style.display = 'none';
            flyout.style.display = 'none';
        });

        imgWrap.addEventListener('mousemove', function(e) {
            if (window.innerWidth < 992) return;
            const rect = imgWrap.getBoundingClientRect();

            const lensW = lens.offsetWidth || 140;
            const lensH = lens.offsetHeight || 140;

            let x = e.clientX - rect.left - (lensW / 2);
            let y = e.clientY - rect.top - (lensH / 2);

            if (x < 0) x = 0;
            if (y < 0) y = 0;
            if (x > rect.width - lensW) x = rect.width - lensW;
            if (y > rect.height - lensH) y = rect.height - lensH;

            lens.style.left = x + 'px';
            lens.style.top = y + 'px';

            const zoomRatio = 2.6;
            flyout.style.backgroundSize = `${rect.width * zoomRatio}px ${rect.height * zoomRatio}px`;
            flyout.style.backgroundPosition = `-${x * zoomRatio}px -${y * zoomRatio}px`;
        });
    });
</script>
@endsection
