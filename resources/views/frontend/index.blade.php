@extends('layouts.frontend.app')

@section('content')

<div class="main-container">

    <main class="site-main">

        <!-- Slider Section -->
        <div id="home-slider-1_wrapper" class="rev_slider_wrapper fullwidthbanner-container"
            data-alias="bb-homeslider-1" data-source="gallery">
            <!-- START REVOLUTION SLIDER 5.4.1 fullwidth mode -->
            <div id="home-slider-1" class="rev_slider fullwidthabanner" style="display:none;" data-version="5.4.1">
                <ul>

                    <!-- SLIDE  -->
                    <li data-index="rs-18"
                        data-transition="random-static,random-premium,random,scaledownfromright,scaledownfromleft,scaledownfromtop,scaledownfrombottom,zoomout,zoomin,slotzoom-horizontal,slotzoom-vertical"
                        data-slotamount="default,default,default,default,default,default,default,default,default,default,default,default"
                        data-hideafterloop="0" data-hideslideonmobile="off" data-randomtransition="on"
                        data-easein="default,default,default,default,default,default,default,default,default,default,default,default"
                        data-easeout="default,default,default,default,default,default,default,default,default,default,default,default"
                        data-masterspeed="default,default,default,default,default,default,default,default,default,default,default,default"
                        data-thumb="{{ asset('frontend/assets') }}/slide-1-100x50.html"
                        data-rotate="0,0,0,0,0,0,0,0,0,0,0,0" data-saveperformance="off" class="slider-shape"
                        data-title="Slide" data-param1="" data-param2="" data-param3="" data-param4="" data-param5=""
                        data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
                        <!-- MAIN IMAGE -->
                        <img src="{{ asset('upload/slider') }}/{{ setting('slider1_img') }}" alt="" title="slide-1" width="1920"
                            height="820" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat"
                            class="rev-slidebg" data-no-retina>
                        <!-- LAYERS -->

                        <!-- LAYER NR. 1 -->
                        <div class="tp-caption tp-layer-selectable" id="slide-18-layer-1"
                            data-x="['left','left','left','left']" data-hoffset="['375','29','23','13']"
                            data-y="['middle','middle','middle','middle']" data-voffset="['-108','-108','-108','-108']"
                            data-fontsize="['60','60','60','40']" data-lineheight="['68','68','68','50']"
                            data-width="none" data-height="none" data-whitespace="nowrap" data-type="text"
                            data-responsive_offset="on"
                            data-frames='[{"delay":10,"speed":2000,"frame":"0","from":"x:right;","to":"o:1;","ease":"easeInOutExpo"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'
                            data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                            data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                            style="z-index: 5; white-space: nowrap; font-size: 60px; line-height: 68px; font-weight: 700; color: #ffffff; letter-spacing: 1px;font-family:Poppins;text-transform:uppercase;">
                            {{ setting('slider1_text') }}
                        </div>


                        <!-- LAYER NR. 3 -->
                        <div class="tp-caption tp-layer-selectable tp-resizeme" id="slide-18-layer-3"
                            data-x="['left','left','left','left']" data-hoffset="['376','29','23','14']"
                            data-y="['middle','middle','middle','middle']" data-voffset="['35','35','35','21']"
                            data-width="['684','684','684','427']" data-height="['58','58','58','85']"
                            data-whitespace="normal" data-type="text" data-responsive_offset="on"
                            data-frames='[{"delay":10,"speed":1000,"frame":"0","from":"x:[175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:1;","mask":"x:[-100%];y:0;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                            data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                            data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                            style="z-index: 7; min-width: 684px; max-width: 684px; max-width: 58px; max-width: 58px; white-space: normal; font-size: 16px; line-height: 28px; font-weight: 400; color: #d7d2ca; letter-spacing: 0px;font-family:Open Sans;">


                          {{setting('slider1_description')}}


                        </div>

                        <!-- LAYER NR. 4 -->
                        <div class="tp-caption rev-btn slide-btn" id="slide-18-layer-4"
                            data-x="['left','left','left','left']" data-hoffset="['376','31','27','13']"
                            data-y="['top','top','middle','middle']" data-voffset="['506','413','117','110']"
                            data-width="none" data-height="none" data-whitespace="nowrap" data-type="button"
                            data-responsive_offset="on"
                            data-frames='[{"delay":10,"speed":300,"frame":"0","from":"x:-50px;opacity:0;","to":"o:1;","ease":"Power4.easeOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"},{"frame":"hover","speed":"0","ease":"Linear.easeNone","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgb(19,19,19);bg:rgb(255,255,255);bs:solid;bw:0 0 0 0;"}]'
                            data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[10,10,10,10]"
                            data-paddingright="[30,30,30,30]" data-paddingbottom="[10,10,10,10]"
                            data-paddingleft="[30,30,30,30]"
                            style="z-index: 8; white-space: nowrap; font-size: 18px; line-height: 28px; font-weight: 600; color: rgba(255,255,255,1); letter-spacing: 1.26px;font-family:Poppins;text-transform:uppercase;background-color:rgba(0, 0, 0, 0);border-color:rgba(0,0,0,1);outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;cursor:pointer;">

                        </div>

                        <!-- LAYER NR. 5 -->
                        <div class="tp-caption rev-btn  slide-btn slide-btn2" id="slide-18-layer-5"
                            data-x="['left','left','left','left']" data-hoffset="['562','215','216','204']"
                            data-y="['top','top','middle','middle']" data-voffset="['506','414','117','110']"
                            data-width="none" data-height="none" data-whitespace="nowrap" data-type="button"
                            data-responsive_offset="on"
                            data-frames='[{"delay":10,"speed":300,"frame":"0","from":"x:50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"},{"frame":"hover","speed":"0","ease":"Linear.easeNone","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgb(19,19,19);bg:rgb(255,255,255);bs:solid;bw:0 0 0 0;"}]'
                            data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[8,8,8,8]"
                            data-paddingright="[30,30,30,30]" data-paddingbottom="[8,8,8,8]"
                            data-paddingleft="[30,30,30,30]"
                            style="z-index: 9; white-space: nowrap; font-size: 18px; line-height: 28px; font-weight: 600; color: rgba(255,255,255,1); letter-spacing: 1.26px;font-family:Poppins;text-transform:uppercase;background-color:rgba(0, 0, 0, 0);border-color:rgba(0,0,0,1);outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;cursor:pointer;">

                        </div>
                    </li>

                    <!-- SLIDE-2  -->
                    <li data-index="rs-19"
                        data-transition="random-static,random-premium,random,scaledownfromright,scaledownfromleft,scaledownfromtop,scaledownfrombottom,zoomout,zoomin,slotzoom-horizontal,slotzoom-vertical"
                        data-slotamount="default,default,default,default,default,default,default,default,default,default,default,default"
                        data-hideafterloop="0" data-hideslideonmobile="off" data-randomtransition="on"
                        data-easein="default,default,default,default,default,default,default,default,default,default,default,default"
                        data-easeout="default,default,default,default,default,default,default,default,default,default,default,default"
                        data-masterspeed="default,default,default,default,default,default,default,default,default,default,default,default"
                        data-thumb="{{ asset('frontend/assets') }}/slide-1-100x50.jpg"
                        data-rotate="0,0,0,0,0,0,0,0,0,0,0,0" data-saveperformance="off" class="slider-shape"
                        data-title="Slide" data-param1="" data-param2="" data-param3="" data-param4="" data-param5=""
                        data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
                        <!-- MAIN IMAGE -->
                        <img src="{{ asset('upload/slider') }}/{{ setting('slider2_img') }}" alt="" title="slide-1" width="1920"
                            height="820" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat"
                            class="rev-slidebg" data-no-retina>
                        <!-- LAYERS -->

                        <!-- LAYER NR. 6 -->
                        <div class="tp-caption tp-layer-selectable" id="slide-19-layer-1"
                            data-x="['left','left','left','left']" data-hoffset="['375','29','23','13']"
                            data-y="['middle','middle','middle','middle']" data-voffset="['-108','-108','-108','-108']"
                            data-fontsize="['60','60','60','40']" data-lineheight="['68','68','68','50']"
                            data-width="none" data-height="none" data-whitespace="nowrap" data-type="text"
                            data-responsive_offset="on"
                            data-frames='[{"delay":10,"speed":2000,"frame":"0","from":"x:right;","to":"o:1;","ease":"easeInOutExpo"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'
                            data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                            data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                            style="z-index: 5; white-space: nowrap; font-size: 60px; line-height: 68px; font-weight: 700; color: #ffffff; letter-spacing: 1px;font-family:Poppins;text-transform:uppercase;">

                            {{ setting('slider2_text') }}

                        </div>



                        <!-- LAYER NR. 8 -->
                        <div class="tp-caption tp-layer-selectable tp-resizeme" id="slide-19-layer-3"
                            data-x="['left','left','left','left']" data-hoffset="['376','29','23','13']"
                            data-y="['middle','middle','middle','middle']" data-voffset="['35','35','35','21']"
                            data-width="['684','684','684','427']" data-height="['58','58','58','85']"
                            data-whitespace="normal" data-type="text" data-responsive_offset="on"
                            data-frames='[{"delay":10,"speed":1000,"frame":"0","from":"x:[175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:1;","mask":"x:[-100%];y:0;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                            data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                            data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                            style="z-index: 7; min-width: 684px; max-width: 684px; max-width: 58px; max-width: 58px; white-space: normal; font-size: 16px; line-height: 28px; font-weight: 400; color: #d7d2ca; letter-spacing: 0px;font-family:Open Sans;">
                            {{setting('slider2_description')}}
                        </div>

                        <!-- LAYER NR. 9 -->
                        <div class="tp-caption rev-btn  slide-btn" id="slide-19-layer-4"
                            data-x="['left','left','left','left']" data-hoffset="['376','31','27','13']"
                            data-y="['top','top','middle','middle']" data-voffset="['506','413','117','110']"
                            data-width="none" data-height="none" data-whitespace="nowrap" data-type="button"
                            data-responsive_offset="on"
                            data-frames='[{"delay":10,"speed":300,"frame":"0","from":"x:-50px;opacity:0;","to":"o:1;","ease":"Power4.easeOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"},{"frame":"hover","speed":"0","ease":"Linear.easeNone","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgb(19,19,19);bg:rgb(255,255,255);bs:solid;bw:0 0 0 0;"}]'
                            data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[10,10,10,10]"
                            data-paddingright="[30,30,30,30]" data-paddingbottom="[10,10,10,10]"
                            data-paddingleft="[30,30,30,30]"
                            style="z-index: 8; white-space: nowrap; font-size: 18px; line-height: 28px; font-weight: 600; color: rgba(255,255,255,1); letter-spacing: 1.26px;font-family:Poppins;text-transform:uppercase;background-color:rgba(0, 0, 0, 0);border-color:rgba(0,0,0,1);outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;cursor:pointer;">

                        </div>

                        <!-- LAYER NR. 10 -->
                        <div class="tp-caption rev-btn  slide-btn slide-btn2" id="slide-19-layer-5"
                            data-x="['left','left','left','left']" data-hoffset="['562','215','216','204']"
                            data-y="['top','top','middle','middle']" data-voffset="['506','414','117','110']"
                            data-width="none" data-height="none" data-whitespace="nowrap" data-type="button"
                            data-responsive_offset="on"
                            data-frames='[{"delay":10,"speed":300,"frame":"0","from":"x:50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"},{"frame":"hover","speed":"0","ease":"Linear.easeNone","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgb(19,19,19);bg:rgb(255,255,255);bs:solid;bw:0 0 0 0;"}]'
                            data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[8,8,8,8]"
                            data-paddingright="[30,30,30,30]" data-paddingbottom="[8,8,8,8]"
                            data-paddingleft="[30,30,30,30]"
                            style="z-index: 9; white-space: nowrap; font-size: 18px; line-height: 28px; font-weight: 600; color: rgba(255,255,255,1); letter-spacing: 1.26px;font-family:Poppins;text-transform:uppercase;background-color:rgba(0, 0, 0, 0);border-color:rgba(0,0,0,1);outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;cursor:pointer;">

                        </div>
                    </li>

                    {{-- <!-- SLIDE-3  -->
                    <li data-index="rs-19"
                        data-transition="random-static,random-premium,random,scaledownfromright,scaledownfromleft,scaledownfromtop,scaledownfrombottom,zoomout,zoomin,slotzoom-horizontal,slotzoom-vertical"
                        data-slotamount="default,default,default,default,default,default,default,default,default,default,default,default"
                        data-hideafterloop="0" data-hideslideonmobile="off" data-randomtransition="on"
                        data-easein="default,default,default,default,default,default,default,default,default,default,default,default"
                        data-easeout="default,default,default,default,default,default,default,default,default,default,default,default"
                        data-masterspeed="default,default,default,default,default,default,default,default,default,default,default,default"
                        data-thumb="{{ asset('frontend/assets') }}/slide-1-100x50.jpg"
                        data-rotate="0,0,0,0,0,0,0,0,0,0,0,0" data-saveperformance="off" class="slider-shape"
                        data-title="Slide" data-param1="" data-param2="" data-param3="" data-param4="" data-param5=""
                        data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
                        <!-- MAIN IMAGE -->
                        <img src="{{ asset('upload/slider') }}/{{ setting('slider3_img') }}" alt="" title="slide-1" width="1920"
                            height="820" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat"
                            class="rev-slidebg" data-no-retina>
                        <!-- LAYERS -->

                        <!-- LAYER NR. 6 -->
                        <div class="tp-caption tp-layer-selectable" id="slide-20-layer-1"
                            data-x="['left','left','left','left']" data-hoffset="['375','29','23','13']"
                            data-y="['middle','middle','middle','middle']" data-voffset="['-108','-108','-108','-108']"
                            data-fontsize="['60','60','60','40']" data-lineheight="['68','68','68','50']"
                            data-width="none" data-height="none" data-whitespace="nowrap" data-type="text"
                            data-responsive_offset="on"
                            data-frames='[{"delay":10,"speed":2000,"frame":"0","from":"x:right;","to":"o:1;","ease":"easeInOutExpo"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'
                            data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                            data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                            style="z-index: 5; white-space: nowrap; font-size: 60px; line-height: 68px; font-weight: 700; color: #ffffff; letter-spacing: 1px;font-family:Poppins;text-transform:uppercase;">

                            {{ setting('slider3_text') }}

                        </div>



                        <!-- LAYER NR. 8 -->
                        <div class="tp-caption tp-layer-selectable tp-resizeme" id="slide-20-layer-3"
                            data-x="['left','left','left','left']" data-hoffset="['376','29','23','13']"
                            data-y="['middle','middle','middle','middle']" data-voffset="['35','35','35','21']"
                            data-width="['684','684','684','427']" data-height="['58','58','58','85']"
                            data-whitespace="normal" data-type="text" data-responsive_offset="on"
                            data-frames='[{"delay":10,"speed":1000,"frame":"0","from":"x:[175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:1;","mask":"x:[-100%];y:0;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                            data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                            data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                            style="z-index: 7; min-width: 684px; max-width: 684px; max-width: 58px; max-width: 58px; white-space: normal; font-size: 16px; line-height: 28px; font-weight: 400; color: #d7d2ca; letter-spacing: 0px;font-family:Open Sans;">
                            {{setting('slider3_description')}}
                        </div>

                        <!-- LAYER NR. 9 -->
                        <div class="tp-caption rev-btn  slide-btn" id="slide-20-layer-4"
                            data-x="['left','left','left','left']" data-hoffset="['376','31','27','13']"
                            data-y="['top','top','middle','middle']" data-voffset="['506','413','117','110']"
                            data-width="none" data-height="none" data-whitespace="nowrap" data-type="button"
                            data-responsive_offset="on"
                            data-frames='[{"delay":10,"speed":300,"frame":"0","from":"x:-50px;opacity:0;","to":"o:1;","ease":"Power4.easeOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"},{"frame":"hover","speed":"0","ease":"Linear.easeNone","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgb(19,19,19);bg:rgb(255,255,255);bs:solid;bw:0 0 0 0;"}]'
                            data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[10,10,10,10]"
                            data-paddingright="[30,30,30,30]" data-paddingbottom="[10,10,10,10]"
                            data-paddingleft="[30,30,30,30]"
                            style="z-index: 8; white-space: nowrap; font-size: 18px; line-height: 28px; font-weight: 600; color: rgba(255,255,255,1); letter-spacing: 1.26px;font-family:Poppins;text-transform:uppercase;background-color:rgba(0, 0, 0, 0);border-color:rgba(0,0,0,1);outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;cursor:pointer;">

                        </div>

                        <!-- LAYER NR. 10 -->
                        <div class="tp-caption rev-btn  slide-btn slide-btn2" id="slide-20-layer-5"
                            data-x="['left','left','left','left']" data-hoffset="['562','215','216','204']"
                            data-y="['top','top','middle','middle']" data-voffset="['506','414','117','110']"
                            data-width="none" data-height="none" data-whitespace="nowrap" data-type="button"
                            data-responsive_offset="on"
                            data-frames='[{"delay":10,"speed":300,"frame":"0","from":"x:50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"},{"frame":"hover","speed":"0","ease":"Linear.easeNone","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgb(19,19,19);bg:rgb(255,255,255);bs:solid;bw:0 0 0 0;"}]'
                            data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[8,8,8,8]"
                            data-paddingright="[30,30,30,30]" data-paddingbottom="[8,8,8,8]"
                            data-paddingleft="[30,30,30,30]"
                            style="z-index: 9; white-space: nowrap; font-size: 18px; line-height: 28px; font-weight: 600; color: rgba(255,255,255,1); letter-spacing: 1.26px;font-family:Poppins;text-transform:uppercase;background-color:rgba(0, 0, 0, 0);border-color:rgba(0,0,0,1);outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;cursor:pointer;">

                        </div>
                    </li>


                    <!-- SLIDE-4  -->
                    <li data-index="rs-19"
                        data-transition="random-static,random-premium,random,scaledownfromright,scaledownfromleft,scaledownfromtop,scaledownfrombottom,zoomout,zoomin,slotzoom-horizontal,slotzoom-vertical"
                        data-slotamount="default,default,default,default,default,default,default,default,default,default,default,default"
                        data-hideafterloop="0" data-hideslideonmobile="off" data-randomtransition="on"
                        data-easein="default,default,default,default,default,default,default,default,default,default,default,default"
                        data-easeout="default,default,default,default,default,default,default,default,default,default,default,default"
                        data-masterspeed="default,default,default,default,default,default,default,default,default,default,default,default"
                        data-thumb="{{ asset('frontend/assets') }}/slide-1-100x50.jpg"
                        data-rotate="0,0,0,0,0,0,0,0,0,0,0,0" data-saveperformance="off" class="slider-shape"
                        data-title="Slide" data-param1="" data-param2="" data-param3="" data-param4="" data-param5=""
                        data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
                        <!-- MAIN IMAGE -->
                        <img src="{{ asset('upload/slider') }}/{{ setting('slider4_img') }}" alt="" title="slide-1" width="1920"
                            height="820" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat"
                            class="rev-slidebg" data-no-retina>
                        <!-- LAYERS -->

                        <!-- LAYER NR. 6 -->
                        <div class="tp-caption tp-layer-selectable" id="slide-21-layer-1"
                            data-x="['left','left','left','left']" data-hoffset="['375','29','23','13']"
                            data-y="['middle','middle','middle','middle']" data-voffset="['-108','-108','-108','-108']"
                            data-fontsize="['60','60','60','40']" data-lineheight="['68','68','68','50']"
                            data-width="none" data-height="none" data-whitespace="nowrap" data-type="text"
                            data-responsive_offset="on"
                            data-frames='[{"delay":10,"speed":2000,"frame":"0","from":"x:right;","to":"o:1;","ease":"easeInOutExpo"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'
                            data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                            data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                            style="z-index: 5; white-space: nowrap; font-size: 60px; line-height: 68px; font-weight: 700; color: #ffffff; letter-spacing: 1px;font-family:Poppins;text-transform:uppercase;">

                            {{ setting('slider4_text') }}

                        </div>



                        <!-- LAYER NR. 8 -->
                        <div class="tp-caption tp-layer-selectable tp-resizeme" id="slide-21-layer-3"
                            data-x="['left','left','left','left']" data-hoffset="['376','29','23','13']"
                            data-y="['middle','middle','middle','middle']" data-voffset="['35','35','35','21']"
                            data-width="['684','684','684','427']" data-height="['58','58','58','85']"
                            data-whitespace="normal" data-type="text" data-responsive_offset="on"
                            data-frames='[{"delay":10,"speed":1000,"frame":"0","from":"x:[175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:1;","mask":"x:[-100%];y:0;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                            data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                            data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                            style="z-index: 7; min-width: 684px; max-width: 684px; max-width: 58px; max-width: 58px; white-space: normal; font-size: 16px; line-height: 28px; font-weight: 400; color: #d7d2ca; letter-spacing: 0px;font-family:Open Sans;">
                            {{setting('slider4_description')}}
                        </div>

                        <!-- LAYER NR. 9 -->
                        <div class="tp-caption rev-btn  slide-btn" id="slide-21-layer-4"
                            data-x="['left','left','left','left']" data-hoffset="['376','31','27','13']"
                            data-y="['top','top','middle','middle']" data-voffset="['506','413','117','110']"
                            data-width="none" data-height="none" data-whitespace="nowrap" data-type="button"
                            data-responsive_offset="on"
                            data-frames='[{"delay":10,"speed":300,"frame":"0","from":"x:-50px;opacity:0;","to":"o:1;","ease":"Power4.easeOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"},{"frame":"hover","speed":"0","ease":"Linear.easeNone","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgb(19,19,19);bg:rgb(255,255,255);bs:solid;bw:0 0 0 0;"}]'
                            data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[10,10,10,10]"
                            data-paddingright="[30,30,30,30]" data-paddingbottom="[10,10,10,10]"
                            data-paddingleft="[30,30,30,30]"
                            style="z-index: 8; white-space: nowrap; font-size: 18px; line-height: 28px; font-weight: 600; color: rgba(255,255,255,1); letter-spacing: 1.26px;font-family:Poppins;text-transform:uppercase;background-color:rgba(0, 0, 0, 0);border-color:rgba(0,0,0,1);outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;cursor:pointer;">

                        </div>

                        <!-- LAYER NR. 10 -->
                        <div class="tp-caption rev-btn  slide-btn slide-btn2" id="slide-21-layer-5"
                            data-x="['left','left','left','left']" data-hoffset="['562','215','216','204']"
                            data-y="['top','top','middle','middle']" data-voffset="['506','414','117','110']"
                            data-width="none" data-height="none" data-whitespace="nowrap" data-type="button"
                            data-responsive_offset="on"
                            data-frames='[{"delay":10,"speed":300,"frame":"0","from":"x:50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"},{"frame":"hover","speed":"0","ease":"Linear.easeNone","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgb(19,19,19);bg:rgb(255,255,255);bs:solid;bw:0 0 0 0;"}]'
                            data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[8,8,8,8]"
                            data-paddingright="[30,30,30,30]" data-paddingbottom="[8,8,8,8]"
                            data-paddingleft="[30,30,30,30]"
                            style="z-index: 9; white-space: nowrap; font-size: 18px; line-height: 28px; font-weight: 600; color: rgba(255,255,255,1); letter-spacing: 1.26px;font-family:Poppins;text-transform:uppercase;background-color:rgba(0, 0, 0, 0);border-color:rgba(0,0,0,1);outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;cursor:pointer;">

                        </div>
                    </li> --}}


                </ul>
                <div class="tp-bannertimer tp-bottom" style="visibility: hidden !important;"></div>
            </div>
        </div><!-- Slider Section /- -->

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
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm rounded-4" style="transition: all 0.3s ease; background: #fff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0;">
                                <div class="position-relative">
                                    <img src="{{ $image_path }}" class="card-img-top" alt="{{ $stock->stock_name }}" style="height: 200px; object-fit: cover; width: 100%;">
                                    <span class="badge position-absolute top-0 end-0 m-3 px-3 py-2 shadow-sm" style="background: #10b981; color: #fff; border-radius: 20px; font-weight: 600; font-size: 12px;">
                                        <i class="fa fa-line-chart me-1"></i> লাইভ দর
                                    </span>
                                </div>
                                <div class="card-body p-4">
                                    <h4 class="fw-bold mb-2 text-dark" style="font-size: 20px; font-weight: 700; color: #1e293b;">{{ $stock->stock_name }}</h4>
                                    <p class="text-muted mb-3" style="font-size: 14px;">উপলব্ধ পরিমাণ: <span class="fw-bold text-dark">{{ $stock->stock_quantity }}</span></p>

                                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3 mb-3" style="background: #f1f5f9; border-radius: 10px;">
                                        <div>
                                            <small class="text-muted d-block" style="font-size: 12px;">বিক্রয় মূল্য (Selling)</small>
                                            <strong class="text-success" style="font-size: 18px; color: #16a34a;">৳{{ number_format($latest_selling) }}</strong>
                                        </div>
                                        <div style="width: 1px; height: 32px; background: #cbd5e1;"></div>
                                        <div>
                                            <small class="text-muted d-block" style="font-size: 12px;">ক্রয় মূল্য (Buying)</small>
                                            <strong class="text-primary" style="font-size: 18px; color: #1b88ce;">৳{{ number_format($latest_buying) }}</strong>
                                        </div>
                                    </div>

                                    <a href="{{ route('stock.detials', $stock->id) }}" class="btn btn-outline-primary w-100 fw-bold py-2" style="border-color: #1b88ce; color: #1b88ce; border-radius: 8px; font-size: 14px; text-decoration: none; display: block; text-align: center;">
                                        বিস্তারিত তথ্য ও গ্রাফ <i class="fa fa-arrow-right ms-1"></i>
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

        <!-- Why Choose Section -->
        <div class="container-fluid no-left-padding no-right-padding why-choose-section why-choose-style-1" style="padding-top: 60px; padding-bottom: 60px; background: #fff;">
            <!-- Container -->
            <div class="container">

                <!-- Section Header -->
                <div class="section-header section-header-1 text-center mb-5">
                    <h3 style="font-size: 30px; font-weight: 700; color: #0f172a;"><span style="color: #1b88ce;">কেন </span>কৃষি পরিবার বেছে নেবেন?</h3>
                    <p style="color: #64748b; font-size: 15px;">কৃষি ও ব্যবসা খাতের জন্য আমাদের বিশ্বস্ত সেবা ও আধুনিক প্ল্যাটফর্ম</p>
                </div><!-- Section Header /- -->


                <div class="row">
                    @php
                        $all_features = App\Models\Feature::all();
                    @endphp
                    @foreach($all_features as $feature_box)
                    <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                        <div class="why-choose-box p-4 rounded-4 shadow-sm" style="border: 1px solid #e2e8f0; background: #fff; border-radius: 12px; height: 100%;">
                            <i style="color: {{ $feature_box->color ?? '#1b88ce' }}; font-size: 36px; margin-bottom: 15px; display: block;"><i class="fa {{ $feature_box->icon }}"></i></i>
                            <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 10px;">{{ $feature_box->title }}</h3>
                            <p style="color: #64748b; font-size: 14px; line-height: 1.6;">{{ $feature_box->description }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
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
                        <div class="gallery-content rounded-4 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                            <i>
                                <img src="{{ asset('upload/stock_images') }}/{{ $image->image }}" alt="Gallery" style="height: 220px; object-fit: cover; width: 100%;" />
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
