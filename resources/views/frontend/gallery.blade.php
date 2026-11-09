@extends('layouts.frontend.app')

@section('content')
<div class="main-container">

    <!-- Modern Brand Page Banner -->
    <div class="page-modern-hero-banner text-center py-5">
        <div class="container">
            <span class="section-pill-badge mb-2">
                <i class="fa fa-image me-1"></i> ফটো গ্যালারি
            </span>
            <h1 class="hero-page-title text-white">কোম্পানি প্রোডাক্ট ও কার্যক্রম গ্যালারি</h1>
            <p class="hero-page-desc text-slate-300">আমাদের উন্নতমানের কৃষি পণ্য, প্যাকেজিং ও মাঠ পর্যায়ের কার্যক্রমের স্থিরচিত্র</p>
        </div>
    </div>

    <main class="site-main py-5 bg-slate-50">
        <div class="container">
            <!-- Gallery Grid -->
            <div class="row g-3 g-md-4 gallery-list">
                @php
                $gallery_images = App\Models\StockGallery::paginate(12);
                @endphp

                @forelse ($gallery_images as $image)
                    <div class="col-6 col-md-4 col-lg-3 gallery-box">
                        <div class="gallery-modern-card bg-white rounded-4 shadow-sm border overflow-hidden">
                            <div class="gallery-img-wrap position-relative" style="height: 220px; background: #f8fafc; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                <img 
                                    src="{{ asset('upload/stock_images') }}/{{ $image->image }}" 
                                    alt="Gallery Image" 
                                    style="max-width: 100%; max-height: 100%; object-fit: contain; padding: 8px; transition: transform 0.4s ease;"
                                />
                                <a class="gallery-zoom-btn zoom" href="{{ asset('upload/stock_images') }}/{{ $image->image }}" title="বড় করে দেখুন">
                                    <i class="fa fa-search-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="p-5 bg-white rounded-4 border max-w-md mx-auto shadow-sm" style="max-width: 520px;">
                            <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; background: #f1f5f9; color: #94a3b8;">
                                <i class="fa fa-picture-o fa-2x"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">গ্যালারিতে এখনো কোনো ছবি যোগ করা হয়নি</h4>
                            <p class="text-muted small mb-0">আমাদের খামার, প্রজেক্ট ও মাঠ পর্যায়ের কার্যক্রমের স্থিরচিত্র খুব শীঘ্রই এখানে আপলোড করা হবে।</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($gallery_images->hasPages())
                <div class="mt-5 d-flex justify-content-center">
                    {{ $gallery_images->links('frontend.pagination.custom-pagination') }}
                </div>
            @endif
        </div>
    </main>
</div>
@endsection
