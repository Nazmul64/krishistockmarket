@extends('layouts.auth.app')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
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
  background: linear-gradient(135deg, var(--main-Color) 0%, var(--sec-Color) 100%) !important;
  background-color: var(--main-Color) !important;
  display: flex !important;
  justify-content: center !important;
  align-items: center !important;
  min-height: 100vh !important;
  width: 100vw !important;
  margin: 0 !important;
  padding: 0 !important;
  overflow-x: hidden !important;
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
  background: linear-gradient(135deg, var(--main-Color) 0%, var(--sec-Color) 100%) !important;
}

.portal-login-stage {
  position: relative;
  width: 520px;
  height: 520px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.portal-login-stage .circle-container {
  position: absolute;
  width: 100%;
  height: 100%;
  border-radius: 50%;
  animation: rotate 20s linear infinite;
  pointer-events: none;
}

.portal-login-stage .bar {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 4px;
  height: 250px;
  background: transparent;
  transform-origin: top center;
  pointer-events: none;
}

.portal-login-stage .bar::before {
  content: "";
  position: absolute;
  bottom: 0;
  left: 0;
  width: 4px;
  height: 22px;
  background: var(--fiveth-Color);
  border-radius: 2px;
  box-shadow: 0 0 4px var(--sec-Color);
  transition: all 0.25s ease;
}

.portal-login-stage .bar.active::before {
  height: 38px;
  background: var(--third-Color);
  box-shadow: var(--box-shadow-1);
}

.portal-login-stage .login-box {
  width: 380px;
  padding: 34px 28px;
  background: var(--rgba-Color);
  border-radius: 22px;
  backdrop-filter: blur(14px);
  -webkit-backdrop-filter: blur(14px);
  border: 1px solid var(--sixth-Color);
  box-shadow: 0 16px 40px var(--box-shadow-2);
  z-index: 10;
  position: relative;
  box-sizing: border-box;
  text-align: center;
}

.portal-login-stage .logo-wrap img {
  max-height: 48px;
  width: auto;
  margin-bottom: 12px;
  filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
}

.portal-login-stage .title {
  color: var(--third-Color);
  font-size: 22px;
  font-weight: 800;
  letter-spacing: 2px;
  margin: 0 0 8px 0;
  text-transform: uppercase;
}

.portal-login-stage .portal-subtitle {
  color: #94a3b8;
  font-size: 13px;
  margin-bottom: 18px;
  line-height: 1.4;
}

.portal-login-stage .login-alert {
  padding: 10px 14px;
  border-radius: 10px;
  font-size: 13px;
  margin-bottom: 16px;
  text-align: left;
  display: flex;
  align-items: center;
  gap: 10px;
}

.portal-login-stage .login-alert.error {
  background: rgba(239, 68, 68, 0.15);
  border: 1px solid rgba(239, 68, 68, 0.4);
  color: #fca5a5;
}

.portal-login-stage .login-alert.success {
  background: rgba(16, 185, 129, 0.15);
  border: 1px solid rgba(16, 185, 129, 0.4);
  color: #6ee7b7;
}

.portal-login-stage .input-group {
  position: relative;
  margin-bottom: 18px;
}

.portal-login-stage .input-group input {
  width: 100%;
  padding: 13px 44px 13px 18px;
  background: #ffffff !important;
  border: 1px solid #cbd5e1;
  border-radius: 25px;
  color: #1e293b !important;
  font-size: 14.5px;
  font-weight: 500;
  outline: none;
  box-sizing: border-box;
  transition: all 0.3s ease;
}

.portal-login-stage .input-group input:focus {
  border-color: var(--third-Color);
  box-shadow: 0 0 10px rgba(255, 165, 0, 0.4);
}

.portal-login-stage .input-icon {
  position: absolute;
  right: 16px;
  top: 50%;
  transform: translateY(-50%);
  color: #64748b;
  font-size: 15px;
}

.portal-login-stage .login-btn {
  width: 100%;
  padding: 13px;
  background: linear-gradient(90deg, var(--third-Color), var(--fourth-Color));
  border: none;
  border-radius: 25px;
  color: var(--white-Color);
  font-size: 15px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 6px 20px rgba(255, 140, 0, 0.35);
  margin-top: 6px;
}

.portal-login-stage .login-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 30px var(--box-shadow-2);
}

.portal-login-stage .back-to-login {
  margin-top: 18px;
}

.portal-login-stage .back-to-login a {
  color: #cbd5e1;
  font-size: 13px;
  text-decoration: none;
  transition: color 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.portal-login-stage .back-to-login a:hover {
  color: var(--third-Color);
}

@keyframes rotate {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

@media (max-width: 680px) {
  .portal-login-stage {
    transform: scale(calc(100vw / 700));
    transform-origin: center center;
  }
}
</style>

<div class="animated-login-wrapper">
  <div class="portal-login-stage">
    <div class="circle-container" id="circle-Container"></div>

    <div class="login-box">
      <div class="logo-wrap">
        <a href="{{ url('/') }}" title="e-Krishi Sheba">
          <img src="{{ asset('upload/images/backend/logo') }}/{{ setting('logo') }}" alt="ekrishisheba" onerror="this.onerror=null;this.src='{{ asset('upload/images/backend/logo/1794150124EkqIV.png') }}';">
        </a>
      </div>

      <h3 class="title">RESET PASSWORD</h3>
      <div class="portal-subtitle">আপনার রেজিস্টার্ড ইমেইল দিন, আমরা পাসওয়ার্ড রিসেট করার লিঙ্ক পাঠিয়ে দেব।</div>

      @if (session('status'))
        <div class="login-alert success">
          <i class="fa fa-check-circle"></i>
          <span>{{ session('status') }}</span>
        </div>
      @endif

      @if($errors->any())
        <div class="login-alert error">
          <i class="fa fa-exclamation-circle"></i>
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="input-group">
          <input type="email" name="email" value="{{ old('email') }}" placeholder="আপনার ইমেইল অ্যাড্রেস" required autofocus autocomplete="email" />
          <span class="input-icon">
            <i class="fa fa-envelope"></i>
          </span>
        </div>

        <button type="submit" class="login-btn">রিসেট লিঙ্ক পাঠান</button>

        <div class="back-to-login">
          <a href="{{ route('login') }}">
            <i class="fa fa-arrow-left"></i> লগইন পেজে ফিরে যান
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
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
    setInterval(() => {
      const bars = document.querySelectorAll(".circle-container .bar");
      if (!bars.length) return;
      bars[activeBars % numBars].classList.add("active");
      if (activeBars > 8) {
        bars[(activeBars - 8) % numBars].classList.remove("active");
      }
      activeBars++;
    }, 100);
  }
});
</script>
@endsection
