@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="fa fa-map-signs text-success me-2"></i> আমার জমি ও প্রপার্টি আবেদনসমূহ (My Property Bookings & Visits)
                    </h3>
                    <p class="text-muted mb-0">আপনার জমি, প্লট, ফ্ল্যাট পরিদর্শন ও বুকিং আবেদনের বিস্তারিত বিবরণ ও অগ্রগতি</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('properties.index') }}" target="_blank" class="btn btn-success fw-bold">
                        <i class="fa fa-external-link me-1"></i> নতুন প্রপার্টি ব্রাউজ করুন
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- 1. Stats Counter Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card border-0 rounded-4 shadow-sm p-3 bg-white h-100 border-start border-4 border-primary">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted small fw-bold">মোট আবেদন</span>
                            <div class="rounded-circle p-2" style="background: #eff6ff; color: #2563eb;">
                                <i class="fa fa-file-text-o"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-0 text-primary">{{ $total_count }}</h3>
                        <small class="text-muted">সর্বমোট বুকিং ও ভিজিট</small>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card border-0 rounded-4 shadow-sm p-3 bg-white h-100 border-start border-4 border-info">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted small fw-bold">সাইট পরিদর্শন</span>
                            <div class="rounded-circle p-2" style="background: #f0fdfa; color: #0891b2;">
                                <i class="fa fa-eye"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-0 text-info">{{ $site_visits_count }}</h3>
                        <small class="text-muted">মাঠ পরিদর্শনের আবেদন</small>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card border-0 rounded-4 shadow-sm p-3 bg-white h-100 border-start border-4 border-warning">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted small fw-bold">সরাসরি বুকিং</span>
                            <div class="rounded-circle p-2" style="background: #fffbeb; color: #d97706;">
                                <i class="fa fa-bookmark"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-0 text-warning">{{ $bookings_count }}</h3>
                        <small class="text-muted">জমি ও ফ্ল্যাট বুকিং</small>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card border-0 rounded-4 shadow-sm p-3 bg-white h-100 border-start border-4 border-success">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted small fw-bold">নিশ্চিত ও নির্ধারিত</span>
                            <div class="rounded-circle p-2" style="background: #ecfdf5; color: #059669;">
                                <i class="fa fa-check-circle"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-0 text-success">{{ $confirmed_count }}</h3>
                        <small class="text-muted">অনুমোদিত / কনফার্মড</small>
                    </div>
                </div>
            </div>

            <!-- 2. Main Inquiries List Box -->
            <div class="box shadow-sm rounded-4 border-0">
                <div class="box-header with-border bg-white d-flex align-items-center justify-content-between flex-wrap gap-2 py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <h4 class="box-title text-dark fw-bold mb-0">আবেদনের তালিকা</h4>
                        <span class="badge bg-light text-dark border px-2 py-1">{{ $inquiries->total() }} টি রেকর্ড</span>
                    </div>

                    <!-- Filter Tabs -->
                    <div class="d-flex gap-1 flex-wrap">
                        <a href="{{ route('user.properties.my_bookings', ['status' => 'all']) }}" class="btn btn-sm {{ !request('status') || request('status') === 'all' ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill">সব</a>
                        <a href="{{ route('user.properties.my_bookings', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning text-dark' : 'btn-outline-secondary' }} rounded-pill">অপেক্ষমাণ (Pending)</a>
                        <a href="{{ route('user.properties.my_bookings', ['status' => 'scheduled']) }}" class="btn btn-sm {{ request('status') === 'scheduled' ? 'btn-info text-white' : 'btn-outline-secondary' }} rounded-pill">শিডিউলড (Scheduled)</a>
                        <a href="{{ route('user.properties.my_bookings', ['status' => 'confirmed']) }}" class="btn btn-sm {{ request('status') === 'confirmed' ? 'btn-success' : 'btn-outline-secondary' }} rounded-pill">নিশ্চিত (Confirmed)</a>
                        <a href="{{ route('user.properties.my_bookings', ['status' => 'cancelled']) }}" class="btn btn-sm {{ request('status') === 'cancelled' ? 'btn-danger' : 'btn-outline-secondary' }} rounded-pill">বাতিল (Cancelled)</a>
                    </div>
                </div>

                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">ট্র্যাকিং কোড</th>
                                    <th>প্রপার্টির নাম ও বিবরণ</th>
                                    <th>আবেদনের ধরন</th>
                                    <th>পছন্দের তারিখ</th>
                                    <th>স্ট্যাটাস</th>
                                    <th>আবেদনের সময়</th>
                                    <th class="text-end pe-4">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($inquiries as $item)
                                    <tr>
                                        <!-- Tracking Code -->
                                        <td class="ps-4">
                                            <span class="badge bg-secondary font-monospace px-2 py-1 fs-13">{{ $item->inquiry_code }}</span>
                                        </td>

                                        <!-- Property Info -->
                                        <td>
                                            @if($item->property)
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item->property->featured_image ?: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=120' }}" 
                                                         alt="{{ $item->property->property_name }}" 
                                                         class="rounded-3 me-2" 
                                                         style="width: 50px; height: 42px; object-fit: cover;">
                                                    <div>
                                                        <a href="{{ route('properties.show', $item->property->id) }}" target="_blank" class="fw-bold text-dark text-decoration-none">
                                                            {{ $item->property->property_name }}
                                                        </a>
                                                        <div class="small text-muted">
                                                            <span><i class="fa fa-map-marker text-danger me-1"></i>{{ $item->property->district }}</span>
                                                            <span class="mx-1">•</span>
                                                            <span class="text-success fw-bold">৳ {{ number_format($item->property->selling_price) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-muted fw-bold">
                                                    <i class="fa fa-info-circle me-1 text-primary"></i> সাধারণ অনুসন্ধান
                                                </div>
                                            @endif
                                        </td>

                                        <!-- Inquiry Type -->
                                        <td>
                                            @if($item->inquiry_type === 'site_visit')
                                                <span class="badge bg-info bg-opacity-10 text-info border border-info px-2 py-1">
                                                    <i class="fa fa-car me-1"></i> সাইট পরিদর্শন
                                                </span>
                                            @elseif($item->inquiry_type === 'booking_request')
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1">
                                                    <i class="fa fa-bookmark me-1"></i> বুকিং আবেদন
                                                </span>
                                            @elseif($item->inquiry_type === 'invest_inquiry')
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-2 py-1">
                                                    <i class="fa fa-pie-chart me-1"></i> শেয়ার / বিনিয়োগ
                                                </span>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1">
                                                    <i class="fa fa-question-circle me-1"></i> তথ্য জিজ্ঞাসা
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Preferred Date -->
                                        <td>
                                            @if($item->preferred_date)
                                                <div class="fw-bold text-dark">
                                                    <i class="fa fa-calendar text-muted me-1"></i> {{ date('d M, Y', strtotime($item->preferred_date)) }}
                                                </div>
                                            @else
                                                <span class="text-muted small">নির্ধারিত নেই</span>
                                            @endif
                                        </td>

                                        <!-- Status Badge -->
                                        <td>
                                            @if($item->status === 'pending')
                                                <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold">
                                                    <i class="fa fa-clock-o me-1"></i> অপেক্ষমাণ (Pending)
                                                </span>
                                            @elseif($item->status === 'contacted')
                                                <span class="badge bg-primary text-white px-3 py-1 rounded-pill fw-bold">
                                                    <i class="fa fa-phone me-1"></i> যোগাযোগ করা হয়েছে
                                                </span>
                                            @elseif($item->status === 'scheduled')
                                                <span class="badge bg-info text-white px-3 py-1 rounded-pill fw-bold">
                                                    <i class="fa fa-calendar-check-o me-1"></i> পরিদর্শন নির্ধারিত
                                                </span>
                                            @elseif($item->status === 'confirmed')
                                                <span class="badge bg-success text-white px-3 py-1 rounded-pill fw-bold">
                                                    <i class="fa fa-check me-1"></i> চূড়ান্ত নিশ্চিত (Confirmed)
                                                </span>
                                            @elseif($item->status === 'cancelled')
                                                <span class="badge bg-danger text-white px-3 py-1 rounded-pill fw-bold">
                                                    <i class="fa fa-times me-1"></i> বাতিল
                                                </span>
                                            @else
                                                <span class="badge bg-secondary text-white px-3 py-1 rounded-pill">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Created At -->
                                        <td>
                                            <div class="small text-muted">{{ $item->created_at->format('d M, Y') }}</div>
                                            <div class="small text-muted">{{ $item->created_at->format('h:i A') }}</div>
                                        </td>

                                        <!-- Action Buttons -->
                                        <td class="text-end pe-4">
                                            <div class="d-inline-flex gap-1">
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-primary"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#inquiryDetailModal{{ $item->id }}"
                                                        title="বিস্তারিত দেখুন">
                                                    <i class="fa fa-eye"></i> বিস্তারিত
                                                </button>

                                                @if($item->status === 'pending' || $item->status === 'contacted')
                                                    <form action="{{ route('user.properties.cancel_inquiry', $item->id) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই আবেদনটি বাতিল করতে চান?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="বাতিল করুন">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>

                                            <!-- Modal: Inquiry Details -->
                                            <div class="modal fade text-start" id="inquiryDetailModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content rounded-4 border-0 shadow">
                                                        <div class="modal-header bg-light">
                                                            <h5 class="modal-title fw-bold text-dark">
                                                                <i class="fa fa-file-text-o text-success me-2"></i> আবেদনের বিস্তারিত ({{ $item->inquiry_code }})
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            @if($item->property)
                                                                <div class="card bg-light border-0 rounded-3 p-3 mb-3">
                                                                    <div class="fw-bold text-dark fs-15">{{ $item->property->property_name }}</div>
                                                                    <div class="small text-muted">কোড: {{ $item->property->property_code }} | জেলা: {{ $item->property->district }}</div>
                                                                    <div class="small text-success fw-bold mt-1">নির্ধারিত মূল্য: ৳ {{ number_format($item->property->selling_price) }}</div>
                                                                </div>
                                                            @endif

                                                            <div class="row g-2 mb-3">
                                                                <div class="col-6">
                                                                    <div class="small text-muted">আবেদনকারীর নাম:</div>
                                                                    <strong class="text-dark">{{ $item->customer_name }}</strong>
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="small text-muted">মোবাইল নম্বর:</div>
                                                                    <strong class="text-dark">{{ $item->customer_phone }}</strong>
                                                                </div>
                                                                <div class="col-6 mt-2">
                                                                    <div class="small text-muted">আবেদনের ধরন:</div>
                                                                    <strong class="text-dark">{{ ucfirst(str_replace('_', ' ', $item->inquiry_type)) }}</strong>
                                                                </div>
                                                                <div class="col-6 mt-2">
                                                                    <div class="small text-muted">পছন্দের তারিখ:</div>
                                                                    <strong class="text-dark">{{ $item->preferred_date ? date('d M, Y', strtotime($item->preferred_date)) : 'উল্লেখ নেই' }}</strong>
                                                                </div>
                                                            </div>

                                                            @if($item->message)
                                                                <div class="mb-3">
                                                                    <div class="small text-muted fw-bold mb-1">আপনার নোট / জিজ্ঞাসা:</div>
                                                                    <div class="p-2 bg-light rounded text-muted small" style="white-space: pre-wrap;">{{ $item->message }}</div>
                                                                </div>
                                                            @endif

                                                            @if($item->admin_notes)
                                                                <div class="mb-3">
                                                                    <div class="small text-muted fw-bold mb-1">অফিস / অ্যাডমিন আপডেট:</div>
                                                                    <div class="p-2 rounded small bg-warning bg-opacity-10 text-dark border border-warning" style="white-space: pre-wrap;">{{ $item->admin_notes }}</div>
                                                                </div>
                                                            @endif

                                                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                                                <span class="small text-muted">আবেদনের সময়: {{ $item->created_at->format('d M, Y h:i A') }}</span>
                                                                @if($item->property)
                                                                    <a href="{{ route('properties.show', $item->property->id) }}" target="_blank" class="btn btn-sm btn-success">
                                                                        <i class="fa fa-external-link me-1"></i> প্রপার্টি পেজ
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="py-4">
                                                <div class="rounded-circle d-inline-flex p-3 mb-3 bg-light text-muted">
                                                    <i class="fa fa-map-signs fa-3x opacity-50"></i>
                                                </div>
                                                <h5 class="fw-bold text-dark mb-1">বর্তমানে কোনো প্রপার্টি আবেদন নেই</h5>
                                                <p class="text-muted small mb-3">আপনি এখনো কোনো জমি, প্লট বা ফ্ল্যাট পরিদর্শন ও বুকিং এর জন্য আবেদন করেননি।</p>
                                                <a href="{{ route('properties.index') }}" target="_blank" class="btn btn-success fw-bold px-4 rounded-pill">
                                                    <i class="fa fa-search me-1"></i> আমাদের প্রপার্টিসমূহ দেখুন
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($inquiries->hasPages())
                    <div class="box-footer bg-white py-3 px-4 d-flex justify-content-center">
                        {{ $inquiries->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection

