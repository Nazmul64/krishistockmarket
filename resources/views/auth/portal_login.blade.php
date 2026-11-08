@extends('layouts.auth.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&display=swap');

    body {
        font-family: 'Plus Jakarta Sans', 'Hind Siliguri', sans-serif !important;
        background: {{ $config['bg_gradient'] ?? 'linear-gradient(135deg, #0b1120 0%, #1c1917 50%, #431407 100%)' }} !important;
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
        max-width: 480px;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5),
                    0 0 35px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        margin: 0 auto;
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(18px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .brand-header {
        background: {{ $config['header_grad'] ?? 'linear-gradient(135deg, #ea580c 0%, #d97706 50%, #f59e0b 100%)' }};
        padding: 34px 24px 28px;
        text-align: center;
        color: #ffffff;
        position: relative;
    }

    .portal-icon-wrap {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.4);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        margin-bottom: 12px;
        color: #ffffff;
    }

    .brand-header h2 {
        font-size: 24px;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin: 0 0 6px 0;
        color: #ffffff;
    }

    .brand-header p {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.95);
        margin: 0;
        line-height: 1.4;
    }

    .security-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(0, 0, 0, 0.25);
        backdrop-filter: blur(10px);
        padding: 5px 14px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        color: #ffffff;
        margin-top: 14px;
        border: 1px solid rgba(255, 255, 255, 0.35);
        text-transform: uppercase;
        letter-spacing: 0.5px;
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
        color: #94a3b8;
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
        font-size: 14px;
        font-weight: 500;
        color: #0f172a;
        box-sizing: border-box;
        transition: all 0.2s ease;
    }

    .custom-input:focus {
        outline: none;
        background: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
    }

    .options-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 22px;
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
        width: 17px;
        height: 17px;
        cursor: pointer;
        border-radius: 4px;
    }

    .remember-me span {
        font-size: 13px;
        font-weight: 600;
        color: #475569;
    }

    .btn-login {
        width: 100%;
        height: 50px;
        background: {{ $config['header_grad'] ?? 'linear-gradient(135deg, #ea580c 0%, #d97706 50%, #f59e0b 100%)' }};
        border: none;
        border-radius: 12px;
        color: #ffffff;
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 0.5px;
        box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.3);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 26px -5px rgba(0, 0, 0, 0.4);
    }

    .auth-footer {
        text-align: center;
        margin-top: 24px;
        padding-top: 18px;
        border-top: 1px solid #f1f5f9;
        font-size: 13px;
        color: #64748b;
    }

    .alert-error-custom {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
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
        <!-- Portal Header -->
        <div class="brand-header">
            <div class="portal-icon-wrap">
                <i class="{{ $config['icon'] ?? 'ti-lock' }}"></i>
            </div>
            <h2>{{ $config['title'] ?? 'Portal Login' }}</h2>
            <p>{{ $config['subtitle'] ?? 'Sign in to access your role workspace' }}</p>
            <div class="security-badge">
                <i class="ti-shield me-1"></i> {{ $config['badge'] ?? 'Zero Trust Protected' }}
            </div>
        </div>

        <!-- Form Area -->
        <div class="form-container">
            @if(session('error'))
                <div class="alert-error-custom">
                    <i class="ti-alert me-1"></i> {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert-success-custom">
                    <i class="ti-check-box me-1"></i> {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ $config['action_url'] ?? url()->current() }}">
                @csrf

                <!-- Username / Email / Phone -->
                <div>
                    <label class="custom-label">ইউজারনেম / ইমেইল / মোবাইল</label>
                    <div class="custom-input-group">
                        <i class="ti-user input-icon"></i>
                        <input name="username" value="{{ old('username') }}" type="text"
                            class="custom-input" placeholder="Username, Email or Phone" required autofocus>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="custom-label">পাসওয়ার্ড</label>
                    <div class="custom-input-group">
                        <i class="ti-lock input-icon"></i>
                        <input name="password" type="password" class="custom-input"
                            placeholder="Enter your password" required>
                    </div>
                </div>

                <!-- Options -->
                <div class="options-row">
                    <label class="remember-me">
                        <input name="remember" type="checkbox" id="remember_me">
                        <span>মনে রাখুন (Remember Me)</span>
                    </label>

                    @if($portal === 'customer')
                        <a href="{{ route('password.request') }}" class="text-decoration-none small fw-bold text-muted">
                            পাসওয়ার্ড ভুলে গেছেন?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-login">
                    <i class="ti-arrow-right me-1"></i> পোর্টালে লগইন করুন
                </button>
            </form>

            <!-- Footer & Cross Portal Links -->
            <div class="auth-footer">
                @if($portal !== 'customer')
                    <div class="mb-2">
                        সাধারণ গ্রাহক পোর্টাল:
                        <a href="{{ route('login') }}" class="fw-bold text-decoration-none ms-1">Customer Login</a>
                    </div>
                @else
                    <div class="mb-2">
                        অ্যাকাউন্ট নেই?
                        <a href="{{ route('register') }}" class="fw-bold text-decoration-none ms-1">সাইন আপ করুন</a>
                    </div>
                @endif

                <div class="text-muted small mt-2">
                    <i class="ti-shield me-1"></i> iKrishiPoribar Zero-Trust Multi-Role Architecture
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

