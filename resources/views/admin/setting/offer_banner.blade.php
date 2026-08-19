@extends('layouts.backend.app')
@section('title', 'অফার ব্যানার সেটিং')
@section('content')

<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-bullhorn text-warning me-2"></i> অফার ব্যানার ম্যানেজমেন্ট (Offer Banner Setting)
                    </h3>
                    <p class="text-muted mb-0">হোমপেজের স্লাইডারের ডানপাশের অফার ব্যানার পরিচালনা ও আপডেট করুন</p>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="box shadow-sm border-0 rounded-3">
                        <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                            <h4 class="box-title fw-bold text-dark mb-0">
                                <i class="fa fa-image me-2 text-primary"></i> হোমপেজ অফার ব্যানার (Side Offer Banner Setting)
                            </h4>
                            @if(!empty(setting('offer_banner_img')))
                                <a href="{{ route('setting.offer_banner.delete') }}" class="btn btn-sm btn-danger px-3 shadow-sm" onclick="return confirm('আপনি কি নিশ্চিত যে অফার ব্যানারটি মুছে ফেলতে চান?');">
                                    <i class="fa fa-trash me-1"></i> অফার ব্যানার রিমুভ করুন
                                </a>
                            @endif
                        </div>
                        <div class="box-body p-4">
                            <form method="POST" action="{{ route('setting.offer_banner.post') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-4 align-items-center">
                                    <div class="col-lg-6 col-12">
                                        <div class="mb-4">
                                            <label for="offer_banner_title" class="fw-bold mb-2 text-dark">
                                                অফার ব্যানার শিরোনাম / নোট (Title / Note)
                                            </label>
                                            <input type="text" class="form-control form-control-lg" id="offer_banner_title" name="offer_banner_title" value="{{ setting('offer_banner_title') }}" placeholder="উদাহরণ: নতুন বছরের স্পেশাল অফার">
                                            <small class="text-muted">রেফারেন্সের জন্য অথবা ছবির অল্টারনেটিভ ক্যাপশন হিসেবে ব্যবহৃত হবে</small>
                                        </div>

                                        <div class="mb-4">
                                            <label for="offer_banner_link" class="fw-bold mb-2 text-dark">
                                                অফার ব্যানার ক্লিক লিংক (Target URL)
                                            </label>
                                            <input type="text" class="form-control form-control-lg" id="offer_banner_link" name="offer_banner_link" value="{{ setting('offer_banner_link') }}" placeholder="উদাহরণ: http://127.0.0.1:8000/monthly-bazaar">
                                            <small class="text-muted">হোমপেজের অফার ব্যানারে ক্লিক করলে ইউজার যে পেজে যাবে তার লিংক দিন</small>
                                        </div>

                                        <div class="mb-4">
                                            <label for="offer_banner_img" class="fw-bold mb-2 text-dark">
                                                অফার ব্যানার ছবি আপলোড (Upload Offer Image)
                                            </label>
                                            <input type="file" class="form-control form-control-lg" id="offer_banner_img" name="offer_banner_img" accept="image/*">
                                            <small class="text-secondary">সুপারিশকৃত রেজোলিউশন: 600 x 820 px (বা যেকোনো ভার্টিক্যাল সাইজ)</small>
                                        </div>

                                        <div class="mt-4 pt-2">
                                            <button type="submit" class="btn btn-warning btn-lg fw-bold px-5 text-dark shadow">
                                                <i class="fa fa-save me-2"></i> অফার ব্যানার সেভ ও আপডেট করুন (Save Banner)
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-12 text-center">
                                        <div class="p-4 bg-light border rounded-3">
                                            <h6 class="fw-bold text-dark mb-3">
                                                <i class="fa fa-eye me-1 text-info"></i> বর্তমান অফার ব্যানার প্রিভিউ:
                                            </h6>
                                            @if(!empty(setting('offer_banner_img')) && file_exists(public_path('upload/slider/' . setting('offer_banner_img'))))
                                                <div class="d-inline-block border rounded-3 p-2 bg-white shadow-sm">
                                                    <img src="{{ asset('upload/slider/' . setting('offer_banner_img')) }}" alt="Offer Banner" class="img-fluid rounded" style="max-height: 320px; object-fit: contain;">
                                                </div>
                                            @else
                                                <div class="py-5 text-muted border border-dashed rounded bg-white">
                                                    <i class="fa fa-image fa-3x text-secondary mb-3 d-block"></i>
                                                    <span class="fw-bold text-dark">কোনো অফার ব্যানার আপলোড করা নেই।</span><br>
                                                    <small class="text-muted">ছবি আপলোড না থাকলে হোমপেজের স্লাইডারটি ফুল-উইডথ জুড়ে থাকবে।</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
</div>

@endsection
