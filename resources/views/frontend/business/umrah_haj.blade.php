@php
    $siteLang = request()->cookie('ekrishi_lang', 'bn');
    $isBn = ($siteLang === 'bn');
@endphp

@extends('layouts.frontend.app')

@section('content')
<div class="umrah-program-page" style="background: #f8fafc; min-height: 85vh;">

    <!-- Islamic Dark Green Hero Banner -->
    <div class="py-5" style="background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #047857 100%); color: #ffffff;">
        <div class="container text-center py-3">
            <span class="badge px-3 py-1 rounded-pill mb-2 fw-bold" style="background: rgba(255, 255, 255, 0.15); color: #fef08a; font-size: 13px;">
                <i class="fa fa-moon-o me-1"></i> আল্লাহর ঘরে যাওয়ার সুবর্ণ সুযোগ
            </span>
            <h1 class="fw-bold mb-2 text-white" style="font-size: clamp(26px, 4vw, 40px);">
                ফ্রি উমরাহ/হজ নিবন্ধন
            </h1>
            <p class="text-white-50 mx-auto mb-0" style="max-width: 700px; font-size: 15px;">
                যোগ্য ও নির্বাচিত ব্যক্তিদের সম্পূর্ণ বিনামূল্যে উমরাহ/হজ পালনের সুযোগ প্রদান করা হবে। নিচের তথ্যাদি নির্ভুলভাবে পূরণ করুন।
            </p>
        </div>
    </div>

    <div class="container py-5">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4 p-4" style="background: #ecfdf5; border-left: 5px solid #10b981 !important;">
                <div class="d-flex align-items-center">
                    <span class="avatar avatar-md rounded-circle text-white me-3 d-flex align-items-center justify-content-center" style="background: #10b981; width: 44px; height: 44px; min-width: 44px;">
                        <i class="fa fa-check fs-5"></i>
                    </span>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">নিবন্ধন সফলভাবে সম্পন্ন হয়েছে!</h5>
                        <p class="mb-0 text-muted">{{ session('success') }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('business.umrah_haj.apply') }}" method="POST">
            @csrf

            <div class="row g-4">
                <!-- Card 1: ব্যক্তিগত তথ্য -->
                <div class="col-12 col-lg-6">
                    <div class="card border rounded-4 bg-white shadow-sm h-100 overflow-hidden" style="border-color: #e2e8f0 !important;">
                        <div class="p-3 border-bottom d-flex align-items-center gap-2" style="background: #f0fdf4;">
                            <span class="badge rounded-circle text-white d-flex align-items-center justify-content-center" style="background: #047857; width: 28px; height: 28px;">
                                <i class="fa fa-user font-size-12"></i>
                            </span>
                            <h5 class="fw-bold text-dark mb-0 font-size-16">১. ব্যক্তিগত তথ্য</h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Name -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">পূর্ণ নাম (পাসপোর্ট অনুযায়ী) <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control rounded-3" value="{{ old('name') }}" placeholder="যেমন: MD. RAHIM UDDIN" required style="border-color: #cbd5e1;">
                            </div>

                            <!-- Phone & Whatsapp -->
                            <div class="row g-2 mb-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-bold text-dark">মোবাইল নম্বর <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" class="form-control rounded-3" value="{{ old('phone') }}" placeholder="+880 1XXXXXXXXX" required style="border-color: #cbd5e1;">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-bold text-dark">WhatsApp নম্বর</label>
                                    <input type="tel" name="whatsapp_number" class="form-control rounded-3" value="{{ old('whatsapp_number') }}" placeholder="+880 1XXXXXXXXX" style="border-color: #cbd5e1;">
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">ইমেইল ঠিকানা (ঐচ্ছিক)</label>
                                <input type="email" name="email" class="form-control rounded-3" value="{{ old('email') }}" placeholder="example@gmail.com" style="border-color: #cbd5e1;">
                            </div>

                            <!-- Address -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">আপনার স্থায়ী ও বর্তমান ঠিকানা <span class="text-danger">*</span></label>
                                <textarea name="address" rows="2" class="form-control rounded-3" placeholder="গ্রাম/বাসা, ডাকঘর, থানা, জেলা" required style="border-color: #cbd5e1;">{{ old('address') }}</textarea>
                            </div>

                            <!-- Profession -->
                            <div class="mb-0">
                                <label class="form-label small fw-bold text-dark">বর্তমান পেশা <span class="text-danger">*</span></label>
                                <input type="text" name="profession" class="form-control rounded-3" value="{{ old('profession') }}" placeholder="যেমন: কৃষক, ইমাম, শিক্ষক, চাকুরীজীবী, ইত্যাদি" required style="border-color: #cbd5e1;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: আবেদনকারীর তথ্য -->
                <div class="col-12 col-lg-6">
                    <div class="card border rounded-4 bg-white shadow-sm h-100 overflow-hidden" style="border-color: #e2e8f0 !important;">
                        <div class="p-3 border-bottom d-flex align-items-center gap-2" style="background: #f0fdf4;">
                            <span class="badge rounded-circle text-white d-flex align-items-center justify-content-center" style="background: #047857; width: 28px; height: 28px;">
                                <i class="fa fa-info-circle font-size-12"></i>
                            </span>
                            <h5 class="fw-bold text-dark mb-0 font-size-16">২. আবেদনকারীর তথ্য</h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Age & Marital Status -->
                            <div class="row g-2 mb-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-bold text-dark">বয়স <span class="text-danger">*</span></label>
                                    <input type="text" name="age" class="form-control rounded-3" value="{{ old('age') }}" placeholder="যেমন: ৩৫ বছর" required style="border-color: #cbd5e1;">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-bold text-dark d-block">বৈবাহিক অবস্থা</label>
                                    <div class="d-flex gap-2 pt-1">
                                        <input type="radio" class="btn-check" name="marital_status" id="marital_unmarried" value="অবিবাহিত">
                                        <label class="btn btn-sm btn-outline-secondary rounded-pill px-3" for="marital_unmarried">অবিবাহিত</label>

                                        <input type="radio" class="btn-check" name="marital_status" id="marital_married" value="বিবাহিত" checked>
                                        <label class="btn btn-sm btn-outline-secondary rounded-pill px-3" for="marital_married">বিবাহিত</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Gender -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark d-block">লিঙ্গ</label>
                                <div class="d-flex gap-2">
                                    <input type="radio" class="btn-check" name="gender" id="gender_male" value="পুরুষ" checked>
                                    <label class="btn btn-sm btn-outline-secondary rounded-pill px-3" for="gender_male">পুরুষ</label>

                                    <input type="radio" class="btn-check" name="gender" id="gender_female" value="মহিলা">
                                    <label class="btn btn-sm btn-outline-secondary rounded-pill px-3" for="gender_female">মহিলা</label>
                                </div>
                            </div>

                            <!-- Previous Pilgrimage -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark d-block">পূর্বে উমরাহ বা হজ করেছেন? <span class="text-danger">*</span></label>
                                <div class="d-flex flex-wrap gap-2">
                                    <input type="radio" class="btn-check" name="previous_pilgrimage" id="pilg_first" value="না, প্রথমবার" checked>
                                    <label class="btn btn-sm btn-outline-secondary rounded-pill px-3" for="pilg_first">না, প্রথমবার</label>

                                    <input type="radio" class="btn-check" name="previous_pilgrimage" id="pilg_umrah" value="উমরাহ করেছি">
                                    <label class="btn btn-sm btn-outline-secondary rounded-pill px-3" for="pilg_umrah">উমরাহ করেছি</label>

                                    <input type="radio" class="btn-check" name="previous_pilgrimage" id="pilg_haj" value="হজ করেছি">
                                    <label class="btn btn-sm btn-outline-secondary rounded-pill px-3" for="pilg_haj">হজ করেছি</label>
                                </div>
                            </div>

                            <!-- Passport status -->
                            <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                <label class="form-label small fw-bold text-dark d-block mb-1">
                                    <i class="fa fa-id-card-o text-primary me-1"></i> আপনার বৈধ পাসপোর্ট আছে কি? <span class="text-danger">*</span>
                                </label>
                                <div class="d-flex gap-2">
                                    <input type="radio" class="btn-check" name="has_passport" id="pass_yes" value="হ্যাঁ, আছে" checked>
                                    <label class="btn btn-sm btn-outline-success rounded-pill px-3" for="pass_yes">হ্যাঁ, আছে</label>

                                    <input type="radio" class="btn-check" name="has_passport" id="pass_no" value="না, নেই">
                                    <label class="btn btn-sm btn-outline-secondary rounded-pill px-3" for="pass_no">না, নেই</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: নির্বাচন সংক্রান্ত তথ্য -->
                <div class="col-12">
                    <div class="card border rounded-4 bg-white shadow-sm overflow-hidden" style="border-color: #e2e8f0 !important;">
                        <div class="p-3 border-bottom d-flex align-items-center gap-2" style="background: #f0fdf4;">
                            <span class="badge rounded-circle text-white d-flex align-items-center justify-content-center" style="background: #047857; width: 28px; height: 28px;">
                                <i class="fa fa-file-text-o font-size-12"></i>
                            </span>
                            <h5 class="fw-bold text-dark mb-0 font-size-16">৩. নির্বাচন ও মূল্যায়ন সংক্রান্ত তথ্য</h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Reason -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">
                                    কেন আপনি ফ্রি উমরাহ/হজের জন্য নির্বাচিত হতে চান? <span class="text-danger">*</span>
                                </label>
                                <textarea name="selection_reason" rows="3" class="form-control rounded-3" placeholder="আপনার মনের আকাঙ্খা ও কারণ বিস্তারিত লিখুন... (কমপক্ষে ৩০ অক্ষর)" required style="border-color: #cbd5e1;">{{ old('selection_reason') }}</textarea>
                            </div>

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-bold text-dark d-block">আর্থিকভাবে নিজ খরচে উমরাহ/হজে যেতে সক্ষম কি না? <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-2">
                                        <input type="radio" class="btn-check" name="is_financially_capable" id="capable_no" value="না, অক্ষম" checked>
                                        <label class="btn btn-sm btn-outline-danger rounded-pill px-3" for="capable_no">না (অক্ষম)</label>

                                        <input type="radio" class="btn-check" name="is_financially_capable" id="capable_yes" value="হ্যাঁ, কিছুটা সক্ষম">
                                        <label class="btn btn-sm btn-outline-secondary rounded-pill px-3" for="capable_yes">হ্যাঁ (কিছুটা সক্ষম)</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-bold text-dark">কীভাবে জানলেন?</label>
                                    <select name="source_info" class="form-select rounded-3" style="border-color: #cbd5e1;">
                                        <option value="ফেসবুক / সোশ্যাল মিডিয়া">ফেসবুক / সোশ্যাল মিডিয়া</option>
                                        <option value="ওয়েবসাইট">ওয়েবসাইট</option>
                                        <option value="পরিচিতজন / বন্ধুবান্ধব">পরিচিতজন / বন্ধুবান্ধব</option>
                                        <option value="আউটলেট বা ব্রাঞ্চ">আউটলেট বা ব্রাঞ্চ</option>
                                        <option value="অন্যান্য">অন্যান্য</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4: সম্মতি ও জমা -->
                <div class="col-12">
                    <div class="card border rounded-4 bg-white shadow-sm p-4 text-center" style="border-color: #cbd5e1 !important;">
                        <div class="d-flex flex-column align-items-center gap-2 mb-3">
                            <div class="form-check text-start">
                                <input class="form-check-input" type="checkbox" id="agree_truth" required checked>
                                <label class="form-check-label small fw-bold text-dark" for="agree_truth">
                                    আমি প্রদত্ত সকল তথ্য সত্য ও সঠিক বলে ঘোষণা করছি।
                                </label>
                            </div>
                            <div class="form-check text-start">
                                <input class="form-check-input" type="checkbox" id="agree_decision" required checked>
                                <label class="form-check-label small fw-bold text-dark" for="agree_decision">
                                    নির্বাচন প্রক্রিয়ার চূড়ান্ত সিদ্ধান্ত সম্পূর্ণ আয়োজক কর্তৃপক্ষের ওপর নির্ভর করবে।
                                </label>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="btn text-white rounded-pill px-5 py-3 fw-bold shadow-sm" style="background: #047857; font-size: 16px; min-width: 250px;">
                                আবেদন জমা দিন <i class="fa fa-paper-plane ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection
