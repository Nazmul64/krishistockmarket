@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1"><i class="ti-layers-alt text-success me-2"></i>মেইন ক্যাটাগরি ম্যানেজমেন্ট</h3>
                    <p class="text-muted mb-0">সুপারশপের প্রধান প্রোডাক্ট ক্যাটাগরি তৈরি, সম্পাদনা (Edit), ডিলিট ও ক্রম সাজান</p>
                </div>
                <a href="{{ route('admin.ecommerce.subcategories') }}" class="btn btn-sm btn-outline-primary">
                    <i class="ti-split-v me-1"></i> সাব-ক্যাটাগরি ম্যানেজমেন্টে যান
                </a>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-triangle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- Add Category Form -->
                <div class="col-lg-4 col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border">
                            <h4 class="box-title"><i class="ti-plus me-1 text-primary"></i>নতুন ক্যাটাগরি যোগ করুন</h4>
                        </div>
                        <div class="box-body">
                            <form action="{{ route('admin.ecommerce.categories.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ক্যাটাগরি নাম (English) <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required placeholder="e.g. Food, Baby Care">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ক্যাটাগরি নাম (বাংলা)</label>
                                    <input type="text" name="name_bn" class="form-control" placeholder="যেমন: খাদ্যদ্রব্য, বেবি ফুড">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ফন্টঅসাম আইকন ক্লাস</label>
                                    <input type="text" name="icon" class="form-control" value="fa-shopping-basket" placeholder="e.g. fa-utensils, fa-baby">
                                    <small class="text-muted">উদাহরণ: <code>fa-utensils</code>, <code>fa-baby</code>, <code>fa-spray-can</code>, <code>fa-shirt</code></small>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ক্যাটাগরি ছবি (ঐচ্ছিক)</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    <small class="text-muted">সংরক্ষিত হবে: <code>public/uploads/category/</code></small>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ডিসপ্লে ক্রম (Order)</label>
                                    <input type="number" name="order_num" class="form-control" value="1">
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="catActive" checked>
                                    <label class="form-check-label fw-bold" for="catActive">সক্রিয় রাখুন (Active)</label>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti-check me-1"></i> ক্যাটাগরি তৈরি করুন
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Categories List -->
                <div class="col-lg-8 col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border">
                            <h4 class="box-title"><i class="ti-list me-1 text-success"></i>সকল ক্যাটাগরি ({{ $categories->count() }} টি)</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">ক্রম</th>
                                            <th style="width: 60px;">আইকন/ছবি</th>
                                            <th>ক্যাটাগরির নাম</th>
                                            <th>সাব-ক্যাটাগরি</th>
                                            <th>মোট পণ্য</th>
                                            <th>স্ট্যাটাস</th>
                                            <th class="text-end" style="min-width: 100px;">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($categories as $cat)
                                            <tr>
                                                <td class="fw-bold text-center">{{ $cat->order_num }}</td>
                                                <td>
                                                    @if($cat->image)
                                                        <img src="{{ asset($cat->image) }}" alt="{{ $cat->name }}" class="rounded border p-1 bg-white" style="width: 38px; height: 38px; object-fit: contain;">
                                                    @else
                                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-primary" style="width: 38px; height: 38px;">
                                                            <i class="fa {{ $cat->icon ?: 'fa-shopping-basket' }}"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong class="text-dark d-block">{{ $cat->name }}</strong>
                                                    <small class="text-muted">{{ $cat->name_bn }} | <code>{{ $cat->slug }}</code></small>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.ecommerce.subcategories') }}" class="badge bg-light text-primary border px-2 py-1">
                                                        {{ $cat->subcategories_count }} টি সাব-ক্যাটাগরি
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary text-white px-2 py-1 shadow-sm">
                                                        <i class="ti-package me-1"></i>{{ $cat->products_count }} টি পণ্য
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($cat->is_active)
                                                        <span class="badge bg-success">সক্রিয়</span>
                                                    @else
                                                        <span class="badge bg-danger">নিষ্ক্রিয়</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <!-- Edit Button -->
                                                    <button type="button" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $cat->id }}" title="এডিট করুন">
                                                        <i class="ti-pencil-alt"></i>
                                                    </button>
                                                    <!-- Delete Button -->
                                                    <a href="{{ route('admin.ecommerce.categories.destroy', $cat->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('আপনি কি নিশ্চিত এই ক্যাটাগরি মুছে ফেলতে চান?')" title="ডিলিট করুন">
                                                        <i class="ti-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>

                                            <!-- Edit Category Modal -->
                                            <div class="modal fade" id="editCategoryModal{{ $cat->id }}" tabindex="-1" aria-labelledby="editCatLabel{{ $cat->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('admin.ecommerce.categories.update', $cat->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-header">
                                                                <h5 class="modal-title fw-bold" id="editCatLabel{{ $cat->id }}">
                                                                    <i class="ti-pencil-alt text-primary me-1"></i> ক্যাটাগরি সম্পাদনা: {{ $cat->name }}
                                                                </h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group mb-3">
                                                                    <label class="form-label fw-bold">ক্যাটাগরি নাম (English) <span class="text-danger">*</span></label>
                                                                    <input type="text" name="name" class="form-control" value="{{ $cat->name }}" required>
                                                                </div>

                                                                <div class="form-group mb-3">
                                                                    <label class="form-label fw-bold">ক্যাটাগরি নাম (বাংলা)</label>
                                                                    <input type="text" name="name_bn" class="form-control" value="{{ $cat->name_bn }}">
                                                                </div>

                                                                <div class="form-group mb-3">
                                                                    <label class="form-label fw-bold">ফন্টঅসাম আইকন ক্লাস</label>
                                                                    <input type="text" name="icon" class="form-control" value="{{ $cat->icon }}">
                                                                </div>

                                                                <div class="form-group mb-3">
                                                                    <label class="form-label fw-bold">ক্যাটাগরি ছবি পরিবর্তন করুন (ঐচ্ছিক)</label>
                                                                    <input type="file" name="image" class="form-control" accept="image/*">
                                                                    @if($cat->image)
                                                                        <div class="mt-2 d-flex align-items-center gap-2">
                                                                            <img src="{{ asset($cat->image) }}" style="width: 36px; height: 36px; object-fit: contain;" class="border rounded p-1">
                                                                            <small class="text-muted">বর্তমান ছবি: <code>{{ $cat->image }}</code></small>
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                <div class="form-group mb-3">
                                                                    <label class="form-label fw-bold">ডিসপ্লে ক্রম (Order)</label>
                                                                    <input type="number" name="order_num" class="form-control" value="{{ $cat->order_num }}">
                                                                </div>

                                                                <div class="form-check form-switch mb-2">
                                                                    <input class="form-check-input" type="checkbox" name="is_active" id="editCatActive{{ $cat->id }}" {{ $cat->is_active ? 'checked' : '' }}>
                                                                    <label class="form-check-label fw-bold" for="editCatActive{{ $cat->id }}">সক্রিয় রাখুন (Active)</label>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                                                                <button type="submit" class="btn btn-primary">
                                                                    <i class="ti-check me-1"></i> আপডেট সংরক্ষণ করুন
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted">কোনো ক্যাটাগরি তৈরি করা হয়নি।</td>
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
@endsection
