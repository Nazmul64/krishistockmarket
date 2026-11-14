@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1"><i class="ti-image text-info me-2"></i>হোমপেজ স্লাইডার ম্যানেজমেন্ট</h3>
                    <p class="text-muted mb-0">হোমপেজের মূল ব্যানার স্লাইডার আপলোড, পরিবর্তন ও সময়সূচী নির্ধারণ করুন</p>
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
                <!-- Left: Upload New Slider Form -->
                <div class="col-lg-4 col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border">
                            <h4 class="box-title"><i class="ti-plus me-1 text-primary"></i>নতুন স্লাইডার যোগ করুন</h4>
                        </div>
                        <div class="box-body">
                            <form action="{{ route('admin.ecommerce.sliders.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">স্লাইডার ইমেজ <span class="text-danger">*</span></label>
                                    <input type="file" name="image" class="form-control" required accept="image/*">
                                    <small class="text-muted">সংরক্ষিত হবে: <code>public/uploads/slider/</code> (অনুরূপ মাপ: ১২০০ × ৫০০ পিক্সেল)</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">শিরোনাম (Title)</label>
                                    <input type="text" name="title" class="form-control" placeholder="যেমন: এখন ঢাকাজুড়ে ফ্রি হোম ডেলিভারি!">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">সাব-টাইটেল (Subtitle)</label>
                                    <input type="text" name="subtitle" class="form-control" placeholder="যেমন: স্পেশাল সুপারশপ অফার">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">বিবরণ / ফিচার ট্যাগ</label>
                                    <textarea name="description" rows="2" class="form-control" placeholder="যেমন: বিশ্বস্ত পণ্য | তাজা ও মানসম্মত | দ্রুত ডেলিভারি"></textarea>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label fw-bold">বাটন টেক্সট</label>
                                        <input type="text" name="button_text" class="form-control" value="এখনই অর্ডার করুন →">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-bold">বাটন লিংক</label>
                                        <input type="text" name="button_link" class="form-control" value="#exclusive-deals">
                                    </div>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label fw-bold">হটলাইন নম্বর</label>
                                        <input type="text" name="hotline" class="form-control" value="09617900200">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-bold">সিরিয়াল ক্রম (Order)</label>
                                        <input type="number" name="order_num" class="form-control" value="1">
                                    </div>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="sliderActive" checked>
                                    <label class="form-check-label fw-bold" for="sliderActive">সক্রিয় রাখুন (Active Status)</label>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti-upload me-1"></i> স্লাইডার সেভ করুন
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right: Existing Sliders List -->
                <div class="col-lg-8 col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border d-flex justify-content-between align-items-center">
                            <h4 class="box-title"><i class="ti-list me-1 text-success"></i>বর্তমান স্লাইডার তালিকা ({{ $sliders->count() }} টি)</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">ক্রম</th>
                                            <th style="width: 140px;">ছবি</th>
                                            <th>শিরোনাম ও টেক্সট</th>
                                            <th>বাটন ও লিংক</th>
                                            <th>স্ট্যাটাস</th>
                                            <th class="text-end" style="width: 100px;">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($sliders as $slider)
                                            <tr>
                                                <td class="text-center fw-bold">{{ $slider->order_num }}</td>
                                                <td>
                                                    <img src="{{ Str::startsWith($slider->image, 'http') ? $slider->image : asset($slider->image) }}" class="rounded shadow-sm border" style="width: 130px; height: 65px; object-fit: cover;">
                                                </td>
                                                <td>
                                                    <strong class="text-dark d-block">{{ $slider->title ?: '(কোনো টাইটেল নেই)' }}</strong>
                                                    <small class="text-muted">{{ $slider->subtitle }}</small>
                                                    <div class="text-muted small mt-1"><i class="fa fa-phone me-1 text-success"></i>{{ $slider->hotline }}</div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border">{{ $slider->button_text }}</span>
                                                    <div class="small text-truncate text-muted" style="max-width: 150px;">{{ $slider->button_link }}</div>
                                                </td>
                                                <td>
                                                    @if($slider->is_active)
                                                        <span class="badge bg-success">সক্রিয়</span>
                                                    @else
                                                        <span class="badge bg-danger">নিষ্ক্রিয়</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('admin.ecommerce.sliders.destroy', $slider->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('আপনি কি নিশ্চিত এই স্লাইডারটি মুছে ফেলতে চান?')" title="Delete">
                                                        <i class="ti-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">কোনো স্লাইডার আপলোড করা হয়নি।</td>
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

