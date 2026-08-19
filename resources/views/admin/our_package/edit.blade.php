@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title">প্যাকেজ এডিট করুন (Edit Package)</h4>
                    <p class="text-muted mb-0">প্যাকেজ ইমেজ, টাইটেল ও বিবরণ আপডেট করুন</p>
                </div>
                <a href="{{ route('admin.our_packages.index') }}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left me-1"></i> তালিকায় ফিরে যান</a>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-8">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-edit me-2 text-info"></i> প্যাকেজ এডিট ফর্ম</h4>
                        </div>
                        <div class="box-body">
                            <form method="POST" action="{{ route('admin.our_packages.update', $package->id) }}" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group mb-4 text-center">
                                    <label class="form-label fw-bold d-block">বর্তমান ছবি</label>
                                    <img src="{{ asset($package->image) }}" alt="Package Image" style="max-width: 280px; max-height: 180px; object-fit: cover; border-radius: 8px; border: 2px solid #e2e8f0;">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">নতুন ছবি আপলোড করুন (পরিবর্তন করতে চাইলে)</label>
                                    <input type="file" class="form-control" name="image" accept="image/*">
                                    <small class="text-muted">ছবি পরিবর্তন না করতে চাইলে খালি রাখুন</small>
                                    @error('image') <br><span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">প্যাকেজ টাইটেল (Title)</label>
                                    <input type="text" class="form-control" name="title" value="{{ old('title', $package->title) }}" placeholder="যেমন: কৃষি প্যাকেজ ১">
                                    @error('title') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">প্যাকেজ বিবরণী (Description)</label>
                                    <textarea class="form-control" name="description" rows="3" placeholder="সংক্ষিপ্ত বিবরণ...">{{ old('description', $package->description) }}</textarea>
                                    @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold">সাজানোর ক্রমানুসারে (Sort Order)</label>
                                    <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', $package->sort_order) }}">
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.our_packages.index') }}" class="btn btn-light px-4">বাতিল</a>
                                    <button type="submit" class="btn btn-success px-4"><i class="fa fa-save me-1"></i> আপডেট করুন</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
