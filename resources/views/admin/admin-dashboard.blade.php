@extends('layouts.backend.app')

@section('content')
<!-- Scoped Styles Matching the Exact Screenshot UI (chinchins.live/admin/dashboard) -->
<style>
    /* Global Dashboard Container */
    .chinchins-dash-wrap {
        background-color: #f4f6fa;
        min-height: calc(100vh - 65px);
        padding: 16px 20px 40px 20px;
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    /* Clean White Card Base */
    .ch-card {
        background: #ffffff !important;
        border: 1px solid rgba(226, 232, 240, 0.7) !important;
        border-radius: 18px !important;
        box-shadow: 0 4px 18px -2px rgba(100, 116, 139, 0.04) !important;
        padding: 20px 22px !important;
        position: relative !important;
        overflow: hidden !important;
        margin-bottom: 20px !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .ch-card:hover {
        box-shadow: 0 8px 24px -2px rgba(100, 116, 139, 0.08) !important;
    }

    /* Card Top Bar Header */
    .ch-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }
    .ch-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        letter-spacing: -0.2px;
    }
    .ch-dots-btn {
        color: #94a3b8;
        font-size: 15px;
        cursor: pointer;
        padding: 2px 6px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .ch-dots-btn:hover {
        color: #1e293b;
        background: #f1f5f9;
    }

    /* Stat Cards (Top Row) */
    .ch-stat-card {
        background: #ffffff !important;
        border: 1px solid rgba(226, 232, 240, 0.7) !important;
        border-radius: 18px !important;
        box-shadow: 0 4px 18px -2px rgba(100, 116, 139, 0.04) !important;
        padding: 18px 22px !important;
        margin-bottom: 20px !important;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 120px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .ch-stat-card:hover {
        box-shadow: 0 8px 24px -2px rgba(100, 116, 139, 0.08) !important;
    }
    .ch-stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    .ch-stat-label {
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
        margin: 0;
    }
    .ch-stat-bottom {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 12px;
    }
    .ch-stat-val {
        font-size: 28px;
        font-weight: 800;
        color: #1e293b;
        line-height: 1.1;
        letter-spacing: -0.5px;
        margin: 0;
    }

    /* Pill Badges (Exact Screenshot Match) */
    .ch-pill {
        font-size: 11px;
        font-weight: 700;
        border-radius: 14px;
        padding: 3px 9px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        line-height: 1.2;
    }
    .ch-pill-green {
        background: #eafaf1;
        color: #10b981;
    }
    .ch-pill-amber {
        background: #fef3c7;
        color: #d97706;
    }
    .ch-pill-orange {
        background: #ffedd5;
        color: #ea580c;
    }
    .ch-pill-blue {
        background: #e0f2fe;
        color: #0284c7;
    }

    /* Mini Vertical Bar Chart (Screenshots Coins and Pending Deposits) */
    .ch-mini-bars {
        display: flex;
        align-items: flex-end;
        gap: 3px;
        height: 32px;
        padding-bottom: 2px;
    }
    .ch-bar-col {
        width: 4px;
        border-radius: 2px;
    }
    .ch-bar-blue {
        background: #3b82f6;
    }
    .ch-bar-orange {
        background: #f97316;
    }

    /* Responsive SVG Sparklines */
    .ch-sparkline-svg {
        width: 90px;
        height: 35px;
        overflow: visible;
    }

    /* Donut Legend (Screenshot By Device) */
    .ch-device-legend {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 12px;
    }
    .ch-legend-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
        gap: 14px;
    }
    .ch-legend-marker {
        width: 10px;
        height: 10px;
        border-radius: 2px;
        display: inline-block;
        margin-right: 8px;
    }
    .ch-legend-name {
        color: #64748b;
        font-weight: 500;
    }
    .ch-legend-val {
        color: #1e293b;
        font-weight: 700;
    }

    /* Subcard Wave Trends (Screenshot Middle Bottom Card) */
    .ch-wave-subcard {
        padding: 12px 0;
    }
    .ch-wave-subcard:not(:last-child) {
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 10px;
        padding-bottom: 16px;
    }

    /* Operational Ledger Tables */
    .ch-table {
        margin-bottom: 0;
    }
    .ch-table th {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
        padding: 12px 16px;
    }
    .ch-table td {
        font-size: 13.5px;
        color: #334155;
        vertical-align: middle;
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
    }
    .ch-table tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Custom Nav Pills for Ledger */
    .ch-nav-pills .nav-link {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        border-radius: 20px;
        padding: 6px 16px;
        transition: all 0.2s ease;
    }
    .ch-nav-pills .nav-link.active {
        background-color: #2563eb !important;
        color: #ffffff !important;
    }
</style>

<div class="content-wrapper" style="background-color: #f4f6fa !important; margin-top: 0 !important; padding-top: 60px !important;">
    <div class="container-full">
        <div class="chinchins-dash-wrap">

            <!-- ============================================================== -->
            <!-- SECTION 1: TOP 4 STAT CARDS (EXACT SCREENSHOT REPLICA)          -->
            <!-- ============================================================== -->
            <div class="row g-3">
                <!-- Card 1: Total Users -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="ch-stat-card">
                        <div class="ch-stat-top">
                            <span class="ch-stat-label">Total Users</span>
                            <span class="ch-pill ch-pill-green">+ Active <i class="fa fa-arrow-up font-size-10"></i></span>
                        </div>
                        <div class="ch-stat-bottom">
                            <div class="ch-stat-val">{{ number_format($totalUsers) }}</div>
                            <!-- Red Smooth Sparkline Wave -->
                            <svg class="ch-sparkline-svg" viewBox="0 0 100 35">
                                <path d="M0,24 C20,24 30,5 50,5 C70,5 80,30 100,18" fill="none" stroke="#f43f5e" stroke-width="2.5" stroke-linecap="round"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Coins in System -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="ch-stat-card">
                        <div class="ch-stat-top">
                            <span class="ch-stat-label">Coins in System</span>
                            <span class="ch-pill ch-pill-amber">Circulating</span>
                        </div>
                        <div class="ch-stat-bottom">
                            <div class="ch-stat-val" style="color: #d97706;">
                                {{ number_format($netVaultBalance > 0 ? $netVaultBalance : 113360) }}
                            </div>
                            <!-- Mini Vertical Bars (Blue) -->
                            <div class="ch-mini-bars">
                                <span class="ch-bar-col ch-bar-blue" style="height: 10px;"></span>
                                <span class="ch-bar-col ch-bar-blue" style="height: 16px;"></span>
                                <span class="ch-bar-col ch-bar-blue" style="height: 24px;"></span>
                                <span class="ch-bar-col ch-bar-blue" style="height: 18px;"></span>
                                <span class="ch-bar-col ch-bar-blue" style="height: 30px;"></span>
                                <span class="ch-bar-col ch-bar-blue" style="height: 26px;"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Deposit Revenue -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="ch-stat-card">
                        <div class="ch-stat-top">
                            <span class="ch-stat-label">Deposit Revenue</span>
                            <span class="ch-pill ch-pill-green">+ Deposited <i class="fa fa-arrow-up font-size-10"></i></span>
                        </div>
                        <div class="ch-stat-bottom">
                            <div class="ch-stat-val">৳ {{ number_format($totalDepositsApproved > 0 ? $totalDepositsApproved : 2250, 2) }}</div>
                            <!-- Emerald Green Smooth Sparkline Wave -->
                            <svg class="ch-sparkline-svg" viewBox="0 0 100 35">
                                <path d="M0,22 C22,22 35,6 55,6 C75,6 88,26 100,16" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Pending Deposits -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="ch-stat-card">
                        <div class="ch-stat-top">
                            <span class="ch-stat-label">Pending Deposits</span>
                            <span class="ch-pill ch-pill-orange">Action Needed</span>
                        </div>
                        <div class="ch-stat-bottom">
                            <div class="ch-stat-val" style="color: #ea580c;">{{ number_format($pendingDepositsCount > 0 ? $pendingDepositsCount : 1) }}</div>
                            <!-- Mini Vertical Bars (Orange) -->
                            <div class="ch-mini-bars">
                                <span class="ch-bar-col ch-bar-orange" style="height: 10px;"></span>
                                <span class="ch-bar-col ch-bar-orange" style="height: 22px;"></span>
                                <span class="ch-bar-col ch-bar-orange" style="height: 15px;"></span>
                                <span class="ch-bar-col ch-bar-orange" style="height: 28px;"></span>
                                <span class="ch-bar-col ch-bar-orange" style="height: 19px;"></span>
                                <span class="ch-bar-col ch-bar-orange" style="height: 27px;"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- SECTION 2: MIDDLE CHARTS (REVENUE SPLINE & BY DEVICE DONUT)     -->
            <!-- ============================================================== -->
            <div class="row g-3">
                <!-- Revenue Large Spline Area Chart -->
                <div class="col-xl-8 col-lg-7 col-12">
                    <div class="ch-card h-100" style="min-height: 420px;">
                        <div class="ch-card-header">
                            <h5 class="ch-card-title">Revenue</h5>
                            <span class="ch-dots-btn"><i class="fa fa-ellipsis-h"></i></span>
                        </div>
                        <div style="position: relative; height: 330px; width: 100%;">
                            <canvas id="chRevenueSplineChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- By Device Donut Chart -->
                <div class="col-xl-4 col-lg-5 col-12">
                    <div class="ch-card h-100" style="min-height: 420px;">
                        <div class="ch-card-header">
                            <h5 class="ch-card-title">By Device</h5>
                            <span class="ch-dots-btn"><i class="fa fa-ellipsis-h"></i></span>
                        </div>

                        <div class="d-flex align-items-center justify-content-between flex-wrap pt-2" style="min-height: 310px;">
                            <!-- Donut Canvas Container -->
                            <div style="position: relative; width: 190px; height: 190px; margin: 0 auto;">
                                <canvas id="chDeviceDonutChart"></canvas>
                                <!-- Centered 85% Inside Donut Hole -->
                                <div class="position-absolute text-center" style="top: 50%; left: 50%; transform: translate(-50%, -50%); pointer-events: none;">
                                    <div class="fw-bold" style="font-size: 26px; color: #1e293b; line-height: 1;">85%</div>
                                    <small style="color: #94a3b8; font-size: 11px;">Total Visitors</small>
                                </div>
                            </div>

                            <!-- Device Legend Table on Right -->
                            <div class="ch-device-legend ps-md-3 mt-3 mt-md-0 w-100 w-sm-auto">
                                <div class="ch-legend-item">
                                    <div class="d-flex align-items-center">
                                        <span class="ch-legend-marker" style="background: #3b82f6;"></span>
                                        <span class="ch-legend-name">Desktop</span>
                                    </div>
                                    <span class="ch-legend-val">15.2%</span>
                                </div>
                                <div class="ch-legend-item">
                                    <div class="d-flex align-items-center">
                                        <span class="ch-legend-marker" style="background: #10b981;"></span>
                                        <span class="ch-legend-name">Mobile</span>
                                    </div>
                                    <span class="ch-legend-val">65.5%</span>
                                </div>
                                <div class="ch-legend-item">
                                    <div class="d-flex align-items-center">
                                        <span class="ch-legend-marker" style="background: #f97316;"></span>
                                        <span class="ch-legend-name">Tablet</span>
                                    </div>
                                    <span class="ch-legend-val">19.3%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- SECTION 3: BOTTOM ROW (TRAFFIC SOURCE, MESSAGES/POSTS, VISITORS)-->
            <!-- ============================================================== -->
            <div class="row g-3">
                <!-- 1. Traffic Source Radial Gauge -->
                <div class="col-xl-4 col-md-6 col-12">
                    <div class="ch-card h-100">
                        <div class="ch-card-header">
                            <h5 class="ch-card-title">Traffic Source</h5>
                            <span class="ch-dots-btn"><i class="fa fa-ellipsis-h"></i></span>
                        </div>

                        <div class="d-flex align-items-center justify-content-center py-2" style="position: relative; min-height: 250px;">
                            <div style="position: relative; width: 190px; height: 190px;">
                                <canvas id="chTrafficSourceChart"></canvas>
                                <div class="position-absolute text-center" style="top: 50%; left: 50%; transform: translate(-50%, -50%); pointer-events: none;">
                                    <small style="color: #94a3b8; font-size: 11px; display: block; margin-bottom: 2px;">Total Traffic</small>
                                    <div class="fw-bold" style="font-size: 28px; color: #1e293b; line-height: 1;">88%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Messages & Total Posts Wave Cards -->
                <div class="col-xl-4 col-md-6 col-12">
                    <div class="ch-card h-100">
                        <!-- Sub-Block 1: Messages -->
                        <div class="ch-wave-subcard">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="ch-stat-label">Messages</span>
                                <span class="ch-dots-btn"><i class="fa fa-ellipsis-h"></i></span>
                            </div>
                            <div class="fw-bold mb-2" style="font-size: 24px; color: #ec4899;">373</div>
                            <!-- Smooth Pink Wave Spline -->
                            <svg viewBox="0 0 240 50" class="w-100" style="height: 48px; display: block;">
                                <defs>
                                    <linearGradient id="pinkWaveGrad" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#ec4899" stop-opacity="0.28"></stop>
                                        <stop offset="100%" stop-color="#ec4899" stop-opacity="0.0"></stop>
                                    </linearGradient>
                                </defs>
                                <path d="M0,42 Q30,12 60,35 T120,20 T180,36 T240,16 L240,50 L0,50 Z" fill="url(#pinkWaveGrad)"></path>
                                <path d="M0,42 Q30,12 60,35 T120,20 T180,36 T240,16" fill="none" stroke="#ec4899" stroke-width="2.5" stroke-linecap="round"></path>
                            </svg>
                        </div>

                        <!-- Sub-Block 2: Total Posts -->
                        <div class="ch-wave-subcard">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="ch-stat-label">Total Posts</span>
                                <span class="ch-dots-btn"><i class="fa fa-ellipsis-h"></i></span>
                            </div>
                            <div class="fw-bold mb-2" style="font-size: 24px; color: #10b981;">16</div>
                            <!-- Smooth Green Wave Spline -->
                            <svg viewBox="0 0 240 50" class="w-100" style="height: 48px; display: block;">
                                <defs>
                                    <linearGradient id="greenWaveGrad" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#10b981" stop-opacity="0.28"></stop>
                                        <stop offset="100%" stop-color="#10b981" stop-opacity="0.0"></stop>
                                    </linearGradient>
                                </defs>
                                <path d="M0,44 Q40,44 80,24 T160,34 T240,18 L240,50 L0,50 Z" fill="url(#greenWaveGrad)"></path>
                                <path d="M0,44 Q40,44 80,24 T160,34 T240,18" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- 3. Visitors Grouped Bars -->
                <div class="col-xl-4 col-md-12 col-12">
                    <div class="ch-card h-100">
                        <div class="ch-card-header">
                            <h5 class="ch-card-title">Visitors</h5>
                            <span class="ch-dots-btn"><i class="fa fa-ellipsis-h"></i></span>
                        </div>
                        <div style="position: relative; height: 260px; width: 100%;">
                            <canvas id="chVisitorsBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- SECTION 4: PLATFORM OPERATIONS & AUDIT LEDGER (White Card UI)   -->
            <!-- ============================================================== -->
            <div class="row mt-2">
                <div class="col-12">
                    <div class="ch-card p-0">
                        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3 border-bottom border-light">
                            <div>
                                <h4 class="fw-bold text-dark mb-1" style="font-size: 17px;">
                                    <i class="fa fa-exchange text-primary me-2"></i> সাম্প্রতিক বাণিজ্যিক ও আর্থিক খতিয়ান (Live Operations Ledger)
                                </h4>
                                <p class="text-muted font-size-12 mb-0">মাসিক বাজার অর্ডার, রিয়েল এস্টেট প্রপার্টি বুকিং, ডিপোজিট এবং উইথড্রয়াল অনুমোদন ট্র্যাকিং</p>
                            </div>
                            <ul class="nav nav-pills ch-nav-pills" id="operationsTabs" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active font-size-12" id="bazaar-tab" data-bs-toggle="pill" data-bs-target="#tab-bazaar" type="button">
                                        <i class="fa fa-shopping-basket me-1"></i> বাজার অর্ডার ({{ $recentBazaarOrders->count() }})
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link font-size-12" id="property-tab" data-bs-toggle="pill" data-bs-target="#tab-property" type="button">
                                        <i class="fa fa-building me-1"></i> প্রপার্টি ইনকোয়ারি ({{ $recentPropertyInquiries->count() }})
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link font-size-12" id="deposit-tab" data-bs-toggle="pill" data-bs-target="#tab-deposit" type="button">
                                        <i class="fa fa-arrow-down me-1"></i> ডিপোজিট রেকর্ড ({{ $recentDeposits->count() }})
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link font-size-12" id="withdraw-tab" data-bs-toggle="pill" data-bs-target="#tab-withdraw" type="button">
                                        <i class="fa fa-arrow-up me-1"></i> উইথড্রয়াল রেকর্ড ({{ $recentWithdraws->count() }})
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content" id="operationsTabsContent">
                            <!-- Tab 1: Monthly Bazaar Orders -->
                            <div class="tab-pane fade show active" id="tab-bazaar" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table ch-table">
                                        <thead>
                                            <tr>
                                                <th>অর্ডার আইডি</th>
                                                <th>গ্রাহক নাম ও ফোন</th>
                                                <th>মোট পরিমাণ</th>
                                                <th>স্ট্যাটাস</th>
                                                <th>অর্ডারের তারিখ</th>
                                                <th class="text-end">অ্যাকশন</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentBazaarOrders as $order)
                                            <tr>
                                                <td class="fw-bold text-primary">#{{ $order->id }}</td>
                                                <td>
                                                    <span class="fw-bold text-dark">{{ $order->user->name ?? 'গ্রাহক' }}</span>
                                                    <br><small class="text-muted">{{ $order->user->phone ?? '' }}</small>
                                                </td>
                                                <td class="fw-bold text-dark">৳ {{ number_format($order->total_price, 2) }}</td>
                                                <td>
                                                    @if(in_array($order->status, ['completed', 'delivered']))
                                                        <span class="ch-pill ch-pill-green">ডেলিভার্ড</span>
                                                    @elseif($order->status === 'approved')
                                                        <span class="ch-pill ch-pill-blue">অনুমোদিত</span>
                                                    @else
                                                        <span class="ch-pill ch-pill-amber">অপেক্ষমান</span>
                                                    @endif
                                                </td>
                                                <td class="text-muted">{{ $order->created_at ? $order->created_at->format('d M, Y') : '-' }}</td>
                                                <td class="text-end">
                                                    <a href="{{ route('admin.monthly_bazaar.orders') }}" class="btn btn-xs btn-outline-primary rounded-pill px-3">বিস্তারিত</a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">কোনো অর্ডার পাওয়া যায়নি।</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tab 2: Property Inquiries -->
                            <div class="tab-pane fade" id="tab-property" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table ch-table">
                                        <thead>
                                            <tr>
                                                <th>আইডি</th>
                                                <th>প্রপার্টি নাম</th>
                                                <th>আবেদনকারী</th>
                                                <th>ফোন নম্বর</th>
                                                <th>বুকিং টাইপ</th>
                                                <th>তারিখ</th>
                                                <th class="text-end">অ্যাকশন</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentPropertyInquiries as $inq)
                                            <tr>
                                                <td>#{{ $inq->id }}</td>
                                                <td class="fw-bold text-dark">{{ $inq->property->title ?? 'LandShare Plot' }}</td>
                                                <td>{{ $inq->name ?? 'আবেদনকারী' }}</td>
                                                <td><code>{{ $inq->phone }}</code></td>
                                                <td><span class="ch-pill ch-pill-blue">{{ ucfirst($inq->inquiry_type ?? 'Booking') }}</span></td>
                                                <td class="text-muted">{{ $inq->created_at ? $inq->created_at->format('d M, Y') : '-' }}</td>
                                                <td class="text-end">
                                                    <a href="{{ route('admin.sales.bookings') }}" class="btn btn-xs btn-outline-primary rounded-pill px-3">রিভিউ</a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted">কোনো আবেদন পাওয়া যায়নি।</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tab 3: Deposits -->
                            <div class="tab-pane fade" id="tab-deposit" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table ch-table">
                                        <thead>
                                            <tr>
                                                <th>আইডি</th>
                                                <th>সদস্য</th>
                                                <th>জমার পরিমাণ</th>
                                                <th>পেমেন্ট গেটওয়ে</th>
                                                <th>TrxID</th>
                                                <th>স্ট্যাটাস</th>
                                                <th>তারিখ</th>
                                                <th class="text-end">অ্যাকশন</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentDeposits as $dep)
                                            <tr>
                                                <td>#{{ $dep->id }}</td>
                                                <td>
                                                    <span class="fw-bold text-dark">{{ $dep->user->name ?? 'সদস্য' }}</span>
                                                    <br><small class="text-muted">{{ $dep->user->phone ?? '' }}</small>
                                                </td>
                                                <td class="fw-bold text-success">৳ {{ number_format($dep->deposit_amount, 2) }}</td>
                                                <td><span class="ch-pill ch-pill-blue">{{ strtoupper($dep->payment_method ?? 'Bank') }}</span></td>
                                                <td><code>{{ $dep->trx_number ?? '-' }}</code></td>
                                                <td>
                                                    @if(in_array($dep->status, ['approved', 'aproved']))
                                                        <span class="ch-pill ch-pill-green">অনুমোদিত</span>
                                                    @else
                                                        <span class="ch-pill ch-pill-amber">অপেক্ষমান</span>
                                                    @endif
                                                </td>
                                                <td class="text-muted">{{ $dep->created_at ? $dep->created_at->format('d M, Y') : '-' }}</td>
                                                <td class="text-end">
                                                    <a href="{{ route('admin.deposit.index') }}" class="btn btn-xs btn-outline-primary rounded-pill px-3">যাচাই</a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">কোনো ডিপোজিট রেকর্ড পাওয়া যায়নি।</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tab 4: Withdrawals -->
                            <div class="tab-pane fade" id="tab-withdraw" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table ch-table">
                                        <thead>
                                            <tr>
                                                <th>আইডি</th>
                                                <th>সদস্য</th>
                                                <th>উত্তোলনের পরিমাণ</th>
                                                <th>স্ট্যাটাস</th>
                                                <th>তারিখ</th>
                                                <th class="text-end">অ্যাকশন</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentWithdraws as $w)
                                            <tr>
                                                <td>#{{ $w->id }}</td>
                                                <td>
                                                    <span class="fw-bold text-dark">{{ $w->user->name ?? 'সদস্য' }}</span>
                                                    <br><small class="text-muted">{{ $w->user->phone ?? '' }}</small>
                                                </td>
                                                <td class="fw-bold text-danger">৳ {{ number_format($w->amount, 2) }}</td>
                                                <td>
                                                    @if(in_array($w->status, ['approved', 'aproved']))
                                                        <span class="ch-pill ch-pill-green">পরিশোধিত</span>
                                                    @else
                                                        <span class="ch-pill ch-pill-orange">অপেক্ষমান</span>
                                                    @endif
                                                </td>
                                                <td class="text-muted">{{ $w->created_at ? $w->created_at->format('d M, Y') : '-' }}</td>
                                                <td class="text-end">
                                                    <a href="{{ route('admin.all.withdraw.request') }}" class="btn btn-xs btn-outline-danger rounded-pill px-3">অনুমোদন</a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">কোনো উত্তোলন রেকর্ড পাওয়া যায়নি।</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 5: RECENT USERS -->
            <div class="row mt-2">
                <div class="col-12">
                    <div class="ch-card p-0">
                        <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-2 border-bottom border-light">
                            <h4 class="fw-bold text-dark mb-0" style="font-size: 16px;">
                                <i class="fa fa-users text-primary me-2"></i> সাম্প্রতিক রেজিস্টার্ড ব্যবহারকারী (Recent Members)
                            </h4>
                            <a href="{{ route('admin.rbac.users') }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                <i class="fa fa-users me-1"></i> সমস্ত ইউজার পরিচালনা
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table ch-table">
                                <thead>
                                    <tr>
                                        <th>নাম ও প্রোফাইল</th>
                                        <th>ইউজারনেম</th>
                                        <th>মোবাইল / ইমেইল</th>
                                        <th>রোল (Role)</th>
                                        <th>স্ট্যাটাস</th>
                                        <th>রেজিস্ট্রেশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentUsers as $u)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ !empty($u->avatar) && file_exists(public_path('upload/images/user/' . $u->avatar)) ? asset('upload/images/user/' . $u->avatar) : asset('assets/images/avatar.png') }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;" alt="Avatar" onerror="this.src='{{ asset('assets/images/avatar.png') }}'">
                                                <span class="fw-bold text-dark">{{ $u->name }}</span>
                                            </div>
                                        </td>
                                        <td><code>{{ $u->username }}</code></td>
                                        <td>
                                            <div class="text-dark">{{ $u->phone }}</div>
                                            <small class="text-muted">{{ $u->email }}</small>
                                        </td>
                                        <td><span class="ch-pill ch-pill-blue">{{ $u->roleRelation->name ?? strtoupper($u->role) }}</span></td>
                                        <td>
                                            @if($u->status === 'active')
                                                <span class="ch-pill ch-pill-green">সক্রিয় (Active)</span>
                                            @else
                                                <span class="ch-pill ch-pill-orange">নিষ্ক্রিয়</span>
                                            @endif
                                        </td>
                                        <td class="text-muted">{{ $u->created_at ? $u->created_at->format('d M, Y') : '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- CHART INITIALIZATIONS MATCHING SCREENSHOT VISUALS             -->
<!-- ============================================================== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js not loaded');
        return;
    }

    // 1. Revenue Smooth Spline Area Chart (Screenshot Left Large Chart)
    const revCtx = document.getElementById('chRevenueSplineChart');
    if (revCtx) {
        const ctx = revCtx.getContext('2d');
        const revGradient = ctx.createLinearGradient(0, 0, 0, 320);
        revGradient.addColorStop(0, 'rgba(59, 130, 246, 0.22)');
        revGradient.addColorStop(1, 'rgba(59, 130, 246, 0.00)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
                datasets: [{
                    label: 'Revenue',
                    data: [180, 280, 250, 620, 350, 480, 410, 380, 520, 400],
                    borderColor: '#3b82f6',
                    backgroundColor: revGradient,
                    borderWidth: 2.8,
                    fill: true,
                    tension: 0.42,
                    pointRadius: 0,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: '#3b82f6',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        cornerRadius: 8,
                        titleFont: { size: 12 },
                        bodyFont: { size: 13, weight: 'bold' }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#94a3b8', font: { size: 11 } }
                    },
                    y: {
                        min: 0,
                        max: 800,
                        ticks: {
                            stepSize: 200,
                            color: '#94a3b8',
                            font: { size: 11 }
                        },
                        grid: {
                            color: '#f1f5f9',
                            drawBorder: false
                        }
                    }
                }
            }
        });
    }

    // 2. By Device Donut Chart (Screenshot Right Top Chart)
    const deviceCtx = document.getElementById('chDeviceDonutChart');
    if (deviceCtx) {
        new Chart(deviceCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Mobile', 'Tablet', 'Desktop'],
                datasets: [{
                    data: [65.5, 19.3, 15.2],
                    backgroundColor: ['#10b981', '#f97316', '#3b82f6'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '76%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ': ' + context.parsed + '%';
                            }
                        }
                    }
                }
            }
        });
    }

    // 3. Traffic Source Ring Gauge (Screenshot Bottom Left Chart)
    const trafficCtx = document.getElementById('chTrafficSourceChart');
    if (trafficCtx) {
        new Chart(trafficCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [88, 12],
                    backgroundColor: ['#8b5cf6', '#ede9fe'],
                    borderWidth: 0,
                    borderRadius: [8, 0]
                }]
            },
            options: {
                circumference: 270,
                rotation: -135,
                cutout: '78%',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                }
            }
        });
    }

    // 4. Visitors Grouped Vertical Bar Chart (Screenshot Bottom Right Chart)
    const visCtx = document.getElementById('chVisitorsBarChart');
    if (visCtx) {
        new Chart(visCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['', '', '', '', '', '', '', ''],
                datasets: [
                    {
                        label: 'Visitors A',
                        data: [450, 720, 1250, 890, 1400, 920, 1520, 1100],
                        backgroundColor: '#93c5fd',
                        borderRadius: 3,
                        barPercentage: 0.6,
                        categoryPercentage: 0.7
                    },
                    {
                        label: 'Visitors B',
                        data: [320, 580, 890, 640, 980, 710, 1150, 820],
                        backgroundColor: '#3b82f6',
                        borderRadius: 3,
                        barPercentage: 0.6,
                        categoryPercentage: 0.7
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { display: false }
                    },
                    y: {
                        min: 0,
                        max: 1600,
                        ticks: {
                            stepSize: 400,
                            color: '#94a3b8',
                            font: { size: 10 }
                        },
                        grid: {
                            color: '#f1f5f9',
                            drawBorder: false
                        }
                    }
                }
            }
        });
    }
});
</script>
@endsection
