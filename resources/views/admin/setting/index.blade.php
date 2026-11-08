@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Setting</h4>
                </div>

            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">


                <!-- /.col -->
                <div class="col-xl-12 col-lg-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Site Setting</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <form class="form-horizontal form-bordered" method="POST"
                                action="{{ route('setting.update') }}" enctype="multipart/form-data">

                                @csrf

                                <div class="row">


                                    <div class="col-xl-6">


                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-6 control-label text-lg-start pt-2"
                                                    for="title">Website Title (টাইটেল)</label>
                                                <div class="col-lg-6">
                                                    <input type="text" class="form-control" id="title" name="title"
                                                        title="Enter Site Title" value="{{ setting('title') }}">
                                                    <span class="text-danger">
                                                        @error('title')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-6 control-label text-lg-start pt-2 text-primary fw-bold"
                                                    for="top_notice"><i class="fa fa-bullhorn text-warning me-1"></i> Top Marquee Notice (শীর্ষ স্ক্রোলিং নোটিশ)</label>
                                                <div class="col-lg-6">
                                                    <textarea class="form-control" id="top_notice" name="top_notice" rows="2"
                                                        placeholder="ওয়েবসাইটের শীর্ষে স্ক্রোল করার নোটিশ বা ঘোষণা লিখুন...">{{ setting('top_notice') ?: (setting('notice') ?: 'কৃষি পরিবারে স্বাগতম! আধুনিক ও নিরাপদ কৃষি বিনিয়োগের নির্ভরযোগ্য ডিজিটাল প্ল্যাটফর্ম।') }}</textarea>
                                                    <small class="text-muted">এই নোটিশটি ওয়েবসাইটের একদম উপরে স্বয়ংক্রিয়ভাবে স্ক্রোল করবে।</small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- ============================================== -->
                                        <!-- 100 Font Families Selector & Live Font Preview -->
                                        <!-- ============================================== -->
                                        <div id="site_font_family_card" class="form-group pb-4 p-3 mb-4 rounded-3" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 2px solid #ea580c; box-shadow: 0 6px 20px rgba(234, 88, 12, 0.12); border-radius: 14px;">
                                            <div class="row align-items-center mb-2">
                                                <div class="col-12">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <label class="control-label text-dark fw-bold mb-0" for="site_font_family" style="font-size: 15px;">
                                                            <i class="fa fa-font text-warning me-2"></i> Website Font Family (১০০+ ফন্ট ফ্যামিলি সিলেক্টর)
                                                        </label>
                                                        <span class="badge bg-primary px-3 py-1 font-size-12">১০০ প্রকার ফন্ট উপলভ্য</span>
                                                    </div>
                                                    <p class="text-muted font-size-12 mb-3">
                                                        এডমিন থেকে যে কোনো ফন্ট সিলেক্ট করলে ওয়েবসাইটের বাংলা ও ইংরেজি সমস্ত টাইপোগ্রাফি সাথে সাথে সেই ফন্টে পরিবর্তিত হবে।
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    @php
                                                        $currentFont = setting('site_font_family') ?: 'Hind Siliguri';
                                                        $fontList = config('fonts.families', []);
                                                        $groupedFonts = [];
                                                        foreach ($fontList as $fName => $fMeta) {
                                                            $groupedFonts[$fMeta['category']][$fName] = $fMeta;
                                                        }
                                                    @endphp
                                                    <select class="form-select form-control font-select-picker" id="site_font_family" name="site_font_family" style="font-size: 15px; height: 46px; border-radius: 10px; border: 1.5px solid #94a3b8; font-weight: 600;">
                                                        @foreach($groupedFonts as $category => $items)
                                                            <optgroup label="── {{ $category }} ({{ count($items) }}) ──">
                                                                @foreach($items as $fName => $fMeta)
                                                                    <option value="{{ $fName }}" {{ $currentFont === $fName ? 'selected' : '' }} data-query="{{ $fMeta['query'] ?? '' }}" data-fallback="{{ $fMeta['fallback'] }}">
                                                                        {{ $fName }} {{ !empty($fMeta['bengali']) ? '★ [বাংলা সাপোর্টেড]' : '' }}
                                                                    </option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Live Font Preview Box -->
                                            <div class="mt-3 p-3 rounded-2" id="fontPreviewCard" style="background: #ffffff; border: 1.5px dashed #ea580c; border-radius: 10px;">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <small class="text-muted fw-bold font-size-11 text-uppercase">
                                                        <i class="fa fa-eye text-info me-1"></i> লাইভ ফন্ট প্রিভিউ (Live Preview):
                                                    </small>
                                                    <span id="previewFontBadge" class="badge bg-warning text-dark font-size-11">{{ $currentFont }}</span>
                                                </div>
                                                <div id="previewSampleText" style="font-size: 18px; line-height: 1.5; color: #1e293b; transition: font-family 0.2s ease;">
                                                    আমরা আধুনিক ও নিরাপদ কৃষি সেবায় বিশ্বাসী - ekrishisheba Digital Platform 2026
                                                </div>
                                                <div id="previewSampleNumbers" class="text-muted font-size-14 mt-1">
                                                    ১২৩৪৫৬৭৮৯০ / 1234567890 (Aa Bb Cc Dd Ee Ff Gg)
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-3 control-label text-lg-start pt-2"
                                                    for="logo">Logo</label>
                                                <div class="col-lg-5">
                                                    <input type="file" class="form-control" id="logo" name="logo">
                                                    <span class="text-danger">
                                                        @error('logo')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                                <div class="col-lg-4">
                                                    <img src="{{ asset('upload/images/backend/logo') }}/{{ setting('logo') }}"
                                                        alt="" style="width:30%; margin-top:5px">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-3 control-label text-lg-start pt-2"
                                                    for="favicon">Favicon</label>
                                                <div class="col-lg-5">
                                                    <input type="file" class="form-control" id="favicon" name="favicon">
                                                    <span class="text-danger">
                                                        @error('favicon')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                                <div class="col-lg-4">
                                                    <img src="{{ asset('upload/images/backend/logo') }}/{{ setting('favicon') }}"
                                                        alt="" style="width:30%; margin-top:5px">
                                                </div>
                                            </div>


                                        </div>









                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-5 control-label text-lg-start pt-2"
                                                    for="address1">Address Line one</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="address1"
                                                        name="address1" title="Enter Address"
                                                        value="{{ setting('address1') }}">
                                                    <span class="text-danger">
                                                        @error('address1')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>




                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-5 control-label text-lg-start pt-2"
                                                    for="address2">Address Line tow</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="address2"
                                                        name="address2" title="Enter Address"
                                                        value="{{ setting('address2') }}">
                                                    <span class="text-danger">
                                                        @error('address2')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>




                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-5 control-label text-lg-start pt-2"
                                                    for="phone1">Phone one</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="phone1"
                                                        name="phone1" title="Enter Phone one"
                                                        value="{{ setting('phone1') }}">
                                                    <span class="text-danger">
                                                        @error('phone1')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>




                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-5 control-label text-lg-start pt-2"
                                                    for="facbook_link">Facebook Link</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="facbook_link"
                                                        name="facbook_link" title="Facebook Link"
                                                        value="{{ setting('facbook_link') }}">
                                                    <span class="text-danger">
                                                        @error('facbook_link')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-5 control-label text-lg-start pt-2"
                                                    for="linkedin_link">Linkedin Link</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="linkedin_link"
                                                        name="linkedin_link" title="Enter Phone one"
                                                        value="{{ setting('linkedin_link') }}">
                                                    <span class="text-danger">
                                                        @error('linkedin_link')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-5 control-label text-lg-start pt-2"
                                                    for="twitter_link">Twitter Link</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="twitter_link"
                                                        name="twitter_link" title="Enter Phone one"
                                                        value="{{ setting('twitter_link') }}">
                                                    <span class="text-danger">
                                                        @error('twitter_link')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <!-- /.col -->









                                    <div class="col-xl-6">
                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="phone2">Phone tow</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="phone2"
                                                        name="phone2" title="Enter Phone tow"
                                                        value="{{ setting('phone2') }}">
                                                    <span class="text-danger">
                                                        @error('phone2')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>








                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="email1">Email Address one</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="email1"
                                                        name="email1" title="Enter Email Address"
                                                        value="{{ setting('email1') }}">
                                                    <span class="text-danger">
                                                        @error('email1')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>






                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="email2">Enter Address</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="email2"
                                                        name="email2" title="Enter eMAIL Address"
                                                        value="{{ setting('email2') }}">
                                                    <span class="text-danger">
                                                        @error('email2')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="about_us_text">About text</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="about_us_text"
                                                        name="about_us_text" title="About text"
                                                        value="{{ setting('about_us_text') }}">
                                                    <span class="text-danger">
                                                        @error('about_us_text')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="about_us_text2">About text tow</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="about_us_text2"
                                                        name="about_us_text2" title="About text tow"
                                                        value="{{ setting('about_us_text2') }}">
                                                    <span class="text-danger">
                                                        @error('about_us_text2')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col (right column) -->

                                    <!-- ============================================== -->
                                    <!-- Google Map Embed Location Setting Card -->
                                    <!-- ============================================== -->
                                    <div id="google_map_card" class="col-12 my-3">
                                        <div class="card border-2 shadow-sm rounded-4" style="border: 2px solid #ea580c !important; border-radius: 14px; background: linear-gradient(135deg, #ffffff 0%, #fffbf5 100%);">
                                            <div class="card-header bg-white py-3 px-4 d-flex align-items-center justify-content-between border-bottom" style="border-radius: 14px 14px 0 0;">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px; min-width: 44px; background: #fee2e2; color: #dc2626;">
                                                        <i class="fa fa-map-marker fs-4"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="fw-bold mb-1 text-dark">
                                                            গুগল ম্যাপ লোকেশন সেটিংস (Google Map Embed Location)
                                                        </h5>
                                                        <p class="text-muted mb-0 font-size-12">
                                                            কন্টাক্ট পেইজে (Contact Us) আপনার অফিসের সুনির্দিষ্ট লোকেশন ম্যাপে প্রদর্শনের জন্য এখানে গুগল ম্যাপের এমবেড কোড (Embed HTML Code) দিন।
                                                        </p>
                                                    </div>
                                                </div>
                                                <span class="badge bg-danger px-3 py-2 font-size-12">
                                                    <i class="fa fa-globe me-1"></i> কন্টাক্ট পেইজ ম্যাপ
                                                </span>
                                            </div>

                                            <div class="card-body p-4">
                                                <div class="row g-4 align-items-start">
                                                    <div class="col-lg-7">
                                                        <label class="form-label fw-bold text-dark mb-2" for="google_map_embed">
                                                            <i class="fa fa-code text-danger me-1"></i> গুগল ম্যাপ এমবেড কোড (Iframe Embed Code / Map URL):
                                                        </label>
                                                        <textarea
                                                            class="form-control font-monospace"
                                                            id="google_map_embed"
                                                            name="google_map_embed"
                                                            rows="5"
                                                            placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'
                                                            style="font-size: 13px; line-height: 1.5; border: 1.5px solid #cbd5e1; border-radius: 10px;"
                                                        >{{ setting('google_map_embed') }}</textarea>

                                                        <div class="mt-3 p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                                            <h6 class="fw-bold text-slate-800 mb-2 font-size-13">
                                                                <i class="fa fa-question-circle text-primary me-1"></i> গুগল ম্যাপ থেকে এমবেড কোড পাওয়ার নিয়ম:
                                                            </h6>
                                                            <ol class="mb-0 text-muted font-size-12 ps-3" style="line-height: 1.8;">
                                                                <li><a href="https://www.google.com/maps" target="_blank" class="fw-bold text-primary">Google Maps (maps.google.com)</a> ওয়েবসাইটে গিয়ে আপনার অফিস/প্রতিষ্ঠানের ঠিকানা সার্চ করুন।</li>
                                                                <li>লোকেশন আসার পর <strong>Share (শেয়ার)</strong> বাটনে ক্লিক করুন।</li>
                                                                <li>পপ-আপ থেকে <strong>Embed a map (ম্যাপ এমবেড করুন)</strong> ট্যাবে যান।</li>
                                                                <li><strong>COPY HTML</strong> বাটনে ক্লিক করে কোডটি কপি করুন এবং উপরের বক্সে পেস্ট করে নিচে <strong>Update Settings</strong> চাপুন।</li>
                                                            </ol>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-5">
                                                        <label class="form-label fw-bold text-dark mb-2">
                                                            <i class="fa fa-eye text-success me-1"></i> লাইভ ম্যাপ প্রিভিউ (Live Map Preview):
                                                        </label>
                                                        <div id="admin_map_preview_box" class="rounded-3 border overflow-hidden" style="min-height: 250px; height: 250px; background: #f8fafc; position: relative;">
                                                            <div class="d-flex flex-column align-items-center justify-content-center h-100 p-4 text-center text-muted">
                                                                <i class="fa fa-map-o fs-1 text-secondary mb-2"></i>
                                                                <span>লোকেশন লোড হচ্ছে...</span>
                                                            </div>
                                                        </div>
                                                        <small class="text-muted d-block mt-2">
                                                            বামে কোড পেস্ট করলে এখানে সরাসরি লাইভ প্রিভিউ দেখতে পাবেন।
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 my-3">
                                        <hr>
                                        <h4 class="text-primary fw-bold mb-3"><i class="fa fa-sliders me-2"></i> Footer & Branding Settings (ফুটার ও ব্র্যান্ডিং সেটিংস)</h4>
                                    </div>

                                    <div class="col-xl-6 mb-3">
                                        <div class="form-group pb-3">
                                            <label class="form-label fw-bold" for="footer_about">Footer About Text (ফুটারে কোম্পানি পরিচিতি)</label>
                                            <textarea class="form-control" id="footer_about" name="footer_about" rows="3" placeholder="ফুটার সম্পর্কে টেক্সট...">{{ setting('footer_about', 'কৃষি পরিবার - আপনার কৃষি সেবা, স্টকের সঠিক মূল্য এবং আর্থিক সমৃদ্ধির নির্ভরযোগ্য ডিজিটাল মাধ্যম।') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-xl-6 mb-3">
                                        <div class="form-group pb-3">
                                            <label class="form-label fw-bold" for="footer_copyright">Footer Copyright Text (কপিরাইট টেক্সট)</label>
                                            <input type="text" class="form-control" id="footer_copyright" name="footer_copyright" value="{{ setting('footer_copyright', '© '.date('Y').' iKrishiPoribar. All Rights Reserved.') }}" placeholder="© 2026 iKrishiPoribar. All Rights Reserved.">
                                        </div>
                                    </div>

                                    <div class="col-xl-6 mb-3">
                                        <div class="form-group pb-3">
                                            <label class="form-label fw-bold" for="footer_developed_by">Developed By / Sub-tagline (ডেভেলপড বাই / সাব-ট্যাগলাইন)</label>
                                            <input type="text" class="form-control" id="footer_developed_by" name="footer_developed_by" value="{{ setting('footer_developed_by', 'Smart Agriculture & Stock Market Solutions') }}" placeholder="Developed by or Tagline">
                                        </div>
                                    </div>

                                    <div class="col-xl-6 mb-3">
                                        <div class="form-group pb-3">
                                            <label class="form-label fw-bold" for="footer_developed_by_url">Developed By Link URL (ডেভেলপারের ওয়েবসাইট/লিঙ্ক)</label>
                                            <input type="url" class="form-control" id="footer_developed_by_url" name="footer_developed_by_url" value="{{ setting('footer_developed_by_url', '') }}" placeholder="https://yourwebsite.com">
                                        </div>
                                    </div>

                                    <div class="col-xl-6 mb-3">
                                        <div class="form-group pb-3">
                                            <label class="form-label fw-bold" for="instagram_link">Instagram Link</label>
                                            <input type="url" class="form-control" id="instagram_link" name="instagram_link" value="{{ setting('instagram_link', '') }}" placeholder="https://instagram.com/...">
                                        </div>
                                    </div>

                                    <div class="col-xl-6 mb-3">
                                        <div class="form-group pb-3">
                                            <label class="form-label fw-bold" for="youtube_link">YouTube Link</label>
                                            <input type="url" class="form-control" id="youtube_link" name="youtube_link" value="{{ setting('youtube_link', '') }}" placeholder="https://youtube.com/...">
                                        </div>
                                    </div>

                                    <div class="col-12 my-3">
                                        <hr>
                                        <h4 class="text-primary fw-bold mb-3"><i class="fa fa-file-text-o me-2"></i> Page Content Settings (এবাউট, প্রাইভেসি ও টার্মস সেটিংস)</h4>
                                    </div>

                                    <div class="col-xl-12 mb-3">
                                        <div class="form-group pb-3">
                                            <label class="form-label fw-bold" for="about_us_full_text">About Us Details (আমাদের সম্পর্কে বিস্তারিত)</label>
                                            <textarea class="form-control editor" id="about_us_full_text" name="about_us_full_text" rows="5" placeholder="Enter About Us details content...">{{ setting('about_us_full_text') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-xl-12 mb-3">
                                        <div class="form-group pb-3">
                                            <label class="form-label fw-bold" for="terms_conditions">Terms & Conditions (টার্মস এন্ড কন্ডিশনস)</label>
                                            <textarea class="form-control editor" id="terms_conditions" name="terms_conditions" rows="6" placeholder="Enter Terms & Conditions content...">{{ setting('terms_conditions') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-xl-12 mb-3">
                                        <div class="form-group pb-3">
                                            <label class="form-label fw-bold" for="privacy_policy">Privacy Policy (প্রাইভেসি পলিসি)</label>
                                            <textarea class="form-control editor" id="privacy_policy" name="privacy_policy" rows="6" placeholder="Enter Privacy Policy content...">{{ setting('privacy_policy') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group pb-4 text-center">
                                            <button type="submit" class="btn btn-success btn-lg px-5"><i class="fa fa-save me-2"></i> Update Settings</button>
                                        </div>
                                    </div>


                                </div>
                                <!-- /.row -->
                            </form>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->

                </div>
                <!-- /.col -->


            </div>
        </section>
        <!-- /.content -->

    </div>


</div>
@endsection

@section('script')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.editor').summernote({
            height: 220,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'italic', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });

        // Interactive Font Family Live Preview Handlers
        var fontSelect = document.getElementById('site_font_family');
        var previewBox = document.getElementById('previewSampleText');
        var previewNumbers = document.getElementById('previewSampleNumbers');
        var previewBadge = document.getElementById('previewFontBadge');

        function updateFontPreview(fontName) {
            if (!fontName || !fontSelect) return;
            var opt = fontSelect.querySelector('option[value="' + fontName + '"]');
            var query = opt ? opt.getAttribute('data-query') : null;
            var fallback = opt ? opt.getAttribute('data-fallback') : 'sans-serif';

            if (query) {
                var linkId = 'dyn-preview-' + fontName.replace(/[^a-zA-Z0-9]/g, '');
                if (!document.getElementById(linkId)) {
                    var link = document.createElement('link');
                    link.id = linkId;
                    link.rel = 'stylesheet';
                    link.href = 'https://fonts.googleapis.com/css2?' + query + '&display=swap';
                    document.head.appendChild(link);
                }
            }

            var fullFont = "'" + fontName + "', 'Hind Siliguri', " + fallback;
            if (previewBox) previewBox.style.fontFamily = fullFont;
            if (previewNumbers) previewNumbers.style.fontFamily = fullFont;
            if (previewBadge) previewBadge.textContent = fontName;
        }

        if (fontSelect) {
            fontSelect.addEventListener('change', function () {
                updateFontPreview(this.value);
            });
            updateFontPreview(fontSelect.value);
        }

        // Live Google Map Preview Handler
        var mapTextarea = document.getElementById('google_map_embed');
        var mapPreviewBox = document.getElementById('admin_map_preview_box');

        function renderAdminMapPreview() {
            if (!mapTextarea || !mapPreviewBox) return;
            var val = mapTextarea.value.trim();
            if (!val) {
                var defAddr = '{{ urlencode(setting("address1") ?: "Narsingdi, Dhaka, Bangladesh") }}';
                mapPreviewBox.innerHTML = '<iframe src="https://maps.google.com/maps?q=' + defAddr + '&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="100%" style="border:0; width:100%; height:100%; min-height:250px;" allowfullscreen="" loading="lazy"></iframe>';
                return;
            }
            if (val.indexOf('<iframe') !== -1) {
                // Ensure width and height are 100%
                var cleanHtml = val.replace(/width="[^"]*"/gi, 'width="100%"').replace(/height="[^"]*"/gi, 'height="100%"');
                mapPreviewBox.innerHTML = cleanHtml;
                var ifr = mapPreviewBox.querySelector('iframe');
                if (ifr) {
                    ifr.style.width = '100%';
                    ifr.style.height = '100%';
                    ifr.style.minHeight = '250px';
                    ifr.style.border = '0';
                }
            } else if (val.indexOf('http://') === 0 || val.indexOf('https://') === 0) {
                mapPreviewBox.innerHTML = '<iframe src="' + val + '" width="100%" height="100%" style="border:0; width:100%; height:100%; min-height:250px;" allowfullscreen="" loading="lazy"></iframe>';
            } else {
                mapPreviewBox.innerHTML = '<iframe src="https://maps.google.com/maps?q=' + encodeURIComponent(val) + '&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="100%" style="border:0; width:100%; height:100%; min-height:250px;" allowfullscreen="" loading="lazy"></iframe>';
            }
        }

        if (mapTextarea) {
            renderAdminMapPreview();
            mapTextarea.addEventListener('input', renderAdminMapPreview);
        }
    });
</script>
@endsection
