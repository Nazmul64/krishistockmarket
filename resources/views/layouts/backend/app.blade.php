<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="images/favicon.ico">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>



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
                    <ul class="nav navbar-nav">

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
                                        <a class="dropdown-item" href="{{ route('my.cart') }}">
                                            <i class="ti-shopping-cart text-muted me-2"></i>
                                            My Cart
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
                            <li class="{{ (Request::route()->getName() == 'home') ? 'menu-open' : '' }}">
                                <a href="{{ route('home') }}">
                                    <img src="{{ asset('assets') }}/images/svg-icon/sidebar-menu/dashboard.svg"
                                        class="svg-icon" alt="">
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <!-- ================================= /Common sidebar menu  Item-->

                        <!-- ================================= Admin sidebar menu-->
                        @if (Auth::user()->role == "admin")

                            <li class="{{ (Request::route()->getName() == 'admin.card_numbers.index') ? 'menu-open' : '' }}">
                                <a href="{{ route('admin.card_numbers.index') }}">
                                    <img src="{{ asset('assets') }}/images/svg-icon/sidebar-menu/cards.svg"
                                        class="svg-icon" alt="">
                                    <span>Number Generator</span>
                                </a>
                            </li>

                            <li class="header">Stock Managment</li>
                            <li class="treeview {{ (Request::route()->getName() == 'admin.stock.index') || (Request::route()->getName() == 'admin.stock.allStock') || (Request::route()->getName() == 'admin.stock_preset.index') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <img src="{{ asset('assets') }}/images/svg-icon/sidebar-menu/transactions.svg"
                                        class="svg-icon" alt="">
                                    <span>Stock</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (Request::route()->getName() == 'admin.stock.index') || (Request::route()->getName() == 'admin.stock.allStock') || (Request::route()->getName() == 'admin.stock_preset.index') ? 'd-block' : '' }}" >

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
                            <li class="{{ (Request::route()->getName() == 'admin.stock.list')? 'menu-open' : '' }}">
                                <a href="{{ route('admin.stock.list') }}">
                                    <img src="{{ asset('assets') }}/images/svg-icon/sidebar-menu/transactions.svg"
                                        class="svg-icon" alt="">
                                    <span>Set Stock Price</span>
                                </a>
                            </li>
                            <li class="treeview {{ (Request::route()->getName() == 'admin.stock.buyrequest.list') || (Request::route()->getName() == 'admin.stock.sellrequest.list')|| (Request::route()->getName() == 'admin.buy.stock.list')|| (Request::route()->getName() == 'admin.sell.stock.list') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <img src="{{ asset('assets') }}/images/svg-icon/sidebar-menu/transactions.svg"
                                        class="svg-icon" alt="">
                                    <span>Stock Buy/Sell</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (Request::route()->getName() == 'admin.stock.buyrequest.list') || (Request::route()->getName() == 'admin.stock.sellrequest.list')|| (Request::route()->getName() == 'admin.buy.stock.list')|| (Request::route()->getName() == 'admin.sell.stock.list') ? 'd-block' : '' }}">

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

                            <li class="header">মাসিক বাজার Management</li>
                            <li class="treeview {{ (Request::route()->getName() == 'admin.monthly_bazaar.index') || (Request::route()->getName() == 'admin.monthly_bazaar.orders') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <img src="{{ asset('assets') }}/images/svg-icon/sidebar-menu/transactions.svg"
                                        class="svg-icon" alt="">
                                    <span>মাসিক বাজার</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (Request::route()->getName() == 'admin.monthly_bazaar.index') || (Request::route()->getName() == 'admin.monthly_bazaar.orders') ? 'd-block' : '' }}">
                                    <li class="">
                                        <a href="{{ route('admin.monthly_bazaar.index') }}">
                                            <i class="ti-more"></i>
                                            <span>প্যাকেজসমূহ</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.monthly_bazaar.orders') }}">
                                            <i class="ti-more"></i>
                                            <span>অর্ডার রিকোয়েস্ট</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="header">User Managment</li>
                            <li class="treeview {{ (Request::route()->getName() == 'alluser')? 'menu-open' : '' }}">
                                <a href="#">
                                    <img src="{{ asset('assets') }}/images/svg-icon/sidebar-menu/transactions.svg"
                                        class="svg-icon" alt="">
                                    <span>User's</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (Request::route()->getName() == 'alluser')? 'd-block' : '' }}">
                                    <li class="">
                                        <a href="{{ route('alluser') }}">
                                            <i class="ti-more"></i>
                                            <span>All User</span>
                                        </a>
                                    </li>

                                </ul>
                            </li>

                            <li class="treeview {{ (Request::route()->getName() == 'admin.all.withdraw.request') || (Request::route()->getName() == 'admin.all.withdraw') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <img src="{{ asset('assets') }}/images/svg-icon/sidebar-menu/transactions.svg"
                                        class="svg-icon" alt="">
                                    <span>Withdraw</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (Request::route()->getName() == 'admin.all.withdraw.request') || (Request::route()->getName() == 'admin.all.withdraw') ? 'd-block' : '' }}">

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
                            <li class="header">Employee Managment</li>

                            <li class="treeview {{ (Request::route()->getName() == 'admin.employee.index') || (Request::route()->getName() == 'admin.agent_ledger.index') || (Request::route()->getName() == 'admin.agent_ledger.show') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <img src="{{ asset('assets') }}/images/svg-icon/sidebar-menu/transactions.svg"
                                        class="svg-icon" alt="">
                                    <span>Employee / Agent</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (Request::route()->getName() == 'admin.employee.index') || (Request::route()->getName() == 'admin.agent_ledger.index') || (Request::route()->getName() == 'admin.agent_ledger.show') ? 'd-block' : '' }}">

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
                            <li class="header">Settings Managment</li>

                            <li class="treeview {{ (Request::route()->getName() == 'setting.index')||(Request::route()->getName() == 'setting.slider')||(Request::route()->getName() == 'profile.index')||(Request::route()->getName() == 'admin.payment.index') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <img src="{{ asset('assets') }}/images/svg-icon/sidebar-menu/transactions.svg"
                                        class="svg-icon" alt="">
                                    <span>Settings</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (Request::route()->getName() == 'setting.index')||(Request::route()->getName() == 'setting.slider')||(Request::route()->getName() == 'profile.index')||(Request::route()->getName() == 'admin.payment.index') ? 'd-block' : '' }}">

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

                                    <li class="">
                                        <a href="{{ route('admin.payment.index') }}">
                                            <i class="ti-more"></i>
                                            <span>Setup Paymet Method</span>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                        <!-- ================================= /Admin sidebar menu-->

                        <!-- ================================= User sidebar menu-->
                        @elseif (Auth::user()->role == "user")
                            <li class="{{ (Request::route()->getName() == 'stock.index') ? 'menu-open' : '' }}">
                                <a href="{{ route('stock.index') }}">
                                    <img src="{{ asset('assets') }}/images/svg-icon/sidebar-menu/transactions.svg"
                                        class="svg-icon" alt="">
                                    <span>Stock</span>
                                </a>
                            </li>
                            <li class="header">User Managment</li>


                            <li class="treeview {{ (Request::route()->getName() == 'userbuystocklist')||(Request::route()->getName() == 'usersellstocklist') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <img src="{{ asset('assets') }}/images/svg-icon/sidebar-menu/transactions.svg"
                                        class="svg-icon" alt="">
                                    <span>My Stock</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (Request::route()->getName() == 'userbuystocklist')||(Request::route()->getName() == 'usersellstocklist') ? 'd-block' : '' }}">

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

                            <li class="treeview {{ (Request::route()->getName() == 'user.monthly_bazaar.index') || (Request::route()->getName() == 'user.monthly_bazaar.my_orders') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <img src="{{ asset('assets') }}/images/svg-icon/sidebar-menu/transactions.svg"
                                        class="svg-icon" alt="">
                                    <span>মাসিক বাজার</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (Request::route()->getName() == 'user.monthly_bazaar.index') || (Request::route()->getName() == 'user.monthly_bazaar.my_orders') ? 'd-block' : '' }}">
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

                            <li class="{{ (Request::route()->getName() == 'my.cart') ? 'menu-open' : '' }}">
                                <a href="{{ route('my.cart') }}">
                                    <img src="{{ asset('assets') }}/images/svg-icon/sidebar-menu/transactions.svg"
                                        class="svg-icon" alt="">
                                    <span>My Cart</span>
                                </a>
                            </li>

                            <li class="treeview {{ (Request::route()->getName() == 'withdraw.index')||(Request::route()->getName() == 'withdraw.form') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <img src="{{ asset('assets') }}/images/svg-icon/sidebar-menu/transactions.svg"
                                        class="svg-icon" alt="">
                                        <span>Withdraw</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (Request::route()->getName() == 'withdraw.index')||(Request::route()->getName() == 'withdraw.form') ? 'd-block' : '' }}">
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


                            <li class="treeview {{ (Request::route()->getName() == 'profile.index')||(Request::route()->getName() == 'payment.index') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <img src="{{ asset('assets') }}/images/svg-icon/sidebar-menu/transactions.svg"
                                        class="svg-icon" alt="">
                                    <span>Settings</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (Request::route()->getName() == 'profile.index')||(Request::route()->getName() == 'payment.index') ? 'd-block' : '' }}">

                                    <li class="">
                                        <a href="{{ route('profile.index') }}">
                                            <i class="ti-more"></i>
                                            <span>My Profile</span>
                                        </a>
                                    </li>

                                    <li class="">
                                        <a href="{{ route('payment.index') }}">
                                            <i class="ti-more"></i>
                                            <span>Setup Paymet Method</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <!-- ================================ /User sidebar menu -->
                        @else

                        <!-- ================================ Employee sidebar menu -->
                        <li class="{{ (Request::route()->getName() == 'employee.stock_ledger.index') ? 'menu-open' : '' }}">
                            <a href="{{ route('employee.stock_ledger.index') }}">
                                <img src="{{ asset('assets') }}/images/svg-icon/sidebar-menu/transactions.svg"
                                    class="svg-icon" alt="">
                                <span>আমার লাইভ স্টক ও হিসাব</span>
                            </a>
                        </li>

                        <li class="treeview {{ (Request::route()->getName() == 'my.referal') ? 'menu-open' : '' }}">
                            <a href="#">
                                <img src="{{ asset('assets') }}/images/svg-icon/sidebar-menu/transactions.svg"
                                    class="svg-icon" alt="">
                                <span>Referal User</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu {{ (Request::route()->getName() == 'my.referal') ? 'd-block' : '' }}">
                                <li class="">
                                    <a href="{{ route('my.referal') }}">
                                        <i class="ti-more"></i>
                                        <span>My Referal</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="treeview {{ (Request::route()->getName() == 'profile.index') || (Request::route()->getName() == 'profile.business') ? 'menu-open' : '' }}">
                            <a href="#">
                                <img src="{{ asset('assets') }}/images/svg-icon/sidebar-menu/transactions.svg"
                                    class="svg-icon" alt="">
                                <span>Settings</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu {{ (Request::route()->getName() == 'profile.index') || (Request::route()->getName() == 'profile.business') ? 'd-block' : '' }}">

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


    @yield('script')


</body>

</html>
