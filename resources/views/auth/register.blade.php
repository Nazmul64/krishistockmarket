@extends('layouts.auth.app')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
/* Start Global Component */
:root {
  --main-Color: #1e2a3a;
  --sec-Color: #2d3e50;
  --third-Color: #ffa500;
  --fourth-Color: #ff8c00;
  --fiveth-Color: #4a5f7f;
  --sixth-Color: rgba(255, 255, 255, 0.1);
  --rgba-Color: rgba(30, 42, 58, 0.95);
  --box-shadow-1: 0 0 20px rgba(255, 165, 0, 0.85);
  --box-shadow-2: rgba(0, 0, 0, 0.5);
  --white-Color: #fff;
  --black-Color: #000;
}

html, body {
  font-family: Arial, Helvetica, sans-serif !important;
  background: linear-gradient(
    135deg,
    var(--main-Color) 0%,
    var(--sec-Color) 100%
  ) !important;
  background-color: var(--main-Color) !important;
  background-image: none !important;
  display: flex !important;
  justify-content: center !important;
  align-items: center !important;
  min-height: 100vh !important;
  width: 100vw !important;
  margin: 0 !important;
  padding: 0 !important;
  overflow-x: hidden !important;
  direction: ltr !important;
}

.animated-login-wrapper {
  min-height: 100vh;
  width: 100vw;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 30px 10px;
  box-sizing: border-box;
  position: relative;
  background: linear-gradient(
    135deg,
    var(--main-Color) 0%,
    var(--sec-Color) 100%
  ) !important;
  direction: ltr !important;
}

/* Stage holding both the 360-degree circle and the register card */
.portal-login-stage {
  position: relative;
  width: 760px;
  height: 760px;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 0 auto;
}

/* 360-degree Rotating Halo Equalizer Ring */
.portal-login-stage .circle-container {
  position: absolute;
  top: 0;
  left: 0;
  width: 760px;
  height: 760px;
  animation: rotate 20s linear infinite;
  pointer-events: none;
  z-index: 2;
}

.portal-login-stage .circle-container .bar {
  position: absolute;
  width: 8px;
  height: 35px;
  background-color: var(--fiveth-Color);
  border-radius: 4px;
  top: 0;
  left: 50%;
  margin-left: -4px;
  transform-origin: center 380px;
  transition: background 0.3s ease, box-shadow 0.3s ease;
}

.portal-login-stage .circle-container .bar.active {
  background: linear-gradient(
    180deg,
    var(--third-Color),
    var(--fourth-Color)
  ) !important;
  box-shadow: var(--box-shadow-1) !important;
}

/* Center Register Card */
.portal-login-stage .login-box {
  position: relative;
  z-index: 10;
  background: var(--rgba-Color);
  padding: 32px 34px 26px;
  border-radius: 20px;
  box-shadow: 0 20px 60px var(--box-shadow-2);
  width: 410px;
  max-width: 90vw;
  box-sizing: border-box;
  border: 1px solid rgba(255, 255, 255, 0.08);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  text-align: left;
}

/* Brand Logo */
.portal-login-stage .logo-wrap {
  text-align: center;
  margin-bottom: 10px;
}

.portal-login-stage .logo-wrap img {
  height: 40px;
  max-height: 44px;
  width: auto;
  max-width: 190px;
  object-fit: contain;
  background: transparent !important;
  filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.4));
}

/* Title */
.portal-login-stage .login-box .title {
  text-align: center;
  color: var(--third-Color);
  margin-bottom: 8px;
  font-size: 24px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 2px;
}

/* Bonus Badge */
.portal-login-stage .bonus-badge-wrap {
  text-align: center;
  margin-bottom: 16px;
}

.portal-login-stage .bonus-badge-pill {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  background: rgba(255, 165, 0, 0.15);
  border: 1px solid rgba(255, 165, 0, 0.4);
  color: #ffd166;
  padding: 5px 15px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 700;
  box-shadow: 0 0 14px rgba(255, 165, 0, 0.2);
  letter-spacing: 0.3px;
}

/* Alerts */
.portal-login-stage .login-alert {
  padding: 10px 14px;
  border-radius: 12px;
  font-size: 13px;
  font-weight: 500;
  margin-bottom: 14px;
  display: flex;
  align-items: center;
  gap: 8px;
  line-height: 1.4;
  text-transform: none;
}

.portal-login-stage .login-alert.error {
  background: rgba(239, 68, 68, 0.16);
  border: 1px solid rgba(239, 68, 68, 0.4);
  color: #fca5a5;
}

.portal-login-stage .login-alert.success {
  background: rgba(16, 185, 129, 0.16);
  border: 1px solid rgba(16, 185, 129, 0.4);
  color: #6ee7b7;
}

/* Form Style */
.portal-login-stage .input-group {
  position: relative;
  margin-bottom: 14px;
  width: 100%;
}

.portal-login-stage .input-group input {
  width: 100%;
  padding: 11px 48px 11px 18px;
  background: var(--sixth-Color);
  border: 1px solid var(--sixth-Color);
  border-radius: 25px;
  color: var(--white-Color);
  caret-color: var(--fourth-Color);
  font-size: 14px;
  outline: none;
  transition: all 0.3s ease;
  box-sizing: border-box;
}

.portal-login-stage .input-group input:focus {
  border-color: var(--third-Color);
  box-shadow: 0 0 15px rgba(255, 165, 0, 0.3);
  background: rgba(255, 255, 255, 0.14);
}

.portal-login-stage .input-group input::placeholder {
  color: rgba(255, 255, 255, 0.55);
  text-transform: none;
  font-size: 13px;
}

.portal-login-stage .input-group .input-icon {
  position: absolute;
  right: 16px;
  top: 50%;
  transform: translateY(-50%);
  color: rgba(255, 255, 255, 0.5);
  font-size: 15px;
  pointer-events: none;
}

/* Clickable Eye Button for Password */
.portal-login-stage .input-group .input-icon-btn {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  background: transparent;
  border: none;
  color: rgba(255, 255, 255, 0.55);
  font-size: 15px;
  cursor: pointer;
  padding: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: color 0.2s ease, transform 0.2s ease;
  z-index: 5;
  outline: none;
}

.portal-login-stage .input-group .input-icon-btn:hover {
  color: var(--third-Color);
  transform: translateY(-50%) scale(1.15);
}

.portal-login-stage .field-error-text {
  color: #fca5a5;
  font-size: 11px;
  margin-top: -8px;
  margin-bottom: 10px;
  padding-left: 14px;
  text-align: left;
  display: flex;
  align-items: center;
  gap: 5px;
}

.portal-login-stage .login-btn {
  width: 100%;
  padding: 12px;
  background: linear-gradient(
    90deg,
    var(--third-Color),
    var(--fourth-Color)
  );
  border: none;
  border-radius: 25px;
  color: var(--white-Color);
  font-size: 14px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
  box-shadow: 0 6px 20px rgba(255, 140, 0, 0.35);
  margin-top: 4px;
}

.portal-login-stage .login-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 30px var(--box-shadow-2);
}

.portal-login-stage .login-btn::before {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0px;
  height: 0px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.3);
  transform: translate(-50%, -50%);
  transition: width 0.6s ease, height 0.6s ease;
}

.portal-login-stage .login-btn:active::before {
  width: 300px;
  height: 300px;
}

.portal-login-stage .register-footer {
  text-align: center;
  margin-top: 16px;
  font-size: 13px;
  color: rgba(255, 255, 255, 0.7);
}

.portal-login-stage .register-footer a {
  color: var(--third-Color);
  font-weight: 700;
  text-decoration: none;
  margin-left: 5px;
  transition: color 0.2s ease;
}

.portal-login-stage .register-footer a:hover {
  color: var(--fourth-Color);
  text-decoration: underline;
}

/* Animations */
@keyframes rotate {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

/* Responsive Scaling for smaller screens */
@media (max-width: 780px) {
  .animated-login-wrapper {
    padding: 20px 10px;
    align-items: flex-start;
  }
  .portal-login-stage {
    transform: scale(calc(100vw / 780));
    transform-origin: top center;
    margin-top: 10px;
    margin-bottom: 20px;
  }
}

@media (max-height: 840px) and (min-width: 781px) {
  .portal-login-stage {
    transform: scale(0.88);
    transform-origin: center center;
  }
}
</style>

<div class="animated-login-wrapper">
  <div class="portal-login-stage">
    <!-- Complete 360-degree Rotating Halo Equalizer Ring -->
    <div class="circle-container" id="circle-Container"></div>

    <!-- Center Register Card -->
    <div class="login-box">
      <!-- Brand Logo -->
      <div class="logo-wrap">
        <a href="{{ url('/') }}" title="e-Krishi Sheba">
          <img src="{{ asset('upload/images/backend/logo') }}/{{ setting('logo') }}" alt="ekrishisheba" onerror="this.onerror=null;this.src='{{ asset('upload/images/backend/logo/1794150124EkqIV.png') }}';">
        </a>
      </div>

      <!-- Title -->
      <h3 class="title">REGISTRATION</h3>

      <!-- Bonus Badge -->
      <div class="bonus-badge-wrap">
        <div class="bonus-badge-pill">
          <i class="fa fa-gift"></i>
          <span>Instant ৳300 Bonus Credit</span>
        </div>
      </div>

      <!-- Alerts -->
      @if(session('error'))
        <div class="login-alert error">
          <i class="fa fa-exclamation-circle"></i>
          <span>{{ session('error') }}</span>
        </div>
      @endif
      @if(session('success'))
        <div class="login-alert success">
          <i class="fa fa-check-circle"></i>
          <span>{{ session('success') }}</span>
        </div>
      @endif
      @if($errors->any())
        <div class="login-alert error">
          <i class="fa fa-exclamation-circle"></i>
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      <!-- Registration Form -->
      <form id="register-Form" method="POST" action="{{ route('register.post') }}">
        @csrf

        <!-- Card Number -->
        <div class="input-group">
          <input type="text" name="card_number" value="{{ old('card_number') }}" maxlength="12" placeholder="12-Digit Card Number (কার্ড নাম্বার)" required autofocus autocomplete="off" />
          <span class="input-icon">
            <i class="fa fa-id-card"></i>
          </span>
        </div>
        @error('card_number')
          <div class="field-error-text"><i class="fa fa-exclamation-triangle"></i> {{ $message }}</div>
        @enderror

        <!-- Name -->
        <div class="input-group">
          <input type="text" name="name" value="{{ old('name') }}" placeholder="Full Name (পূর্ণ নাম)" required autocomplete="name" />
          <span class="input-icon">
            <i class="fa fa-user"></i>
          </span>
        </div>
        @error('name')
          <div class="field-error-text"><i class="fa fa-exclamation-triangle"></i> {{ $message }}</div>
        @enderror

        <!-- Mobile Number -->
        <div class="input-group">
          <input type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="Mobile Number (মোবাইল নাম্বার)" required autocomplete="tel" />
          <span class="input-icon">
            <i class="fa fa-phone"></i>
          </span>
        </div>
        @error('phone_number')
          <div class="field-error-text"><i class="fa fa-exclamation-triangle"></i> {{ $message }}</div>
        @enderror

        <!-- Password -->
        <div class="input-group">
          <input type="password" name="password" id="registerPassword" placeholder="Password (পাসওয়ার্ড কমপক্ষে ৮ অক্ষর)" required autocomplete="new-password" />
          <button type="button" class="input-icon-btn toggle-password-visibility" id="togglePasswordBtn" title="পাসওয়ার্ড দেখুন বা লুকান" aria-label="Toggle password visibility">
            <i class="fa fa-eye" id="togglePasswordIcon"></i>
          </button>
        </div>
        @error('password')
          <div class="field-error-text"><i class="fa fa-exclamation-triangle"></i> {{ $message }}</div>
        @enderror

        <!-- Confirm Password -->
        <div class="input-group">
          <input type="password" name="password_confirmation" id="registerConfirmPassword" placeholder="Confirm Password (পাসওয়ার্ড নিশ্চিত করুন)" required autocomplete="new-password" />
          <button type="button" class="input-icon-btn toggle-password-visibility" id="toggleConfirmPasswordBtn" title="পাসওয়ার্ড নিশ্চিতকরণ দেখুন বা লুকান" aria-label="Toggle password confirmation visibility">
            <i class="fa fa-eye" id="toggleConfirmPasswordIcon"></i>
          </button>
        </div>
        @error('password_confirmation')
          <div class="field-error-text"><i class="fa fa-exclamation-triangle"></i> {{ $message }}</div>
        @enderror

        <!-- Submit Button -->
        <button type="submit" class="login-btn">
          CREATE ACCOUNT & CLAIM ৳300
        </button>

        <!-- Footer Links -->
        <div class="register-footer">
          Already have an account?
          <a href="{{ route('login') }}">Sign In Here</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  // 1. Generate Halo Equalizer Ring around the Stage
  const circleContainer = document.getElementById("circle-Container");
  if (circleContainer) {
    const numBars = 56;
    let activeBars = 0;

    for (let i = 0; i < numBars; i++) {
      const bar = document.createElement("div");
      bar.className = "bar";
      bar.style.transform = `rotate(${(360 / numBars) * i}deg)`;
      circleContainer.appendChild(bar);
    }

    function animateBars() {
      const bars = document.querySelectorAll(".circle-container .bar");
      if (!bars.length) return;
      setInterval(() => {
        bars[activeBars % numBars].classList.add("active");
        if (activeBars > 8) {
          bars[(activeBars - 8) % numBars].classList.remove("active");
        }
        activeBars++;
      }, 100);
    }
    animateBars();
  }

  // 2. Toggle Password Visibility for Password & Confirm Password
  function bindPasswordToggle(btnId, inputId, iconId) {
    const btn = document.getElementById(btnId);
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (!btn || !input || !icon) return;

    btn.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
        btn.title = "পাসওয়ার্ড লুকান";
      } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
        btn.title = "পাসওয়ার্ড দেখুন";
      }
      input.focus();
    });
  }

  bindPasswordToggle("togglePasswordBtn", "registerPassword", "togglePasswordIcon");
  bindPasswordToggle("toggleConfirmPasswordBtn", "registerConfirmPassword", "toggleConfirmPasswordIcon");
});
</script>

@endsection
