<!DOCTYPE html>
<html lang="en">

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

    <!-- Library - Google Font Familys -->
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets') }}/revolution/css/settings.css">

    <!-- Library -->
    <link href="{{ asset('frontend/assets') }}/css/lib.css" rel="stylesheet">
    <link href="{{ asset('frontend/assets') }}/css/flags.css" rel="stylesheet">

    <!-- Custom - Common CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets') }}/css/rtl.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets') }}/css/style.css">
    <link id="color" href="{{ asset('frontend/assets') }}/css/color-schemes/default.css" rel="stylesheet" />
    <style>
        body, html {
            font-family: 'Hind Siliguri', 'Poppins', sans-serif !important;
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
        .navbar-brand img {
            max-height: 52px;
            background: #fff;
            padding: 4px 10px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .ownavigation .navbar-nav .nav-link {
            font-family: 'Hind Siliguri', 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 15px;
            color: #334155 !important;
            padding: 15px 16px !important;
            transition: all 0.2s ease;
        }
        .ownavigation .navbar-nav .nav-link:hover,
        .ownavigation .navbar-nav li.active .nav-link {
            color: #1b88ce !important;
        }
        .btn-nav-register {
            background: linear-gradient(135deg, #1b88ce, #1469a0);
            color: #fff !important;
            border-radius: 25px;
            padding: 8px 22px !important;
            box-shadow: 0 4px 12px rgba(27,136,206,0.3);
            margin-left: 10px;
        }
        .btn-nav-register:hover {
            box-shadow: 0 6px 16px rgba(27,136,206,0.45);
            transform: translateY(-1px);
        }

        /* Hide slider arrows & overlay caption boxes as requested */
        .tparrows, .tp-bullets, .tp-caption {
            display: none !important;
        }
    </style>
</head>

<body data-offset="200" data-spy="scroll" data-target=".ownavigation">
    <!-- Loader -->
    <div id="site-loader" class="load-complete">
        <div class="loader">
            <div class="line-scale">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div><!-- Loader /- -->

    <!-- Top Header Bar (Only Icons) -->
    <div class="top-header-bar d-none d-md-block">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <a href="tel:{{ setting('phone1') }}" title="{{ setting('phone1') }}" class="me-4"><i class="fa fa-phone text-info me-1"></i></a>
                    <a href="mailto:{{ setting('email1') }}" title="{{ setting('email1') }}" class="me-4"><i class="fa fa-envelope text-info me-1"></i></a>
                    <span title="{{ setting('address1') }}"><i class="fa fa-map-marker text-info me-1"></i></span>
                </div>
                <div class="d-flex align-items-center">
                    <a href="{{ setting('facbook_link') }}" title="Facebook" class="me-3"><i class="fa fa-facebook"></i></a>
                    <a href="{{ setting('linkedin_link') }}" title="Linkedin" class="me-3"><i class="fa fa-linkedin"></i></a>
                    <a href="{{ setting('twitter_link') }}" title="Twitter" class="me-3"><i class="fa fa-twitter"></i></a>
                    @auth
                        <a href="{{ route('dashboard') }}" title="ড্যাশবোর্ড" class="fw-bold text-success"><i class="fa fa-user"></i></a>
                    @else
                        <a href="{{ route('login') }}" title="লগইন" class="me-3"><i class="fa fa-sign-in"></i></a>
                        <a href="{{ route('register') }}" title="রেজিস্ট্রেশন" class="text-info"><i class="fa fa-user-plus"></i></a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Header Section -->
    <header class="container-fluid no-left-padding no-right-padding header_s header_s1 sticky-top bg-white shadow-sm">

        <!-- Menu Block -->
        <div class="menu-block">
            <!-- Container -->
            <div class="container">
                <nav class="navbar ownavigation navbar-expand-lg">
                    <a class="navbar-brand" href="{{ route('/') }}">
                        <img src="{{ asset('upload/images/backend/logo') }}/{{ setting('logo') }}" alt="Logo" />
                    </a>
                    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                        data-target="#navbar3" aria-controls="navbar3" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <i class="fa fa-bars"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbar3">
                        <ul class="navbar-nav ms-auto align-items-center">
                            <li class="{{ Route::is('/') ? 'active' : '' }}">
                                <a class="nav-link" title="হোম" href="{{ route('/') }}">হোম</a>
                            </li>
                            <li class="{{ Route::is('about') ? 'active' : '' }}">
                                <a class="nav-link" title="আমাদের সম্পর্কে" href="{{ route('about') }}">আমাদের সম্পর্কে</a>
                            </li>
                            <li class="{{ Route::is('stock.index') ? 'active' : '' }}">
                                <a class="nav-link" title="স্টক মার্কেট" href="{{ route('stock.index') }}">স্টক মার্কেট</a>
                            </li>
                            <li class="{{ Route::is('gallery') ? 'active' : '' }}">
                                <a class="nav-link" title="গ্যালারি" href="{{ route('gallery') }}">গ্যালারি</a>
                            </li>
                            <li class="{{ Route::is('contact') ? 'active' : '' }}">
                                <a class="nav-link" title="যোগাযোগ" href="{{ route('contact') }}">যোগাযোগ</a>
                            </li>
                            @auth
                                <li><a class="nav-link btn-nav-register" title="ড্যাশবোর্ড" href="{{ route('dashboard') }}"><i class="fa fa-tachometer me-1"></i> ড্যাশবোর্ড</a></li>
                            @else
                                <li><a class="nav-link btn-nav-register" title="রেজিস্ট্রেশন" href="{{ route('register') }}"><i class="fa fa-user-plus me-1"></i> রেজিস্ট্রেশন</a></li>
                            @endauth
                        </ul>
                    </div>
                </nav>

            </div><!-- Container /- -->
        </div>
        <!-- Menu Block /- -->

    </header><!-- Header Section /- -->






    @yield("content")





    <!-- Footer Main -->
    <footer class="container-fluid no-left-padding no-right-padding footer-main footer-section2">
        <!-- Main Footer Widgets (4 Columns) -->
        <div class="container-fluid no-left-padding no-right-padding footer-widget" style="background: #1e242b; padding-top: 50px; padding-bottom: 30px;">
            <div class="container">
                <div class="row">
                    <!-- Column 1: About & Logo -->
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-4 mb-lg-0">
                        <aside class="widget widget_about">
                            <a class="ftr-logo d-inline-block mb-3" href="{{ route('/') }}">
                                <img src="{{ asset('upload/images/backend/logo') }}/{{ setting('logo') }}" alt="Logo" style="max-height: 55px; background: #fff; padding: 5px 12px; border-radius: 8px;">
                            </a>
                            <p style="color: #a0aab5; font-size: 14px; line-height: 1.6; margin-top: 15px; margin-bottom: 20px;">
                                কৃষি পরিবার - আপনার কৃষি সেবা, স্টকের সঠিক মূল্য এবং আর্থিক সমৃদ্ধির নির্ভরযোগ্য ডিজিটাল মাধ্যম।
                            </p>
                            <ul class="social" style="margin: 0; padding: 0; list-style: none; display: flex; gap: 10px;">
                                <li><a href="{{ setting('facbook_link') }}" title="Facebook" style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: #1b88ce; color: #fff; border-radius: 50%; transition: all 0.3s ease;"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="{{ setting('linkedin_link') }}" title="Linkedin" style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: #1b88ce; color: #fff; border-radius: 50%; transition: all 0.3s ease;"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="{{ setting('twitter_link') }}" title="Twitter" style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: #1b88ce; color: #fff; border-radius: 50%; transition: all 0.3s ease;"><i class="fa fa-twitter"></i></a></li>
                            </ul>
                        </aside>
                    </div>

                    <!-- Column 2: Quick Links -->
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-4 mb-lg-0">
                        <aside class="widget widget_links">
                            <h3 class="widget-title" style="color: #fff; font-size: 18px; font-weight: 700; margin-bottom: 20px; border-bottom: 2px solid #1b88ce; display: inline-block; padding-bottom: 5px;">প্রয়োজনীয় লিঙ্ক</h3>
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                <li style="margin-bottom: 8px;"><a href="{{ route('/') }}" style="color: #a0aab5; text-decoration: none; font-size: 14px; transition: color 0.2s;"><i class="fa fa-angle-right text-info me-2"></i> হোমপেজ</a></li>
                                <li style="margin-bottom: 8px;"><a href="{{ route('about') }}" style="color: #a0aab5; text-decoration: none; font-size: 14px; transition: color 0.2s;"><i class="fa fa-angle-right text-info me-2"></i> আমাদের সম্পর্কে</a></li>
                                <li style="margin-bottom: 8px;"><a href="{{ route('privacy') }}" style="color: #a0aab5; text-decoration: none; font-size: 14px; transition: color 0.2s;"><i class="fa fa-angle-right text-info me-2"></i> প্রাইভেসি পলিসি</a></li>
                                <li style="margin-bottom: 8px;"><a href="{{ route('terms') }}" style="color: #a0aab5; text-decoration: none; font-size: 14px; transition: color 0.2s;"><i class="fa fa-angle-right text-info me-2"></i> টার্মস এন্ড কন্ডিশনস</a></li>
                                <li style="margin-bottom: 8px;"><a href="{{ route('gallery') }}" style="color: #a0aab5; text-decoration: none; font-size: 14px; transition: color 0.2s;"><i class="fa fa-angle-right text-info me-2"></i> গ্যালারি</a></li>
                                <li style="margin-bottom: 8px;"><a href="{{ route('contact') }}" style="color: #a0aab5; text-decoration: none; font-size: 14px; transition: color 0.2s;"><i class="fa fa-angle-right text-info me-2"></i> যোগাযোগ</a></li>
                            </ul>
                        </aside>
                    </div>

                    <!-- Column 3: Contact Info & Support -->
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-4 mb-lg-0">
                        <aside class="widget widget_contact_info">
                            <h3 class="widget-title" style="color: #fff; font-size: 18px; font-weight: 700; margin-bottom: 20px; border-bottom: 2px solid #1b88ce; display: inline-block; padding-bottom: 5px;">যোগাযোগ করুন</h3>
                            <ul style="list-style: none; padding: 0; margin: 0; color: #a0aab5; font-size: 14px;">
                                <li style="margin-bottom: 12px; display: flex; align-items: flex-start;">
                                    <i class="fa fa-map-marker text-info me-2" style="font-size: 16px; margin-top: 3px; margin-right: 10px;"></i>
                                    <span>{{ setting('address1') }} {{ setting('address2') }}</span>
                                </li>
                                <li style="margin-bottom: 12px; display: flex; align-items: center;">
                                    <i class="fa fa-phone text-info me-2" style="font-size: 16px; margin-right: 10px;"></i>
                                    <span>{{ setting('phone1') }} / {{ setting('phone2') }}</span>
                                </li>
                                <li style="margin-bottom: 12px; display: flex; align-items: center;">
                                    <i class="fa fa-envelope text-info me-2" style="font-size: 16px; margin-right: 10px;"></i>
                                    <span>{{ setting('email1') }}</span>
                                </li>
                            </ul>
                        </aside>
                    </div>

                    <!-- Column 4: Gallery Thumbnails -->
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <aside class="widget widget_instagram">
                            <h3 class="widget-title" style="color: #fff; font-size: 18px; font-weight: 700; margin-bottom: 20px; border-bottom: 2px solid #1b88ce; display: inline-block; padding-bottom: 5px;">প্রোডাক্ট গ্যালারি</h3>
                            <div class="instagram-box">
                                @php
                                    $gallery_images = App\Models\StockGallery::take(6)->get();
                                @endphp
                                <div class="row g-2">
                                    @foreach ($gallery_images as $image)
                                    <div class="col-4 mb-2" style="padding: 2px;">
                                        <a href="{{ route('gallery') }}" style="display: block; border-radius: 6px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                                            <img src="{{ asset('upload/stock_images') }}/{{ $image->image }}" alt="Gallery" style="width: 100%; height: 55px; object-fit: cover; transition: transform 0.3s ease;">
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </aside>
                    </div>

                </div>
            </div>
        </div><!-- Top Footer /- -->

        <!-- Bottom Footer -->
        <div class="container-fluid no-left-padding no-right-padding bottom-footer" style="background: #161a1e; padding: 18px 0; border-top: 1px solid rgba(255,255,255,0.08);">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p style="color: #94a3b8; font-size: 14px; margin: 0;">
                            © {{ date('Y') }} <a href="{{ route('/') }}" style="color: #38bdf8; text-decoration: none; font-weight: 600;">iKrishiPoribar</a>. All Rights Reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                        <p style="color: #64748b; font-size: 13px; margin: 0;">
                            Smart Agriculture & Stock Market Solutions
                        </p>
                    </div>
                </div>
            </div>
        </div><!-- Bottom Footer /- -->

    </footer>
    <!-- Footer Main /- -->

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

    <!-- SLIDER REVOLUTION 5.0 EXTENSIONS  (Load Extensions only on Local File Systems !  The following part can be removed on Server for On Demand Loading) -->
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

</body>

</html>
