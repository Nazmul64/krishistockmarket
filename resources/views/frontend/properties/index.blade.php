@extends('layouts.frontend.app')

@section('content')
<!-- Hero Section for Properties -->
<div class="properties-hero-banner py-5" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #064e3b 100%); color: #ffffff; position: relative;">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-3 rounded-pill">
                    <i class="fa fa-shield me-1"></i> ১০০% নিষ্কণ্টক ও আইনি যাচাইকৃত প্রপার্টি
                </span>
                <h1 class="fw-extrabold display-5 mb-3 text-white">
                    জমি, প্লট ও প্রপার্টি ক্যাটালগ
                </h1>
                <p class="lead text-slate-300 mb-4" style="color: #cbd5e1; font-size: 1.15rem;">
                    নিরাপদ ও লাভজনক রিয়েল এস্টেট বিনিয়োগ। যাচাইকৃত জমি, আবাসিক প্লট, ফ্ল্যাট ও বাণিজ্যিক স্পেস কিনুন অথবা আকর্ষণীয় কিস্তিতে বুকিং দিন।
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-2 bg-white bg-opacity-10 px-3 py-2 rounded-3 border border-white border-opacity-10">
                        <i class="fa fa-check-circle text-success fs-5"></i>
                        <span class="small">খতিয়ান ও দাগ ভেরিফাইড</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 bg-white bg-opacity-10 px-3 py-2 rounded-3 border border-white border-opacity-10">
                        <i class="fa fa-check-circle text-success fs-5"></i>
                        <span class="small">সহজ কিস্তি সুবিধা</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 bg-white bg-opacity-10 px-3 py-2 rounded-3 border border-white border-opacity-10">
                        <i class="fa fa-check-circle text-success fs-5"></i>
                        <span class="small">দ্রুত সাফ-কবলা রেজিস্ট্রি</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-none d-lg-block text-end">
                <div class="bg-white p-4 rounded-4 shadow-lg text-dark text-start" style="border: 2px solid #f59e0b;">
                    <div class="small text-muted fw-bold text-uppercase mb-1">মোট উপলব্ধ প্রপার্টি</div>
                    <h2 class="fw-bold text-success mb-2">{{ $totalProperties }}+ টি সাইট</h2>
                    <p class="small text-muted mb-3">ঢাকা, পূর্বাচল, উত্তরা, মিরপুর ও সাভারে প্রস্তুত প্রপার্টি সরাসরি পরিদর্শন করুন।</p>
                    <a href="#propertyListSection" class="btn btn-warning w-100 fw-bold py-2">
                        <i class="fa fa-arrow-down me-1"></i> প্রপার্টি সমূহ দেখুন
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search & Category Filter Navigation Bar -->
<div class="py-3 bg-white border-bottom shadow-sm sticky-top" style="top: 74px; z-index: 99;" id="propertyListSection">
    <div class="container">
        <form action="{{ route('properties.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5 col-12">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa fa-search text-muted"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0" placeholder="প্রপার্টির নাম, জেলা, থানা, দাগ বা মৌজা লিখুন...">
                </div>
            </div>
            <div class="col-md-5 col-8">
                <div class="d-flex gap-2 overflow-auto py-1" style="white-space: nowrap;">
                    <a href="{{ route('properties.index') }}" class="btn btn-sm {{ !request('type') || request('type') == 'all' ? 'btn-success text-white' : 'btn-outline-secondary' }} rounded-pill px-3">
                        সব প্রপার্টি
                    </a>
                    <a href="{{ route('properties.index', ['type' => 'প্লট', 'q' => request('q')]) }}" class="btn btn-sm {{ request('type') == 'প্লট' ? 'btn-success text-white' : 'btn-outline-secondary' }} rounded-pill px-3">
                        প্লট
                    </a>
                    <a href="{{ route('properties.index', ['type' => 'জমি', 'q' => request('q')]) }}" class="btn btn-sm {{ request('type') == 'জমি' ? 'btn-success text-white' : 'btn-outline-secondary' }} rounded-pill px-3">
                        জমি / ফার্ম
                    </a>
                    <a href="{{ route('properties.index', ['type' => 'ফ্ল্যাট', 'q' => request('q')]) }}" class="btn btn-sm {{ request('type') == 'ফ্ল্যাট' ? 'btn-success text-white' : 'btn-outline-secondary' }} rounded-pill px-3">
                        ফ্ল্যাট
                    </a>
                    <a href="{{ route('properties.index', ['type' => 'বাণিজ্যিক', 'q' => request('q')]) }}" class="btn btn-sm {{ request('type') == 'বাণিজ্যিক' ? 'btn-success text-white' : 'btn-outline-secondary' }} rounded-pill px-3">
                        বাণিজ্যিক স্পেস
                    </a>
                </div>
            </div>
            <div class="col-md-2 col-4 text-end">
                <button type="submit" class="btn btn-dark w-100 btn-sm py-2 fw-bold">
                    <i class="fa fa-filter me-1"></i> ফিল্টার
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Main Property Listing Section -->
<div class="py-5" style="background-color: #f8fafc; min-height: 600px;">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">উপলব্ধ জমি ও প্রপার্টি তালিকা</h4>
                <p class="text-muted small mb-0">সর্বমোট {{ $properties->total() }} টি সাইট প্রদর্শিত হচ্ছে</p>
            </div>
            @if(request('q') || request('type'))
                <a href="{{ route('properties.index') }}" class="btn btn-sm btn-outline-danger">
                    <i class="fa fa-times me-1"></i> ফিল্টার মুছুন
                </a>
            @endif
        </div>

        <div class="row g-4">
            @forelse($properties as $item)
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden property-card-hover" style="transition: transform 0.25s ease, box-shadow 0.25s ease;">
                        <!-- Property Image with Badges -->
                        <div class="position-relative" style="height: 220px; overflow: hidden; background: #e2e8f0;">
                            <img src="{{ $item->featured_image ?: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800&auto=format&fit=crop&q=60' }}" 
                                 alt="{{ $item->property_name }}" 
                                 class="w-100 h-100" 
                                 style="object-fit: cover; transition: transform 0.4s ease;">
                            
                            <!-- Badges -->
                            <div class="position-absolute top-0 start-0 m-3 d-flex flex-column gap-1">
                                <span class="badge bg-dark bg-opacity-75 text-white px-2 py-1 rounded">
                                    {{ $item->property_type_name }}
                                </span>
                                @if($item->is_featured)
                                    <span class="badge bg-warning text-dark px-2 py-1 rounded fw-bold">
                                        <i class="fa fa-star me-1"></i> স্পেশাল অফার
                                    </span>
                                @endif
                            </div>

                            <div class="position-absolute top-0 end-0 m-3">
                                @if($item->status === 'available')
                                    <span class="badge bg-success text-white px-2 py-1 rounded-pill shadow-sm">
                                        বিক্রয়যোগ্য
                                    </span>
                                @elseif($item->status === 'reserved')
                                    <span class="badge bg-warning text-dark px-2 py-1 rounded-pill shadow-sm">
                                        রিজার্ভড
                                    </span>
                                @endif
                            </div>

                            <!-- Area Badge at Bottom-Right of Image -->
                            <div class="position-absolute bottom-0 end-0 m-3 bg-dark bg-opacity-80 text-white px-3 py-1 rounded-3 small fw-bold">
                                <i class="fa fa-expand me-1 text-warning"></i> {{ number_format($item->total_area, 2) }} {{ $item->measurement_unit }}
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="small text-muted font-monospace mb-1">{{ $item->property_code }}</div>
                            <h5 class="fw-bold text-dark mb-2" style="font-size: 1.15rem; line-height: 1.4;">
                                <a href="{{ route('properties.show', $item->id) }}" class="text-dark text-decoration-none hover-primary">
                                    {{ $item->property_name }}
                                </a>
                            </h5>

                            <!-- Location Info -->
                            <div class="d-flex align-items-center text-muted small mb-3">
                                <i class="fa fa-map-marker text-danger me-2 fs-6"></i>
                                <span>{{ $item->upazila ? $item->upazila . ', ' : '' }}{{ $item->district }}</span>
                                @if($item->dag_number)
                                    <span class="mx-1">•</span>
                                    <span>দাগ: {{ $item->dag_number }}</span>
                                @endif
                            </div>

                            <!-- Highlights / Description -->
                            @if($item->features)
                                <div class="bg-light p-2 rounded-3 mb-3 small text-slate-700 text-truncate">
                                    <i class="fa fa-check text-success me-1"></i> {{ $item->features }}
                                </div>
                            @elseif($item->description)
                                <p class="text-muted small mb-3 text-truncate-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $item->description }}
                                </p>
                            @endif

                            <div class="mt-auto pt-3 border-top">
                                <!-- Price Row -->
                                <div class="d-flex align-items-end justify-content-between mb-3">
                                    <div>
                                        <div class="small text-muted">সর্বমোট বিক্রয়মূল্য:</div>
                                        <div class="fw-bold font-monospace text-success fs-5">
                                            ৳ {{ number_format($item->selling_price) }}
                                        </div>
                                    </div>
                                    @if($item->booking_amount > 0)
                                        <div class="text-end">
                                            <div class="small text-muted">বুকিং ডিপোজিট:</div>
                                            <div class="fw-bold font-monospace text-primary">
                                                ৳ {{ number_format($item->booking_amount) }}
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="row g-2">
                                    <div class="col-6">
                                        <a href="{{ route('properties.show', $item->id) }}" class="btn btn-outline-dark btn-sm w-100 py-2 fw-bold">
                                            <i class="fa fa-eye me-1"></i> বিস্তারিত
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" 
                                                class="btn btn-success btn-sm w-100 py-2 fw-bold"
                                                onclick="openBookingModal('{{ $item->id }}', '{{ addslashes($item->property_name) }}', '{{ number_format($item->selling_price) }}')">
                                            <i class="fa fa-paper-plane me-1"></i> বুকিং / সাইট
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 py-5 text-center">
                    <div class="p-5 bg-white rounded-4 shadow-sm">
                        <i class="fa fa-map-o fa-4x text-muted mb-3 opacity-50"></i>
                        <h4 class="fw-bold text-dark">কোনো প্রপার্টি খুঁজে পাওয়া যায়নি!</h4>
                        <p class="text-muted">আপনার খোঁজের সাথে মিল রেখে বর্তমানে কোনো প্রপার্টি নেই। অনুগ্রহ করে অন্য ফিল্টার চেষ্টা করুন।</p>
                        <a href="{{ route('properties.index') }}" class="btn btn-success fw-bold px-4">সকল প্রপার্টি দেখুন</a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($properties->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $properties->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Customer Site Visit / Booking Modal -->
<div class="modal fade" id="customerInquiryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-success text-white py-3 px-4">
                <h5 class="modal-title fw-bold mb-0">
                    <i class="fa fa-calendar-check-o me-2"></i> সাইট পরিদর্শন বা বুকিং আবেদন
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('properties.inquiry') }}" method="POST">
                @csrf
                <input type="hidden" name="property_id" id="modalPropertyId">
                <div class="modal-body p-4">
                    <div class="alert alert-light border mb-3 p-3 rounded-3">
                        <div class="small text-muted">নির্বাচিত প্রপার্টি:</div>
                        <div class="fw-bold text-dark" id="modalPropertyName">প্রপার্টি লোড হচ্ছে...</div>
                        <div class="small text-success fw-bold mt-1" id="modalPropertyPrice">মূল্য: ৳ ০</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold">আপনার নাম <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" required class="form-control" value="{{ Auth::check() ? Auth::user()->name : '' }}" placeholder="আপনার পূর্ণ নাম লিখুন">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">মোবাইল নম্বর <span class="text-danger">*</span></label>
                            <input type="text" name="customer_phone" required class="form-control" value="{{ Auth::check() ? Auth::user()->phone : '' }}" placeholder="০১xxxxxxxxx">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">ইমেইল (যদি থাকে)</label>
                            <input type="email" name="customer_email" class="form-control" value="{{ Auth::check() ? Auth::user()->email : '' }}" placeholder="email@example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">আবেদনের ধরন <span class="text-danger">*</span></label>
                            <select name="inquiry_type" class="form-select" required>
                                <option value="site_visit" selected>সাইট পরিদর্শন (Site Visit)</option>
                                <option value="booking_request">সরাসরি বুকিং আবেদন (Booking)</option>
                                <option value="price_inquiry">বিস্তারিত ও মূল্য আলোচনা</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">পছন্দের তারিখ</label>
                            <input type="date" name="preferred_date" class="form-control" min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">আপনার কোনো প্রশ্ন বা বিশেষ নোট</label>
                            <textarea name="message" rows="2" class="form-control" placeholder="যেমন: শুক্রবারে সাইট ভিজিট করতে চাই অথবা কিস্তি সুবিধা জানতে চাই..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3 px-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success fw-bold px-4">
                        <i class="fa fa-send me-1"></i> আবেদন সাবমিট করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openBookingModal(id, name, price) {
    document.getElementById('modalPropertyId').value = id;
    document.getElementById('modalPropertyName').innerText = name;
    document.getElementById('modalPropertyPrice').innerText = 'মূল্য: ৳ ' + price;
    var modal = new bootstrap.Modal(document.getElementById('customerInquiryModal'));
    modal.show();
}
</script>

<style>
.property-card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.12) !important;
}
.hover-primary:hover {
    color: #16a34a !important;
}
</style>
@endsection
