@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1"><i class="ti-bookmark-alt text-warning me-2"></i>ব্র্যান্ড ম্যানেজমেন্ট</h3>
                    <p class="text-muted mb-0">পণ্য প্রস্তুতকারক ও সরবরাহকারী ব্র্যান্ড যুক্ত ও পরিচালনা করুন</p>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- Add Brand Form -->
                <div class="col-lg-4 col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border">
                            <h4 class="box-title"><i class="ti-plus me-1 text-primary"></i>নতুন ব্র্যান্ড যোগ করুন</h4>
                        </div>
                        <div class="box-body">
                            <form action="{{ route('admin.ecommerce.brands.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ব্র্যান্ডের নাম <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required placeholder="যেমন: Fortune, Rupchada, Unilever">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ব্র্যান্ড লোগো (ঐচ্ছিক)</label>
                                    <input type="file" name="logo" class="form-control" accept="image/*">
                                    <small class="text-muted">সংরক্ষিত হবে: <code>public/uploads/brand/</code></small>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">সংক্ষিপ্ত বিবরণ</label>
                                    <textarea name="description" rows="2" class="form-control" placeholder="ব্র্যান্ড সম্পর্কিত সংক্ষিপ্ত তথ্য..."></textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ডিসপ্লে ক্রম (Order)</label>
                                    <input type="number" name="order_num" class="form-control" value="1">
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="brandActive" checked>
                                    <label class="form-check-label fw-bold" for="brandActive">সক্রিয় রাখুন (Active)</label>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti-check me-1"></i> ব্র্যান্ড সংরক্ষণ করুন
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Brands List -->
                <div class="col-lg-8 col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border">
                            <h4 class="box-title"><i class="ti-list me-1 text-success"></i>সকল ব্র্যান্ড ({{ $brands->count() }} টি)</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ক্রম</th>
                                            <th>লোগো</th>
                                            <th>নাম ও স্লাগ</th>
                                            <th>মোট পণ্য</th>
                                            <th>স্ট্যাটাস</th>
                                            <th class="text-end">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($brands as $brand)
                                            <tr>
                                                <td class="fw-bold text-center">{{ $brand->order_num }}</td>
                                                <td>
                                                    @if($brand->logo)
                                                        <img src="{{ asset($brand->logo) }}" class="rounded border p-1" style="width: 50px; height: 35px; object-fit: contain;">
                                                    @else
                                                        <span class="badge bg-light text-muted border">No Logo</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong class="text-dark d-block">{{ $brand->name }}</strong>
                                                    <small class="text-muted"><code>{{ $brand->slug }}</code></small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border">{{ $brand->products_count }} টি পণ্য</span>
                                                </td>
                                                <td>
                                                    @if($brand->is_active)
                                                        <span class="badge bg-success">সক্রিয়</span>
                                                    @else
                                                        <span class="badge bg-danger">নিষ্ক্রিয়</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('admin.ecommerce.brands.destroy', $brand->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('মুছে ফেলতে চান?')">
                                                        <i class="ti-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">কোনো ব্র্যান্ড তৈরি করা হয়নি।</td>
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

