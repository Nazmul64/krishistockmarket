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
  padding: 20px 10px;
  box-sizing: border-box;
  position: relative;
  background: linear-gradient(
    135deg,
    var(--main-Color) 0%,
    var(--sec-Color) 100%
  ) !important;
  direction: ltr !important;
}

/* Stage that holds both the 360-degree circle and the login card */
.portal-login-stage {
  position: relative;
  width: 660px;
  height: 660px;
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
  width: 660px;
  height: 660px;
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
  transform-origin: center 330px;
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

/* Center Login Card */
.portal-login-stage .login-box {
  position: relative;
  z-index: 10;
  background: var(--rgba-Color);
  padding: 38px 36px 32px;
  border-radius: 20px;
  box-shadow: 0 20px 60px var(--box-shadow-2);
  width: 380px;
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
  margin-bottom: 14px;
}

.portal-login-stage .logo-wrap img {
  height: 42px;
  max-height: 46px;
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
  margin-bottom: 6px;
  font-size: 26px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 2px;
}

.portal-login-stage .portal-subtitle {
  text-align: center;
  color: rgba(255, 255, 255, 0.7);
  font-size: 12px;
  margin-bottom: 22px;
  text-transform: none;
  letter-spacing: 0.3px;
}

/* Alerts */
.portal-login-stage .login-alert {
  padding: 10px 14px;
  border-radius: 12px;
  font-size: 13px;
  font-weight: 500;
  margin-bottom: 18px;
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
  margin-bottom: 20px;
  width: 100%;
}

.portal-login-stage .input-group input {
  width: 100%;
  padding: 12px 48px 12px 18px;
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
  color: rgba(255, 255, 255, 0.5);
  text-transform: none;
}

.portal-login-stage .input-group .input-icon {
  position: absolute;
  right: 16px;
  top: 50%;
  transform: translateY(-50%);
  color: rgba(255, 255, 255, 0.5);
  font-size: 16px;
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
  font-size: 16px;
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

.portal-login-stage .options-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-top: -8px;
  margin-bottom: 24px;
  font-size: 12px;
}

.portal-login-stage .remember-label {
  display: flex;
  align-items: center;
  gap: 6px;
  color: rgba(255, 255, 255, 0.65);
  cursor: pointer;
  user-select: none;
  margin: 0;
  font-size: 12px;
}

.portal-login-stage .remember-label input {
  accent-color: var(--third-Color);
  cursor: pointer;
  width: 14px;
  height: 14px;
}

.portal-login-stage .forget-password a {
  color: var(--white-Color);
  opacity: 0.6;
  text-decoration: none;
  font-size: 12px;
  transition: color 0.3s ease, opacity 0.2s ease;
}

.portal-login-stage .forget-password a:hover {
  color: var(--third-Color);
  opacity: 1;
}

.portal-login-stage .login-btn {
  width: 100%;
  padding: 13px;
  background: linear-gradient(
    90deg,
    var(--third-Color),
    var(--fourth-Color)
  );
  border: none;
  border-radius: 25px;
  color: var(--white-Color);
  font-size: 16px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 1px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
  box-shadow: 0 6px 20px rgba(255, 140, 0, 0.35);
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
@media (max-width: 680px) {
  .portal-login-stage {
    transform: scale(calc(100vw / 700));
    transform-origin: center center;
  }
}
</style>

<div class="animated-login-wrapper">
  <div class="portal-login-stage">
    <!-- Complete 360-degree Rotating Halo Equalizer Ring -->
    <div class="circle-container" id="circle-Container"></div>

    <!-- Center Login Card -->
    <div class="login-box">
      <!-- Logo -->
      <div class="logo-wrap">
        <a href="{{ url('/') }}" title="e-Krishi Sheba">
          <img src="{{ asset('upload/images/backend/logo') }}/{{ setting('logo') }}" alt="ekrishisheba" onerror="this.onerror=null;this.src='{{ asset('upload/images/backend/logo/1794150124EkqIV.png') }}';">
        </a>
      </div>

      <!-- Title -->
      <h3 class="title">
        @if(isset($portal) && $portal !== 'customer')
          {{ strtoupper($portal) }} LOGIN
        @else
          LOGIN
        @endif
      </h3>

      @if(isset($config['title']) && isset($portal) && $portal !== 'customer')
        <div class="portal-subtitle">{{ $config['title'] }}</div>
      @endif

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

      <!-- Form -->
      <form id="login-Form" method="POST" action="{{ $config['action_url'] ?? url()->current() }}">
        @csrf
        <div class="input-group">
          <input type="text" name="username" value="{{ old('username') }}" placeholder="Email / Username" required autofocus />
          <span class="input-icon">
            <i class="fa fa-envelope"></i>
          </span>
        </div>
        <div class="input-group">
          <input type="password" name="password" id="loginPassword" placeholder="Password" required />
          <button type="button" class="input-icon-btn toggle-password-visibility" id="togglePasswordBtn" title="পাসওয়ার্ড দেখুন বা লুকান" aria-label="Toggle password visibility">
            <i class="fa fa-eye" id="togglePasswordIcon"></i>
          </button>
        </div>
        <div class="options-container">
          <label class="remember-label">
            <input type="checkbox" name="remember" id="remember_me">
            <span>Remember me</span>
          </label>
          <div class="forget-password">
            @if(Route::has('password.request'))
              <a href="{{ route('password.request') }}">forget your password ?</a>
            @else
              <a href="#">forget your password ?</a>
            @endif
          </div>
        </div>
        <button type="submit" class="login-btn">login</button>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  // 1. Generate All 50 Bars in a Full 360-degree Circle around the Card
  const circleContainer = document.getElementById("circle-Container");
  if (circleContainer) {
    const numBars = 50;
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

  // 2. Toggle Password Visibility (Eye Button)
  const togglePasswordBtn = document.getElementById("togglePasswordBtn");
  const passwordInput = document.getElementById("loginPassword");
  const togglePasswordIcon = document.getElementById("togglePasswordIcon");

  if (togglePasswordBtn && passwordInput && togglePasswordIcon) {
    togglePasswordBtn.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        togglePasswordIcon.classList.remove("fa-eye");
        togglePasswordIcon.classList.add("fa-eye-slash");
        togglePasswordBtn.title = "পাসওয়ার্ড লুকান";
      } else {
        passwordInput.type = "password";
        togglePasswordIcon.classList.remove("fa-eye-slash");
        togglePasswordIcon.classList.add("fa-eye");
        togglePasswordBtn.title = "পাসওয়ার্ড দেখুন";
      }
      passwordInput.focus();
    });
  }
});
</script>

@endsection
