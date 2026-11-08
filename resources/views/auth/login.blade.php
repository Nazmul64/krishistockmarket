@extends('layouts.auth.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&display=swap');

    body {
        font-family: 'Plus Jakarta Sans', 'Hind Siliguri', sans-serif !important;
        background: linear-gradient(135deg, #0b1120 0%, #1c1917 50%, #431407 100%) !important;
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }

    .login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        box-sizing: border-box;
    }

    .auth-card {
        width: 100%;
        max-width: 460px;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5),
                    0 0 35px rgba(245, 158, 11, 0.28);
        overflow: hidden;
        margin: 0 auto;
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .brand-header {
        background: linear-gradient(135deg, #ea580c 0%, #d97706 50%, #f59e0b 100%);
        padding: 36px 24px 30px;
        text-align: center;
        color: #ffffff;
        position: relative;
    }

    .brand-header h2 {
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin: 0 0 6px 0;
        color: #ffffff;
    }

    .brand-header p {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.95);
        margin: 0;
    }

    .security-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.22);
        backdrop-filter: blur(10px);
        padding: 5px 14px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        color: #ffffff;
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
        margin-bottom: 20px;
    }

    .custom-input-group .input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 18px;
        z-index: 10;
        transition: color 0.2s ease;
    }

    .custom-input {
        width: 100%;
        height: 52px;
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
        border-color: #f59e0b;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.2);
    }

    .custom-input-group:focus-within .input-icon {
        color: #d97706;
    }

    .options-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        user-select: none;
        margin-bottom: 0;
    }

    .remember-me input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #d97706;
        cursor: pointer;
        border-radius: 4px;
    }

    .remember-me span {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
    }

    .forgot-link {
        font-size: 13px;
        font-weight: 700;
        color: #d97706;
        text-decoration: none;
        transition: color 0.2s;
    }

    .forgot-link:hover {
        color: #b45309;
        text-decoration: underline;
    }

    .btn-login {
        width: 100%;
        height: 52px;
        background: linear-gradient(135deg, #ea580c 0%, #d97706 50%, #f59e0b 100%);
        border: none;
        border-radius: 12px;
        color: #ffffff;
        font-size: 15px;
        font-weight: 800;
        letter-spacing: 0.5px;
        box-shadow: 0 10px 22px -5px rgba(234, 88, 12, 0.45);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-login:hover {
        background: linear-gradient(135deg, #c2410c 0%, #b45309 50%, #d97706 100%);
        transform: translateY(-2px);
        box-shadow: 0 14px 26px -5px rgba(234, 88, 12, 0.6);
    }

    .auth-footer {
        text-align: center;
        margin-top: 24px;
        padding-top: 18px;
        border-top: 1px solid #f1f5f9;
        font-size: 14px;
        color: #64748b;
    }

    .auth-footer a {
        color: #d97706;
        font-weight: 700;
        text-decoration: none;
    }

    .auth-footer a:hover {
        color: #ea580c;
        text-decoration: underline;
    }

    .error-feedback {
        color: #ef4444;
        font-size: 12px;
        font-weight: 600;
        margin-top: 4px;
        display: block;
    }

    .alert-success-custom {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
</style>

<div class="login-wrapper">
    <div class="auth-card">
        <!-- Header Banner -->
        <div class="brand-header">
            <h2>iKrishiPoribar</h2>
            <p>Welcome Back! Sign in to access your account</p>
            <div class="security-badge">
                <i class="ti-shield me-1"></i> Secure Member Portal
            </div>
        </div>

        <!-- Login Form -->
        <div class="form-container">
            @if(session('success'))
                <div class="alert-success-custom">
                    <i class="ti-check-box me-1"></i> {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Mobile / Username -->
                <div>
                    <label class="custom-label">Mobile Number / Username</label>
                    <div class="custom-input-group">
                        <i class="ti-user input-icon"></i>
                        <input name="username" value="{{ old('username') }}" type="text"
                            class="custom-input" placeholder="Enter Mobile Number or Username" required autofocus>
                    </div>
                    @error('username')
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

                <!-- Options Row -->
                <div class="options-row">
                    <label class="remember-me">
                        <input name="remember" type="checkbox" id="basic_checkbox_1">
                        <span>Remember Me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Forgot Password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-login">
                    SIGN IN TO YOUR ACCOUNT
                </button>
            </form>

            <!-- Footer -->
            <div class="auth-footer text-center">
                <div class="mb-2">
                    Don't have a Customer account?
                    <a href="{{ route('register') }}" class="ms-1">Sign Up Here</a>
                </div>
                <div class="mt-3 pt-2 border-top">
                    <a href="{{ route('supplier.register') }}" class="btn btn-sm w-100 py-2 fw-bold" style="border-radius: 10px; border: 1.5px solid #d97706; color: #d97706; background: #fffbeb; transition: all 0.2s ease;">
                        <i class="ti-truck me-1"></i> Supplier Registration / সাপ্লায়ার সাইন আপ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
