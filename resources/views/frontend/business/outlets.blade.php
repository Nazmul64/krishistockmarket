@php
    $siteLang = request()->cookie('ekrishi_lang', 'bn');
    $isBn = ($siteLang === 'bn');
    $btnColor = setting('button_color') ?: '#007e8a';
@endphp

@extends('layouts.frontend.app')

@section('content')
<div class="outlets-showcase-page" style="background: #fafaf9; min-height: 85vh; padding: 40px 0 70px;">
    <div class="container">

        <!-- Top Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="background: transparent; padding: 0; font-size: 14px;">
                <li class="breadcrumb-item"><a href="{{ route('/') }}" class="text-decoration-none text-muted"><i class="fa fa-home me-1"></i>{{ $isBn ? 'হোম' : 'Home' }}</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">{{ $isBn ? 'আমাদের সম্পর্কে' : 'About Us' }}</a></li>
                <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">{{ $isBn ? 'আউটলেট ও ব্রাঞ্চ' : 'Outlets' }}</li>
            </ol>
        </nav>

        <!-- Centered Modern Store Locations Header -->
        <div class="text-center mb-5">
            <span class="badge border px-3 py-1 rounded-pill mb-2 fw-bold text-uppercase" style="border-color: {{ $btnColor }} !important; color: {{ $btnColor }}; font-size: 11px; letter-spacing: 1.5px; background: rgba(0, 126, 138, 0.08);">
                STORE LOCATIONS
            </span>
            <h1 class="fw-bold text-dark mb-4" style="font-size: clamp(26px, 4vw, 42px); letter-spacing: -0.5px;">
                Find Your Nearest <span style="color: {{ $btnColor }};">{{ setting('title') ?: 'ekrishisheba' }}</span> Outlet
            </h1>

            <!-- Search Bar on right / centered -->
            <div class="d-flex justify-content-center justify-content-md-end mt-4">
                <form action="{{ route('business.outlets') }}" method="GET" class="w-100" style="max-width: 360px;">
                    <div class="input-group rounded-pill shadow-sm bg-white overflow-hidden p-1 border" style="border-color: #e2e8f0;">
                        <span class="input-group-text bg-transparent border-0 text-muted ps-3">
                            <i class="fa fa-search"></i>
                        </span>
                        <input type="text" name="search" id="outletSearchInput" value="{{ request('search') }}" class="form-control border-0 shadow-none font-size-14" placeholder="Search by name or address...">
                        @if(request('search'))
                            <a href="{{ route('business.outlets') }}" class="btn btn-sm btn-light border-0 d-flex align-items-center text-muted px-2">
                                <i class="fa fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Outlets Grid Cards (Image 7, 8, 9 matching) -->
        <div class="row g-4 mb-5" id="outletsGridContainer">
            @forelse($outlets as $outlet)
                @php
                    $weeklyOff = strtolower($outlet->weekly_off_day ?: 'Daily Open');
                    $isDailyOpen = ($weeklyOff === 'daily open' || str_contains($weeklyOff, 'open'));
                    $days = ['Sat' => 'Sat', 'Sun' => 'Sun', 'Mon' => 'Mon', 'Tue' => 'Tue', 'Wed' => 'Wed', 'Thu' => 'Thu', 'Fri' => 'Fri'];
                    $mapUrl = $outlet->google_map_url ?: ('https://maps.google.com/?q=' . urlencode($outlet->name . ', ' . $outlet->address));
                @endphp
                <div class="col-12 col-md-6 col-lg-4 outlet-card-col">
                    <div class="card h-100 rounded-4 border-0 shadow-sm overflow-hidden bg-white outlet-card-item" style="border: 1.5px solid {{ $btnColor }}33 !important; transition: all 0.25s ease;">
                        
                        <!-- Card Top Bar: OUTLET - 01 -->
                        <div class="px-4 py-2 d-flex align-items-center justify-content-between" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                            <span class="fw-bold font-size-11 text-uppercase" style="color: {{ $btnColor }}; letter-spacing: 0.5px;">
                                {{ $outlet->code }}
                            </span>
                            @if($isDailyOpen)
                                <span class="badge rounded-pill font-size-10 px-2 py-1 text-white" style="background-color: {{ $btnColor }};">Open 7 Days</span>
                            @else
                                <span class="badge bg-secondary rounded-pill font-size-10 px-2 py-1">Off: {{ ucfirst($outlet->weekly_off_day) }}</span>
                            @endif
                        </div>

                        <div class="card-body p-4 d-flex flex-column justify-content-between">
                            <div>
                                <!-- Outlet Branch Name -->
                                <h5 class="fw-bold text-dark mb-3" style="font-size: 17px; line-height: 1.35;">
                                    {{ $outlet->name }}
                                </h5>

                                <!-- Address -->
                                <div class="d-flex align-items-start gap-2 mb-3">
                                    <i class="fa fa-map-marker text-danger mt-1 fs-6"></i>
                                    <div class="small text-muted" style="line-height: 1.5;">
                                        {{ $outlet->address }}
                                        @if(!$isDailyOpen)
                                            <span class="d-block text-secondary mt-1 fw-bold font-size-12">
                                                Weekly Closing Day : {{ ucfirst($outlet->weekly_off_day) }}
                                            </span>
                                        @else
                                            <span class="d-block text-success mt-1 fw-bold font-size-12">
                                                Weekly Closing Day : Daily Open
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="d-flex align-items-center gap-2 mb-4">
                                    <i class="fa fa-phone text-warning fs-6"></i>
                                    <a href="tel:{{ $outlet->phone }}" class="text-decoration-none fw-bold small text-dark">
                                        {{ $outlet->phone ?: '+880 1805020348' }}
                                    </a>
                                </div>
                            </div>

                            <div>
                                <!-- Day Schedule Badges -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center gap-1">
                                        @foreach($days as $code => $label)
                                            @php
                                                $isOffDay = (!$isDailyOpen && str_contains($weeklyOff, strtolower($code)));
                                            @endphp
                                            <div class="text-center flex-fill">
                                                @if($isOffDay)
                                                    <div class="badge bg-danger rounded-pill font-size-9 mb-1 d-block px-1" style="font-size: 8px;">Weekly Off</div>
                                                    <span class="badge py-1 px-1 w-100 rounded-2 fw-bold text-white bg-dark font-size-10">
                                                        {{ $code }}
                                                    </span>
                                                @else
                                                    <span class="badge py-1 px-1 w-100 rounded-2 fw-semibold font-size-10" style="background: #ffedd5; color: #c2410c;">
                                                        {{ $code }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Bottom Status & Google Map Button -->
                                <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                                    <span class="d-inline-flex align-items-center gap-1 font-size-12 fw-bold text-success">
                                        <span class="rounded-circle bg-success" style="width: 8px; height: 8px; display: inline-block;"></span>
                                        {{ $outlet->open_days_text ?: ($isDailyOpen ? 'Open 7 Days' : 'Open 6 Days') }}
                                    </span>

                                    <!-- Google Map Link Button -->
                                    <a href="{{ $mapUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm text-white rounded-3 px-3 py-1 fw-bold d-inline-flex align-items-center gap-1 shadow-sm" style="background: {{ $btnColor }}; border-color: {{ $btnColor }}; font-size: 12px;">
                                        <i class="fa fa-map-o"></i> Google Map
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fa fa-building-o text-muted fs-1 mb-3"></i>
                    <h5 class="text-muted">কোনো আউটলেট খুঁজে পাওয়া যায়নি।</h5>
                    <a href="{{ route('business.outlets') }}" class="btn btn-sm btn-outline-warning mt-2">সকল আউটলেট দেখুন</a>
                </div>
            @endforelse
        </div>

        <!-- Bottom Directory Summary List (Image 10 matching) -->
        <div class="card border rounded-4 bg-white p-4 p-md-5 shadow-sm mt-5" style="border-color: #fed7aa !important;">
            <div class="d-flex align-items-center justify-content-between pb-3 border-bottom mb-4">
                <h4 class="fw-bold text-dark mb-0 font-size-20">
                    <i class="fa fa-list-ul text-warning me-2"></i>All Showrooms & Outlets Directory
                </h4>
                <span class="badge bg-warning text-dark font-size-12 px-3 py-1 rounded-pill">Total: {{ $outlets->count() }} Outlets</span>
            </div>

            <div class="row g-4">
                @foreach($outlets as $outlet)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="d-flex align-items-start gap-2">
                            <i class="fa fa-map-marker mt-1 fs-5" style="color: #ea580c;"></i>
                            <div>
                                <a href="{{ $outlet->google_map_url ?: ('https://maps.google.com/?q=' . urlencode($outlet->name)) }}" target="_blank" class="fw-bold text-decoration-none d-block mb-1" style="color: #c2410c; font-size: 15px;">
                                    {{ $outlet->name }}
                                </a>
                                <p class="text-muted small mb-1" style="line-height: 1.4;">{{ $outlet->address }}</p>
                                <small class="text-dark fw-bold"><i class="fa fa-phone text-muted me-1"></i>{{ $outlet->phone }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

<style>
.outlet-card-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(234, 88, 12, 0.12) !important;
}
</style>
@endsection
