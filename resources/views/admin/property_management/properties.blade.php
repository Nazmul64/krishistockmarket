@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="me-auto">
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="fa fa-map-marker text-success me-2"></i> জমি, প্লট ও প্রপার্টি ক্যাটালগ
                    </h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">ড্যাশবোর্ড</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.landshare.hub') }}">রিয়েল এস্টেট</a></li>
                                <li class="breadcrumb-item active" aria-current="page">জমি ও প্রপার্টি</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('properties.index') }}" target="_blank" class="btn btn-outline-success btn-sm shadow-sm px-3 py-2 fw-bold">
                        <i class="fa fa-external-link me-1"></i> ফ্রন্টএন্ডে লাইভ দেখুন (Frontend View)
                    </a>
                    <button type="button" class="btn btn-success btn-sm shadow-sm px-3 py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#modalAddProperty">
                        <i class="fa fa-plus-circle me-1"></i> + নতুন সম্পত্তি যোগ করুন (Add Property)
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="box bg-white border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="box-header with-border py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="box-title fw-bold text-dark mb-0">নিবন্ধিত জমি, প্লট ও ভবনের তালিকা</h5>
                    <form action="{{ route('admin.properties.index') }}" method="GET" class="d-flex gap-2">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="নাম, কোড, দাগ বা মৌজা খুঁজুন..." style="width: 250px;">
                        <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="fa fa-search"></i></button>
                        @if(request('q'))
                            <a href="{{ route('admin.properties.index') }}" class="btn btn-sm btn-link text-muted"><i class="fa fa-times"></i></a>
                        @endif
                    </form>
                </div>

                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>কোড ও প্রপার্টির নাম</th>
                                    <th>ধরন (Type)</th>
                                    <th>অবস্থান (Location)</th>
                                    <th>মোট আয়তন</th>
                                    <th>ক্রয় মূল্য</th>
                                    <th>বিক্রয় মূল্য</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($properties as $property)
                                    <tr>
                                        <td>
                                            <span class="badge bg-dark font-monospace">{{ $property->property_code }}</span>
                                            <div class="fw-bold text-dark mt-1">{{ $property->property_name }}</div>
                                            @if($property->dag_number || $property->khatian_number)
                                                <small class="text-muted">দাগ: {{ $property->dag_number ?: '-' }}, খতিয়ান: {{ $property->khatian_number ?: '-' }}</small>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-light text-dark border">{{ $property->property_type_name }}</span></td>
                                        <td>
                                            <i class="fa fa-map-marker text-danger me-1"></i>
                                            {{ $property->upazila ? $property->upazila . ', ' : '' }}{{ $property->district }}
                                        </td>
                                        <td><strong>{{ number_format($property->total_area, 2) }} {{ $property->measurement_unit }}</strong></td>
                                        <td class="font-monospace text-muted">৳ {{ number_format($property->purchase_price) }}</td>
                                        <td class="font-monospace fw-bold text-success">৳ {{ number_format($property->selling_price) }}</td>
                                        <td>
                                            @if($property->status === 'available')
                                                <span class="badge bg-success bg-opacity-25 text-success">বিক্রয়যোগ্য (Available)</span>
                                            @elseif($property->status === 'reserved')
                                                <span class="badge bg-warning bg-opacity-25 text-warning">বুকড / রিজার্ভ</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($property->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('properties.show', $property->id) }}" target="_blank" class="btn btn-sm btn-outline-success" title="ফ্রন্টএন্ডে দেখুন">
                                                <i class="fa fa-external-link"></i> ভিউ
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <i class="fa fa-building-o fa-3x mb-3 text-secondary opacity-50"></i>
                                            <p class="mb-0 fw-bold">কোনো প্রপার্টি পাওয়া যায়নি। উপরের বোতামে ক্লিক করে নতুন প্রপার্টি যুক্ত করুন।</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($properties->hasPages())
                    <div class="box-footer bg-light px-4 py-3">
                        {{ $properties->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>

<!-- ========================================================
    ADD PROPERTY MODAL (SRS Section 5.2)
======================================================== -->
<div class="modal fade" id="modalAddProperty" tabindex="-1" aria-labelledby="modalAddPropertyLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-success text-white py-3 px-4">
                <h5 class="modal-title fw-bold mb-0" id="modalAddPropertyLabel">
                    <i class="fa fa-plus-circle me-1"></i> নতুন সম্পত্তি এন্ট্রি ফরম (Add Property)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.properties.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">সম্পত্তির নাম (Property Title) <span class="text-danger">*</span></label>
                            <input type="text" name="property_name" required class="form-control" placeholder="যেমন: পূর্বাচল সেক্টর ২১ বাণিজ্যিক প্লট">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">প্রপার্টি কোড (Unique Code) <span class="text-danger">*</span></label>
                            <input type="text" name="property_code" required class="form-control font-monospace" value="PRP-{{ date('Y') }}-{{ rand(100, 999) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">সম্পত্তির ধরন <span class="text-danger">*</span></label>
                            <select name="property_type_name" class="form-select" required>
                                <option value="জমি / কৃষি জমি">জমি (Agricultural Land)</option>
                                <option value="আবাসিক প্লট" selected>আবাসিক প্লট (Residential Plot)</option>
                                <option value="বাণিজ্যিক প্লট">বাণিজ্যিক প্লট (Commercial Plot)</option>
                                <option value="আবাসিক ফ্ল্যাট">আবাসিক ফ্ল্যাট (Apartment / Flat)</option>
                                <option value="বাণিজ্যিক স্পেস">বাণিজ্যিক স্পেস (Commercial Space / Shop)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">বিভাগ <span class="text-danger">*</span></label>
                            <input type="text" name="division" required class="form-control" value="ঢাকা">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">জেলা <span class="text-danger">*</span></label>
                            <input type="text" name="district" required class="form-control" value="ঢাকা">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">উপজেলা / থানা <span class="text-danger">*</span></label>
                            <input type="text" name="upazila" required class="form-control" value="উত্তরা">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">মৌজা ও গ্রাম</label>
                            <input type="text" name="mouza" class="form-control" placeholder="মৌজার নাম">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">দাগ নম্বর (Dag Number)</label>
                            <input type="text" name="dag_number" class="form-control" placeholder="সিএস / আরএস / বিএস দাগ">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">খতিয়ান নম্বর (Khatian Number)</label>
                            <input type="text" name="khatian_number" class="form-control" placeholder="নামজারি খতিয়ান নম্বর">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">মোট আয়তন <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="total_area" required class="form-control" placeholder="যেমন: 5">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">পরিমাপের একক <span class="text-danger">*</span></label>
                            <select name="measurement_unit" class="form-select" required>
                                <option value="Katha" selected>কাঠা (Katha)</option>
                                <option value="Decimal">শতাংশ / ডেসিমাল (Decimal)</option>
                                <option value="Bigha">বিঘা (Bigha)</option>
                                <option value="SqFt">বর্গফুট (SqFt)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">ক্রয় মূল্য (৳)</label>
                            <input type="number" name="purchase_price" class="form-control font-monospace" placeholder="কোম্পানির ক্রয় মূল্য">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">বিক্রয় মূল্য (৳) <span class="text-danger">*</span></label>
                            <input type="number" name="selling_price" required class="form-control font-monospace text-success fw-bold" placeholder="নির্ধারিত বিক্রয় মূল্য">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">বুকিং ডিপোজিট মানি (৳)</label>
                            <input type="number" name="booking_amount" class="form-control font-monospace text-primary fw-bold" placeholder="যেমন: ২,০০,০০০">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">স্ট্যাটাস</label>
                            <select name="status" class="form-select">
                                <option value="available" selected>সরাসরি বিক্রয়যোগ্য (Available)</option>
                                <option value="reserved">রিজার্ভ / বুকড (Reserved)</option>
                                <option value="draft">ড্রাফট (Draft - ফ্রন্টএন্ডে দেখাবে না)</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">প্রপার্টির ছবি (Featured Image)</label>
                            <input type="file" name="featured_image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">সুযোগ-সুবিধা (Amenities - কমা দিয়ে লিখুন)</label>
                            <input type="text" name="features" class="form-control" placeholder="যেমন: ৬০ ফুট চওড়া রাস্তা, বিদ্যুৎ লাইন, গ্যাস সংযোগ, বাউন্ডারি ওয়াল">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">সম্পত্তির বিবরণ</label>
                            <textarea name="description" rows="3" class="form-control" placeholder="রোড কানেক্টিভিটি, ইউটিলিটি ও দলিল সংক্রান্ত বিবরণ লিখুন..."></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured" checked>
                                <label class="form-check-label small fw-bold" for="isFeatured">
                                    হোমপেজ ও ফ্রন্টএন্ডের স্পেশাল ফিচারে হাইলাইট করুন
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3 px-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success px-4 fw-bold">
                        <i class="fa fa-save me-1"></i> সম্পত্তি সংরক্ষণ ও পাবলিশ করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
