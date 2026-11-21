@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-pencil-square-o text-primary me-2"></i>Edit Agent: {{ $agentPoint->name }}
                    </h3>
                    <p class="text-muted mb-0">এজেন্ট পয়েন্ট ও প্রোফাইল তথ্য হালনাগাদ করুন</p>
                </div>
                <div>
                    <a href="{{ route('admin.agents.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="card-title fw-bold text-dark mb-0">
                                <i class="fa fa-id-card-o text-primary me-2"></i> এজেন্ট ও পয়েন্টের তথ্য আপডেট
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('admin.agents.update', $agentPoint->id) }}" method="POST">
                                @csrf

                                <!-- Section 1: Basic & Identification -->
                                <h6 class="text-primary fw-bold text-uppercase font-size-12 mb-3 pb-2 border-bottom">
                                    ১. আইডেন্টিফিকেশন ও পয়েন্টের নাম
                                </h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Agent ID (ইউনিক আইডি)</label>
                                        <input type="text" class="form-control bg-light fw-bold text-primary" 
                                               value="{{ $agentPoint->code }}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Agent Point Name (পয়েন্টের নাম) <span class="text-danger">*</span></label>
                                        <input type="text" name="agent_point_name" class="form-control" 
                                               value="{{ old('agent_point_name', $agentPoint->name) }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Company Name (প্রতিষ্ঠানের নাম)</label>
                                        <input type="text" name="company_name" class="form-control" 
                                               value="{{ old('company_name', $agentPoint->company_name) }}">
                                    </div>
                                </div>

                                <!-- Section 2: Agent Personal & Login Credentials -->
                                <h6 class="text-primary fw-bold text-uppercase font-size-12 mb-3 pb-2 border-bottom">
                                    ২. দায়িত্বপ্রাপ্ত ব্যক্তি ও লগইন তথ্য
                                </h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Agent Name (এজেন্ট ব্যক্তির নাম) <span class="text-danger">*</span></label>
                                        <input type="text" name="agent_name" class="form-control" 
                                               value="{{ old('agent_name', $agentPoint->agent_name) }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Mobile Number <span class="text-danger">*</span></label>
                                        <input type="text" name="mobile_number" class="form-control" 
                                               value="{{ old('mobile_number', $agentPoint->contact_number) }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Alternative Phone</label>
                                        <input type="text" name="alt_phone" class="form-control" 
                                               value="{{ old('alt_phone', $agentPoint->alt_phone) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" 
                                               value="{{ old('email', optional($agentPoint->agentUser)->email) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">New Password (পাসওয়ার্ড পরিবর্তন করতে চাইলে)</label>
                                        <input type="password" name="password" class="form-control" 
                                               placeholder="অপরিবর্তিত রাখতে ফাঁকা রাখুন" minlength="6">
                                    </div>
                                </div>

                                <!-- Section 3: Location & Operational Details -->
                                <h6 class="text-primary fw-bold text-uppercase font-size-12 mb-3 pb-2 border-bottom">
                                    ৩. এলাকা, ঠিকানা ও কার্যপরিধি
                                </h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">District (জেলা)</label>
                                        <input type="text" name="district" class="form-control" 
                                               value="{{ old('district', $agentPoint->district) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Upazila / Thana</label>
                                        <input type="text" name="thana_upazila" class="form-control" 
                                               value="{{ old('thana_upazila', $agentPoint->thana_upazila) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Area / Union / Village <span class="text-danger">*</span></label>
                                        <input type="text" name="area" class="form-control" 
                                               value="{{ old('area', $agentPoint->area) }}" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Full Address</label>
                                        <textarea name="address" rows="2" class="form-control">{{ old('address', $agentPoint->address) }}</textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Opening Date</label>
                                        <input type="date" name="opening_date" class="form-control" 
                                               value="{{ old('opening_date', $agentPoint->opening_date) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Monthly Card Capacity</label>
                                        <input type="number" name="capacity" class="form-control" 
                                               value="{{ old('capacity', $agentPoint->capacity) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                        <select name="status" class="form-select" required>
                                            <option value="active" {{ old('status', $agentPoint->status) == 'active' ? 'selected' : '' }}>Active (সক্রিয়)</option>
                                            <option value="inactive" {{ old('status', $agentPoint->status) == 'inactive' ? 'selected' : '' }}>Inactive (নিষ্ক্রিয়)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                    <a href="{{ route('admin.agents.index') }}" class="btn btn-light px-4">বাতিল</a>
                                    <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                                        <i class="fa fa-save me-1"></i> আপডেট সংরক্ষণ করুন
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
