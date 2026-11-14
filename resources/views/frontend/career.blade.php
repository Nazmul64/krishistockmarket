@extends('layouts.frontend.app')

@section('content')
<div class="main-container">

    <!-- Scoped Styles for Modern Form UI -->
    <style>
        .career-hero-banner {
            background: linear-gradient(135deg, #064e3b 0%, #0f172a 60%, #1e293b 100%);
            position: relative;
            overflow: hidden;
        }
        .career-hero-banner::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.2) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        /* Modern Form Container */
        .modern-form-card {
            background: #ffffff;
            border-radius: 22px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.08), 0 1px 3px rgba(0,0,0,0.03);
            overflow: hidden;
            position: relative;
            transition: all 0.3s ease;
        }
        .modern-form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981 0%, #059669 50%, #047857 100%);
        }

        /* Section Separator & Badges */
        .form-section-title {
            font-size: 14px;
            font-weight: 700;
            padding: 8px 18px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }
        .form-section-emerald {
            color: #065f46;
            background: #ecfdf5;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        .form-section-blue {
            color: #1e40af;
            background: #eff6ff;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        .form-section-purple {
            color: #6b21a8;
            background: #faf5ff;
            border: 1px solid rgba(168, 85, 247, 0.3);
        }

        /* Modern Form Controls */
        .modern-form-group {
            margin-bottom: 22px;
        }
        .modern-label {
            font-size: 13.5px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .modern-label .required-star {
            color: #ef4444;
            font-weight: bold;
            margin-left: 3px;
        }
        .modern-label-hint {
            font-size: 12px;
            font-weight: 500;
            color: #94a3b8;
        }

        /* Input Group with Integrated Icon */
        .modern-input-group {
            position: relative;
            display: flex;
            align-items: center;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            background: #f8fafc;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }
        .modern-input-group:hover {
            border-color: #cbd5e1;
            background: #ffffff;
        }
        .modern-input-group:focus-within {
            border-color: #10b981;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.14), 0 2px 8px rgba(16, 185, 129, 0.08);
        }
        .modern-input-icon {
            width: 48px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 16px;
            background: transparent;
            transition: color 0.25s ease;
            flex-shrink: 0;
        }
        .modern-input-group:focus-within .modern-input-icon {
            color: #10b981;
        }
        .modern-input-field {
            flex: 1;
            height: 50px;
            padding: 0 16px 0 0;
            font-size: 14.5px;
            color: #0f172a;
            background: transparent;
            border: none;
            outline: none;
            font-family: inherit;
        }
        .modern-input-field::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        /* Custom Modern Select Wrap */
        .modern-select-wrap {
            position: relative;
            display: flex;
            align-items: center;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            background: #f8fafc;
            transition: all 0.25s ease;
            overflow: hidden;
        }
        .modern-select-wrap:hover {
            border-color: #cbd5e1;
            background: #ffffff;
        }
        .modern-select-wrap:focus-within {
            border-color: #10b981;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.14);
        }
        .modern-select-field {
            flex: 1;
            height: 50px;
            padding: 0 42px 0 0;
            font-size: 14.5px;
            color: #0f172a;
            background: transparent;
            border: none;
            outline: none;
            font-family: inherit;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
        }
        .modern-select-arrow {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #64748b;
            font-size: 14px;
            transition: color 0.25s ease;
        }
        .modern-select-wrap:focus-within .modern-select-arrow {
            color: #10b981;
        }

        /* Modern Textarea Wrap */
        .modern-textarea-wrap {
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            background: #f8fafc;
            transition: all 0.25s ease;
            overflow: hidden;
            padding: 12px 16px;
        }
        .modern-textarea-wrap:hover {
            border-color: #cbd5e1;
            background: #ffffff;
        }
        .modern-textarea-wrap:focus-within {
            border-color: #10b981;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.14);
        }
        .modern-textarea-field {
            width: 100%;
            min-height: 110px;
            border: none;
            outline: none;
            background: transparent;
            font-size: 14.5px;
            color: #0f172a;
            line-height: 1.6;
            resize: vertical;
            font-family: inherit;
        }
        .modern-textarea-field::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        /* Modern Drag & Drop File Upload Zone */
        .modern-dropzone {
            border: 2px dashed #10b981;
            background: linear-gradient(180deg, #f0fdf4 0%, #f8fafc 100%);
            border-radius: 18px;
            padding: 34px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        .modern-dropzone:hover, .modern-dropzone.dragover {
            border-color: #059669;
            background: #ecfdf5;
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(16, 185, 129, 0.15);
        }
        .modern-dropzone-icon-circle {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 2px solid #a7f3d0;
            color: #059669;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 14px;
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.18);
            transition: transform 0.3s ease;
        }
        .modern-dropzone:hover .modern-dropzone-icon-circle {
            transform: scale(1.1);
        }
        .btn-dropzone-browse {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 24px;
            background: #ffffff;
            color: #059669;
            border: 1.5px solid #10b981;
            border-radius: 50px;
            font-size: 13.5px;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);
            transition: all 0.2s ease;
        }
        .btn-dropzone-browse:hover {
            background: #10b981;
            color: #ffffff;
        }

        /* File Selected Preview Card */
        .file-selected-card {
            display: none;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            border: 1.5px solid #10b981;
            border-radius: 14px;
            padding: 12px 18px;
            margin-top: 14px;
            box-shadow: 0 4px 16px rgba(16, 185, 129, 0.12);
        }

        /* Submit Button with Modern Pulse Gradient */
        .btn-modern-submit {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            border: none;
            color: #ffffff;
            padding: 16px 28px;
            font-size: 16px;
            font-weight: 700;
            border-radius: 14px;
            width: 100%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.45);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-modern-submit:hover {
            background: linear-gradient(135deg, #047857 0%, #059669 100%);
            transform: translateY(-2px);
            box-shadow: 0 16px 32px -4px rgba(16, 185, 129, 0.55);
            color: #ffffff;
        }

        /* Guideline Perks Card */
        .perk-item-card {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 14px 16px;
            border-radius: 14px;
            background: #f8fafc;
            border: 1px solid #edf2f7;
            margin-bottom: 14px;
            transition: all 0.2s ease;
        }
        .perk-item-card:hover {
            background: #ffffff;
            border-color: #cbd5e1;
            box-shadow: 0 6px 16px rgba(0,0,0,0.04);
            transform: translateY(-2px);
        }
        .perk-icon-wrap {
            width: 44px;
            height: 44px;
            min-width: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
    </style>
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
    </style>

    <!-- Hero Banner -->
    <div class="career-hero-banner text-center py-5">
        <div class="container position-relative py-3" style="z-index: 2;">
            <span class="badge px-3 py-2 rounded-pill mb-3" style="background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.35); font-size: 13px;">
                <i class="fa fa-briefcase me-1"></i> ক্যারিয়ার গড়ুন আমাদের সাথে
            </span>
            <h1 class="display-5 fw-bold text-white mb-2" style="letter-spacing: -0.5px;">ক্যারিয়ার পাথ (Career Opportunities)</h1>
            <p class="text-white-50 mx-auto fs-6" style="max-width: 650px;">
                ই-কৃষি সেবা পরিবারের সাথে আপনার মেধা ও পরিশ্রম যুক্ত করে গড়ে তুলুন একটি সমৃদ্ধ ও স্থায়ী ডিজিটাল ক্যারিয়ার
            </p>
        </div>
    </div>

    <main class="site-main py-5 bg-light">
        <div class="container">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 p-4 mb-4 shadow-sm" role="alert" style="background: #ecfdf5; border: 1.5px solid #10b981; color: #065f46;">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-check-circle fa-2x me-3 text-success"></i>
                        <div>
                            <h5 class="fw-bold mb-1">আবেদন সফলভাবে গৃহীত হয়েছে!</h5>
                            <p class="mb-0 fs-6">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-4 p-4 mb-4 shadow-sm" role="alert">
                    <h6 class="fw-bold mb-2"><i class="fa fa-exclamation-triangle me-2"></i>কিছু তথ্যে অসঙ্গতি রয়েছে:</h6>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4">

                <!-- Left Column: Career Guidelines & Benefits -->
                <div class="col-lg-4 col-md-5">
                    <div class="modern-form-card p-4 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-4">
                                <span class="badge bg-warning bg-opacity-20 text-warning px-3 py-2 rounded-pill fw-bold">
                                    <i class="fa fa-star me-1"></i> আমাদের সুবিধা
                                </span>
                            </div>
                            <h4 class="fw-bold text-dark mb-4">কেন আমাদের সাথে কাজ করবেন?</h4>

                            <div class="perk-item-card">
                                <div class="perk-icon-wrap" style="background: #ecfdf5; color: #059669;">
                                    <i class="fa fa-leaf"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">ডিজিটাল এগ্রো বিপ্লব</h6>
                                    <p class="text-muted small mb-0">বাংলাদেশের কৃষিখাতে আধুনিক তথ্যপ্রযুক্তি ও ডিজিটাল সাপ্লাই চেইনে কাজ করার অনন্য সুযোগ।</p>
                                </div>
                            </div>

                            <div class="perk-item-card">
                                <div class="perk-icon-wrap" style="background: #eff6ff; color: #2563eb;">
                                    <i class="fa fa-line-chart"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">উজ্জ্বল ক্যারিয়ার প্রবৃদ্ধি</h6>
                                    <p class="text-muted small mb-0">মেধা ও পারফরম্যান্স অনুযায়ী নিয়মিত ইনসেন্টিভ, বোনাস এবং দ্রুত পদোন্নতি সুবিধা।</p>
                                </div>
                            </div>

                            <div class="perk-item-card">
                                <div class="perk-icon-wrap" style="background: #fef3c7; color: #d97706;">
                                    <i class="fa fa-users"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">সৌহার্দ্যপূর্ণ সংস্কৃতি</h6>
                                    <p class="text-muted small mb-0">সহযোগিতাপূর্ণ আধুনিক অফিস কালচার যেখানে প্রতিটি কর্মীর মতামত সমান গুরুত্বপূর্ণ।</p>
                                </div>
                            </div>

                            <!-- Rules Box -->
                            <div class="p-3 rounded-4 mt-4" style="background: #f1f5f9; border: 1px solid #e2e8f0;">
                                <h6 class="fw-bold text-dark mb-2">
                                    <i class="fa fa-info-circle text-primary me-1"></i> আবেদনের নির্দেশিকা:
                                </h6>
                                <ul class="text-muted mb-0 ps-3 small" style="line-height: 1.7;">
                                    <li>আপনার হালনাগাদ <strong>সিভি (PDF ফরম্যাটে)</strong> আপলোড করুন (সর্বোচ্চ ১০MB)।</li>
                                    <li>সঠিক ও সচল মোবাইল নম্বর এবং বর্তমান ঠিকানা প্রদান করুন।</li>
                                    <li>কভার লেটার ঐচ্ছিক, তবে সংক্ষিপ্ত পরিচিতি লিখলে অগ্রাধিকার দেওয়া হবে।</li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top text-center text-muted small">
                            নিয়োগ সংক্রান্ত তথ্যের জন্য সরাসরি যোগাযোগ:<br>
                            <strong class="text-dark"><i class="fa fa-phone text-success me-1"></i>{{ setting('phone1') }}</strong> | 
                            <span class="text-primary"><i class="fa fa-envelope text-primary me-1"></i>{{ setting('email1') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Career Application Form -->
                <div class="col-lg-8 col-md-7">
                    <div class="modern-form-card p-4 p-lg-5">
                        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2 border-bottom pb-3">
                            <div>
                                <h3 class="fw-bold text-dark mb-1">
                                    <i class="fa fa-id-card-o text-success me-2"></i> অনলাইনে ক্যারিয়ার আবেদন
                                </h3>
                                <p class="text-muted small mb-0">আপনার প্রয়োজনীয় তথ্য পূরণ করে নিচে সিভি (PDF) আপলোড করুন</p>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill fw-bold" style="font-size: 12px;">
                                <i class="fa fa-circle text-success me-1" style="font-size: 8px;"></i> নিয়োগ উন্মুক্ত
                            </span>
                        </div>

                        <form action="{{ route('career.apply') }}" method="POST" enctype="multipart/form-data" id="careerForm">
                            @csrf

                            <!-- Section 1: Personal Info -->
                            <div class="mb-4">
                                <div class="form-section-title form-section-emerald">
                                    <i class="fa fa-user-circle"></i> ১. ব্যক্তিগত পরিচয় (Personal Information)
                                </div>

                                <div class="row">
                                    <!-- First Name -->
                                    <div class="col-md-6 mb-3">
                                        <div class="modern-form-group mb-0">
                                            <label for="first_name" class="modern-label">
                                                <span>ফার্স্ট নেম (First Name) <span class="required-star">*</span></span>
                                            </label>
                                            <div class="modern-input-group">
                                                <div class="modern-input-icon"><i class="fa fa-user"></i></div>
                                                <input type="text" class="modern-input-field @error('first_name') is-invalid @enderror" 
                                                       id="first_name" name="first_name" value="{{ old('first_name') }}" 
                                                       placeholder="উদাঃ মোঃ নাজমুল" required>
                                            </div>
                                            @error('first_name')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Last Name -->
                                    <div class="col-md-6 mb-3">
                                        <div class="modern-form-group mb-0">
                                            <label for="last_name" class="modern-label">
                                                <span>লাস্ট নেম (Last Name) <span class="required-star">*</span></span>
                                            </label>
                                            <div class="modern-input-group">
                                                <div class="modern-input-icon"><i class="fa fa-user-o"></i></div>
                                                <input type="text" class="modern-input-field @error('last_name') is-invalid @enderror" 
                                                       id="last_name" name="last_name" value="{{ old('last_name') }}" 
                                                       placeholder="উদাঃ ইসলাম" required>
                                            </div>
                                            @error('last_name')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Phone Number -->
                                    <div class="col-md-6 mb-3">
                                        <div class="modern-form-group mb-0">
                                            <label for="phone" class="modern-label">
                                                <span>মোবাইল নম্বর (Phone Number) <span class="required-star">*</span></span>
                                            </label>
                                            <div class="modern-input-group">
                                                <div class="modern-input-icon"><i class="fa fa-phone"></i></div>
                                                <input type="tel" class="modern-input-field @error('phone') is-invalid @enderror" 
                                                       id="phone" name="phone" value="{{ old('phone') }}" 
                                                       placeholder="উদাঃ 017XXXXXXXX" required>
                                            </div>
                                            @error('phone')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6 mb-3">
                                        <div class="modern-form-group mb-0">
                                            <label for="email" class="modern-label">
                                                <span>ইমেইল ঠিকানা (Email)</span>
                                                <span class="modern-label-hint">(ঐচ্ছিক)</span>
                                            </label>
                                            <div class="modern-input-group">
                                                <div class="modern-input-icon"><i class="fa fa-envelope"></i></div>
                                                <input type="email" class="modern-input-field @error('email') is-invalid @enderror" 
                                                       id="email" name="email" value="{{ old('email') }}" 
                                                       placeholder="উদাঃ yourname@example.com">
                                            </div>
                                            @error('email')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Position & Address -->
                            <div class="mb-4">
                                <div class="form-section-title form-section-blue">
                                    <i class="fa fa-briefcase"></i> ২. পদের পছন্দ ও বর্তমান ঠিকানা (Position & Address)
                                </div>

                                <div class="row">
                                    <!-- Position Applied -->
                                    <div class="col-12 mb-3">
                                        <div class="modern-form-group mb-0">
                                            <label for="position_applied" class="modern-label">
                                                <span>আবেদিত পদবী / পছন্দের বিভাগ (Position Applied) <span class="required-star">*</span></span>
                                            </label>
                                            <div class="modern-select-wrap">
                                                <div class="modern-input-icon"><i class="fa fa-id-badge"></i></div>
                                                <select class="modern-select-field @error('position_applied') is-invalid @enderror" id="position_applied" name="position_applied">
                                                    <option value="General Career Application">সাধারণ ক্যারিয়ার আবেদন (Open / General Application)</option>
                                                    <option value="Field Agro Officer">ফিল্ড এগ্রো অফিসার (Field Agro Officer)</option>
                                                    <option value="Marketing & Distribution Executive">মার্কেটিং & ডিস্ট্রিবিউশন এক্সিকিউটিভ</option>
                                                    <option value="Customer Support Representative">কাস্টমার সাপোর্ট রিপ্রেজেনটেটিভ</option>
                                                    <option value="Branch Coordinator">শাখা পরিচালক / ব্রাঞ্চ কো-অর্ডিনেটর</option>
                                                    <option value="IT & Digital Operations">আইটি & ডিজিটাল অপারেশনস</option>
                                                    <option value="Accounting & Audit">অ্যাকাউন্টিং & অডিট</option>
                                                </select>
                                                <div class="modern-select-arrow"><i class="fa fa-chevron-down"></i></div>
                                            </div>
                                            @error('position_applied')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Full Address -->
                                    <div class="col-12 mb-3">
                                        <div class="modern-form-group mb-0">
                                            <label for="address" class="modern-label">
                                                <span>বর্তমান ঠিকানা (Full Address) <span class="required-star">*</span></span>
                                            </label>
                                            <div class="modern-input-group">
                                                <div class="modern-input-icon"><i class="fa fa-map-marker"></i></div>
                                                <input type="text" class="modern-input-field @error('address') is-invalid @enderror" 
                                                       id="address" name="address" value="{{ old('address') }}" 
                                                       placeholder="বাড়ি নং, রোড, এলাকা/গ্রাম, থানা, জেলা" required>
                                            </div>
                                            @error('address')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3: Documents & Cover Letter -->
                            <div class="mb-4">
                                <div class="form-section-title form-section-purple">
                                    <i class="fa fa-file-text-o"></i> ৩. সিভি ও পরিচিতি সংযুক্তি (Resume & Cover Letter)
                                </div>

                                <div class="row">
                                    <!-- Resume PDF Upload -->
                                    <div class="col-12 mb-3">
                                        <div class="modern-form-group mb-0">
                                            <label class="modern-label">
                                                <span>সিভি / রেজুমে আপলোড (Resume PDF) <span class="required-star">*</span></span>
                                                <span class="modern-label-hint">শুধুমাত্র PDF ফাইল (সর্বোচ্চ ১০MB)</span>
                                            </label>

                                            <!-- Custom Modern Dropzone -->
                                            <div class="modern-dropzone" id="dropzoneWrap" onclick="document.getElementById('resume').click()">
                                                <div class="modern-dropzone-icon-circle">
                                                    <i class="fa fa-cloud-upload"></i>
                                                </div>
                                                <h5 class="fw-bold text-dark mb-1" style="font-size: 16px;">আপনার সিভি (PDF ফাইল) নির্বাচন করতে এখানে ক্লিক করুন</h5>
                                                <p class="text-muted small mb-3">অথবা আপনার ড্রাইভ থেকে ফাইল টেনে এনে ছেড়ে দিন (Drag & Drop)</p>
                                                
                                                <span class="btn-dropzone-browse">
                                                    <i class="fa fa-folder-open-o"></i> ফাইল ব্রাউজ করুন (Browse File)
                                                </span>

                                                <!-- 100% Invisible Native File Input -->
                                                <input type="file" id="resume" name="resume" accept="application/pdf" 
                                                       style="display: none !important; position: absolute !important; opacity: 0 !important; width: 0 !important; height: 0 !important; pointer-events: none !important;" 
                                                       required onchange="handleFileSelect(this)">

                                                <!-- Selected File Display -->
                                                <div id="fileSelectedDisplay" class="file-selected-card">
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-danger text-white rounded p-2 me-2" style="font-size: 14px;">
                                                            <i class="fa fa-file-pdf-o"></i> PDF
                                                        </span>
                                                        <div class="text-start">
                                                            <strong id="fileNameTxt" class="d-block text-dark small">কোনো ফাইল নির্বাচিত হয়নি</strong>
                                                            <span id="fileSizeTxt" class="text-muted" style="font-size: 11px;"></span>
                                                        </div>
                                                    </div>
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1 rounded-pill small">
                                                        <i class="fa fa-check-circle me-1"></i> নির্বাচিত হয়েছে
                                                    </span>
                                                </div>
                                            </div>

                                            @error('resume')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Cover Letter (Optional) -->
                                    <div class="col-12 mb-3">
                                        <div class="modern-form-group mb-0">
                                            <label for="cover_letter" class="modern-label">
                                                <span>কভার লেটার (Cover Letter)</span>
                                                <span class="modern-label-hint">(ঐচ্ছিক - সংক্ষেপে নিজের যোগ্যতা লিখুন)</span>
                                            </label>
                                            <div class="modern-textarea-wrap">
                                                <textarea class="modern-textarea-field @error('cover_letter') is-invalid @enderror" 
                                                          id="cover_letter" name="cover_letter" rows="4" 
                                                          placeholder="নিজের পূর্ব অভিজ্ঞতা, শিক্ষাগত যোগ্যতা এবং কেন আপনি আমাদের প্রতিষ্ঠানে কাজ করতে আগ্রহী তা সংক্ষেপে তুলে ধরুন...">{{ old('cover_letter') }}</textarea>
                                            </div>
                                            @error('cover_letter')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="mt-4 pt-2">
                                <button type="submit" class="btn-modern-submit">
                                    <span>আবেদন নিশ্চিত ও জমা দিন (Submit Application)</span>
                                    <i class="fa fa-paper-plane"></i>
                                </button>
                                <small class="text-muted text-center d-block mt-3">
                                    <i class="fa fa-shield text-success me-1"></i> আপনার প্রদত্ত সমস্ত তথ্য সম্পূর্ণ সুরক্ষিত ও গোপনীয় থাকবে।
                                </small>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>

</div>

<!-- Dropzone Interactive Script -->
<script>
function handleFileSelect(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        var sizeMB = (file.size / (1024 * 1024)).toFixed(2);
        document.getElementById('fileNameTxt').innerText = file.name;
        document.getElementById('fileSizeTxt').innerText = '(' + sizeMB + ' MB)';
        var displayEl = document.getElementById('fileSelectedDisplay');
        displayEl.style.display = 'flex';
    }
}

// Drag & drop highlight
var dropWrap = document.getElementById('dropzoneWrap');
if (dropWrap) {
    ['dragenter', 'dragover'].forEach(function(eventName) {
        dropWrap.addEventListener(eventName, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropWrap.classList.add('dragover');
        }, false);
    });
    ['dragleave', 'drop'].forEach(function(eventName) {
        dropWrap.addEventListener(eventName, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropWrap.classList.remove('dragover');
        }, false);
    });
    dropWrap.addEventListener('drop', function(e) {
        var dt = e.dataTransfer;
        var files = dt.files;
        if (files.length > 0) {
            var fileInput = document.getElementById('resume');
            fileInput.files = files;
            handleFileSelect(fileInput);
        }
    }, false);
}
</script>
@endsection
