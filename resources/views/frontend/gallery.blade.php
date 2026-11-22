@extends('layouts.frontend.app')

@section('content')
@php
    $isBn = session('locale', 'bn') !== 'en';
@endphp
<div class="main-container">

    <!-- Modern Hero Banner -->
    <div class="page-modern-hero-banner text-center py-5" style="background: linear-gradient(135deg, #004d53 0%, #007e8a 100%);">
        <div class="container">
            <span class="section-pill-badge mb-2 d-inline-flex align-items-center bg-white text-dark px-3 py-1 rounded-pill shadow-sm" style="font-size: 13px; font-weight: 600;">
                <i class="fa fa-picture-o text-warning me-1"></i> {{ $isBn ? 'ফটো গ্যালারি' : 'Photo Gallery' }}
            </span>
            <h1 class="hero-page-title text-white fw-bold mb-2">
                {{ $isBn ? 'কোম্পানির কার্যক্রম ও ফটো গ্যালারি' : 'Company Activities & Photo Gallery' }}
            </h1>
            <p class="hero-page-desc text-white-50 max-w-xl mx-auto mb-0" style="font-size: 15px;">
                {{ $isBn 
                    ? 'আমাদের খামার, প্রজেক্ট, সমাজসেবামূলক উদ্যোগ ও মাঠ পর্যায়ের বিভিন্ন কার্যক্রমের স্থিরচিত্র' 
                    : 'Glimpse of our farms, agriculture projects, and enterprise field activities' }}
            </p>
        </div>
    </div>

    <main class="site-main py-5 bg-slate-50">
        <div class="container">
            <!-- Gallery Grid -->
            <div class="row g-3 g-md-4 gallery-list">
                @forelse ($galleries as $item)
                    @php
                        $caption = $isBn ? ($item->title_bn ?: $item->title_en) : ($item->title_en ?: $item->title_bn);
                        $imgUrl = asset('upload/gallery/' . $item->image);
                    @endphp
                    <div class="col-6 col-md-4 col-lg-3 gallery-box">
                        <div class="gallery-modern-card bg-white rounded-4 shadow-sm border overflow-hidden h-100 d-flex flex-column" style="transition: all 0.3s ease;">
                            <div class="gallery-img-wrap position-relative cursor-pointer" 
                                style="height: 220px; background: #f8fafc; display: flex; align-items: center; justify-content: center; overflow: hidden;"
                                onclick="openGalleryModal('{{ $imgUrl }}', '{{ addslashes($caption ?? '') }}')">
                                <img 
                                    src="{{ $imgUrl }}" 
                                    alt="{{ $caption ?: 'Gallery Image' }}" 
                                    style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;"
                                    loading="lazy"
                                />
                                <div class="gallery-overlay-hover position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
                                    style="background: rgba(0, 77, 83, 0.45); opacity: 0; transition: opacity 0.3s ease;">
                                    <span class="btn btn-sm btn-light rounded-circle shadow-sm" style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fa fa-search-plus text-dark font-size-16"></i>
                                    </span>
                                </div>
                            </div>
                            @if($caption)
                                <div class="p-3 text-center bg-white border-top flex-grow-1 d-flex align-items-center justify-content-center">
                                    <h6 class="fw-bold text-dark mb-0 font-size-13 text-truncate-2" title="{{ $caption }}">
                                        {{ $caption }}
                                    </h6>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="p-5 bg-white rounded-4 border max-w-md mx-auto shadow-sm" style="max-width: 520px;">
                            <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; background: #f0fdfa; color: #007e8a;">
                                <i class="fa fa-picture-o fa-2x"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">
                                {{ $isBn ? 'গ্যালারিতে এখনো কোনো ছবি যোগ করা হয়নি' : 'No photos added to gallery yet' }}
                            </h4>
                            <p class="text-muted small mb-0">
                                {{ $isBn 
                                    ? 'আমাদের খামার, প্রজেক্ট ও মাঠ পর্যায়ের কার্যক্রমের স্থিরচিত্র খুব শীঘ্রই এখানে আপলোড করা হবে।' 
                                    : 'Field activities, farm glimpses, and enterprise photos will be updated soon.' }}
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($galleries->hasPages())
                <div class="mt-5 d-flex justify-content-center">
                    {{ $galleries->links('frontend.pagination.custom-pagination') }}
                </div>
            @endif
        </div>
    </main>
</div>

<!-- Gallery Lightbox Modal -->
<div class="modal fade" id="galleryPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark border-0 rounded-4 overflow-hidden shadow-lg">
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3 pt-0">
                <img id="galleryModalImg" src="" alt="Preview" style="max-height: 75vh; max-width: 100%; object-fit: contain; border-radius: 8px;">
                <p id="galleryModalCaption" class="text-white mt-3 mb-1 fw-bold font-size-15"></p>
            </div>
        </div>
    </div>
</div>

<style>
.gallery-modern-card:hover .gallery-overlay-hover {
    opacity: 1 !important;
}
.gallery-modern-card:hover img {
    transform: scale(1.05);
}
.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
function openGalleryModal(imgUrl, caption) {
    document.getElementById('galleryModalImg').src = imgUrl;
    document.getElementById('galleryModalCaption').innerText = caption || '';
    const modal = new bootstrap.Modal(document.getElementById('galleryPreviewModal'));
    modal.show();
}
</script>
@endsection
