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
            background: linear-gradient(90deg, #10b981 0%, #059669 50%, #047857 100%);
        }
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
        .form-section-amber {
            color: #b45309;
            background: #fef3c7;
            border: 1px solid rgba(245, 158, 11, 0.3);
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
        }
        .modern-textarea-wrap {
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            background: #f8fafc;
            transition: all 0.25s ease;
            overflow: hidden;
            padding: 10px 14px;
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
            min-height: 70px;
            border: none;
            outline: none;
            background: transparent;
            font-size: 14px;
            color: #0f172a;
            line-height: 1.5;
            resize: vertical;
            font-family: inherit;
        }
        .tier-select-card {
            border: 2px solid #e2e8f0 !important;
            border-radius: 14px;
            transition: all 0.25s ease;
        }
        .tier-select-card:hover {
            border-color: #10b981 !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.06);
        }
        .tier-select-card.active {
            border-color: #10b981 !important;
            background: #f0fdf4 !important;
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.15);
        }
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
    </style>

    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5">
            <span class="badge px-3 py-2 rounded-pill mb-2" style="background: rgba(16, 185, 129, 0.15); color: #059669; border: 1px solid rgba(16, 185, 129, 0.3); font-size: 13px;">
                <i class="fa fa-id-card-o me-1"></i> অনলাইন মেম্বারশিপ পোর্টাল
            </span>
            <h2 class="display-6 fw-bold text-dark mt-2" style="letter-spacing: -0.5px;">মেম্বারশিপ কার্ড ও অ্যাকাউন্ট আবেদন</h2>
            <p class="text-muted mx-auto fs-6" style="max-width: 680px;">
                আপনার পছন্দের মেম্বারশিপ কার্ড নির্বাচন করুন এবং প্রয়োজনীয় তথ্য ও পেমেন্ট ট্রানজাকশন আইডি দিয়ে তাৎক্ষণিক আবেদন সম্পন্ন করুন
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Left: Card Selection & Benefit Preview -->
            <div class="col-lg-5">
                <div class="modern-form-card p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa fa-check-square-o text-success me-2"></i>মেম্বারশিপ প্যাকেজ নির্বাচন করুন</h5>
                    
                    <div class="d-flex flex-column gap-3 mb-4" id="cardTierSelector">
                        @foreach($cardBenefits as $cb)
                        <div class="card p-3 cursor-pointer tier-select-card {{ ($type ?? 'sme') === $cb->card_type ? 'active' : '' }}"
                             onclick="selectCardTier('{{ $cb->card_type }}', '{{ $cb->card_name }}', '{{ $cb->card_fee }}', this)"
                             style="cursor: pointer;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-dark text-white font-size-11 mb-1 px-2 py-1 rounded-pill">{{ $cb->badge_text }}</span>
                                    <h6 class="fw-bold text-dark mb-1">{{ $cb->card_name }}</h6>
                                    <span class="text-success fw-bold font-size-13">{{ $cb->card_fee }}</span>
                                </div>
                                <span class="badge rounded-circle p-2 {{ ($type ?? 'sme') === $cb->card_type ? 'bg-success text-white' : 'bg-light text-muted' }}" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fa fa-check"></i>
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Payment Instruction Box -->
                    <div class="p-3 rounded-4" style="background: #f8fafc; border: 1px solid #e2e8f0; font-size: 13px;">
                        <strong class="text-dark d-block mb-1"><i class="fa fa-info-circle text-primary me-1"></i> ফি পরিশোধের নির্দেশনা:</strong>
                        <p class="text-muted mb-2">
                            নির্ধারিত মেম্বারশিপ ফি আমাদের অফিসিয়াল <strong>বিকাশ/নগদ/রকেট</strong> মার্চেন্ট অথবা ব্যাংক একাউন্টে জমা দিয়ে ট্রানজাকশন আইডিটি (TrxID) ফরমে পূরণ করুন।
                        </p>
                        <div class="p-2 bg-white rounded-3 border small">
                            <div><strong>বিকাশ (মার্চেন্ট):</strong> ০১৭০০-০০০০০০ (পেমেন্ট)</div>
                            <div><strong>নগদ (মার্চেন্ট):</strong> ০১৭০০-০০০০০১ (পেমেন্ট)</div>
                            <div><strong>ব্যাংক:</strong> ইসলামী ব্যাংক বাংলাদেশ লি:, একাউন্ট: ২০৫০...</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Application Form -->
            <div class="col-lg-7">
                <div class="modern-form-card p-4 p-lg-5">
                    <h4 class="fw-bold text-dark mb-4 pb-2 border-bottom" id="formHeaderTitle">
                        <i class="fa fa-pencil-square-o text-success me-2"></i>আবেদন ফরম (Application Form)
                    </h4>

                    @if(session('error'))
                        <div class="alert alert-danger rounded-4">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('apply.card.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="card_type" id="hiddenCardType" value="{{ $type ?? 'sme' }}">

                        <!-- Section 1: Personal Info -->
                        <div class="mb-4">
                            <div class="form-section-title form-section-emerald">
                                <i class="fa fa-user"></i> ১. ব্যক্তিগত তথ্য (Personal Details)
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="modern-label">পূর্ণ নাম (Full Name) <span class="required-star">*</span></label>
                                    <div class="modern-input-group">
                                        <div class="modern-input-icon"><i class="fa fa-user"></i></div>
                                        <input type="text" name="name" class="modern-input-field" value="{{ Auth::user()->name ?? old('name') }}" placeholder="উদাঃ মোঃ নাজমুল ইসলাম" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="modern-label">মোবাইল নম্বর (Phone) <span class="required-star">*</span></label>
                                    <div class="modern-input-group">
                                        <div class="modern-input-icon"><i class="fa fa-phone"></i></div>
                                        <input type="text" name="phone" class="modern-input-field" value="{{ Auth::user()->phone ?? old('phone') }}" placeholder="উদাঃ 017XXXXXXXX" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="modern-label">ইমেইল ঠিকানা (Email)</label>
                                    <div class="modern-input-group">
                                        <div class="modern-input-icon"><i class="fa fa-envelope"></i></div>
                                        <input type="email" name="email" class="modern-input-field" value="{{ Auth::user()->email ?? old('email') }}" placeholder="উদাঃ name@example.com">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="modern-label">জাতীয় পরিচয়পত্র নম্বর (NID)</label>
                                    <div class="modern-input-group">
                                        <div class="modern-input-icon"><i class="fa fa-id-card"></i></div>
                                        <input type="text" name="nid_number" class="modern-input-field" value="{{ Auth::user()->nid_number ?? old('nid_number') }}" placeholder="১০ বা ১৭ ডিজিটের এনআইডি">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="modern-label">বর্তমান ঠিকানা (Present Address) <span class="required-star">*</span></label>
                                    <div class="modern-textarea-wrap">
                                        <textarea name="present_address" class="modern-textarea-field" rows="2" placeholder="বাড়ি নং, রোড, এলাকা/গ্রাম, থানা, জেলা" required>{{ Auth::user()->present_address ?? old('present_address') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="modern-label">স্থায়ী ঠিকানা (Permanent Address)</label>
                                    <div class="modern-textarea-wrap">
                                        <textarea name="permanent_address" class="modern-textarea-field" rows="2" placeholder="স্থায়ী ঠিকানার বিবরণ...">{{ Auth::user()->permanent_address ?? old('permanent_address') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Nominee Info -->
                        <div class="mb-4">
                            <div class="form-section-title form-section-blue">
                                <i class="fa fa-users"></i> ২. নমিনির তথ্য (Nominee Details)
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="modern-label">নমিনির পূর্ণ নাম</label>
                                    <div class="modern-input-group">
                                        <div class="modern-input-icon"><i class="fa fa-user-plus"></i></div>
                                        <input type="text" name="nominee_name" class="modern-input-field" value="{{ Auth::user()->nominee_name ?? old('nominee_name') }}" placeholder="নমিনির নাম">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="modern-label">নমিনির মোবাইল নম্বর</label>
                                    <div class="modern-input-group">
                                        <div class="modern-input-icon"><i class="fa fa-phone"></i></div>
                                        <input type="text" name="nominee_phone" class="modern-input-field" value="{{ Auth::user()->nominee_phone ?? old('nominee_phone') }}" placeholder="01XXXXXXXXX">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="modern-label">সম্পর্ক (Relation)</label>
                                    <div class="modern-input-group">
                                        <div class="modern-input-icon"><i class="fa fa-heart"></i></div>
                                        <input type="text" name="nominee_relation" class="modern-input-field" value="{{ Auth::user()->nominee_relation ?? old('nominee_relation') }}" placeholder="স্ত্রী / সন্তান / ভাই">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Photo & Document Upload -->
                        <div class="mb-4">
                            <div class="form-section-title form-section-purple">
                                <i class="fa fa-cloud-upload"></i> ৩. ছবি ও নথিপত্র সংযুক্তি (Uploads)
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="modern-label">সাম্প্রতিক পাসপোর্ট সাইজ ছবি</label>
                                    <div class="modern-input-group p-2">
                                        <input type="file" name="photo" class="form-control-file border-0" accept="image/*">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="modern-label">এনআইডি বা পরিচয়পত্রের ছবি</label>
                                    <div class="modern-input-group p-2">
                                        <input type="file" name="nid_document" class="form-control-file border-0" accept="image/*,application/pdf">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Payment Gateway & TrxID -->
                        <div class="mb-4">
                            <div class="form-section-title form-section-amber">
                                <i class="fa fa-credit-card"></i> ৪. পেমেন্ট ভেরিফিকেশন (Payment Details)
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="modern-label">পেমেন্ট মেথড নির্বাচন করুন <span class="required-star">*</span></label>
                                    <div class="modern-select-wrap">
                                        <div class="modern-input-icon"><i class="fa fa-credit-card"></i></div>
                                        <select name="payment_method" class="modern-select-field" required>
                                            <option value="bkash">বিকাশ (bKash)</option>
                                            <option value="nagad">নগদ (Nagad)</option>
                                            <option value="rocket">রকেট (Rocket)</option>
                                            <option value="bank_transfer">ব্যাংক ট্রান্সফার (Bank)</option>
                                        </select>
                                        <div class="modern-select-arrow"><i class="fa fa-chevron-down"></i></div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="modern-label">পেমেন্ট ট্রানজাকশন আইডি (TrxID) <span class="required-star">*</span></label>
                                    <div class="modern-input-group">
                                        <div class="modern-input-icon"><i class="fa fa-tag"></i></div>
                                        <input type="text" name="transaction_id" class="modern-input-field" placeholder="উদাঃ TXN-89012345" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Terms & Submit -->
                        <div class="form-check mb-4 p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <input class="form-check-input ms-0 me-2" type="checkbox" id="termsAgree" required checked>
                            <label class="form-check-label small text-muted" for="termsAgree">
                                আমি প্রতিষ্ঠানের <a href="{{ route('terms') }}" target="_blank" class="fw-bold text-success">টার্মস এন্ড কন্ডিশন</a> এবং <a href="{{ route('corporate.investment_policy') }}" target="_blank" class="fw-bold text-success">বিনিয়োগ নীতিমালা</a> সজ্ঞানে পাঠ করেছি এবং পূর্ণ সম্মতি প্রদান করছি।
                            </label>
                        </div>

                        <button type="submit" class="btn-modern-submit">
                            <span>আবেদন নিশ্চিত ও জমা দিন</span>
                            <i class="fa fa-arrow-right"></i>
                        </button>
                    </form>
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

<script>
function selectCardTier(type, name, fee, element) {
    document.getElementById('hiddenCardType').value = type;
    document.querySelectorAll('.tier-select-card').forEach(function(el) {
        el.classList.remove('active');
        var chk = el.querySelector('.fa-check');
        if (chk && chk.parentElement) {
            chk.parentElement.classList.remove('bg-success', 'text-white');
            chk.parentElement.classList.add('bg-light', 'text-muted');
        }
    });
    element.classList.add('active');
    var badge = element.querySelector('.badge.rounded-circle');
    if (badge) {
        badge.classList.remove('bg-light', 'text-muted');
        badge.classList.add('bg-success', 'text-white');
    }
}
</script>
@endsection
