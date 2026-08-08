@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title">Edit Monthly Grocery Package</h4>
                </div>
                <div>
                    <a href="{{ route('admin.monthly_bazaar.index') }}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left me-1"></i> ফিরে যান</a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-8">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-edit me-2 text-info"></i> মাসিক বাজার প্যাকেজ এডিট করুন</h4>
                        </div>
                        <div class="box-body">
                            <form method="POST" action="{{ route('admin.monthly_bazaar.update', $item->id) }}" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">প্যাকেজের টাইটেল (Title) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="title" value="{{ old('title', $item->title) }}" required>
                                    @error('title') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">প্যাকেজের নাম (Package Name) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="package_name" value="{{ old('package_name', $item->package_name) }}" required>
                                    @error('package_name') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">মূল্য (Price ৳) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="price" step="0.01" min="0" value="{{ old('price', $item->price) }}" required>
                                    @error('price') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">স্টক পরিমাণ (Quantity) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="quantity" min="0" value="{{ old('quantity', $item->quantity) }}" required>
                                    @error('quantity') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">প্যাকেজের ছবি (Package Image)</label>
                                    <input type="file" class="form-control" name="image">
                                    @if($item->image)
                                        <div class="mt-2">
                                            <img src="{{ asset('upload/monthly_bazaar/'.$item->image) }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 6px;">
                                        </div>
                                    @endif
                                    @error('image') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold">বিবরণী (Description)</label>
                                    <textarea class="form-control" name="description" rows="4">{{ old('description', $item->description) }}</textarea>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.monthly_bazaar.index') }}" class="btn btn-secondary py-2 px-4">বাতিল</a>
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
