<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - অননুমোদিত পোর্টাল এক্সেস | iKrishiPoribar</title>
    <link rel="stylesheet" href="{{ asset('assets/main/css/vendors_css.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/main/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/icons/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/icons/themify-icons/themify-icons.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #311042 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #f8fafc;
            margin: 0;
            padding: 20px;
        }
        .error-card {
            background: rgba(30, 41, 59, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 20px;
            max-width: 560px;
            width: 100%;
            padding: 40px;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7), 0 0 30px rgba(239, 68, 68, 0.2);
        }
        .shield-icon {
            width: 80px;
            height: 80px;
            background: rgba(239, 68, 68, 0.15);
            border: 2px solid #ef4444;
            color: #ef4444;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            margin-bottom: 24px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.06); }
        }
        .code-badge {
            display: inline-block;
            background: #ef4444;
            color: #fff;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }
        .error-title {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 12px;
            color: #ffffff;
        }
        .error-desc {
            font-size: 15px;
            color: #cbd5e1;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .isolation-info {
            background: rgba(15, 23, 42, 0.6);
            border: 1px dashed rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 28px;
            text-align: left;
            font-size: 13px;
        }
        .isolation-info div {
            margin-bottom: 6px;
            display: flex;
            justify-content: space-between;
        }
        .isolation-info div:last-child {
            margin-bottom: 0;
        }
        .btn-return {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            font-weight: 700;
            padding: 14px 28px;
            border-radius: 12px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
            box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.4);
            transition: all 0.2s ease;
        }
        .btn-return:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 14px 25px -5px rgba(16, 185, 129, 0.6);
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="shield-icon">
            <i class="ti-lock"></i>
        </div>
        <div>
            <span class="code-badge">HTTP 403 • ZERO TRUST VIOLATION</span>
        </div>
        <h2 class="error-title">Unauthorized Portal Access</h2>
        <p class="error-desc">
            {{ $errorMessage ?? 'Unauthorized Portal Access: আপনি এই পোর্টালে প্রবেশের অনুমতিপ্রাপ্ত নন।' }}
        </p>

        <div class="isolation-info">
            <div>
                <span class="text-muted"><i class="ti-user me-1"></i> ব্যবহারকারী:</span>
                <span class="fw-bold">{{ $user->name ?? 'Guest' }} ({{ $user->username ?? '' }})</span>
            </div>
            <div>
                <span class="text-muted"><i class="ti-id-badge me-1"></i> নির্ধারিত রোল:</span>
                <span class="badge bg-primary">{{ strtoupper($user->role ?? 'N/A') }}</span>
            </div>
            <div>
                <span class="text-muted"><i class="ti-direction-alt me-1"></i> আপনার অনুমোদিত পোর্টাল:</span>
                <span class="text-success fw-bold">{{ $userPortal ?? '/portal' }}</span>
            </div>
            <div>
                <span class="text-muted"><i class="ti-alert me-1"></i> নিষিদ্ধ পোর্টাল পাথ:</span>
                <span class="text-danger fw-bold">{{ $requiredPortal ?? 'N/A' }}</span>
            </div>
        </div>

        <div>
            <a href="{{ route($ownDashboard ?? 'home') }}" class="btn-return">
                <i class="ti-arrow-left"></i> আপনার নির্ধারিত ড্যাশবোর্ডে ফিরুন
            </a>
        </div>
        <div class="mt-4 text-muted small">
            <i class="ti-shield me-1"></i> এই অননুমোদিত চেষ্টাটি সরাসরি সাইবার সিকিউরিটি অ্যালার্টে রেকর্ড করা হয়েছে।
        </div>
    </div>
</body>
</html>

