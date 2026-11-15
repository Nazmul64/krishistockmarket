@extends('layouts.frontend.app')

@section('title', 'আমাদের ভিডিওসমূহ - Our Videos | ' . setting('website_title', 'e-Krishi Sheba'))

@section('content')
<div class="main-container">

    <!-- Modern Brand Page Banner -->
    <div class="page-modern-hero-banner text-center py-5" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #064e3b 100%);">
        <div class="container">
            <span class="section-pill-badge mb-2" style="background: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.35); border-radius: 20px; padding: 4px 14px; font-size: 13px; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fa fa-play-circle text-danger"></i> ভিডিও গ্যালারি (Video Gallery)
            </span>
            <h1 class="hero-page-title text-white fw-bold mt-2" style="font-size: 32px; letter-spacing: -0.5px;">আমাদের ভিডিওসমূহ (Our Videos)</h1>
            <p class="hero-page-desc text-slate-300 mx-auto" style="max-width: 650px; font-size: 15px; color: #cbd5e1;">ই-কৃষি সেবা ও আমাদের কৃষি পরিবারের কার্যক্রম, পণ্যের পরিচিতি এবং খামার পরিদর্শনের ভিডিও চিত্র</p>
        </div>
    </div>

    <!-- Main Content Container with Bootstrap Video Cards -->
    <main class="site-main py-5 bg-slate-50" style="background-color: #f8fafc; min-height: 480px;">
        <div class="container">
            <div class="row g-4 align-items-stretch">
                @forelse ($videos as $video)
                    <div class="col-12 col-md-6 col-lg-4 d-flex">
                        <div class="card w-100 shadow-sm border-0 rounded-4 overflow-hidden d-flex flex-column" 
                             style="background: #ffffff; border: 1px solid #e2e8f0; transition: transform 0.25s ease, box-shadow 0.25s ease;">
                            
                            <!-- Video Player Section (Ratio 16:9 for YouTube or HTML5 Video Tag) -->
                            <div class="video-media-wrapper position-relative bg-dark" style="background: #000000;">
                                @if($video->is_direct_video)
                                    <video src="{{ $video->video_url }}" class="card-img-top w-100" style="aspect-ratio: 16/9; object-fit: cover;" controls preload="metadata"></video>
                                @else
                                    <div class="ratio ratio-16x9">
                                        <iframe src="{{ $video->embed_url }}" 
                                                title="{{ $video->title }}" 
                                                frameborder="0" 
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                allowfullscreen
                                                loading="lazy">
                                        </iframe>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Button (If Provided) -->
                            @if($video->button_link)
                                <div class="card-body p-3 text-center">
                                    <a href="{{ $video->button_link }}" class="btn btn-golden-action w-100 py-2 fw-semibold text-center d-flex align-items-center justify-content-center gap-2">
                                        <span>{{ $video->button_text ?: 'বিস্তারিত দেখুন' }}</span>
                                        <i class="fa fa-arrow-right font-size-12"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="col-12 text-center py-5">
                        <div class="p-5 bg-white rounded-4 border mx-auto shadow-sm" style="max-width: 520px;">
                            <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; background: #fee2e2; color: #ef4444;">
                                <i class="fa fa-play-circle fa-3x"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">শীঘ্রই ভিডিও যুক্ত করা হবে</h4>
                            <p class="text-muted small mb-0">আমাদের ইউটিউব চ্যানেলের নতুন ভিডিওসমূহ খুব শীঘ্রই এখানে উন্মুক্ত করা হবে। অনুগ্রহ করে সাথে থাকুন।</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($videos->hasPages())
                <div class="mt-5 d-flex justify-content-center">
                    {{ $videos->links() }}
                </div>
            @endif
        </div>
    </main>
</div>

<style>
.video-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(0,0,0,0.1) !important;
}
</style>
@endsection
