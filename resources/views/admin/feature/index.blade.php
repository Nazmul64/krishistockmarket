@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title">Feature Boxes (কেন কৃষি পরিবার বেছে নেবেন?)</h4>
                    <p class="text-muted mb-0">হোমপেজের বৈশিষ্ঠ্য বা ফিচার বক্স ম্যানেজমেন্ট</p>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <!-- Add New Feature Box Form -->
                <div class="col-xl-4 col-lg-5 mb-4">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-plus-circle me-2 text-success"></i> নতুন ফিচার বক্স যোগ করুন</h4>
                        </div>
                        <div class="box-body">
                            <form method="POST" action="{{ route('admin.feature.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">টাইটেল (Title) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="title" required placeholder="যেমন: স্মার্ট স্টক পর্যবেক্ষণ" value="{{ old('title') }}">
                                    @error('title') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ফিচার ইমেজ (Feature Image)</label>
                                    <input type="file" class="form-control" name="image" id="feature_image_input" accept="image/*" onchange="previewFeatureImage(this)">
                                    <small class="text-muted d-block mt-1">সুপারিশকৃত ফরম্যাট: PNG, JPG, WEBP বা SVG</small>
                                    @error('image') <span class="text-danger small">{{ $message }}</span> @enderror
                                    
                                    <div id="image_preview_box" class="mt-2 text-center p-2 border rounded bg-light" style="display: none;">
                                        <img id="image_preview" src="#" alt="Preview" style="max-height: 100px; max-width: 100%; object-fit: contain;" class="rounded">
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold">বিবরণী (Description) <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="description" rows="4" required placeholder="ফিচারের সংক্ষিপ্ত বিবরণ লিখুন...">{{ old('description') }}</textarea>
                                    @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <button type="submit" class="btn btn-success w-100 py-2"><i class="fa fa-save me-1"></i> সেভ করুন</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Features List Table -->
                <div class="col-xl-8 col-lg-7">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-list me-2 text-info"></i> সকল ফিচার বক্স তালিকা ({{ count($features) }})</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th style="width: 80px;">ছবি / আইকন</th>
                                            <th>টাইটেল</th>
                                            <th>বিবরণী</th>
                                            <th class="text-center" style="width: 130px;">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($features as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td class="text-center">
                                                @if(!empty($item->image) && file_exists(public_path($item->image)))
                                                    <img src="{{ asset($item->image) }}" alt="{{ $item->title }}" style="width: 50px; height: 50px; object-fit: contain; border-radius: 8px; border: 1px solid #e2e8f0; background: #f8fafc; padding: 3px;">
                                                @else
                                                    <div style="width: 45px; height: 45px; border-radius: 8px; background: {{ $item->color ?? '#1b88ce' }}15; color: {{ $item->color ?? '#1b88ce' }}; display: inline-flex; align-items: center; justify-content: center; font-size: 20px; border: 1px solid {{ $item->color ?? '#1b88ce' }}30;">
                                                        <i class="fa {{ $item->icon ?: 'fa-star' }}"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="fw-bold text-dark">{{ $item->title }}</td>
                                            <td style="max-width: 250px; font-size: 13px; color: #64748b;">{{ $item->description }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.feature.edit', $item->id) }}" class="btn btn-sm btn-info me-1" title="Edit"><i class="fa fa-edit"></i> Edit</a>
                                                <a href="{{ route('admin.feature.delete', $item->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this feature box?')" title="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">কোনো ফিচার বক্স পাওয়া যায়নি।</td>
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

<script>
function previewFeatureImage(input) {
    var previewBox = document.getElementById('image_preview_box');
    var previewImg = document.getElementById('image_preview');
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

