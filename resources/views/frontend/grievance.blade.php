@extends(Auth::check() ? 'layouts.backend.app' : 'layouts.frontend.app')

@section('content')
@if(Auth::check())
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">
@endif
<div class="corporate-page-wrap py-5 bg-light">

    <!-- Scoped Styles for Modern Form UI -->
    <style>
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
            background: linear-gradient(90deg, #dc2626 0%, #ef4444 50%, #f87171 100%);
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
            border-color: #dc2626;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.14), 0 2px 8px rgba(220, 38, 38, 0.08);
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
            color: #dc2626;
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
            border-color: #dc2626;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.14);
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
        }
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
            border-color: #dc2626;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.14);
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
        .btn-modern-grievance {
            background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%);
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
            box-shadow: 0 10px 25px -5px rgba(220, 38, 38, 0.45);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-modern-grievance:hover {
            background: linear-gradient(135deg, #991b1b 0%, #b91c1c 100%);
            transform: translateY(-2px);
            box-shadow: 0 16px 32px -4px rgba(220, 38, 38, 0.55);
            color: #ffffff;
        }
    </style>

    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5">
            <span class="badge px-3 py-2 rounded-pill mb-2" style="background: rgba(220, 38, 38, 0.12); color: #dc2626; border: 1px solid rgba(220, 38, 38, 0.25); font-size: 13px;">
                <i class="fa fa-comments-o me-1"></i> গ্রাহক সেবা ও স্বচ্ছতা
            </span>
            <h2 class="display-6 fw-bold text-dark mt-2" style="letter-spacing: -0.5px;">অভিযোগ ও পরামর্শ প্রতিকার সেল (Grievance Redressal)</h2>
            <p class="text-muted mx-auto fs-6" style="max-width: 680px;">
                আপনার যেকোনো সেবা সংক্রান্ত অভিযোগ, অনিয়ম বা প্রাতিষ্ঠানিক উন্নয়নের মূল্যবান পরামর্শ সরাসরি কর্তৃপক্ষের গোচরে আনুন
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Form Card -->
            <div class="col-lg-7">
                <div class="modern-form-card p-4 p-lg-5">
                    <h4 class="fw-bold text-dark mb-4 pb-2 border-bottom">
                        <i class="fa fa-pencil-square-o text-danger me-2"></i>অভিযোগ বা পরামর্শ জমা দিন
                    </h4>

                    <form action="{{ route('grievance.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="modern-label">আপনার পূর্ণ নাম <span class="required-star">*</span></label>
                                <div class="modern-input-group">
                                    <div class="modern-input-icon"><i class="fa fa-user"></i></div>
                                    <input type="text" name="name" class="modern-input-field" value="{{ Auth::user()->name ?? old('name') }}" placeholder="পূর্ণ নাম লিখুন" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="modern-label">মোবাইল নম্বর <span class="required-star">*</span></label>
                                <div class="modern-input-group">
                                    <div class="modern-input-icon"><i class="fa fa-phone"></i></div>
                                    <input type="text" name="phone" class="modern-input-field" value="{{ Auth::user()->phone ?? old('phone') }}" placeholder="017XXXXXXXX" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="modern-label">ইমেইল ঠিকানা</label>
                                <div class="modern-input-group">
                                    <div class="modern-input-icon"><i class="fa fa-envelope"></i></div>
                                    <input type="email" name="email" class="modern-input-field" value="{{ Auth::user()->email ?? old('email') }}" placeholder="yourname@example.com">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="modern-label">আবেদনের ধরন <span class="required-star">*</span></label>
                                <div class="modern-select-wrap">
                                    <div class="modern-input-icon"><i class="fa fa-tag"></i></div>
                                    <select name="type" class="modern-select-field" required>
                                        <option value="complaint">অভিযোগ (Complaint)</option>
                                        <option value="suggestion">পরামর্শ (Suggestion)</option>
                                        <option value="inquiry">তথ্য অনুসন্ধান (Inquiry)</option>
                                        <option value="feedback">ফিডব্যাক (Feedback)</option>
                                    </select>
                                    <div class="modern-select-arrow"><i class="fa fa-chevron-down"></i></div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="modern-label">সেবা ক্ষেত্র / বিভাগ <span class="required-star">*</span></label>
                                <div class="modern-select-wrap">
                                    <div class="modern-input-icon"><i class="fa fa-list"></i></div>
                                    <select name="category" class="modern-select-field" required>
                                        <option value="service">সাধারণ সেবা ও ডেলিভারি</option>
                                        <option value="payment">পেমেন্ট ও ফান্ড উত্তোলন</option>
                                        <option value="investment">বিনিয়োগ ও প্রকল্প সংক্রান্ত</option>
                                        <option value="card">মেম্বারশিপ কার্ড ও প্রোফাইল</option>
                                        <option value="staff">কর্মকর্তা-কর্মচারীর আচরণ</option>
                                        <option value="general">অন্যান্য</option>
                                    </select>
                                    <div class="modern-select-arrow"><i class="fa fa-chevron-down"></i></div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="modern-label">বিষয় (Subject) <span class="required-star">*</span></label>
                                <div class="modern-input-group">
                                    <div class="modern-input-icon"><i class="fa fa-bookmark"></i></div>
                                    <input type="text" name="subject" class="modern-input-field" placeholder="অভিযোগ বা পরামর্শের সংক্ষিপ্ত শিরোনাম" required>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="modern-label">বিস্তারিত বিবরণ (Description) <span class="required-star">*</span></label>
                                <div class="modern-textarea-wrap">
                                    <textarea name="description" class="modern-textarea-field" rows="4" placeholder="ঘটনার তারিখ, স্থান, সংশ্লিষ্ট ব্যক্তি এবং বিস্তারিত তথ্য স্পষ্টভাবে লিখুন..." required></textarea>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="modern-label">সংযুক্তি ফাইল / ছবি (ঐচ্ছিক)</label>
                                <div class="modern-input-group p-2">
                                    <input type="file" name="attachment" class="form-control-file border-0" accept="image/*,application/pdf">
                                </div>
                            </div>
                            <div class="col-12 mt-4 pt-2">
                                <button type="submit" class="btn-modern-grievance">
                                    <span>জমা দিন ও ট্র্যাকিং কোড গ্রহণ করুন</span>
                                    <i class="fa fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tracking Card & Assistance -->
            <div class="col-lg-5">
                <div class="modern-form-card p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa fa-search text-primary me-2"></i>পূর্বের আবেদনের অগ্রগতি জানুন</h5>
                    <p class="text-muted small mb-3">
                        আপনার কাছে ট্র্যাকিং কোড থাকলে নিচের বক্সে লিখে বর্তমান অগ্রগতি ও প্রশাসনিক নিষ্পত্তি জেনে নিন।
                    </p>
                    <form action="{{ route('grievance.track') }}" method="GET">
                        <div class="modern-input-wrap mb-3">
                            <div class="modern-icon"><i class="fa fa-tag"></i></div>
                            <input type="text" name="code" class="modern-input" placeholder="GRV-XXXXXX-XXX" required>
                        </div>
                        <button class="btn btn-primary w-100 py-2 rounded-3 fw-bold" type="submit">
                            <i class="fa fa-search me-1"></i> ট্র্যাক করুন
                        </button>
                    </form>
                </div>

                <div class="modern-form-card p-4 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border: none;">
                    <h6 class="fw-bold text-warning mb-2"><i class="fa fa-shield me-2"></i>তথ্যের শতভাগ গোপনীয়তা</h6>
                    <p class="small text-light opacity-90 mb-0" style="line-height: 1.7;">
                        আপনার প্রেরিত অভিযোগ ও পরিচয় অত্যন্ত সতর্কতার সাথে সুরক্ষিত রাখা হবে। নিরপেক্ষ তদন্ত ও দ্রুততম সময়ে সমাধান প্রদান করাই আমাদের অঙ্গীকার।
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@if(Auth::check())
        </section>
    </div>
</div>
@endif
@endsection
