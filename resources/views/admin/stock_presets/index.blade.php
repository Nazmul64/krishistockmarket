@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title">Stock Package Presets (স্টক প্যাকেজ প্রিসেটসমূহ)</h4>
                    <p class="text-muted mb-0">দ্রুত স্টক তৈরির জন্য প্যাকেজ প্রিসেট সেটআপ করুন</p>
                </div>
                <div>
                    <a href="{{ route('admin.stock.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus me-1"></i> Add Stock Page</a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <!-- Add New Preset Form -->
                <div class="col-xl-4 col-lg-5 mb-4">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-plus-circle me-2 text-success"></i> নতুন স্টক প্রিসেট যোগ করুন</h4>
                        </div>
                        <div class="box-body">
                            <form method="POST" action="{{ route('admin.stock_preset.store') }}">
                                @csrf

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">বোটনের লেবেল (Display Title) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="title" required placeholder="যেমন: ৳৫,০০০ স্টক">
                                    <small class="text-muted">যেমন: ৳৫,০০০ স্টক, ৳১০,০০০ স্টক, ৳১,০০,০০০ স্টক</small>
                                    @error('title') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">প্যাকেজের নাম (Package Stock Name) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="package_name" required placeholder="যেমন: Stock Package ৳5,000">
                                    @error('package_name') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">মূল্য (Selling & Buying Price) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="price" step="0.01" min="0" required placeholder="যেমন: 5000">
                                    @error('price') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold">ডিফল্ট পরিমাণ (Default Quantity) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="quantity" min="1" value="10" required placeholder="যেমন: 10">
                                    @error('quantity') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                </div>

                                <button type="submit" class="btn btn-success w-100 py-2"><i class="fa fa-save me-1"></i> সেভ করুন</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Presets List Table -->
                <div class="col-xl-8 col-lg-7">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-list me-2 text-info"></i> সকল স্টক প্যাকেজ প্রিসেট ({{ count($presets) }})</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th>ডিভিপ্লে লেবেল (Title)</th>
                                            <th>প্যাকেজের নাম</th>
                                            <th>মূল্য (৳)</th>
                                            <th>পরিমাণ</th>
                                            <th class="text-center" style="width: 140px;">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($presets as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td class="fw-bold text-success">{{ $item->title }}</td>
                                            <td class="fw-bold text-dark">{{ $item->package_name }}</td>
                                            <td>৳{{ number_format($item->price, 2) }}</td>
                                            <td>{{ $item->quantity }} টি</td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.stock_preset.edit', $item->id) }}" class="btn btn-sm btn-info me-1" title="Edit"><i class="fa fa-edit"></i> Edit</a>
                                                <a href="{{ route('admin.stock_preset.destroy', $item->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('আপনি কি এই প্যাকেজ প্রিসেটটি মুছে ফেলতে চান?')" title="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">কোনো স্টক প্রিসেট পাওয়া যায়নি।</td>
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
