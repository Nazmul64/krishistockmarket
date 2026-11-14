@extends(Auth::check() ? 'layouts.backend.app' : 'layouts.frontend.app')

@section('content')
@if(Auth::check())
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">
@endif
<div class="corporate-page-wrap py-5 bg-light">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5">
            <span class="badge bg-primary text-white px-3 py-2 rounded-pill fw-bold font-size-14 mb-2">
                <i class="fa fa-qrcode me-1"></i> অনলাইন ভেরিফিকেশন সিস্টেম
            </span>
            <h2 class="fw-bold display-6 text-dark mt-2">ডিজিটাল মেম্বারশিপ কার্ড যাচাই (Verify Membership Card)</h2>
            <p class="text-muted max-w-700 mx-auto font-size-16">
                সদস্যের ডিজিটাল কার্ডের বৈধতা, স্ট্যাটাস, কার্ডের ধরন ও পরিচিতি যাচাই করতে নিচের বক্সে Customer ID বা ফোন নম্বর লিখুন।
            </p>
        </div>

        <!-- Search / Verification Form -->
        <div class="row justify-content-center mb-5">
            <div class="col-md-7 col-lg-6">
                <form action="{{ route('verify.card') }}" method="GET" class="card border-0 shadow-sm rounded-pill p-2 bg-white d-flex flex-row align-items-center">
                    <input type="text" name="code" class="form-control border-0 ps-4 bg-transparent" placeholder="Customer ID (যেমন: SME-2026-..., GLD-..., PRP-...)" value="{{ $code ?? '' }}" required>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="fa fa-search me-1"></i> যাচাই করুন
                    </button>
                </form>
            </div>
        </div>

        <!-- Verification Result -->
        @if(!empty($code))
            @if(isset($user) && $user)
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Success Verification Banner -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-success text-white text-center mb-4">
                        <div class="d-inline-block p-3 rounded-circle bg-white text-success mx-auto mb-2" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-check-circle fa-2x"></i>
                        </div>
                        <h4 class="fw-bold mb-1">যাচাইকরণ সফল: কার্ডটি বৈধ ও নিবন্ধিত!</h4>
                        <p class="mb-0 text-white opacity-90 small">Official Verified Member of E-Krishi Sheba System</p>
                    </div>

                    <!-- Digital Card Visual Representation (Flip / Luxury Design) -->
                    <div class="card border-0 shadow-lg rounded-4 p-4 p-lg-5 text-white mb-4" 
                         style="background: {{ match($user->membership_card_type) {
                             'gold' => 'linear-gradient(135deg, #78350f 0%, #b45309 50%, #d97706 100%)',
                             'property' => 'linear-gradient(135deg, #0f172a 0%, #1e3a8a 60%, #2563eb 100%)',
                             'shareholder' => 'linear-gradient(135deg, #3b0764 0%, #6b21a8 60%, #9333ea 100%)',
                             'partner' => 'linear-gradient(135deg, #064e3b 0%, #047857 60%, #059669 100%)',
                             default => 'linear-gradient(135deg, #064e3b 0%, #0d9488 100%)',
                         } }}; position: relative; overflow: hidden; border: 1px solid rgba(255,255,255,0.2) !important;">
                        
                        <!-- Watermark -->
                        <div class="position-absolute end-0 bottom-0 opacity-10 p-3" style="font-size: 140px;">
                            <i class="fa fa-id-card"></i>
                        </div>

                        <!-- Card Top Bar -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill text-uppercase">
                                    {{ strtoupper($user->membership_card_type ?: 'SME') }} MEMBERSHIP
                                </span>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold tracking-widest text-white font-size-16">e-krishisheba</span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="row align-items-center g-3 my-3">
                            <div class="col-auto">
                                <img src="{{ !empty($user->avatar) && file_exists(public_path($user->avatar)) ? asset($user->avatar) : asset('frontend/assets/images/team/member1.jpg') }}" 
                                     alt="{{ $user->name }}" 
                                     class="rounded-circle border border-3 border-warning shadow" 
                                     style="width: 85px; height: 85px; object-fit: cover;">
                            </div>
                            <div class="col">
                                <h4 class="fw-bold text-white mb-1">{{ $user->name }}</h4>
                                <div class="font-monospace text-warning fw-bold font-size-18 tracking-wide">
                                    {{ $user->customer_id ?: 'ID: ' . $user->username }}
                                </div>
                                <span class="text-light opacity-90 small">মোবাইল: {{ substr($user->phone, 0, 5) }}****{{ substr($user->phone, -2) }}</span>
                            </div>
                            <div class="col-auto text-center bg-white p-2 rounded-3 shadow-sm">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode(route('verify.card', ['card_number' => $user->customer_id ?: $user->username])) }}" alt="QR Code" style="width: 70px; height: 70px;">
                                <div class="text-dark font-size-10 fw-bold mt-1">VERIFIED</div>
                            </div>
                        </div>

                        <!-- Card Bottom Bar -->
                        <div class="d-flex justify-content-between align-items-end pt-3 border-top border-white-50 font-size-12">
                            <div>
                                <span class="text-light opacity-75 d-block">ইস্যুর তারিখ:</span>
                                <strong>{{ \Carbon\Carbon::parse($user->created_at)->format('d M, Y') }}</strong>
                            </div>
                            <div>
                                <span class="text-light opacity-75 d-block">স্ট্যাটাস:</span>
                                <span class="badge bg-success text-white">সক্রিয় সদস্য (Active)</span>
                            </div>
                            <div class="text-end">
                                <span class="text-light opacity-75 d-block">মেয়াদ:</span>
                                <strong>আজীবন / নবায়নযোগ্য</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Member Verified Details Table -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                        <h5 class="fw-bold text-dark mb-3"><i class="fa fa-info-circle text-primary me-2"></i> কার্ডের বিস্তারিত বিবরণী</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered small mb-0">
                                <tbody>
                                    <tr>
                                        <th class="bg-light" style="width: 35%;">সদস্যের নাম:</th>
                                        <td class="fw-bold text-dark">{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">ইউনিক Customer ID:</th>
                                        <td class="text-success fw-bold font-monospace">{{ $user->customer_id ?: $user->username }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">মেম্বারশিপ ক্যাটাগরি:</th>
                                        <td class="text-capitalize">{{ $user->membership_card_type ?: 'SME Card' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">অ্যাকাউন্টের ভূমিকা:</th>
                                        <td class="text-capitalize">{{ $user->role }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">নিবন্ধনের তারিখ:</th>
                                        <td>{{ \Carbon\Carbon::parse($user->created_at)->isoFormat('D MMMM, YYYY') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            @else
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-5 bg-white text-center">
                        <div class="d-inline-block p-3 rounded-circle bg-danger-subtle text-danger mx-auto mb-3" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-times-circle fa-3x"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">কোনো তথ্য পাওয়া যায়নি</h4>
                        <p class="text-muted small mb-4">
                            প্রদত্ত আইডি <strong>"{{ $code }}"</strong> এর বিপরীতে কোনো নিবন্ধিত মেম্বারশিপ কার্ড পাওয়া যায়নি। সঠিক Customer ID বা ফোন নম্বর দিয়ে পুনরায় চেষ্টা করুন।
                        </p>
                        <a href="{{ route('apply.card') }}" class="btn btn-outline-success rounded-pill px-4 btn-sm fw-bold">
                            নতুন কার্ডের জন্য আবেদন করুন <i class="fa fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endif
        @endif

    </div>
</div>
@if(Auth::check())
        </section>
    </div>
</div>
@endif
@endsection
