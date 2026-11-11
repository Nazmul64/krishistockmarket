@extends('layouts.frontend.app')

@section('content')
<div class="main-container">

    <!-- Modern Brand Page Banner -->
    <div class="page-modern-hero-banner text-center py-5" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <div class="container">
            <span class="section-pill-badge mb-2" style="background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);">
                <i class="fa fa-briefcase me-1"></i> ক্যারিয়ার গড়ুন আমাদের সাথে
            </span>
            <h1 class="hero-page-title text-white">ক্যারিয়ার পাথ (Career Path)</h1>
            <p class="hero-page-desc text-slate-300">ই-কৃষি সেবা পরিবারের সাথে আপনার মেধা ও পরিশ্রম যুক্ত করে সমৃদ্ধ ক্যারিয়ার গড়ে তুলুন</p>
        </div>
    </div>

    <main class="site-main py-5 bg-slate-50">
        <div class="container">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 p-4 mb-4 shadow-sm" role="alert" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46;">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-check-circle fa-2x me-3 text-success"></i>
                        <div>
                            <h5 class="fw-bold mb-1">আবেদন সফল হয়েছে!</h5>
                            <p class="mb-0 fs-6">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-4 p-4 mb-4 shadow-sm" role="alert">
                    <h6 class="fw-bold mb-2"><i class="fa fa-exclamation-triangle me-2"></i>কিছু তথ্যে ত্রুটি রয়েছে:</h6>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4">

                <!-- Left Column: Career Guidelines & Benefits -->
                <div class="col-lg-4 col-md-5 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white border h-100">
                        <div class="card-body p-4 d-flex flex-column justify-content-between">
                            <div>
                                <h4 class="fw-bold mb-4 text-slate-800">
                                    <i class="fa fa-star text-warning me-2"></i> কেন আমাদের সাথে কাজ করবেন?
                                </h4>

                                <div class="d-flex align-items-start mb-4">
                                    <div class="rounded-circle text-success p-3 me-3 text-center d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; min-width: 44px; background: #ecfdf5;">
                                        <i class="fa fa-leaf fs-5 text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1 text-slate-700">ডিজিটাল এগ্রো বিপ্লব</h6>
                                        <p class="text-muted mb-0" style="font-size: 13.5px;">
                                            বাংলাদেশের কৃষিখাতে আধুনিক তথ্যপ্রযুক্তি ও ডিজিটাল সাপ্লাই চেইনের অগ্রণী প্লাটফর্মে কাজ করার অনন্য অভিজ্ঞতা।
                                        </p>
                                    </div>
                                </div>

                                <div class="d-flex align-items-start mb-4">
                                    <div class="rounded-circle text-primary p-3 me-3 text-center d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; min-width: 44px; background: #eff6ff;">
                                        <i class="fa fa-line-chart fs-5 text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1 text-slate-700">উজ্জ্বল ক্যারিয়ার বৃদ্ধি</h6>
                                        <p class="text-muted mb-0" style="font-size: 13.5px;">
                                            যোগ্যতা ও পারফরম্যান্স অনুযায়ী দ্রুত পদোন্নতি, নিয়মিত প্রশিক্ষণ এবং ইনসেন্টিভ সুবিধা।
                                        </p>
                                    </div>
                                </div>

                                <div class="d-flex align-items-start mb-4">
                                    <div class="rounded-circle text-warning p-3 me-3 text-center d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; min-width: 44px; background: #fffbeb;">
                                        <i class="fa fa-users fs-5 text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1 text-slate-700">সহযোগিতাপূর্ণ পরিবেশ</h6>
                                        <p class="text-muted mb-0" style="font-size: 13.5px;">
                                            সৌহার্দ্যপূর্ণ প্রাতিষ্ঠানিক সংস্কৃতি যেখানে প্রতিটি কর্মীর মতামতকে গুরুত্ব দেওয়া হয়।
                                        </p>
                                    </div>
                                </div>

                                <div class="p-3 rounded-3 bg-light border mt-4">
                                    <h6 class="fw-bold text-dark mb-2"><i class="fa fa-info-circle text-info me-1"></i> আবেদনের নিয়মাবলী:</h6>
                                    <ul class="text-muted mb-0 ps-3" style="font-size: 13px; line-height: 1.6;">
                                        <li>আপনার হালনাগাদ <strong>সিভি / রেজুমে শুধুমাত্র PDF ফরম্যাটে</strong> আপলোড করুন (সর্বোচ্চ ১০MB)।</li>
                                        <li>সঠিক ও সচল মোবাইল নম্বর এবং পূর্ণ ঠিকানা লিখুন।</li>
                                        <li>কভার লেটার ঐচ্ছিক (Optional), তবে নিজের যোগ্যতা তুলে ধরলে অগ্রাধিকার দেওয়া হবে।</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top text-center text-muted" style="font-size: 13px;">
                                যেকোনো নিয়োগ জিজ্ঞাসার জন্য যোগাযোগ করুন:<br>
                                <strong class="text-dark">{{ setting('phone1') }}</strong> | <span class="text-primary">{{ setting('email1') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Career Application Form -->
                <div class="col-lg-8 col-md-7">
                    <div class="card border-0 shadow-sm rounded-4 bg-white border">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2 border-bottom pb-3">
                                <div>
                                    <h3 class="fw-bold text-slate-800 mb-1">
                                        <i class="fa fa-id-card-o text-success me-2"></i> অনলাইনে ক্যারিয়ার আবেদন
                                    </h3>
                                    <p class="text-muted mb-0">আপনার সঠিক তথ্য প্রদান করে নিচে সিভি (PDF) সংযুক্ত করুন</p>
                                </div>
                                <span class="badge bg-success px-3 py-2 fs-7">
                                    <i class="fa fa-circle text-white me-1"></i> রিক্রুটমেন্ট চলছে
                                </span>
                            </div>

                            <form action="{{ route('career.apply') }}" method="POST" enctype="multipart/form-data" id="careerForm">
                                @csrf

                                <div class="row g-3">
                                    <!-- First Name -->
                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label fw-bold text-slate-700">
                                            ফার্স্ট নেম (First Name) <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class="fa fa-user text-muted"></i></span>
                                            <input type="text" class="form-control border-start-0 @error('first_name') is-invalid @enderror" 
                                                   id="first_name" name="first_name" value="{{ old('first_name') }}" 
                                                   placeholder="উদাঃ মোঃ নাজমুল" required>
                                        </div>
                                        @error('first_name')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Last Name -->
                                    <div class="col-md-6">
                                        <label for="last_name" class="form-label fw-bold text-slate-700">
                                            লাস্ট নেম (Last Name) <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class="fa fa-user-o text-muted"></i></span>
                                            <input type="text" class="form-control border-start-0 @error('last_name') is-invalid @enderror" 
                                                   id="last_name" name="last_name" value="{{ old('last_name') }}" 
                                                   placeholder="উদাঃ ইসলাম" required>
                                        </div>
                                        @error('last_name')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Phone Number -->
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label fw-bold text-slate-700">
                                            মোবাইল নম্বর (Phone) <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class="fa fa-phone text-muted"></i></span>
                                            <input type="tel" class="form-control border-start-0 @error('phone') is-invalid @enderror" 
                                                   id="phone" name="phone" value="{{ old('phone') }}" 
                                                   placeholder="উদাঃ 017XXXXXXXX" required>
                                        </div>
                                        @error('phone')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6">
                                        <label for="email" class="form-label fw-bold text-slate-700">
                                            ইমেইল ঠিকানা (Email) <small class="text-muted">(ঐচ্ছিক)</small>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class="fa fa-envelope text-muted"></i></span>
                                            <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                                   id="email" name="email" value="{{ old('email') }}" 
                                                   placeholder="উদাঃ yourname@example.com">
                                        </div>
                                        @error('email')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Position Applied -->
                                    <div class="col-12">
                                        <label for="position_applied" class="form-label fw-bold text-slate-700">
                                            আবেদিত পদবী / পছন্দের বিভাগ (Position Applied)
                                        </label>
                                        <select class="form-select @error('position_applied') is-invalid @enderror" id="position_applied" name="position_applied">
                                            <option value="General Career Application">সাধারণ ক্যারিয়ার আবেদন (Open / General)</option>
                                            <option value="Field Agro Officer">ফিল্ড এগ্রো অফিসার (Field Agro Officer)</option>
                                            <option value="Marketing & Distribution Executive">মার্কেটিং & ডিস্ট্রিবিউশন এক্সিকিউটিভ</option>
                                            <option value="Customer Support Representative">কাস্টমার সাপোর্ট রিপ্রেজেনটেটিভ</option>
                                            <option value="Branch Coordinator">শাখা পরিচালক / ব্রাঞ্চ কো-অর্ডিনেটর</option>
                                            <option value="IT & Digital Operations">আইটি & ডিজিটাল অপারেশনস</option>
                                            <option value="Accounting & Audit">অ্যাকাউন্টিং & অডিট</option>
                                        </select>
                                    </div>

                                    <!-- Full Address -->
                                    <div class="col-12">
                                        <label for="address" class="form-label fw-bold text-slate-700">
                                            বর্তমান ঠিকানা (Full Address) <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class="fa fa-map-marker text-muted"></i></span>
                                            <input type="text" class="form-control border-start-0 @error('address') is-invalid @enderror" 
                                                   id="address" name="address" value="{{ old('address') }}" 
                                                   placeholder="বাড়ি নং, রোড, এলাকা/গ্রাম, থানা, জেলা" required>
                                        </div>
                                        @error('address')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Resume PDF Upload -->
                                    <div class="col-12">
                                        <label for="resume" class="form-label fw-bold text-slate-700">
                                            সিভি / রেজুমে আপলোড (Resume PDF) <span class="text-danger">*</span>
                                        </label>
                                        <div class="p-3 border-2 border-dashed rounded-3 bg-light text-center" style="border: 2px dashed #cbd5e1;">
                                            <i class="fa fa-cloud-upload fa-2x text-primary mb-2"></i>
                                            <div class="fw-bold text-dark fs-6 mb-1">আপনার সিভি (PDF ফাইল) নির্বাচন করুন</div>
                                            <p class="text-muted small mb-2">শুধুমাত্র PDF ফাইল গ্রহণযোগ্য (সর্বোচ্চ ১০MB)</p>
                                            <input type="file" class="form-control @error('resume') is-invalid @enderror" 
                                                   id="resume" name="resume" accept="application/pdf" required style="max-width: 380px; margin: 0 auto;">
                                        </div>
                                        @error('resume')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Cover Letter (Optional) -->
                                    <div class="col-12">
                                        <label for="cover_letter" class="form-label fw-bold text-slate-700 d-flex justify-content-between align-items-center">
                                            <span>কভার লেটার (Cover Letter)</span>
                                            <small class="text-muted fw-normal">(ঐচ্ছিক - চাইলে দিতে পারবেন)</small>
                                        </label>
                                        <textarea class="form-control @error('cover_letter') is-invalid @enderror" 
                                                  id="cover_letter" name="cover_letter" rows="4" 
                                                  placeholder="নিজের অভিজ্ঞতা, দক্ষতা এবং কেন আপনি এই প্ল্যাটফর্মে কাজ করতে আগ্রহী তা সংক্ষেপে লিখুন...">{{ old('cover_letter') }}</textarea>
                                        @error('cover_letter')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-success btn-lg w-100 py-3 rounded-3 shadow-sm fw-bold">
                                            <i class="fa fa-paper-plane me-2"></i> আবেদন জমা দিন (Submit Application)
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

</div>
@endsection

