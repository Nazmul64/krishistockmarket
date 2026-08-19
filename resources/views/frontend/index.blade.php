@extends('layouts.frontend.app')

@section('content')

<div class="main-container">

    <main class="site-main">

        <style>
            #home-slider-1_wrapper, #home-slider-1_wrapper .rev_slider {
                max-height: 380px !important;
                border-radius: 12px;
                overflow: hidden;
            }
            #home-slider-1 .rev-slidebg {
                max-height: 380px !important;
                object-fit: cover !important;
                background-size: cover !important;
            }
            @media (max-width: 767.98px) {
                .stock-card-img {
                    height: 130px !important;
                }
            }
        </style>
        <!-- Top Banner & Slider Section -->
        <section class="slider-and-banner-section py-3" style="background: #f8fafc;">
            <div class="container">
                @if(!empty(setting('offer_banner_img')) && file_exists(public_path('upload/slider/'.setting('offer_banner_img'))))
                    <div class="row g-3 align-items-stretch">
                        <!-- Left Side: Main Slider (col-lg-8) -->
                        <div class="col-lg-8 col-12 mb-3 mb-lg-0">
                            <div id="home-slider-1_wrapper" class="rev_slider_wrapper fullwidthbanner-container shadow-sm"
                                data-alias="bb-homeslider-1" data-source="gallery">
                                <div id="home-slider-1" class="rev_slider fullwidthabanner" style="display:none;" data-version="5.4.1">
                                    <ul>
                                        @if(!empty(setting('slider1_img')))
                                        <!-- SLIDE 1 -->
                                        <li data-index="rs-18"
                                            data-transition="random-static,random-premium,random,scaledownfromright,scaledownfromleft,scaledownfromtop,scaledownfrombottom,zoomout,zoomin,slotzoom-horizontal,slotzoom-vertical"
                                            data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off" data-randomtransition="on"
                                            data-thumb="{{ asset('upload/slider') }}/{{ setting('slider1_img') }}"
                                            class="slider-shape" data-title="Slide 1">
                                            <img src="{{ asset('upload/slider') }}/{{ setting('slider1_img') }}" alt="" title="slide-1" width="1920"
                                                height="480" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat"
                                                class="rev-slidebg" data-no-retina>
                                            <div class="tp-caption tp-layer-selectable" id="slide-18-layer-1"
                                                data-x="['left','left','left','left']" data-hoffset="['375','29','23','13']"
                                                data-y="['middle','middle','middle','middle']" data-voffset="['-108','-108','-108','-108']"
                                                data-fontsize="['60','60','60','40']" data-lineheight="['68','68','68','50']"
                                                data-width="none" data-height="none" data-whitespace="nowrap" data-type="text"
                                                data-responsive_offset="on"
                                                data-frames='[{"delay":10,"speed":2000,"frame":"0","from":"x:right;","to":"o:1;","ease":"easeInOutExpo"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'
                                                style="z-index: 5; white-space: nowrap; font-size: 60px; line-height: 68px; font-weight: 700; color: #ffffff; letter-spacing: 1px;font-family:Poppins;text-transform:uppercase;">
                                                {{ setting('slider1_text') }}
                                            </div>
                                            <div class="tp-caption tp-layer-selectable tp-resizeme" id="slide-18-layer-3"
                                                data-x="['left','left','left','left']" data-hoffset="['376','29','23','14']"
                                                data-y="['middle','middle','middle','middle']" data-voffset="['35','35','35','21']"
                                                data-width="['684','684','684','427']" data-height="['58','58','58','85']"
                                                data-whitespace="normal" data-type="text" data-responsive_offset="on"
                                                data-frames='[{"delay":10,"speed":1000,"frame":"0","from":"x:[175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:1;","mask":"x:[-100%];y:0;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                                                style="z-index: 7; min-width: 684px; max-width: 684px; white-space: normal; font-size: 16px; line-height: 28px; font-weight: 400; color: #d7d2ca; font-family:Open Sans;">
                                                {{ setting('slider1_description') }}
                                            </div>
                                        </li>
                                        @endif

                                        @if(!empty(setting('slider2_img')))
                                        <!-- SLIDE 2 -->
                                        <li data-index="rs-19"
                                            data-transition="random-static,random-premium,random,scaledownfromright,scaledownfromleft,scaledownfromtop,scaledownfrombottom,zoomout,zoomin,slotzoom-horizontal,slotzoom-vertical"
                                            data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off" data-randomtransition="on"
                                            data-thumb="{{ asset('upload/slider') }}/{{ setting('slider2_img') }}"
                                            class="slider-shape" data-title="Slide 2">
                                            <img src="{{ asset('upload/slider') }}/{{ setting('slider2_img') }}" alt="" title="slide-2" width="1920"
                                                height="480" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat"
                                                class="rev-slidebg" data-no-retina>
                                            <div class="tp-caption tp-layer-selectable" id="slide-19-layer-1"
                                                data-x="['left','left','left','left']" data-hoffset="['375','29','23','13']"
                                                data-y="['middle','middle','middle','middle']" data-voffset="['-108','-108','-108','-108']"
                                                data-fontsize="['60','60','60','40']" data-lineheight="['68','68','68','50']"
                                                data-width="none" data-height="none" data-whitespace="nowrap" data-type="text"
                                                data-responsive_offset="on"
                                                data-frames='[{"delay":10,"speed":2000,"frame":"0","from":"x:right;","to":"o:1;","ease":"easeInOutExpo"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'
                                                style="z-index: 5; white-space: nowrap; font-size: 60px; line-height: 68px; font-weight: 700; color: #ffffff; letter-spacing: 1px;font-family:Poppins;text-transform:uppercase;">
                                                {{ setting('slider2_text') }}
                                            </div>
                                            <div class="tp-caption tp-layer-selectable tp-resizeme" id="slide-19-layer-3"
                                                data-x="['left','left','left','left']" data-hoffset="['376','29','23','13']"
                                                data-y="['middle','middle','middle','middle']" data-voffset="['35','35','35','21']"
                                                data-width="['684','684','684','427']" data-height="['58','58','58','85']"
                                                data-whitespace="normal" data-type="text" data-responsive_offset="on"
                                                data-frames='[{"delay":10,"speed":1000,"frame":"0","from":"x:[175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:1;","mask":"x:[-100%];y:0;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                                                style="z-index: 7; min-width: 684px; max-width: 684px; white-space: normal; font-size: 16px; line-height: 28px; font-weight: 400; color: #d7d2ca; font-family:Open Sans;">
                                                {{ setting('slider2_description') }}
                                            </div>
                                        </li>
                                        @endif

                                        @if(!empty(setting('slider3_img')))
                                        <!-- SLIDE 3 -->
                                        <li data-index="rs-20"
                                            data-transition="random-static,random-premium,random,scaledownfromright,scaledownfromleft,scaledownfromtop,scaledownfrombottom,zoomout,zoomin,slotzoom-horizontal,slotzoom-vertical"
                                            data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off" data-randomtransition="on"
                                            data-thumb="{{ asset('upload/slider') }}/{{ setting('slider3_img') }}"
                                            class="slider-shape" data-title="Slide 3">
                                            <img src="{{ asset('upload/slider') }}/{{ setting('slider3_img') }}" alt="" title="slide-3" width="1920"
                                                height="480" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat"
                                                class="rev-slidebg" data-no-retina>
                                            <div class="tp-caption tp-layer-selectable" id="slide-20-layer-1"
                                                data-x="['left','left','left','left']" data-hoffset="['375','29','23','13']"
                                                data-y="['middle','middle','middle','middle']" data-voffset="['-108','-108','-108','-108']"
                                                data-fontsize="['60','60','60','40']" data-lineheight="['68','68','68','50']"
                                                data-width="none" data-height="none" data-whitespace="nowrap" data-type="text"
                                                data-responsive_offset="on"
                                                data-frames='[{"delay":10,"speed":2000,"frame":"0","from":"x:right;","to":"o:1;","ease":"easeInOutExpo"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'
                                                style="z-index: 5; white-space: nowrap; font-size: 60px; line-height: 68px; font-weight: 700; color: #ffffff; letter-spacing: 1px;font-family:Poppins;text-transform:uppercase;">
                                                {{ setting('slider3_text') }}
                                            </div>
                                            <div class="tp-caption tp-layer-selectable tp-resizeme" id="slide-20-layer-3"
                                                data-x="['left','left','left','left']" data-hoffset="['376','29','23','13']"
                                                data-y="['middle','middle','middle','middle']" data-voffset="['35','35','35','21']"
                                                data-width="['684','684','684','427']" data-height="['58','58','58','85']"
                                                data-whitespace="normal" data-type="text" data-responsive_offset="on"
                                                data-frames='[{"delay":10,"speed":1000,"frame":"0","from":"x:[175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:1;","mask":"x:[-100%];y:0;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                                                style="z-index: 7; min-width: 684px; max-width: 684px; white-space: normal; font-size: 16px; line-height: 28px; font-weight: 400; color: #d7d2ca; font-family:Open Sans;">
                                                {{ setting('slider3_description') }}
                                            </div>
                                        </li>
                                        @endif

                                        @if(!empty(setting('slider4_img')))
                                        <!-- SLIDE 4 -->
                                        <li data-index="rs-21"
                                            data-transition="random-static,random-premium,random,scaledownfromright,scaledownfromleft,scaledownfromtop,scaledownfrombottom,zoomout,zoomin,slotzoom-horizontal,slotzoom-vertical"
                                            data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off" data-randomtransition="on"
                                            data-thumb="{{ asset('upload/slider') }}/{{ setting('slider4_img') }}"
                                            class="slider-shape" data-title="Slide 4">
                                            <img src="{{ asset('upload/slider') }}/{{ setting('slider4_img') }}" alt="" title="slide-4" width="1920"
                                                height="480" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat"
                                                class="rev-slidebg" data-no-retina>
                                            <div class="tp-caption tp-layer-selectable" id="slide-21-layer-1"
                                                data-x="['left','left','left','left']" data-hoffset="['375','29','23','13']"
                                                data-y="['middle','middle','middle','middle']" data-voffset="['-108','-108','-108','-108']"
                                                data-fontsize="['60','60','60','40']" data-lineheight="['68','68','68','50']"
                                                data-width="none" data-height="none" data-whitespace="nowrap" data-type="text"
                                                data-responsive_offset="on"
                                                data-frames='[{"delay":10,"speed":2000,"frame":"0","from":"x:right;","to":"o:1;","ease":"easeInOutExpo"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'
                                                style="z-index: 5; white-space: nowrap; font-size: 60px; line-height: 68px; font-weight: 700; color: #ffffff; letter-spacing: 1px;font-family:Poppins;text-transform:uppercase;">
                                                {{ setting('slider4_text') }}
                                            </div>
                                            <div class="tp-caption tp-layer-selectable tp-resizeme" id="slide-21-layer-3"
                                                data-x="['left','left','left','left']" data-hoffset="['376','29','23','13']"
                                                data-y="['middle','middle','middle','middle']" data-voffset="['35','35','35','21']"
                                                data-width="['684','684','684','427']" data-height="['58','58','58','85']"
                                                data-whitespace="normal" data-type="text" data-responsive_offset="on"
                                                data-frames='[{"delay":10,"speed":1000,"frame":"0","from":"x:[175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:1;","mask":"x:[-100%];y:0;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                                                style="z-index: 7; min-width: 684px; max-width: 684px; white-space: normal; font-size: 16px; line-height: 28px; font-weight: 400; color: #d7d2ca; font-family:Open Sans;">
                                                {{ setting('slider4_description') }}
                                            </div>
                                        </li>
                                        @endif
                                    </ul>
                                    <div class="tp-bannertimer tp-bottom" style="visibility: hidden !important;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side: Pure Uploaded Offer Banner Image (col-lg-4) -->
                        <div class="col-lg-4 col-12 d-flex">
                            <div class="w-100 h-100 rounded-3 overflow-hidden shadow-sm d-flex align-items-center justify-content-center bg-white" style="border-radius: 12px;">
                                <a href="{{ setting('offer_banner_link') ?: 'javascript:void(0)' }}" class="d-block w-100 h-100">
                                    <img src="{{ asset('upload/slider/'.setting('offer_banner_img')) }}" 
                                         alt="{{ setting('offer_banner_title') ?: 'অফার ব্যানার' }}" 
                                         class="w-100 h-100 img-fluid" 
                                         style="object-fit: fill; border-radius: 12px; height: 100%; width: 100%;">
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- If no offer banner uploaded, slider takes full width cleanly -->
                    <div class="row">
                        <div class="col-12">
                            <div id="home-slider-1_wrapper" class="rev_slider_wrapper fullwidthbanner-container shadow-sm"
                                data-alias="bb-homeslider-1" data-source="gallery">
                                <div id="home-slider-1" class="rev_slider fullwidthabanner" style="display:none;" data-version="5.4.1">
                                    <ul>
                                        @if(!empty(setting('slider1_img')))
                                        <!-- SLIDE 1 -->
                                        <li data-index="rs-18"
                                            data-transition="random-static,random-premium,random,scaledownfromright,scaledownfromleft,scaledownfromtop,scaledownfrombottom,zoomout,zoomin,slotzoom-horizontal,slotzoom-vertical"
                                            data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off" data-randomtransition="on"
                                            data-thumb="{{ asset('upload/slider') }}/{{ setting('slider1_img') }}"
                                            class="slider-shape" data-title="Slide 1">
                                            <img src="{{ asset('upload/slider') }}/{{ setting('slider1_img') }}" alt="" title="slide-1" width="1920"
                                                height="480" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat"
                                                class="rev-slidebg" data-no-retina>
                                            <div class="tp-caption tp-layer-selectable" id="slide-18-layer-1"
                                                data-x="['left','left','left','left']" data-hoffset="['375','29','23','13']"
                                                data-y="['middle','middle','middle','middle']" data-voffset="['-108','-108','-108','-108']"
                                                data-fontsize="['60','60','60','40']" data-lineheight="['68','68','68','50']"
                                                data-width="none" data-height="none" data-whitespace="nowrap" data-type="text"
                                                data-responsive_offset="on"
                                                data-frames='[{"delay":10,"speed":2000,"frame":"0","from":"x:right;","to":"o:1;","ease":"easeInOutExpo"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'
                                                style="z-index: 5; white-space: nowrap; font-size: 60px; line-height: 68px; font-weight: 700; color: #ffffff; letter-spacing: 1px;font-family:Poppins;text-transform:uppercase;">
                                                {{ setting('slider1_text') }}
                                            </div>
                                            <div class="tp-caption tp-layer-selectable tp-resizeme" id="slide-18-layer-3"
                                                data-x="['left','left','left','left']" data-hoffset="['376','29','23','14']"
                                                data-y="['middle','middle','middle','middle']" data-voffset="['35','35','35','21']"
                                                data-width="['684','684','684','427']" data-height="['58','58','58','85']"
                                                data-whitespace="normal" data-type="text" data-responsive_offset="on"
                                                data-frames='[{"delay":10,"speed":1000,"frame":"0","from":"x:[175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:1;","mask":"x:[-100%];y:0;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                                                style="z-index: 7; min-width: 684px; max-width: 684px; white-space: normal; font-size: 16px; line-height: 28px; font-weight: 400; color: #d7d2ca; font-family:Open Sans;">
                                                {{ setting('slider1_description') }}
                                            </div>
                                        </li>
                                        @endif

                                        @if(!empty(setting('slider2_img')))
                                        <!-- SLIDE 2 -->
                                        <li data-index="rs-19"
                                            data-transition="random-static,random-premium,random,scaledownfromright,scaledownfromleft,scaledownfromtop,scaledownfrombottom,zoomout,zoomin,slotzoom-horizontal,slotzoom-vertical"
                                            data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off" data-randomtransition="on"
                                            data-thumb="{{ asset('upload/slider') }}/{{ setting('slider2_img') }}"
                                            class="slider-shape" data-title="Slide 2">
                                            <img src="{{ asset('upload/slider') }}/{{ setting('slider2_img') }}" alt="" title="slide-2" width="1920"
                                                height="480" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat"
                                                class="rev-slidebg" data-no-retina>
                                            <div class="tp-caption tp-layer-selectable" id="slide-19-layer-1"
                                                data-x="['left','left','left','left']" data-hoffset="['375','29','23','13']"
                                                data-y="['middle','middle','middle','middle']" data-voffset="['-108','-108','-108','-108']"
                                                data-fontsize="['60','60','60','40']" data-lineheight="['68','68','68','50']"
                                                data-width="none" data-height="none" data-whitespace="nowrap" data-type="text"
                                                data-responsive_offset="on"
                                                data-frames='[{"delay":10,"speed":2000,"frame":"0","from":"x:right;","to":"o:1;","ease":"easeInOutExpo"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'
                                                style="z-index: 5; white-space: nowrap; font-size: 60px; line-height: 68px; font-weight: 700; color: #ffffff; letter-spacing: 1px;font-family:Poppins;text-transform:uppercase;">
                                                {{ setting('slider2_text') }}
                                            </div>
                                            <div class="tp-caption tp-layer-selectable tp-resizeme" id="slide-19-layer-3"
                                                data-x="['left','left','left','left']" data-hoffset="['376','29','23','13']"
                                                data-y="['middle','middle','middle','middle']" data-voffset="['35','35','35','21']"
                                                data-width="['684','684','684','427']" data-height="['58','58','58','85']"
                                                data-whitespace="normal" data-type="text" data-responsive_offset="on"
                                                data-frames='[{"delay":10,"speed":1000,"frame":"0","from":"x:[175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:1;","mask":"x:[-100%];y:0;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                                                style="z-index: 7; min-width: 684px; max-width: 684px; white-space: normal; font-size: 16px; line-height: 28px; font-weight: 400; color: #d7d2ca; font-family:Open Sans;">
                                                {{ setting('slider2_description') }}
                                            </div>
                                        </li>
                                        @endif

                                        @if(!empty(setting('slider3_img')))
                                        <!-- SLIDE 3 -->
                                        <li data-index="rs-20"
                                            data-transition="random-static,random-premium,random,scaledownfromright,scaledownfromleft,scaledownfromtop,scaledownfrombottom,zoomout,zoomin,slotzoom-horizontal,slotzoom-vertical"
                                            data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off" data-randomtransition="on"
                                            data-thumb="{{ asset('upload/slider') }}/{{ setting('slider3_img') }}"
                                            class="slider-shape" data-title="Slide 3">
                                            <img src="{{ asset('upload/slider') }}/{{ setting('slider3_img') }}" alt="" title="slide-3" width="1920"
                                                height="480" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat"
                                                class="rev-slidebg" data-no-retina>
                                            <div class="tp-caption tp-layer-selectable" id="slide-20-layer-1"
                                                data-x="['left','left','left','left']" data-hoffset="['375','29','23','13']"
                                                data-y="['middle','middle','middle','middle']" data-voffset="['-108','-108','-108','-108']"
                                                data-fontsize="['60','60','60','40']" data-lineheight="['68','68','68','50']"
                                                data-width="none" data-height="none" data-whitespace="nowrap" data-type="text"
                                                data-responsive_offset="on"
                                                data-frames='[{"delay":10,"speed":2000,"frame":"0","from":"x:right;","to":"o:1;","ease":"easeInOutExpo"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'
                                                style="z-index: 5; white-space: nowrap; font-size: 60px; line-height: 68px; font-weight: 700; color: #ffffff; letter-spacing: 1px;font-family:Poppins;text-transform:uppercase;">
                                                {{ setting('slider3_text') }}
                                            </div>
                                            <div class="tp-caption tp-layer-selectable tp-resizeme" id="slide-20-layer-3"
                                                data-x="['left','left','left','left']" data-hoffset="['376','29','23','13']"
                                                data-y="['middle','middle','middle','middle']" data-voffset="['35','35','35','21']"
                                                data-width="['684','684','684','427']" data-height="['58','58','58','85']"
                                                data-whitespace="normal" data-type="text" data-responsive_offset="on"
                                                data-frames='[{"delay":10,"speed":1000,"frame":"0","from":"x:[175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:1;","mask":"x:[-100%];y:0;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                                                style="z-index: 7; min-width: 684px; max-width: 684px; white-space: normal; font-size: 16px; line-height: 28px; font-weight: 400; color: #d7d2ca; font-family:Open Sans;">
                                                {{ setting('slider3_description') }}
                                            </div>
                                        </li>
                                        @endif

                                        @if(!empty(setting('slider4_img')))
                                        <!-- SLIDE 4 -->
                                        <li data-index="rs-21"
                                            data-transition="random-static,random-premium,random,scaledownfromright,scaledownfromleft,scaledownfromtop,scaledownfrombottom,zoomout,zoomin,slotzoom-horizontal,slotzoom-vertical"
                                            data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off" data-randomtransition="on"
                                            data-thumb="{{ asset('upload/slider') }}/{{ setting('slider4_img') }}"
                                            class="slider-shape" data-title="Slide 4">
                                            <img src="{{ asset('upload/slider') }}/{{ setting('slider4_img') }}" alt="" title="slide-4" width="1920"
                                                height="480" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat"
                                                class="rev-slidebg" data-no-retina>
                                            <div class="tp-caption tp-layer-selectable" id="slide-21-layer-1"
                                                data-x="['left','left','left','left']" data-hoffset="['375','29','23','13']"
                                                data-y="['middle','middle','middle','middle']" data-voffset="['-108','-108','-108','-108']"
                                                data-fontsize="['60','60','60','40']" data-lineheight="['68','68','68','50']"
                                                data-width="none" data-height="none" data-whitespace="nowrap" data-type="text"
                                                data-responsive_offset="on"
                                                data-frames='[{"delay":10,"speed":2000,"frame":"0","from":"x:right;","to":"o:1;","ease":"easeInOutExpo"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'
                                                style="z-index: 5; white-space: nowrap; font-size: 60px; line-height: 68px; font-weight: 700; color: #ffffff; letter-spacing: 1px;font-family:Poppins;text-transform:uppercase;">
                                                {{ setting('slider4_text') }}
                                            </div>
                                            <div class="tp-caption tp-layer-selectable tp-resizeme" id="slide-21-layer-3"
                                                data-x="['left','left','left','left']" data-hoffset="['376','29','23','13']"
                                                data-y="['middle','middle','middle','middle']" data-voffset="['35','35','35','21']"
                                                data-width="['684','684','684','427']" data-height="['58','58','58','85']"
                                                data-whitespace="normal" data-type="text" data-responsive_offset="on"
                                                data-frames='[{"delay":10,"speed":1000,"frame":"0","from":"x:[175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:1;","mask":"x:[-100%];y:0;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                                                style="z-index: 7; min-width: 684px; max-width: 684px; white-space: normal; font-size: 16px; line-height: 28px; font-weight: 400; color: #d7d2ca; font-family:Open Sans;">
                                                {{ setting('slider4_description') }}
                                            </div>
                                        </li>
                                        @endif
                                    </ul>
                                    <div class="tp-bannertimer tp-bottom" style="visibility: hidden !important;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <!-- Live Agriculture Stock Market Section -->
        <section class="py-5" style="background: #f8fafc; padding-top: 60px; padding-bottom: 60px;">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="badge px-3 py-2 text-uppercase mb-2" style="background: #1b88ce; color: #fff; border-radius: 20px; font-size: 13px; font-weight: 600;">বাজার হালনাগাদ</span>
                    <h2 class="fw-bold text-dark mb-2" style="font-size: 32px; color: #0f172a; font-weight: 700;">লাইভ কৃষি স্টক ও বাজার দর</h2>
                    <p class="text-muted" style="max-width: 650px; margin: 0 auto; font-size: 15px; color: #64748b;">প্রতিদিনের রিয়েল-টাইম ক্রয় ও বিক্রয় মূল্য পর্যবেক্ষণ করুন এবং সেরা দামে সিদ্ধান্ত নিন</p>
                </div>

                <div class="row">
                    @php
                        $stocks = App\Models\Stock::latest()->take(6)->get();
                    @endphp
                    @foreach($stocks as $stock)
                        @php
                            $images = App\Models\StockGallery::where('stock_id', $stock->id)->first();
                            $image_path = $images ? asset('upload/stock_images/'.$images->image) : asset('upload/images/backend/logo/'.setting('logo'));
                            $last_price = StockLastPricing($stock->id);
                            $latest_selling = $last_price ? $last_price->selling_price : 0;
                            $latest_buying = $last_price ? $last_price->buying_price : 0;
                        @endphp
                        <div class="col-6 col-md-6 col-lg-4 mb-3 px-2 px-md-3">
                            <div class="card h-100 border-0 shadow-sm rounded-4" style="transition: all 0.3s ease; background: #fff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0;">
                                <div class="position-relative">
                                    <a href="{{ route('stock.detials', $stock->id) }}" class="d-block text-center">
                                        <img src="{{ $image_path }}" class="card-img-top w-100" alt="{{ $stock->stock_name }}" style="display: block;">
                                    </a>
                                    <span class="badge position-absolute top-0 end-0 m-2 m-md-3 px-2 px-md-3 py-1 py-md-2 shadow-sm" style="background: #10b981; color: #fff; border-radius: 20px; font-weight: 600; font-size: 11px;">
                                        <i class="fa fa-line-chart me-1"></i> লাইভ
                                    </span>
                                </div>
                                <div class="card-body p-2 p-md-4">
                                    <h4 class="fw-bold mb-1 mb-md-2 text-dark" title="{{ $stock->stock_name }}" style="font-size: 15px; font-weight: 700; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block;">{{ $stock->stock_name }}</h4>
                                    <p class="text-muted mb-2" style="font-size: 12px;">উপলব্ধ: <span class="fw-bold text-dark">{{ $stock->stock_quantity }}</span></p>

                                    <div class="d-flex justify-content-between align-items-center p-2 p-md-3 rounded-3 mb-2 mb-md-3" style="background: #f1f5f9; border-radius: 10px;">
                                        <div>
                                            <small class="text-muted d-block" style="font-size: 10px;">বিক্রয় মূল্য</small>
                                            <strong class="text-success" style="font-size: 14px; color: #16a34a;">৳{{ number_format($latest_selling) }}</strong>
                                        </div>
                                        <div style="width: 1px; height: 26px; background: #cbd5e1;"></div>
                                        <div>
                                            <small class="text-muted d-block" style="font-size: 10px;">ক্রয় মূল্য</small>
                                            <strong class="text-primary" style="font-size: 14px; color: #1b88ce;">৳{{ number_format($latest_buying) }}</strong>
                                        </div>
                                    </div>

                                    <a href="{{ route('stock.detials', $stock->id) }}" class="btn btn-outline-primary w-100 fw-bold py-1 py-md-2" style="border-color: #1b88ce; color: #1b88ce; border-radius: 8px; font-size: 12px; text-decoration: none; display: block; text-align: center;">
                                        বিস্তারিত <i class="fa fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('stock.index') }}" class="btn btn-primary btn-lg px-5 rounded-pill shadow" style="background: linear-gradient(135deg, #1b88ce, #1469a0); border: none; font-size: 16px; font-weight: 600; padding: 12px 35px; border-radius: 30px; color: #fff; text-decoration: none; display: inline-block;">
                        সকল স্টক ও মার্কেট রেট দেখুন <i class="fa fa-chevron-right ms-2"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- Our Packages Slider Section -->
        @php
            $our_packages = \App\Models\OurPackage::where('status', 1)->latest()->get();
        @endphp
        <div class="container-fluid no-left-padding no-right-padding our-packages-section" style="padding-top: 60px; padding-bottom: 60px; background: #f8fafc; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
            <div class="container">
                <!-- Section Header -->
                <div class="section-header section-header-1 text-center mb-4">
                    <h3 style="font-size: 30px; font-weight: 700; color: #0f172a;"><span style="color: #1b88ce;">আওয়ার </span>প্যাকেজসমূহ (Our Packages)</h3>
                    <p style="color: #64748b; font-size: 15px;">আমাদের বিশেষ মেম্বারশিপ প্রসেস ও প্যাকেজসমূহ দেখে নিন</p>
                </div>

                @if(count($our_packages) > 0)
                <div class="swiper ourPackagesSwiper" style="padding-bottom: 45px; position: relative;">
                    <div class="swiper-wrapper">
                        @foreach($our_packages as $pkg)
                        <div class="swiper-slide">
                            <div class="package-card shadow-sm rounded-4 overflow-hidden bg-white" style="border: 1px solid #cbd5e1; border-radius: 14px; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                                <div class="package-img-box" style="position: relative; overflow: hidden; background: #f1f5f9; text-align: center;">
                                    <img src="{{ asset($pkg->image) }}" alt="Our Package Image" class="img-fluid w-100" style="max-width: 100%; height: auto; border-radius: 14px; display: block;">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <!-- Pagination Dots Only -->
                    <div class="swiper-pagination"></div>
                </div>
                @else
                <div class="text-center py-5 bg-white rounded-4 shadow-sm" style="border: 2px dashed #cbd5e1; border-radius: 16px;">
                    <i class="fa fa-images text-muted mb-3" style="font-size: 48px;"></i>
                    <h5 style="color: #475569; font-weight: 600;">এডমিন প্যানেল থেকে 'Our Packages' ইমেজ আপলোড করুন</h5>
                    <p style="color: #94a3b8; font-size: 14px;">এডমিন সাইডবার > Our Packages (আওয়ার প্যাকেজ) থেকে ছবি আপলোড করলে এখানে স্লাইডার আকারে শো করবে।</p>
                </div>
                @endif
            </div>
        </div>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (document.querySelector('.ourPackagesSwiper')) {
                    var swiper = new Swiper(".ourPackagesSwiper", {
                        slidesPerView: 1,
                        spaceBetween: 20,
                        loop: true,
                        autoplay: {
                            delay: 3500,
                            disableOnInteraction: false,
                        },
                        pagination: {
                            el: ".swiper-pagination",
                            clickable: true,
                        },
                        breakpoints: {
                            576: {
                                slidesPerView: 2,
                                spaceBetween: 20,
                            },
                            768: {
                                slidesPerView: 3,
                                spaceBetween: 25,
                            },
                            1024: {
                                slidesPerView: 3,
                                spaceBetween: 30,
                            },
                        },
                    });
                }
            });
        </script>

        <!-- Krishi SME Card & Gold Card Benefits Section -->
        @php
            $card_benefits = \App\Models\CardBenefit::where('status', 1)->orderBy('order_num', 'asc')->get();
        @endphp
        @if(count($card_benefits) > 0)
        <div class="container-fluid no-left-padding no-right-padding card-benefits-section" style="padding-top: 75px; padding-bottom: 75px; background: linear-gradient(180deg, #f8fafc 0%, #edf2f7 100%); border-bottom: 1px solid #e2e8f0;">
            <div class="container">
                <!-- Section Header -->
                <div class="section-header section-header-1 text-center mb-5">
                    <span class="badge px-4 py-2 text-uppercase fw-bold mb-2 shadow-sm" style="background: rgba(27, 136, 206, 0.12); color: #0284c7; font-size: 13px; letter-spacing: 1px; border-radius: 30px; border: 1px solid rgba(27, 136, 206, 0.2);">
                        <i class="fa fa-id-card me-1"></i> কৃষি পরিবার মেম্বারশিপ কার্ড
                    </span>
                    <h3 style="font-size: 32px; font-weight: 800; color: #0f172a; margin-top: 10px;">
                        <span style="color: #1b88ce;">কার্ড</span> বিশেষ সুবিধাসমূহ ও নিয়মাবলী
                    </h3>
                    <p style="color: #64748b; font-size: 15px; max-width: 680px; margin: 0 auto;">
                        কৃষি এসএমই কার্ড ও গোল্ডেন কার্ডের বিশেষ সুযোগ-সুবিধা, বিনিয়োগ নীতিমালা ও এক্সক্লুসিভ অফারসমূহ
                    </p>
                </div>

                <!-- Card Slider / Grid Container -->
                <div class="swiper cardBenefitsSwiper" style="padding-bottom: 50px; position: relative;">
                    <div class="swiper-wrapper">
                        @foreach($card_benefits as $cardItem)
                        @php
                            $isGold = $cardItem->card_color_theme == 'gold' || $cardItem->card_type == 'gold';
                            $isRed = $cardItem->card_color_theme == 'red' || $cardItem->card_type == 'red';
                            
                            $themeBorder = $isGold ? '#eab308' : ($isRed ? '#ef4444' : '#3b82f6');
                            $themeGradient = $isGold ? 'linear-gradient(135deg, #92400e 0%, #d97706 50%, #f59e0b 100%)' : ($isRed ? 'linear-gradient(135deg, #7f1d1d 0%, #dc2626 50%, #ef4444 100%)' : 'linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #3b82f6 100%)');
                            $badgeBg = $isGold ? '#fef3c7' : ($isRed ? '#fee2e2' : '#dbeafe');
                            $badgeColor = $isGold ? '#92400e' : ($isRed ? '#991b1b' : '#1e40af');
                            $btnGradient = $isGold ? 'linear-gradient(135deg, #d97706, #b45309)' : ($isRed ? 'linear-gradient(135deg, #dc2626, #991b1b)' : 'linear-gradient(135deg, #2563eb, #1d4ed8)');
                        @endphp
                        <div class="swiper-slide" style="height: auto;">
                            <div class="card-benefit-box h-100 bg-white rounded-4 shadow-sm overflow-hidden d-flex flex-column" style="border: 2px solid {{ $themeBorder }}; border-radius: 22px; transition: all 0.35s ease; position: relative;">
                                <!-- Top Card Banner -->
                                <div class="card-top-header p-4 text-white position-relative" style="background: {{ $themeGradient }};">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge px-3 py-1 fw-bold shadow-sm" style="background: {{ $badgeBg }}; color: {{ $badgeColor }}; border-radius: 20px; font-size: 13px;">
                                            <i class="fa fa-star me-1"></i> {{ $cardItem->badge_text ?: ($isGold ? 'গোল্ডেন মেম্বারশিপ' : 'নরমাল মেম্বারশিপ') }}
                                        </span>
                                        @if($cardItem->validity)
                                        <span class="text-white-50 small fw-bold">
                                            <i class="fa fa-calendar-alt me-1"></i> মেয়াদ: {{ $cardItem->validity }}
                                        </span>
                                        @endif
                                    </div>
                                    <h4 class="fw-bold mb-1 text-white" style="font-size: 22px;">{{ $cardItem->card_name }}</h4>
                                    @if($cardItem->card_number_sample)
                                        <span class="badge bg-black bg-opacity-25 text-white font-monospace small px-2 py-1">{{ $cardItem->card_number_sample }}</span>
                                    @endif
                                </div>

                                <!-- Card Image Preview -->
                                <div class="card-visual-box text-center p-3 position-relative" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                                    @if($cardItem->image && file_exists(public_path($cardItem->image)))
                                        <img src="{{ asset($cardItem->image) }}" alt="{{ $cardItem->card_name }}" class="img-fluid rounded-3 shadow" style="max-height: 190px; object-fit: contain; transform: perspective(800px) rotateX(4deg); transition: transform 0.3s ease;">
                                    @else
                                        <div class="p-4 bg-light rounded-3 text-muted">
                                            <i class="fa fa-id-card fa-3x mb-2 text-primary"></i>
                                            <div>{{ $cardItem->card_name }}</div>
                                        </div>
                                    @endif

                                    @if($cardItem->brochure_image && file_exists(public_path($cardItem->brochure_image)))
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-dark px-3 py-1" style="font-size: 12px; border-radius: 20px;" data-bs-toggle="modal" data-bs-target="#brochureModal{{ $cardItem->id }}" data-toggle="modal" data-target="#brochureModal{{ $cardItem->id }}">
                                            <i class="fa fa-eye me-1"></i> অফিসিয়াল লিফলেট/ব্রোশিউর দেখুন
                                        </button>
                                    </div>
                                    @endif
                                </div>

                                <!-- Card Body & Facilities List -->
                                <div class="card-benefit-body p-4 d-flex flex-column flex-grow-1">
                                    @if($cardItem->short_description)
                                        <p class="text-muted small mb-3" style="line-height: 1.6; border-left: 3px solid {{ $themeBorder }}; padding-left: 12px;">
                                            {{ $cardItem->short_description }}
                                        </p>
                                    @endif

                                    <!-- Key Metrics / Specification Badges -->
                                    <div class="row g-2 mb-3">
                                        @if($cardItem->card_fee)
                                        <div class="col-12">
                                            <div class="p-2 rounded bg-light border d-flex align-items-center justify-content-between">
                                                <small class="fw-bold text-dark"><i class="fa fa-receipt text-primary me-1"></i> সদস্য ফি:</small>
                                                <span class="badge bg-white text-dark border small fw-bold">{{ $cardItem->card_fee }}</span>
                                            </div>
                                        </div>
                                        @endif

                                        @if($cardItem->investment_limit)
                                        <div class="col-12">
                                            <div class="p-2 rounded bg-light border d-flex align-items-center justify-content-between">
                                                <small class="fw-bold text-dark"><i class="fa fa-chart-line text-success me-1"></i> বিনিয়োগ সীমা:</small>
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success small fw-bold">{{ $cardItem->investment_limit }}</span>
                                            </div>
                                        </div>
                                        @endif

                                        @if($cardItem->monthly_profit)
                                        <div class="col-12">
                                            <div class="p-2 rounded bg-light border">
                                                <small class="fw-bold text-dark d-block mb-1"><i class="fa fa-coins text-warning me-1"></i> লভ্যাংশ বিবরণ:</small>
                                                <span class="text-secondary small">{{ $cardItem->monthly_profit }}</span>
                                            </div>
                                        </div>
                                        @endif

                                        @if($cardItem->withdrawal_notice)
                                        <div class="col-12">
                                            <div class="p-2 rounded bg-light border d-flex align-items-center justify-content-between">
                                                <small class="fw-bold text-dark"><i class="fa fa-clock text-danger me-1"></i> উত্তোলন নোটিশ:</small>
                                                <span class="text-danger small fw-bold">{{ $cardItem->withdrawal_notice }}</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Rules / Facilities List -->
                                    <div class="facilities-container flex-grow-1 mb-4">
                                        <h6 class="fw-bold text-dark mb-3" style="font-size: 15px;">
                                            <i class="fa fa-list-check me-1 text-primary"></i> নিয়মাবলী ও বিশেষ সুবিধাসমূহ:
                                        </h6>
                                        @if(is_array($cardItem->facilities) && count($cardItem->facilities) > 0)
                                            <ul class="list-unstyled mb-0">
                                                @foreach($cardItem->facilities as $facility)
                                                    <li class="d-flex align-items-start mb-2 pb-1" style="font-size: 13.5px; color: #334155; line-height: 1.55;">
                                                        <i class="fa fa-check-circle me-2 mt-1 text-success flex-shrink-0" style="font-size: 14px;"></i>
                                                        <span>{{ $facility }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-muted small">কোনো নিয়ম বা সুবিধা তালিকাভুক্ত নেই</p>
                                        @endif
                                    </div>

                                    <!-- Action Button -->
                                    <div class="mt-auto pt-3 border-top">
                                        <a href="{{ url($cardItem->action_button_url ?: '/register') }}" class="btn w-100 py-2 text-white fw-bold shadow-sm rounded-3 d-flex align-items-center justify-content-center" style="background: {{ $btnGradient }}; border: none; font-size: 15px; transition: all 0.3s ease;">
                                            <span>{{ $cardItem->action_button_text ?: 'কার্ডের জন্য আবেদন করুন' }}</span>
                                            <i class="fa fa-arrow-right ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal for full brochure preview -->
                        @if($cardItem->brochure_image && file_exists(public_path($cardItem->brochure_image)))
                        <div class="modal fade" id="brochureModal{{ $cardItem->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
                                    <div class="modal-header py-3 text-white" style="background: {{ $themeGradient }};">
                                        <h5 class="modal-title fw-bold text-white"><i class="fa fa-file-image me-2"></i> {{ $cardItem->card_name }} - অফিসিয়াল ব্রোশিউর</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-0 text-center bg-dark">
                                        <img src="{{ asset($cardItem->brochure_image) }}" alt="Brochure" class="img-fluid w-100" style="max-height: 85vh; object-fit: contain;">
                                    </div>
                                    <div class="modal-footer bg-light py-2">
                                        <a href="{{ asset($cardItem->brochure_image) }}" download class="btn btn-sm btn-primary">
                                            <i class="fa fa-download me-1"></i> ব্রোশিউর ডাউনলোড করুন
                                        </a>
                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">বন্ধ করুন</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @endforeach
                    </div>
                    <!-- Pagination Dots -->
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (document.querySelector('.cardBenefitsSwiper')) {
                    new Swiper(".cardBenefitsSwiper", {
                        slidesPerView: 1,
                        spaceBetween: 25,
                        loop: {{ count($card_benefits) > 2 ? 'true' : 'false' }},
                        autoplay: {
                            delay: 5000,
                            disableOnInteraction: false,
                        },
                        pagination: {
                            el: ".swiper-pagination",
                            clickable: true,
                        },
                        breakpoints: {
                            768: {
                                slidesPerView: 2,
                                spaceBetween: 25,
                            },
                            1024: {
                                slidesPerView: 2,
                                spaceBetween: 30,
                            },
                        },
                    });
                }
            });
        </script>
        @endif

        <!-- Why Choose Section -->
        <style>
            .why-choose-card {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 18px;
                padding: 28px 24px;
                height: 100%;
                display: flex;
                flex-direction: column;
                transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
                position: relative;
                overflow: hidden;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
            }
            .why-choose-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: linear-gradient(90deg, #1b88ce, #06b6d4);
                opacity: 0;
                transition: opacity 0.35s ease;
            }
            .why-choose-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 20px 30px -10px rgba(27, 136, 206, 0.18), 0 8px 12px -6px rgba(0, 0, 0, 0.06);
                border-color: rgba(27, 136, 206, 0.4);
            }
            .why-choose-card:hover::before {
                opacity: 1;
            }
            .why-choose-card .feature-img-wrapper {
                width: 75px;
                height: 75px;
                border-radius: 16px;
                background: #f0f7fc;
                border: 1px solid #e0f0fa;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 20px;
                padding: 10px;
                transition: all 0.35s ease;
            }
            .why-choose-card:hover .feature-img-wrapper {
                background: #e0f2fe;
                transform: scale(1.08) rotate(-2deg);
                border-color: #bae6fd;
            }
            .why-choose-card .feature-img-wrapper img {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
                display: block;
            }
            .why-choose-card .feature-title {
                font-size: 19px;
                font-weight: 700;
                color: #0f172a;
                margin-bottom: 12px;
                transition: color 0.3s ease;
            }
            .why-choose-card:hover .feature-title {
                color: #1b88ce;
            }
            .why-choose-card .feature-desc {
                color: #64748b;
                font-size: 14.5px;
                line-height: 1.65;
                margin-bottom: 0;
            }
        </style>

        <div class="container-fluid no-left-padding no-right-padding why-choose-section why-choose-style-1" style="padding-top: 70px; padding-bottom: 70px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
            <!-- Container -->
            <div class="container">

                <!-- Section Header -->
                <div class="section-header section-header-1 text-center mb-5">
                    <span class="badge px-4 py-2 text-uppercase fw-bold mb-2 shadow-sm" style="background: rgba(27, 136, 206, 0.12); color: #0284c7; font-size: 13px; letter-spacing: 1px; border-radius: 30px; border: 1px solid rgba(27, 136, 206, 0.2);">
                        <i class="fa fa-gem me-1"></i> আমাদের বিশেষত্ব
                    </span>
                    <h3 style="font-size: 32px; font-weight: 800; color: #0f172a; margin-top: 10px;">
                        <span style="color: #1b88ce;">কেন </span>কৃষি পরিবার বেছে নেবেন?
                    </h3>
                    <p style="color: #64748b; font-size: 15px; max-width: 650px; margin: 0 auto;">
                        কৃষি ও ব্যবসা খাতের জন্য আমাদের বিশ্বস্ত সেবা, আধুনিক সুযোগ-সুবিধা ও নির্ভরযোগ্য প্ল্যাটফর্ম
                    </p>
                </div><!-- Section Header /- -->

                @php
                    $all_features = App\Models\Feature::all();
                @endphp

                @if(count($all_features) > 0)
                <div class="row g-4 justify-content-center">
                    @foreach($all_features as $feature_box)
                    <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                        <div class="why-choose-card">
                            <div class="feature-img-wrapper">
                                @if(!empty($feature_box->image) && file_exists(public_path($feature_box->image)))
                                    <img src="{{ asset($feature_box->image) }}" alt="{{ $feature_box->title }}">
                                @else
                                    <i class="fa {{ $feature_box->icon ?: 'fa-check-circle' }}" style="font-size: 32px; color: {{ $feature_box->color ?? '#1b88ce' }};"></i>
                                @endif
                            </div>
                            <h4 class="feature-title">{{ $feature_box->title }}</h4>
                            <p class="feature-desc">{{ $feature_box->description }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="row g-4 justify-content-center">
                    <!-- Default sample features if none created yet -->
                    <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                        <div class="why-choose-card">
                            <div class="feature-img-wrapper">
                                <i class="fa fa-shield-alt" style="font-size: 32px; color: #1b88ce;"></i>
                            </div>
                            <h4 class="feature-title">১০০% নিরাপদ ও বিশ্বস্ত</h4>
                            <p class="feature-desc">আমাদের প্ল্যাটফর্মে সকল লেনদেন এবং তথ্য সর্বোচ্চ নিরাপত্তা বজায় রেখে পরিচালিত হয়।</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                        <div class="why-choose-card">
                            <div class="feature-img-wrapper">
                                <i class="fa fa-chart-line" style="font-size: 32px; color: #10b981;"></i>
                            </div>
                            <h4 class="feature-title">স্মার্ট স্টক পর্যবেক্ষণ</h4>
                            <p class="feature-desc">দৈনিক ও সাপ্তাহিক স্টকের রিয়েল-টাইম প্রাইস আপডেট ও লাভ-ক্ষতির বিশ্লেষণ সহজে দেখুন।</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                        <div class="why-choose-card">
                            <div class="feature-img-wrapper">
                                <i class="fa fa-headset" style="font-size: 32px; color: #f59e0b;"></i>
                            </div>
                            <h4 class="feature-title">২৪/৭ সার্বক্ষণিক সাপোর্ট</h4>
                            <p class="feature-desc">যেকোনো তথ্য ও সহযোগিতার জন্য আমাদের অভিজ্ঞ সাপোর্ট টিম সর্বদা আপনার পাশে রয়েছে।</p>
                        </div>
                    </div>
                </div>
                @endif

            </div><!-- Container /- -->
        </div><!-- Why Choose Section /- -->



        <!-- Portfolio Section -->
        <div id="Gallery" class="container-fluid no-left-padding no-right-padding portfolio-section" style="padding-top: 60px; padding-bottom: 60px; background: #fff;">
            <!-- Container -->
            <div class="container text-center">
                <!-- Section Header -->
                <div class="section-header mb-5">
                    <div class="section-title">
                        <h3 style="font-size: 30px; font-weight: 700; color: #0f172a;"><span style="color: #1b88ce;">প্রোডাক্ট </span>গ্যালারি</h3>
                    </div>
                    <p style="color: #64748b; font-size: 15px;">আমাদের বিভিন্ন কৃষি পণ্য ও স্টকের উচ্চমানসম্পন্ন সেরা ছবিসমূহ</p>
                </div><!-- Section Header /- -->

                <!-- Gallery List -->
                <div class="row gallery-list">

                    @php
                    $gallery_images = App\Models\StockGallery::take(9)->get();
                    @endphp

                    @foreach ($gallery_images as $image)
                    <div class="col-lg-4 col-md-6 col-sm-6 col-6 gallery-box mb-4">
                        <div class="gallery-content rounded-4 shadow-sm" style="border-radius: 12px; overflow: hidden; background: #ffffff; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; min-height: 220px; position: relative;">
                            <i style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; padding: 8px;">
                                <img src="{{ asset('upload/stock_images') }}/{{ $image->image }}" alt="Gallery" style="max-height: 220px; max-width: 100%; width: auto; height: auto; object-fit: contain; display: block; margin: auto;" />
                            </i>
                            <div class="gallery-detail">
                                <a class="zoom" href="{{ asset('upload/stock_images') }}/{{ $image->image }}">
                                    <i class="fa fa-search"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach



                </div><!-- Gallery List /- -->

                <div class="mt-4">
                    <a href="{{ route('gallery') }}" class="btn btn-outline-primary px-5 py-2 fw-bold" style="border-color: #1b88ce; color: #1b88ce; border-radius: 25px; text-decoration: none; display: inline-block;">সকল গ্যালারি পিকচার দেখুন <i class="fa fa-chevron-right ms-1"></i></a>
                </div>
            </div><!-- Container /- -->
        </div><!-- Portfolio Section /- -->




        <!-- Contact-us section -->
        <div id="Contact" class="container-fluid contact-us" style="padding-top: 60px; padding-bottom: 60px; background: #f8fafc;">
            <div class="container">
                <!-- Section Header -->
                <div class="section-header section-header2 text-center mb-5">
                    <div class="section-title">
                        <h3 style="font-size: 30px; font-weight: 700; color: #0f172a;"><span style="color: #1b88ce;">যোগাযোগ </span>করুন</h3>
                    </div>
                    <p style="color: #64748b; font-size: 15px;">যেকোনো জিজ্ঞাসা বা তথ্যের জন্য নিচের ফর্মে মেসেজ পাঠান। আমাদের টিম দ্রুত আপনার সাথে যোগাযোগ করবে।</p>
                </div><!-- Section Header /- -->
                <div class="row justify-content-md-center">
                    <div class="col-lg-10 contact-form bg-white p-4 p-md-5 rounded-4 shadow-sm" style="border-radius: 16px; border: 1px solid #e2e8f0;">
                        <form class="row">
                            <div class="col-md-6 form-group mb-3">
                                <input type="text" class="form-control py-3" placeholder="আপনার নাম *" name="contact-name" id="input_name" style="border-radius: 8px; font-size: 14px;" />
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <input type="text" class="form-control py-3" placeholder="ফোন নম্বর *" name="contact-phone" id="input_phone" style="border-radius: 8px; font-size: 14px;" />
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <input type="text" class="form-control py-3" placeholder="ইমেইল এড্রেস" name="contact-email" id="input_email" style="border-radius: 8px; font-size: 14px;" />
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <input type="text" class="form-control py-3" placeholder="বার্তা বিষয় (Subject)" name="contact-subject" id="input_subject" style="border-radius: 8px; font-size: 14px;" />
                            </div>
                            <div class="col-md-12 form-group mb-4">
                                <textarea class="form-control" placeholder="আপনার বার্তাটি লিখুন..." name="textarea-message" id="textarea_message" rows="5" style="border-radius: 8px; font-size: 14px;"></textarea>
                            </div>
                            <div class="col-md-12 text-center">
                                <button id="btn_submit" name="submit" class="btn btn-primary btn-lg px-5 py-3 shadow" style="background: linear-gradient(135deg, #1b88ce, #1469a0); border: none; font-weight: 600; border-radius: 30px; color: #fff;">মেসেজ পাঠান <i class="fa fa-paper-plane ms-2"></i></button>
                            </div>
                            <div id="alert-msg" class="alert-msg"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- Contact-us section /- -->


    </main>

</div>

@endsection
