@extends('layouts.frontend.app')

@section('content')
<!-- Floating Animated Toast Notification for Booking / Inquiries -->
<div id="propertyToastNotification" class="property-toast-wrap" style="display: none;">
    <div class="property-toast-box">
        <div class="toast-icon-wrap">
            <i class="fa fa-check-circle toast-check-icon"></i>
        </div>
        <div class="toast-content-wrap">
            <div class="toast-title">🎉 আবেদন সফলভাবে গৃহীত হয়েছে!</div>
            <div class="toast-message" id="toastMessageText">আমাদের কাস্টমার রিপ্রেজেন্টেটিভ খুব শীঘ্রই যোগাযোগ করবেন।</div>
            <div class="toast-tracking-badge" id="toastTrackingCode"></div>
        </div>
        <button type="button" class="toast-close-btn" onclick="hidePropertyToast()">
            <i class="fa fa-times"></i>
        </button>
        <div class="toast-progress-bar">
            <div class="toast-progress-fill" id="toastProgressFill"></div>
        </div>
    </div>
</div>

<div class="py-4 bg-light border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('/') }}" class="text-decoration-none">হোম</a></li>
                <li class="breadcrumb-item"><a href="{{ route('properties.index') }}" class="text-decoration-none">জমি ও প্রপার্টি</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $property->property_name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="py-5" style="background-color: #f8fafc;">
    <div class="container">
        <div class="row g-4">
            <!-- Left Column: Media & Specifications -->
            <div class="col-lg-8">
                <div class="bg-white rounded-4 p-4 shadow-sm mb-4">
                    <!-- Featured Image -->
                    <div class="rounded-4 overflow-hidden position-relative mb-4" style="max-height: 460px;">
                        <img src="{{ $property->featured_image ?: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800&auto=format&fit=crop&q=60' }}" 
                             alt="{{ $property->property_name }}" 
                             class="w-100 h-100" 
                             style="object-fit: cover;">
                        <span class="position-absolute top-0 start-0 m-3 badge bg-dark px-3 py-2 fs-6">
                            {{ $property->property_type_name }}
                        </span>
                        <span class="position-absolute top-0 end-0 m-3 badge bg-success px-3 py-2 fs-6">
                            {{ ucfirst($property->status) }}
                        </span>
                    </div>

                    <!-- Title & Code -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                        <span class="badge bg-light text-muted border font-monospace">{{ $property->property_code }}</span>
                        <span class="text-muted small"><i class="fa fa-calendar me-1"></i> তালিকাভুক্ত: {{ $property->created_at->format('d M, Y') }}</span>
                    </div>
                    <h2 class="fw-bold text-dark mb-3">{{ $property->property_name }}</h2>
                    <p class="text-muted fs-6 mb-4">
                        <i class="fa fa-map-marker text-danger me-2"></i>
                        {{ $property->mouza ? 'মৌজা: ' . $property->mouza . ', ' : '' }}
                        {{ $property->upazila ? $property->upazila . ', ' : '' }}
                        {{ $property->district }}, {{ $property->division }}
                    </p>

                    <!-- Key Land / Property Records Grid -->
                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                        <i class="fa fa-file-text-o text-success me-2"></i> জমি ও দলিলের আইনি রেকর্ডসমূহ
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="property-spec-box">
                                <i class="fa fa-expand text-success spec-icon"></i>
                                <span class="spec-label">মোট আয়তন</span>
                                <span class="spec-value">{{ number_format($property->total_area, 2) }} {{ $property->measurement_unit }}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="property-spec-box">
                                <i class="fa fa-map-o text-primary spec-icon"></i>
                                <span class="spec-label">দাগ নম্বর</span>
                                <span class="spec-value">{{ $property->dag_number ?: 'যাচাইকৃত' }}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="property-spec-box">
                                <i class="fa fa-file-text-o text-warning spec-icon"></i>
                                <span class="spec-label">খতিয়ান নম্বর</span>
                                <span class="spec-value">{{ $property->khatian_number ?: 'নামজারি রেডি' }}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="property-spec-box">
                                <i class="fa fa-map-marker text-danger spec-icon"></i>
                                <span class="spec-label">জেএল / মৌজা</span>
                                <span class="spec-value">{{ $property->jl_number ?: ($property->mouza ?: 'অনুমোদিত') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                        <i class="fa fa-align-left text-success me-2"></i> বিস্তারিত বিবরণ
                    </h5>
                    <p class="text-slate-700 lead mb-4" style="line-height: 1.8; font-size: 1.05rem; color: #334155;">
                        {{ $property->description ?: 'প্রপার্টিটি ১০০% ভেটিং ও লিগ্যাল ক্লিয়ারেন্স সম্পন্ন। বিদ্যুৎ, রাস্তা ও ইউটিলিটি সংযোগ বিদ্যমান। সাইট পরিদর্শনের জন্য বুকিং আবেদন করুন।' }}
                    </p>

                    <!-- Amenities & Features -->
                    @if($property->features)
                        <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                            <i class="fa fa-check-square-o text-success me-2"></i> সুযোগ-সুবিধা ও বৈশিষ্ট্য
                        </h5>
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            @foreach(explode(',', $property->features) as $feature)
                                <span class="property-amenity-pill">
                                    <i class="fa fa-check-circle text-success me-2"></i>
                                    <span>{{ trim($feature) }}</span>
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Pricing & Modern Booking Application Card -->
            <div class="col-lg-4">
                <div class="booking-application-card sticky-top" style="top: 90px;">
                    <!-- Pricing Header Banner -->
                    <div class="booking-card-header">
                        <div class="small fw-bold text-uppercase mb-1" style="color: #a7f3d0; font-size: 12px; letter-spacing: 0.5px;">নির্ধারিত বিক্রয় মূল্য</div>
                        <h2 class="booking-price-text mb-2">
                            ৳ {{ number_format($property->selling_price) }}
                        </h2>
                        @if($property->booking_amount > 0)
                            <span class="booking-badge-pill">
                                <i class="fa fa-bookmark me-1 text-warning"></i> বুকিং মানি: ৳ {{ number_format($property->booking_amount) }}
                            </span>
                        @endif
                    </div>

                    <!-- Direct Inquiry / Booking Form Body -->
                    <div class="p-4 bg-white">
                        <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <div class="booking-icon-circle">
                                <i class="fa fa-paper-plane text-success"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">সরাসরি বুকিং বা সাইট ভিজিট</h6>
                                <small class="text-muted">দ্রুত কনফার্মেশনের জন্য ফর্ম পূরণ করুন</small>
                            </div>
                        </div>

                        <form action="{{ route('properties.inquiry') }}" method="POST" onsubmit="handleAjaxBookingSubmit(event, this)">
                            @csrf
                            <input type="hidden" name="property_id" value="{{ $property->id }}">

                            <!-- Name -->
                            <div class="form-group-modern mb-3">
                                <label class="form-label-modern">
                                    <i class="fa fa-user text-success me-1"></i> আপনার নাম <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="customer_name" required class="form-control-modern" value="{{ Auth::check() ? Auth::user()->name : '' }}" placeholder="আপনার নাম লিখুন">
                            </div>

                            <!-- Mobile -->
                            <div class="form-group-modern mb-3">
                                <label class="form-label-modern">
                                    <i class="fa fa-phone text-success me-1"></i> মোবাইল নম্বর <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="customer_phone" required class="form-control-modern" value="{{ Auth::check() ? Auth::user()->phone : '' }}" placeholder="০১xxxxxxxxx">
                            </div>

                            <!-- Inquiry Type (Circled by user) -->
                            <div class="form-group-modern mb-3">
                                <label class="form-label-modern">
                                    <i class="fa fa-tags text-success me-1"></i> আবেদনের ধরন <span class="text-danger">*</span>
                                </label>
                                <div class="custom-select-wrap">
                                    <select name="inquiry_type" class="form-select-modern" required>
                                        <option value="site_visit" selected>📍 সাইট পরিদর্শন (Site Visit)</option>
                                        <option value="booking_request">📝 সরাসরি বুকিং আবেদন (Booking)</option>
                                        <option value="price_inquiry">💬 মূল্য ও কিস্তি আলোচনা (Inquiry)</option>
                                    </select>
                                    <i class="fa fa-chevron-down custom-select-arrow"></i>
                                </div>
                            </div>

                            <!-- Preferred Date -->
                            <div class="form-group-modern mb-3">
                                <label class="form-label-modern">
                                    <i class="fa fa-calendar text-success me-1"></i> পরিদর্শনের পছন্দের তারিখ
                                </label>
                                <input type="date" name="preferred_date" class="form-control-modern" min="{{ date('Y-m-d') }}">
                            </div>

                            <!-- Note/Message -->
                            <div class="form-group-modern mb-3">
                                <label class="form-label-modern">
                                    <i class="fa fa-commenting-o text-success me-1"></i> নোট / বার্তা
                                </label>
                                <textarea name="message" rows="2" class="form-control-modern" placeholder="আপনার কোনো নির্দিষ্ট জিজ্ঞাসা থাকলে লিখুন..."></textarea>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn-submit-booking w-100 py-3 fw-bold">
                                <i class="fa fa-send me-1"></i> আবেদন সাবমিট করুন
                            </button>
                        </form>

                        <div class="booking-security-note mt-3 text-center">
                            <i class="fa fa-shield text-success me-1"></i> আপনার তথ্য সম্পূর্ণ সুরক্ষিত ও গোপনীয় রাখা হবে।
                        </div>

                        <!-- Direct Contact Options -->
                        <div class="d-flex gap-2 mt-3 pt-3 border-top">
                            <a href="tel:01700000000" class="btn-contact-action flex-fill text-dark">
                                <i class="fa fa-phone text-success me-1"></i> সরাসরি কল
                            </a>
                            <a href="https://wa.me/8801700000000" target="_blank" class="btn-contact-action flex-fill text-success">
                                <i class="fa fa-whatsapp me-1"></i> হোয়াটসঅ্যাপ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var toastTimer = null;

function showPropertyToast(message, trackingCode) {
    var toastWrap = document.getElementById('propertyToastNotification');
    var msgEl = document.getElementById('toastMessageText');
    var codeEl = document.getElementById('toastTrackingCode');
    var progressEl = document.getElementById('toastProgressFill');

    if (!toastWrap || !msgEl) return;

    msgEl.innerText = message || 'আপনার আবেদনটি সফলভাবে গৃহীত হয়েছে। আমাদের প্রতিনিধি দ্রুত যোগাযোগ করবেন।';
    if (codeEl) {
        if (trackingCode) {
            codeEl.innerText = 'ট্র্যাকিং কোড: ' + trackingCode;
            codeEl.style.display = 'inline-block';
        } else {
            codeEl.style.display = 'none';
        }
    }

    if (progressEl) {
        progressEl.style.transition = 'none';
        progressEl.style.width = '100%';
        setTimeout(function() {
            progressEl.style.transition = 'width 5s linear';
            progressEl.style.width = '0%';
        }, 50);
    }

    toastWrap.style.display = 'block';
    toastWrap.style.animation = 'toastSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards';

    if (toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(function() {
        hidePropertyToast();
    }, 5200);
}

function hidePropertyToast() {
    var toastWrap = document.getElementById('propertyToastNotification');
    if (toastWrap) {
        toastWrap.style.animation = 'toastSlideOut 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards';
        setTimeout(function() {
            toastWrap.style.display = 'none';
        }, 400);
    }
}

function handleAjaxBookingSubmit(e, form) {
    e.preventDefault();
    var btn = form.querySelector('button[type="submit"]');
    var originalBtnHtml = btn ? btn.innerHTML : '';
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> আবেদন পাঠানো হচ্ছে...';
    }

    var formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(function(res) {
        return res.json();
    })
    .then(function(data) {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalBtnHtml;
        }

        form.reset();

        // Trigger Animated Toaster Notification
        var msg = data.message || 'আপনার আবেদনটি সফলভাবে গৃহীত হয়েছে!';
        var code = data.inquiry_code || (data.inquiry ? data.inquiry.inquiry_code : '');
        showPropertyToast(msg, code);
    })
    .catch(function(err) {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalBtnHtml;
        }
        form.submit(); // fallback
    });
}

document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        showPropertyToast("{{ session('success') }}", '');
    @endif
});
</script>

<style>
/* Modern Spec Boxes */
.property-spec-box {
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    padding: 14px 10px;
    text-align: center;
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
}
.property-spec-box:hover {
    border-color: #10b981;
    background: #ffffff;
    box-shadow: 0 4px 14px rgba(16, 185, 129, 0.1);
    transform: translateY(-2px);
}
.spec-icon {
    font-size: 20px;
    margin-bottom: 2px;
}
.spec-label {
    font-size: 12px;
    color: #64748b;
    font-weight: 600;
}
.spec-value {
    font-size: 14px;
    color: #0f172a;
    font-weight: 800;
}

/* Modern Amenity Pills */
.property-amenity-pill {
    display: inline-flex;
    align-items: center;
    background: #ecfdf5 !important;
    color: #065f46 !important;
    border: 1.5px solid #a7f3d0 !important;
    font-size: 13.5px;
    font-weight: 700;
    padding: 8px 18px;
    border-radius: 50px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    transition: all 0.2s ease;
}
.property-amenity-pill:hover {
    background: #d1fae5 !important;
    border-color: #6ee7b7 !important;
    transform: translateY(-1px);
}

/* Modern Right Booking Card */
.booking-application-card {
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}
.booking-card-header {
    background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
    color: #ffffff;
    padding: 22px 20px;
    text-align: center;
}
.booking-price-text {
    font-size: 1.95rem;
    font-weight: 800;
    color: #ffffff;
    letter-spacing: -0.5px;
    font-family: monospace;
}
.booking-badge-pill {
    display: inline-flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.16);
    color: #fef08a;
    border: 1px solid rgba(254, 240, 138, 0.35);
    font-size: 13px;
    font-weight: 700;
    padding: 4px 14px;
    border-radius: 50px;
}
.booking-icon-circle {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #ecfdf5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}

/* Modern Form Controls */
.form-group-modern {
    position: relative;
}
.form-label-modern {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 5px;
}
.form-control-modern {
    width: 100%;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px 14px;
    font-size: 14px;
    color: #0f172a;
    font-weight: 500;
    transition: all 0.2s ease;
    outline: none;
}
.form-control-modern:focus {
    background: #ffffff;
    border-color: #16a34a;
    box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12);
}
.custom-select-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.form-select-modern {
    width: 100%;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px 36px 10px 14px;
    font-size: 14px;
    color: #0f172a;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    outline: none;
}
.form-select-modern:focus {
    background: #ffffff;
    border-color: #16a34a;
    box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12);
}
.custom-select-arrow {
    position: absolute;
    right: 14px;
    pointer-events: none;
    color: #64748b;
    font-size: 13px;
}
.btn-submit-booking {
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    color: #ffffff;
    border: none;
    border-radius: 50px;
    font-size: 15px;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(22, 163, 74, 0.35);
    transition: all 0.25s ease;
}
.btn-submit-booking:hover {
    background: linear-gradient(135deg, #15803d 0%, #166534 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(22, 163, 74, 0.45);
    color: #ffffff;
}
.booking-security-note {
    font-size: 12px;
    color: #64748b;
}
.btn-contact-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 50px;
    padding: 8px 12px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s ease;
}
.btn-contact-action:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
    transform: translateY(-1px);
}

/* Toast Styles */
.property-toast-wrap {
    position: fixed;
    top: 95px;
    right: 25px;
    z-index: 9999999;
    max-width: 440px;
    width: calc(100% - 40px);
    pointer-events: none;
}
.property-toast-box {
    pointer-events: auto;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 16px 45px rgba(0, 0, 0, 0.2), 0 2px 10px rgba(0, 0, 0, 0.08);
    border: 1.5px solid #86efac;
    border-left: 6px solid #16a34a;
    padding: 16px 20px 20px 18px;
    display: flex;
    align-items: flex-start;
    gap: 14px;
    position: relative;
    overflow: hidden;
}
.toast-icon-wrap {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #dcfce7;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #16a34a;
    font-size: 22px;
}
.toast-content-wrap {
    flex-grow: 1;
}
.toast-title {
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 4px;
}
.toast-message {
    font-size: 13px;
    color: #334155;
    line-height: 1.45;
    margin-bottom: 6px;
}
.toast-tracking-badge {
    display: inline-block;
    background: #f1f5f9;
    color: #0f172a;
    font-family: monospace;
    font-size: 12px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
}
.toast-close-btn {
    background: transparent;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    font-size: 16px;
    padding: 0;
    margin-left: 6px;
    line-height: 1;
}
.toast-close-btn:hover {
    color: #0f172a;
}
.toast-progress-bar {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: #f1f5f9;
}
.toast-progress-fill {
    height: 100%;
    background: #16a34a;
    width: 100%;
}
@keyframes toastSlideIn {
    from {
        transform: translateX(110%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
@keyframes toastSlideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(110%);
        opacity: 0;
    }
}
</style>
@endsection
