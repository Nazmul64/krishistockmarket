@extends('layouts.auth.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&display=swap');

    body {
        font-family: 'Hind Siliguri', 'Plus Jakarta Sans', sans-serif !important;
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #064e3b 100%) !important;
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }

    .register-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        box-sizing: border-box;
    }

    .auth-card {
        width: 100%;
        max-width: 580px;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4),
                    0 0 35px rgba(16, 185, 129, 0.25);
        overflow: hidden;
        margin: 0 auto;
    }

    .brand-header {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        padding: 32px 24px;
        text-align: center;
        color: #ffffff;
    }

    .brand-header h2 {
        font-size: 26px;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin: 0 0 6px 0;
        color: #ffffff;
    }

    .brand-header p {
        font-size: 15px;
        color: rgba(255, 255, 255, 0.95);
        margin: 0;
    }

    .supplier-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 700;
        color: #fef08a;
        margin-top: 12px;
        border: 1px solid rgba(255, 255, 255, 0.35);
    }

    .form-container {
        padding: 32px 28px;
    }

    .custom-label {
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
        display: block;
    }

    .custom-input-group {
        position: relative;
        margin-bottom: 18px;
    }

    .custom-input-group .input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 18px;
        z-index: 10;
        transition: color 0.2s ease;
    }

    .custom-input {
        width: 100%;
        height: 48px;
        padding-left: 48px;
        padding-right: 16px;
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        color: #0f172a;
        box-sizing: border-box;
        transition: all 0.2s ease;
    }

    .custom-textarea {
        width: 100%;
        padding-left: 48px;
        padding-right: 16px;
        padding-top: 12px;
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        color: #0f172a;
        box-sizing: border-box;
        transition: all 0.2s ease;
    }

    .custom-input:focus, .custom-textarea:focus {
        outline: none;
        background: #ffffff;
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
    }

    .custom-input-group:focus-within .input-icon {
        color: #10b981;
    }

    .btn-register {
        width: 100%;
        height: 52px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        border-radius: 12px;
        color: #ffffff;
        font-size: 16px;
        font-weight: 700;
        letter-spacing: 0.5px;
        box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.4);
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 10px;
    }

    .btn-register:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-2px);
        box-shadow: 0 14px 24px -5px rgba(16, 185, 129, 0.5);
    }

    .auth-footer {
        text-align: center;
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid #f1f5f9;
        font-size: 14px;
        color: #64748b;
    }

    .auth-footer a {
        color: #059669;
        font-weight: 700;
        text-decoration: none;
    }

    .auth-footer a:hover {
        text-decoration: underline;
    }

    .error-feedback {
        color: #ef4444;
        font-size: 12px;
        font-weight: 600;
        margin-top: 4px;
        display: block;
    }
</style>

<div class="register-wrapper">
    <div class="auth-card">
        <!-- Header Banner -->
        <div class="brand-header">
            <h2>iKrishiPoribar</h2>
            <p>সাপ্লায়ার / সরবরাহকারী অ্যাকাউন্ট রেজিস্ট্রেশন</p>
            <div class="supplier-badge">
                <i class="ti-truck me-1"></i> Supplier Portal Sign Up
            </div>
        </div>

        <!-- Registration Form -->
        <div class="form-container">
            @if ($errors->any())
                <div class="alert alert-danger mb-3 p-2 rounded" style="font-size: 13px;">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('supplier.register.post') }}">
                @csrf

                <div class="row">
                    <!-- Company Name -->
                    <div class="col-md-6 col-12">
                        <label class="custom-label">প্রতিষ্ঠানের নাম <span class="text-danger">*</span></label>
                        <div class="custom-input-group">
                            <i class="ti-briefcase input-icon"></i>
                            <input name="company_name" value="{{ old('company_name') }}" type="text"
                                class="custom-input" placeholder="যেমন: সততা ট্রেডার্স" required autofocus>
                        </div>
                        @error('company_name')
                            <span class="error-feedback"><i class="ti-alert me-1"></i>{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Contact Person Name -->
                    <div class="col-md-6 col-12">
                        <label class="custom-label">সাপ্লায়ার/প্রতিনিধির নাম <span class="text-danger">*</span></label>
                        <div class="custom-input-group">
                            <i class="ti-user input-icon"></i>
                            <input name="name" value="{{ old('name') }}" type="text"
                                class="custom-input" placeholder="আপনার পূর্ণ নাম লিখুন" required>
                        </div>
                        @error('name')
                            <span class="error-feedback"><i class="ti-alert me-1"></i>{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Phone Number -->
                    <div class="col-md-6 col-12">
                        <label class="custom-label">মোবাইল নম্বর <span class="text-danger">*</span></label>
                        <div class="custom-input-group">
                            <i class="ti-mobile input-icon"></i>
                            <input name="phone" value="{{ old('phone') }}" type="text"
                                class="custom-input" placeholder="017XXXXXXXX" required>
                        </div>
                        @error('phone')
                            <span class="error-feedback"><i class="ti-alert me-1"></i>{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="col-md-6 col-12">
                        <label class="custom-label">ইমেইল অ্যাড্রেস (ঐচ্ছিক)</label>
                        <div class="custom-input-group">
                            <i class="ti-email input-icon"></i>
                            <input name="email" value="{{ old('email') }}" type="email"
                                class="custom-input" placeholder="example@email.com">
                        </div>
                        @error('email')
                            <span class="error-feedback"><i class="ti-alert me-1"></i>{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- District & Thana -->
                    <div class="col-md-12 col-12">
                        <label class="custom-label">জেলা / থানা</label>
                        <div class="custom-input-group">
                            <i class="ti-location-pin input-icon"></i>
                            <input name="district_thana" value="{{ old('district_thana') }}" type="text"
                                class="custom-input" placeholder="যেমন: ঢাকা, ধানমণ্ডি / নওগাঁ, সদর">
                        </div>
                    </div>
                </div>

                <!-- Full Address -->
                <div>
                    <label class="custom-label">পূর্ণাঙ্গ ঠিকানা</label>
                    <div class="custom-input-group">
                        <i class="ti-home input-icon"></i>
                        <textarea name="address" rows="2" class="custom-textarea" placeholder="প্রতিষ্ঠানের বিস্তারিত ঠিকানা লিখুন">{{ old('address') }}</textarea>
                    </div>
                </div>

                <div class="row">
                    <!-- Password -->
                    <div class="col-md-6 col-12">
                        <label class="custom-label">পাসওয়ার্ড <span class="text-danger">*</span></label>
                        <div class="custom-input-group">
                            <i class="ti-lock input-icon"></i>
                            <input name="password" type="password" class="custom-input"
                                placeholder="কমপক্ষে ৬ অক্ষর" required>
                        </div>
                        @error('password')
                            <span class="error-feedback"><i class="ti-alert me-1"></i>{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-md-6 col-12">
                        <label class="custom-label">পাসওয়ার্ড নিশ্চিত করুন <span class="text-danger">*</span></label>
                        <div class="custom-input-group">
                            <i class="ti-check-box input-icon"></i>
                            <input name="password_confirmation" type="password" class="custom-input"
                                placeholder="পুনরায় পাসওয়ার্ড লিখুন" required>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-register">
                    <i class="ti-check me-1"></i> সাপ্লায়ার অ্যাকাউন্ট তৈরি করুন
                </button>
            </form>

            <!-- Footer -->
            <div class="auth-footer">
                ইতিমধ্যে অ্যাকাউন্ট আছে?
                <a href="{{ route('login') }}" class="ms-1">লগইন করুন (Sign In)</a>
            </div>
        </div>
    </div>
</div>

@endsection
