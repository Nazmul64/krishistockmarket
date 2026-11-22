@php
    $siteLang = request()->cookie('ekrishi_lang', 'bn');
    $isBn = ($siteLang === 'bn');
@endphp

@extends('layouts.frontend.app')

@section('content')
<div class="franchise-program-page" style="background: #fafaf9; min-height: 85vh; padding: 40px 0 70px;">
    <div class="container">

        <!-- Top Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="background: transparent; padding: 0; font-size: 14px;">
                <li class="breadcrumb-item"><a href="{{ route('/') }}" class="text-decoration-none text-muted"><i class="fa fa-home me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Business</a></li>
                <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Franchising</li>
            </ol>
        </nav>

        <!-- Centered Header -->
        <div class="text-center mb-5 pb-2">
            <span class="badge border px-3 py-1 rounded-pill mb-2 fw-bold text-uppercase" style="border-color: #d6d3d1 !important; color: #78716c; font-size: 11px; letter-spacing: 1px;">
                FRANCHISE PROGRAM
            </span>
            <h1 class="fw-bold text-dark mb-2" style="font-size: clamp(26px, 4vw, 38px); letter-spacing: -0.5px;">
                Build a showroom with <span style="color: #b45309;">ekrishisheba</span>
            </h1>
            <p class="text-muted mx-auto mb-0" style="max-width: 650px; font-size: 15px;">
                Tell us about your location and we'll get back to you with the next steps — site review, agreement, and launch support.
            </p>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4 p-4" style="background: #ecfdf5; border-left: 5px solid #10b981 !important;">
                <div class="d-flex align-items-center">
                    <span class="avatar avatar-md rounded-circle text-white me-3 d-flex align-items-center justify-content-center" style="background: #10b981; width: 44px; height: 44px; min-width: 44px;">
                        <i class="fa fa-check fs-5"></i>
                    </span>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Application Submitted Successfully!</h5>
                        <p class="mb-0 text-muted">{{ session('success') }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4 align-items-start">
            <!-- Left Column: How It Works & Checklist -->
            <div class="col-12 col-lg-5">
                <div class="d-flex flex-column gap-4">
                    
                    <!-- How it works card -->
                    <div class="card border rounded-4 bg-white p-4 shadow-sm" style="border-color: #e7e5e4 !important;">
                        <h5 class="fw-bold text-dark mb-4 pb-2 border-bottom">How it works</h5>
                        
                        <div class="d-flex align-items-start gap-3 mb-4">
                            <span class="badge rounded-circle text-white fw-bold d-flex align-items-center justify-content-center" style="background: #1e293b; width: 32px; height: 32px; min-width: 32px; font-size: 12px;">01</span>
                            <div>
                                <h6 class="fw-bold text-dark mb-1 font-size-15">Apply</h6>
                                <p class="text-muted small mb-0">Submit your details and proposed showroom location.</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 mb-4">
                            <span class="badge rounded-circle text-white fw-bold d-flex align-items-center justify-content-center" style="background: #1e293b; width: 32px; height: 32px; min-width: 32px; font-size: 12px;">02</span>
                            <div>
                                <h6 class="fw-bold text-dark mb-1 font-size-15">Site review</h6>
                                <p class="text-muted small mb-0">Our team assesses the location and floor size.</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 mb-4">
                            <span class="badge rounded-circle text-white fw-bold d-flex align-items-center justify-content-center" style="background: #1e293b; width: 32px; height: 32px; min-width: 32px; font-size: 12px;">03</span>
                            <div>
                                <h6 class="fw-bold text-dark mb-1 font-size-15">Agreement</h6>
                                <p class="text-muted small mb-0">We finalize franchise terms and onboarding.</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3">
                            <span class="badge rounded-circle text-white fw-bold d-flex align-items-center justify-content-center" style="background: #b45309; width: 32px; height: 32px; min-width: 32px; font-size: 12px;">04</span>
                            <div>
                                <h6 class="fw-bold text-dark mb-1 font-size-15">Launch</h6>
                                <p class="text-muted small mb-0">Open your showroom with our full operational support.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Before you apply checklist -->
                    <div class="card border rounded-4 bg-white p-4 shadow-sm" style="border-color: #e7e5e4 !important;">
                        <h6 class="fw-bold text-dark mb-3">Before you apply</h6>
                        <ul class="list-unstyled mb-0 d-flex flex-column gap-3 small text-muted">
                            <li class="d-flex align-items-start gap-2">
                                <i class="fa fa-check-square-o text-warning mt-1 fs-6"></i>
                                <span>Minimum showroom space secured or identified</span>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="fa fa-check-square-o text-warning mt-1 fs-6"></i>
                                <span>Location in a prime retail or commercial area</span>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="fa fa-check-square-o text-warning mt-1 fs-6"></i>
                                <span>Ability to meet initial setup and inventory investment requirements</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

            <!-- Right Column: Application Form -->
            <div class="col-12 col-lg-7">
                <div class="card border rounded-4 bg-white shadow-sm overflow-hidden" style="border-color: #e7e5e4 !important;">
                    <div class="p-4 text-white" style="background: #1e293b;">
                        <h4 class="fw-bold mb-1 text-white font-size-20">Showroom Franchise Application</h4>
                        <p class="small text-white-50 mb-0">Fields marked * are required.</p>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('business.franchise.apply') }}" method="POST">
                            @csrf

                            <!-- Full Name -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small mb-1">Full name *</label>
                                <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter your full name" required style="border-color: #cbd5e1;">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Phone Number -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small mb-1">Phone number *</label>
                                <input type="tel" name="phone" class="form-control rounded-3 @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="01XXXXXXXXX" required style="border-color: #cbd5e1;">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Address -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small mb-1">Address *</label>
                                <textarea name="address" rows="2" class="form-control rounded-3 @error('address') is-invalid @enderror" placeholder="Your residential/office address" required style="border-color: #cbd5e1;">{{ old('address') }}</textarea>
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Location Fields -->
                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-bold text-dark small mb-1">Division *</label>
                                    <input type="text" name="division" class="form-control rounded-3" value="{{ old('division') }}" placeholder="e.g. Dhaka" required style="border-color: #cbd5e1;">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-bold text-dark small mb-1">District *</label>
                                    <input type="text" name="district" class="form-control rounded-3" value="{{ old('district') }}" placeholder="e.g. Gazipur" required style="border-color: #cbd5e1;">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-bold text-dark small mb-1">Upazila *</label>
                                    <input type="text" name="upazila" class="form-control rounded-3" value="{{ old('upazila') }}" placeholder="e.g. Kaliakair" required style="border-color: #cbd5e1;">
                                </div>
                            </div>

                            <!-- Showroom Specs -->
                            <div class="row g-3 mb-4">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold text-dark small mb-1">Showroom size (Sft) *</label>
                                    <input type="text" name="showroom_size" class="form-control rounded-3" value="{{ old('showroom_size') }}" placeholder="e.g. 500 Sft" required style="border-color: #cbd5e1;">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold text-dark small mb-1">Showroom location *</label>
                                    <input type="text" name="showroom_location" class="form-control rounded-3" value="{{ old('showroom_location') }}" placeholder="e.g. Main Market, 1st Floor" required style="border-color: #cbd5e1;">
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn w-100 text-white rounded-3 py-3 fw-bold shadow-sm" style="background: #b45309; border-color: #b45309; font-size: 15px;">
                                Submit application
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
