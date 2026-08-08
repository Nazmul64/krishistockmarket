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

    <title>Crypto Tokenizer UI Interface & Cryptocurrency Admin Template</title>



    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/perfect-scrollbar/css/perfect-scrollbar.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/morris.js/morris.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/OwlCarousel2/dist/assets/owl.carousel.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/OwlCarousel2/dist/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/horizontal-timeline/css/horizontal-timeline.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/flexslider/flexslider.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/prism/prism.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/datatable/datatables.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/Magnific-Popup-master/dist/magnific-popup.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/gallery/css/animated-masonry-gallery.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/lightbox-master/dist/ekko-lightbox.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/jvectormap/lib2/jquery-jvectormap-2.0.2.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/sweetalert/sweetalert.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-markdown-master/css/bootstrap-markdown.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/dropzone/dropzone.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-select/dist/css/bootstrap-select.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/raty-master/lib/jquery.raty.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/ion-rangeSlider/css/ion.rangeSlider.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/ion-rangeSlider/css/ion.rangeSlider.skinModern.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/gridstack/gridstack.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/jquery-toast-plugin-master/src/jquery.toast.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/nestable/nestable.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-switch/switch.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/c3/c3.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/chartist-js-develop/chartist.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_plugins/bootstrap-slider/slider.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_plugins/iCheck/flat/blue.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_plugins/iCheck/all.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_plugins/timepicker/bootstrap-timepicker.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_plugins/pace/pace.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/fullcalendar/fullcalendar.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/fullcalendar/fullcalendar.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/weather-icons/weather-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/FlipClock-master/compiled/flipclock.css">
    {{-- /*# sourceMappingURL=vendors_css.css.map */ --}}


    <!-- Style-->
    <link rel="stylesheet" href="{{ asset('assets/main/css/color_theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/main/css/style_rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/font-awesome/css/all.min">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/Ionicons/css/ionicons.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/linea-icons/linea.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/glyphicons/glyphicon.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/flag-icon-css/css/flag-icon.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/material-design-iconic-font/css/materialdesignicons.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/cryptocoins-master/cryptocoins.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/weather-icons/css/weather-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/iconsmind/style.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/icomoon/style.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/animate/animate.css">


    <link rel="stylesheet" href="{{ asset('assets/main/css/style.css') }}">




    <link rel="stylesheet" href="{{ asset('assets/main/css/skin_color.css') }}">





</head>

<body class="hold-transition theme-primary bg-img" style="background-image: url({{ asset('assets/images/auth-bg/bg-9.jpg') }})">


    @yield('content')

    <!-- Vendor JS -->
    <script src="{{ asset('assets/main/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/main/js/pages/chat-popup.js') }}"></script>
    <script src="{{ asset('assets/plugin/icons/feather-icons/feather.min.js') }}"></script>

</body>

</html>
