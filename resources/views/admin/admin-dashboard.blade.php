@extends('layouts.backend.app')

@section('content')
<style>
/* ============================================================== */
/* JIDOX EXECUTIVE DARK DASHBOARD STYLES (Fixes All Overlaps)    */
/* ============================================================== */
.jidox-dashboard-wrap {
    background-color: #131924;
    padding: 4px 18px 20px 18px;
    color: #e2e8f0;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}
.jidox-header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 0;
    margin-bottom: 12px;
    padding-top: 0;
    padding-bottom: 6px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.07);
}
.jidox-header-title {
    color: #ffffff;
    font-size: 22px;
    font-weight: 700;
    margin: 0;
    letter-spacing: -0.3px;
}
.jidox-date-badge {
    background: #1e2640;
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: #94a3b8;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
}
.jidox-card {
    background: #1e2640 !important;
    border: 1px solid rgba(255, 255, 255, 0.07) !important;
    border-radius: 12px !important;
    padding: 18px 20px !important;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.28) !important;
    position: relative !important;
    overflow: hidden !important;
    z-index: 1;
    margin-bottom: 16px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.jidox-card:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.35) !important;
}
.jidox-stat-label {
    color: #94a3b8;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 4px;
}
.jidox-stat-val {
    color: #ffffff;
    font-size: 22px;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 6px;
}
.jidox-icon-box {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}
.jidox-icon-teal {
    background: rgba(20, 184, 166, 0.18);
    color: #14b8a6;
}
.jidox-icon-cyan {
    background: rgba(6, 182, 212, 0.18);
    color: #06b6d4;
}
.jidox-icon-coral {
    background: rgba(244, 63, 94, 0.18);
    color: #f43f5e;
}
.jidox-icon-violet {
    background: rgba(139, 92, 246, 0.18);
    color: #a78bfa;
}
.jidox-icon-amber {
    background: rgba(245, 158, 11, 0.18);
    color: #f59e0b;
}
.jidox-icon-slate {
    background: rgba(100, 116, 139, 0.25);
    color: #cbd5e1;
}
.jidox-badge-green {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: #10b981;
    background: rgba(16, 185, 129, 0.12);
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    width: fit-content;
}
.jidox-badge-red {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: #ef4444;
    background: rgba(239, 68, 68, 0.12);
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    width: fit-content;
}
.jidox-kpi-item {
    padding: 4px 6px;
}
.jidox-kpi-title {
    color: #94a3b8;
    font-size: 11px;
    font-weight: 600;
    margin-bottom: 2px;
    white-space: nowrap;
}
.jidox-kpi-val {
    color: #ffffff;
    font-size: 15px;
    font-weight: 700;
}
.jidox-chart-container {
    position: relative;
    height: 260px;
    max-height: 260px;
    width: 100%;
    overflow: hidden;
    clear: both;
}
.jidox-location-bar {
    margin-bottom: 14px;
}
.jidox-location-label {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    color: #cbd5e1;
    margin-bottom: 5px;
}
.jidox-progress-track {
    height: 6px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 4px;
    overflow: hidden;
}
.jidox-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #3b82f6, #6366f1);
    border-radius: 4px;
}
.jidox-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    color: #cbd5e1;
}
.jidox-table thead th {
    background: rgba(255, 255, 255, 0.03) !important;
    color: #94a3b8 !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.6px !important;
    border: none !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
    padding: 12px 14px !important;
    vertical-align: middle !important;
}
.jidox-table tbody td {
    border: none !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
    padding: 14px 14px !important;
    vertical-align: middle !important;
    font-size: 13px !important;
    line-height: 1.4 !important;
    background: transparent !important;
}
.jidox-table tbody tr:last-child td {
    border-bottom: none !important;
}
.jidox-table tbody tr:hover td {
    background: rgba(255, 255, 255, 0.02) !important;
}
</style>

<div class="content-wrapper" style="background-color: #131924 !important; margin-top: 0 !important; padding-top: 60px !important;">
    <div class="container-full">

        <!-- ============================================================== -->
        <!-- JIDOX EXECUTIVE DARK DASHBOARD (Modern Analytics Suite) -->
        <!-- ============================================================== -->
        <div class="jidox-dashboard-wrap">
            
            <!-- JIDOX Top Header Bar -->
            <div class="jidox-header-bar">
                <div>
                    <h2 class="jidox-header-title">Dashboard</h2>
                    <span class="text-muted font-size-12">Corporate Enterprise ERP, Investment & Central Operations Hub</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="jidox-date-badge">
                        <span>{{ date('d/m/Y') }}</span>
                    </div>
                    <button class="btn btn-sm btn-primary rounded-3 px-3 py-2" title="ক্যালেন্ডার ভিউ" style="background: #2563eb; border: none;">
                        <i class="fa fa-calendar"></i>
                    </button>
                    <button class="btn btn-sm btn-dark rounded-3 px-3 py-2" onclick="location.reload()" title="রিফ্রেশ" style="background: #1e293b; border: 1px solid rgba(255,255,255,0.1); color: #cbd5e1;">
                        <i class="fa fa-refresh"></i>
                    </button>
                </div>
            </div>

            <!-- JIDOX Top 6 Metric Cards Row -->
            <div class="row g-3 mb-4">
                <!-- 1. CUSTOMERS -->
                <div class="col-xl-2 col-md-4 col-sm-6 d-flex">
                    <div class="jidox-card w-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="jidox-stat-label">CUSTOMERS</div>
                                <div class="jidox-stat-val">{{ number_format($totalUsers) }}</div>
                            </div>
                            <div class="jidox-icon-box jidox-icon-teal">
                                <i class="fa fa-users"></i>
                            </div>
                        </div>
                        <div class="jidox-badge-green">
                            <i class="fa fa-arrow-up font-size-10"></i> + 2,541 <span class="text-muted font-size-10 ms-1">Since last month</span>
                        </div>
                    </div>
                </div>

                <!-- 2. ORDERS -->
                <div class="col-xl-2 col-md-4 col-sm-6 d-flex">
                    <div class="jidox-card w-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="jidox-stat-label">ORDERS</div>
                                <div class="jidox-stat-val">{{ number_format($bazaarTotalOrders) }}</div>
                            </div>
                            <div class="jidox-icon-box jidox-icon-cyan">
                                <i class="fa fa-shopping-bag"></i>
                            </div>
                        </div>
                        <div class="jidox-badge-red">
                            <i class="fa fa-arrow-down font-size-10"></i> - 1.08% <span class="text-muted font-size-10 ms-1">Since last month</span>
                        </div>
                    </div>
                </div>

                <!-- 3. REVENUE -->
                <div class="col-xl-2 col-md-4 col-sm-6 d-flex">
                    <div class="jidox-card w-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="jidox-stat-label">REVENUE</div>
                                <div class="jidox-stat-val">৳ {{ number_format($grandTotalTurnover >= 1000000 ? $grandTotalTurnover / 1000 : $grandTotalTurnover, 0) }}{{ $grandTotalTurnover >= 1000000 ? 'k' : '' }}</div>
                            </div>
                            <div class="jidox-icon-box jidox-icon-coral">
                                <i class="fa fa-dollar"></i>
                            </div>
                        </div>
                        <div class="jidox-badge-red">
                            <i class="fa fa-arrow-down font-size-10"></i> - 7.00% <span class="text-muted font-size-10 ms-1">Since last month</span>
                        </div>
                    </div>
                </div>

                <!-- 4. GROWTH -->
                <div class="col-xl-2 col-md-4 col-sm-6 d-flex">
                    <div class="jidox-card w-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="jidox-stat-label">GROWTH</div>
                                <div class="jidox-stat-val">+ 20.6%</div>
                            </div>
                            <div class="jidox-icon-box jidox-icon-violet">
                                <i class="fa fa-refresh"></i>
                            </div>
                        </div>
                        <div class="jidox-badge-green">
                            <i class="fa fa-arrow-up font-size-10"></i> + 4.87% <span class="text-muted font-size-10 ms-1">Since last month</span>
                        </div>
                    </div>
                </div>

                <!-- 5. CONVERSATION -->
                <div class="col-xl-2 col-md-4 col-sm-6 d-flex">
                    <div class="jidox-card w-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="jidox-stat-label">CONVERSATION</div>
                                <div class="jidox-stat-val">9.62%</div>
                            </div>
                            <div class="jidox-icon-box jidox-icon-amber">
                                <i class="fa fa-line-chart"></i>
                            </div>
                        </div>
                        <div class="jidox-badge-green">
                            <i class="fa fa-arrow-up font-size-10"></i> + 3.07% <span class="text-muted font-size-10 ms-1">Since last month</span>
                        </div>
                    </div>
                </div>

                <!-- 6. BALANCE -->
                <div class="col-xl-2 col-md-4 col-sm-6 d-flex">
                    <div class="jidox-card w-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="jidox-stat-label">BALANCE</div>
                                <div class="jidox-stat-val">৳ {{ number_format($netVaultBalance >= 1000 ? $netVaultBalance / 1000 : $netVaultBalance, 1) }}k</div>
                            </div>
                            <div class="jidox-icon-box jidox-icon-slate">
                                <i class="fa fa-credit-card"></i>
                            </div>
                        </div>
                        <div class="jidox-badge-green">
                            <i class="fa fa-arrow-up font-size-10"></i> + 18.34% <span class="text-muted font-size-10 ms-1">Since last month</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- JIDOX Middle Row: Total Sales (Donut/Locations) & Revenue (Spline Area & Bar) -->
            <div class="row g-3 mb-4 align-items-stretch">
                <!-- Left: Total Sales -->
                <div class="col-xl-4 col-12 d-flex">
                    <div class="jidox-card w-100 h-100" style="min-height: 480px;">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="text-white fw-bold mb-0 font-size-16">Total Sales</h5>
                                <div class="text-muted cursor-pointer"><i class="fa fa-ellipsis-v"></i></div>
                            </div>

                            <!-- Radial Semicircle Gauge -->
                            <div class="jidox-gauge-wrapper">
                                <canvas id="jidoxSalesGauge" style="max-height: 190px; width: 100%; display: block;"></canvas>
                                <div class="position-absolute" style="top: 55%; left: 50%; transform: translate(-50%, -50%); pointer-events: none;">
                                    <h2 class="text-white fw-bold mb-0 font-size-26">67%</h2>
                                    <small class="text-muted font-size-11">Returning Customer</small>
                                </div>
                            </div>

                            <!-- Locations Performance Bars -->
                            <div class="mt-3 pt-2">
                                <div class="jidox-location-bar">
                                    <div class="jidox-location-label">
                                        <span>ঢাকা কেন্দ্রীয় হেড অফিস (Dhaka HQ)</span>
                                        <span class="text-white fw-bold">72k</span>
                                    </div>
                                    <div class="jidox-progress-track">
                                        <div class="jidox-progress-fill" style="width: 78%;"></div>
                                    </div>
                                </div>

                                <div class="jidox-location-bar">
                                    <div class="jidox-location-label">
                                        <span>চট্টগ্রাম আঞ্চলিক হাব (Chattogram Branch)</span>
                                        <span class="text-white fw-bold">39k</span>
                                    </div>
                                    <div class="jidox-progress-track">
                                        <div class="jidox-progress-fill" style="width: 48%;"></div>
                                    </div>
                                </div>

                                <div class="jidox-location-bar mb-0">
                                    <div class="jidox-location-label">
                                        <span>রাজশাহী এগ্রো সেন্টার (Rajshahi Agro)</span>
                                        <span class="text-white fw-bold">61k</span>
                                    </div>
                                    <div class="jidox-progress-track">
                                        <div class="jidox-progress-fill" style="width: 68%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Revenue Analytics Chart -->
                <div class="col-xl-8 col-12 d-flex">
                    <div class="jidox-card w-100 h-100" style="min-height: 480px;">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-white fw-bold mb-0 font-size-16">Revenue</h5>
                                <div class="text-muted cursor-pointer"><i class="fa fa-ellipsis-v"></i></div>
                            </div>

                            <!-- KPI Summary Row -->
                            <div class="row g-2 mb-3 pb-3" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                                <div class="col-3">
                                    <div class="jidox-kpi-item">
                                        <div class="jidox-kpi-title"><i class="fa fa-circle font-size-8 text-primary me-1"></i> Current Week</div>
                                        <div class="jidox-kpi-val">৳ 1,705.54</div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="jidox-kpi-item">
                                        <div class="jidox-kpi-title"><i class="fa fa-circle font-size-8 text-info me-1"></i> Previous Week</div>
                                        <div class="jidox-kpi-val">৳ 6,523.25 <i class="fa fa-arrow-up font-size-12 text-success ms-1"></i></div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="jidox-kpi-item">
                                        <div class="jidox-kpi-title"><i class="fa fa-circle font-size-8 text-warning me-1"></i> Conversation</div>
                                        <div class="jidox-kpi-val">8.27%</div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="jidox-kpi-item">
                                        <div class="jidox-kpi-title"><i class="fa fa-circle font-size-8 text-danger me-1"></i> Customers</div>
                                        <div class="jidox-kpi-val">69k <i class="fa fa-arrow-down font-size-12 text-danger ms-1"></i></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Combined Area & Bar Chart Container -->
                            <div class="jidox-chart-wrapper">
                                <canvas id="jidoxRevenueChart" style="height: 270px; width: 100%; display: block;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix" style="clear: both;"></div>

            <!-- JIDOX Bottom Row: Revenue By Locations & Top Selling Products -->
            <div class="row g-3 mb-4 align-items-stretch" style="clear: both; margin-top: 15px;">
                <!-- Revenue By Locations / Sectors -->
                <div class="col-xl-6 col-12 d-flex">
                    <div class="jidox-card w-100 h-100" style="min-height: 340px;">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-white fw-bold mb-0 font-size-16">Revenue By Locations & Sectors</h5>
                                <div class="text-muted cursor-pointer"><i class="fa fa-ellipsis-v"></i></div>
                            </div>
                            <div class="table-responsive">
                                <table class="table jidox-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>শাখা / বিনিয়োগ খাত</th>
                                            <th>অর্ডার সংখ্যা</th>
                                            <th>মোট টার্নওভার</th>
                                            <th>প্রবৃদ্ধি</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-white"><i class="fa fa-leaf text-success me-2"></i>এগ্রিকালচারাল ফার্মিং খাত</span>
                                                <div class="text-muted font-size-11">ঢাকা কেন্দ্রীয় প্রধান শাখা</div>
                                            </td>
                                            <td>1,240 টি</td>
                                            <td class="text-white fw-bold">৳ 4,820,000</td>
                                            <td><span class="jidox-badge-green">+ 12.4%</span></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-white"><i class="fa fa-building text-primary me-2"></i>জমি ও প্রপার্টি ডেভেলপমেন্ট</span>
                                                <div class="text-muted font-size-11">চট্টগ্রাম আঞ্চলিক হাব</div>
                                            </td>
                                            <td>380 টি</td>
                                            <td class="text-white fw-bold">৳ 8,450,000</td>
                                            <td><span class="jidox-badge-green">+ 18.2%</span></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-white"><i class="fa fa-cubes text-info me-2"></i>সেন্ট্রাল ক্যাটেল ফিড মিল</span>
                                                <div class="text-muted font-size-11">রাজশাহী বিভাগীয় শাখা</div>
                                            </td>
                                            <td>740 টি</td>
                                            <td class="text-white fw-bold">৳ 2,190,000</td>
                                            <td><span class="jidox-badge-green">+ 8.9%</span></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-white"><i class="fa fa-tint text-warning me-2"></i>অর্গানিক কোল্ড প্রেস অয়েল মিল</span>
                                                <div class="text-muted font-size-11">খুলনা কৃষি প্রক্রিয়াজাতকরণ হাব</div>
                                            </td>
                                            <td>520 টি</td>
                                            <td class="text-white fw-bold">৳ 1,650,000</td>
                                            <td><span class="jidox-badge-red">- 2.4%</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Selling Products -->
                <div class="col-xl-6 col-12 d-flex">
                    <div class="jidox-card w-100 h-100" style="min-height: 340px;">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-white fw-bold mb-0 font-size-16">Top Selling Products</h5>
                                <button class="btn btn-sm btn-primary rounded-pill px-3" style="background: #2563eb; border: none; font-size: 12px;">
                                    <i class="fa fa-download me-1"></i> Export
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table jidox-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>পণ্য ও সার্ভিস নাম</th>
                                            <th>একক মূল্য</th>
                                            <th>মোট বিক্রয়</th>
                                            <th>স্ট্যাটাস</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-white">প্রিমিয়াম খাঁটি সরিষার তেল (৫ লিটার)</span>
                                                <div class="text-muted font-size-11">মাসিক বাজার ক্যাটাগরি</div>
                                            </td>
                                            <td>৳ ১,৩৫০</td>
                                            <td class="text-white fw-bold">৮৪০ বোতল</td>
                                            <td><span class="badge bg-success bg-opacity-20 text-success font-size-11 px-2 py-1">স্টক ইন</span></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-white">নাজিরশাইল প্রিমিয়াম চাল (৫০ কেজি)</span>
                                                <div class="text-muted font-size-11">এগ্রো ফুড প্যাকেজ</div>
                                            </td>
                                            <td>৳ ৪,২০০</td>
                                            <td class="text-white fw-bold">৬১০ বস্তা</td>
                                            <td><span class="badge bg-success bg-opacity-20 text-success font-size-11 px-2 py-1">স্টক ইন</span></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-white">LandShare কাঠা শেয়ার (পূর্বাচল প্রজেক্ট)</span>
                                                <div class="text-muted font-size-11">রিয়েল এস্টেট ইনভেস্টমেন্ট</div>
                                            </td>
                                            <td>৳ ১,৫০,০০০</td>
                                            <td class="text-white fw-bold">৭৫ ইউনিট</td>
                                            <td><span class="badge bg-primary bg-opacity-20 text-primary font-size-11 px-2 py-1">বুকড</span></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-white">SME Gold ডিজিটাল মেম্বারশিপ কার্ড</span>
                                                <div class="text-muted font-size-11">করপোরেট কার্ড সুবিধা</div>
                                            </td>
                                            <td>৳ ১,০০০</td>
                                            <td class="text-white fw-bold">৯২০ টি</td>
                                            <td><span class="badge bg-warning bg-opacity-20 text-warning font-size-11 px-2 py-1">সক্রিয়</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /JIDOX EXECUTIVE DARK DASHBOARD -->

        <div class="my-5 pt-3 border-top border-secondary border-opacity-25" style="clear: both;"></div>

        <!-- Content Header -->
        <div class="content-header pt-2">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-primary"><i class="fa fa-university me-2"></i> প্ল্যাটফর্ম নিয়ন্ত্রণ ও সার্বিক খতিয়ান বিস্তারিত</h3>
                    <p class="text-muted mb-0">প্ল্যাটফর্মের মোট কেনাবেচা, মাসিক বাজার, জমি-প্রপার্টি, স্টক মার্কেট ও ডিপোজিট-উইথড্রর কেন্দ্রীয় হিসাব</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge badge-lg bg-success-light text-success fw-bold px-3 py-2"><i class="fa fa-circle text-success me-1"></i> সিস্টেম অনলাইন</span>
                    <span class="badge badge-lg bg-primary-light text-primary fw-bold px-3 py-2"><i class="fa fa-user-circle me-1"></i> {{ (in_array(strtolower(Auth::user()->role ?? ''), ['admin', 'super_admin']) || (Auth::user()->role_id ?? 0) == 1) ? 'ADMIN' : (Auth::user()->roleRelation->name ?? strtoupper(Auth::user()->role)) }}</span>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">

            <!-- ============================================================== -->
            <!-- SECTION 1: কেন্দ্রীয় আর্থিক ক্যালকুলেশন ও গ্র্যান্ড টার্নওভার ব্যানার -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-12">
                    <div class="box bg-gradient-primary text-white mb-4 shadow-sm" style="background: linear-gradient(135deg, #0d3b66 0%, #001e3d 100%) !important; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px;">
                        <div class="box-body p-4">
                            <div class="row align-items-center">
                                <div class="col-xl-4 col-12 border-end-xl mb-3 mb-xl-0">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="avatar avatar-lg bg-white-20 rounded-circle text-white me-3 d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.15); width: 54px; height: 54px;">
                                            <i class="fa fa-line-chart font-size-26 text-warning"></i>
                                        </div>
                                        <div>
                                            <span class="text-uppercase fw-bold text-white-50 font-size-12 tracking-wide">সর্বমোট প্ল্যাটফর্ম কেনাবেচা ও টার্নওভার</span>
                                            <h2 class="mb-0 fw-bold text-white font-size-30">৳ {{ number_format($grandTotalTurnover, 2) }}</h2>
                                        </div>
                                    </div>
                                    <p class="text-white-50 font-size-12 mb-0">
                                        <i class="fa fa-info-circle text-warning me-1"></i> মাসিক বাজার + স্টক কেনা + প্রপার্টি বুকিং + ডিপোজিট ভলিউম
                                    </p>
                                </div>

                                <div class="col-xl-8 col-12">
                                    <div class="row g-3">
                                        <div class="col-md-4 col-sm-6">
                                            <div class="p-3 rounded" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <span class="text-white-50 font-size-12 text-uppercase fw-bold">নেট ওয়ালেট রিজার্ভ (Vault)</span>
                                                        <h3 class="mb-0 fw-bold {{ $netVaultBalance >= 0 ? 'text-success' : 'text-danger' }}">
                                                            ৳ {{ number_format($netVaultBalance, 2) }}
                                                        </h3>
                                                    </div>
                                                    <div class="text-success font-size-22"><i class="fa fa-shield"></i></div>
                                                </div>
                                                <small class="text-white-50 font-size-11">অনুমোদিত ডিপোজিট - অনুমোদিত উইথড্র</small>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-sm-6">
                                            <div class="p-3 rounded" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <span class="text-white-50 font-size-12 text-uppercase fw-bold">অনুমোদিত ডিপোজিট</span>
                                                        <h3 class="mb-0 fw-bold text-info">৳ {{ number_format($totalDepositsApproved, 2) }}</h3>
                                                    </div>
                                                    <div class="text-info font-size-22"><i class="fa fa-arrow-circle-down"></i></div>
                                                </div>
                                                <small class="text-warning font-size-11">
                                                    @if($pendingDepositsCount > 0)
                                                        <i class="fa fa-clock-o"></i> {{ $pendingDepositsCount }} টি পেন্ডিং (৳ {{ number_format($pendingDepositsAmount, 2) }})
                                                    @else
                                                        <i class="fa fa-check"></i> সব ডিপোজিট প্রসেসড
                                                    @endif
                                                </small>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-sm-12">
                                            <div class="p-3 rounded" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <span class="text-white-50 font-size-12 text-uppercase fw-bold">অনুমোদিত উইথড্রয়াল</span>
                                                        <h3 class="mb-0 fw-bold text-white">৳ {{ number_format($totalWithdrawApproved, 2) }}</h3>
                                                    </div>
                                                    <div class="text-danger font-size-22"><i class="fa fa-arrow-circle-up"></i></div>
                                                </div>
                                                <small class="text-danger-light font-size-11">
                                                    @if($pendingWithdrawsCount > 0)
                                                        <i class="fa fa-exclamation-circle"></i> {{ $pendingWithdrawsCount }} টি রিকোয়েস্ট পেন্ডিং
                                                    @else
                                                        <i class="fa fa-check"></i> কোন পেন্ডিং নেই
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- SECTION 2: ৪টি মূল বাণিজ্য সেক্টরের বিশদ ক্যালকুলেশন কার্ড -->
            <!-- ============================================================== -->
            <div class="row">
                <!-- 1. মাসিক বাজার কেনাবেচা -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body border-top border-4 border-success pull-up shadow-sm h-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge bg-success-light text-success fw-bold font-size-12 mb-1">
                                    <i class="fa fa-shopping-basket me-1"></i> মাসিক বাজার কেনাবেচা
                                </span>
                                <h3 class="mb-0 fw-bold text-dark">৳ {{ number_format($bazaarTotalSales, 2) }}</h3>
                                <p class="text-muted font-size-12 mb-0">মোট অর্ডার ভলিউম</p>
                            </div>
                            <div class="icon bg-success text-white rounded p-3">
                                <i class="fa fa-shopping-cart font-size-22"></i>
                            </div>
                        </div>

                        <div class="border-top pt-2 mt-2">
                            <div class="d-flex justify-content-between font-size-12 mb-1">
                                <span class="text-muted">অনুমোদিত/ডেলিভার্ড বিক্রয়:</span>
                                <span class="fw-bold text-success">৳ {{ number_format($bazaarApprovedSales, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between font-size-12 mb-1">
                                <span class="text-muted">সর্বমোট অর্ডার:</span>
                                <span class="fw-bold text-dark">{{ $bazaarTotalOrders }} টি</span>
                            </div>
                            <div class="d-flex justify-content-between font-size-12 mb-2">
                                <span class="text-muted">পেন্ডিং ডেলিভারি:</span>
                                <span class="badge {{ $bazaarPendingOrders > 0 ? 'bg-warning text-dark' : 'bg-light text-muted' }}">
                                    {{ $bazaarPendingOrders }} টি অপেক্ষমান
                                </span>
                            </div>
                        </div>

                        <div class="d-grid gap-1 mt-auto pt-2">
                            <a href="{{ route('admin.monthly_bazaar.orders') }}" class="btn btn-sm btn-outline btn-success font-size-12">
                                <i class="fa fa-truck me-1"></i> অর্ডার ব্যবস্থাপনা ও ডেলিভারি
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 2. জমি ও প্রপার্টি কেনাবেচা -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body border-top border-4 border-primary pull-up shadow-sm h-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge bg-primary-light text-primary fw-bold font-size-12 mb-1">
                                    <i class="fa fa-building me-1"></i> জমি ও প্রপার্টি হিসাব
                                </span>
                                <h3 class="mb-0 fw-bold text-dark">৳ {{ number_format($propertyTotalValuation, 2) }}</h3>
                                <p class="text-muted font-size-12 mb-0">মোট লিস্টিং পোর্টফোলিও মূল্য</p>
                            </div>
                            <div class="icon bg-primary text-white rounded p-3">
                                <i class="fa fa-home font-size-22"></i>
                            </div>
                        </div>

                        <div class="border-top pt-2 mt-2">
                            <div class="d-flex justify-content-between font-size-12 mb-1">
                                <span class="text-muted">বুকিং ডিপোজিট ভলিউম:</span>
                                <span class="fw-bold text-primary">৳ {{ number_format($propertyBookingVolume, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between font-size-12 mb-1">
                                <span class="text-muted">মোট সাইট ও ইউনিট:</span>
                                <span class="fw-bold text-dark">{{ $propertyTotalCount }} টি ({{ $propertyAvailableCount }} খালি | {{ $propertyBookedCount }} বুকড)</span>
                            </div>
                            <div class="d-flex justify-content-between font-size-12 mb-2">
                                <span class="text-muted">গ্রাহক ইনকোয়ারি ও আবেদন:</span>
                                <span class="badge {{ $propertyPendingInquiries > 0 ? 'bg-info text-white' : 'bg-light text-muted' }}">
                                    {{ $propertyTotalInquiries }} টি ({{ $propertyPendingInquiries }} পেন্ডিং)
                                </span>
                            </div>
                        </div>

                        <div class="d-grid gap-1 mt-auto pt-2">
                            <a href="{{ route('admin.sales.bookings') }}" class="btn btn-sm btn-outline btn-primary font-size-12">
                                <i class="fa fa-shopping-bag me-1"></i> বিক্রি, বুকিং ও কিস্তি খতিয়ান
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 3. স্টক মার্কেট ট্রেডিং -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body border-top border-4 border-info pull-up shadow-sm h-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge bg-info-light text-info fw-bold font-size-12 mb-1">
                                    <i class="fa fa-line-chart me-1"></i> স্টক মার্কেট ট্রেডিং
                                </span>
                                <h3 class="mb-0 fw-bold text-dark">৳ {{ number_format($totalStockBuy, 2) }}</h3>
                                <p class="text-muted font-size-12 mb-0">মোট অনুমোদিত স্টক ক্রয়</p>
                            </div>
                            <div class="icon bg-info text-white rounded p-3">
                                <i class="fa fa-cubes font-size-22"></i>
                            </div>
                        </div>

                        <div class="border-top pt-2 mt-2">
                            <div class="d-flex justify-content-between font-size-12 mb-1">
                                <span class="text-muted">মোট স্টক বিক্রয়:</span>
                                <span class="fw-bold text-info">৳ {{ number_format($totalStockSell, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between font-size-12 mb-1">
                                <span class="text-muted">গ্রাহকদের অর্জিত লাভ:</span>
                                <span class="fw-bold text-success">৳ {{ number_format($totalProfitDistributed, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between font-size-12 mb-2">
                                <span class="text-muted">পেন্ডিং রিকোয়েস্ট:</span>
                                <span class="badge {{ ($pendingStockBuy + $pendingStockSell) > 0 ? 'bg-warning text-dark' : 'bg-light text-muted' }}">
                                    বাই: {{ $pendingStockBuy }} | সেল: {{ $pendingStockSell }}
                                </span>
                            </div>
                        </div>

                        <div class="d-grid gap-1 mt-auto pt-2">
                            <a href="{{ route('admin.stock.buyrequest.list') }}" class="btn btn-sm btn-outline btn-info font-size-12">
                                <i class="fa fa-exchange me-1"></i> স্টক লেনদেন ও রিকোয়েস্ট
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 4. ডিপোজিট ও ওয়ালেট ক্যাশ-ফ্লো -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body border-top border-4 border-warning pull-up shadow-sm h-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge bg-warning-light text-dark fw-bold font-size-12 mb-1">
                                    <i class="fa fa-money me-1"></i> ডিপোজিট ও ওয়ালেট ফান্ড
                                </span>
                                <h3 class="mb-0 fw-bold text-dark">৳ {{ number_format($totalDepositsApproved, 2) }}</h3>
                                <p class="text-muted font-size-12 mb-0">মোট সংগৃহীত সফল ডিপোজিট</p>
                            </div>
                            <div class="icon bg-warning text-white rounded p-3">
                                <i class="fa fa-university font-size-22"></i>
                            </div>
                        </div>

                        <div class="border-top pt-2 mt-2">
                            <div class="d-flex justify-content-between font-size-12 mb-1">
                                <span class="text-muted">মোট সফল উত্তোলন:</span>
                                <span class="fw-bold text-danger">৳ {{ number_format($totalWithdrawApproved, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between font-size-12 mb-1">
                                <span class="text-muted">পেন্ডিং ডিপোজিট:</span>
                                <span class="fw-bold {{ $pendingDepositsCount > 0 ? 'text-warning' : 'text-muted' }}">
                                    ৳ {{ number_format($pendingDepositsAmount, 2) }} ({{ $pendingDepositsCount }} টি)
                                </span>
                            </div>
                            <div class="d-flex justify-content-between font-size-12 mb-2">
                                <span class="text-muted">পেন্ডিং উইথড্রয়াল:</span>
                                <span class="badge {{ $pendingWithdrawsCount > 0 ? 'bg-danger text-white' : 'bg-light text-muted' }}">
                                    ৳ {{ number_format($pendingWithdrawsAmount, 2) }} ({{ $pendingWithdrawsCount }} টি)
                                </span>
                            </div>
                        </div>

                        <div class="d-grid gap-1 mt-auto pt-2">
                            <a href="{{ route('admin.deposit.index') }}" class="btn btn-sm btn-outline btn-warning font-size-12">
                                <i class="fa fa-credit-card me-1"></i> ডিপোজিট অনুমোদন প্যানেল
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- SECTION 3: লাইভ বাণিজ্যিক ও আর্থিক খতিয়ান ট্যাব (Recent Activities) -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h4 class="box-title mb-0">
                                <i class="fa fa-file-text-o text-primary me-2"></i> সাম্প্রতিক প্ল্যাটফর্ম লেনদেন ও বাণিজ্যিক খতিয়ান (Live Commercial Ledger)
                            </h4>
                            <ul class="nav nav-pills custom-pills" id="financialTabs" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active font-size-12 py-1 px-3" id="bazaar-tab" data-bs-toggle="pill" data-bs-target="#tab-bazaar" type="button" role="tab">
                                        <i class="fa fa-shopping-basket me-1"></i> মাসিক বাজার অর্ডার ({{ $recentBazaarOrders->count() }})
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link font-size-12 py-1 px-3" id="property-tab" data-bs-toggle="pill" data-bs-target="#tab-property" type="button" role="tab">
                                        <i class="fa fa-building me-1"></i> প্রপার্টি বুকিং/ইনকোয়ারি ({{ $recentPropertyInquiries->count() }})
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link font-size-12 py-1 px-3" id="deposit-tab" data-bs-toggle="pill" data-bs-target="#tab-deposit" type="button" role="tab">
                                        <i class="fa fa-arrow-down me-1"></i> ডিপোজিট খতিয়ান ({{ $recentDeposits->count() }})
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link font-size-12 py-1 px-3" id="withdraw-tab" data-bs-toggle="pill" data-bs-target="#tab-withdraw" type="button" role="tab">
                                        <i class="fa fa-arrow-up me-1"></i> উইথড্রয়াল খতিয়ান ({{ $recentWithdraws->count() }})
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div class="box-body p-0">
                            <div class="tab-content" id="financialTabsContent">
                                <!-- Tab 1: মাসিক বাজার অর্ডার -->
                                <div class="tab-pane fade show active" id="tab-bazaar" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped mb-0 font-size-13">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>অর্ডার আইডি</th>
                                                    <th>গ্রাহকের নাম ও ফোন</th>
                                                    <th>প্যাকেজ/আইটেম</th>
                                                    <th>পরিমাণ</th>
                                                    <th>মোট মূল্য</th>
                                                    <th>পেমেন্ট মেথড</th>
                                                    <th>স্ট্যাটাস</th>
                                                    <th>তারিখ ও সময়</th>
                                                    <th class="text-end">অ্যাকশন</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($recentBazaarOrders as $order)
                                                <tr>
                                                    <td><code>#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</code></td>
                                                    <td>
                                                        <span class="fw-bold text-dark">{{ $order->user->name ?? 'অজ্ঞাত গ্রাহক' }}</span>
                                                        <br><small class="text-muted">{{ $order->user->phone ?? $order->pay_from_number }}</small>
                                                    </td>
                                                    <td>{{ $order->package_title ?? 'মাসিক বাজার প্যাকেজ' }}</td>
                                                    <td><span class="badge bg-light text-dark">{{ $order->quantity }} টি</span></td>
                                                    <td class="fw-bold text-success">৳ {{ number_format($order->total_price, 2) }}</td>
                                                    <td><span class="badge bg-info-light text-info">{{ strtoupper($order->payment_method ?? 'N/A') }}</span></td>
                                                    <td>
                                                        @if(in_array($order->status, ['approved', 'completed', 'delivered']))
                                                            <span class="badge bg-success">সম্পন্ন</span>
                                                        @elseif($order->status === 'pending')
                                                            <span class="badge bg-warning text-dark">অপেক্ষমান</span>
                                                        @else
                                                            <span class="badge bg-danger">{{ $order->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-muted">{{ $order->created_at ? $order->created_at->format('d M, Y h:i A') : '-' }}</td>
                                                    <td class="text-end">
                                                        <a href="{{ route('admin.monthly_bazaar.orders') }}" class="btn btn-xs btn-primary">বিস্তারিত</a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="9" class="text-center py-4 text-muted">
                                                        <i class="fa fa-info-circle me-1"></i> এখনো কোনো মাসিক বাজার অর্ডার পাওয়া যায়নি।
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Tab 2: প্রপার্টি ও জমি বুকিং/ইনকোয়ারি -->
                                <div class="tab-pane fade" id="tab-property" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped mb-0 font-size-13">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>আবেদন কোড</th>
                                                    <th>গ্রাহকের নাম ও ফোন</th>
                                                    <th>প্রপার্টি ও সাইট</th>
                                                    <th>আবেদনের ধরন</th>
                                                    <th>পছন্দের তারিখ</th>
                                                    <th>স্ট্যাটাস</th>
                                                    <th>আবেদনের সময়</th>
                                                    <th class="text-end">অ্যাকশন</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($recentPropertyInquiries as $inq)
                                                <tr>
                                                    <td><code>{{ $inq->inquiry_code ?? '#INQ-'.$inq->id }}</code></td>
                                                    <td>
                                                        <span class="fw-bold text-dark">{{ $inq->customer_name }}</span>
                                                        <br><small class="text-muted"><i class="fa fa-phone me-1"></i>{{ $inq->customer_phone }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="fw-bold text-primary">{{ $inq->property->property_name ?? 'জমি ও প্রপার্টি' }}</span>
                                                        <br><small class="text-muted">{{ $inq->property->district ?? '' }} {{ $inq->property->upazila ?? '' }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary">
                                                            {{ $inq->inquiry_type == 'booking' ? 'সরাসরি বুকিং' : ($inq->inquiry_type == 'visit' ? 'সাইট ভিজিট' : 'সাধারণ তথ্য') }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $inq->preferred_date ?? 'উল্লেখ নেই' }}</td>
                                                    <td>
                                                        @if($inq->status === 'confirmed')
                                                            <span class="badge bg-success">অনুমোদিত/বুকড</span>
                                                        @elseif($inq->status === 'pending')
                                                            <span class="badge bg-warning text-dark">অপেক্ষমান</span>
                                                        @else
                                                            <span class="badge bg-info">{{ ucfirst($inq->status) }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-muted">{{ $inq->created_at ? $inq->created_at->format('d M, Y h:i A') : '-' }}</td>
                                                    <td class="text-end">
                                                        <a href="{{ route('admin.sales.bookings') }}" class="btn btn-xs btn-outline btn-primary">খতিয়ান দেখুন</a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="8" class="text-center py-4 text-muted">
                                                        <i class="fa fa-info-circle me-1"></i> কোনো প্রপার্টি বুকিং বা আবেদন পাওয়া যায়নি।
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Tab 3: সাম্প্রতিক ডিপোজিট -->
                                <div class="tab-pane fade" id="tab-deposit" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped mb-0 font-size-13">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>আইডি</th>
                                                    <th>ব্যবহারকারী</th>
                                                    <th>টাকার পরিমাণ</th>
                                                    <th>গেটওয়ে</th>
                                                    <th>প্রেরক মোবাইল</th>
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
                                                    <td><span class="badge bg-primary-light text-primary">{{ strtoupper($dep->payment_method ?? 'Bank/MFS') }}</span></td>
                                                    <td><code>{{ $dep->pay_from_number ?? '-' }}</code></td>
                                                    <td><code>{{ $dep->trx_number ?? '-' }}</code></td>
                                                    <td>
                                                        @if(in_array($dep->status, ['approved', 'aproved']))
                                                            <span class="badge bg-success">অনুমোদিত</span>
                                                        @elseif($dep->status === 'pending')
                                                            <span class="badge bg-warning text-dark">অপেক্ষমান</span>
                                                        @else
                                                            <span class="badge bg-danger">{{ $dep->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-muted">{{ $dep->created_at ? $dep->created_at->format('d M, Y') : '-' }}</td>
                                                    <td class="text-end">
                                                        <a href="{{ route('admin.deposit.index') }}" class="btn btn-xs btn-warning">রিভিউ</a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="9" class="text-center py-4 text-muted">
                                                        <i class="fa fa-info-circle me-1"></i> কোনো ডিপোজিট রেকর্ড পাওয়া যায়নি।
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Tab 4: সাম্প্রতিক উইথড্র -->
                                <div class="tab-pane fade" id="tab-withdraw" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped mb-0 font-size-13">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>আইডি</th>
                                                    <th>ব্যবহারকারী</th>
                                                    <th>উত্তোলনের পরিমাণ</th>
                                                    <th>স্ট্যাটাস</th>
                                                    <th>তারিখ ও সময়</th>
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
                                                            <span class="badge bg-success">পরিশোধিত</span>
                                                        @elseif($w->status === 'pending')
                                                            <span class="badge bg-danger">উত্তোলন অপেক্ষমান</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ $w->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-muted">{{ $w->created_at ? $w->created_at->format('d M, Y h:i A') : '-' }}</td>
                                                    <td class="text-end">
                                                        <a href="{{ route('admin.all.withdraw.request') }}" class="btn btn-xs btn-danger">অনুমোদন</a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4 text-muted">
                                                        <i class="fa fa-info-circle me-1"></i> কোনো উত্তোলন রেকর্ড পাওয়া যায়নি।
                                                    </td>
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
            </div>

            <!-- ============================================================== -->
            <!-- SECTION 4: সিস্টেম ব্যবহারকারী ও RBAC নিয়ন্ত্রণ কার্ডসমূহ -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body pull-up bg-primary-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 text-uppercase fw-bold font-size-12">মোট ব্যবহারকারী (Users)</p>
                                <h2 class="mb-0 fw-bold text-primary">{{ number_format($totalUsers) }}</h2>
                                <small class="text-success"><i class="fa fa-check-circle"></i> {{ $activeUsers }} সক্রিয় | {{ $inactiveUsers }} নিষ্ক্রিয়</small>
                            </div>
                            <div class="icon bg-primary text-white rounded-circle p-3">
                                <i class="fa fa-users font-size-24"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body pull-up bg-info-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 text-uppercase fw-bold font-size-12">রোল ও পারমিশন (RBAC)</p>
                                <h2 class="mb-0 fw-bold text-info">{{ $rolesCount }} <span class="font-size-16 fw-normal">টি রোল</span></h2>
                                <small class="text-info"><i class="fa fa-lock"></i> Least Privilege Enforced</small>
                            </div>
                            <div class="icon bg-info text-white rounded-circle p-3">
                                <i class="fa fa-key font-size-24"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body pull-up bg-warning-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 text-uppercase fw-bold font-size-12">সাপোর্ট টিকেট (Active)</p>
                                <h2 class="mb-0 fw-bold text-warning">{{ $openTicketsCount }}</h2>
                                <small class="text-dark"><i class="fa fa-clock-o"></i> ইন-প্রগ্রেস ও উন্মুক্ত টিকেট</small>
                            </div>
                            <div class="icon bg-warning text-white rounded-circle p-3">
                                <i class="fa fa-headphones font-size-24"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body pull-up bg-danger-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 text-uppercase fw-bold font-size-12">সাইবার অ্যালার্ট (Alerts)</p>
                                <h2 class="mb-0 fw-bold text-danger">{{ $activeAlertsCount }}</h2>
                                <small class="text-danger"><i class="fa fa-exclamation-triangle"></i> থ্রেট ডিটেকশন ও ফ্রড চেক</small>
                            </div>
                            <div class="icon bg-danger text-white rounded-circle p-3">
                                <i class="fa fa-shield font-size-24"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Access RBAC & Modules -->
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title"><i class="fa fa-th-large text-primary me-2"></i> আন্তর্জাতিক মানের RBAC কন্ট্রোল ও ডিরেক্টরি মডিউল</h4>
                        </div>
                        <div class="box-body">
                            <div class="row g-3">
                                <div class="col-md-3 col-sm-6">
                                    <a href="{{ route('admin.rbac.users') }}" class="card text-center p-4 border pull-up text-decoration-none h-100">
                                        <div class="avatar avatar-lg bg-primary-light text-primary mx-auto mb-3">
                                            <i class="fa fa-user-plus font-size-24"></i>
                                        </div>
                                        <h5 class="fw-bold mb-1 text-dark">User Management</h5>
                                        <p class="text-muted font-size-13 mb-0">ব্যবহারকারী তালিকা, নতুন ইউজার যোগ ও রোল এসাইন</p>
                                    </a>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <a href="{{ route('admin.rbac.roles') }}" class="card text-center p-4 border pull-up text-decoration-none h-100">
                                        <div class="avatar avatar-lg bg-info-light text-info mx-auto mb-3">
                                            <i class="fa fa-sliders font-size-24"></i>
                                        </div>
                                        <h5 class="fw-bold mb-1 text-dark">Roles & Permissions</h5>
                                        <p class="text-muted font-size-13 mb-0">রোল ক্যাটালগ, ক্লোন ও গ্র্যানুলার পারমিশন ট্রি</p>
                                    </a>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <a href="{{ route('admin.rbac.security_hub') }}" class="card text-center p-4 border pull-up text-decoration-none h-100">
                                        <div class="avatar avatar-lg bg-danger-light text-danger mx-auto mb-3">
                                            <i class="fa fa-user-secret font-size-24"></i>
                                        </div>
                                        <h5 class="fw-bold mb-1 text-dark">Security & Cyber Alerts</h5>
                                        <p class="text-muted font-size-13 mb-0">লাইভ অ্যালার্ট, ফ্রড চেক ও আইপি ব্লকলিস্ট</p>
                                    </a>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <a href="{{ route('admin.rbac.audit_logs') }}" class="card text-center p-4 border pull-up text-decoration-none h-100">
                                        <div class="avatar avatar-lg bg-success-light text-success mx-auto mb-3">
                                            <i class="fa fa-history font-size-24"></i>
                                        </div>
                                        <h5 class="fw-bold mb-1 text-dark">Audit Trail & Logs</h5>
                                        <p class="text-muted font-size-13 mb-0">কে কখন কি পরিবর্তন করেছে তার অপরিবর্তনীয় অডিট লগ</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role Distribution & Live Audit Trail -->
            <div class="row">
                <!-- Role Distribution -->
                <div class="col-xl-5 col-12">
                    <div class="box">
                        <div class="box-header with-border d-flex justify-content-between align-items-center">
                            <h4 class="box-title"><i class="fa fa-pie-chart text-info me-2"></i> রোল অনুযায়ী সদস্য সংখ্যা (Role Distribution)</h4>
                            <a href="{{ route('admin.rbac.roles') }}" class="btn btn-xs btn-primary">সব রোল দেখুন</a>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>রোল নাম</th>
                                            <th>ক্যাটাগরি</th>
                                            <th>ডাটা স্কোপ</th>
                                            <th class="text-end">সদস্য</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($roles as $r)
                                        <tr>
                                            <td class="fw-bold">
                                                <span class="badge bg-primary-light text-primary me-1">{{ $r->name }}</span>
                                            </td>
                                            <td><span class="badge {{ $r->category === 'Internal' ? 'bg-info' : 'bg-secondary' }}">{{ $r->category }}</span></td>
                                            <td><code>{{ $r->data_scope }}</code></td>
                                            <td class="text-end"><span class="badge bg-light text-dark fw-bold">{{ $r->users_count }} জন</span></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Privileged Audit Trail -->
                <div class="col-xl-7 col-12">
                    <div class="box">
                        <div class="box-header with-border d-flex justify-content-between align-items-center">
                            <h4 class="box-title"><i class="fa fa-shield text-success me-2"></i> সর্বশেষ প্রশাসনিক অ্যাকশন (Audit Log Trail)</h4>
                            <a href="{{ route('admin.rbac.audit_logs') }}" class="btn btn-xs btn-outline btn-success">সম্পূর্ণ লগ দেখুন</a>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 font-size-13">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>সময়</th>
                                            <th>অ্যাক্টর (Actor)</th>
                                            <th>অ্যাকশন (Action)</th>
                                            <th>আইপি এড্রেস</th>
                                            <th>কারণ/বিবরণ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentAuditLogs as $log)
                                        <tr>
                                            <td class="text-nowrap text-muted">{{ $log->created_at->diffForHumans() }}</td>
                                            <td>
                                                <span class="fw-bold text-dark">{{ $log->actor_name }}</span>
                                                <br><small class="text-muted">{{ $log->actor_role }}</small>
                                            </td>
                                            <td><span class="badge bg-primary-light text-primary">{{ $log->action }}</span></td>
                                            <td><code>{{ $log->ip_address }}</code></td>
                                            <td>{{ Str::limit($log->reason, 40) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">কোন অডিট রেকর্ড নেই।</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Users Table -->
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border d-flex justify-content-between align-items-center">
                            <h4 class="box-title"><i class="fa fa-user-circle text-primary me-2"></i> সাম্প্রতিক রেজিস্টার্ড ব্যবহারকারী (Recent Users)</h4>
                            <a href="{{ route('admin.rbac.users') }}" class="btn btn-sm btn-primary"><i class="fa fa-users me-1"></i> সমস্ত ইউজার পরিচালনা</a>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>নাম ও প্রোফাইল</th>
                                            <th>ইউজারনেম</th>
                                            <th>মোবাইল / ইমেইল</th>
                                            <th>ইউজার টাইপ</th>
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
                                                    <img src="{{ !empty($u->avatar) && file_exists(public_path('upload/images/user/' . $u->avatar)) ? asset('upload/images/user/' . $u->avatar) : asset('assets/images/avatar/avatar-1.png') }}" class="avatar avatar-sm rounded-circle me-2" alt="">
                                                    <span class="fw-bold text-dark">{{ $u->name }}</span>
                                                </div>
                                            </td>
                                            <td><code>{{ $u->username }}</code></td>
                                            <td>
                                                <div>{{ $u->phone }}</div>
                                                <small class="text-muted">{{ $u->email }}</small>
                                            </td>
                                            <td><span class="badge bg-secondary">{{ $u->userType->name ?? 'Default' }}</span></td>
                                            <td><span class="badge bg-info-light text-info fw-bold">{{ $u->roleRelation->name ?? strtoupper($u->role) }}</span></td>
                                            <td>
                                                @if($u->status === 'active')
                                                    <span class="badge bg-success">সক্রিয় (Active)</span>
                                                @else
                                                    <span class="badge bg-danger">নিষ্ক্রিয় ({{ $u->status }})</span>
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

        </section>
    </div>
</div>

<!-- JIDOX Analytics Charts Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Semicircle Sales Radial Gauge
    const gaugeCanvas = document.getElementById('jidoxSalesGauge');
    if (gaugeCanvas && typeof Chart !== 'undefined') {
        new Chart(gaugeCanvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [67, 33],
                    backgroundColor: ['#4f46e5', 'rgba(255, 255, 255, 0.08)'],
                    borderWidth: 0,
                    borderRadius: [8, 0]
                }]
            },
            options: {
                circumference: 240,
                rotation: -120,
                cutout: '80%',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: { enabled: false },
                    legend: { display: false }
                }
            }
        });
    }

    // 2. Revenue Spline Area + Sales Bar Chart
    const revCanvas = document.getElementById('jidoxRevenueChart');
    if (revCanvas && typeof Chart !== 'undefined') {
        new Chart(revCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Jan 01', '02 Jan', '03 Jan', '04 Jan', '05 Jan', '06 Jan', '07 Jan', '08 Jan', '09 Jan', '10 Jan', '11 Jan', '12 Jan'],
                datasets: [
                    {
                        type: 'line',
                        label: 'Revenue',
                        data: [420, 480, 510, 450, 600, 430, 490, 710, 520, 840, 630, 720],
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.25)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#6366f1',
                        pointRadius: 3,
                        order: 1
                    },
                    {
                        type: 'bar',
                        label: 'Sales',
                        data: [320, 420, 260, 380, 680, 360, 290, 550, 390, 410, 320, 280],
                        backgroundColor: '#14b8a6',
                        borderRadius: 4,
                        barPercentage: 0.4,
                        order: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            color: '#94a3b8',
                            usePointStyle: true,
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1,
                        padding: 10
                    }
                },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#64748b', font: { size: 11 } }
                    },
                    y: {
                        grid: { color: 'rgba(255, 255, 255, 0.05)', drawBorder: false },
                        ticks: { color: '#64748b', font: { size: 11 } }
                    }
                }
            }
        });
    }
});
</script>
@endsection
