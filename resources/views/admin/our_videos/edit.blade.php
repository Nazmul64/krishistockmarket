@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-edit text-primary me-2"></i>ভিডিও সম্পাদনা (Edit Video)</h4>
                    <p class="text-muted mb-0">ভিডিওর শিরোনাম, ইউটিউব লিংক, বিবরণ বা বাটন পরিবর্তন করুন</p>
                </div>
                <div>
                    <a href="{{ route('admin.our_videos.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> ফিরে যান (Back to List)
                    </a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark">ভিডিও তথ্য ফরম</h4>
                        </div>
                        <div class="box-body">
                            <form method="POST" action="{{ route('admin.our_videos.update', $video->id) }}">
                                @csrf

                                <!-- Video Title -->
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ভিডিও শিরোনাম (Title) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg" name="title" value="{{ old('title', $video->title) }}" required>
                                </div>

                                <!-- YouTube / Video URL -->
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ইউটিউব ভিডিও লিংক (YouTube URL) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="video_url" id="adminEditVideoUrl" value="{{ old('video_url', $video->video_url) }}" required>
                                    <small class="text-muted d-block mt-1">ইউটিউব লিংক (watch, share, shorts) বা সরাসরি MP4 ফাইল লিংক</small>
                                </div>

                                <!-- Live Preview Container -->
                                <div class="mb-3" id="adminEditPreviewBox">
                                    <label class="form-label fw-bold small text-muted">ভিডিও প্রিভিউ:</label>
                                    <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm border bg-dark">
                                        <iframe id="adminEditPreviewIframe" src="{{ $video->embed_url }}" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">সংক্ষিপ্ত বিবরণ (Description)</label>
                                    <textarea class="form-control" name="description" rows="4">{{ old('description', $video->description) }}</textarea>
                                </div>

                                <!-- Button Text & Link -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">বাটন টেক্সট (Button Text)</label>
                                            <input type="text" class="form-control" name="button_text" value="{{ old('button_text', $video->button_text) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">বাটন লিংক (Button Link)</label>
                                            <input type="text" class="form-control" name="button_link" value="{{ old('button_link', $video->button_link) }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Order & Status -->
                                <div class="row mb-4 align-items-center">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">ক্রমিক নম্বর (Sort Order)</label>
                                        <input type="number" class="form-control" name="order_num" value="{{ old('order_num', $video->order_num) }}" min="0">
                                    </div>
                                    <div class="col-md-6 pt-3 pt-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="isActiveCheckEdit" value="1" {{ $video->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="isActiveCheckEdit">সক্রিয় (Active on Frontend)</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                                    <a href="{{ route('admin.our_videos.index') }}" class="btn btn-outline-secondary px-4">বাতিল</a>
                                    <button type="submit" class="btn btn-primary px-4 fw-bold">
                                        <i class="fa fa-save me-1"></i> পরিবর্তন সেভ করুন (Update)
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('adminEditVideoUrl');
    const iframe = document.getElementById('adminEditPreviewIframe');

    function updatePreview() {
        const val = (input.value || '').trim();
        const ytMatch = val.match(/(?:youtube(?:-nocookie)?\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i);
        if (ytMatch && ytMatch[1]) {
            iframe.src = 'https://www.youtube.com/embed/' + ytMatch[1];
        } else if (val) {
            iframe.src = val;
        }
    }

    input.addEventListener('input', updatePreview);
    input.addEventListener('change', updatePreview);
});
</script>
@endpush
@endsection
