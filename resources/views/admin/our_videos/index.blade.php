@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-play-circle text-danger me-2"></i>Our Videos (আমাদের ভিডিওসমূহ)</h4>
                    <p class="text-muted mb-0">ইউটিউব ভিডিওর লিংক, শিরোনাম ও বাটন ম্যানেজমেন্ট যা ফ্রন্টএন্ডে প্রদর্শিত হবে</p>
                </div>
                <div>
                    <a href="{{ route('our_videos') }}" target="_blank" class="btn btn-outline-info btn-sm">
                        <i class="fa fa-external-link me-1"></i> ফ্রন্টএন্ডে দেখুন (View Live)
                    </a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- Add New Video Form -->
                <div class="col-xl-4 col-lg-5 mb-4">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-plus-circle me-2 text-success"></i> নতুন ভিডিও যোগ করুন</h4>
                        </div>
                        <div class="box-body">
                            <form method="POST" action="{{ route('admin.our_videos.store') }}">
                                @csrf

                                <!-- Video Title -->
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ভিডিও শিরোনাম (Title) <small class="text-muted">(ঐচ্ছিক - অ্যাডমিনে চেনার জন্য)</small></label>
                                    <input type="text" class="form-control" name="title" value="{{ old('title') }}" placeholder="যেমন: আমাদের নতুন পণ্যের পরিচিতি (ঐচ্ছিক)">
                                </div>

                                <!-- YouTube / Video URL -->
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ইউটিউব ভিডিও লিংক (YouTube URL) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="video_url" id="adminVideoUrlInput" value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=..." required>
                                    <small class="text-muted d-block mt-1">ইউটিউব লিংক (watch, share link, shorts) অথবা সরাসরি MP4 ভিডিও লিংক দিন</small>
                                </div>

                                <!-- Live Preview Container -->
                                <div class="mb-3 d-none" id="adminVideoPreviewBox">
                                    <label class="form-label fw-bold small text-muted">লাইভ প্রিভিউ:</label>
                                    <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm border bg-dark">
                                        <iframe id="adminPreviewIframe" src="" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">সংক্ষিপ্ত বিবরণ (Description) <small class="text-muted">(ঐচ্ছিক)</small></label>
                                    <textarea class="form-control" name="description" rows="3" placeholder="ভিডিও সম্পর্কিত সংক্ষিপ্ত বিবরণ লিখুন...">{{ old('description') }}</textarea>
                                </div>

                                <!-- Button Text & Link -->
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">বাটন টেক্সট</label>
                                            <input type="text" class="form-control" name="button_text" value="{{ old('button_text', 'বিস্তারিত দেখুন') }}" placeholder="যেমন: বিস্তারিত">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">বাটন লিংক</label>
                                            <input type="text" class="form-control" name="button_link" value="{{ old('button_link') }}" placeholder="/our-products">
                                        </div>
                                    </div>
                                </div>

                                <!-- Order & Status -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-6">
                                        <label class="form-label fw-bold">ক্রমিক নম্বর</label>
                                        <input type="number" class="form-control" name="order_num" value="{{ old('order_num', 0) }}" min="0">
                                    </div>
                                    <div class="col-6 pt-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="isActiveCheck" value="1" checked>
                                            <label class="form-check-label fw-bold" for="isActiveCheck">সক্রিয় (Active)</label>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success w-100 py-2 fs-15 fw-bold">
                                    <i class="fa fa-save me-1"></i> সেভ করুন (Save Video)
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Videos List Table -->
                <div class="col-xl-8 col-lg-7">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light d-flex align-items-center justify-content-between">
                            <h4 class="box-title text-dark"><i class="fa fa-list me-2 text-info"></i> সকল ভিডিও তালিকা ({{ $videos->total() }})</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th style="width: 140px;">ভিডিও প্রিভিউ</th>
                                            <th>শিরোনাম ও বিবরণ</th>
                                            <th>বাটন</th>
                                            <th style="width: 90px;" class="text-center">স্ট্যাটাস</th>
                                            <th style="width: 110px;" class="text-center">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($videos as $index => $item)
                                            <tr>
                                                <td class="fw-bold font-monospace">{{ $videos->firstItem() + $index }}</td>
                                                <td>
                                                    <div class="position-relative rounded overflow-hidden border shadow-sm" style="width: 120px; height: 68px; background: #000;">
                                                        @if($item->is_direct_video)
                                                            <video src="{{ $item->video_url }}" style="width: 100%; height: 100%; object-fit: cover;" muted></video>
                                                        @else
                                                            <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                            <div class="position-absolute top-50 start-50 translate-middle text-danger bg-white rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 24px; height: 24px; font-size: 11px;">
                                                                <i class="fa fa-play ms-1"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-dark">{{ $item->title }}</div>
                                                    @if($item->description)
                                                        <small class="text-muted d-block text-truncate" style="max-width: 260px;">{{ $item->description }}</small>
                                                    @endif
                                                    <small class="text-primary font-monospace d-block text-truncate" style="max-width: 240px; font-size: 11px;">
                                                        <i class="fa fa-link me-1"></i>{{ $item->video_url }}
                                                    </small>
                                                </td>
                                                <td>
                                                    @if($item->button_link)
                                                        <span class="badge bg-light text-dark border">
                                                            {{ $item->button_text ?: 'বাটন' }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted small">—</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <form method="POST" action="{{ route('admin.our_videos.toggle_status', $item->id) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm {{ $item->is_active ? 'btn-success' : 'btn-secondary' }} py-0 px-2 font-size-11" title="ক্লিক করে স্ট্যাটাস পরিবর্তন করুন">
                                                            {{ $item->is_active ? 'Active' : 'Inactive' }}
                                                        </button>
                                                    </form>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                                        <a href="{{ route('admin.our_videos.edit', $item->id) }}" class="btn btn-sm btn-outline-primary" title="এডিট করুন">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <a href="{{ route('admin.our_videos.destroy', $item->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('আপনি কি নিশ্চিত এই ভিডিওটি মুছে ফেলতে চান?');" title="ডিলিট করুন">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">
                                                    <i class="fa fa-video-camera fa-2x mb-2 d-block text-muted"></i>
                                                    কোনো ভিডিও যোগ করা হয়নি। বাম পাশের ফর্ম থেকে ভিডিও লিংক দিন।
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($videos->hasPages())
                            <div class="box-footer bg-light">
                                {{ $videos->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('adminVideoUrlInput');
    const previewBox = document.getElementById('adminVideoPreviewBox');
    const iframe = document.getElementById('adminPreviewIframe');

    function updatePreview() {
        const val = (input.value || '').trim();
        const ytMatch = val.match(/(?:youtube(?:-nocookie)?\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i);
        if (ytMatch && ytMatch[1]) {
            iframe.src = 'https://www.youtube.com/embed/' + ytMatch[1];
            previewBox.classList.remove('d-none');
        } else {
            previewBox.classList.add('d-none');
            iframe.src = '';
        }
    }

    input.addEventListener('input', updatePreview);
    input.addEventListener('change', updatePreview);
    updatePreview();
});
</script>
@endpush
@endsection
