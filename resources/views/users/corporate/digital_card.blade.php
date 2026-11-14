@extends('layouts.backend.app')

@section('content')
@php
    $user = $user ?? Auth::user();
    $cardType = $cardType ?? ($user->membership_card_type ?? 'sme');
@endphp
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content py-3">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-id-card text-success me-2"></i> ডিজিটাল মেম্বারশিপ কার্ড</h4>
                    <p class="text-muted small mb-0">আপনার ভার্চুয়াল পরিচয়পত্র, কিউআর ভেরিফিকেশন ও এক্সক্লুসিভ মেম্বারশিপ প্রিভিলেজ।</p>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                        <i class="fa fa-print me-1"></i> কার্ড প্রিন্ট করুন
                    </button>
                    <a href="{{ route('verify.card', ['card_number' => $user->customer_id ?: $user->username]) }}" target="_blank" class="btn btn-success btn-sm rounded-pill px-3">
                        <i class="fa fa-external-link me-1"></i> লাইভ ভেরিফিকেশন লিংক
                    </a>
                </div>
            </div>

            <div class="row g-4 justify-content-center">
                <!-- Digital Card Preview (Front & Back) -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center">
                        <h5 class="fw-bold text-dark mb-3">ভার্চুয়াল কার্ড (Virtual Card Preview)</h5>
                        <p class="text-muted small mb-4">কার্ডটি ক্লিক করে সামনের ও পেছনের পাশ পরিবর্তন (Flip) করুন</p>

                        <!-- Flip Card Container -->
                        <div class="flip-card-wrapper mx-auto mb-4" onclick="this.classList.toggle('flipped')" style="perspective: 1000px; max-width: 440px; height: 260px; cursor: pointer;">
                            <div class="flip-card-inner position-relative w-100 h-100 text-start shadow-lg rounded-4" style="transition: transform 0.8s; transform-style: preserve-3d;">
                                
                                <!-- FRONT SIDE -->
                                <div class="flip-card-front position-absolute w-100 h-100 rounded-4 p-4 text-white d-flex flex-column justify-content-between"
                                     style="backface-visibility: hidden; background: {{ match($cardType) {
                                         'gold' => 'linear-gradient(135deg, #78350f 0%, #b45309 45%, #d97706 100%)',
                                         'property' => 'linear-gradient(135deg, #0f172a 0%, #1e3a8a 60%, #2563eb 100%)',
                                         'shareholder' => 'linear-gradient(135deg, #3b0764 0%, #6b21a8 60%, #9333ea 100%)',
                                         'partnership' => 'linear-gradient(135deg, #064e3b 0%, #047857 60%, #059669 100%)',
                                         default => 'linear-gradient(135deg, #064e3b 0%, #0d9488 100%)',
                                     } }}; border: 1px solid rgba(255,255,255,0.25);">
                                    
                                    <!-- Front Header -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="card-chip rounded-2 shadow-sm" style="width: 40px; height: 28px; background: linear-gradient(135deg, #fef08a, #ca8a04); border: 1px solid #a16207;"></div>
                                            <i class="fa fa-wifi text-white-50" style="transform: rotate(90deg); font-size: 16px;"></i>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-warning text-dark font-size-10 fw-bold px-2 py-1 rounded-pill text-uppercase">
                                                {{ strtoupper($cardType) }} CARD
                                            </span>
                                            <div class="fw-bold tracking-widest text-white font-size-14 mt-1">ekrishisheba</div>
                                        </div>
                                    </div>

                                    <!-- Front Middle: Customer ID & Member Name -->
                                    <div class="my-2">
                                        <div class="font-monospace text-warning fw-bold font-size-20 tracking-wider">
                                            {{ $user->customer_id ?: 'ID: ' . strtoupper($user->username) }}
                                        </div>
                                        <div class="fw-bold text-white font-size-16 mt-1">{{ $user->name }}</div>
                                    </div>

                                    <!-- Front Footer -->
                                    <div class="d-flex justify-content-between align-items-end pt-2 border-top border-white-50 font-size-11">
                                        <div>
                                            <span class="text-white-50 d-block">ইস্যুর তারিখ:</span>
                                            <strong class="text-white">{{ \Carbon\Carbon::parse($user->created_at)->format('m/Y') }}</strong>
                                        </div>
                                        <div>
                                            <span class="text-white-50 d-block">স্ট্যাটাস:</span>
                                            <span class="badge bg-success text-white py-0 px-2 font-size-10">VERIFIED</span>
                                        </div>
                                        <div class="text-end">
                                            <span class="text-white-50 d-block">ভ্যালিডিটি:</span>
                                            <strong class="text-white">LIFETIME</strong>
                                        </div>
                                    </div>
                                </div>

                                <!-- BACK SIDE -->
                                <div class="flip-card-back position-absolute w-100 h-100 rounded-4 text-white d-flex flex-column justify-content-between overflow-hidden"
                                     style="backface-visibility: hidden; transform: rotateY(180deg); background: #0f172a; border: 1px solid rgba(255,255,255,0.15);">
                                    
                                    <!-- Black Magnetic Stripe -->
                                    <div class="w-100 bg-black mt-3" style="height: 42px;"></div>

                                    <!-- Signature Strip & QR -->
                                    <div class="px-4 py-2 d-flex justify-content-between align-items-center">
                                        <div class="w-65">
                                            <div class="bg-white text-dark font-monospace px-3 py-1 rounded small fw-bold font-size-12" style="background-image: repeating-linear-gradient(45deg, #e2e8f0, #e2e8f0 10px, #ffffff 10px, #ffffff 20px);">
                                                {{ $user->customer_id ?: $user->username }}
                                            </div>
                                            <span class="text-muted font-size-10 d-block mt-1">Authorized Digital Signature</span>
                                        </div>
                                        <div class="bg-white p-1 rounded">
                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data={{ urlencode(route('verify.card', ['card_number' => $user->customer_id ?: $user->username])) }}" alt="QR" style="width: 50px; height: 50px;">
                                        </div>
                                    </div>

                                    <!-- Back Disclaimer -->
                                    <div class="px-4 pb-3 font-size-10 text-muted border-top border-secondary pt-2">
                                        <p class="mb-1 leading-tight">এই কার্ডটি ই-কৃষি সেবা লিমিটেডের ডিজিটাল সম্পত্তি। কার্ড সংক্রান্ত যেকোনো তথ্যে ভিজিট করুন: {{ url('/') }}</p>
                                        <div class="d-flex justify-content-between text-white-50">
                                            <span>হেল্পলাইন: {{ setting('phone1') ?: '01700000000' }}</span>
                                            <span>কাস্টমার কেয়ার ২৪/৭</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="text-muted small">
                            <i class="fa fa-hand-pointer-o me-1"></i> কার্ডের ওপর ক্লিক করে উল্টিয়ে পেছনের অংশ দেখুন
                        </div>
                    </div>
                </div>

                <!-- Card Details & Facilities -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <h5 class="fw-bold text-dark mb-3">মেম্বারশিপ প্রোফাইল ও নির্ধারিত সুবিধাসমূহ</h5>
                        
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered small">
                                <tbody>
                                    <tr>
                                        <th class="bg-light" style="width: 35%;">সদস্যের নাম:</th>
                                        <td class="fw-bold text-dark">{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Customer ID:</th>
                                        <td class="text-success fw-bold font-monospace">{{ $user->customer_id ?: $user->username }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">কার্ডের শ্রেণি:</th>
                                        <td class="text-uppercase fw-bold text-primary">{{ $cardType }} CARD</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">ওয়ালেট ব্যালেন্স:</th>
                                        <td class="text-success fw-bold font-size-15">{{ number_format($user->balance, 2) }} ৳</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">লকড ব্যালেন্স:</th>
                                        <td class="text-danger fw-bold">{{ number_format($user->locked_balance, 2) }} ৳</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h6 class="fw-bold text-dark mb-2"><i class="fa fa-star text-warning me-1"></i> আপনার কার্ডের বিশেষ সুবিধাসমূহ:</h6>
                        @if($benefit && !empty($benefit->facilities))
                            @php
                                $facilities = is_array($benefit->facilities) ? $benefit->facilities : json_decode($benefit->facilities, true);
                            @endphp
                            <ul class="list-unstyled small text-muted mb-4">
                                @if(is_array($facilities))
                                    @foreach($facilities as $fac)
                                        <li class="mb-2 d-flex align-items-center gap-2">
                                            <i class="fa fa-check-circle text-success"></i>
                                            <span>{{ $fac }}</span>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        @else
                            <ul class="list-unstyled small text-muted mb-4">
                                <li class="mb-2"><i class="fa fa-check-circle text-success me-1"></i> ডিজিটাল মেম্বারশিপ কার্ড ও ইউনিক Customer ID</li>
                                <li class="mb-2"><i class="fa fa-check-circle text-success me-1"></i> রিয়েল-টাইম ওয়ালেট ব্যালেন্স ও লেনদেন হিস্ট্রি</li>
                                <li class="mb-2"><i class="fa fa-check-circle text-success me-1"></i> প্রকল্পভিত্তিক বিনিয়োগ পর্যবেক্ষণ ও লভ্যাংশ সুবিধা</li>
                            </ul>
                        @endif

                        <div class="mt-auto d-flex gap-2">
                            <a href="{{ route('apply.card') }}" class="btn btn-outline-warning rounded-pill btn-sm px-4 fw-bold flex-grow-1">
                                <i class="fa fa-arrow-up me-1"></i> কার্ড আপগ্রেড করুন
                            </a>
                            <a href="{{ route('user.investments.my') }}" class="btn btn-success rounded-pill btn-sm px-4 fw-bold flex-grow-1">
                                <i class="fa fa-line-chart me-1"></i> আমার বিনিয়োগ
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
</div>

<style>
.flip-card-wrapper.flipped .flip-card-inner {
    transform: rotateY(180deg);
}
@media print {
    body * {
        visibility: hidden;
    }
    .flip-card-wrapper, .flip-card-wrapper * {
        visibility: visible;
    }
    .flip-card-wrapper {
        position: absolute;
        left: 0;
        top: 0;
    }
}
</style>
@endsection
