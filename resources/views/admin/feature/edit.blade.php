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
                            <form method="POST" action="{{ route('admin.feature.update', $feature->id) }}">
                                @csrf

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">টাইটেল (Title) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="title" value="{{ $feature->title }}" required>
                                    @error('title') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">আইকন ক্লাস (FontAwesome Icon) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="icon" value="{{ $feature->icon }}" required>
                                    <small class="text-muted">FontAwesome আইকন ক্লাস যেমন: fa-line-chart, fa-shield, fa-mobile, fa-leaf</small>
                                    @error('icon') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">আইকন কালার (Icon Color)</label>
                                    <input type="color" class="form-control form-control-color w-100" name="color" value="{{ $feature->color ?? '#1b88ce' }}" title="Choose color">
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold">বিবরণী (Description) <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="description" rows="5" required>{{ $feature->description }}</textarea>
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
@endsection
