@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title">Monthly Grocery Management (মাসিক বাজার মডিউল)</h4>
                    <p class="text-muted mb-0">মাসিক বাজারের খাদ্য পণ্য প্যাকেজসমূহ পরিচালনা করুন</p>
                </div>
                <div>
                    <a href="{{ route('admin.monthly_bazaar.orders') }}" class="btn btn-info btn-sm"><i class="fa fa-shopping-cart me-1"></i> অর্ডার রিকোয়েস্ট তালিকা</a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <!-- Add New Monthly Grocery Package Form -->
                <div class="col-xl-4 col-lg-5 mb-4">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-plus-circle me-2 text-success"></i> নতুন মাসিক বাজার প্যাকেজ যোগ করুন</h4>
                        </div>
                        <div class="box-body">
                            <form method="POST" action="{{ route('admin.monthly_bazaar.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">প্যাকেজের টাইটেল (Title) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="title" required placeholder="যেমন: ৳২,৫০০ মাসিক খাদ্য প্যাক">
                                    @error('title') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">প্যাকেজের নাম (Package Name) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="package_name" required placeholder="যেমন: ২৫০০ টাকার চাল, ডাল, তেল খাদ্য সামগ্রী">
                                    @error('package_name') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">মূল্য (Price ৳) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="price" step="0.01" min="0" required placeholder="যেমন: 2500">
                                    @error('price') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">স্টক পরিমাণ (Quantity) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="quantity" min="1" value="10" required placeholder="যেমন: 10">
                                    @error('quantity') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">প্যাকেজের ছবি (Package Image)</label>
                                    <input type="file" class="form-control" name="image">
                                    @error('image') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold">খাদ্য পণ্যের তালিকা ও বিবরণী (Description)</label>
                                    <textarea class="form-control" name="description" rows="4" placeholder="যেমন: মিনিকেট চাল ১০ কেজী, মসুর ডাল ২ কেজী, সোয়াবিন তেল ২ লিটার..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-success w-100 py-2"><i class="fa fa-save me-1"></i> সেভ করুন</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Monthly Grocery Packages Table -->
                <div class="col-xl-8 col-lg-7">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-list me-2 text-primary"></i> সকল মাসিক বাজার প্যাকেজ তালিকা ({{ count($items) }})</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 40px;">#</th>
                                            <th style="width: 60px;">ছবি</th>
                                            <th>টাইটেল ও নাম</th>
                                            <th>মূল্য (৳)</th>
                                            <th>স্টক / বিক্রিত</th>
                                            <th class="text-center" style="width: 130px;">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($items as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                @if($item->image && file_exists(public_path('upload/monthly_bazaar/'.$item->image)))
                                                    <img src="{{ asset('upload/monthly_bazaar/'.$item->image) }}" style="width: 45px; height: 45px; object-fit: cover; border-radius: 6px;">
                                                @else
                                                    <span class="badge bg-secondary">No Image</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong class="text-dark">{{ $item->title }}</strong><br>
                                                <small class="text-muted">{{ $item->package_name }}</small>
                                            </td>
                                            <td class="fw-bold text-success">৳{{ number_format($item->price, 2) }}</td>
                                            <td>
                                                <span class="badge bg-info">মজুদ: {{ $item->quantity }}</span><br>
                                                <small class="text-muted">বিক্রি: {{ $item->sold_quantity }}</small>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.monthly_bazaar.edit', $item->id) }}" class="btn btn-sm btn-info me-1" title="Edit"><i class="fa fa-edit"></i> Edit</a>
                                                <a href="{{ route('admin.monthly_bazaar.destroy', $item->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('আপনি কি এই প্যাকেজটি মুছে ফেলতে চান?')" title="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">কোনো মাসিক বাজার প্যাকেজ পাওয়া যায়নি।</td>
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
