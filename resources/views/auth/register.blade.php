@extends('layouts.auth.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    body {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
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
        max-width: 480px;
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
        font-size: 14px;
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
    }

    .bonus-badge {
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
        margin-top: 14px;
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
        height: 50px;
        padding-left: 48px;
        padding-right: 16px;
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 500;
        color: #0f172a;
        box-sizing: border-box;
        transition: all 0.2s ease;
    }

    .custom-input:focus {
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
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 0.5px;
        box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.4);
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 8px;
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
            <p>Register your account to get started</p>
            <div class="bonus-badge">
                <i class="ti-gift"></i> Instant ৳300 Bonus Credit
            </div>
        </div>

        <!-- Registration Form -->
        <div class="form-container">
            <form method="POST" action="{{ route('register.post') }}">
                @csrf

                <!-- Card Number -->
                <div>
                    <label class="custom-label">Card Number (কার্ড নাম্বার)</label>
                    <div class="custom-input-group">
                        <i class="ti-credit-card input-icon"></i>
                        <input name="card_number" value="{{ old('card_number') }}" type="text" 
                            maxlength="12" class="custom-input"
                            placeholder="Enter 12-Digit Card Number" required autofocus>
                    </div>
                    @error('card_number')
                        <span class="error-feedback"><i class="ti-alert me-1"></i>{{ $message }}</span>
                    @enderror
                </div>

                <!-- Name -->
                <div>
                    <label class="custom-label">Name (নাম)</label>
                    <div class="custom-input-group">
                        <i class="ti-user input-icon"></i>
                        <input name="name" value="{{ old('name') }}" type="text"
                            class="custom-input"
                            placeholder="Enter your full name" required>
                    </div>
                    @error('name')
                        <span class="error-feedback"><i class="ti-alert me-1"></i>{{ $message }}</span>
                    @enderror
                </div>

                <!-- Mobile Number -->
                <div>
                    <label class="custom-label">Mobile Number (মোবাইল নাম্বার)</label>
                    <div class="custom-input-group">
                        <i class="ti-mobile input-icon"></i>
                        <input name="phone_number" value="{{ old('phone_number') }}" type="text"
                            class="custom-input"
                            placeholder="e.g. 01712345678" required>
                    </div>
                    @error('phone_number')
                        <span class="error-feedback"><i class="ti-alert me-1"></i>{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="custom-label">Password</label>
                    <div class="custom-input-group">
                        <i class="ti-lock input-icon"></i>
                        <input name="password" type="password" class="custom-input"
                            placeholder="Enter your password" required>
                    </div>
                    @error('password')
                        <span class="error-feedback"><i class="ti-alert me-1"></i>{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="custom-label">Confirm Password</label>
                    <div class="custom-input-group">
                        <i class="ti-check-box input-icon"></i>
                        <input name="password_confirmation" type="password" class="custom-input"
                            placeholder="Retype your password" required>
                    </div>
                    @error('password_confirmation')
                        <span class="error-feedback"><i class="ti-alert me-1"></i>{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-register">
                    CREATE ACCOUNT & CLAIM ৳300
                </button>
            </form>

            <!-- Footer -->
            <div class="auth-footer">
                Already have an account?
                <a href="{{ route('login') }}" class="ms-1">Sign In Here</a>
            </div>
        </div>
    </div>
</div>

@endsection
