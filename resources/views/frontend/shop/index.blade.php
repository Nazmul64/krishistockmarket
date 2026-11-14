@extends('layouts.frontend.shop')

@section('title', 'Asmi Supershop | e-Krishi Sheba - নিত্যপ্রয়োজনীয় সুপারশপ')

@section('extra_css')
<style>
    /* ================= 1. HERO SLIDER BANNER ================= */
    .shop-hero-slider {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--shop-border);
        margin-bottom: 25px;
        background: #f8fafc;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
    }
    .hero-slide {
        display: none;
        width: 100%;
        line-height: 0;
    }
    .hero-slide.active {
        display: block;
        animation: fadeInSlide 0.4s ease-in-out;
    }
    @keyframes fadeInSlide {
        from { opacity: 0; transform: scale(0.99); }
        to { opacity: 1; transform: scale(1); }
    }
    .hero-full-link {
        display: block;
        width: 100%;
        text-decoration: none;
        line-height: 0;
    }
    .hero-full-img {
        width: 100%;
        height: auto;
        display: block;
        border-radius: 12px;
    }

    .slider-ctrl-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 38px;
        height: 38px;
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid var(--shop-border);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1e293b;
        cursor: pointer;
        z-index: 10;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .slider-ctrl-btn:hover {
        background: var(--shop-teal);
        color: #ffffff;
        border-color: var(--shop-teal);
        transform: translateY(-50%) scale(1.08);
    }
    .slider-ctrl-prev { left: 14px; }
    .slider-ctrl-next { right: 14px; }

    .slider-dots {
        position: absolute;
        bottom: 12px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 8px;
        z-index: 10;
        background: rgba(0, 0, 0, 0.2);
        padding: 4px 10px;
        border-radius: 20px;
        backdrop-filter: blur(4px);
    }
    .slider-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.65);
        cursor: pointer;
        transition: all 0.25s ease;
    }
    .slider-dot.active {
        background: #ffffff;
        width: 22px;
        border-radius: 10px;
    }

    /* ================= 2. CATEGORIES HORIZONTAL CAROUSEL ================= */
    .cat-carousel-container {
        margin-bottom: 35px;
    }
    .cat-carousel-wrapper {
        position: relative;
        padding: 0 16px;
    }
    .cat-carousel-track {
        display: flex;
        gap: 12px;
        overflow-x: auto;
        scroll-behavior: smooth;
        scrollbar-width: none;
        -ms-overflow-style: none;
        padding: 4px 2px 8px;
    }
    .cat-carousel-track::-webkit-scrollbar {
        display: none;
    }

    .cat-slide-item {
        flex: 0 0 calc((100% - (7 * 12px)) / 8);
        min-width: 105px;
        text-align: center;
    }
    @media (max-width: 1400px) {
        .cat-slide-item { flex: 0 0 calc((100% - (6 * 12px)) / 7); }
    }
    @media (max-width: 1200px) {
        .cat-slide-item { flex: 0 0 calc((100% - (5 * 10px)) / 6); }
    }
    @media (max-width: 991px) {
        .cat-slide-item { flex: 0 0 calc((100% - (4 * 10px)) / 5); }
    }
    @media (max-width: 767px) {
        .cat-slide-item { flex: 0 0 calc((100% - (3 * 8px)) / 4); min-width: 85px; }
    }
    @media (max-width: 480px) {
        .cat-slide-item { flex: 0 0 calc((100% - (2 * 8px)) / 3.2); }
    }

    .cat-item-link {
        text-decoration: none;
        display: block;
    }
    .cat-box-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        aspect-ratio: 1 / 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px;
        transition: all 0.25s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
    }
    .cat-item-link:hover .cat-box-card {
        border-color: var(--shop-teal);
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0, 126, 138, 0.12);
    }
    .cat-box-img {
        width: 85%;
        height: 85%;
        object-fit: contain;
        transition: transform 0.2s ease;
    }
    .cat-item-link:hover .cat-box-img {
        transform: scale(1.08);
    }
    .cat-box-icon {
        font-size: 32px;
        color: var(--shop-teal);
    }
    .cat-box-name {
        margin-top: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        line-height: 1.25;
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: color 0.2s;
    }
    .cat-item-link:hover .cat-box-name {
        color: var(--shop-teal);
        font-weight: 700;
    }

    .cat-carousel-nav {
        position: absolute;
        top: 38%;
        transform: translateY(-50%);
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #ffffff;
        border: 1px solid var(--shop-border);
        color: #334155;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 5;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    }
    .cat-carousel-nav:hover {
        background: var(--shop-teal);
        color: #ffffff;
        border-color: var(--shop-teal);
        transform: translateY(-50%) scale(1.08);
    }
    .cat-nav-prev { left: -8px; }
    .cat-nav-next { right: -8px; }

    /* ================= 3. ASMI PRODUCT CARD DESIGN ================= */
    .shop-product-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 10px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        text-align: center;
        position: relative;
        transition: all 0.22s ease;
    }
    .shop-product-card:hover {
        border-color: var(--shop-teal);
        box-shadow: 0 4px 15px rgba(0, 126, 138, 0.08);
        transform: translateY(-2px);
    }
    .prod-img-wrap {
        display: block;
        width: 100%;
        height: 135px;
        margin-bottom: 6px;
        position: relative;
        text-decoration: none;
    }
    .prod-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.2s ease;
    }
    .shop-product-card:hover .prod-img-wrap img {
        transform: scale(1.05);
    }

    .badge-out-of-stock {
        position: absolute;
        top: 6px;
        right: 6px;
        background: rgba(30, 41, 59, 0.85);
        color: #ffffff;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 12px;
        z-index: 2;
    }

    .prod-delivery-sub {
        font-size: 11px;
        color: #64748b;
        font-style: italic;
        margin-bottom: 4px;
        line-height: 1.2;
    }
    .prod-name {
        font-size: 13px;
        font-weight: 500;
        color: #1e293b;
        line-height: 1.35;
        min-height: 35px;
        margin-bottom: 6px;
        text-decoration: none;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.15s;
    }
    .prod-name:hover {
        color: var(--shop-teal);
    }
    .prod-price-wrap {
        font-size: 15px;
        font-weight: 700;
        color: var(--shop-teal);
        margin-bottom: 8px;
    }
    .btn-add-bag {
        background: var(--shop-teal);
        color: #ffffff;
        border: none;
        border-radius: 6px;
        padding: 7px 10px;
        font-size: 12.5px;
        font-weight: 600;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-add-bag:hover {
        background: var(--shop-teal-dark);
        color: #ffffff;
    }
    .btn-out-stock {
        background: #f1f5f9;
        color: #94a3b8;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 7px 10px;
        font-size: 12px;
        font-weight: 600;
        width: 100%;
        cursor: not-allowed;
    }

    /* Product Horizontal Scrolling Carousel Track (6 items per row) */
    .product-carousel-track {
        display: flex;
        gap: 12px;
        overflow-x: auto;
        scroll-behavior: smooth;
        scrollbar-width: none;
        padding: 4px 2px 10px;
    }
    .product-carousel-track::-webkit-scrollbar {
        display: none;
    }
    .product-carousel-item {
        flex: 0 0 calc((100% - (5 * 12px)) / 6);
        min-width: 155px;
    }
    @media (max-width: 1400px) {
        .product-carousel-item { flex: 0 0 calc((100% - (4 * 12px)) / 5); }
    }
    @media (max-width: 1100px) {
        .product-carousel-item { flex: 0 0 calc((100% - (3 * 10px)) / 4); }
    }
    @media (max-width: 768px) {
        .product-carousel-item { flex: 0 0 calc((100% - (1 * 8px)) / 2.3); min-width: 135px; }
    }

    /* ================= 4. HEADINGS & BUTTONS ================= */
    .exclusive-offer-badge {
        background: var(--shop-teal);
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 4px 14px;
        border-radius: 20px;
        letter-spacing: 0.5px;
        display: inline-block;
    }
    .title-underline-bar {
        display: block;
        width: 45px;
        height: 3px;
        background: var(--shop-teal);
        border-radius: 2px;
        margin: 4px auto 0;
    }
    .title-underline-bar-left {
        display: block;
        width: 45px;
        height: 3px;
        background: var(--shop-teal);
        border-radius: 2px;
        margin: 4px 0 0;
    }

    .btn-asmi-view-all {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border: 1.5px solid var(--shop-teal);
        color: var(--shop-teal);
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
        background: #ffffff;
    }
    .btn-asmi-view-all:hover {
        background: var(--shop-teal);
        color: #ffffff;
        transform: translateX(2px);
    }

    /* Explore Our Products Tab Pills */
    .explore-tab-pills {
        background: #f1f5f9;
        padding: 4px;
        border-radius: 30px;
        display: inline-flex;
        gap: 6px;
    }
    .explore-pill {
        border: none;
        background: transparent;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        padding: 6px 18px;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .explore-pill.active {
        background: var(--shop-teal);
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(0, 126, 138, 0.25);
    }

    /* Carousel Pagination Dots (Pink & Teal like ASMI) */
    .section-dots {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 6px;
        margin-top: 14px;
    }
    .sec-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #fbcfe8;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .sec-dot.active {
        background: var(--shop-teal);
        width: 10px;
        height: 10px;
    }

    /* Countdown Timer Strip (ONLY on Daily Offers page) */
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
    }
    .t-sep {
        font-size: 11px;
        font-weight: 800;
        color: #99f6e4;
        margin-top: -3px;
    }

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

    .collection-page-banner {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--shop-border);
    }
    .collection-banner-img {
        width: 100%;
        height: auto;
        display: block;
        border-radius: 12px;
    }
</style>
@endsection

@section('shop_content')

@if(!$isFiltered)
    <!-- ================================= 1. HERO SLIDER ================================= -->
    <div class="shop-hero-slider shadow-sm" id="shopHeroSlider">
        @if(isset($sliders) && $sliders->isNotEmpty())
            @foreach($sliders as $sIdx => $sl)
                <div class="hero-slide {{ $sIdx === 0 ? 'active' : '' }}">
                    <a href="{{ $sl->button_link ?: '#exclusive-deals' }}" class="hero-full-link">
                        <img src="{{ asset($sl->image) }}" alt="{{ $sl->title }}" class="hero-full-img">
                    </a>
                </div>
            @endforeach
        @else
            <div class="hero-slide active">
                <a href="#exclusive-deals" class="hero-full-link">
                    <img src="{{ asset('uploads/slider/slider-1.svg') }}" alt="Super Offer" class="hero-full-img">
                </a>
            </div>
        @endif

        <button type="button" class="slider-ctrl-btn slider-ctrl-prev" onclick="prevSlide()" aria-label="Previous Slide">
            <i class="fa fa-chevron-left"></i>
        </button>
        <button type="button" class="slider-ctrl-btn slider-ctrl-next" onclick="nextSlide()" aria-label="Next Slide">
            <i class="fa fa-chevron-right"></i>
        </button>

        <div class="slider-dots">
            @if(isset($sliders) && $sliders->count() > 1)
                @foreach($sliders as $dIdx => $sl)
                    <span class="slider-dot {{ $dIdx === 0 ? 'active' : '' }}" onclick="goToSlide({{ $dIdx }})"></span>
                @endforeach
            @else
                <span class="slider-dot active" onclick="goToSlide(0)"></span>
            @endif
        </div>
    </div>

    <!-- ================================= 2. CATEGORIES HORIZONTAL CAROUSEL ================================= -->
    <div class="cat-carousel-container position-relative mb-5">
        <h4 class="fw-bold mb-3 text-dark text-center">Categories</h4>

        <div class="cat-carousel-wrapper position-relative">
            <button type="button" class="cat-carousel-nav cat-nav-prev shadow-sm" onclick="slideCategories(-1)" aria-label="Previous Categories">
                <i class="fa fa-chevron-left"></i>
            </button>

            <div class="cat-carousel-track" id="catSliderTrack">
                @foreach($categories as $cat)
                    <div class="cat-slide-item">
                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="cat-item-link">
                            <div class="cat-box-card shadow-sm">
                                @if($cat->image)
                                    <img src="{{ asset($cat->image) }}" alt="{{ $cat->name }}" class="cat-box-img">
                                @else
                                    <i class="fa {{ $cat->icon ?: 'fa-shopping-basket' }} cat-box-icon"></i>
                                @endif
                            </div>
                            <div class="cat-box-name" title="{{ $cat->name }}">{{ $cat->name }}</div>
                        </a>
                    </div>
                @endforeach
            </div>

            <button type="button" class="cat-carousel-nav cat-nav-next shadow-sm" onclick="slideCategories(1)" aria-label="Next Categories">
                <i class="fa fa-chevron-right"></i>
            </button>
        </div>
    </div>

    <!-- ================================= 3. EXCLUSIVE DEALS (ASMI Image 1) ================================= -->
    <div class="mb-5" id="exclusive-deals">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark mb-1" style="font-size: 22px;">Exclusive Deals</h3>
            <span class="exclusive-offer-badge">EXCLUSIVE OFFER</span>
        </div>

        <div class="position-relative">
            <div class="product-carousel-track" id="trackExclusiveDeals">
                @foreach($exclusiveDeals as $product)
                    <div class="product-carousel-item">
                        <div class="shop-product-card shadow-sm">
                            <div>
                                <a href="{{ route('products.show', $product->slug) }}" class="prod-img-wrap">
                                    @if($product->stock_quantity <= 0)
                                        <span class="badge-out-of-stock">Out of Stock !</span>
                                    @endif
                                    <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=400' }}" alt="{{ $product->name }}" loading="lazy">
                                </a>

                                <div class="prod-delivery-sub">{{ $product->delivery_time }}</div>

                                <a href="{{ route('products.show', $product->slug) }}" class="prod-name" title="{{ $product->name }}">
                                    {{ $product->name }}
                                </a>
                            </div>
                            <div>
                                <div class="prod-price-wrap">
                                    <span>{{ $product->formatted_price }}</span>
                                    @if($product->unit)
                                        <small class="text-muted fw-normal" style="font-size: 11px;">/ Per {{ $product->unit }}</small>
                                    @endif
                                </div>
                                @if($product->stock_quantity <= 0)
                                    <button type="button" class="btn-out-stock" disabled>Out of Stock</button>
                                @else
                                    <button type="button" class="btn-add-bag" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-image="{{ $product->image }}" data-sku="{{ $product->sku }}" data-delivery="{{ $product->delivery_time }}" onclick="addCardToBag(this)">
                                        <i class="fa fa-plus"></i>
                                        <span>Add to Bag</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="section-dots" id="dotsExclusiveDeals">
                <span class="sec-dot active" onclick="slideProductTrack('trackExclusiveDeals', 0, this)"></span>
                <span class="sec-dot" onclick="slideProductTrack('trackExclusiveDeals', 1, this)"></span>
            </div>
        </div>
    </div>

    <!-- ================================= 4. TODAY'S FEATURED PRODUCTS (ASMI Image 2) ================================= -->
    <div class="mb-5" id="todays-featured">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark mb-1 position-relative d-inline-block pb-2" style="font-size: 22px;">
                Today's Featured Products
                <span class="title-underline-bar"></span>
            </h3>
        </div>

        <div class="position-relative">
            <div class="product-carousel-track" id="trackFeaturedProducts">
                @foreach($featuredProducts as $product)
                    <div class="product-carousel-item">
                        <div class="shop-product-card shadow-sm">
                            <div>
                                <a href="{{ route('products.show', $product->slug) }}" class="prod-img-wrap">
                                    @if($product->stock_quantity <= 0)
                                        <span class="badge-out-of-stock">Out of Stock !</span>
                                    @endif
                                    <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=400' }}" alt="{{ $product->name }}" loading="lazy">
                                </a>

                                <div class="prod-delivery-sub">{{ $product->delivery_time }}</div>

                                <a href="{{ route('products.show', $product->slug) }}" class="prod-name" title="{{ $product->name }}">
                                    {{ $product->name }}
                                </a>
                            </div>
                            <div>
                                <div class="prod-price-wrap">
                                    <span>{{ $product->formatted_price }}</span>
                                    @if($product->unit)
                                        <small class="text-muted fw-normal" style="font-size: 11px;">/ Per {{ $product->unit }}</small>
                                    @endif
                                </div>
                                @if($product->stock_quantity <= 0)
                                    <button type="button" class="btn-out-stock" disabled>Out of Stock</button>
                                @else
                                    <button type="button" class="btn-add-bag" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-image="{{ $product->image }}" data-sku="{{ $product->sku }}" data-delivery="{{ $product->delivery_time }}" onclick="addCardToBag(this)">
                                        <i class="fa fa-plus"></i>
                                        <span>Add to Bag</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="section-dots" id="dotsFeaturedProducts">
                <span class="sec-dot active" onclick="slideProductTrack('trackFeaturedProducts', 0, this)"></span>
                <span class="sec-dot" onclick="slideProductTrack('trackFeaturedProducts', 1, this)"></span>
            </div>
        </div>
    </div>

    <!-- ================================= 5. EXPLORE OUR PRODUCTS WITH TABS (ASMI Image 3) ================================= -->
    <div class="mb-5" id="explore-our-products">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark mb-3" style="font-size: 22px;">Explore Our Products</h3>
            <div class="explore-tab-pills shadow-sm">
                <button type="button" class="explore-pill active" onclick="switchExploreTab('new-arrival', this)">NEW ARRIVAL</button>
                <button type="button" class="explore-pill" onclick="switchExploreTab('trending', this)">TRENDING</button>
                <button type="button" class="explore-pill" onclick="switchExploreTab('popular', this)">POPULAR</button>
            </div>
        </div>

        <!-- Tab 1: New Arrival -->
        <div class="explore-tab-content" id="tabContent_new-arrival">
            <div class="row row-cols-2 row-cols-md-3 row-cols-xl-6 g-2 g-md-3">
                @foreach($newArrivalProducts as $product)
                    <div class="col">
                        <div class="shop-product-card shadow-sm">
                            <div>
                                <a href="{{ route('products.show', $product->slug) }}" class="prod-img-wrap">
                                    @if($product->stock_quantity <= 0)
                                        <span class="badge-out-of-stock">Out of Stock !</span>
                                    @endif
                                    <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=400' }}" alt="{{ $product->name }}" loading="lazy">
                                </a>

                                <div class="prod-delivery-sub">{{ $product->delivery_time }}</div>

                                <a href="{{ route('products.show', $product->slug) }}" class="prod-name" title="{{ $product->name }}">
                                    {{ $product->name }}
                                </a>
                            </div>
                            <div>
                                <div class="prod-price-wrap">
                                    <span>{{ $product->formatted_price }}</span>
                                    @if($product->unit)
                                        <small class="text-muted fw-normal" style="font-size: 11px;">/ Per {{ $product->unit }}</small>
                                    @endif
                                </div>
                                @if($product->stock_quantity <= 0)
                                    <button type="button" class="btn-out-stock" disabled>Out of Stock</button>
                                @else
                                    <button type="button" class="btn-add-bag" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-image="{{ $product->image }}" data-sku="{{ $product->sku }}" data-delivery="{{ $product->delivery_time }}" onclick="addCardToBag(this)">
                                        <i class="fa fa-plus"></i>
                                        <span>Add to Bag</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Tab 2: Trending -->
        <div class="explore-tab-content d-none" id="tabContent_trending">
            <div class="row row-cols-2 row-cols-md-3 row-cols-xl-6 g-2 g-md-3">
                @foreach($trendingProducts as $product)
                    <div class="col">
                        <div class="shop-product-card shadow-sm">
                            <div>
                                <a href="{{ route('products.show', $product->slug) }}" class="prod-img-wrap">
                                    @if($product->stock_quantity <= 0)
                                        <span class="badge-out-of-stock">Out of Stock !</span>
                                    @endif
                                    <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=400' }}" alt="{{ $product->name }}" loading="lazy">
                                </a>

                                <div class="prod-delivery-sub">{{ $product->delivery_time }}</div>

                                <a href="{{ route('products.show', $product->slug) }}" class="prod-name" title="{{ $product->name }}">
                                    {{ $product->name }}
                                </a>
                            </div>
                            <div>
                                <div class="prod-price-wrap">
                                    <span>{{ $product->formatted_price }}</span>
                                    @if($product->unit)
                                        <small class="text-muted fw-normal" style="font-size: 11px;">/ Per {{ $product->unit }}</small>
                                    @endif
                                </div>
                                @if($product->stock_quantity <= 0)
                                    <button type="button" class="btn-out-stock" disabled>Out of Stock</button>
                                @else
                                    <button type="button" class="btn-add-bag" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-image="{{ $product->image }}" data-sku="{{ $product->sku }}" data-delivery="{{ $product->delivery_time }}" onclick="addCardToBag(this)">
                                        <i class="fa fa-plus"></i>
                                        <span>Add to Bag</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Tab 3: Popular -->
        <div class="explore-tab-content d-none" id="tabContent_popular">
            <div class="row row-cols-2 row-cols-md-3 row-cols-xl-6 g-2 g-md-3">
                @foreach($popularProducts as $product)
                    <div class="col">
                        <div class="shop-product-card shadow-sm">
                            <div>
                                <a href="{{ route('products.show', $product->slug) }}" class="prod-img-wrap">
                                    @if($product->stock_quantity <= 0)
                                        <span class="badge-out-of-stock">Out of Stock !</span>
                                    @endif
                                    <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=400' }}" alt="{{ $product->name }}" loading="lazy">
                                </a>

                                <div class="prod-delivery-sub">{{ $product->delivery_time }}</div>

                                <a href="{{ route('products.show', $product->slug) }}" class="prod-name" title="{{ $product->name }}">
                                    {{ $product->name }}
                                </a>
                            </div>
                            <div>
                                <div class="prod-price-wrap">
                                    <span>{{ $product->formatted_price }}</span>
                                    @if($product->unit)
                                        <small class="text-muted fw-normal" style="font-size: 11px;">/ Per {{ $product->unit }}</small>
                                    @endif
                                </div>
                                @if($product->stock_quantity <= 0)
                                    <button type="button" class="btn-out-stock" disabled>Out of Stock</button>
                                @else
                                    <button type="button" class="btn-add-bag" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-image="{{ $product->image }}" data-sku="{{ $product->sku }}" data-delivery="{{ $product->delivery_time }}" onclick="addCardToBag(this)">
                                        <i class="fa fa-plus"></i>
                                        <span>Add to Bag</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- ================================= 6. CATEGORY-WISE PRODUCT ROWS (ASMI Images 4 & 5) ================================= -->
    @foreach($categorySections as $catSection)
        @if($catSection->products->isNotEmpty())
            <div class="mb-5 category-product-section" id="cat-section-{{ $catSection->slug }}">
                <!-- Category Section Header -->
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                    <h3 class="fw-bold text-dark mb-0 position-relative d-inline-block pb-1" style="font-size: 20px;">
                        {{ $catSection->name }}
                        <span class="title-underline-bar-left"></span>
                    </h3>
                    <a href="{{ route('products.index', ['category' => $catSection->slug]) }}" class="btn-asmi-view-all">
                        <span>View All</span>
                        <i class="fa fa-arrow-right"></i>
                    </a>
                </div>

                <!-- 6 products per row sliding carousel -->
                <div class="position-relative">
                    <div class="product-carousel-track" id="trackCat_{{ $catSection->id }}">
                        @foreach($catSection->products as $product)
                            <div class="product-carousel-item">
                                <div class="shop-product-card shadow-sm">
                                    <div>
                                        <a href="{{ route('products.show', $product->slug) }}" class="prod-img-wrap">
                                            @if($product->stock_quantity <= 0)
                                                <span class="badge-out-of-stock">Out of Stock !</span>
                                            @endif
                                            <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=400' }}" alt="{{ $product->name }}" loading="lazy">
                                        </a>

                                        <div class="prod-delivery-sub">{{ $product->delivery_time }}</div>

                                        <a href="{{ route('products.show', $product->slug) }}" class="prod-name" title="{{ $product->name }}">
                                            {{ $product->name }}
                                        </a>
                                    </div>
                                    <div>
                                        <div class="prod-price-wrap">
                                            <span>{{ $product->formatted_price }}</span>
                                            @if($product->unit)
                                                <small class="text-muted fw-normal" style="font-size: 11px;">/ Per {{ $product->unit }}</small>
                                            @endif
                                        </div>
                                        @if($product->stock_quantity <= 0)
                                            <button type="button" class="btn-out-stock" disabled>Out of Stock</button>
                                        @else
                                            <button type="button" class="btn-add-bag" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-image="{{ $product->image }}" data-sku="{{ $product->sku }}" data-delivery="{{ $product->delivery_time }}" onclick="addCardToBag(this)">
                                                <i class="fa fa-plus"></i>
                                                <span>Add to Bag</span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($catSection->products->count() > 6)
                        <div class="section-dots" id="dotsCat_{{ $catSection->id }}">
                            <span class="sec-dot active" onclick="slideProductTrack('trackCat_{{ $catSection->id }}', 0, this)"></span>
                            <span class="sec-dot" onclick="slideProductTrack('trackCat_{{ $catSection->id }}', 1, this)"></span>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endforeach

@else
    <!-- ================================= FILTERED / COLLECTION LISTING VIEW ================================= -->
    <div class="mb-4">
        <!-- Breadcrumb & Top Bar -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 pb-2 border-bottom">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 small">
                        <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-teal text-decoration-none">Home</a></li>
                        @if($activeCategory)
                            <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $activeCategory->slug]) }}" class="text-teal text-decoration-none">{{ $activeCategory->name }}</a></li>
                        @endif
                        @if($activeSubcategory)
                            <li class="breadcrumb-item active" aria-current="page">{{ $activeSubcategory->name }}</li>
                        @elseif($badgeFilter)
                            <li class="breadcrumb-item active" aria-current="page">{{ ucwords(str_replace('-', ' ', $badgeFilter)) }}</li>
                        @endif
                    </ol>
                </nav>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fa fa-times me-1"></i> ফিল্টার ক্লিয়ার করুন
            </a>
        </div>

        <!-- Collection / Page Dedicated Banner (e.g. Monthly Bazar, Daily Offers) -->
        @if($pageBanner)
            <div class="collection-page-banner mb-4 shadow-sm">
                <a href="{{ $pageBanner->link ?: '#' }}" class="d-block w-100">
                    <img src="{{ asset($pageBanner->image) }}" alt="{{ $pageBanner->title }}" class="collection-banner-img">
                </a>
            </div>
        @endif

        <!-- Collection Heading -->
        <div class="collection-page-header text-center mb-4">
            <h2 class="fw-bold text-dark mb-1">
                @if($searchQuery)
                    অনুসন্ধানের ফলাফল: "{{ $searchQuery }}"
                @elseif($activeSubcategory)
                    {{ $activeSubcategory->name }} ({{ $activeCategory->name ?? '' }})
                @elseif($activeCategory)
                    {{ $activeCategory->name }}
                @elseif($badgeFilter)
                    {{ ucwords(str_replace('-', ' ', $badgeFilter)) }}
                @else
                    পণ্য তালিকা
                @endif
            </h2>
            <p class="text-muted small">মোট {{ $products->total() }} টি পণ্য পাওয়া গেছে</p>
        </div>

        @if($products->count() > 0)
            <div class="row row-cols-2 row-cols-md-3 row-cols-xl-5 g-2 g-md-3">
                @foreach($products as $product)
                    <div class="col">
                        <div class="shop-product-card shadow-sm">
                            <div>
                                {{-- Show red discount badge ONLY on Daily Offers page OR if product is specifically a daily offer --}}
                                @if($badgeFilter === 'daily-offers' || ($product->is_daily_offer && $product->offer_end_date))
                                    @if($product->old_price && $product->old_price > $product->price)
                                        <span class="prod-discount-badge">৳{{ round($product->old_price - $product->price) }}<br><small style="font-size: 8.5px; font-weight: 900;">OFF</small></span>
                                    @endif
                                @endif

                                <a href="{{ route('products.show', $product->slug) }}" class="prod-img-wrap">
                                    @if($product->stock_quantity <= 0)
                                        <span class="badge-out-of-stock">Out of Stock !</span>
                                    @endif
                                    <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=400' }}" alt="{{ $product->name }}" loading="lazy">
                                </a>

                                {{-- Show timer strip ONLY on Daily Offers page --}}
                                @if($badgeFilter === 'daily-offers' || ($product->is_daily_offer && $product->offer_end_date))
                                    @php
                                        $timerTarget = $product->offer_end_date ?: now()->addDays(16)->addHours(10)->addMinutes(12)->addSeconds(10);
                                    @endphp
                                    <div class="prod-timer-strip" data-end-time="{{ \Carbon\Carbon::parse($timerTarget)->toIso8601String() }}">
                                        <div class="timer-segment"><span class="t-val t-day">00</span><span class="t-lbl">DAY</span></div>
                                        <span class="t-sep">:</span>
                                        <div class="timer-segment"><span class="t-val t-hour">00</span><span class="t-lbl">HR</span></div>
                                        <span class="t-sep">:</span>
                                        <div class="timer-segment"><span class="t-val t-min">00</span><span class="t-lbl">MIN</span></div>
                                        <span class="t-sep">:</span>
                                        <div class="timer-segment"><span class="t-val t-sec">00</span><span class="t-lbl">SEC</span></div>
                                    </div>
                                @endif

                                <div class="prod-delivery-sub">{{ $product->delivery_time }}</div>

                                <a href="{{ route('products.show', $product->slug) }}" class="prod-name" title="{{ $product->name }}">
                                    {{ $product->name }}
                                </a>
                            </div>
                            <div>
                                <div class="prod-price-wrap">
                                    <span>{{ $product->formatted_price }}</span>
                                    @if($product->unit)
                                        <small class="text-muted fw-normal" style="font-size: 11px;">/ Per {{ $product->unit }}</small>
                                    @endif
                                </div>
                                @if($product->stock_quantity <= 0)
                                    <button type="button" class="btn-out-stock" disabled>Out of Stock</button>
                                @else
                                    <button type="button" class="btn-add-bag" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-image="{{ $product->image }}" data-sku="{{ $product->sku }}" data-delivery="{{ $product->delivery_time }}" onclick="addCardToBag(this)">
                                        <i class="fa fa-plus"></i>
                                        <span>Add to Bag</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-5 bg-white rounded-3 shadow-sm border p-4">
                <i class="fa fa-magnifying-glass fs-1 text-muted mb-3"></i>
                <h5 class="fw-bold text-dark">দুঃখিত, কোনো পণ্য পাওয়া যায়নি!</h5>
                <p class="text-muted">ভিন্ন কোনো নাম অথবা ক্যাটাগরি দিয়ে পুনরায় চেষ্টা করুন।</p>
                <a href="{{ route('products.index') }}" class="btn btn-sm text-white px-4 py-2" style="background: var(--shop-teal);">
                    সকল পণ্য দেখুন
                </a>
            </div>
        @endif
    </div>
@endif

@endsection

@section('extra_js')
<script>
    // Hero Slider Carousel
    let currentSlideIndex = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.slider-dot');

    function showSlide(index) {
        if (!slides.length) return;
        slides.forEach(s => s.classList.remove('active'));
        dots.forEach(d => d.classList.remove('active'));
        
        currentSlideIndex = (index + slides.length) % slides.length;
        slides[currentSlideIndex].classList.add('active');
        if (dots[currentSlideIndex]) dots[currentSlideIndex].classList.add('active');
    }

    function nextSlide() {
        showSlide(currentSlideIndex + 1);
    }

    function prevSlide() {
        showSlide(currentSlideIndex - 1);
    }

    function goToSlide(index) {
        showSlide(index);
    }

    if (slides.length > 1) {
        setInterval(nextSlide, 5000);
    }

    // Categories Horizontal Carousel Navigation (< and >)
    function slideCategories(direction) {
        const track = document.getElementById('catSliderTrack');
        if (!track) return;
        const scrollDistance = track.clientWidth * 0.75;
        track.scrollBy({
            left: direction * scrollDistance,
            behavior: 'smooth'
        });
    }

    // Generic Product Carousel Tracker (Exclusive Deals, Featured, Categories)
    function slideProductTrack(trackId, pageIndex, dotEl) {
        const track = document.getElementById(trackId);
        if (!track) return;
        const targetLeft = pageIndex * track.clientWidth;
        track.scrollTo({
            left: targetLeft,
            behavior: 'smooth'
        });

        if (dotEl && dotEl.parentElement) {
            dotEl.parentElement.querySelectorAll('.sec-dot').forEach(d => d.classList.remove('active'));
            dotEl.classList.add('active');
        }
    }

    // Auto-slide carousels every 6 seconds
    function initAutoCarousels() {
        const tracks = [
            { trackId: 'trackExclusiveDeals', dotsId: 'dotsExclusiveDeals' },
            { trackId: 'trackFeaturedProducts', dotsId: 'dotsFeaturedProducts' }
        ];

        // Also add category tracks
        document.querySelectorAll('.product-carousel-track').forEach(t => {
            if (t.id && !tracks.some(item => item.trackId === t.id)) {
                const dots = document.getElementById(t.id.replace('track', 'dots'));
                if (dots) {
                    tracks.push({ trackId: t.id, dotsId: dots.id });
                }
            }
        });

        tracks.forEach(cfg => {
            let page = 0;
            setInterval(() => {
                const track = document.getElementById(cfg.trackId);
                const dotsWrap = document.getElementById(cfg.dotsId);
                if (!track) return;

                page = (page === 0) ? 1 : 0;
                track.scrollTo({
                    left: page * track.clientWidth,
                    behavior: 'smooth'
                });

                if (dotsWrap) {
                    const dots = dotsWrap.querySelectorAll('.sec-dot');
                    if (dots.length > page) {
                        dots.forEach(d => d.classList.remove('active'));
                        dots[page].classList.add('active');
                    }
                }
            }, 5500 + Math.random() * 1000);
        });
    }

    // Explore Our Products Tab Switcher
    function switchExploreTab(tabName, pillBtn) {
        document.querySelectorAll('.explore-pill').forEach(b => b.classList.remove('active'));
        if (pillBtn) pillBtn.classList.add('active');

        document.querySelectorAll('.explore-tab-content').forEach(c => c.classList.add('d-none'));
        const target = document.getElementById('tabContent_' + tabName);
        if (target) {
            target.classList.remove('d-none');
        }
    }

    // Dynamic Live Countdown Timer for Daily Offers Page
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

    document.addEventListener('DOMContentLoaded', function() {
        initAutoCarousels();
        updateCountdowns();
        setInterval(updateCountdowns, 1000);
    });
</script>
@endsection
