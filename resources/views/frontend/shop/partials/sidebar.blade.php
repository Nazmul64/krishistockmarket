@php
    $currentBadge = request('badge');
    $currentCategory = request('category');
    $currentSubcategory = request('subcategory');
@endphp

<div class="shop-sidebar {{ !empty($isMobile) ? 'mobile-sidebar shadow-none border-0' : 'shadow-sm' }}" style="{{ !empty($isMobile) ? 'height: auto; max-height: none; position: static;' : '' }}">
    <!-- 1. Header Section (Brand + Quick Badges + Categories Header) -->
    <div class="sidebar-header-section flex-shrink-0">
        <!-- Top Shopping Zone BD banner -->
        <div class="sidebar-brand-box">
            <a href="{{ route('products.index') }}" class="text-decoration-none">
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <i class="fa fa-shopping-bag text-danger fs-5"></i>
                    <div class="text-start">
                        <strong class="text-dark d-block fw-bold" style="font-size: 13px; letter-spacing: -0.3px;">SHOPPING ZONE BD</strong>
                        <div class="brand-tagline">Fashionable Wear in Reasonable Price</div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Quick Badges (Winter Essentials, Monthly Bazar, Combo Offers, Daily Offers, Popular, Shop by Brands) -->
        <div class="sidebar-badges-list">
            <a href="{{ route('products.index', ['badge' => 'winter-essentials']) }}" class="sidebar-badge-link {{ $currentBadge == 'winter-essentials' ? 'active' : '' }}">
                <span class="icon-box text-info"><i class="fa-solid fa-cart-flatbed-suitcase"></i></span>
                <span>Winter Essentials</span>
            </a>

            <a href="{{ route('products.index', ['badge' => 'monthly-bazar']) }}" class="sidebar-badge-link {{ $currentBadge == 'monthly-bazar' ? 'active' : '' }}">
                <span class="icon-box text-success"><i class="fa-solid fa-store"></i></span>
                <span>Monthly Bazar</span>
            </a>

            <a href="{{ route('products.index', ['badge' => 'combo-offers']) }}" class="sidebar-badge-link {{ $currentBadge == 'combo-offers' ? 'active' : '' }}">
                <span class="icon-box text-warning"><i class="fa-solid fa-gift"></i></span>
                <span>Combo Offers</span>
            </a>

            <a href="{{ route('products.index', ['badge' => 'daily-offers']) }}" class="sidebar-badge-link {{ $currentBadge == 'daily-offers' ? 'active' : '' }}">
                <span class="icon-box text-danger"><i class="fa-solid fa-tag"></i></span>
                <span>Daily Offers</span>
            </a>

            <a href="{{ route('products.index', ['badge' => 'popular']) }}" class="sidebar-badge-link {{ $currentBadge == 'popular' ? 'active' : '' }}">
                <span class="icon-box text-danger"><i class="fa-solid fa-heart"></i></span>
                <span>Popular</span>
            </a>

            <a href="{{ route('products.index', ['badge' => 'brands']) }}" class="sidebar-badge-link {{ $currentBadge == 'brands' ? 'active' : '' }}">
                <span class="icon-box text-primary"><i class="fa-solid fa-bag-shopping"></i></span>
                <span>Shop by Brands</span>
            </a>
        </div>

        <!-- CATEGORIES Header -->
        <div class="sidebar-cat-title">
            <i class="fa fa-layer-group me-1"></i> CATEGORIES
        </div>
    </div>

    <!-- 2. Middle Scrollable Category List (Pinned in place; internal scroll if categories exceed height) -->
    <div class="sidebar-scrollable-cats">
        <ul class="sidebar-cat-list">
            @foreach($categories as $category)
                @php
                    $isCatActive = ($currentCategory == $category->slug);
                @endphp
                <li class="sidebar-cat-item {{ $isCatActive ? 'active' : '' }}">
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}">
                        <span class="cat-label">
                            @if($category->image)
                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" style="width: 20px; height: 20px; object-fit: contain; margin-right: 8px;">
                            @else
                                <i class="fa {{ $category->icon ?: 'fa-shopping-basket' }} cat-icon"></i>
                            @endif
                            <span>{{ $category->name }}</span>
                        </span>
                        @if($category->subcategories->count() > 0)
                            <i class="fa fa-angle-right arrow-icon"></i>
                        @endif
                    </a>

                    @if($category->subcategories->count() > 0)
                        <!-- Flyout subcategory dropdown (shows on mouse hover) -->
                        <div class="cat-flyout-menu shadow-lg">
                            <div class="flyout-header d-flex align-items-center justify-content-between">
                                <span>{{ $category->name }}</span>
                                <span class="badge bg-light text-muted small">{{ $category->subcategories->count() }} Subcategories</span>
                            </div>
                            @foreach($category->subcategories as $sub)
                                <a href="{{ route('products.index', ['category' => $category->slug, 'subcategory' => $sub->slug]) }}" class="{{ $currentSubcategory == $sub->slug ? 'text-primary fw-bold bg-light' : '' }}">
                                    <i class="fa fa-chevron-right text-muted" style="font-size: 9px;"></i>
                                    <span>{{ $sub->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    <!-- 3. Bottom Delivery & Hotline Strip (Anchors card to viewport bottom) -->
    <div class="sidebar-footer-strip p-2 p-xl-3 border-top bg-light text-center flex-shrink-0" style="border-radius: 0 0 12px 12px;">
        <div class="d-flex align-items-center justify-content-center gap-2 text-success fw-bold small mb-1">
            <i class="fa-solid fa-truck-fast"></i>
            <span>ফ্রি হোম ডেলিভারি</span>
        </div>
        <p class="text-muted small mb-2 d-none d-xl-block" style="font-size: 11px; line-height: 1.3;">
            ঢাকা সিটিতে ১-২ ঘণ্টায় দ্রুততম হোম ডেলিভারি
        </p>
        <a href="tel:{{ preg_replace('/[^0-9]/', '', $settings['shop_hotline'] ?? '09617900200') }}" class="btn btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-1 gap-xl-2" style="background: #e6f6f7; color: var(--shop-teal); border: 1px solid var(--shop-teal); font-size: 11.5px; border-radius: 6px; padding: 5px;">
            <i class="fa fa-phone"></i>
            <span>হটলাইন: {{ $settings['shop_hotline'] ?? '09617900200' }}</span>
        </a>
        <a href="{{ route('shop.track_order') }}" class="btn btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-1 mt-2" style="background: #fff7ed; color: #ea580c; border: 1px solid #fdba74; font-size: 11px; border-radius: 6px; padding: 4px;">
            <i class="fa fa-truck-fast"></i>
            <span>অর্ডার ট্র্যাক করুন (Track)</span>
        </a>
    </div>
</div>

