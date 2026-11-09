@php
    $siteTheme = request()->cookie('ekrishi_theme', 'light');
    $siteLang = request()->cookie('ekrishi_lang', 'en');
    $isBn = ($siteLang === 'bn');
@endphp
<!DOCTYPE html>
<html lang="en" data-theme="{{ $siteTheme }}">

<head>
    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ setting('title') }}</title>

    <!-- Standard Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('upload/images/backend/logo') }}/{{ setting('favicon') }}" />

    <!-- For iPhone 4 Retina display: -->
    <link rel="apple-touch-icon-precomposed"
        href="{{ asset('frontend/assets') }}/images/apple-touch-icon-114x114-precomposed.png">

    <!-- For iPad: -->
    <link rel="apple-touch-icon-precomposed"
        href="{{ asset('frontend/assets') }}/images/apple-touch-icon-72x72-precomposed.html">

    <!-- For iPhone: -->
    <link rel="apple-touch-icon-precomposed"
        href="{{ asset('frontend/assets') }}/images/apple-touch-icon-57x57-precomposed.png">

    <!-- Dynamic Google Font Family loaded from Admin Settings -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ getGoogleFontUrl() }}" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets') }}/revolution/css/settings.css">

    <!-- Library -->
    <link href="{{ asset('frontend/assets') }}/css/lib.css" rel="stylesheet">
    <link href="{{ asset('frontend/assets') }}/css/flags.css" rel="stylesheet">

    <!-- Custom - Common CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets') }}/css/rtl.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets') }}/css/style.css">
    <link id="color" href="{{ asset('frontend/assets') }}/css/color-schemes/default.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/react-frontend.css') }}?v={{ file_exists(public_path('css/react-frontend.css')) ? filemtime(public_path('css/react-frontend.css')) : time() }}">
    <style>
        @php
            $activeFont = getActiveFontConfig();
            $fontFamilyCss = "'" . $activeFont['name'] . "', 'Hind Siliguri', " . $activeFont['fallback'];
        @endphp
        :root {
            --site-font-family: {!! $fontFamilyCss !!};
        }
        body, html, h1, h2, h3, h4, h5, h6, p, a, span, button, input, select, textarea {
            font-family: var(--site-font-family) !important;
        }
        .rev_slider > ul li.slider-shape .slotholder::before {
            display: none !important;
            background: transparent !important;
            opacity: 0 !important;
        }
        .top-header-bar {
            background: #0f172a;
            color: #cbd5e1;
            padding: 8px 0;
            font-size: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .top-header-bar a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s;
        }
        .top-header-bar a:hover {
            color: #38bdf8;
        }
        @media (min-width: 1200px) {
            .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
                max-width: 1480px !important;
            }
        }
        .ownavigation a.navbar-brand {
            display: inline-flex !important;
            align-items: center !important;
            height: auto !important;
            padding: 0 !important;
            margin: 0 15px 0 0 !important;
            position: relative !important;
            z-index: 10 !important;
        }
        .ownavigation a.navbar-brand img {
            height: 75px !important;
            max-height: 75px !important;
            width: auto !important;
            min-width: 75px !important;
            display: block !important;
            position: static !important;
            background: transparent !important;
            padding: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }
        .ownavigation .navbar-nav .nav-link {
            font-family: 'Hind Siliguri', 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 14px !important;
            white-space: nowrap !important;
            color: #334155 !important;
            padding: 10px 10px !important;
            transition: all 0.2s ease;
        }
        .ownavigation .navbar-nav .nav-link:hover,
        .ownavigation .navbar-nav li.active .nav-link {
            color: #1b88ce !important;
        }
        .ownavigation .navbar-nav .btn-nav-register {
            background: linear-gradient(135deg, #1b88ce, #1469a0) !important;
            color: #ffffff !important;
            border-radius: 25px;
            padding: 8px 20px !important;
            box-shadow: 0 4px 12px rgba(27,136,206,0.3);
            margin-left: 6px;
        }
        .ownavigation .navbar-nav .btn-nav-register,
        .ownavigation .navbar-nav .btn-nav-register:hover,
        .ownavigation .navbar-nav .btn-nav-register:focus,
        .ownavigation .navbar-nav .btn-nav-register i {
            color: #ffffff !important;
        }
        .ownavigation .navbar-nav .btn-nav-register:hover {
            box-shadow: 0 6px 16px rgba(27,136,206,0.45);
            transform: translateY(-1px);
        }

        /* Hide slider arrows & overlay caption boxes as requested */
        .tparrows, .tp-bullets, .tp-caption {
            display: none !important;
        }

        /* Mobile responsive fix for gallery box - 2 items per row */
        @media (max-width: 575.98px) {
            .gallery-list {
                display: flex !important;
                flex-wrap: wrap !important;
                margin-left: -6px !important;
                margin-right: -6px !important;
            }
            .gallery-list .gallery-box,
            .portfolio-section .gallery-box {
                flex: 0 0 50% !important;
                max-width: 50% !important;
                width: 50% !important;
                padding-left: 6px !important;
                padding-right: 6px !important;
                margin-bottom: 12px !important;
                float: left !important;
            }
            .gallery-box .gallery-content {
                position: relative !important;
                overflow: hidden !important;
                border-radius: 8px !important;
                width: 100% !important;
            }
            .gallery-box .gallery-content img,
            .gallery-box .gallery-content i img {
                width: 100% !important;
                height: 160px !important;
                object-fit: cover !important;
                border-radius: 8px !important;
                display: block !important;
            }
        }

        @media (min-width: 576px) and (max-width: 767.98px) {
            .gallery-list {
                display: flex !important;
                flex-wrap: wrap !important;
                margin-left: -8px !important;
                margin-right: -8px !important;
            }
            .gallery-list .gallery-box,
            .portfolio-section .gallery-box {
                flex: 0 0 50% !important;
                max-width: 50% !important;
                width: 50% !important;
                padding-left: 8px !important;
                padding-right: 8px !important;
                margin-bottom: 16px !important;
                float: left !important;
            }
            .gallery-box .gallery-content img,
            .gallery-box .gallery-content i img {
                width: 100% !important;
                height: 200px !important;
                object-fit: cover !important;
                border-radius: 8px !important;
                display: block !important;
            }
        }
        /* Premium ekrishisheba Animated Preloader */
        .ekrishi-preloader-wrap {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            background: radial-gradient(circle at center, #ffffff 0%, #fffbf2 60%, #fef3c7 100%) !important;
            z-index: 9999999 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            opacity: 1;
            visibility: visible;
            transition: opacity 0.45s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.45s ease !important;
            pointer-events: all;
        }
        .ekrishi-preloader-wrap.loaded {
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
        }
        .ekrishi-preloader-inner {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            padding: 24px !important;
            max-width: 380px !important;
        }
        .preloader-logo-ring-outer {
            position: relative !important;
            min-width: 150px !important;
            max-width: 240px !important;
            height: 110px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin-bottom: 22px !important;
        }
        .preloader-logo-ring-pulse {
            position: absolute !important;
            inset: -8px !important;
            border-radius: 28px !important;
            background: radial-gradient(circle, rgba(234, 88, 12, 0.25) 0%, rgba(245, 158, 11, 0.08) 60%, transparent 100%) !important;
            animation: ekrishiPulse 2s ease-in-out infinite !important;
        }
        .preloader-logo-ring-spinner {
            position: absolute !important;
            inset: -4px !important;
            border-radius: 26px !important;
            border: 3px solid rgba(245, 158, 11, 0.18) !important;
            border-top-color: #ea580c !important;
            border-right-color: #f59e0b !important;
            animation: ekrishiSpin 1.4s cubic-bezier(0.55, 0.15, 0.45, 0.85) infinite !important;
        }
        .preloader-logo-circle {
            min-width: 140px !important;
            max-width: 220px !important;
            height: 84px !important;
            border-radius: 22px !important;
            background: #ffffff !important;
            box-shadow: 0 12px 30px rgba(234, 88, 12, 0.22), 0 2px 8px rgba(0, 0, 0, 0.06) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            z-index: 2 !important;
            padding: 10px 20px !important;
            animation: ekrishiFloat 2.8s ease-in-out infinite !important;
        }
        .preloader-logo-img {
            max-width: 180px !important;
            max-height: 64px !important;
            width: auto !important;
            height: auto !important;
            object-fit: contain !important;
            background: transparent !important;
        }
        .preloader-brand-title {
            font-family: 'Poppins', 'Hind Siliguri', sans-serif !important;
            font-size: 26px !important;
            font-weight: 800 !important;
            letter-spacing: 0.02em !important;
            margin: 0 0 4px 0 !important;
            background: linear-gradient(135deg, #ea580c 0%, #d97706 60%, #b45309 100%) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
        }
        .preloader-tagline {
            font-family: 'Hind Siliguri', sans-serif !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            color: #64748b !important;
            margin: 0 0 16px 0 !important;
        }
        .preloader-progress-bar {
            width: 180px !important;
            height: 5px !important;
            background: rgba(234, 88, 12, 0.14) !important;
            border-radius: 10px !important;
            overflow: hidden !important;
            position: relative !important;
            margin-bottom: 10px !important;
        }
        .preloader-progress-fill {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            bottom: 0 !important;
            width: 45% !important;
            background: linear-gradient(90deg, #ea580c, #f59e0b, #ea580c) !important;
            background-size: 200% 100% !important;
            border-radius: 10px !important;
            animation: ekrishiBar 1.4s ease-in-out infinite !important;
        }
        .preloader-loading-text {
            font-family: 'Hind Siliguri', sans-serif !important;
            font-size: 12.5px !important;
            color: #94a3b8 !important;
        }

        @keyframes ekrishiSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes ekrishiPulse {
            0%, 100% { transform: scale(0.95); opacity: 0.5; }
            50% { transform: scale(1.15); opacity: 1; }
        }
        @keyframes ekrishiFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        @keyframes ekrishiBar {
            0% { left: -45%; }
            50% { left: 40%; }
            100% { left: 100%; }
        }

        /* ==========================================================================
           GLOBAL DARK MODE THEME SYSTEM
           ========================================================================== */
        html[data-theme="dark"], body.dark-theme {
            background-color: #0b1120 !important;
            color: #f1f5f9 !important;
        }
        body.dark-theme .header-sub-nav-bar,
        html[data-theme="dark"] .header-sub-nav-bar {
            background: #0f172a !important;
            border-color: #1e293b !important;
        }
        body.dark-theme .sub-nav-link-item,
        html[data-theme="dark"] .sub-nav-link-item {
            color: #cbd5e1 !important;
        }
        body.dark-theme .sub-nav-link-item:hover,
        body.dark-theme .sub-nav-link-item.active,
        html[data-theme="dark"] .sub-nav-link-item:hover,
        html[data-theme="dark"] .sub-nav-link-item.active {
            color: #f59e0b !important;
        }
        body.dark-theme .header-theme-toggle-btn,
        html[data-theme="dark"] .header-theme-toggle-btn {
            background: rgba(255, 255, 255, 0.25) !important;
            color: #fbbf24 !important;
            border-color: rgba(251, 191, 36, 0.6) !important;
        }
        body.dark-theme .mobile-nav-drawer,
        html[data-theme="dark"] .mobile-nav-drawer {
            background: #0f172a !important;
            color: #f1f5f9 !important;
        }
        body.dark-theme .mobile-nav-link,
        html[data-theme="dark"] .mobile-nav-link {
            color: #cbd5e1 !important;
            border-color: #1e293b !important;
        }
        body.dark-theme .site-main,
        html[data-theme="dark"] .site-main {
            background: #0b1120 !important;
        }
        body.dark-theme .bg-white,
        html[data-theme="dark"] .bg-white {
            background-color: #0f172a !important;
            color: #f1f5f9 !important;
        }
        body.dark-theme .bg-light,
        html[data-theme="dark"] .bg-light {
            background-color: #1e293b !important;
            color: #f1f5f9 !important;
        }
        body.dark-theme .text-dark,
        html[data-theme="dark"] .text-dark {
            color: #f8fafc !important;
        }
        body.dark-theme .border,
        html[data-theme="dark"] .border {
            border-color: #1e293b !important;
        }
        body.dark-theme .footer-main-container,
        html[data-theme="dark"] .footer-main-container {
            background: #050b14 !important;
            border-top: 1px solid #1e293b !important;
        }
    </style>
</head>

<body class="{{ $siteTheme === 'dark' ? 'dark-theme' : '' }}" data-offset="200" data-spy="scroll" data-target=".ownavigation">
    <!-- Premium ekrishisheba Animated Preloader with Main Logo -->
    <div id="site-preloader" class="ekrishi-preloader-wrap">
        <div class="ekrishi-preloader-inner">
            <!-- Orbit Rotating Rings around Main Logo -->
            <div class="preloader-logo-ring-outer">
                <div class="preloader-logo-ring-pulse"></div>
                <div class="preloader-logo-ring-spinner"></div>
                <div class="preloader-logo-circle">
                    <img src="{{ asset('upload/images/backend/logo') }}/{{ setting('logo') }}" alt="ekrishisheba" class="preloader-logo-img">
                </div>
            </div>

            <!-- Brand Name & Animated Shimmer Bar -->
            <h3 class="preloader-brand-title">ekrishisheba</h3>
            <p class="preloader-tagline">স্মার্ট কৃষি ও নিরাপদ ডিজিটাল সেবা</p>

            <div class="preloader-progress-bar">
                <div class="preloader-progress-fill"></div>
            </div>
            <small class="preloader-loading-text">লোড হচ্ছে, অনুগ্রহ করে অপেক্ষা করুন...</small>
        </div>
    </div>

    @if(!Route::is('/'))
    @php
        $siteLang = request()->cookie('ekrishi_lang', 'en');
        $isBn = ($siteLang === 'bn');
    @endphp
    <!-- Dynamic Marquee Notice Top Header Bar - Inside Container -->
    <div class="top-marquee-notice-bar">
        <div class="container">
            <div class="d-flex align-items-center w-100">
                <!-- Notice Badge -->
                <div class="marquee-badge-wrap d-flex align-items-center flex-shrink-0" style="margin-right: 14px !important;">
                    <span class="marquee-live-dot" style="margin-right: 6px !important;"></span>
                    <i class="fa fa-bullhorn marquee-icon" style="margin-right: 6px !important;"></i>
                    <span class="marquee-badge-title">{{ $isBn ? 'নোটিশ:' : 'Notice:' }}</span>
                </div>

                <!-- Scrolling Marquee Area -->
                <div class="marquee-ticker-wrap flex-grow-1 overflow-hidden" style="overflow: hidden; width: 100%; min-width: 0;">
                    <marquee behavior="scroll" direction="left" scrollamount="5" onmouseover="this.stop();" onmouseout="this.start();" class="marquee-ticker-content" width="100%" style="width: 100%; display: block;">
                        {{ setting('top_notice') ?: (setting('notice') ?: ($isBn ? 'ekrishisheba-তে স্বাগতম! আধুনিক ও নিরাপদ কৃষি প্ল্যাটফর্ম।' : 'Welcome to ekrishisheba! Modern, safe & reliable digital agro platform.')) }}
                    </marquee>
                </div>
            </div>
        </div>
    </div>

    <!-- Golden Sticky Navbar Header -->
    <header class="react-main-header golden-navbar sticky-top">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between header-inner-row">
                <!-- Brand Logo -->
                <a href="{{ route('/') }}" class="react-brand-logo flex-shrink-0">
                    <img src="{{ asset('upload/images/backend/logo') }}/{{ setting('logo') }}" alt="{{ setting('title') }}" />
                </a>

                <!-- Prominent Search Bar (Taking Full Space on Desktop) -->
                <div class="d-none d-lg-flex flex-grow-1 search-container-col" id="bladeSearchWrap" style="margin: 0 18px !important;">
                    <form class="header-search-bar-unified w-100" id="bladeSearchForm" onsubmit="event.preventDefault();">
                        <input
                            type="text"
                            id="bladeSearchInput"
                            class="unified-search-input"
                            placeholder="{{ $isBn ? 'জমি, ফ্ল্যাট, পণ্য, প্যাকেজ বা সেবা খুঁজুন...' : 'Search land, properties, packages, stocks...' }}"
                            autocomplete="off"
                        />
                        <button type="button" id="bladeSearchClear" class="search-clear-inline-btn" style="display: none;">
                            <i class="fa fa-times-circle"></i>
                        </button>
                        <button type="submit" class="unified-search-submit-btn">
                            <i class="fa fa-search me-1"></i>
                            <span>Search</span>
                        </button>
                    </form>
                    <div id="bladeSearchResults" class="header-search-dropdown-menu" style="display: none;"></div>
                </div>

                <!-- Right Action Icons: Language Pill + Dark Mode Moon/Sun + Auth Buttons (Generous Spacing) -->
                <div class="d-none d-lg-flex align-items-center header-right-actions flex-shrink-0">
                    <!-- Language Switcher Pill [ EN | বাংলা ] -->
                    <div class="header-lang-switcher-pill" style="margin-right: 16px !important;">
                        <button
                            type="button"
                            class="lang-btn {{ !$isBn ? 'active' : '' }}"
                            onclick="switchSiteLang('en')"
                            title="English"
                        >
                            EN
                        </button>
                        <span class="lang-divider">|</span>
                        <button
                            type="button"
                            class="lang-btn {{ $isBn ? 'active' : '' }}"
                            onclick="switchSiteLang('bn')"
                            title="বাংলা"
                        >
                            বাংলা
                        </button>
                    </div>

                    <!-- Dark / Black Mode Toggle Button -->
                    <button
                        type="button"
                        class="header-theme-toggle-btn {{ $siteTheme === 'dark' ? 'is-dark' : '' }}"
                        onclick="switchSiteTheme('{{ $siteTheme === 'dark' ? 'light' : 'dark' }}')"
                        title="{{ $siteTheme === 'dark' ? 'Switch to Light Mode' : 'Switch to Dark Mode / ব্ল্যাক মোড' }}"
                        aria-label="Toggle Theme"
                        style="margin-right: 16px !important;"
                    >
                        @if($siteTheme === 'dark')
                            <i class="fa fa-sun-o theme-icon-sun"></i>
                        @else
                            <i class="fa fa-moon-o theme-icon-moon"></i>
                        @endif
                    </button>

                    <!-- Auth / Dashboard Buttons -->
                    <div class="header-auth-buttons">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-golden-action">
                                <i class="fa fa-tachometer me-2"></i> {{ $isBn ? 'ড্যাশবোর্ড' : 'Dashboard' }}
                            </a>
                        @else
                            <div class="d-flex align-items-center">
                                <a href="{{ route('login') }}" class="btn-golden-action-outline" style="margin-right: 14px !important;">
                                    <i class="fa fa-sign-in me-1"></i> {{ $isBn ? 'লগইন' : 'Login' }}
                                </a>
                                <a href="{{ route('register.referlink') }}" class="btn-golden-action">
                                    <i class="fa fa-user-plus me-1"></i> {{ $isBn ? 'রেজিস্ট্রেশন' : 'Register' }}
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- Mobile Header Controls - Clean Hamburger Toggle Only -->
                <div class="mobile-header-controls d-flex d-lg-none align-items-center">
                    <button
                        type="button"
                        class="mobile-nav-toggle-btn-golden"
                        onclick="document.getElementById('bladeMobileDrawer').classList.toggle('open'); document.getElementById('bladeDrawerOverlay').classList.toggle('show');"
                        aria-label="Toggle Navigation Menu"
                    >
                        <i class="fa fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sub-Navigation Strip (Desktop Menu below Main Header) -->
        <div class="header-sub-nav-bar d-none d-lg-block">
            <div class="container">
                <nav class="d-flex align-items-center sub-nav-links-row">
                    <a href="{{ route('/') }}" class="sub-nav-link-item {{ Route::is('/') ? 'active' : '' }}">{{ $isBn ? 'হোম' : 'Home' }}</a>
                    <a href="{{ route('about') }}" class="sub-nav-link-item {{ Route::is('about') ? 'active' : '' }}">{{ $isBn ? 'আমাদের সম্পর্কে' : 'About Us' }}</a>
                    <a href="{{ route('stock.index') }}" class="sub-nav-link-item {{ Route::is('stock.*') ? 'active' : '' }}">{{ $isBn ? 'স্টক মার্কেট' : 'Stock Market' }}</a>
                    <a href="{{ route('properties.index') }}" class="sub-nav-link-item {{ Route::is('properties.*') ? 'active' : '' }}" style="background: rgba(22, 163, 74, 0.1); border-radius: 20px; padding: 4px 12px; border: 1px solid rgba(22, 163, 74, 0.3);">
                        <i class="fa fa-map-marker text-success me-1"></i>
                        <span class="fw-bold">{{ $isBn ? 'জমি ও প্রপার্টি' : 'Land & Properties' }}</span>
                        <span class="badge bg-danger ms-1 small" style="font-size: 10px; vertical-align: middle;">New</span>
                    </a>
                    <a href="{{ route('/') }}#packages" class="sub-nav-link-item">{{ $isBn ? 'প্যাকেজসমূহ' : 'Our Packages' }}</a>
                    <a href="{{ route('/') }}#card-benefits" class="sub-nav-link-item">{{ $isBn ? 'মেম্বারশিপ কার্ড' : 'Membership Cards' }}</a>
                    <a href="{{ route('gallery') }}" class="sub-nav-link-item {{ Route::is('gallery') ? 'active' : '' }}">{{ $isBn ? 'গ্যালারি' : 'Gallery' }}</a>
                    <a href="{{ route('contact') }}" class="sub-nav-link-item {{ Route::is('contact') ? 'active' : '' }}">{{ $isBn ? 'যোগাযোগ' : 'Contact' }}</a>
                    <a href="{{ route('terms') }}" class="sub-nav-link-item {{ Route::is('terms') ? 'active' : '' }}">{{ $isBn ? 'টার্মস এন্ড কন্ডিশন' : 'Terms & Conditions' }}</a>
                    <a href="{{ route('privacy') }}" class="sub-nav-link-item {{ Route::is('privacy') ? 'active' : '' }}">{{ $isBn ? 'প্রাইভেসি এন্ড পলিসি' : 'Privacy Policy' }}</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Mobile Slide-out Drawer for Blade Pages -->
    <div id="bladeDrawerOverlay" class="mobile-drawer-overlay" onclick="document.getElementById('bladeMobileDrawer').classList.remove('open'); this.classList.remove('show');"></div>
    <div id="bladeMobileDrawer" class="mobile-nav-drawer">
        <div class="mobile-drawer-header golden-drawer-head">
            <img src="{{ asset('upload/images/backend/logo') }}/{{ setting('logo') }}" alt="Logo" class="mobile-drawer-logo" />
            <button class="mobile-drawer-close" onclick="document.getElementById('bladeMobileDrawer').classList.remove('open'); document.getElementById('bladeDrawerOverlay').classList.remove('show');">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <div class="mobile-drawer-body">
            <!-- 1. Login & Registration / Dashboard in Mobile Drawer -->
            <div class="mobile-user-card mb-3 p-3 rounded-3" style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); border: 1px solid #fed7aa;">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-golden-action w-100 py-2 d-flex align-items-center justify-content-center gap-2">
                        <i class="fa fa-tachometer"></i> {{ $isBn ? 'ড্যাশবোর্ডে প্রবেশ করুন' : 'Go to Dashboard' }}
                    </a>
                @else
                    <div class="small text-muted mb-2 fw-bold text-center">
                        {{ $isBn ? 'একাউন্টে প্রবেশ করুন অথবা নিবন্ধন করুন' : 'Sign in to account or register' }}
                    </div>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('login') }}" class="mobile-drawer-btn-login flex-fill text-center py-2 text-decoration-none" style="margin-right: 10px;">
                            <i class="fa fa-sign-in me-1"></i> {{ $isBn ? 'লগইন' : 'Login' }}
                        </a>
                        <a href="{{ route('register.referlink') }}" class="mobile-drawer-btn-register flex-fill text-center py-2 text-decoration-none">
                            <i class="fa fa-user-plus me-1"></i> {{ $isBn ? 'রেজিস্টার' : 'Register' }}
                        </a>
                    </div>
                @endauth
            </div>

            <!-- 2. Dark Mode & Language Switcher inside Mobile Drawer -->
            <div class="d-flex align-items-center justify-content-between mb-3 p-2 bg-slate-100 rounded mobile-pref-card" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                <div class="d-flex align-items-center">
                    <span class="small text-muted fw-bold me-2" style="margin-right: 8px;">
                        {{ $isBn ? 'থিম:' : 'Theme:' }}
                    </span>
                    <button
                        type="button"
                        class="btn btn-sm {{ $siteTheme === 'dark' ? 'btn-dark text-warning' : 'btn-light text-dark' }} border fw-bold px-3 py-1"
                        onclick="switchSiteTheme('{{ $siteTheme === 'dark' ? 'light' : 'dark' }}')"
                        style="border-radius: 20px;"
                    >
                        @if($siteTheme === 'dark')
                            <span><i class="fa fa-sun-o text-warning me-1"></i> Light</span>
                        @else
                            <span><i class="fa fa-moon-o text-warning me-1"></i> Dark</span>
                        @endif
                    </button>
                </div>

                <div class="d-flex align-items-center">
                    <span class="small text-muted fw-bold me-2" style="margin-right: 8px;">
                        {{ $isBn ? 'ভাষা:' : 'Lang:' }}
                    </span>
                    <div class="header-lang-switcher-pill d-inline-flex">
                        <button
                            type="button"
                            class="lang-btn {{ !$isBn ? 'active' : '' }}"
                            onclick="switchSiteLang('en')"
                        >
                            EN
                        </button>
                        <span class="lang-divider">|</span>
                        <button
                            type="button"
                            class="lang-btn {{ $isBn ? 'active' : '' }}"
                            onclick="switchSiteLang('bn')"
                        >
                            বাংলা
                        </button>
                    </div>
                </div>
            </div>

            <!-- 3. Mobile Search Box with Integrated Search Button -->
            <div class="mobile-search-holder mb-3 position-relative" id="bladeMobileSearchWrap">
                <form class="header-search-bar-unified w-100" onsubmit="event.preventDefault();">
                    <input
                        type="text"
                        id="bladeMobileSearchInput"
                        class="unified-search-input"
                        placeholder="{{ $isBn ? 'জমি, ফ্ল্যাট, পণ্য, প্যাকেজ বা সেবা খুঁজুন...' : 'Search land, properties, packages, stocks...' }}"
                        autocomplete="off"
                    />
                    <button type="submit" class="unified-search-submit-btn px-3">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
                <div id="bladeMobileSearchResults" class="header-search-dropdown-menu" style="display: none;"></div>
            </div>

            <ul class="mobile-nav-list">
                <li class="mobile-nav-item">
                    <a href="{{ route('/') }}" class="mobile-nav-link {{ Route::is('/') ? 'active' : '' }}">
                        <span>{{ $isBn ? 'হোম' : 'Home' }}</span><i class="fa fa-angle-right"></i>
                    </a>
                </li>
                <li class="mobile-nav-item">
                    <a href="{{ route('about') }}" class="mobile-nav-link {{ Route::is('about') ? 'active' : '' }}">
                        <span>{{ $isBn ? 'আমাদের সম্পর্কে' : 'About Us' }}</span><i class="fa fa-angle-right"></i>
                    </a>
                </li>
                <li class="mobile-nav-item">
                    <a href="{{ route('stock.index') }}" class="mobile-nav-link {{ Route::is('stock.*') ? 'active' : '' }}">
                        <span>{{ $isBn ? 'স্টক মার্কেট' : 'Stock Market' }}</span><i class="fa fa-angle-right"></i>
                    </a>
                </li>
                <li class="mobile-nav-item">
                    <a href="{{ route('properties.index') }}" class="mobile-nav-link {{ Route::is('properties.*') ? 'active' : '' }}">
                        <span><i class="fa fa-map-marker text-success me-2"></i>{{ $isBn ? 'জমি ও প্রপার্টি' : 'Land & Properties' }} <span class="badge bg-danger ms-1">New</span></span>
                        <i class="fa fa-angle-right"></i>
                    </a>
                </li>
                <li class="mobile-nav-item">
                    <a href="{{ route('gallery') }}" class="mobile-nav-link {{ Route::is('gallery') ? 'active' : '' }}">
                        <span>{{ $isBn ? 'গ্যালারি' : 'Gallery' }}</span><i class="fa fa-angle-right"></i>
                    </a>
                </li>
                <li class="mobile-nav-item">
                    <a href="{{ route('contact') }}" class="mobile-nav-link {{ Route::is('contact') ? 'active' : '' }}">
                        <span>{{ $isBn ? 'যোগাযোগ' : 'Contact' }}</span><i class="fa fa-angle-right"></i>
                    </a>
                </li>
                <li class="mobile-nav-item">
                    <a href="{{ route('terms') }}" class="mobile-nav-link {{ Route::is('terms') ? 'active' : '' }}">
                        <span>{{ $isBn ? 'টার্মস এন্ড কন্ডিশন' : 'Terms & Conditions' }}</span><i class="fa fa-angle-right"></i>
                    </a>
                </li>
                <li class="mobile-nav-item">
                    <a href="{{ route('privacy') }}" class="mobile-nav-link {{ Route::is('privacy') ? 'active' : '' }}">
                        <span>{{ $isBn ? 'প্রাইভেসি এন্ড পলিসি' : 'Privacy Policy' }}</span><i class="fa fa-angle-right"></i>
                    </a>
                </li>
            </ul>

            <div class="mobile-drawer-contact mt-4 pt-3 border-top">
                <div class="small text-muted mb-2 fw-bold">{{ $isBn ? 'জরুরী হেল্পলাইন:' : 'Helpline:' }}</div>
                @if(setting('phone1'))
                    <a href="tel:{{ setting('phone1') }}" class="d-block text-dark mb-2 small text-decoration-none">
                        <i class="fa fa-phone text-warning me-2"></i> {{ setting('phone1') }}
                    </a>
                @endif
                @if(setting('email1'))
                    <a href="mailto:{{ setting('email1') }}" class="d-block text-dark small text-decoration-none" style="word-break: break-all;">
                        <i class="fa fa-envelope text-warning me-2"></i> {{ setting('email1') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
    @endif

    @yield("content")

    @if(!Route::is('/'))
    <!-- Unified Modern Colorful Footer -->
    <footer class="react-main-footer-colorful">
        <div class="footer-glow-bar"></div>

        <div class="footer-top-wrap py-5">
            <div class="container">
                <div class="row g-4">
                    <!-- Col 1: About -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="footer-col-content">
                            <a href="{{ route('/') }}" class="footer-brand-logo d-inline-block mb-3">
                                <img src="{{ asset('upload/images/backend/logo') }}/{{ setting('logo') }}" alt="{{ setting('title') }}" />
                            </a>
                            <p class="footer-about-text">
                                {{ setting('footer_about', 'কৃষি পরিবার - আপনার কৃষি সেবা, স্টকের সঠিক মূল্য এবং আর্থিক সমৃদ্ধির নির্ভরযোগ্য ডিজিটাল মাধ্যম।') }}
                            </p>
                        </div>
                    </div>

                    <!-- Col 2: Quick Links -->
                    <div class="col-6 col-md-6 col-lg-3">
                        <div class="footer-col-content">
                            <h4 class="footer-col-title">প্রয়োজনীয় লিঙ্ক</h4>
                            <ul class="footer-links-list">
                                <li><a href="{{ route('/') }}"><i class="fa fa-angle-right me-2 text-warning"></i> হোমপেজ</a></li>
                                <li><a href="{{ route('about') }}"><i class="fa fa-angle-right me-2 text-warning"></i> আমাদের সম্পর্কে</a></li>
                                <li><a href="{{ route('stock.index') }}"><i class="fa fa-angle-right me-2 text-warning"></i> স্টক মার্কেট</a></li>
                                <li><a href="{{ route('gallery') }}"><i class="fa fa-angle-right me-2 text-warning"></i> প্রোডাক্ট গ্যালারি</a></li>
                                <li><a href="{{ route('privacy') }}"><i class="fa fa-angle-right me-2 text-warning"></i> প্রাইভেসি পলিসি</a></li>
                                <li><a href="{{ route('terms') }}"><i class="fa fa-angle-right me-2 text-warning"></i> টার্মস এন্ড কন্ডিশন</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Col 3: Contact Info -->
                    <div class="col-6 col-md-6 col-lg-3">
                        <div class="footer-col-content">
                            <h4 class="footer-col-title">যোগাযোগ করুন</h4>
                            <ul class="footer-contact-info-list">
                                @if(setting('address1'))
                                    <li>
                                        <i class="fa fa-map-marker text-warning mt-1"></i>
                                        <span>{{ setting('address1') }}@if(setting('address2') && setting('address2') !== setting('address1')), {{ setting('address2') }}@endif</span>
                                    </li>
                                @endif
                                @if(setting('phone1'))
                                    <li>
                                        <i class="fa fa-phone text-warning mt-1"></i>
                                        <a href="tel:{{ setting('phone1') }}">{{ setting('phone1') }}</a>
                                    </li>
                                @endif
                                @if(setting('email1'))
                                    <li>
                                        <i class="fa fa-envelope text-warning mt-1"></i>
                                        <a href="mailto:{{ setting('email1') }}" style="word-break: break-all; overflow-wrap: anywhere;">{{ setting('email1') }}</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <!-- Col 4: Social Media -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="footer-col-content">
                            <h4 class="footer-col-title">সোশ্যাল মিডিয়া</h4>
                            <p class="footer-social-desc">
                                আমাদের সাথে সামাজিক যোগাযোগ মাধ্যমে যুক্ত থাকুন ও সর্বশেষ আপডেট পান।
                            </p>
                            <div class="footer-social-buttons d-flex align-items-center flex-wrap">
                                @if(setting('facbook_link'))
                                    <a href="{{ setting('facbook_link') }}" target="_blank" rel="noreferrer" class="ftr-btn fb" title="Facebook" style="margin-right: 12px; margin-bottom: 8px;">
                                        <i class="fa fa-facebook"></i>
                                    </a>
                                @endif
                                @if(setting('instagram_link'))
                                    <a href="{{ setting('instagram_link') }}" target="_blank" rel="noreferrer" class="ftr-btn insta" title="Instagram" style="margin-right: 12px; margin-bottom: 8px;">
                                        <i class="fa fa-instagram"></i>
                                    </a>
                                @endif
                                @if(setting('twitter_link'))
                                    <a href="{{ setting('twitter_link') }}" target="_blank" rel="noreferrer" class="ftr-btn tw" title="Twitter" style="margin-right: 12px; margin-bottom: 8px;">
                                        <i class="fa fa-twitter"></i>
                                    </a>
                                @endif
                                @if(setting('linkedin_link'))
                                    <a href="{{ setting('linkedin_link') }}" target="_blank" rel="noreferrer" class="ftr-btn in" title="LinkedIn" style="margin-right: 12px; margin-bottom: 8px;">
                                        <i class="fa fa-linkedin"></i>
                                    </a>
                                @endif
                                @if(setting('youtube_link'))
                                    <a href="{{ setting('youtube_link') }}" target="_blank" rel="noreferrer" class="ftr-btn yt" title="YouTube" style="margin-right: 12px; margin-bottom: 8px;">
                                        <i class="fa fa-youtube-play"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="footer-bottom-bar py-3">
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <div class="col-12 col-md-6 text-center text-md-start mb-2 mb-md-0">
                        <p class="copyright-text mb-0">
                            {{ setting('footer_copyright', '© '.date('Y').' iKrishiPoribar. All Rights Reserved.') }}
                        </p>
                    </div>
                    <div class="col-12 col-md-6 text-center text-md-end">
                        <p class="developed-by-text mb-0">
                            @if(setting('footer_developed_by_url'))
                                <a href="{{ setting('footer_developed_by_url') }}" target="_blank" rel="noreferrer" class="developed-by-link">
                                    {{ setting('footer_developed_by', 'Smart Agriculture & Stock Market Solutions') }}
                                </a>
                            @else
                                <span>{{ setting('footer_developed_by', 'Smart Agriculture & Stock Market Solutions') }}</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Scroll to Top -->
        <button type="button" class="scroll-to-top-btn" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" aria-label="Scroll to top">
            <i class="fa fa-arrow-up"></i>
        </button>
    </footer>
    @endif


    <!-- JQuery v1.12.4 -->
    <script src="{{ asset('frontend/assets') }}/js/jquery-1.12.4.min.js"></script>

    <!-- Library - Js -->
    <script src="{{ asset('frontend/assets') }}/js/popper.min.js"></script>
    <script src="{{ asset('frontend/assets') }}/js/lib.js"></script>
    <script src="{{ asset('frontend/assets') }}/js/jquery.flagstrap.min.js"></script>

    <!-- REVOLUTION JS FILES -->
    <script type="text/javascript" src="{{ asset('frontend/assets') }}/revolution/js/jquery.themepunch.tools.min.js">
    </script>
    <script type="text/javascript"
        src="{{ asset('frontend/assets') }}/revolution/js/jquery.themepunch.revolution.min.js"></script>

    <!-- SLIDER REVOLUTION 5.0 EXTENSIONS -->
    <script type="text/javascript"
        src="{{ asset('frontend/assets') }}/revolution/js/extensions/revolution.extension.actions.min.js"></script>
    <script type="text/javascript"
        src="{{ asset('frontend/assets') }}/revolution/js/extensions/revolution.extension.carousel.min.js"></script>
    <script type="text/javascript"
        src="{{ asset('frontend/assets') }}/revolution/js/extensions/revolution.extension.kenburn.min.js"></script>
    <script type="text/javascript"
        src="{{ asset('frontend/assets') }}/revolution/js/extensions/revolution.extension.layeranimation.min.js">
    </script>
    <script type="text/javascript"
        src="{{ asset('frontend/assets') }}/revolution/js/extensions/revolution.extension.migration.min.js"></script>
    <script type="text/javascript"
        src="{{ asset('frontend/assets') }}/revolution/js/extensions/revolution.extension.navigation.min.js">
    </script>
    <script type="text/javascript"
        src="{{ asset('frontend/assets') }}/revolution/js/extensions/revolution.extension.parallax.min.js"></script>
    <script type="text/javascript"
        src="{{ asset('frontend/assets') }}/revolution/js/extensions/revolution.extension.slideanims.min.js">
    </script>
    <script type="text/javascript"
        src="{{ asset('frontend/assets') }}/revolution/js/extensions/revolution.extension.video.min.js"></script>

    <!-- Library - Theme JS -->
    <script src="{{ asset('frontend/assets') }}/js/functions.js"></script>

    <!-- React Application Bundle -->
    <script src="{{ asset('js/app.js') }}"></script>

    @include('components.chat-widget')

    <!-- Preloader Auto-dismiss Script -->
    <script>
        (function () {
            function dismissSitePreloader() {
                var pl = document.getElementById('site-preloader');
                if (pl && !pl.classList.contains('loaded')) {
                    pl.classList.add('loaded');
                    setTimeout(function () {
                        if (pl && pl.parentNode) {
                            pl.style.display = 'none';
                        }
                    }, 500);
                }
            }
            if (document.readyState === 'complete') {
                setTimeout(dismissSitePreloader, 100);
            } else {
                window.addEventListener('load', function () {
                    setTimeout(dismissSitePreloader, 150);
                });
                // Fallback timeout so preloader never stays stuck
                setTimeout(dismissSitePreloader, 3000);
            }
        })();

        // Bilingual Language Switcher Function
        function switchSiteLang(lang) {
            try {
                localStorage.setItem('ekrishi_lang', lang);
                document.cookie = 'ekrishi_lang=' + encodeURIComponent(lang) + ';path=/;max-age=31536000';
            } catch (e) {}
            window.location.reload();
        }

        // Dark / Black Mode Theme Switcher Function
        function switchSiteTheme(theme) {
            try {
                localStorage.setItem('ekrishi_theme', theme);
                document.cookie = 'ekrishi_theme=' + encodeURIComponent(theme) + ';path=/;max-age=31536000';
            } catch (e) {}
            window.location.reload();
        }

        // Blade Pages Live Search Handler
        (function () {
            function bindBladeSearch(inputId, resultsId, clearId) {
                var input = document.getElementById(inputId);
                var results = document.getElementById(resultsId);
                var clear = clearId ? document.getElementById(clearId) : null;
                if (!input || !results) return;

                var timer = null;

                function doSearch(q) {
                    if (!q || q.trim().length === 0) {
                        results.style.display = 'none';
                        results.innerHTML = '';
                        if (clear) clear.style.display = 'none';
                        return;
                    }
                    if (clear) clear.style.display = 'block';

                    if (timer) clearTimeout(timer);
                    timer = setTimeout(function () {
                        fetch('/api/live-search?q=' + encodeURIComponent(q.trim()))
                            .then(function (res) { return res.json(); })
                            .then(function (data) {
                                if (!data || data.total === 0) {
                                    results.innerHTML = '<div class="p-4 text-center text-muted"><p class="mb-0 small">No matching results for "' + q + '"</p></div>';
                                    results.style.display = 'block';
                                    return;
                                }
                                var html = '<div class="search-dropdown-body">';
                                if (data.stocks && data.stocks.length) {
                                    html += '<div class="search-group-section"><div class="search-group-title">Stocks & Products</div>';
                                    data.stocks.forEach(function (s) {
                                        html += '<a href="' + s.url + '" class="search-result-row text-decoration-none">';
                                        html += '<div class="result-thumb-box"><img src="' + s.image + '" alt="' + s.title + '" /></div>';
                                        html += '<div class="result-info-box flex-grow-1"><div class="result-title">' + s.title + '</div><div class="result-sub text-muted">' + s.subtitle + '</div></div>';
                                        if (s.price) html += '<div class="result-price-badge">' + s.price + '</div>';
                                        html += '</a>';
                                    });
                                    html += '</div>';
                                }
                                if (data.packages && data.packages.length) {
                                    html += '<div class="search-group-section"><div class="search-group-title">Packages</div>';
                                    data.packages.forEach(function (p) {
                                        html += '<a href="' + p.url + '" class="search-result-row text-decoration-none">';
                                        html += '<div class="result-thumb-box"><img src="' + p.image + '" alt="' + p.title + '" /></div>';
                                        html += '<div class="result-info-box flex-grow-1"><div class="result-title">' + p.title + '</div><div class="result-sub text-muted">' + p.subtitle + '</div></div>';
                                        html += '</a>';
                                    });
                                    html += '</div>';
                                }
                                if (data.cards && data.cards.length) {
                                    html += '<div class="search-group-section"><div class="search-group-title">Membership Cards</div>';
                                    data.cards.forEach(function (c) {
                                        html += '<a href="' + c.url + '" class="search-result-row text-decoration-none">';
                                        html += '<div class="result-thumb-box"><i class="fa fa-id-card text-muted"></i></div>';
                                        html += '<div class="result-info-box flex-grow-1"><div class="result-title">' + c.title + '</div><div class="result-sub text-muted">' + c.subtitle + '</div></div>';
                                        html += '</a>';
                                    });
                                    html += '</div>';
                                }
                                if (data.pages && data.pages.length) {
                                    html += '<div class="search-group-section"><div class="search-group-title">Pages & Links</div>';
                                    data.pages.forEach(function (pg) {
                                        html += '<a href="' + pg.url + '" class="search-result-row text-decoration-none">';
                                        html += '<div class="result-icon-circle"><i class="fa fa-link"></i></div>';
                                        html += '<div class="result-info-box flex-grow-1"><div class="result-title">' + pg.title + '</div><div class="result-sub text-muted">' + pg.subtitle + '</div></div>';
                                        html += '</a>';
                                    });
                                    html += '</div>';
                                }
                                html += '</div>';
                                results.innerHTML = html;
                                results.style.display = 'block';
                            })
                            .catch(function () {});
                    }, 250);
                }

                input.addEventListener('input', function () { doSearch(input.value); });
                input.addEventListener('focus', function () { if (input.value.trim().length > 0) doSearch(input.value); });

                if (clear) {
                    clear.addEventListener('click', function () {
                        input.value = '';
                        results.style.display = 'none';
                        clear.style.display = 'none';
                    });
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                bindBladeSearch('bladeSearchInput', 'bladeSearchResults', 'bladeSearchClear');
                bindBladeSearch('bladeMobileSearchInput', 'bladeMobileSearchResults', null);

                document.addEventListener('click', function (e) {
                    var deskWrap = document.getElementById('bladeSearchWrap');
                    var deskRes = document.getElementById('bladeSearchResults');
                    if (deskRes && deskWrap && !deskWrap.contains(e.target)) {
                        deskRes.style.display = 'none';
                    }
                    var mobWrap = document.getElementById('bladeMobileSearchWrap');
                    var mobRes = document.getElementById('bladeMobileSearchResults');
                    if (mobRes && mobWrap && !mobWrap.contains(e.target)) {
                        mobRes.style.display = 'none';
                    }
                });
                // Sticky Navbar Scroll Elevation
                window.addEventListener('scroll', function () {
                    var hdr = document.getElementById('bladeMainHeader');
                    if (hdr) {
                        if (window.scrollY > 25) {
                            hdr.classList.add('header-scrolled', 'shadow-lg');
                        } else {
                            hdr.classList.remove('header-scrolled', 'shadow-lg');
                        }
                    }
                }, { passive: true });
            });
        })();
    </script>

</body>

</html>
