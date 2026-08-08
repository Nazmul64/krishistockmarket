@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title">Edit Stock Package Preset</h4>
                </div>
                <div>
                    <a href="{{ route('admin.stock_preset.index') }}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left me-1"></i> ফিরে যান</a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-8">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-edit me-2 text-info"></i> স্টক প্রিসেট এডিট করুন</h4>
                        </div>
                        <div class="box-body">
                            <form method="POST" action="{{ route('admin.stock_preset.update', $preset->id) }}">
                                @csrf

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">বোটনের লেবেল (Display Title) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="title" value="{{ old('title', $preset->title) }}" required>
                                    @error('title') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">প্যাকেজের নাম (Package Stock Name) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="package_name" value="{{ old('package_name', $preset->package_name) }}" required>
                                    @error('package_name') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">মূল্য (Selling & Buying Price) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="price" step="0.01" min="0" value="{{ old('price', $preset->price) }}" required>
                                    @error('price') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold">ডিফল্ট পরিমাণ (Default Quantity) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="quantity" min="1" value="{{ old('quantity', $preset->quantity) }}" required>
                                    @error('quantity') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.stock_preset.index') }}" class="btn btn-secondary py-2 px-4">বাতিল</a>
                                    <button type="submit" class="btn btn-success py-2 px-4"><i class="fa fa-save me-1"></i> আপডেট করুন</button>
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
