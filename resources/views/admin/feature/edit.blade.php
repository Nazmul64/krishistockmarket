@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title">Edit Feature Box (ফিচার বক্স এডিট)</h4>
                </div>
                <a href="{{ route('admin.feature.index') }}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left me-1"></i> ফিরে যান</a>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-8">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-edit me-2 text-info"></i> ফিচার তথ্য আপডেট করুন</h4>
                        </div>
                        <div class="box-body">
                            <form method="POST" action="{{ route('admin.feature.update', $feature->id) }}" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">টাইটেল (Title) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="title" value="{{ old('title', $feature->title) }}" required>
                                    @error('title') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ফিচার ইমেজ (Feature Image)</label>
                                    
                                    @if(!empty($feature->image) && file_exists(public_path($feature->image)))
                                    <div class="mb-2 p-2 border rounded bg-light d-flex align-items-center gap-3">
                                        <img src="{{ asset($feature->image) }}" alt="Current Image" style="max-height: 80px; max-width: 120px; object-fit: contain;" class="rounded border bg-white p-1">
                                        <div>
                                            <span class="badge bg-success mb-1">বর্তমান ছবি</span>
                                            <small class="text-muted d-block">নতুন ছবি আপলোড করলে পূর্বেরটি পরিবর্তিত হবে।</small>
                                        </div>
                                    </div>
                                    @endif

                                    <input type="file" class="form-control" name="image" id="feature_image_edit_input" accept="image/*" onchange="previewEditImage(this)">
                                    <small class="text-muted d-block mt-1">সুপারিশকৃত ফরম্যাট: PNG, JPG, WEBP বা SVG</small>
                                    @error('image') <span class="text-danger small">{{ $message }}</span> @enderror
                                    
                                    <div id="new_image_preview_box" class="mt-2 text-center p-2 border rounded bg-light" style="display: none;">
                                        <span class="badge bg-info mb-1">নতুন ছবির প্রিভিউ</span>
                                        <div>
                                            <img id="new_image_preview" src="#" alt="New Preview" style="max-height: 100px; max-width: 100%; object-fit: contain;" class="rounded">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold">বিবরণী (Description) <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="description" rows="5" required>{{ old('description', $feature->description) }}</textarea>
                                    @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary px-4 me-2"><i class="fa fa-save me-1"></i> আপডেট করুন</button>
                                    <a href="{{ route('admin.feature.index') }}" class="btn btn-light px-4">বাতিল</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
function previewEditImage(input) {
    var previewBox = document.getElementById('new_image_preview_box');
    var previewImg = document.getElementById('new_image_preview');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewBox.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        previewBox.style.display = 'none';
    }
}
</script>
@endsection

