<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    @php
        $site_setting = class_exists(\App\Models\Admin\SiteSetting::class) ? \App\Models\Admin\SiteSetting::first() : null;
        $site_name = $site_setting->site_name ?? $site_setting->name ?? 'ekrishisheba';
        $site_logo = !empty($site_setting->logo) ? asset($site_setting->logo) : asset('assets/images/logo.png');
    @endphp

    <link rel="icon" href="{{ $site_logo }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ $site_logo }}" type="image/x-icon">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $site_name }} - এডমিন প্যানেল</title>



    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/perfect-scrollbar/css/perfect-scrollbar.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/morris.js/morris.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/OwlCarousel2/dist/assets/owl.carousel.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/OwlCarousel2/dist/assets/owl.theme.default.min.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/horizontal-timeline/css/horizontal-timeline.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/flexslider/flexslider.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/prism/prism.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/datatable/datatables.min.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/Magnific-Popup-master/dist/magnific-popup.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/gallery/css/animated-masonry-gallery.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/lightbox-master/dist/ekko-lightbox.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/jvectormap/lib2/jquery-jvectormap-2.0.2.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/sweetalert/sweetalert.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-markdown-master/css/bootstrap-markdown.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/dropzone/dropzone.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/select2/dist/css/select2.min.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-daterangepicker/daterangepicker.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-select/dist/css/bootstrap-select.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/raty-master/lib/jquery.raty.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/ion-rangeSlider/css/ion.rangeSlider.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/ion-rangeSlider/css/ion.rangeSlider.skinModern.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/gridstack/gridstack.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/jquery-toast-plugin-master/src/jquery.toast.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/nestable/nestable.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-switch/switch.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/c3/c3.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/chartist-js-develop/chartist.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_plugins/bootstrap-slider/slider.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_plugins/iCheck/flat/blue.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_plugins/iCheck/all.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_plugins/timepicker/bootstrap-timepicker.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_plugins/pace/pace.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/fullcalendar/fullcalendar.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/fullcalendar/fullcalendar.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/weather-icons/weather-icons.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/FlipClock-master/compiled/flipclock.css">
    {{-- /*# sourceMappingURL=vendors_css.css.map */ --}}


    <!-- Style-->
    <link rel="stylesheet" href="{{ asset('assets/main/css/color_theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/main/css/style_rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/font-awesome/css/font-awesome.css">
    {{-- <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/font-awesome/css/all.min"> --}}
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/Ionicons/css/ionicons.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/linea-icons/linea.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/glyphicons/glyphicon.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/flag-icon-css/css/flag-icon.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/icons/material-design-iconic-font/css/materialdesignicons.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/cryptocoins-master/cryptocoins.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/weather-icons/css/weather-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/iconsmind/style.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/icomoon/style.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/animate/animate.css">
    <link rel="stylesheet" href="{{ asset('assets/main/css/style.css') }}?v={{ file_exists(public_path('assets/main/css/style.css')) ? filemtime(public_path('assets/main/css/style.css')) : time() }}">
    <link rel="stylesheet" href="{{ asset('assets/main/css/skin_color.css') }}">
    <!-- Premium Modern Admin Custom Styles -->
    <link rel="stylesheet" href="{{ asset('assets/main/css/modern_admin.css') }}?v={{ file_exists(public_path('assets/main/css/modern_admin.css')) ? filemtime(public_path('assets/main/css/modern_admin.css')) : time() }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Dynamic Font from Settings -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ getGoogleFontUrl() }}" rel="stylesheet">

    <style>
        @php
            $activeFont = getActiveFontConfig();
            $fontFamilyCss = "'" . $activeFont['name'] . "', 'Hind Siliguri', " . $activeFont['fallback'];
        @endphp
        :root {
            --site-font-family: {!! $fontFamilyCss !!};
        }
        body, html, h1, h2, h3, h4, h5, h6, .main-header, .sidebar-menu, .box-title, .page-title {
            font-family: var(--site-font-family) !important;
        }
        .main-header {
            position: -webkit-sticky !important;
            position: sticky !important;
            top: 0 !important;
            z-index: 1030 !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1) !important;
        }
        .main-header .logo {
            width: auto !important;
            min-width: 150px !important;
            max-width: 230px !important;
        }
    </style>
</head>

<body class="hold-transition light-skin sidebar-mini theme-warning fixed">

    <div class="wrapper">
        <!-- Admin Animated Preloader with Main Logo -->
        <div id="loader" class="ekrishi-preloader-wrap">
            <div class="ekrishi-preloader-inner">
                <div class="preloader-logo-ring-outer">
                    <div class="preloader-logo-ring-pulse"></div>
                    <div class="preloader-logo-ring-spinner"></div>
                    <div class="preloader-logo-circle">
                        <img src="{{ asset('upload/images/backend/logo') }}/{{ setting('logo') }}" alt="ekrishisheba" class="preloader-logo-img">
                    </div>
                </div>
                <h3 class="preloader-brand-title">ekrishisheba</h3>
                <p class="preloader-tagline">এডমিন কন্ট্রোল প্যানেল লোড হচ্ছে...</p>
                <div class="preloader-progress-bar">
                    <div class="preloader-progress-fill"></div>
                </div>
                <small class="preloader-loading-text">অনুগ্রহ করে একটু অপেক্ষা করুন...</small>
            </div>
        </div>

        <header class="main-header">
            <div class="d-flex align-items-center logo-box justify-content-start">

                <!-- Mobile Menu Hamburger Button -->
                <a href="javascript:void(0);" class="waves-effect waves-light nav-link push-btn btn-outline no-border d-inline-flex d-md-none mobile-hamburger-btn me-2"
                    id="mobileMenuToggle" role="button" aria-label="Toggle Navigation" title="মেনু খুলুন">
                    <i class="fa fa-bars font-size-18 text-dark"></i>
                </a>

                <!-- Logo -->
                <a href="{{ route('home') }}" class="logo">
                    <div class="logo-lg d-flex align-items-center">
                        <span class="light-logo">
                            <img style="height: 44px; max-height: 48px; width: auto; max-width: 180px; object-fit: contain; background: transparent !important; padding: 0 !important;" src="{{ asset('upload/images/backend/logo') }}/{{ setting('logo') }}" alt="logo">
                        </span>
                        <span class="dark-logo">
                            <img style="height: 44px; max-height: 48px; width: auto; max-width: 180px; object-fit: contain; background: transparent !important; padding: 0 !important;" src="{{ asset('upload/images/backend/logo') }}/{{ setting('logo') }}" alt="logo">
                        </span>
                    </div>
                </a>
            </div>
            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top">
                <!-- Left Section: Sidebar toggle & Search Bar (Screenshot matching) -->
                <div class="app-menu d-flex align-items-center">
                    <ul class="header-megamenu nav align-items-center">
                        <li class="btn-group nav-item">
                            <a href="#" class="waves-effect waves-light nav-link push-btn btn-outline no-border me-2"
                                data-toggle="push-menu" role="button" title="Toggle Sidebar">
                                <i class="fa fa-bars font-size-18 text-secondary"></i>
                            </a>
                        </li>
                        <li class="btn-group nav-item d-none d-sm-block">
                            <div class="header-search-bar">
                                <i class="fa fa-search"></i>
                                <input type="text" placeholder="Type here to search">
                            </div>
                        </li>

                        @if (Auth::user()->role == "employee")
                            <li class="btn-group d-lg-inline-flex d-none ms-3">
                                <div class="" id="myDiv2" style="line-height: 46px;font-weight: 900;color: red;">
                                    {{ route('/') }}/register?ref={{ Auth::user()->username }}
                                </div>
                                <button style="margin-left: 10px;padding: 5px 10px !important;height: 34px;margin-top: 7px;" id="myButton2" class="btn btn-sm btn-success">Copy Link</button>
                            </li>
                        @endif
                    </ul>
                </div>

                <!-- Right Section: Flag, Dark Toggle, Apps Grid, Notifications, Messages, User Profile (Screenshot matching) -->
                <div class="navbar-custom-menu r-side">
                    <ul class="nav navbar-nav d-flex align-items-center flex-row gap-2">
                        <!-- Flag / Language -->
                        <li class="nav-item">
                            <a href="javascript:void(0)" class="nav-link btn-header-icon" title="Language">
                                <span style="font-size: 16px;">🇺🇸</span>
                            </a>
                        </li>

                        <!-- Dark / Light Mode Toggle -->
                        <li class="nav-item">
                            <a href="javascript:void(0)" class="nav-link btn-header-icon" id="themeModeToggle" title="Toggle Mode">
                                <i class="fa fa-moon-o"></i>
                            </a>
                        </li>

                        <!-- Apps Grid / Modules -->
                        <li class="nav-item d-none d-sm-block">
                            <a href="javascript:void(0)" class="nav-link btn-header-icon" title="Grid Menu">
                                <i class="fa fa-th-large"></i>
                            </a>
                        </li>

                        <!-- Notifications Bell -->
                        <li class="nav-item position-relative">
                            <a href="javascript:void(0)" class="nav-link btn-header-icon position-relative" title="Notifications">
                                <i class="fa fa-bell-o"></i>
                                <span class="header-badge-count">5</span>
                            </a>
                        </li>

                        <!-- Messages -->
                        <li class="nav-item position-relative">
                            <a href="javascript:void(0)" class="nav-link btn-header-icon position-relative" title="Messages">
                                <i class="fa fa-commenting-o"></i>
                                <span class="header-badge-count">0</span>
                            </a>
                        </li>

                        @if(Auth::check() && Auth::user()->role == "user")
                        <li>
                            @php
                                $headerCartCount = \App\Models\UserCart::where('user_id', Auth::id())->sum('quantity');
                            @endphp
                            <a href="{{ route('my.cart') }}" class="btn-outline no-border position-relative text-dark d-flex align-items-center justify-content-center" title="My Cart" style="width: 36px; height: 36px; border-radius: 8px; background: rgba(0,0,0,0.04); font-size: 16px; text-decoration: none;">
                                <i class="fa fa-shopping-cart text-success"></i>
                                <span class="badge bg-danger rounded-pill user-cart-badge {{ $headerCartCount > 0 ? '' : 'd-none' }}" style="position: absolute; top: -3px; right: -4px; font-size: 10px; padding: 2px 5px; min-width: 17px;">{{ $headerCartCount }}</span>
                            </a>
                        </li>
                        @endif

                        <!-- User Profile Pill -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="waves-effect waves-light dropdown-toggle header-user-pill"
                                data-bs-toggle="dropdown" title="User Profile">
                                <img src="{{ !empty(Auth::user()->avatar) && file_exists(public_path('upload/images/user/' . Auth::user()->avatar)) ? asset('upload/images/user/' . Auth::user()->avatar) : asset('assets/images/avatar.png') }}"
                                    class="header-avatar-circle" alt="User" onerror="this.src='{{ asset('assets/images/avatar.png') }}'" />
                                <div class="d-none d-md-flex flex-column text-start">
                                    <span class="header-user-name">{{ Auth::user()->name ?? 'Super Admin' }}</span>
                                    <span class="header-user-role">{{ optional(Auth::user()->roleRelation)->name ?? (Auth::user()->role == 'super_admin' ? 'Super Admin' : (Auth::user()->role == 'admin' ? 'HR Manager' : ucfirst(Auth::user()->role))) }}</span>
                                </div>
                                <i class="fa fa-angle-down text-muted font-size-12 ms-1"></i>
                            </a>
                            <ul class="dropdown-menu animated flipInX">
                                <li class="user-body">
                                    <a class="dropdown-item" href="{{ route('profile.index') }}">
                                        <i class="ti-user text-muted me-2"></i>
                                        Profile
                                    </a>

                                    @if (Auth::user()->role == "user")
                                        @php
                                            $userDropdownCartCount = \App\Models\UserCart::where('user_id', Auth::id())->sum('quantity');
                                        @endphp
                                        <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('my.cart') }}">
                                            <div><i class="ti-shopping-cart text-muted me-2"></i> My Cart</div>
                                            <span class="badge bg-danger rounded-pill user-cart-badge {{ $userDropdownCartCount > 0 ? '' : 'd-none' }}" style="font-size: 10px; padding: 2px 6px;">{{ $userDropdownCartCount }}</span>
                                        </a>
                                    @endif

                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                        <i class="ti-lock text-muted me-2"></i>
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- Floating Settings Gear Tab (Screenshot matching) -->
        <a href="javascript:void(0);" id="openThemeSettings" class="floating-settings-gear-tab" title="Theme Settings Customizer">
            <i class="fa fa-cog fa-spin-hover"></i>
        </a>


        <aside class="main-sidebar">
            <!-- Mobile Sidebar Drawer Header with Close Button -->
            <div class="sidebar-mobile-header d-flex align-items-center justify-content-between px-3 py-3 d-md-none" style="border-bottom: 1px solid #f1f5f9; background: #ffffff;">
                <div class="d-flex align-items-center gap-2">
                    <img style="height: 34px; width: auto; max-width: 110px; object-fit: contain; background: transparent !important; padding: 0 !important; border-radius: 0 !important;" src="{{ asset('upload/images/backend/logo') }}/{{ setting('logo') }}" alt="logo">
                    <div>
                        <div class="text-dark fw-bold font-size-14" style="line-height: 1.2;">ekrishisheba Admin</div>
                        <small class="text-muted font-size-11">{{ Auth::user()->name ?? 'Administrator' }}</small>
                    </div>
                </div>
                <button type="button" class="btn btn-sm rounded-circle sidebar-mobile-close-btn" id="sidebarMobileCloseBtn" style="width: 32px; height: 32px; min-width: 32px; padding: 0; display: flex; align-items: center; justify-content: center; background: #f1f5f9; border: 1px solid #e2e8f0; color: #334155;" title="মেনু বন্ধ করুন">
                    <i class="fa fa-times text-dark font-size-14"></i>
                </button>
            </div>
            <!-- sidebar-->
            <section class="sidebar position-relative">
                <div class="multinav">
                    <div class="multinav-scroll" style="height: 100%;">


                        <!-- sidebar menu-->
                        <ul class="sidebar-menu" data-widget="tree">
                            <!-- ================================= Common sidebar menu  Item-->
                            <li class="{{ (optional(Request::route())->getName() == 'home' || Str::contains(optional(Request::route())->getName(), 'dashboard')) ? 'menu-open active' : '' }}">
                                <a href="{{ route(Auth::user()->getDashboardRoute()) }}">
                                    <i class="ti-dashboard"></i>
                                    <span>ড্যাশবোর্ড (Dashboard)</span>
                                </a>
                            </li>
                            <!-- ================================= /Common sidebar menu  Item-->

                            <!-- ========================================================================= -->
                            <!-- 1. Super Shop & Multi-Branch Retail Chain Ecosystem (Shwapno Model) -->
                            <!-- ========================================================================= -->
                            @if(Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin' || Auth::user()->role == 'manager' || Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager'))
                                <li class="header text-uppercase text-success fw-bold" style="letter-spacing: 0.5px;">
                                    <i class="fa fa-shopping-bag text-success me-1"></i> সুপারশপ ও রিটেল চেইন (Multi-Branch POS)
                                </li>
                                <li class="treeview {{ Str::contains(optional(Request::route())->getName(), 'admin.supershop') ? 'menu-open active' : '' }}">
                                    <a href="#">
                                        <i class="ti-layout-grid2 text-success"></i>
                                        <span>সুপারশপ ও মাল্টি-ব্রাঞ্চ</span>
                                        <span class="pull-right-container">
                                            <i class="fa fa-angle-right pull-right"></i>
                                        </span>
                                    </a>
                                    <ul class="treeview-menu {{ Str::contains(optional(Request::route())->getName(), 'admin.supershop') ? 'd-block' : '' }}">
                                        <!-- 1. Central Dashboard -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.supershop.dashboard' ? 'active' : '' }}">
                                            <a href="{{ route('admin.supershop.dashboard') }}">
                                                <i class="ti-dashboard text-success"></i> <span>সেন্ট্রাল ড্যাশবোর্ড (Dashboard)</span>
                                            </a>
                                        </li>

                                        <!-- 2. Reports & Analytics (Branch, Date, Month & Product Breakdown) -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.supershop.reports' ? 'active' : '' }}">
                                            <a href="{{ route('admin.supershop.reports') }}">
                                                <i class="ti-bar-chart text-info"></i> <span>ব্রাঞ্চ বিক্রয়, তারিখ ও লাভ-ক্ষতি</span>
                                            </a>
                                        </li>

                                        <!-- 3. POS Terminal (Direct Billing) -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.supershop.pos' ? 'active' : '' }}">
                                            <a href="{{ route('admin.supershop.pos') }}">
                                                <i class="ti-shopping-cart-full text-warning"></i> <span>POS কাউন্টার বিলিং (Terminal)</span>
                                            </a>
                                        </li>

                                        <!-- 4. Branch Management -->
                                        @if(Auth::check() && Auth::user()->role === 'manager')
                                            @php
                                                $myBranchId = Auth::user()->branch_id ?: \App\Models\SuperShop\Branch::where('manager_id', Auth::id())->value('id');
                                            @endphp
                                            @if($myBranchId)
                                                <li class="{{ optional(Request::route())->getName() == 'admin.supershop.branches.show' && Request::route('id') == $myBranchId ? 'active' : '' }}">
                                                    <a href="{{ route('admin.supershop.branches.show', $myBranchId) }}">
                                                        <i class="ti-home text-primary"></i> <span>আমার ব্রাঞ্চ ড্যাশবোর্ড (My Branch)</span>
                                                    </a>
                                                </li>
                                            @endif
                                        @else
                                            <li class="{{ optional(Request::route())->getName() == 'admin.supershop.branches' ? 'active' : '' }}">
                                                <a href="{{ route('admin.supershop.branches') }}">
                                                    <i class="ti-home text-primary"></i> <span>সকল ব্রাঞ্চ ওভারভিউ (Overview)</span>
                                                </a>
                                            </li>

                                            <!-- 4.1 Branch Comparison -->
                                            <li class="{{ optional(Request::route())->getName() == 'admin.supershop.branches.comparison' ? 'active' : '' }}">
                                                <a href="{{ route('admin.supershop.branches.comparison') }}">
                                                    <i class="ti-stats-up text-success"></i> <span>ব্রাঞ্চ পারফর্ম্যান্স তুলনা (Comparison)</span>
                                                </a>
                                            </li>
                                        @endif

                                        <!-- 5. Branch Inventory -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.supershop.inventory' ? 'active' : '' }}">
                                            <a href="{{ route('admin.supershop.inventory') }}">
                                                <i class="ti-archive text-info"></i> <span>ব্রাঞ্চ স্টক ও ইনভেন্টরি (Inventory)</span>
                                            </a>
                                        </li>

                                        <!-- 6. Branch Expenses (Electricity, Rent, Wages) -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.supershop.expenses' ? 'active' : '' }}">
                                            <a href="{{ route('admin.supershop.expenses') }}">
                                                <i class="ti-money text-danger"></i> <span>ব্রাঞ্চ পরিচালন ব্যয় (বিদ্যুৎ/ভাড়া)</span>
                                            </a>
                                        </li>

                                        <!-- 7. POS Sales History -->
                                        <li class="{{ in_array(optional(Request::route())->getName(), ['admin.supershop.sales', 'admin.supershop.sales.show']) ? 'active' : '' }}">
                                            <a href="{{ route('admin.supershop.sales') }}">
                                                <i class="ti-receipt text-primary"></i> <span>POS বিক্রয় চালান (Sales & Return)</span>
                                            </a>
                                        </li>

                                        <!-- 8. Stock Transfers -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.supershop.transfers' ? 'active' : '' }}">
                                            <a href="{{ route('admin.supershop.transfers') }}">
                                                <i class="ti-exchange-vertical text-warning"></i> <span>ব্রাঞ্চ ট্রান্সফার (Transfers)</span>
                                            </a>
                                        </li>

                                        <!-- 9. Central Procurement & Suppliers -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.supershop.procurement' ? 'active' : '' }}">
                                            <a href="{{ route('admin.supershop.procurement') }}">
                                                <i class="ti-truck text-success"></i> <span>সাপ্লায়ার ও ক্রয় (PO & GRN)</span>
                                            </a>
                                        </li>

                                        <!-- 10. Cashier Shifts -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.supershop.shifts' ? 'active' : '' }}">
                                            <a href="{{ route('admin.supershop.shifts') }}">
                                                <i class="ti-lock text-danger"></i> <span>ক্যাশিয়ার শিফট ও ড্রয়ার (Shifts)</span>
                                            </a>
                                        </li>

                                        <!-- 11. Stock Movements -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.supershop.inventory.movements' ? 'active' : '' }}">
                                            <a href="{{ route('admin.supershop.inventory.movements') }}">
                                                <i class="ti-time text-secondary"></i> <span>স্টক অডিট ট্রেইল (Movements)</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif

                            <!-- ========================================================================= -->
                            <!-- Branch Employee, Attendance & Salary Advance Management System -->
                            <!-- ========================================================================= -->
                            @if(Auth::check() && (Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin' || Auth::user()->role == 'manager' || (method_exists(Auth::user(), 'hasRole') && (Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager')))))
                                <li class="header text-uppercase text-success fw-bold" style="letter-spacing: 0.5px;">
                                    <i class="fa fa-users text-success me-1"></i> ব্রাঞ্চ কর্মচারী ও হাজিরা সিস্টেম
                                </li>
                                <li class="treeview {{ Str::contains(optional(Request::route())->getName(), 'employee_system') ? 'menu-open active' : '' }}">
                                    <a href="#">
                                        <i class="ti-id-badge text-success"></i>
                                        <span>কর্মচারী, হাজিরা ও অগ্রিম</span>
                                        <span class="pull-right-container">
                                            <i class="fa fa-angle-right pull-right"></i>
                                        </span>
                                    </a>
                                    <ul class="treeview-menu {{ Str::contains(optional(Request::route())->getName(), 'employee_system') ? 'd-block' : '' }}">
                                        <!-- 1. Dashboard -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.supershop.employee_system.dashboard' ? 'active' : '' }}">
                                            <a href="{{ route('admin.supershop.employee_system.dashboard') }}">
                                                <i class="ti-dashboard text-primary"></i> <span>কর্মচারী ড্যাশবোর্ড</span>
                                            </a>
                                        </li>

                                        <!-- 2. Employees Master List -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.supershop.employee_system.employees.index' ? 'active' : '' }}">
                                            <a href="{{ route('admin.supershop.employee_system.employees.index') }}">
                                                <i class="ti-user text-info"></i> <span>স্টাফ তালিকা ও বায়োডাটা</span>
                                            </a>
                                        </li>

                                        <!-- 3. Add Employee -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.supershop.employee_system.employees.create' ? 'active' : '' }}">
                                            <a href="{{ route('admin.supershop.employee_system.employees.create') }}">
                                                <i class="ti-plus text-success"></i> <span>নতুন কর্মচারী নিবন্ধন</span>
                                            </a>
                                        </li>

                                        <!-- 4. Daily Attendance Sheet -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.supershop.employee_system.attendance.index' ? 'active' : '' }}">
                                            <a href="{{ route('admin.supershop.employee_system.attendance.index') }}">
                                                <i class="ti-calendar text-primary"></i> <span>দৈনিক উপস্থিতি (Attendance)</span>
                                            </a>
                                        </li>

                                        <!-- 5. Attendance Calendar -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.supershop.employee_system.attendance.calendar' ? 'active' : '' }}">
                                            <a href="{{ route('admin.supershop.employee_system.attendance.calendar') }}">
                                                <i class="ti-layout-grid3 text-warning"></i> <span>মাসিক উপস্থিতি ক্যালেন্ডার</span>
                                            </a>
                                        </li>

                                        <!-- 6. Biometric Fingerprint Machines -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.supershop.employee_system.devices.index' ? 'active' : '' }}">
                                            <a href="{{ route('admin.supershop.employee_system.devices.index') }}">
                                                <i class="ti-thumb-up text-secondary"></i> <span>বায়োমেট্রিক মেশিন সিঙ্ক</span>
                                            </a>
                                        </li>

                                        <!-- 7. Salary Advance -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.supershop.employee_system.advances.index' ? 'active' : '' }}">
                                            <a href="{{ route('admin.supershop.employee_system.advances.index') }}">
                                                <i class="ti-wallet text-warning"></i> <span>বেতন অগ্রিম (Advance)</span>
                                            </a>
                                        </li>

                                        <!-- 8. Central Advance Report (Admin only) -->
                                        @if(Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin' || (method_exists(Auth::user(), 'hasRole') && (Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('admin'))))
                                            <li class="{{ optional(Request::route())->getName() == 'admin.supershop.employee_system.advances.central_report' ? 'active' : '' }}">
                                                <a href="{{ route('admin.supershop.employee_system.advances.central_report') }}">
                                                    <i class="ti-bar-chart-alt text-danger"></i> <span>অল-ব্রাঞ্চ অগ্রিম রিপোর্ট</span>
                                                </a>
                                            </li>
                                        @endif

                                        <!-- 9. Monthly Payroll -->
                                        <li class="{{ in_array(optional(Request::route())->getName(), ['admin.supershop.employee_system.payroll.index', 'admin.supershop.employee_system.payroll.show']) ? 'active' : '' }}">
                                            <a href="{{ route('admin.supershop.employee_system.payroll.index') }}">
                                                <i class="ti-receipt text-success"></i> <span>মাসিক বেতন ও পে-রোল</span>
                                            </a>
                                        </li>

                                        <!-- 10. Leaves -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.supershop.employee_system.leaves.index' ? 'active' : '' }}">
                                            <a href="{{ route('admin.supershop.employee_system.leaves.index') }}">
                                                <i class="ti-time text-info"></i> <span>ছুটি ব্যবস্থাপনা (Leaves)</span>
                                            </a>
                                        </li>

                                        <!-- 11. Reports -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.supershop.employee_system.reports.index' ? 'active' : '' }}">
                                            <a href="{{ route('admin.supershop.employee_system.reports.index') }}">
                                                <i class="ti-bar-chart text-dark"></i> <span>হাজিরা, বেতন ও সেলস রিপোর্ট</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif

                            <!-- ========================================================================= -->
                            <!-- 2. Monthly Bazaar Management (মাসিক বাজার) -->
                            <!-- ========================================================================= -->
                            @if(Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin' || Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('admin') || Auth::user()->hasPermission('monthly_bazaar'))
                                <li class="header text-uppercase text-warning fw-bold" style="letter-spacing: 0.5px;">
                                    <i class="fa fa-shopping-basket text-warning me-1"></i> মাসিক বাজার ম্যানেজমেন্ট
                                </li>
                                <li class="treeview {{ (Str::contains(optional(Request::route())->getName(), 'admin.monthly_bazaar') || Str::contains(optional(Request::route())->getName(), 'admin.agent_points')) ? 'menu-open active' : '' }}">
                                    <a href="#">
                                        <i class="ti-shopping-cart text-warning"></i>
                                        <span>মাসিক বাজার হিসাব ও প্যাকেজ</span>
                                        <span class="pull-right-container">
                                            <i class="fa fa-angle-right pull-right"></i>
                                        </span>
                                    </a>
                                    <ul class="treeview-menu {{ (Str::contains(optional(Request::route())->getName(), 'admin.monthly_bazaar') || Str::contains(optional(Request::route())->getName(), 'admin.agent_points')) ? 'd-block' : '' }}">
                                        <li class="{{ optional(Request::route())->getName() == 'admin.monthly_bazaar.index' ? 'active' : '' }}">
                                            <a href="{{ route('admin.monthly_bazaar.index') }}">
                                                <i class="ti-package text-info"></i>
                                                <span>মাসিক বাজার প্যাকেজসমূহ</span>
                                            </a>
                                        </li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.monthly_bazaar.orders' ? 'active' : '' }}">
                                            <a href="{{ route('admin.monthly_bazaar.orders') }}">
                                                <i class="ti-receipt text-success"></i>
                                                <span>অর্ডার রিকোয়েস্ট ও মোট চাহিদা</span>
                                            </a>
                                        </li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.monthly_bazaar.distribution_reports' ? 'active' : '' }}">
                                            <a href="{{ route('admin.monthly_bazaar.distribution_reports') }}">
                                                <i class="ti-bar-chart text-primary"></i>
                                                <span>এলাকাভিত্তিক ডিস্ট্রিবিউশন হিসাব</span>
                                            </a>
                                        </li>
                                        <li class="{{ Str::startsWith(optional(Request::route())->getName() ?? '', 'admin.agents.') ? 'active' : '' }}">
                                            <a href="{{ route('admin.agents.index') }}">
                                                <i class="fa fa-users text-primary"></i>
                                                <span>এজেন্ট ও পয়েন্ট ব্যবস্থাপনা</span>
                                            </a>
                                        </li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.agents.assign_cardholders' ? 'active' : '' }}">
                                            <a href="{{ route('admin.agents.assign_cardholders') }}">
                                                <i class="fa fa-id-card text-info"></i>
                                                <span>কার্ডহোল্ডার পয়েন্ট ম্যাপিং</span>
                                            </a>
                                        </li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.agents.supply_allocation' ? 'active' : '' }}">
                                            <a href="{{ route('admin.agents.supply_allocation') }}">
                                                <i class="fa fa-truck text-warning"></i>
                                                <span>পণ্য বরাদ্দ ও ডিস্ট্রিবিউশন</span>
                                            </a>
                                        </li>

                                        <!-- Module 10: Central Customer Distribution Monitoring -->
                                        <li class="header text-uppercase text-info fw-bold py-2 px-3 font-size-11" style="letter-spacing: 0.5px;">
                                            <i class="fa fa-cubes text-info me-1"></i> ডিস্ট্রিবিউশন মনিটরিং
                                        </li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.distribution.dashboard' ? 'active' : '' }}">
                                            <a href="{{ route('admin.distribution.dashboard') }}">
                                                <i class="fa fa-dashboard text-danger"></i>
                                                <span>সেন্ট্রাল বিতরণ ড্যাশবোর্ড</span>
                                            </a>
                                        </li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.distribution.customer_list' ? 'active' : '' }}">
                                            <a href="{{ route('admin.distribution.customer_list') }}">
                                                <i class="fa fa-id-card text-success"></i>
                                                <span>গ্রাহক সংগ্রহ খতিয়ান</span>
                                            </a>
                                        </li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.distribution.daily_agent_report' ? 'active' : '' }}">
                                            <a href="{{ route('admin.distribution.daily_agent_report') }}">
                                                <i class="fa fa-calendar-check-o text-warning"></i>
                                                <span>দৈনিক ক্রস-এজেন্ট রিপোর্ট</span>
                                            </a>
                                        </li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.distribution.product_report' ? 'active' : '' }}">
                                            <a href="{{ route('admin.distribution.product_report') }}">
                                                <i class="fa fa-cubes text-info"></i>
                                                <span>পণ্যভিত্তিক বিতরণ খতিয়ান</span>
                                            </a>
                                        </li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.distribution.date_wise_report' ? 'active' : '' }}">
                                            <a href="{{ route('admin.distribution.date_wise_report') }}">
                                                <i class="fa fa-filter text-primary"></i>
                                                <span>তারিখ ও ভাউচার ফিল্টার</span>
                                            </a>
                                        </li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.distribution.comparison_report' ? 'active' : '' }}">
                                            <a href="{{ route('admin.distribution.comparison_report') }}">
                                                <i class="fa fa-line-chart text-success"></i>
                                                <span>পয়েন্ট পারফরম্যান্স তুলনা</span>
                                            </a>
                                        </li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.distribution.daily_closings' ? 'active' : '' }}">
                                            <a href="{{ route('admin.distribution.daily_closings') }}">
                                                <i class="fa fa-lock text-danger"></i>
                                                <span>দৈনিক ক্লোজিং অডিট অনুমোদন</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif

                            <!-- ========================================================================= -->
                            <!-- 3. Branch Staff, Attendance & Payroll (HRM System) -->
                            <!-- ========================================================================= -->
                            @if(Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin' || Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('admin') || Auth::user()->hasPermission('hrm_management'))
                                <li class="header text-uppercase text-danger fw-bold" style="letter-spacing: 0.5px;">
                                    <i class="fa fa-users text-danger me-1"></i> ব্রাঞ্চ কর্মী ও পে-রোল (HRM)
                                </li>
                                <li class="treeview {{ str_contains(optional(Request::route())->getName() ?? '', 'admin.hrm') ? 'active menu-open' : '' }}">
                                    <a href="#">
                                        <i class="ti-user text-danger"></i>
                                        <span>ব্রাঞ্চ স্টাফ ও বেতন (HRM)</span>
                                        <span class="pull-right-container">
                                            <i class="fa fa-angle-right pull-right"></i>
                                        </span>
                                    </a>
                                    <ul class="treeview-menu {{ str_contains(optional(Request::route())->getName() ?? '', 'admin.hrm') ? 'd-block' : '' }}">
                                        <li class="{{ optional(Request::route())->getName() == 'admin.hrm.dashboard' ? 'active' : '' }}"><a href="{{ route('admin.hrm.dashboard') }}"><i class="ti-dashboard text-success"></i> <span>HRM ড্যাশবোর্ড</span></a></li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.hrm.employees.index' ? 'active' : '' }}"><a href="{{ route('admin.hrm.employees.index') }}"><i class="ti-id-badge text-info"></i> <span>ব্রাঞ্চ স্টাফ ও কর্মী তালিকা</span></a></li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.hrm.attendance.index' ? 'active' : '' }}"><a href="{{ route('admin.hrm.attendance.index') }}"><i class="ti-alarm-clock text-danger"></i> <span>দৈনিক কর্মী উপস্থিতি</span></a></li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.hrm.payroll.index' ? 'active' : '' }}"><a href="{{ route('admin.hrm.payroll.index') }}"><i class="ti-money text-success"></i> <span>মাসিক পে-রোল & বেতন</span></a></li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.hrm.loans.index' ? 'active' : '' }}"><a href="{{ route('admin.hrm.loans.index') }}"><i class="ti-hand-point-right text-warning"></i> <span>বেতন অগ্রিম (Advances)</span></a></li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.hrm.leave.index' ? 'active' : '' }}"><a href="{{ route('admin.hrm.leave.index') }}"><i class="ti-calendar text-dark"></i> <span>ছুটি অনুমোদন (Leave)</span></a></li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.hrm.branches.index' ? 'active' : '' }}"><a href="{{ route('admin.hrm.branches.index') }}"><i class="ti-location-pin text-info"></i> <span>ব্রাঞ্চসমূহ ও লোকেশন</span></a></li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.hrm.departments.index' ? 'active' : '' }}"><a href="{{ route('admin.hrm.departments.index') }}"><i class="ti-layers text-primary"></i> <span>ডিপার্টমেন্টসমূহ</span></a></li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.hrm.designations.index' ? 'active' : '' }}"><a href="{{ route('admin.hrm.designations.index') }}"><i class="ti-medall text-warning"></i> <span>পদবীসমূহ</span></a></li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.hrm.shifts.index' ? 'active' : '' }}"><a href="{{ route('admin.hrm.shifts.index') }}"><i class="ti-time text-secondary"></i> <span>শিফট শিডিউল</span></a></li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.hrm.recruitment.index' ? 'active' : '' }}"><a href="{{ route('admin.hrm.recruitment.index') }}"><i class="ti-briefcase text-info"></i> <span>নিয়োগ ও রিক্রুটমেন্ট</span></a></li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.hrm.performance.index' ? 'active' : '' }}"><a href="{{ route('admin.hrm.performance.index') }}"><i class="ti-star text-warning"></i> <span>পারফরম্যান্স রিভিউ</span></a></li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.hrm.reports.index' ? 'active' : '' }}"><a href="{{ route('admin.hrm.reports.index') }}"><i class="ti-bar-chart text-primary"></i> <span>এইচআর রিপোর্টস</span></a></li>
                                    </ul>
                                </li>
                            @endif

                            <!-- ========================================================================= -->
                            <!-- 4. E-commerce Management (সকল প্রোডাক্ট, ক্যাটাগরি ও অনলাইন সেলস) -->
                            <!-- ========================================================================= -->
                            @if (Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin' || Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('admin'))
                                <li class="header text-uppercase text-info fw-bold" style="letter-spacing: 0.5px;">
                                    <i class="fa fa-shopping-cart text-info me-1"></i> ই-কমার্স শপ ম্যানেজমেন্ট
                                </li>
                                <li class="treeview {{ Str::contains(optional(Request::route())->getName(), 'admin.ecommerce') ? 'menu-open active' : '' }}">
                                    <a href="#">
                                        <i class="ti-shopping-cart text-info"></i>
                                        <span>ই-কমার্স পণ্য ও ক্যাটালগ</span>
                                        <span class="pull-right-container">
                                            <span class="badge bg-warning rounded-pill font-size-10 px-2 py-0">Shop Pro</span>
                                            <i class="fa fa-angle-right pull-right ms-1"></i>
                                        </span>
                                    </a>
                                    <ul class="treeview-menu {{ Str::contains(optional(Request::route())->getName(), 'admin.ecommerce') ? 'd-block' : '' }}">
                                        <!-- 1. Dashboard -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.ecommerce.dashboard' ? 'active' : '' }}">
                                            <a href="{{ route('admin.ecommerce.dashboard') }}">
                                                <i class="ti-dashboard text-primary"></i> <span>ড্যাশবোর্ড (Dashboard)</span>
                                            </a>
                                        </li>

                                        <!-- 2. Products -->
                                        <li class="{{ in_array(optional(Request::route())->getName(), ['admin.ecommerce.products', 'admin.ecommerce.products.edit']) ? 'active' : '' }}">
                                            <a href="{{ route('admin.ecommerce.products') }}">
                                                <i class="ti-package text-success"></i> <span>সকল পণ্য (All Products)</span>
                                            </a>
                                        </li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.ecommerce.products.create' ? 'active' : '' }}">
                                            <a href="{{ route('admin.ecommerce.products.create') }}">
                                                <i class="ti-plus text-primary"></i> <span>নতুন পণ্য যুক্ত করুন (Add)</span>
                                            </a>
                                        </li>

                                        <!-- 3. Categories & Brands -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.ecommerce.categories' ? 'active' : '' }}">
                                            <a href="{{ route('admin.ecommerce.categories') }}">
                                                <i class="ti-layers-alt text-success"></i> <span>মেইন ক্যাটাগরি (Categories)</span>
                                            </a>
                                        </li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.ecommerce.subcategories' ? 'active' : '' }}">
                                            <a href="{{ route('admin.ecommerce.subcategories') }}">
                                                <i class="ti-split-v text-info"></i> <span>সাব-ক্যাটাগরি (Subcategories)</span>
                                            </a>
                                        </li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.ecommerce.brands' ? 'active' : '' }}">
                                            <a href="{{ route('admin.ecommerce.brands') }}">
                                                <i class="ti-bookmark-alt text-warning"></i> <span>ব্র্যান্ডসমূহ (Brands)</span>
                                            </a>
                                        </li>

                                        <!-- 4. Orders -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.ecommerce.orders' ? 'active' : '' }}">
                                            <a href="{{ route('admin.ecommerce.orders') }}">
                                                <i class="ti-receipt text-danger"></i> <span>অনলাইন অর্ডারসমূহ (Orders)</span>
                                            </a>
                                        </li>

                                        <!-- 5. Sliders & Banners -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.ecommerce.sliders' ? 'active' : '' }}">
                                            <a href="{{ route('admin.ecommerce.sliders') }}">
                                                <i class="ti-image text-info"></i> <span>হোমপেজ স্লাইডার (Sliders)</span>
                                            </a>
                                        </li>
                                        <li class="{{ optional(Request::route())->getName() == 'admin.ecommerce.banners' ? 'active' : '' }}">
                                            <a href="{{ route('admin.ecommerce.banners') }}">
                                                <i class="ti-layout-slider text-primary"></i> <span>অফার ব্যানার (Banners)</span>
                                            </a>
                                        </li>

                                        <!-- 6. Coupons -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.ecommerce.coupons' ? 'active' : '' }}">
                                            <a href="{{ route('admin.ecommerce.coupons') }}">
                                                <i class="ti-gift text-danger"></i> <span>কুপন ও ডিসকাউন্ট (Coupons)</span>
                                            </a>
                                        </li>

                                        <!-- 7. Reports -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.ecommerce.reports' ? 'active' : '' }}">
                                            <a href="{{ route('admin.ecommerce.reports') }}">
                                                <i class="ti-bar-chart text-info"></i> <span>বিক্রয় ও অর্ডার রিপোর্ট (Reports)</span>
                                            </a>
                                        </li>

                                        <!-- 8. Settings & Preview -->
                                        <li class="{{ optional(Request::route())->getName() == 'admin.ecommerce.settings' ? 'active' : '' }}">
                                            <a href="{{ route('admin.ecommerce.settings') }}">
                                                <i class="ti-settings text-muted"></i> <span>ই-কমার্স সেটিংস (Settings)</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('products.index') }}" target="_blank">
                                                <i class="ti-new-window text-danger"></i> <span>সুপারশপ প্রিভিউ (Live)</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif

                        <!-- ================================= RBAC Management menu (Super Admin & Admin) -->
                        @if (Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('admin'))
                            <li class="header text-uppercase font-size-11 fw-bold text-muted px-3 pt-3">RBAC & Access Control</li>
                            <li class="treeview {{ Str::contains(optional(Request::route())->getName(), 'admin.rbac') ? 'menu-open active' : '' }}">
                                <a href="#">
                                    <i class="ti-shield text-primary"></i>
                                    <span>Role & Permission</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="{{ optional(Request::route())->getName() == 'admin.rbac.users' ? 'active' : '' }}">
                                        <a href="{{ route('admin.rbac.users') }}"><i class="ti-user text-primary"></i> ব্যবহারকারী তালিকা (Users)</a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.rbac.roles' ? 'active' : '' }}">
                                        <a href="{{ route('admin.rbac.roles') }}"><i class="ti-key text-info"></i> রোল ও পারমিশন (Roles)</a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.rbac.permissions' ? 'active' : '' }}">
                                        <a href="{{ route('admin.rbac.permissions') }}"><i class="ti-layout-grid2 text-success"></i> পারমিশন ম্যাট্রিক্স</a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.rbac.security_hub' ? 'active' : '' }}">
                                        <a href="{{ route('admin.rbac.security_hub') }}"><i class="ti-alert text-danger"></i> সিকিউরিটি ও অ্যালার্ট</a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.rbac.audit_logs' ? 'active' : '' }}">
                                        <a href="{{ route('admin.rbac.audit_logs') }}"><i class="ti-time text-warning"></i> অডিট ট্রেইল (Audit Logs)</a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        <!-- ================================= Real Estate & Property Management (LandShare ERP) -->
                        @if (Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('admin'))
                            <li class="header text-uppercase font-size-11 fw-bold text-muted px-3 pt-3">রিয়েল এস্টেট ও প্রপার্টি (LandShare ERP)</li>
                            <li class="treeview {{ Str::contains(optional(Request::route())->getName(), 'admin.properties') || Str::contains(optional(Request::route())->getName(), 'admin.projects') || Str::contains(optional(Request::route())->getName(), 'admin.landshare') ? 'menu-open active' : '' }}">
                                <a href="#">
                                    <i class="ti-home text-success"></i>
                                    <span>প্রপার্টি ও জমি ব্যবস্থাপনা</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="{{ optional(Request::route())->getName() == 'admin.landshare.hub' ? 'active' : '' }}">
                                        <a href="{{ route('admin.landshare.hub') }}"><i class="ti-dashboard text-danger"></i> রিয়েল এস্টেট কন্ট্রোল হাব</a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.properties.index' ? 'active' : '' }}">
                                        <a href="{{ route('admin.properties.index') }}"><i class="ti-layers-alt text-success"></i> জমি ও প্রপার্টি ক্যাটালগ</a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.properties.units' ? 'active' : '' }}">
                                        <a href="{{ route('admin.properties.units') }}"><i class="ti-layout-grid2 text-primary"></i> ফ্ল্যাট ও একক ইউনিট</a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.projects.index' ? 'active' : '' }}">
                                        <a href="{{ route('admin.projects.index') }}"><i class="ti-folder text-warning"></i> প্রকল্প সমূহ (Projects)</a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.investments.offers' ? 'active' : '' }}">
                                        <a href="{{ route('admin.investments.offers') }}"><i class="ti-stats-up text-info"></i> বিনিয়োগ অফারসমূহ</a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.investors.index' ? 'active' : '' }}">
                                        <a href="{{ route('admin.investors.index') }}"><i class="ti-user text-primary"></i> বিনিয়োগকারী ও KYC</a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.sales.bookings' ? 'active' : '' }}">
                                        <a href="{{ route('admin.sales.bookings') }}"><i class="ti-shopping-cart text-danger"></i> বিক্রি, বুকিং ও কিস্তি</a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.profit.reconciliation' ? 'active' : '' }}">
                                        <a href="{{ route('admin.profit.reconciliation') }}"><i class="ti-money text-success"></i> মুনাফা বণ্টন ইঞ্জিন</a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.property.accounting' ? 'active' : '' }}">
                                        <a href="{{ route('admin.property.accounting') }}"><i class="ti-receipt text-warning"></i> ডাবল-এন্ট্রি হিসাব</a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.property.payments' ? 'active' : '' }}">
                                        <a href="{{ route('admin.property.payments') }}"><i class="ti-credit-card text-info"></i> পেমেন্ট ভেরিফিকেশন</a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.rentals.index' ? 'active' : '' }}">
                                        <a href="{{ route('admin.rentals.index') }}"><i class="ti-id-badge text-primary"></i> ভাড়া ও ভাড়াটিয়া লিজ</a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.land_owners.index' ? 'active' : '' }}">
                                        <a href="{{ route('admin.land_owners.index') }}"><i class="ti-hand-point-right text-warning"></i> জমি মালিক পার্টনারশিপ</a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.brokers.index' ? 'active' : '' }}">
                                        <a href="{{ route('admin.brokers.index') }}"><i class="ti-medall text-success"></i> ব্রোকার ও কমিশন নেটওয়ার্ক</a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.legal.documents' ? 'active' : '' }}">
                                        <a href="{{ route('admin.legal.documents') }}"><i class="ti-files text-secondary"></i> দলিল ও পরচা ভল্ট</a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        <!-- ================================= Manager Dedicated Menu -->
                        @if (Auth::user()->hasRole('manager'))
                            <li class="header text-uppercase font-size-11 fw-bold text-muted px-3 pt-3">ব্রাঞ্চ ম্যানেজার মেনু</li>
                            <li class="{{ optional(Request::route())->getName() == 'manager.dashboard' ? 'menu-open active' : '' }}">
                                <a href="{{ route('manager.dashboard') }}">
                                    <i class="ti-dashboard text-primary"></i>
                                    <span>ব্রাঞ্চ ড্যাশবোর্ড ও রিপোর্ট</span>
                                </a>
                            </li>
                            <li class="{{ optional(Request::route())->getName() == 'admin.supershop.pos' ? 'active' : '' }}">
                                <a href="{{ route('admin.supershop.pos') }}">
                                    <i class="ti-shopping-cart-full text-success"></i>
                                    <span>POS বিলিং কাউন্টার</span>
                                </a>
                            </li>
                            <li class="{{ optional(Request::route())->getName() == 'admin.supershop.inventory' ? 'active' : '' }}">
                                <a href="{{ route('admin.supershop.inventory') }}">
                                    <i class="ti-archive text-info"></i>
                                    <span>ব্রাঞ্চ স্টক ও পণ্য তালিকা</span>
                                </a>
                            </li>
                            <li class="{{ optional(Request::route())->getName() == 'admin.supershop.sales' ? 'active' : '' }}">
                                <a href="{{ route('admin.supershop.sales') }}">
                                    <i class="ti-receipt text-warning"></i>
                                    <span>বিক্রয় চালান ও রসিদ</span>
                                </a>
                            </li>
                        @endif

                        <!-- ================================= Staff Dedicated Menu -->
                        @if (Auth::user()->hasRole('staff'))
                            <li class="header text-uppercase font-size-11 fw-bold text-muted px-3 pt-3">স্টাফ মেনু</li>
                            <li class="{{ optional(Request::route())->getName() == 'staff.dashboard' ? 'menu-open active' : '' }}">
                                <a href="{{ route('staff.dashboard') }}">
                                    <i class="ti-tasks text-info"></i>
                                    <span>অপারেশনাল কিউ</span>
                                </a>
                            </li>
                        @endif

                        <!-- ================================= Support Dedicated Menu -->
                        @if (Auth::user()->hasRole('support_agent') || Auth::user()->hasRole('support_lead'))
                            <li class="header text-uppercase font-size-11 fw-bold text-muted px-3 pt-3">সাপোর্ট মেনু</li>
                            <li class="{{ optional(Request::route())->getName() == 'support.dashboard' ? 'menu-open active' : '' }}">
                                <a href="{{ route('support.dashboard') }}">
                                    <i class="ti-headphone-alt text-warning"></i>
                                    <span>সাপোর্ট টিকেট ও ভেরিফিকেশন</span>
                                </a>
                            </li>
                            <li class="{{ optional(Request::route())->getName() == 'admin.chat.index' ? 'active' : '' }}">
                                <a href="{{ route('admin.chat.index') }}">
                                    <i class="ti-comments text-success"></i>
                                    <span>লাইভ চ্যাট হাব</span>
                                </a>
                            </li>
                        @endif

                        <!-- ================================= Security Dedicated Menu -->
                        @if (Auth::user()->hasRole('security_analyst'))
                            <li class="header text-uppercase font-size-11 fw-bold text-muted px-3 pt-3">সিকিউরিটি মেনু</li>
                            <li class="{{ optional(Request::route())->getName() == 'security.dashboard' ? 'menu-open active' : '' }}">
                                <a href="{{ route('security.dashboard') }}">
                                    <i class="ti-shield text-danger"></i>
                                    <span>সাইবার অ্যালার্ট ও ফ্রড চেক</span>
                                </a>
                            </li>
                        @endif

                        <!-- ================================= Auditor Dedicated Menu -->
                        @if (Auth::user()->hasRole('auditor'))
                            <li class="header text-uppercase font-size-11 fw-bold text-muted px-3 pt-3">অডিট মেনু</li>
                            <li class="{{ optional(Request::route())->getName() == 'audit.dashboard' ? 'menu-open active' : '' }}">
                                <a href="{{ route('audit.dashboard') }}">
                                    <i class="ti-eye text-success"></i>
                                    <span>সিস্টেম অডিট ট্রেইল</span>
                                </a>
                            </li>
                        @endif

                        <!-- ================================= Employee Dedicated Menu -->
                        @if (Auth::user()->hasRole('employee'))
                            <li class="header text-uppercase font-size-11 fw-bold text-muted px-3 pt-3">এমপ্লয়ী মেনু</li>
                            <li class="{{ optional(Request::route())->getName() == 'employee.dashboard' ? 'menu-open active' : '' }}">
                                <a href="{{ route('employee.dashboard') }}">
                                    <i class="ti-layout-grid2 text-primary"></i>
                                    <span>আমার ড্যাশবোর্ড</span>
                                </a>
                            </li>
                            @if (Auth::user()->hasPermission('packages.view') || Auth::user()->hasPermission('packages.upload_image'))
                            <li class="{{ optional(Request::route())->getName() == 'employee.packages' ? 'menu-open active' : '' }}">
                                <a href="{{ route('employee.packages') }}">
                                    <i class="ti-package text-success"></i>
                                    <span>আওয়ার প্যাকেজসমূহ</span>
                                </a>
                            </li>
                            @endif
                            <li class="{{ optional(Request::route())->getName() == 'employee.stock_ledger.index' ? 'menu-open active' : '' }}">
                                <a href="{{ route('employee.stock_ledger.index') }}">
                                    <i class="ti-archive text-warning"></i>
                                    <span>লাইভ স্টক লেজার</span>
                                </a>
                            </li>
                        @endif

                        <!-- ================================= Admin sidebar menu-->
                        @if (Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('admin'))

                            @if(Auth::user()->hasPermission('card_numbers'))
                            <li class="{{ (optional(Request::route())->getName() == 'admin.card_numbers.index') ? 'menu-open' : '' }}">
                                <a href="{{ route('admin.card_numbers.index') }}">
                                    <span>Number Generator</span>
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('packages.view') || Auth::user()->hasPermission('our_packages'))
                            <li class="{{ (Str::startsWith(optional(Request::route())->getName(), 'admin.our_packages')) ? 'active menu-open' : '' }}">
                                <a href="{{ route('admin.our_packages.index') }}">
                                    <i class="ti-package text-success"></i>
                                    <span>Our Packages</span>
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('packages.view') || Auth::user()->hasPermission('our_packages') || Auth::user()->role_id == 1)
                            <li class="{{ (Str::startsWith(optional(Request::route())->getName(), 'admin.card_benefits')) ? 'active menu-open' : '' }}">
                                <a href="{{ route('admin.card_benefits.index') }}">
                                    <i class="ti-id-badge text-warning"></i>
                                    <span>কার্ড সুবিধাসমূহ</span>
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('settings') || Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('admin'))
                            <li class="treeview {{ (Str::startsWith(optional(Request::route())->getName(), 'admin.appearance')) ? 'active menu-open' : '' }}">
                                <a href="javascript:void(0);" title="Appearance & Customization Studio">
                                    <i class="fa fa-paint-brush text-success"></i>
                                    <span class="fw-bold text-success">ডিজাইন ও টেমপ্লেট স্টুডিও</span>
                                    <span class="pull-right-container"><i class="fa fa-angle-right pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="{{ optional(Request::route())->getName() == 'admin.appearance.index' ? 'active' : '' }}">
                                        <a href="{{ route('admin.appearance.index') }}"><i class="fa fa-th-large text-warning"></i> ১০টি টেমপ্লেট ও মেনু বিল্ডার</a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.appearance.components' ? 'active' : '' }}">
                                        <a href="{{ route('admin.appearance.components') }}"><i class="fa fa-sliders text-success"></i> প্রোডাক্ট ও কম্পোনেন্ট কাস্টমাইজার</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="{{ (optional(Request::route())->getName() == 'setting.index') ? 'active menu-open' : '' }}">
                                <a href="{{ route('setting.index') }}" title="ফন্ট সেটিংস">
                                    <i class="fa fa-font text-warning"></i>
                                    <span>ফন্ট সেটিংস (Fonts)</span>
                                </a>
                            </li>

                            <!-- Business Applications Manager -->
                            <li class="treeview {{ Str::startsWith(optional(Request::route())->getName(), 'admin.business_applications') ? 'active menu-open' : '' }}">
                                <a href="#">
                                    <i class="fa fa-briefcase text-success"></i>
                                    <span>বিজনেস আবেদনসমূহ</span>
                                    <span class="pull-right-container">
                                        @php
                                            $pendingBizCount = \App\Models\BusinessApplication::where('status', 'pending')->count();
                                        @endphp
                                        @if($pendingBizCount > 0)
                                            <span class="label label-warning pull-right">{{ $pendingBizCount }}</span>
                                        @else
                                            <i class="fa fa-angle-right pull-right"></i>
                                        @endif
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ Str::startsWith(optional(Request::route())->getName(), 'admin.business_applications') ? 'd-block' : '' }}">
                                    <li class="{{ optional(Request::route())->getName() == 'admin.business_applications.index' && request('type', 'all') == 'all' ? 'active' : '' }}">
                                        <a href="{{ route('admin.business_applications.index') }}"><i class="fa fa-list text-primary"></i> সব আবেদন</a>
                                    </li>
                                    <li class="{{ request('type') == 'investor' ? 'active' : '' }}">
                                        <a href="{{ route('admin.business_applications.index', ['type' => 'investor']) }}"><i class="fa fa-line-chart text-primary"></i> ইনভেস্টর আবেদন</a>
                                    </li>
                                    <li class="{{ request('type') == 'wholesale' ? 'active' : '' }}">
                                        <a href="{{ route('admin.business_applications.index', ['type' => 'wholesale']) }}"><i class="fa fa-shopping-cart text-warning"></i> হোলসেল আবেদন</a>
                                    </li>
                                    <li class="{{ request('type') == 'franchise' ? 'active' : '' }}">
                                        <a href="{{ route('admin.business_applications.index', ['type' => 'franchise']) }}"><i class="fa fa-building text-info"></i> ফ্র্যাঞ্চাইজি আবেদন</a>
                                    </li>
                                    <li class="{{ request('type') == 'umrah_haj' ? 'active' : '' }}">
                                        <a href="{{ route('admin.business_applications.index', ['type' => 'umrah_haj']) }}"><i class="fa fa-moon-o text-success"></i> উমরাহ/হজ নিবন্ধন</a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Outlets & Branches Manager -->
                            <li class="{{ (optional(Request::route())->getName() == 'admin.outlets.index') ? 'active menu-open' : '' }}">
                                <a href="{{ route('admin.outlets.index') }}" title="আউটলেট ও ব্রাঞ্চসমূহ">
                                    <i class="fa fa-map-marker text-danger"></i>
                                    <span>আউটলেট ও ব্রাঞ্চসমূহ</span>
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('contact_messages'))
                            <li class="{{ (optional(Request::route())->getName() == 'admin.contact_messages.index') ? 'active menu-open' : '' }}">
                                <a href="{{ route('admin.contact_messages.index') }}">
                                    <i class="ti-email text-info"></i>
                                    <span>কন্টাক্ট বার্তা</span>
                                    @php
                                        $sidebarUnreadCount = \App\Models\ContactMessage::where('status', 'unread')->count();
                                    @endphp
                                    @if($sidebarUnreadCount > 0)
                                        <span class="pull-right-container">
                                            <span class="label label-danger pull-right">{{ $sidebarUnreadCount }}</span>
                                        </span>
                                    @endif
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('chat.view') || Auth::user()->hasPermission('live_chat'))
                            <li class="header">লাইভ চ্যাট ম্যানেজমেন্ট</li>
                            <li class="treeview {{ (optional(Request::route())->getName() == 'admin.chat.index') ? 'active menu-open' : '' }}">
                                <a href="#">
                                    <i class="fa fa-comments text-warning"></i>
                                    <span>লাইভ চ্যাট সাপোর্ট</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'admin.chat.index') ? 'd-block' : '' }}">
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.chat.index' && request('role') == 'user') ? 'active' : '' }}">
                                        <a href="{{ route('admin.chat.index', ['role' => 'user']) }}">
                                            <i class="ti-user text-primary"></i>
                                            <span>ইউজার চ্যাট</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.chat.index' && request('role') == 'employee') ? 'active' : '' }}">
                                        <a href="{{ route('admin.chat.index', ['role' => 'employee']) }}">
                                            <i class="ti-id-badge text-info"></i>
                                            <span>এমপ্লয়ী চ্যাট</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.chat.index' && request('role') == 'supplier') ? 'active' : '' }}">
                                        <a href="{{ route('admin.chat.index', ['role' => 'supplier']) }}">
                                            <i class="ti-truck text-success"></i>
                                            <span>সাপ্লায়ার চ্যাট</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.chat.index' && request('role') == 'agent') ? 'active' : '' }}">
                                        <a href="{{ route('admin.chat.index', ['role' => 'agent']) }}">
                                            <i class="ti-headphone-alt text-warning"></i>
                                            <span>এজেন্ট চ্যাট</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('stock_management'))
                            <li class="header">Stock Managment</li>
                            <li class="treeview {{ (optional(Request::route())->getName() == 'admin.stock.index') || (optional(Request::route())->getName() == 'admin.stock.allStock') || (optional(Request::route())->getName() == 'admin.stock_preset.index') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <span>Stock</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'admin.stock.index') || (optional(Request::route())->getName() == 'admin.stock.allStock') || (optional(Request::route())->getName() == 'admin.stock_preset.index') ? 'd-block' : '' }}" >
                                    <li class="">
                                        <a href="{{ route('admin.stock.index') }}">
                                            <i class="ti-more"></i>
                                            <span>Add Stock</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.stock.allStock') }}">
                                            <i class="ti-more"></i>
                                            <span>All Stock</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.stock_preset.index') }}">
                                            <i class="ti-more"></i>
                                            <span>Stock Presets</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('set_stock_price'))
                            <li class="{{ (optional(Request::route())->getName() == 'admin.stock.list')? 'menu-open' : '' }}">
                                <a href="{{ route('admin.stock.list') }}">
                                    <span>Set Stock Price</span>
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('stock_buy_sell'))
                            <li class="treeview {{ (optional(Request::route())->getName() == 'admin.stock.buyrequest.list') || (optional(Request::route())->getName() == 'admin.stock.sellrequest.list')|| (optional(Request::route())->getName() == 'admin.buy.stock.list')|| (optional(Request::route())->getName() == 'admin.sell.stock.list') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <span>Stock Buy/Sell</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'admin.stock.buyrequest.list') || (optional(Request::route())->getName() == 'admin.stock.sellrequest.list')|| (optional(Request::route())->getName() == 'admin.buy.stock.list')|| (optional(Request::route())->getName() == 'admin.sell.stock.list') ? 'd-block' : '' }}">
                                    <li class="">
                                        <a href="{{ route('admin.stock.buyrequest.list') }}">
                                            <i class="ti-more"></i>
                                            <span>Request for Buy</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.stock.sellrequest.list') }}">
                                            <i class="ti-more"></i>
                                            <span>Request for Sell</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.buy.stock.list') }}">
                                            <i class="ti-more"></i>
                                            <span>Buy List</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.sell.stock.list') }}">
                                            <i class="ti-more"></i>
                                            <span>Sell List</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('user_financial'))
                            <li class="header">User Managment</li>
                            <li class="treeview {{ (optional(Request::route())->getName() == 'alluser') || (optional(Request::route())->getName() == 'admin.deposit.index') || (optional(Request::route())->getName() == 'admin.reports.index') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <span>User & Financial</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'alluser') || (optional(Request::route())->getName() == 'admin.deposit.index') || (optional(Request::route())->getName() == 'admin.reports.index') ? 'd-block' : '' }}">
                                    <li class="">
                                        <a href="{{ route('alluser') }}">
                                            <i class="ti-more"></i>
                                            <span>All Customer List</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.deposit.index') }}">
                                            <i class="ti-more"></i>
                                            <span>Deposit Requests</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.reports.index') }}">
                                            <i class="ti-more"></i>
                                            <span>Reports & Analytics</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('withdraw'))
                            <li class="treeview {{ (optional(Request::route())->getName() == 'admin.all.withdraw.request') || (optional(Request::route())->getName() == 'admin.all.withdraw') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <span>Withdraw</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'admin.all.withdraw.request') || (optional(Request::route())->getName() == 'admin.all.withdraw') ? 'd-block' : '' }}">
                                    <li class="">
                                        <a href="{{ route('admin.all.withdraw.request') }}">
                                            <i class="ti-more"></i>
                                            <span>Withdraw Request</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.all.withdraw') }}">
                                            <i class="ti-more"></i>
                                            <span>All Withdraw</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            

                            @if(Auth::user()->hasPermission('employee_agent'))
                            <li class="header">Employee Managment</li>
                            <li class="treeview {{ (optional(Request::route())->getName() == 'admin.employee.index') || (optional(Request::route())->getName() == 'admin.agent_ledger.index') || (optional(Request::route())->getName() == 'admin.agent_ledger.show') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <span>Employee / Agent</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'admin.employee.index') || (optional(Request::route())->getName() == 'admin.agent_ledger.index') || (optional(Request::route())->getName() == 'admin.agent_ledger.show') ? 'd-block' : '' }}">
                                    <li class="">
                                        <a href="{{ route('admin.employee.index') }}">
                                            <i class="ti-more"></i>
                                            <span>Employee List</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.agent_ledger.index') }}">
                                            <i class="ti-more"></i>
                                            <span>বিপণন এজেন্ট লাইভ হিসাব</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('settings'))
                            <li class="header">Settings Managment</li>
                            <li class="treeview {{ (optional(Request::route())->getName() == 'setting.index')||(optional(Request::route())->getName() == 'setting.slider')||(optional(Request::route())->getName() == 'admin.our_packages.index')||(optional(Request::route())->getName() == 'setting.offer_banner')||(optional(Request::route())->getName() == 'profile.index')||(optional(Request::route())->getName() == 'admin.payment.index')||Str::startsWith(optional(Request::route())->getName(), 'admin.our_videos') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <span>Settings</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'setting.index')||(optional(Request::route())->getName() == 'setting.slider')||(optional(Request::route())->getName() == 'admin.our_packages.index')||(optional(Request::route())->getName() == 'setting.offer_banner')||(optional(Request::route())->getName() == 'profile.index')||(optional(Request::route())->getName() == 'admin.payment.index')||Str::startsWith(optional(Request::route())->getName(), 'admin.our_videos') ? 'd-block' : '' }}">
                                    <li class="">
                                        <a href="{{ route('setting.index') }}#theme_display_settings_card">
                                            <i class="ti-more"></i>
                                            <span class="text-success fw-bold"><i class="fa fa-paint-brush me-1"></i> Theme & Colors (হেডার ও বাটন)</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('setting.index') }}">
                                            <i class="ti-more"></i>
                                            <span>Site & Font Setting (ফন্ট ও সাইট সেটিংস)</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('setting.index') }}#about_us_full_text">
                                            <i class="ti-more"></i>
                                            <span>Pages (About, Privacy, Terms)</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('setting.index') }}#google_map_card">
                                            <i class="ti-more"></i>
                                            <span class="text-danger fw-bold"><i class="fa fa-map-marker me-1"></i> Google Map (গুগল ম্যাপ সেটিংস)</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'setting.notice') ? 'active' : '' }}">
                                        <a href="{{ route('setting.notice') }}">
                                            <i class="ti-more"></i>
                                            <span class="text-warning fw-bold"><i class="fa fa-bullhorn me-1"></i> Marquee Notice (নোটিশ)</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('setting.slider') }}">
                                            <i class="ti-more"></i>
                                            <span>Site Slider</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.our_packages.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.our_packages.index') }}">
                                            <i class="ti-more"></i>
                                            <span>Our Packages (আওয়ার প্যাকেজসমূহ)</span>
                                        </a>
                                    </li>
                                    <li class="{{ (Str::startsWith(optional(Request::route())->getName(), 'admin.card_benefits')) ? 'active' : '' }}">
                                        <a href="{{ route('admin.card_benefits.index') }}">
                                            <i class="ti-more"></i>
                                            <span>Card Benefits (কার্ড সুবিধাসমূহ)</span>
                                        </a>
                                    </li>
                                    <li class="{{ (Str::startsWith(optional(Request::route())->getName(), 'admin.our_videos')) ? 'active' : '' }}">
                                        <a href="{{ route('admin.our_videos.index') }}">
                                            <i class="ti-more"></i>
                                            <span class="text-danger fw-bold"><i class="fa fa-play-circle me-1"></i> Our Videos (আমাদের ভিডিওসমূহ)</span>
                                        </a>
                                    </li>
                                    <li class="{{ (Str::startsWith(optional(Request::route())->getName(), 'admin.gallery')) ? 'active' : '' }}">
                                        <a href="{{ route('admin.gallery.index') }}">
                                            <i class="ti-more"></i>
                                            <span class="text-success fw-bold"><i class="fa fa-picture-o me-1"></i> Photo Gallery (ফটো গ্যালারি)</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'setting.offer_banner') ? 'active' : '' }}">
                                        <a href="{{ route('setting.offer_banner') }}">
                                            <i class="ti-more"></i>
                                            <span>Offer Banner (অফার ব্যানার)</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.feature.index') }}">
                                            <i class="ti-more"></i>
                                            <span>Feature Boxes (কেন কৃষি পরিবার)</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('profile.index') }}">
                                            <i class="ti-more"></i>
                                            <span>My Profile</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="{{ (optional(Request::route())->getName() == 'admin.payment.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.payment.index') }}">
                                    <i class="ti-more"></i>
                                    <span>পেমেন্ট মেথড</span>
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('supplier_management'))
                            <li class="header">Supplier Management</li>
                            <li class="treeview {{ (optional(Request::route())->getName() == 'admin.suppliers.index') || (optional(Request::route())->getName() == 'admin.suppliers.create') || (optional(Request::route())->getName() == 'admin.suppliers.pending_supplies') || (optional(Request::route())->getName() == 'admin.suppliers.reports') || (optional(Request::route())->getName() == 'admin.suppliers.show') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <span>সাপ্লায়ার ব্যবস্থাপনা</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'admin.suppliers.index') || (optional(Request::route())->getName() == 'admin.suppliers.create') || (optional(Request::route())->getName() == 'admin.suppliers.pending_supplies') || (optional(Request::route())->getName() == 'admin.suppliers.reports') || (optional(Request::route())->getName() == 'admin.suppliers.show') ? 'd-block' : '' }}">
                                    <li class="">
                                        <a href="{{ route('admin.suppliers.index') }}">
                                            <i class="ti-more"></i>
                                            <span>সকল সাপ্লায়ার</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.suppliers.create') }}">
                                            <i class="ti-more"></i>
                                            <span>নতুন সাপ্লায়ার যোগ করুন</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.suppliers.pending_supplies') }}">
                                            <i class="ti-more"></i>
                                            <span>পণ্য সরবরাহ ভেরিফিকেশন</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.suppliers.reports') }}">
                                            <i class="ti-more"></i>
                                            <span>সাপ্লায়ার রিপোর্টস</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            <!-- Monthly Market Request & Area Distribution System (SRD Modules 02, 03, 04) -->
                            <li class="header">মাসিক বাজার ও ডিস্ট্রিবিউশন</li>
                            <li class="treeview {{ str_contains(optional(Request::route())->getName() ?? '', 'admin.monthly_market.') || str_contains(optional(Request::route())->getName() ?? '', 'admin.agent_points.') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <i class="fa fa-shopping-basket text-success"></i>
                                    <span>বাজার চাহিদা ও ডিস্ট্রিবিউশন</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ str_contains(optional(Request::route())->getName() ?? '', 'admin.monthly_market.') || str_contains(optional(Request::route())->getName() ?? '', 'admin.agent_points.') ? 'd-block' : '' }}">
                                    <li class="{{ optional(Request::route())->getName() == 'admin.monthly_market.requests' ? 'active' : '' }}">
                                        <a href="{{ route('admin.monthly_market.requests') }}">
                                            <i class="ti-more"></i>
                                            <span>বাজার চাহিদা রিকোয়েস্ট</span>
                                        </a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.monthly_market.demand_analysis' ? 'active' : '' }}">
                                        <a href="{{ route('admin.monthly_market.demand_analysis') }}">
                                            <i class="ti-more"></i>
                                            <span>ডিমান্ড অ্যানালাইসিস</span>
                                        </a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.monthly_market.allocations' ? 'active' : '' }}">
                                        <a href="{{ route('admin.monthly_market.allocations') }}">
                                            <i class="ti-more"></i>
                                            <span>এজেন্ট পয়েন্ট বরাদ্দ</span>
                                        </a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.monthly_market.dispatches' ? 'active' : '' }}">
                                        <a href="{{ route('admin.monthly_market.dispatches') }}">
                                            <i class="ti-more"></i>
                                            <span>সেন্ট্রাল ডিসপ্যাচ ও চালান</span>
                                        </a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.agent_points.index' ? 'active' : '' }}">
                                        <a href="{{ route('admin.agent_points.index') }}">
                                            <i class="ti-more"></i>
                                            <span>এজেন্ট পয়েন্ট ব্যবস্থাপনা</span>
                                        </a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.monthly_market.reports' ? 'active' : '' }}">
                                        <a href="{{ route('admin.monthly_market.reports') }}">
                                            <i class="ti-more"></i>
                                            <span>সার্বিক ডিস্ট্রিবিউশন রিপোর্ট</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        <!-- ================================= /Admin sidebar menu-->

                        <!-- ================================= User sidebar menu-->
                        @elseif (Auth::user()->role == "user")
                            <li class="{{ (optional(Request::route())->getName() == 'portal.dashboard' || optional(Request::route())->getName() == 'home' || optional(Request::route())->getName() == 'user.dashboard') ? 'active' : '' }}">
                                <a href="{{ route('portal.dashboard') }}">
                                    <i class="ti-dashboard text-success"></i>
                                    <span>ড্যাশবোর্ড (Dashboard)</span>
                                </a>
                            </li>

                            <li class="header text-uppercase text-success fw-bold" style="letter-spacing: 0.5px;">
                                <i class="fa fa-user-circle text-success me-1"></i> কাস্টমার সার্ভিস পোর্টাল
                            </li>

                            <!-- Digital Membership & 3D Cards -->
                            <li class="treeview {{ str_contains(optional(Request::route())->getName() ?? '', 'user.corporate.card') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <i class="fa fa-id-card me-2" style="color: #8b5cf6;"></i>
                                    <span>মেম্বারশিপ ও কার্ড</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ str_contains(optional(Request::route())->getName() ?? '', 'user.corporate.card') ? 'd-block' : '' }}">
                                    <li class="{{ (optional(Request::route())->getName() == 'user.corporate.card') ? 'active' : '' }}">
                                        <a href="{{ route('user.corporate.card') }}">
                                            <i class="ti-more"></i>
                                            <span>ডিজিটাল কার্ড (3D Card)</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Stock Management -->
                            <li class="treeview {{ (optional(Request::route())->getName() == 'stock.index')||(optional(Request::route())->getName() == 'userbuystocklist')||(optional(Request::route())->getName() == 'usersellstocklist') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <i class="fa fa-line-chart text-primary me-2"></i>
                                    <span>স্টক ম্যানেজমেন্ট (Stock)</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'stock.index')||(optional(Request::route())->getName() == 'userbuystocklist')||(optional(Request::route())->getName() == 'usersellstocklist') ? 'd-block' : '' }}">
                                    <li class="{{ (optional(Request::route())->getName() == 'stock.index') ? 'active' : '' }}">
                                        <a href="{{ route('stock.index') }}">
                                            <i class="ti-more"></i>
                                            <span>লাইভ স্টক মার্কেট</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'userbuystocklist') ? 'active' : '' }}">
                                        <a href="{{ route('userbuystocklist') }}">
                                            <i class="ti-more"></i>
                                            <span>বাই লিস্ট (Buy list)</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'usersellstocklist') ? 'active' : '' }}">
                                        <a href="{{ route('usersellstocklist') }}">
                                            <i class="ti-more"></i>
                                            <span>সেল লিস্ট (Sell List)</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Monthly Bazaar & Card Holder Market Request -->
                            <li class="treeview {{ (optional(Request::route())->getName() == 'user.monthly_bazaar.index') || (optional(Request::route())->getName() == 'user.monthly_bazaar.my_orders') || (optional(Request::route())->getName() == 'user.monthly_market.request') || (optional(Request::route())->getName() == 'user.monthly_market.my_requests') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <i class="fa fa-shopping-basket text-warning me-2"></i>
                                    <span>মাসিক বাজার ও চাহিদা</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'user.monthly_bazaar.index') || (optional(Request::route())->getName() == 'user.monthly_bazaar.my_orders') || (optional(Request::route())->getName() == 'user.monthly_market.request') || (optional(Request::route())->getName() == 'user.monthly_market.my_requests') ? 'd-block' : '' }}">
                                    <li class="{{ (optional(Request::route())->getName() == 'user.monthly_market.request') ? 'active' : '' }}">
                                        <a href="{{ route('user.monthly_market.request') }}">
                                            <i class="ti-more"></i>
                                            <span>বাজার চাহিদা সাবমিট করুন</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'user.monthly_market.my_requests') ? 'active' : '' }}">
                                        <a href="{{ route('user.monthly_market.my_requests') }}">
                                            <i class="ti-more"></i>
                                            <span>আমার চাহিদা ও টোকেন স্লিপ</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'user.monthly_bazaar.index') ? 'active' : '' }}">
                                        <a href="{{ route('user.monthly_bazaar.index') }}">
                                            <i class="ti-more"></i>
                                            <span>মাসিক বাজার প্যাকেজ</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'user.monthly_bazaar.my_orders') ? 'active' : '' }}">
                                        <a href="{{ route('user.monthly_bazaar.my_orders') }}">
                                            <i class="ti-more"></i>
                                            <span>আমার প্যাকেজ অর্ডারসমূহ</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Properties & Real Estate -->
                            <li class="treeview {{ str_contains(optional(Request::route())->getName() ?? '', 'user.properties.') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <i class="fa fa-map-signs text-success me-2"></i>
                                    <span>জমি ও প্রপার্টি (Properties)</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ str_contains(optional(Request::route())->getName() ?? '', 'user.properties.') ? 'd-block' : '' }}">
                                    <li class="">
                                        <a href="{{ route('properties.index') }}" target="_blank">
                                            <i class="ti-more"></i>
                                            <span>প্রপার্টি ক্যাটালগ (Browse)</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'user.properties.my_bookings') ? 'active' : '' }}">
                                        <a href="{{ route('user.properties.my_bookings') }}">
                                            <i class="ti-more"></i>
                                            <span>আমার আবেদন ও বুকিং</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'user.properties.investments') ? 'active' : '' }}">
                                        <a href="{{ route('user.properties.investments') }}">
                                            <i class="ti-more"></i>
                                            <span>আমার শেয়ার ও বিনিয়োগ</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Share, Investments & Corporate Services -->
                            <li class="treeview {{ str_contains(optional(Request::route())->getName() ?? '', 'user.corporate.') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <i class="fa fa-university text-info me-2"></i>
                                    <span>শেয়ার ও প্রাতিষ্ঠানিক সেবা</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ str_contains(optional(Request::route())->getName() ?? '', 'user.corporate.') ? 'd-block' : '' }}">
                                    <li class="{{ (optional(Request::route())->getName() == 'user.corporate.investments') ? 'active' : '' }}">
                                        <a href="{{ route('user.corporate.investments') }}">
                                            <i class="ti-more"></i>
                                            <span>আমার বিনিয়োগ ও লভ্যাংশ</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'user.corporate.shareholder') ? 'active' : '' }}">
                                        <a href="{{ route('user.corporate.shareholder') }}">
                                            <i class="ti-more"></i>
                                            <span>শেয়ারহোল্ডার প্রোফাইল</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'user.corporate.partnership') ? 'active' : '' }}">
                                        <a href="{{ route('user.corporate.partnership') }}">
                                            <i class="ti-more"></i>
                                            <span>পার্টনারশিপ প্রোফাইল</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'user.corporate.community') ? 'active' : '' }}">
                                        <a href="{{ route('user.corporate.community') }}">
                                            <i class="ti-more"></i>
                                            <span>মেম্বার কমিউনিটি ফোরাম</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Wallet & Transactions -->
                            <li class="{{ (optional(Request::route())->getName() == 'user.deposit.index') ? 'active' : '' }}">
                                <a href="{{ route('user.deposit.index') }}">
                                    <i class="fa fa-plus-circle text-success me-2"></i>
                                    <span>Deposit Money (ডিপোজিট)</span>
                                </a>
                            </li>

                            <li class="{{ (optional(Request::route())->getName() == 'user.wallet.ledger') ? 'active' : '' }}">
                                <a href="{{ route('user.wallet.ledger') }}">
                                    <i class="fa fa-file-text-o text-warning me-2"></i>
                                    <span>Wallet Ledger (ওয়ালেট খতিয়ান)</span>
                                </a>
                            </li>

                            <li class="{{ (optional(Request::route())->getName() == 'my.cart') ? 'active' : '' }}">
                                <a href="{{ route('my.cart') }}" class="d-flex align-items-center justify-content-between">
                                    <span>
                                        <i class="fa fa-shopping-cart text-danger me-2"></i>
                                        My Cart (শপিং ব্যাগ)
                                    </span>
                                    @php
                                        $sidebarCartCount = \App\Models\UserCart::where('user_id', Auth::id())->sum('quantity');
                                    @endphp
                                    <span class="badge bg-danger rounded-pill user-cart-badge {{ $sidebarCartCount > 0 ? '' : 'd-none' }}" style="font-size: 11px; padding: 3px 8px;">{{ $sidebarCartCount }}</span>
                                </a>
                            </li>

                            <li class="treeview {{ (optional(Request::route())->getName() == 'withdraw.index')||(optional(Request::route())->getName() == 'withdraw.form') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <i class="fa fa-money text-success me-2"></i>
                                    <span>Withdraw (উত্তোলন)</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'withdraw.index')||(optional(Request::route())->getName() == 'withdraw.form') ? 'd-block' : '' }}">
                                    <li>
                                        <a href="{{ route('withdraw.index') }}">
                                            <i class="ti-more"></i>
                                            <span>Withdraw History</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('withdraw.form') }}">
                                            <i class="ti-more"></i>
                                            <span>Send Request</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="{{ (optional(Request::route())->getName() == 'payment.index') ? 'active' : '' }}">
                                <a href="{{ route('payment.index') }}">
                                    <i class="fa fa-credit-card text-info me-2"></i>
                                    <span>পেমেন্ট মেথড</span>
                                </a>
                            </li>

                            <!-- Settings & Logout -->
                            <li class="treeview {{ (optional(Request::route())->getName() == 'profile.index') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <i class="fa fa-cog text-secondary me-2"></i>
                                    <span>Settings (সেটিংস)</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'profile.index') ? 'd-block' : '' }}">
                                    <li class="">
                                        <a href="{{ route('profile.index') }}">
                                            <i class="ti-more"></i>
                                            <span>My Profile</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('customer-logout-form').submit();" class="text-danger">
                                            <i class="ti-power-off text-danger"></i>
                                            <span class="text-danger">লগআউট (Logout)</span>
                                        </a>
                                        <form id="customer-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        <!-- ================================ /User sidebar menu -->
                        @elseif (Auth::user()->role == "supplier" || Auth::user()->hasRole('supplier') || Auth::user()->hasRole('seller'))

                        <!-- ================================ Supplier sidebar menu -->
                        <li class="{{ (optional(Request::route())->getName() == 'supplier.dashboard') ? 'menu-open active' : '' }}">
                            <a href="{{ route('supplier.dashboard') }}">
                                <i class="fa fa-dashboard text-success me-2"></i>
                                <span>সাপ্লায়ার ড্যাশবোর্ড</span>
                            </a>
                        </li>
                        <li class="{{ (optional(Request::route())->getName() == 'supplier.supplies.index') ? 'menu-open active' : '' }}">
                            <a href="{{ route('supplier.supplies.index') }}">
                                <i class="fa fa-cubes text-info me-2"></i>
                                <span>আমার সরবরাহকৃত পণ্য</span>
                            </a>
                        </li>
                        <li class="{{ (optional(Request::route())->getName() == 'supplier.supplies.create') ? 'menu-open active' : '' }}">
                            <a href="{{ route('supplier.supplies.create') }}">
                                <i class="fa fa-plus-circle text-warning me-2"></i>
                                <span>নতুন পণ্য এন্ট্রি/পোস্টিং</span>
                            </a>
                        </li>
                        <li class="{{ (optional(Request::route())->getName() == 'supplier.statement') ? 'menu-open active' : '' }}">
                            <a href="{{ route('supplier.statement') }}">
                                <i class="fa fa-file-text-o text-primary me-2"></i>
                                <span>অ্যাকাউন্ট স্টেটমেন্ট</span>
                            </a>
                        </li>
                        @elseif (Auth::user()->role == "agent" || Auth::user()->hasRole('agent'))

                        <!-- ================================ Agent Point Dedicated Sidebar Menu ================================ -->
                        <li class="header text-uppercase text-warning fw-bold px-3 pt-3 font-size-11" style="letter-spacing: 0.5px;">
                            <i class="fa fa-building-o text-warning me-1"></i> এজেন্ট পয়েন্ট হাব
                        </li>
                        <li class="{{ (optional(Request::route())->getName() == 'agent.dashboard') ? 'menu-open active' : '' }}">
                            <a href="{{ route('agent.dashboard') }}">
                                <i class="fa fa-dashboard text-warning me-2"></i>
                                <span>এজেন্ট ড্যাশবোর্ড</span>
                            </a>
                        </li>
                        <li class="{{ (optional(Request::route())->getName() == 'agent.stock.incoming') ? 'menu-open active' : '' }}">
                            <a href="{{ route('agent.stock.incoming') }}">
                                <i class="fa fa-truck text-info me-2"></i>
                                <span>আগত পণ্য রিসিভ (Incoming Stock)</span>
                            </a>
                        </li>
                        <li class="{{ (optional(Request::route())->getName() == 'agent.inventory') ? 'menu-open active' : '' }}">
                            <a href="{{ route('agent.inventory') }}">
                                <i class="fa fa-cubes text-success me-2"></i>
                                <span>পয়েন্ট স্টক ও ইনভেন্টরি</span>
                            </a>
                        </li>
                        <li class="{{ (optional(Request::route())->getName() == 'agent.stock.ledger') ? 'menu-open active' : '' }}">
                            <a href="{{ route('agent.stock.ledger') }}">
                                <i class="fa fa-list-alt text-primary me-2"></i>
                                <span>স্টক লেজার ও ট্রানজেকশন</span>
                            </a>
                        </li>
                        <li class="{{ (optional(Request::route())->getName() == 'agent.orders') ? 'menu-open active' : '' }}">
                            <a href="{{ route('agent.orders') }}">
                                <i class="fa fa-shopping-basket text-danger me-2"></i>
                                <span>কার্ডহোল্ডার অর্ডার ডেলিভারি</span>
                            </a>
                        </li>

                        <!-- Module 09: Agent Point Customer Distribution System -->
                        <li class="header text-uppercase text-success fw-bold py-2 px-3 font-size-11" style="letter-spacing: 0.5px;">
                            <i class="fa fa-shopping-bag text-success me-1"></i> গ্রাহক বাজার বিতরণ (Module 09)
                        </li>
                        <li class="{{ (optional(Request::route())->getName() == 'agent.distribution.entry') ? 'menu-open active' : '' }}">
                            <a href="{{ route('agent.distribution.entry') }}">
                                <i class="fa fa-qrcode text-warning me-2"></i>
                                <span>বাজার বিতরণ এন্ট্রি (QR/Card)</span>
                            </a>
                        </li>
                        <li class="{{ (optional(Request::route())->getName() == 'agent.distribution.daily_register') ? 'menu-open active' : '' }}">
                            <a href="{{ route('agent.distribution.daily_register') }}">
                                <i class="fa fa-calendar-check-o text-info me-2"></i>
                                <span>দৈনিক বিতরণ রেজিস্টার</span>
                            </a>
                        </li>
                        <li class="{{ (optional(Request::route())->getName() == 'agent.distribution.customers') ? 'menu-open active' : '' }}">
                            <a href="{{ route('agent.distribution.customers') }}">
                                <i class="fa fa-users text-primary me-2"></i>
                                <span>পয়েন্ট কার্ডহোল্ডার তালিকা</span>
                            </a>
                        </li>
                        <li class="{{ (optional(Request::route())->getName() == 'agent.distribution.product_report') ? 'menu-open active' : '' }}">
                            <a href="{{ route('agent.distribution.product_report') }}">
                                <i class="fa fa-bar-chart text-success me-2"></i>
                                <span>বিতরণকৃত পণ্য রিপোর্ট</span>
                            </a>
                        </li>
                        <li class="{{ (optional(Request::route())->getName() == 'agent.distribution.daily_closing') ? 'menu-open active' : '' }}">
                            <a href="{{ route('agent.distribution.daily_closing') }}">
                                <i class="fa fa-lock text-danger me-2"></i>
                                <span>দৈনিক ক্লোজিং দাখিল</span>
                            </a>
                        </li>
                        <li class="{{ (optional(Request::route())->getName() == 'profile.index') ? 'menu-open active' : '' }}">
                            <a href="{{ route('profile.index') }}">
                                <i class="fa fa-user me-2 text-secondary"></i>
                                <span>এজেন্ট প্রোফাইল</span>
                            </a>
                        </li>
                        @else

                        <!-- ================================ Employee sidebar menu -->
                        <li class="{{ (optional(Request::route())->getName() == 'employee.stock_ledger.index') ? 'menu-open' : '' }}">
                            <a href="{{ route('employee.stock_ledger.index') }}">
                                
                                <span>আমার লাইভ স্টক ও হিসাব</span>
                            </a>
                        </li>

                        <li class="treeview {{ (optional(Request::route())->getName() == 'my.referal') ? 'menu-open' : '' }}">
                            <a href="#">
                                
                                <span>Referal User</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'my.referal') ? 'd-block' : '' }}">
                                <li class="">
                                    <a href="{{ route('my.referal') }}">
                                        <i class="ti-more"></i>
                                        <span>My Referal</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="treeview {{ (optional(Request::route())->getName() == 'profile.index') || (optional(Request::route())->getName() == 'profile.business') ? 'menu-open' : '' }}">
                            <a href="#">
                                
                                <span>Settings</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'profile.index') || (optional(Request::route())->getName() == 'profile.business') ? 'd-block' : '' }}">

                                <li class="">
                                    <a href="{{ route('profile.index') }}">
                                        <i class="ti-more"></i>
                                        <span>My Profile</span>
                                    </a>
                                </li>

                                <li class="">
                                    <a href="{{ route('profile.business') }}">
                                        <i class="ti-more"></i>
                                        <span>Business Profile</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <!-- ================================= /Employee sidebar menu -->
                        @endif

                    </ul>

                        <div class="sidebar-widgets">
                            <div class="copyright text-start m-25">
                                <p class="text-center">
                                    <strong class="d-block">
                                        <a href="#">ekrishisheba</a>
                                    </strong>
                                    © {{ date('Y') }} All Rights Reserved
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </aside>

        <!-- Mobile Sidebar Backdrop Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        @yield('content')

        <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>

    </div>
    <!-- ./wrapper -->


    <!-- Page Content overlay -->

    <!-- Vendor JS -->
    <script src="{{ asset('assets/main/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/main/js/pages/chat-popup.js') }}"></script>
    <script src="{{ asset('assets/plugin/icons/feather-icons/feather.min.js') }}"></script>



    <script src="{{ asset('assets/plugin') }}/vendor_components/apexcharts-bundle/data.js"></script>
    <script src="{{ asset('assets/plugin') }}/vendor_components/apexcharts-bundle/dist/apexcharts.js"></script>

    <script src="{{ asset('assets/plugin') }}/vendor_components/jquery-steps-master/build/jquery.steps.js"></script>
    <script src="{{ asset('assets/plugin') }}/vendor_components/jquery-validation-1.17.0/dist/jquery.validate.min.js">
    </script>


    <!-- Crypto Tokenizer Admin App -->
    <script src="{{ asset('assets/main/js') }}/template.js"></script>
    <script src="{{ asset('assets/main/js') }}/pages/steps.js"></script>



    <!-- Toastr Notification CSS & JS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "4000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        @if(Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}";
            switch(type){
                case 'info':
                    toastr.info("{{ Session::get('message') }}");
                    break;
                case 'success':
                    toastr.success("{{ Session::get('message') }}");
                    break;
                case 'warning':
                    toastr.warning("{{ Session::get('message') }}");
                    break;
                case 'error':
                    toastr.error("{{ Session::get('message') }}");
                    break;
                default:
                    toastr.success("{{ Session::get('message') }}");
                    break;
            }
        @endif

        @if(Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @endif

        @if(Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
        @endif

        @if(Session::has('warning'))
            toastr.warning("{{ Session::get('warning') }}");
        @endif

        @if(Session::has('info'))
            toastr.info("{{ Session::get('info') }}");
        @endif

        @if(Session::has('status'))
            toastr.success("{{ Session::get('status') }}");
        @endif

        window.updateCartBadgeCount = function(count) {
            var countNum = parseInt(count) || 0;
            document.querySelectorAll('.user-cart-badge').forEach(function(badge) {
                badge.textContent = countNum;
                if (countNum > 0) {
                    badge.classList.remove('d-none');
                } else {
                    badge.classList.add('d-none');
                }
            });
        };
    </script>

    <script>
        const myDiv = document.getElementById('myDiv2');
         const myButton = document.getElementById('myButton2');

         if(myButton && myDiv){
             myButton.addEventListener('click', () => {
             const textToCopy = myDiv.textContent;
             navigator.clipboard.writeText(textToCopy)
                 .then(() => {
                     toastr.success('Link Copy Successfully');
                 })
                 .catch((err) => {
                     toastr.error('Failed to copy link');
                 });
             });
         }
     </script>


    <!-- WEDASH Theme Customizer Slide-out Drawer -->
    <div class="theme-drawer-overlay" id="themeOverlay"></div>
    <div class="theme-drawer" id="themeDrawer">
        <div class="theme-drawer-header">
            <div class="theme-drawer-title">
                <i class="fa fa-paint-brush text-primary me-2"></i> থিম কালার কাস্টমাইজ
            </div>
            <button type="button" id="closeThemeDrawer" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        <p class="text-muted" style="font-size: 13px; margin-bottom: 16px;">এডমিন প্যানেলের পছন্দসই কালার থিম নির্বাচন করুন:</p>
        <div class="theme-swatch-list">
            <div class="theme-swatch-item" data-theme-name="fuchsia">
                <div class="swatch-color-bubble" style="background: #d946ef;"></div>
                <div>
                    <strong class="d-block" style="font-size: 13.5px; color: #0f172a;">WEDASH Fuchsia (Default)</strong>
                    <small class="text-muted">মেজেন্টা পিঙ্ক স্টাইল</small>
                </div>
            </div>
            <div class="theme-swatch-item" data-theme-name="emerald">
                <div class="swatch-color-bubble" style="background: #10b981;"></div>
                <div>
                    <strong class="d-block" style="font-size: 13.5px; color: #0f172a;">iKrishi Emerald Green</strong>
                    <small class="text-muted">কৃষি পরিবার সিগনেচার</small>
                </div>
            </div>
            <div class="theme-swatch-item" data-theme-name="blue">
                <div class="swatch-color-bubble" style="background: #2563eb;"></div>
                <div>
                    <strong class="d-block" style="font-size: 13.5px; color: #0f172a;">Royal Blue</strong>
                    <small class="text-muted">কর্পোরেট ব্লু</small>
                </div>
            </div>
            <div class="theme-swatch-item" data-theme-name="purple">
                <div class="swatch-color-bubble" style="background: #8b5cf6;"></div>
                <div>
                    <strong class="d-block" style="font-size: 13.5px; color: #0f172a;">Deep Purple</strong>
                    <small class="text-muted">লাক্সারি পারপল</small>
                </div>
            </div>
            <div class="theme-swatch-item" data-theme-name="dark">
                <div class="swatch-color-bubble" style="background: #0f172a;"></div>
                <div>
                    <strong class="d-block" style="font-size: 13.5px; color: #0f172a;">Midnight Dark</strong>
                    <small class="text-muted">ডিপ ডার্ক নাইট মোড</small>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const savedTheme = localStorage.getItem('admin_theme') || 'fuchsia';
            setAdminTheme(savedTheme);

            const gearBtn = document.getElementById('openThemeSettings');
            const closeBtn = document.getElementById('closeThemeDrawer');
            const overlay = document.getElementById('themeOverlay');
            const drawer = document.getElementById('themeDrawer');

            function openDrawer() {
                if (drawer && overlay) {
                    drawer.classList.add('active');
                    overlay.classList.add('active');
                }
            }

            function closeDrawer() {
                if (drawer && overlay) {
                    drawer.classList.remove('active');
                    overlay.classList.remove('active');
                }
            }

            if (gearBtn) gearBtn.addEventListener('click', openDrawer);
            if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
            if (overlay) overlay.addEventListener('click', closeDrawer);

            document.querySelectorAll('.theme-swatch-item').forEach(item => {
                item.addEventListener('click', function () {
                    const theme = this.getAttribute('data-theme-name');
                    setAdminTheme(theme);
                });
            });

            function setAdminTheme(theme) {
                if (theme === 'fuchsia') {
                    document.body.removeAttribute('data-theme');
                } else {
                    document.body.setAttribute('data-theme', theme);
                }
                localStorage.setItem('admin_theme', theme);
                document.querySelectorAll('.theme-swatch-item').forEach(el => {
                    if (el.getAttribute('data-theme-name') === theme) {
                        el.classList.add('active');
                    } else {
                        el.classList.remove('active');
                    }
                });
            }
        });
    </script>

    <!-- Dynamic Sidebar Active Menu & Full Mobile Controller -->
    <script>
        (function ($) {
            'use strict';

            // Auto Highlight and Expand Active Menu Item
            function activateSidebarRoute() {
                var currentPath = window.location.pathname.replace(/\/+$/, '') || '/';
                var currentHref = window.location.href.split('#')[0].split('?')[0].replace(/\/+$/, '');
                var $sidebar = $('.sidebar-menu');
                if (!$sidebar.length) return;

                var bestMatch = null;
                var maxMatchLen = 0;

                // Loop through every link in sidebar
                $sidebar.find('a').each(function () {
                    var $link = $(this);
                    var rawHref = $link.attr('href');
                    if (!rawHref || rawHref === '#' || rawHref.startsWith('javascript:')) return;

                    var linkHref = (this.href || '').split('#')[0].split('?')[0].replace(/\/+$/, '');
                    var linkPath = (this.pathname || '').replace(/\/+$/, '') || '/';

                    // 1. Exact Match (Highest Priority)
                    if (currentHref === linkHref || currentPath === linkPath) {
                        bestMatch = $link;
                        maxMatchLen = 99999;
                        return false; // Exact match found, stop looking
                    }

                    // 2. Prefix Match (for sub-routes like edit, create, show)
                    // Ensure we don't accidentally match root "/" or "/admin" unless exact
                    if (linkPath !== '/' && linkPath !== '/admin' && currentPath.indexOf(linkPath) === 0) {
                        if (linkPath.length > maxMatchLen) {
                            maxMatchLen = linkPath.length;
                            bestMatch = $link;
                        }
                    }
                });

                // 3. Fallback to stored clicked href from sessionStorage
                if (!bestMatch) {
                    var storedHref = sessionStorage.getItem('ekrishi_active_sidebar_href');
                    if (storedHref) {
                        $sidebar.find('a').each(function () {
                            if (this.href && this.href.split('#')[0].split('?')[0].replace(/\/+$/, '') === storedHref) {
                                bestMatch = $(this);
                                return false;
                            }
                        });
                    }
                }

                if (bestMatch && bestMatch.length) {
                    // Remove other active items inside submenus to prevent stale multi-highlights
                    $sidebar.find('li.active:not(.treeview)').removeClass('active');
                    $sidebar.find('a.active').removeClass('active');

                    // Highlight the matched item
                    bestMatch.addClass('active');
                    var $parentLi = bestMatch.closest('li');
                    $parentLi.addClass('active menu-open');

                    // If inside treeview, open and expand all parent treeviews
                    var $treeviews = bestMatch.parents('li.treeview');
                    if ($treeviews.length) {
                        // Close any unrelated treeviews
                        $sidebar.find('li.treeview').not($treeviews).removeClass('active menu-open').children('.treeview-menu').hide();

                        $treeviews.each(function () {
                            var $tv = $(this);
                            $tv.addClass('active menu-open');
                            var $menu = $tv.children('.treeview-menu');
                            $menu.show().css('display', 'block');
                            $tv.find('> a .pull-right-container .fa-angle-right').css('transform', 'rotate(90deg)');
                        });
                    }

                    // Save matched href in sessionStorage
                    try {
                        sessionStorage.setItem('ekrishi_active_sidebar_href', (bestMatch[0].href || '').split('#')[0].split('?')[0].replace(/\/+$/, ''));
                    } catch (e) {}

                    // Scroll the active menu item into view in the sidebar
                    setTimeout(function () {
                        var activeEl = bestMatch[0];
                        var scrollContainer = document.querySelector('.multinav-scroll') || document.querySelector('.main-sidebar');
                        if (activeEl && scrollContainer) {
                            var rect = activeEl.getBoundingClientRect();
                            var containerRect = scrollContainer.getBoundingClientRect();
                            if (rect.top < containerRect.top || rect.bottom > containerRect.bottom) {
                                activeEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        }
                    }, 200);
                }
            }

            // Mobile Navigation & Backdrop Overlay Handlers
            function initMobileDrawer() {
                var $body = $('body');
                var $overlay = $('#sidebarOverlay');
                var $closeBtn = $('#sidebarMobileCloseBtn');

                // Close mobile sidebar
                function closeMobileMenu() {
                    $body.removeClass('sidebar-open');
                }

                // Mobile Menu Toggle Button (Hamburger)
                $(document).on('click', '#mobileMenuToggle, .mobile-hamburger-btn', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $body.toggleClass('sidebar-open');
                });

                // Overlay click/touch closes sidebar
                if ($overlay.length) {
                    $overlay.on('click touchstart', function (e) {
                        e.preventDefault();
                        closeMobileMenu();
                    });
                }

                // Close button inside sidebar
                if ($closeBtn.length) {
                    $closeBtn.on('click', function (e) {
                        e.preventDefault();
                        closeMobileMenu();
                    });
                }

                // Close drawer when content-wrapper is clicked on mobile
                $(document).on('click', '.content-wrapper', function () {
                    if ($body.hasClass('sidebar-open')) {
                        closeMobileMenu();
                    }
                });

                // When user clicks a sidebar menu link on mobile or desktop, immediately highlight & remember
                $('.sidebar-menu').on('click', 'a', function () {
                    var href = $(this).attr('href');
                    if (href && href !== '#' && !href.startsWith('javascript:')) {
                        // Immediately highlight clicked link
                        $('.sidebar-menu li.active:not(.treeview)').removeClass('active menu-open');
                        $('.sidebar-menu a.active').removeClass('active');
                        var $parentLi = $(this).closest('li');
                        $(this).addClass('active');
                        $parentLi.addClass('active menu-open');

                        var $treeviews = $(this).parents('li.treeview');
                        if ($treeviews.length) {
                            $treeviews.addClass('active menu-open');
                        }

                        try {
                            var cleanHref = (this.href || '').split('#')[0].split('?')[0].replace(/\/+$/, '');
                            sessionStorage.setItem('ekrishi_active_sidebar_href', cleanHref);
                        } catch (e) {}

                        if ($(window).width() <= 767) {
                            setTimeout(closeMobileMenu, 200);
                        }
                    }
                });
            }

            // Safety dismiss for admin preloader
            function dismissAdminLoader() {
                var ldr = document.getElementById('loader');
                if (ldr) {
                    ldr.classList.add('loaded');
                    setTimeout(function () {
                        if (ldr) ldr.style.display = 'none';
                    }, 400);
                }
            }
            window.addEventListener('load', function () {
                setTimeout(dismissAdminLoader, 150);
            });
            setTimeout(dismissAdminLoader, 2500);

            // Run on Ready and Window Load
            $(document).ready(function () {
                activateSidebarRoute();
                initMobileDrawer();
            });

            $(window).on('load', function () {
                activateSidebarRoute();
            });

        })(jQuery);
    </script>

    @yield('script')

    @include('components.chat-widget')

</body>

</html>
