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
        $site_name = $site_setting->site_name ?? $site_setting->name ?? 'iKrishiPoribar';
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
    <link rel="stylesheet" href="{{ asset('assets/main/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/main/css/skin_color.css') }}">
    <!-- Premium Modern Admin Custom Styles -->
    <link rel="stylesheet" href="{{ asset('assets/main/css/modern_admin.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .main-header .logo {
            width: 115px !important;
        }
    </style>
</head>

<body class="hold-transition light-skin sidebar-mini theme-warning fixed">

    <div class="wrapper">
        <div id="loader"></div>

        <header class="main-header">
            <div class="d-flex align-items-center logo-box justify-content-start">

                <a href="{{ route('home') }}" class="waves-effect waves-light nav-link rounded d-none d-md-inline-block push-btn"
                    data-toggle="push-menu" role="button">
                    <img style="width: 46px;" src="{{ asset('upload/images/backend/logo') }}/{{ setting('logo') }}"
                        class="img-fluid svg-icon" alt="">
                </a>

                <!-- Logo -->
                <a href="{{ route('home') }}" class="logo">
                    <div class="logo-lg">
                        <span class="light-logo">
                            <img style="width: 46px;" src="{{ asset('upload/images/backend/logo') }}/{{ setting('logo') }}" alt="logo">
                        </span>
                        <span class="dark-logo">
                            <img style="width: 46px;" src="{{ asset('upload/images/backend/logo') }}/{{ setting('logo') }}" alt="logo">
                        </span>
                    </div>
                </a>
            </div>
            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <div class="app-menu">
                    <ul class="header-megamenu nav">
                        <li class="btn-group nav-item d-md-none">
                            <a href="#" class="waves-effect waves-light nav-link push-btn btn-outline no-border"
                                data-toggle="push-menu" role="button">
                                <img src="{{ asset('assets') }}/images/svg-icon/sidebar-menu/collapse.svg"
                                    class="img-fluid svg-icon" alt="">
                            </a>
                        </li>
                        <li class="btn-group nav-item">
                            <a href="#" data-provide="fullscreen"
                                class="waves-effect waves-light nav-link btn-outline no-border full-screen"
                                title="Full Screen">
                                <img src="{{ asset('assets') }}/images/svg-icon/sidebar-menu/fullscreen.svg"
                                    class="img-fluid svg-icon" alt="">
                            </a>
                        </li>

                        @if (Auth::user()->role == "employee")
                            <li class="btn-group d-lg-inline-flex d-none">
                                <div class="" id="myDiv2" style="line-height: 46px;font-weight: 900;color: red;">
                                    {{ route('/') }}/register?ref={{ Auth::user()->username }}
                                </div>
                                <button style="margin-left: 10px;padding: 5px 10px !important;height: 34px;margin-top: 7px;" id="myButton2" class="btn btn-sm btn-success">Copy Link</button>
                            </li>
                        @endif

                    </ul>
                </div>

                <div class="navbar-custom-menu r-side">
                    <ul class="nav navbar-nav d-flex align-items-center flex-row gap-3">
                        <li class="d-none d-md-block">
                            <div class="header-search-bar">
                                <i class="fa fa-search"></i>
                                <input type="text" placeholder="Search menu, users, stock...">
                            </div>
                        </li>

                        @if(Auth::check() && Auth::user()->role == "user")
                        <li>
                            @php
                                $headerCartCount = \App\Models\UserCart::where('user_id', Auth::id())->sum('quantity');
                            @endphp
                            <a href="{{ route('my.cart') }}" class="btn-outline no-border position-relative text-dark d-flex align-items-center justify-content-center" title="My Cart" style="width: 38px; height: 38px; border-radius: 8px; background: rgba(0,0,0,0.04); font-size: 17px; text-decoration: none;">
                                <i class="fa fa-shopping-cart text-success"></i>
                                <span class="badge bg-danger rounded-pill user-cart-badge {{ $headerCartCount > 0 ? '' : 'd-none' }}" style="position: absolute; top: -3px; right: -4px; font-size: 10px; padding: 2px 5px; min-width: 17px;">{{ $headerCartCount }}</span>
                            </a>
                        </li>
                        @endif

                        <li>
                            <button type="button" class="btn-theme-gear" id="openThemeSettings" title="Theme Settings Customizer">
                                <i class="fa fa-cog"></i>
                            </button>
                        </li>

                        <!-- User Account-->
                        <li class="dropdown user user-menu">
                            <a href="#" class="waves-effect waves-light dropdown-toggle btn-outline no-border"
                                data-bs-toggle="dropdown" title="User">
                                <img src="{{ asset('assets') }}/images/svg-icon/sidebar-menu/user.svg"
                                    class="rounded svg-icon" alt="" />
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


        <aside class="main-sidebar">
            <!-- sidebar-->
            <section class="sidebar position-relative">
                <div class="multinav">
                    <div class="multinav-scroll" style="height: 100%;">


                        <!-- sidebar menu-->
                        <ul class="sidebar-menu" data-widget="tree">
                            <!-- ================================= Common sidebar menu  Item-->
                            <li class="{{ (optional(Request::route())->getName() == 'home') ? 'menu-open' : '' }}">
                                <a href="{{ route('home') }}">
                                    
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <!-- ================================= /Common sidebar menu  Item-->

                        <!-- ================================= Admin sidebar menu-->
                        <!-- ================================= Admin sidebar menu-->
                        @if (Auth::user()->role == "admin" || Auth::user()->role == "employee")

                            @if(Auth::user()->hasPermission('card_numbers'))
                            <li class="{{ (optional(Request::route())->getName() == 'admin.card_numbers.index') ? 'menu-open' : '' }}">
                                <a href="{{ route('admin.card_numbers.index') }}">
                                    <span>Number Generator</span>
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('our_packages'))
                            <li class="{{ (optional(Request::route())->getName() == 'admin.our_packages.index') ? 'active menu-open' : '' }}">
                                <a href="{{ route('admin.our_packages.index') }}">
                                    <i class="ti-package text-success"></i>
                                    <span>Our Packages</span>
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('our_packages') || Auth::user()->role_id == 1)
                            <li class="{{ (Str::startsWith(optional(Request::route())->getName(), 'admin.card_benefits')) ? 'active menu-open' : '' }}">
                                <a href="{{ route('admin.card_benefits.index') }}">
                                    <i class="ti-id-badge text-warning"></i>
                                    <span>কার্ড সুবিধাসমূহ</span>
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

                            @if(Auth::user()->hasPermission('live_chat'))
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

                            @if(Auth::user()->hasPermission('monthly_bazaar'))
                            <li class="header">মাসিক বাজার Management</li>
                            <li class="treeview {{ (optional(Request::route())->getName() == 'admin.monthly_bazaar.index') || (optional(Request::route())->getName() == 'admin.monthly_bazaar.orders') || (optional(Request::route())->getName() == 'admin.monthly_bazaar.distribution_reports') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <span>মাসিক বাজার</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'admin.monthly_bazaar.index') || (optional(Request::route())->getName() == 'admin.monthly_bazaar.orders') || (optional(Request::route())->getName() == 'admin.monthly_bazaar.distribution_reports') ? 'd-block' : '' }}">
                                    <li class="">
                                        <a href="{{ route('admin.monthly_bazaar.index') }}">
                                            <i class="ti-more"></i>
                                            <span>প্যাকেজসমূহ</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.monthly_bazaar.orders') }}">
                                            <i class="ti-more"></i>
                                            <span>অর্ডার রিকোয়েস্ট ও ডিস্ট্রিবিউশন</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.monthly_bazaar.distribution_reports') }}">
                                            <i class="ti-more"></i>
                                            <span>এলাকা ডিস্ট্রিবিউশন রিপোর্ট</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="{{ (optional(Request::route())->getName() == 'admin.agent_points.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.agent_points.index') }}">
                                    <i class="fa fa-map-marker text-danger"></i>
                                    <span>এজেন্ট পয়েন্ট</span>
                                </a>
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

                            @if(Auth::user()->hasPermission('hrm_management'))
                            <li class="header">HRM Management System</li>
                            <li class="treeview {{ str_contains(optional(Request::route())->getName() ?? '', 'admin.hrm') ? 'active menu-open' : '' }}">
                                <a href="#">
                                    <i class="ti-user text-danger"></i>
                                    <span>HRM সিস্টেম</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ str_contains(optional(Request::route())->getName() ?? '', 'admin.hrm') ? 'd-block' : '' }}">
                                    <li><a href="{{ route('admin.hrm.dashboard') }}"><i class="ti-dashboard text-success"></i> <span>HRM ড্যাশবোর্ড</span></a></li>
                                    <li><a href="{{ route('admin.hrm.employees.index') }}"><i class="ti-id-badge text-info"></i> <span>কর্মী ডিরেক্টরি</span></a></li>
                                    <li><a href="{{ route('admin.hrm.departments.index') }}"><i class="ti-layers text-primary"></i> <span>ডিপার্টমেন্টসমূহ</span></a></li>
                                    <li><a href="{{ route('admin.hrm.designations.index') }}"><i class="ti-medall text-warning"></i> <span>পদবীসমূহ</span></a></li>
                                    <li><a href="{{ route('admin.hrm.branches.index') }}"><i class="ti-location-pin text-info"></i> <span>ব্রাঞ্চসমূহ</span></a></li>
                                    <li><a href="{{ route('admin.hrm.shifts.index') }}"><i class="ti-time text-secondary"></i> <span>শিফট শিডিউল</span></a></li>
                                    <li><a href="{{ route('admin.hrm.attendance.index') }}"><i class="ti-alarm-clock text-danger"></i> <span>উপস্থিতি রেজিস্টার</span></a></li>
                                    <li><a href="{{ route('admin.hrm.leave.index') }}"><i class="ti-calendar text-dark"></i> <span>ছুটি অনুমোদন</span></a></li>
                                    <li><a href="{{ route('admin.hrm.payroll.index') }}"><i class="ti-money text-success"></i> <span>পে-রোল & বেতন</span></a></li>
                                    <li><a href="{{ route('admin.hrm.loans.index') }}"><i class="ti-hand-point-right text-warning"></i> <span>লোন & এডভান্স</span></a></li>
                                    <li><a href="{{ route('admin.hrm.recruitment.index') }}"><i class="ti-briefcase text-info"></i> <span>নিয়োগ ও রিক্রুটমেন্ট</span></a></li>
                                    <li><a href="{{ route('admin.hrm.performance.index') }}"><i class="ti-star text-warning"></i> <span>পারফরম্যান্স রিভিউ</span></a></li>
                                    <li><a href="{{ route('admin.hrm.assets.index') }}"><i class="ti-desktop text-secondary"></i> <span>কোম্পানি অ্যাসেট</span></a></li>
                                    <li><a href="{{ route('admin.hrm.announcements.index') }}"><i class="ti-announcement text-danger"></i> <span>ঘোষণা & নোটিশ</span></a></li>
                                    <li><a href="{{ route('admin.hrm.reports.index') }}"><i class="ti-bar-chart text-primary"></i> <span>এইচআর রিপোর্টস</span></a></li>
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
                            <li class="treeview {{ (optional(Request::route())->getName() == 'setting.index')||(optional(Request::route())->getName() == 'setting.slider')||(optional(Request::route())->getName() == 'admin.our_packages.index')||(optional(Request::route())->getName() == 'setting.offer_banner')||(optional(Request::route())->getName() == 'profile.index')||(optional(Request::route())->getName() == 'admin.payment.index') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <span>Settings</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'setting.index')||(optional(Request::route())->getName() == 'setting.slider')||(optional(Request::route())->getName() == 'admin.our_packages.index')||(optional(Request::route())->getName() == 'setting.offer_banner')||(optional(Request::route())->getName() == 'profile.index')||(optional(Request::route())->getName() == 'admin.payment.index') ? 'd-block' : '' }}">
                                    <li class="">
                                        <a href="{{ route('setting.index') }}">
                                            <i class="ti-more"></i>
                                            <span>Site & Contact Setting</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('setting.index') }}#about_us_full_text">
                                            <i class="ti-more"></i>
                                            <span>Pages (About, Privacy, Terms)</span>
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

                        <!-- ================================= /Admin sidebar menu-->

                        <!-- ================================= User sidebar menu-->
                        @elseif (Auth::user()->role == "user")
                            <li class="{{ (optional(Request::route())->getName() == 'stock.index') ? 'menu-open' : '' }}">
                                <a href="{{ route('stock.index') }}">
                                    
                                    <span>Stock</span>
                                </a>
                            </li>
                            <li class="header">User Managment</li>


                            <li class="treeview {{ (optional(Request::route())->getName() == 'userbuystocklist')||(optional(Request::route())->getName() == 'usersellstocklist') ? 'menu-open' : '' }}">
                                <a href="#">
                                    
                                    <span>My Stock</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'userbuystocklist')||(optional(Request::route())->getName() == 'usersellstocklist') ? 'd-block' : '' }}">

                                    <li class="">
                                        <a href="{{ route('userbuystocklist') }}">
                                            <i class="ti-more"></i>
                                            <span>Buy list</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('usersellstocklist') }}">
                                            <i class="ti-more"></i>
                                            <span>Sell List</span>
                                        </a>
                                    </li>

                                </ul>
                            </li>

                            <li class="treeview {{ (optional(Request::route())->getName() == 'user.monthly_bazaar.index') || (optional(Request::route())->getName() == 'user.monthly_bazaar.my_orders') ? 'menu-open' : '' }}">
                                <a href="#">
                                    
                                    <span>মাসিক বাজার</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'user.monthly_bazaar.index') || (optional(Request::route())->getName() == 'user.monthly_bazaar.my_orders') ? 'd-block' : '' }}">
                                    <li class="">
                                        <a href="{{ route('user.monthly_bazaar.index') }}">
                                            <i class="ti-more"></i>
                                            <span>মাসিক বাজার প্যাকেজ</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('user.monthly_bazaar.my_orders') }}">
                                            <i class="ti-more"></i>
                                            <span>আমার অর্ডারসমূহ</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="{{ (optional(Request::route())->getName() == 'user.deposit.index') ? 'menu-open' : '' }}">
                                <a href="{{ route('user.deposit.index') }}">
                                    
                                    <span>Deposit Money (ডিপোজিট)</span>
                                </a>
                            </li>

                            <li class="{{ (optional(Request::route())->getName() == 'user.wallet.ledger') ? 'menu-open' : '' }}">
                                <a href="{{ route('user.wallet.ledger') }}">
                                    
                                    <span>Wallet Ledger (ওয়ালেট খতিয়ান)</span>
                                </a>
                            </li>

                            <li class="{{ (optional(Request::route())->getName() == 'my.cart') ? 'menu-open' : '' }}">
                                <a href="{{ route('my.cart') }}" class="d-flex align-items-center justify-content-between">
                                    <span>My Cart</span>
                                    @php
                                        $sidebarCartCount = \App\Models\UserCart::where('user_id', Auth::id())->sum('quantity');
                                    @endphp
                                    <span class="badge bg-danger rounded-pill user-cart-badge {{ $sidebarCartCount > 0 ? '' : 'd-none' }}" style="font-size: 11px; padding: 3px 8px;">{{ $sidebarCartCount }}</span>
                                </a>
                            </li>

                            <li class="treeview {{ (optional(Request::route())->getName() == 'withdraw.index')||(optional(Request::route())->getName() == 'withdraw.form') ? 'menu-open' : '' }}">
                                <a href="#">
                                    
                                        <span>Withdraw</span>
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
                                    <i class="ti-more"></i>
                                    <span>পেমেন্ট মেথড</span>
                                </a>
                            </li>

                            <li class="treeview {{ (optional(Request::route())->getName() == 'profile.index') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <span>Settings</span>
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
                                </ul>
                            </li>
                        <!-- ================================ /User sidebar menu -->
                        @elseif (Auth::user()->role == "supplier")

                        <!-- ================================ Supplier sidebar menu -->
                        <li class="{{ (optional(Request::route())->getName() == 'supplier.dashboard') ? 'menu-open' : '' }}">
                            <a href="{{ route('supplier.dashboard') }}">
                                
                                <span>সাপ্লায়ার ড্যাশবোর্ড</span>
                            </a>
                        </li>
                        <li class="{{ (optional(Request::route())->getName() == 'supplier.supplies.index') ? 'menu-open' : '' }}">
                            <a href="{{ route('supplier.supplies.index') }}">
                                
                                <span>আমার সরবরাহকৃত পণ্য</span>
                            </a>
                        </li>
                        <li class="{{ (optional(Request::route())->getName() == 'supplier.supplies.create') ? 'menu-open' : '' }}">
                            <a href="{{ route('supplier.supplies.create') }}">
                                
                                <span>নতুন পণ্য এন্ট্রি/পোস্টিং</span>
                            </a>
                        </li>
                        <li class="{{ (optional(Request::route())->getName() == 'supplier.statement') ? 'menu-open' : '' }}">
                            <a href="{{ route('supplier.statement') }}">
                                
                                <span>অ্যাকাউন্ট স্টেটমেন্ট</span>
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
                                        <a href="http://ikrishiporibar.shop/">ikrishiporibar</a>
                                    </strong>
                                    © 2023 All Rights Reserved
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </aside>

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

    @yield('script')

    @include('components.chat-widget')

</body>

</html>
