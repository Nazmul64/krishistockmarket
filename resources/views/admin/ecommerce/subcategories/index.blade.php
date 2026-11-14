@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1"><i class="ti-split-v text-info me-2"></i>সাব-ক্যাটাগরি ম্যানেজমেন্ট</h3>
                    <p class="text-muted mb-0">মেইন ক্যাটাগরির অধীনে সাব-ক্যাটাগরি তৈরি, সম্পাদনা (Edit), ডিলিট ও ড্রপডাউন ক্রম সাজান</p>
                </div>
                <a href="{{ route('admin.ecommerce.categories') }}" class="btn btn-sm btn-outline-success">
                    <i class="ti-arrow-left me-1"></i> মেইন ক্যাটাগরিতে ফিরুন
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
                <!-- Add Subcategory Form -->
                <div class="col-lg-4 col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border">
                            <h4 class="box-title"><i class="ti-plus me-1 text-primary"></i>নতুন সাব-ক্যাটাগরি যোগ করুন</h4>
                        </div>
                        <div class="box-body">
                            <form action="{{ route('admin.ecommerce.subcategories.store') }}" method="POST">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">মেইন ক্যাটাগরি নির্বাচন করুন <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-select" required>
                                        <option value="">-- ক্যাটাগরি বেছে নিন --</option>
                                        @foreach($categories as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->name_bn }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">সাব-ক্যাটাগরি নাম (English) <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required placeholder="যেমন: Cooking, Rice, Oil, Skin Care">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">সাব-ক্যাটাগরি নাম (বাংলা)</label>
                                    <input type="text" name="name_bn" class="form-control" placeholder="যেমন: রান্নার উপকরণ, চাল, তেল">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ডিসপ্লে ক্রম (Order)</label>
                                    <input type="number" name="order_num" class="form-control" value="1">
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="subActive" checked>
                                    <label class="form-check-label fw-bold" for="subActive">সক্রিয় রাখুন (Active)</label>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti-check me-1"></i> সাব-ক্যাটাগরি সেভ করুন
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Subcategories List -->
                <div class="col-lg-8 col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border">
                            <h4 class="box-title"><i class="ti-list me-1 text-success"></i>সকল সাব-ক্যাটাগরি ({{ $subcategories->count() }} টি)</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">ক্রম</th>
                                            <th>মেইন ক্যাটাগরি</th>
                                            <th>সাব-ক্যাটাগরি নাম</th>
                                            <th>মোট পণ্য</th>
                                            <th>স্ট্যাটাস</th>
                                            <th class="text-end" style="min-width: 100px;">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($subcategories as $sub)
                                            <tr>
                                                <td class="fw-bold text-center">{{ $sub->order_num }}</td>
                                                <td>
                                                    <span class="badge bg-primary text-white">
                                                        <i class="fa {{ $sub->category->icon ?? 'fa-shopping-basket' }} me-1"></i> {{ $sub->category->name ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <strong class="text-dark d-block">{{ $sub->name }}</strong>
                                                    <small class="text-muted">{{ $sub->name_bn }} | <code>{{ $sub->slug }}</code></small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary text-white px-2 py-1">
                                                        <i class="ti-package me-1"></i>{{ $sub->products_count }} টি পণ্য
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($sub->is_active)
                                                        <span class="badge bg-success">সক্রিয়</span>
                                                    @else
                                                        <span class="badge bg-danger">নিষ্ক্রিয়</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <!-- Edit Button -->
                                                    <button type="button" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editSubcategoryModal{{ $sub->id }}" title="এডিট করুন">
                                                        <i class="ti-pencil-alt"></i>
                                                    </button>
                                                    <!-- Delete Button -->
                                                    <a href="{{ route('admin.ecommerce.subcategories.destroy', $sub->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('আপনি কি নিশ্চিত এই সাব-ক্যাটাগরি মুছে ফেলতে চান?')" title="ডিলিট করুন">
                                                        <i class="ti-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>

                                            <!-- Edit Subcategory Modal -->
                                            <div class="modal fade" id="editSubcategoryModal{{ $sub->id }}" tabindex="-1" aria-labelledby="editSubLabel{{ $sub->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('admin.ecommerce.subcategories.update', $sub->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-header">
                                                                <h5 class="modal-title fw-bold" id="editSubLabel{{ $sub->id }}">
                                                                    <i class="ti-pencil-alt text-primary me-1"></i> সাব-ক্যাটাগরি সম্পাদনা: {{ $sub->name }}
                                                                </h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group mb-3">
                                                                    <label class="form-label fw-bold">মেইন ক্যাটাগরি <span class="text-danger">*</span></label>
                                                                    <select name="category_id" class="form-select" required>
                                                                        @foreach($categories as $c)
                                                                            <option value="{{ $c->id }}" {{ $sub->category_id == $c->id ? 'selected' : '' }}>
                                                                                {{ $c->name }} ({{ $c->name_bn }})
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="form-group mb-3">
                                                                    <label class="form-label fw-bold">সাব-ক্যাটাগরি নাম (English) <span class="text-danger">*</span></label>
                                                                    <input type="text" name="name" class="form-control" value="{{ $sub->name }}" required>
                                                                </div>

                                                                <div class="form-group mb-3">
                                                                    <label class="form-label fw-bold">সাব-ক্যাটাগরি নাম (বাংলা)</label>
                                                                    <input type="text" name="name_bn" class="form-control" value="{{ $sub->name_bn }}">
                                                                </div>

                                                                <div class="form-group mb-3">
                                                                    <label class="form-label fw-bold">ডিসপ্লে ক্রম (Order)</label>
                                                                    <input type="number" name="order_num" class="form-control" value="{{ $sub->order_num }}">
                                                                </div>

                                                                <div class="form-check form-switch mb-2">
                                                                    <input class="form-check-input" type="checkbox" name="is_active" id="editSubActive{{ $sub->id }}" {{ $sub->is_active ? 'checked' : '' }}>
                                                                    <label class="form-check-label fw-bold" for="editSubActive{{ $sub->id }}">সক্রিয় রাখুন (Active)</label>
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
                                                <td colspan="6" class="text-center py-4 text-muted">কোনো সাব-ক্যাটাগরি পাওয়া যায়নি।</td>
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
