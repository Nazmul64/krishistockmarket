@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="me-auto">
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="fa fa-picture-o text-warning me-2"></i>গ্যালারি ম্যানেজমেন্ট (Photo Gallery System)
                    </h3>
                    <p class="text-muted small mb-0">কোম্পানির কার্যক্রম ও ফটো গ্যালারির ছবি আপলোড ও নিয়ন্ত্রণ করুন</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('gallery') }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm">
                        <i class="fa fa-external-link me-1"></i> লাইভ গ্যালারি দেখুন
                    </a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <!-- 1. Statistics Cards -->
            <div class="row g-3 mb-4">
                <div class="col-xl-4 col-md-4 col-12">
                    <div class="box box-body py-3 border-start border-4 border-primary rounded-4 shadow-sm bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted text-uppercase fw-bold mb-1 font-size-12">মোট গ্যালারি ছবি</h6>
                                <h3 class="fw-bold mb-0 text-dark">{{ $totalPhotos }}</h3>
                            </div>
                            <span class="avatar avatar-lg rounded-circle bg-primary-light text-primary">
                                <i class="fa fa-images font-size-20"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-4 col-12">
                    <div class="box box-body py-3 border-start border-4 border-success rounded-4 shadow-sm bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted text-uppercase fw-bold mb-1 font-size-12">সক্রিয় (Active) ছবি</h6>
                                <h3 class="fw-bold mb-0 text-success">{{ $activePhotos }}</h3>
                            </div>
                            <span class="avatar avatar-lg rounded-circle bg-success-light text-success">
                                <i class="fa fa-check-circle font-size-20"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-4 col-12">
                    <div class="box box-body py-3 border-start border-4 border-warning rounded-4 shadow-sm bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-muted text-uppercase fw-bold mb-1 font-size-12">নিষ্ক্রিয় (Inactive) ছবি</h6>
                                <h3 class="fw-bold mb-0 text-warning">{{ $inactivePhotos }}</h3>
                            </div>
                            <span class="avatar avatar-lg rounded-circle bg-warning-light text-warning">
                                <i class="fa fa-eye-slash font-size-20"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Image Upload Box (Single & Multiple Upload) -->
            <div class="box rounded-4 shadow-sm border mb-4">
                <div class="box-header with-border" style="background: linear-gradient(135deg, #007e8a 0%, #004d53 100%); color: #ffffff; padding: 16px 20px;">
                    <h4 class="box-title text-white fw-bold mb-0">
                        <i class="fa fa-cloud-upload me-2 text-warning"></i>নতুন ছবি আপলোড করুন (Upload Single / Multiple Photos)
                    </h4>
                </div>
                <div class="box-body p-4 bg-white">
                    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">
                            <div class="col-12">
                                <div class="upload-dropzone-box p-4 text-center rounded-3" style="border: 2px dashed #007e8a; background: #f0fdfa; cursor: pointer;" onclick="document.getElementById('galleryImageInput').click();">
                                    <i class="fa fa-file-image-o fa-3x text-teal mb-2" style="color: #007e8a;"></i>
                                    <h5 class="fw-bold text-dark mb-1">ছবি সিলেক্ট করুন অথবা ড্র্যাগ করে আনুন</h5>
                                    <p class="text-muted small mb-2">আপনি একসাথে একাধিক (Multiple) ছবি নির্বাচন করতে পারবেন। (JPG, PNG, WEBP)</p>
                                    <span class="btn btn-sm text-white px-3 py-2 rounded-pill shadow-sm" style="background: #007e8a;">
                                        <i class="fa fa-folder-open me-1"></i> ব্রাউজ করুন (Browse Images)
                                    </span>
                                    <input type="file" id="galleryImageInput" name="images[]" multiple class="d-none" accept="image/*" onchange="previewGalleryImages(this)">
                                </div>
                                <div id="imagePreviewContainer" class="d-flex flex-wrap gap-2 mt-3" style="min-height: 10px;"></div>
                            </div>

                            <div class="col-md-5 col-12">
                                <label class="form-label fw-bold text-dark">ছবির ক্যাপশন / নাম (বাংলা) <small class="text-muted">(ঐচ্ছিক)</small></label>
                                <input type="text" name="title_bn" class="form-control" placeholder="যেমন: আমাদের প্রজেক্ট পরিদর্শন">
                            </div>

                            <div class="col-md-5 col-12">
                                <label class="form-label fw-bold text-dark">Image Caption (English) <small class="text-muted">(Optional)</small></label>
                                <input type="text" name="title_en" class="form-control" placeholder="e.g. Field Inspection & Farm Visit">
                            </div>

                            <div class="col-md-2 col-12">
                                <label class="form-label fw-bold text-dark">সিরিয়াল / অর্ডার</label>
                                <input type="number" name="sort_order" class="form-control" value="0">
                            </div>

                            <div class="col-12 d-flex align-items-center justify-content-between pt-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" id="galleryStatusToggle" value="1" checked>
                                    <label class="form-check-label fw-bold text-dark" for="galleryStatusToggle">সরাসরি গ্যালারিতে দেখান (Active Status)</label>
                                </div>
                                <button type="submit" class="btn text-white px-4 py-2 rounded-pill shadow-sm fw-bold" style="background: linear-gradient(135deg, #007e8a 0%, #004d53 100%);">
                                    <i class="fa fa-upload me-1"></i> গ্যালারিতে আপলোড করুন
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 3. Existing Gallery Items Grid -->
            <div class="box rounded-4 shadow-sm border">
                <div class="box-header with-border bg-white d-flex align-items-center justify-content-between py-3">
                    <h4 class="box-title fw-bold text-dark mb-0">
                        <i class="fa fa-th-large text-warning me-2"></i>আপলোডকৃত ছবিসমূহ (Gallery Photos)
                    </h4>
                    <span class="badge bg-secondary font-size-12">মোট {{ $galleries->total() }}টি ছবি</span>
                </div>
                <div class="box-body p-4">
                    <div class="row g-3">
                        @forelse($galleries as $item)
                            <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                <div class="card h-100 border rounded-3 overflow-hidden shadow-sm" style="transition: transform 0.2s;">
                                    <div class="position-relative" style="height: 190px; background: #f8fafc; overflow: hidden;">
                                        <img src="{{ asset('upload/gallery/' . $item->image) }}" alt="{{ $item->title_en ?: 'Gallery' }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        <div class="position-absolute top-0 end-0 p-2">
                                            @if($item->status == 1)
                                                <span class="badge bg-success shadow-sm">সক্রিয়</span>
                                            @else
                                                <span class="badge bg-warning shadow-sm">নিষ্ক্রিয়</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $item->title_bn ?: ($item->title_en ?: 'ছবির শিরোনাম নেই') }}">
                                            {{ $item->title_bn ?: ($item->title_en ?: 'শিরোনাম ছাড়া ছবি') }}
                                        </h6>
                                        @if($item->title_en && $item->title_bn)
                                            <p class="small text-muted mb-2 text-truncate">{{ $item->title_en }}</p>
                                        @endif
                                        <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                            <!-- Status toggle button -->
                                            <button type="button" class="btn btn-xs rounded-pill {{ $item->status == 1 ? 'btn-outline-warning' : 'btn-outline-success' }}" onclick="toggleGalleryStatus({{ $item->id }})">
                                                <i class="fa {{ $item->status == 1 ? 'fa-pause' : 'fa-play' }} me-1"></i>
                                                {{ $item->status == 1 ? 'হাইড করুন' : 'অন করুন' }}
                                            </button>

                                            <!-- Delete button -->
                                            <a href="{{ route('admin.gallery.destroy', $item->id) }}" onclick="return confirm('আপনি কি নিশ্চিত এই ছবিটি মুছে ফেলতে চান?')" class="btn btn-xs btn-outline-danger rounded-pill">
                                                <i class="fa fa-trash me-1"></i> মুছুন
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 py-5 text-center">
                                <div class="py-4">
                                    <i class="fa fa-picture-o fa-3x text-muted mb-3 d-block"></i>
                                    <h5 class="fw-bold text-muted">এখনো কোনো ছবি আপলোড করা হয়নি</h5>
                                    <p class="small text-muted mb-0">উপরের আপলোড বক্স থেকে কোম্পানির কার্যক্রমের ছবি যোগ করুন।</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    @if($galleries->hasPages())
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $galleries->links('frontend.pagination.custom-pagination') }}
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>

<script>
function previewGalleryImages(input) {
    const container = document.getElementById('imagePreviewContainer');
    container.innerHTML = '';
    if (input.files && input.files.length > 0) {
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const box = document.createElement('div');
                box.className = 'position-relative rounded overflow-hidden shadow-sm border';
                box.style.width = '75px';
                box.style.height = '75px';
                box.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
                container.appendChild(box);
            }
            reader.readAsDataURL(file);
        });
    }
}

function toggleGalleryStatus(id) {
    fetch("{{ url('/admin/gallery-manager/status') }}/" + id, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(err => {
        location.reload();
    });
}
</script>
@endsection
