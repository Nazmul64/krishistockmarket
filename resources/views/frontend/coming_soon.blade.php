@php
    $siteTheme = request()->cookie('ekrishi_theme', 'light');
    $siteLang = request()->cookie('ekrishi_lang', 'bn');
    $isBn = ($siteLang === 'bn');
    $targetDate = setting('coming_soon_date') ?: '2026-12-31 00:00:00';
    $title = setting('coming_soon_title') ?: 'COMING SOON';
    $subtitle = setting('coming_soon_subtitle') ?: 'আমাদের ওয়েবসাইট ও ডিজিটাল কৃষিপণ্য সেবা খুব শীঘ্রই উদ্বোধন হতে যাচ্ছে!';
    $themeColor = setting('header_bg_color') ?: (setting('header_color') ?: (setting('button_color') ?: '#007e8a'));
    $logo = setting('logo');
@endphp
<!DOCTYPE html>
<html lang="{{ $siteLang }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ setting('title') ?: 'e-Krishi Sheba' }} - {{ $title }}</title>
    
    <!-- Favicon -->
    @if(setting('favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset('upload/images/backend/logo') }}/{{ setting('favicon') }}" />
    @endif

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Hind+Siliguri:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body, html {
            height: 100%;
            width: 100%;
            font-family: 'Hind Siliguri', 'Montserrat', sans-serif;
            background: #090d16;
            color: #ffffff;
            overflow-x: hidden;
        }

        /* Cinematic Dark Background with Mountain & Stars Overlay */
        .coming-soon-wrapper {
            position: relative;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            background: radial-gradient(circle at 50% 20%, rgba(0, 126, 138, 0.25) 0%, rgba(9, 13, 22, 0.95) 70%),
                        url('https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=1920&q=80') center center / cover no-repeat;
            padding: 40px 20px 25px;
            text-align: center;
        }

        .coming-soon-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(9, 13, 22, 0.75) 0%, rgba(9, 13, 22, 0.92) 100%);
            z-index: 1;
        }

        .coming-soon-content {
            position: relative;
            z-index: 2;
            max-width: 860px;
            width: 100%;
            margin: auto;
        }

        /* Logo Emblem with Glow */
        .brand-emblem-wrap {
            margin-bottom: 25px;
            display: inline-block;
        }
        .brand-emblem {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            border: 2px solid {{ $themeColor }};
            box-shadow: 0 0 30px {{ $themeColor }}88, inset 0 0 15px {{ $themeColor }}44;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            backdrop-filter: blur(10px);
            padding: 10px;
            animation: pulseGlow 3s infinite alternate;
        }
        .brand-emblem img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        @keyframes pulseGlow {
            0% { box-shadow: 0 0 20px {{ $themeColor }}66; transform: scale(1); }
            100% { box-shadow: 0 0 40px {{ $themeColor }}aa; transform: scale(1.04); }
        }

        /* Coming Soon Main Heading */
        .cs-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 56px;
            font-weight: 900;
            letter-spacing: 8px;
            text-transform: uppercase;
            color: #ffffff;
            margin-bottom: 15px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.6);
            line-height: 1.15;
        }
        .cs-subtitle {
            font-size: 19px;
            font-weight: 500;
            color: #cbd5e1;
            margin-bottom: 45px;
            max-width: 650px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        /* Countdown Grid (Days, Hours, Minutes, Seconds) */
        .countdown-grid {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 45px;
            flex-wrap: wrap;
        }
        .countdown-card {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            padding: 20px 15px;
            min-width: 110px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35);
            transition: all 0.3s ease;
        }
        .countdown-card:hover {
            transform: translateY(-5px);
            border-color: {{ $themeColor }};
            box-shadow: 0 15px 35px {{ $themeColor }}44;
        }
        .countdown-number {
            font-family: 'Montserrat', sans-serif;
            font-size: 44px;
            font-weight: 800;
            color: #ffffff;
            line-height: 1;
            margin-bottom: 8px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }
        .countdown-label {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #94a3b8;
        }

        /* Email Subscribe / Notify Input */
        .subscribe-wrap {
            max-width: 520px;
            margin: 0 auto 35px;
        }
        .subscribe-form {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.08);
            border: 1.5px solid rgba(255, 255, 255, 0.2);
            border-radius: 50px;
            padding: 6px 8px 6px 22px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            transition: border-color 0.3s ease;
        }
        .subscribe-form:focus-within {
            border-color: {{ $themeColor }};
            box-shadow: 0 8px 30px {{ $themeColor }}55;
        }
        .subscribe-input {
            flex: 1;
            background: transparent;
            border: none;
            color: #ffffff;
            font-size: 15px;
            outline: none;
            font-family: inherit;
        }
        .subscribe-input::placeholder {
            color: #94a3b8;
        }
        .subscribe-btn {
            background: {{ $themeColor }};
            color: #ffffff;
            border: none;
            border-radius: 50px;
            padding: 12px 28px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            letter-spacing: 0.5px;
        }
        .subscribe-btn:hover {
            filter: brightness(1.15);
            transform: scale(1.02);
        }
        .subscribe-msg {
            margin-top: 12px;
            font-size: 14px;
            display: none;
        }

        /* Social Icons */
        .cs-social-links {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px;
        }
        .cs-social-link {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .cs-social-link:hover {
            background: {{ $themeColor }};
            border-color: {{ $themeColor }};
            transform: translateY(-3px);
            color: #ffffff;
        }

        /* Footer & Admin Login */
        .cs-footer {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 1000px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 13px;
            color: #64748b;
        }
        .admin-login-link {
            color: #94a3b8;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.2s ease;
        }
        .admin-login-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
            border-color: {{ $themeColor }};
        }

        @media (max-width: 600px) {
            .cs-title {
                font-size: 36px;
                letter-spacing: 4px;
            }
            .cs-subtitle {
                font-size: 15px;
                margin-bottom: 30px;
            }
            .countdown-grid {
                gap: 10px;
            }
            .countdown-card {
                min-width: 72px;
                padding: 14px 8px;
            }
            .countdown-number {
                font-size: 28px;
            }
            .countdown-label {
                font-size: 10px;
                letter-spacing: 1px;
            }
            .subscribe-form {
                flex-direction: column;
                border-radius: 20px;
                padding: 12px;
                gap: 10px;
            }
            .subscribe-input {
                width: 100%;
                text-align: center;
            }
            .subscribe-btn {
                width: 100%;
            }
            .cs-footer {
                justify-content: center;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <div class="coming-soon-wrapper">
        <div class="coming-soon-overlay"></div>

        <div class="coming-soon-content">
            <!-- Brand Emblem / Logo -->
            <div class="brand-emblem-wrap">
                <div class="brand-emblem">
                    @if($logo && file_exists(public_path('upload/images/backend/logo/' . $logo)))
                        <img src="{{ asset('upload/images/backend/logo/' . $logo) }}" alt="{{ setting('title') }}">
                    @else
                        <i class="fa fa-leaf" style="font-size: 40px; color: {{ $themeColor }};"></i>
                    @endif
                </div>
            </div>

            <!-- Title & Subtitle -->
            <h1 class="cs-title">{{ $title }}</h1>
            <p class="cs-subtitle">{{ $subtitle }}</p>

            <!-- 4-Block Countdown Timer -->
            <div class="countdown-grid" id="csCountdown">
                <div class="countdown-card">
                    <div class="countdown-number" id="csDays">00</div>
                    <div class="countdown-label">{{ $isBn ? 'দিন (Days)' : 'Days' }}</div>
                </div>
                <div class="countdown-card">
                    <div class="countdown-number" id="csHours">00</div>
                    <div class="countdown-label">{{ $isBn ? 'ঘণ্টা (Hours)' : 'Hours' }}</div>
                </div>
                <div class="countdown-card">
                    <div class="countdown-number" id="csMinutes">00</div>
                    <div class="countdown-label">{{ $isBn ? 'মিনিট (Mins)' : 'Minutes' }}</div>
                </div>
                <div class="countdown-card">
                    <div class="countdown-number" id="csSeconds">00</div>
                    <div class="countdown-label">{{ $isBn ? 'সেকেন্ড (Secs)' : 'Seconds' }}</div>
                </div>
            </div>

            <!-- Email Subscribe Notification Form -->
            <div class="subscribe-wrap">
                <form class="subscribe-form" id="csSubscribeForm" onsubmit="handleCsSubscribe(event)">
                    <input type="email" id="csEmailInput" class="subscribe-input" placeholder="{{ $isBn ? 'আপনার ইমেইল ঠিকানা দিন...' : 'Enter your email address...' }}" required autocomplete="email">
                    <button type="submit" class="subscribe-btn">
                        <i class="fa fa-bell-o me-1"></i> {{ $isBn ? 'আমাকে জানান (Notify Me)' : 'Notify Me' }}
                    </button>
                </form>
                <div id="csSubscribeSuccess" class="subscribe-msg text-success" style="color: #4ade80;">
                    <i class="fa fa-check-circle"></i> {{ $isBn ? 'ধন্যবাদ! উদ্বোধন মুহূর্তে আপনাকে ইমেইলে জানিয়ে দেওয়া হবে।' : 'Thank you! We will notify you upon launch.' }}
                </div>
            </div>

            <!-- Social Links -->
            <div class="cs-social-links">
                @if(setting('facbook_link'))
                    <a href="{{ setting('facbook_link') }}" target="_blank" rel="noopener" class="cs-social-link" title="Facebook"><i class="fa fa-facebook"></i></a>
                @endif
                @if(setting('twitter_link'))
                    <a href="{{ setting('twitter_link') }}" target="_blank" rel="noopener" class="cs-social-link" title="Twitter"><i class="fa fa-twitter"></i></a>
                @endif
                @if(setting('linkedin_link'))
                    <a href="{{ setting('linkedin_link') }}" target="_blank" rel="noopener" class="cs-social-link" title="LinkedIn"><i class="fa fa-linkedin"></i></a>
                @endif
                @if(setting('youtube_link'))
                    <a href="{{ setting('youtube_link') }}" target="_blank" rel="noopener" class="cs-social-link" title="YouTube"><i class="fa fa-youtube-play"></i></a>
                @endif
                @if(setting('instagram_link'))
                    <a href="{{ setting('instagram_link') }}" target="_blank" rel="noopener" class="cs-social-link" title="Instagram"><i class="fa fa-instagram"></i></a>
                @endif
            </div>
        </div>

        <!-- Footer Strip -->
        <div class="cs-footer">
            <div>
                &copy; {{ date('Y') }} {{ setting('title') ?: 'e-Krishi Sheba' }}. {{ setting('footer_copyright') ?: 'All Rights Reserved.' }}
            </div>
            <div>
                <a href="{{ route('login') }}" class="admin-login-link" title="Admin Portal Login">
                    <i class="fa fa-lock"></i> <span>{{ $isBn ? 'এডমিন লগইন' : 'Admin Login' }}</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Live Countdown Script -->
    <script>
        const targetDateStr = "{{ $targetDate }}";
        const targetTime = new Date(targetDateStr.replace(/-/g, "/")).getTime();

        function updateCountdown() {
            const now = new Date().getTime();
            let distance = targetTime - now;

            if (distance < 0 || isNaN(distance)) {
                // If past date, set a nice future counter or zeroes
                distance = 0;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('csDays').innerText = String(days).padStart(2, '0');
            document.getElementById('csHours').innerText = String(hours).padStart(2, '0');
            document.getElementById('csMinutes').innerText = String(minutes).padStart(2, '0');
            document.getElementById('csSeconds').innerText = String(seconds).padStart(2, '0');
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);

        function handleCsSubscribe(e) {
            e.preventDefault();
            const email = document.getElementById('csEmailInput').value;
            if(!email) return;

            document.getElementById('csSubscribeForm').style.display = 'none';
            document.getElementById('csSubscribeSuccess').style.display = 'block';

            // Optional background post
            fetch("{{ route('coming_soon.subscribe') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email: email })
            }).catch(() => {});
        }
    </script>
</body>
</html>
