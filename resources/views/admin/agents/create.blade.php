@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-user-plus text-primary me-2"></i>Create New Agent (নতুন এজেন্ট তৈরি করুন)
                    </h3>
                    <p class="text-muted mb-0">এজেন্ট পয়েন্ট এবং লগইন অ্যাকাউন্ট রেজিস্ট্রেশন ফর্ম</p>
                </div>
                <div>
                    <a href="{{ route('admin.agents.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> এজেন্ট তালিকায় ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <!-- Main content -->
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
                                <i class="fa fa-id-card-o text-primary me-2"></i> এজেন্ট ও পয়েন্টের সাধারণ তথ্য
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('admin.agents.store') }}" method="POST">
                                @csrf

                                <!-- Section 1: Basic & Identification -->
                                <h6 class="text-primary fw-bold text-uppercase font-size-12 mb-3 pb-2 border-bottom">
                                    ১. আইডেন্টিফিকেশন ও পয়েন্টের নাম
                                </h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Agent ID (স্বয়ংক্রিয় ইউনিক আইডি)</label>
                                        <input type="text" name="agent_code" class="form-control bg-light fw-bold text-primary" 
                                               value="{{ old('agent_code', $autoAgentId) }}" readonly>
                                        <small class="text-muted">সিস্টেম থেকে স্বয়ংক্রিয়ভাবে জেনারেট করা</small>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Agent Point Name (পয়েন্টের নাম) <span class="text-danger">*</span></label>
                                        <input type="text" name="agent_point_name" class="form-control" 
                                               placeholder="যেমন: নওগাঁ সদর এজেন্ট হাব" value="{{ old('agent_point_name') }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Company Name (প্রতিষ্ঠানের নাম)</label>
                                        <input type="text" name="company_name" class="form-control" 
                                               placeholder="যেমন: গ্রিন এগ্রো ট্রেডার্স" value="{{ old('company_name') }}">
                                    </div>
                                </div>

                                <!-- Section 2: Agent Personal & Login Credentials -->
                                <h6 class="text-primary fw-bold text-uppercase font-size-12 mb-3 pb-2 border-bottom">
                                    ২. দায়িত্বপ্রাপ্ত ব্যক্তি ও লগইন তথ্য
                                </h6>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Agent Name (এজেন্ট/দায়িত্বপ্রাপ্ত ব্যক্তির নাম) <span class="text-danger">*</span></label>
                                        <input type="text" name="agent_name" class="form-control" 
                                               placeholder="ব্যক্তির পূর্ণ নাম" value="{{ old('agent_name') }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Mobile Number (লগইন মোবাইল নম্বর) <span class="text-danger">*</span></label>
                                        <input type="text" name="mobile_number" class="form-control" 
                                               placeholder="017XXXXXXXX" value="{{ old('mobile_number') }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Alternative Phone (বিকল্প ফোন নম্বর)</label>
                                        <input type="text" name="alt_phone" class="form-control" 
                                               placeholder="জরুরী যোগাযোগ নম্বর" value="{{ old('alt_phone') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Email Address (লগইন ইমেইল) <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" 
                                               placeholder="agent@example.com" value="{{ old('email') }}" required>
                                        <small class="text-muted">এই ইমেইল দিয়ে এজেন্ট পোর্টালে লগইন করতে হবে</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Portal Login Password (লগইন পাসওয়ার্ড) <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control" 
                                               placeholder="ন্যূনতম ৬ অক্ষর" required minlength="6">
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
                                               placeholder="যেমন: নওগাঁ" value="{{ old('district') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Upazila / Thana (উপজেলা / থানা)</label>
                                        <input type="text" name="thana_upazila" class="form-control" 
                                               placeholder="যেমন: নওগাঁ সদর" value="{{ old('thana_upazila') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Area / Union / Village (এলাকা/ইউনিয়ন) <span class="text-danger">*</span></label>
                                        <input type="text" name="area" class="form-control" 
                                               placeholder="যেমন: বালুডাঙ্গা বাসস্ট্যান্ড সংলগ্ন" value="{{ old('area') }}" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Full Address (বিস্তারিত ঠিকানা)</label>
                                        <textarea name="address" rows="2" class="form-control" 
                                                  placeholder="রোড, বাড়ি নং, বাজারের নাম ইত্যাদি">{{ old('address') }}</textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Opening Date (উদ্বোধনের তারিখ)</label>
                                        <input type="date" name="opening_date" class="form-control" 
                                               value="{{ old('opening_date', date('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Monthly Card Capacity (ধারণক্ষমতা)</label>
                                        <input type="number" name="capacity" class="form-control" 
                                               placeholder="যেমন: 500" value="{{ old('capacity', 500) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Status (স্ট্যাটাস) <span class="text-danger">*</span></label>
                                        <select name="status" class="form-select" required>
                                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active (সক্রিয়)</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive (নিষ্ক্রিয়)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                    <a href="{{ route('admin.agents.index') }}" class="btn btn-light px-4">বাতিল</a>
                                    <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                                        <i class="fa fa-check-circle me-1"></i> এজেন্ট অ্যাকাউন্ট তৈরি করুন
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
