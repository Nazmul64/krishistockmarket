@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1"><i class="ti-layout-slider text-primary me-2"></i>অফার ব্যানার ম্যানেজমেন্ট</h3>
                    <p class="text-muted mb-0">হোমপেজ ও ক্যাটাগরি পেজের প্রোমোশনাল অফার ব্যানার পরিচালনা করুন</p>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- Add Banner Form -->
                <div class="col-lg-4 col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border">
                            <h4 class="box-title"><i class="ti-plus me-1 text-primary"></i>নতুন ব্যানার যোগ করুন</h4>
                        </div>
                        <div class="box-body">
                            <form action="{{ route('admin.ecommerce.banners.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ব্যানার ইমেজ <span class="text-danger">*</span></label>
                                    <input type="file" name="image" class="form-control" required accept="image/*">
                                    <small class="text-muted">সংরক্ষিত হবে: <code>public/uploads/banner/</code></small>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ব্যানার শিরোনাম (Title)</label>
                                    <input type="text" name="title" class="form-control" placeholder="যেমন: উইন্টার স্পেশাল ডিসকাউন্ট">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">টার্গেট লিংক (URL)</label>
                                    <input type="text" name="link" class="form-control" value="#">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ব্যানার প্রদর্শনের স্থান / পেজ (Position) <span class="text-danger">*</span></label>
                                    <select name="position" class="form-select">
                                        <option value="monthly_bazar">মাসিক বাজার পেজ ব্যানার (Monthly Bazar)</option>
                                        <option value="daily_offers">ডেইলি অফার পেজ ব্যানার (Daily Offers)</option>
                                        <option value="combo_offers">কম্বো অফার পেজ ব্যানার (Combo Offers)</option>
                                        <option value="winter_essentials">উইন্টার এসেনশিয়ালস ব্যানার (Winter Essentials)</option>
                                        <option value="popular">পপুলার প্রোডাক্টস ব্যানার (Popular Page)</option>
                                        <option value="homepage_middle">হোমপেজ মিডল সেকশন (Homepage Middle)</option>
                                        <option value="homepage_top">হোমপেজ টপ স্ট্রিপ (Homepage Top)</option>
                                        <option value="sidebar">সাইডবার অফার ব্যানার (Sidebar)</option>
                                    </select>
                                    <small class="text-muted">যে পেজে এই ব্যানারটি শীর্ষে প্রদর্শিত হবে তা নির্বাচন করুন</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ক্রম (Order)</label>
                                    <input type="number" name="order_num" class="form-control" value="1">
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="bannerActive" checked>
                                    <label class="form-check-label fw-bold" for="bannerActive">সক্রিয় রাখুন (Active)</label>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti-upload me-1"></i> ব্যানার সেভ করুন
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Banners List -->
                <div class="col-lg-8 col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border">
                            <h4 class="box-title"><i class="ti-list me-1 text-success"></i>বর্তমান ব্যানার তালিকা</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ক্রম</th>
                                            <th>ছবি</th>
                                            <th>শিরোনাম</th>
                                            <th>পজিশন</th>
                                            <th>স্ট্যাটাস</th>
                                            <th class="text-end">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($banners as $b)
                                            <tr>
                                                <td>{{ $b->order_num }}</td>
                                                <td>
                                                    <img src="{{ asset($b->image) }}" class="rounded border" style="width: 110px; height: 50px; object-fit: cover;">
                                                </td>
                                                <td><strong>{{ $b->title ?: '(কোনো টাইটেল নেই)' }}</strong></td>
                                                @php
                                                    $posNames = [
                                                        'monthly_bazar' => 'মাসিক বাজার (Monthly Bazar)',
                                                        'daily_offers' => 'ডেইলি অফার (Daily Offers)',
                                                        'combo_offers' => 'কম্বো অফার (Combo Offers)',
                                                        'winter_essentials' => 'উইন্টার (Winter Essentials)',
                                                        'popular' => 'পপুলার (Popular)',
                                                        'homepage_middle' => 'হোমপেজ মিডল',
                                                        'homepage_top' => 'হোমপেজ টপ',
                                                        'sidebar' => 'সাইডবার',
                                                    ];
                                                @endphp
                                                <td><span class="badge bg-info-subtle text-info border">{{ $posNames[$b->position] ?? $b->position }}</span></td>
                                                <td>
                                                    @if($b->is_active)
                                                        <span class="badge bg-success">সক্রিয়</span>
                                                    @else
                                                        <span class="badge bg-danger">নিষ্ক্রিয়</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('admin.ecommerce.banners.destroy', $b->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('মুছে ফেলতে চান?')">
                                                        <i class="ti-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">কোনো ব্যানার যোগ করা হয়নি।</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

