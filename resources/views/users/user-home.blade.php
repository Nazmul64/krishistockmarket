@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content py-3">

            <!-- Scoped Custom Styles for Customer Dashboard -->
            <style>
                :root {
                    --ekrishi-primary: #10b981;
                    --ekrishi-primary-dark: #047857;
                    --ekrishi-navy: #0f172a;
                    --ekrishi-blue: #2563eb;
                    --ekrishi-amber: #f59e0b;
                    --ekrishi-card-bg: #ffffff;
                }

                .cust-dash-banner {
                    background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #047857 100%);
                    border-radius: 20px;
                    padding: 26px 28px;
                    color: #ffffff;
                    position: relative;
                    overflow: hidden;
                    box-shadow: 0 10px 25px -5px rgba(6, 78, 59, 0.25);
                    margin-bottom: 24px;
                }
                .cust-dash-banner::before {
                    content: '';
                    position: absolute;
                    top: -50px;
                    right: -50px;
                    width: 200px;
                    height: 200px;
                    background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
                    border-radius: 50%;
                    pointer-events: none;
                }

                /* Digital Virtual Card */
                .ekrishi-digital-card {
                    background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #0f172a 100%);
                    border: 1px solid rgba(245, 158, 11, 0.35);
                    border-radius: 16px;
                    padding: 18px 22px;
                    color: #ffffff;
                    min-width: 290px;
                    max-width: 360px;
                    width: 100%;
                    box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.4);
                    position: relative;
                }
                .ekrishi-card-chip {
                    width: 36px;
                    height: 26px;
                    background: linear-gradient(135deg, #ffd700 0%, #e6b800 100%);
                    border-radius: 5px;
                    display: inline-block;
                    position: relative;
                    box-shadow: inset 0 0 2px rgba(0,0,0,0.4);
                }
                .ekrishi-card-number {
                    font-family: 'Courier New', Courier, monospace;
                    font-size: 16px;
                    font-weight: 700;
                    letter-spacing: 2px;
                    color: #fbbf24;
                    margin: 10px 0 6px 0;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                }

                /* Quick Action Cards */
                .action-tile {
                    background: #ffffff;
                    border: 1px solid #e2e8f0;
                    border-radius: 16px;
                    padding: 18px 14px;
                    text-align: center;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    text-decoration: none !important;
                    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
                    height: 100%;
                    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
                    position: relative;
                }
                .action-tile:hover {
                    transform: translateY(-4px);
                    box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.1);
                    border-color: #cbd5e1;
                }
                .action-icon-wrap {
                    width: 52px;
                    height: 52px;
                    border-radius: 14px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 22px;
                    margin-bottom: 12px;
                    transition: transform 0.25s ease;
                }
                .action-tile:hover .action-icon-wrap {
                    transform: scale(1.08);
                }
                .action-title {
                    font-size: 14px;
                    font-weight: 700;
                    color: #1e293b;
                    margin-bottom: 2px;
                    line-height: 1.2;
                }
                .action-subtitle {
                    font-size: 11px;
                    color: #64748b;
                    line-height: 1.2;
                }

                /* Metric Stat Cards */
                .metric-stat-card {
                    background: #ffffff;
                    border: 1px solid #edf2f7;
                    border-radius: 16px;
                    padding: 20px;
                    position: relative;
                    overflow: hidden;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
                    height: 100%;
                    transition: transform 0.2s ease, box-shadow 0.2s ease;
                }
                .metric-stat-card:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
                }
                .metric-icon-badge {
                    width: 48px;
                    height: 48px;
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 20px;
                    flex-shrink: 0;
                }
                .metric-label {
                    font-size: 12px;
                    font-weight: 600;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    color: #64748b;
                    margin-bottom: 4px;
                }
                .metric-value {
                    font-size: 24px;
                    font-weight: 800;
                    color: #0f172a;
                    margin-bottom: 0;
                    line-height: 1.2;
                }

                /* Activity Tables & Modern Cards */
                .content-card-modern {
                    background: #ffffff;
                    border: 1px solid #edf2f7;
                    border-radius: 16px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
                    overflow: hidden;
                }
                .content-card-modern .card-header-styled {
                    padding: 16px 20px;
                    background: #ffffff;
                    border-bottom: 1px solid #f1f5f9;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                }
                .nav-pills-modern .nav-link {
                    border-radius: 10px;
                    font-size: 13px;
                    font-weight: 600;
                    color: #64748b;
                    padding: 8px 16px;
                    margin-right: 6px;
                    transition: all 0.2s ease;
                }
                .nav-pills-modern .nav-link.active {
                    background: #10b981;
                    color: #ffffff;
                    box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
                }

                /* Table Styling */
                .custom-dash-table th {
                    font-size: 11px;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    color: #64748b;
                    font-weight: 700;
                    background: #f8fafc;
                    border-top: none;
                    border-bottom: 1px solid #e2e8f0;
                    padding: 12px 16px;
                }
                .custom-dash-table td {
                    padding: 14px 16px;
                    vertical-align: middle;
                    font-size: 13px;
                    border-bottom: 1px solid #f1f5f9;
                    color: #1e293b;
                }

                /* Custom Badges */
                .badge-status {
                    padding: 5px 10px;
                    border-radius: 20px;
                    font-size: 11px;
                    font-weight: 600;
                    display: inline-flex;
                    align-items: center;
                    gap: 4px;
                }
                .badge-status-approved {
                    background: #dcfce7;
                    color: #15803d;
                }
                .badge-status-pending {
                    background: #fef3c7;
                    color: #b45309;
                }
                .badge-status-completed {
                    background: #e0e7ff;
                    color: #4338ca;
                }
                .badge-status-rejected {
                    background: #fee2e2;
                    color: #b91c1c;
                }

                /* Support Box */
                .support-promo-card {
                    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
                    border-radius: 16px;
                    padding: 22px;
                    color: #ffffff;
                    position: relative;
                    overflow: hidden;
                }
                .support-promo-card::after {
                    content: '';
                    position: absolute;
                    bottom: -30px;
                    right: -30px;
                    width: 120px;
                    height: 120px;
                    background: radial-gradient(circle, rgba(16, 185, 129, 0.2) 0%, transparent 70%);
                    border-radius: 50%;
                }

                @media (max-width: 767px) {
                    .cust-dash-banner {
                        padding: 18px 16px;
                    }
                    .ekrishi-digital-card {
                        max-width: 100%;
                        margin-top: 16px;
                    }
                    .metric-value {
                        font-size: 20px;
                    }
                    .action-tile {
                        padding: 14px 8px;
                    }
                    .action-icon-wrap {
                        width: 44px;
                        height: 44px;
                        font-size: 18px;
                    }
                }
            </style>

            <!-- 1. Customer Welcome Header & Digital Card -->
            <div class="cust-dash-banner">
                <div class="row align-items-center">
                    <!-- Left: Greetings & Member Meta -->
                    <div class="col-lg-7 col-md-6 mb-3 mb-md-0">
                        <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                            <span class="badge bg-warning text-dark px-2 py-1 fw-bold rounded-pill" style="font-size: 11px;">
                                <i class="fa fa-shield me-1"></i> ভেরিফায়েড গ্রাহক (Verified)
                            </span>
                            <span class="badge bg-white bg-opacity-25 text-white px-2 py-1 rounded-pill" style="font-size: 11px;">
                                Customer ID: #{{ Auth::id() }}
                            </span>
                        </div>
                        <h2 class="fw-bold mb-1" style="letter-spacing: -0.5px;">
                            স্বাগতম, {{ Auth::user()->name }}! 👋
                        </h2>
                        <p class="mb-3 text-white-50 small">
                            <i class="fa fa-phone me-1"></i>মোবাইল: {{ Auth::user()->phone ?? Auth::user()->username }} &nbsp;|&nbsp; 
                            <i class="fa fa-calendar me-1"></i>সদস্য শুরু: {{ Auth::user()->created_at ? Auth::user()->created_at->format('d M, Y') : 'Active' }}
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('user.deposit.index') }}" class="btn btn-warning text-dark fw-bold px-3 py-2 rounded-pill shadow-sm" style="font-size: 13px;">
                                <i class="fa fa-plus-circle me-1"></i> ব্যালেন্স যোগ করুন (Deposit)
                            </a>
                            <a href="{{ route('withdraw.form') }}" class="btn btn-outline-light px-3 py-2 rounded-pill fw-semibold" style="font-size: 13px;">
                                <i class="fa fa-arrow-circle-up me-1"></i> টাকা উত্তোলন (Withdraw)
                            </a>
                        </div>
                    </div>

                    <!-- Right: Digital E-Krishi Card -->
                    <div class="col-lg-5 col-md-6 d-flex justify-content-md-end justify-content-center">
                        <div class="ekrishi-digital-card">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-success bg-opacity-25 text-success px-2 py-1 rounded small fw-bold" style="border: 1px solid rgba(16,185,129,0.3);">
                                    <i class="fa fa-leaf me-1"></i> e-Krishi Member
                                </span>
                                <i class="fa fa-wifi text-warning opacity-75" style="transform: rotate(90deg); font-size: 14px;"></i>
                            </div>

                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="ekrishi-card-chip"></div>
                                <span class="text-white-50" style="font-size: 10px; text-transform: uppercase; letter-spacing: 1px;">Smart Agro Card</span>
                            </div>

                            <div class="ekrishi-card-number">
                                @php
                                    $rawCard = GetUserCardNumber(Auth::id());
                                    $formattedCard = chunk_split(str_replace(' ', '', $rawCard), 4, ' ');
                                @endphp
                                <span>{{ $formattedCard }}</span>
                                <button type="button" onclick="navigator.clipboard.writeText('{{ $rawCard }}'); alert('কার্ড নম্বর কপি করা হয়েছে!');" class="btn btn-sm btn-outline-secondary p-0 px-1 text-white-50 border-0" title="Copy Card Number">
                                    <i class="fa fa-clone"></i>
                                </button>
                            </div>

                            <div class="d-flex justify-content-between align-items-end mt-2 pt-2 border-top border-secondary border-opacity-25">
                                <div>
                                    <small class="text-white-50 d-block" style="font-size: 9px; text-transform: uppercase;">Card Holder</small>
                                    <span class="fw-bold text-white small">{{ Str::limit(Auth::user()->name, 18) }}</span>
                                </div>
                                <div class="text-end">
                                    <small class="text-white-50 d-block" style="font-size: 9px; text-transform: uppercase;">Available Balance</small>
                                    <span class="fw-bold text-success" style="font-size: 14px;">৳{{ number_format(Auth::user()->balance, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Main Quick Actions Grid (6 Clean Tiles) -->
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="fa fa-th-large text-success me-2"></i> দ্রুত সেবা সমূহ (Quick Services)
                        </h5>
                        <small class="text-muted">এক ক্লিকেই সব সেবা ব্রাউজ করুন</small>
                    </div>
                </div>

                <!-- Tile 1: Deposit Money -->
                <div class="col-6 col-md-4 col-xl-3">
                    <a href="{{ route('user.deposit.index') }}" class="action-tile">
                        <div class="action-icon-wrap" style="background: #ecfdf5; color: #059669;">
                            <i class="fa fa-plus-circle"></i>
                        </div>
                        <div class="action-title">টাকা ডিপোজিট</div>
                        <div class="action-subtitle">ব্যালেন্স লোড করুন</div>
                    </a>
                </div>

                <!-- Tile 2: Stock Market -->
                <div class="col-6 col-md-4 col-xl-3">
                    <a href="{{ route('stock.index') }}" class="action-tile">
                        <div class="action-icon-wrap" style="background: #eff6ff; color: #2563eb;">
                            <i class="fa fa-line-chart"></i>
                        </div>
                        <div class="action-title">স্টক মার্কেট</div>
                        <div class="action-subtitle">দৈনিক স্টক কিনুন</div>
                    </a>
                </div>

                <!-- Tile 3: Monthly Bazaar -->
                <div class="col-6 col-md-4 col-xl-3">
                    <a href="{{ route('user.monthly_bazaar.index') }}" class="action-tile">
                        <div class="action-icon-wrap" style="background: #fffbeb; color: #d97706;">
                            <i class="fa fa-shopping-basket"></i>
                        </div>
                        <div class="action-title">মাসিক বাজার</div>
                        <div class="action-subtitle">প্যাকেজ খাদ্যপণ্য</div>
                    </a>
                </div>

                <!-- Tile 4: Wallet Ledger -->
                <div class="col-6 col-md-4 col-xl-3">
                    <a href="{{ route('user.wallet.ledger') }}" class="action-tile">
                        <div class="action-icon-wrap" style="background: #f0fdfa; color: #0d9488;">
                            <i class="fa fa-list-alt"></i>
                        </div>
                        <div class="action-title">ওয়ালেট লেজার</div>
                        <div class="action-subtitle">হিসাব বিবরণী দেখুন</div>
                    </a>
                </div>

                <!-- Tile 5: Land & Properties (New) -->
                <div class="col-6 col-md-4 col-xl-3">
                    <a href="{{ route('properties.index') }}" target="_blank" class="action-tile position-relative">
                        <span class="badge bg-danger position-absolute top-0 end-0 m-2 px-2 py-1" style="font-size: 10px;">New</span>
                        <div class="action-icon-wrap" style="background: #ecfdf5; color: #059669;">
                            <i class="fa fa-map-signs"></i>
                        </div>
                        <div class="action-title">জমি ও প্রপার্টি</div>
                        <div class="action-subtitle">প্লট, ফ্ল্যাট ও শেয়ার</div>
                    </a>
                </div>

                <!-- Tile 6: My Property Bookings (New) -->
                <div class="col-6 col-md-4 col-xl-3">
                    <a href="{{ route('user.properties.my_bookings') }}" class="action-tile">
                        <div class="action-icon-wrap" style="background: #eff6ff; color: #1d4ed8;">
                            <i class="fa fa-bookmark"></i>
                        </div>
                        <div class="action-title">আমার প্রপার্টি বুকিং</div>
                        <div class="action-subtitle">সাইট ভিজিট ও আবেদন</div>
                    </a>
                </div>

                <!-- Tile 7: My Stocks -->
                <div class="col-6 col-md-4 col-xl-3">
                    <a href="{{ route('userbuystocklist') }}" class="action-tile">
                        <div class="action-icon-wrap" style="background: #f5f3ff; color: #7c3aed;">
                            <i class="fa fa-cubes"></i>
                        </div>
                        <div class="action-title">আমার স্টকসমূহ</div>
                        <div class="action-subtitle">ক্রয়কৃত স্টক দেখুন</div>
                    </a>
                </div>

                <!-- Tile 8: My Bazaar Orders -->
                <div class="col-6 col-md-4 col-xl-3">
                    <a href="{{ route('user.monthly_bazaar.my_orders') }}" class="action-tile">
                        <div class="action-icon-wrap" style="background: #fdf2f8; color: #db2777;">
                            <i class="fa fa-truck"></i>
                        </div>
                        <div class="action-title">আমার অর্ডারসমূহ</div>
                        <div class="action-subtitle">মাসিক বাজার ডেলিভারি</div>
                    </a>
                </div>
            </div>

            <!-- 3. Key Financial Metric Cards (4 Modern Cards) -->
            <div class="row g-3 mb-4">
                <!-- Stat 1: Wallet Balance -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="metric-stat-card border-start border-4 border-success">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="metric-label">বর্তমান ওয়ালেট ব্যালেন্স</span>
                            <div class="metric-icon-badge" style="background: #ecfdf5; color: #10b981;">
                                <i class="fa fa-credit-card"></i>
                            </div>
                        </div>
                        <h3 class="metric-value text-success">৳{{ number_format(Auth::user()->balance, 2) }}</h3>
                        <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top">
                            <small class="text-muted"><i class="fa fa-shield me-1 text-success"></i>নিরাপদ ব্যালেন্স</small>
                            <a href="{{ route('user.deposit.index') }}" class="small fw-bold text-success text-decoration-none">+ জমা</a>
                        </div>
                    </div>
                </div>

                <!-- Stat 2: Total Stock Buy -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="metric-stat-card border-start border-4 border-primary">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="metric-label">মোট স্টক ক্রয় (Total Buy)</span>
                            <div class="metric-icon-badge" style="background: #eff6ff; color: #2563eb;">
                                <i class="fa fa-cubes"></i>
                            </div>
                        </div>
                        <h3 class="metric-value text-primary">৳{{ number_format($all_buy, 2) }}</h3>
                        <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top">
                            <small class="text-muted">সক্রিয় স্টক: <strong>{{ $active_stocks_count }} টি</strong></small>
                            <a href="{{ route('userbuystocklist') }}" class="small fw-bold text-primary text-decoration-none">তালিকা <i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Stat 3: Total Stock Sell -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="metric-stat-card border-start border-4 border-info">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="metric-label">মোট বিক্রি (Total Sell)</span>
                            <div class="metric-icon-badge" style="background: #f0fdfa; color: #0891b2;">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                        </div>
                        <h3 class="metric-value text-info">৳{{ number_format($all_sell, 2) }}</h3>
                        <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top">
                            <small class="text-muted">বিক্রিত স্টক রিটার্ন</small>
                            <a href="{{ route('usersellstocklist') }}" class="small fw-bold text-info text-decoration-none">হিস্ট্রি <i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Stat 4: Total Profit -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="metric-stat-card border-start border-4 border-warning">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="metric-label">অর্জিত মোট লাভ (Total Profit)</span>
                            <div class="metric-icon-badge" style="background: #fffbeb; color: #d97706;">
                                <i class="fa fa-trophy"></i>
                            </div>
                        </div>
                        <h3 class="metric-value text-warning">৳{{ number_format($user_profit, 2) }}</h3>
                        <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top">
                            <small class="text-muted">বোনাস ও রিটার্ন প্রফিট</small>
                            <span class="badge bg-warning bg-opacity-25 text-dark font-weight-bold" style="font-size: 10px;">শেয়ারিং একটিভ</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Split Content: Recent Activities & Support Hub -->
            <div class="row g-4">
                <!-- Left Column: Recent Orders & Stock Activity Tabs (col-lg-8) -->
                <div class="col-lg-8">
                    <div class="content-card-modern">
                        <!-- Card Header & Navigation Tabs -->
                        <div class="card-header-styled">
                            <ul class="nav nav-pills nav-pills-modern mb-0" id="dashActivityTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="bazaar-tab" data-bs-toggle="pill" data-bs-target="#bazaar-tab-pane" type="button" role="tab">
                                        <i class="fa fa-shopping-basket me-1"></i> মাসিক বাজার অর্ডার ({{ count($recent_bazaar_orders) }})
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="stock-tab" data-bs-toggle="pill" data-bs-target="#stock-tab-pane" type="button" role="tab">
                                        <i class="fa fa-cubes me-1"></i> সাম্প্রতিক স্টক ক্রয় ({{ count($recent_buystocks) }})
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="deposit-tab" data-bs-toggle="pill" data-bs-target="#deposit-tab-pane" type="button" role="tab">
                                        <i class="fa fa-history me-1"></i> ডিপোজিট রেকর্ড ({{ count($recent_deposits) }})
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="property-tab" data-bs-toggle="pill" data-bs-target="#property-tab-pane" type="button" role="tab">
                                        <i class="fa fa-map-signs me-1"></i> জমি ও প্রপার্টি ({{ $property_inquiries_count ?? 0 }})
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <!-- Tab Content -->
                        <div class="tab-content" id="dashActivityTabsContent">
                            <!-- Tab 1: Recent Bazaar Orders -->
                            <div class="tab-pane fade show active" id="bazaar-tab-pane" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table custom-dash-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>প্যাকেজের নাম</th>
                                                <th>পরিমাণ</th>
                                                <th>মূল্য</th>
                                                <th>পয়েন্ট / এরিয়া</th>
                                                <th>স্ট্যাটাস</th>
                                                <th>তারিখ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recent_bazaar_orders as $order)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="me-2 text-success">
                                                                <i class="fa fa-box fa-lg"></i>
                                                            </div>
                                                            <div>
                                                                <strong class="d-block text-dark">{{ $order->package_title }}</strong>
                                                                <small class="text-muted">পেমেন্ট: {{ strtoupper($order->payment_method) }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><strong>{{ $order->quantity }}</strong> টি</td>
                                                    <td class="fw-bold text-success">৳{{ number_format($order->total_price, 2) }}</td>
                                                    <td>
                                                        <small class="d-block text-dark"><i class="fa fa-map-marker text-danger me-1"></i>{{ $order->agent_point }}</small>
                                                        <small class="text-muted">{{ $order->request_area }}</small>
                                                    </td>
                                                    <td>
                                                        @if($order->status === 'approved')
                                                            <span class="badge-status badge-status-approved"><i class="fa fa-check-circle"></i> অনুমোদিত</span>
                                                        @elseif($order->status === 'pending')
                                                            <span class="badge-status badge-status-pending"><i class="fa fa-clock-o"></i> পেন্ডিং</span>
                                                        @elseif($order->status === 'delivered')
                                                            <span class="badge-status badge-status-completed"><i class="fa fa-truck"></i> ডেলিভারড</span>
                                                        @else
                                                            <span class="badge-status badge-status-rejected"><i class="fa fa-times-circle"></i> {{ ucfirst($order->status) }}</span>
                                                        @endif
                                                    </td>
                                                    <td><small class="text-muted">{{ $order->created_at->format('d M, Y') }}</small></td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4 text-muted">
                                                        <i class="fa fa-shopping-basket fa-2x mb-2 text-secondary opacity-50 d-block"></i>
                                                        এখনও কোনো মাসিক বাজার অর্ডার করা হয়নি।
                                                        <div class="mt-2">
                                                            <a href="{{ route('user.monthly_bazaar.index') }}" class="btn btn-sm btn-success rounded-pill px-3">
                                                                প্যাকেজ দেখুন ও অর্ডার করুন
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if(count($recent_bazaar_orders) > 0)
                                    <div class="p-3 text-end bg-light border-top">
                                        <a href="{{ route('user.monthly_bazaar.my_orders') }}" class="small fw-bold text-success text-decoration-none">
                                            সব মাসিক বাজার অর্ডার দেখুন <i class="fa fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Tab 2: Recent Stock Purchases -->
                            <div class="tab-pane fade" id="stock-tab-pane" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table custom-dash-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>স্টক নাম</th>
                                                <th>পরিমাণ</th>
                                                <th>ক্রয়মূল্য</th>
                                                <th>Trx ID</th>
                                                <th>স্ট্যাটাস</th>
                                                <th>তারিখ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recent_buystocks as $bs)
                                                <tr>
                                                    <td>
                                                        <strong class="text-dark">{{ $bs->stock->name ?? ('স্টক #' . $bs->stock_id) }}</strong>
                                                    </td>
                                                    <td>{{ $bs->buy_quantiy }} ইউনিট</td>
                                                    <td class="fw-bold text-primary">৳{{ number_format($bs->buyed_price, 2) }}</td>
                                                    <td><code>{{ $bs->trx_number }}</code></td>
                                                    <td>
                                                        @if($bs->status === 'aproved')
                                                            <span class="badge-status badge-status-approved"><i class="fa fa-check-circle"></i> অনুমোদিত</span>
                                                        @elseif($bs->status === 'pending')
                                                            <span class="badge-status badge-status-pending"><i class="fa fa-clock-o"></i> পেন্ডিং</span>
                                                        @elseif($bs->status === 'sellpending')
                                                            <span class="badge-status badge-status-completed"><i class="fa fa-hourglass-half"></i> বিক্রয় অপেক্ষমান</span>
                                                        @elseif($bs->status === 'sellaproved')
                                                            <span class="badge-status badge-status-approved"><i class="fa fa-check"></i> বিক্রিত</span>
                                                        @else
                                                            <span class="badge-status badge-status-rejected">{{ $bs->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td><small class="text-muted">{{ $bs->created_at ? $bs->created_at->format('d M, Y') : '-' }}</small></td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4 text-muted">
                                                        <i class="fa fa-cubes fa-2x mb-2 text-secondary opacity-50 d-block"></i>
                                                        এখনও কোনো স্টক ক্রয় করা হয়নি।
                                                        <div class="mt-2">
                                                            <a href="{{ route('stock.index') }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                                                স্টক মার্কেট দেখুন
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if(count($recent_buystocks) > 0)
                                    <div class="p-3 text-end bg-light border-top">
                                        <a href="{{ route('userbuystocklist') }}" class="small fw-bold text-primary text-decoration-none">
                                            সকল স্টক বিনিয়োগ দেখুন <i class="fa fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Tab 3: Recent Deposits -->
                            <div class="tab-pane fade" id="deposit-tab-pane" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table custom-dash-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>ডিপোজিট পরিমাণ</th>
                                                <th>পেমেন্ট মেথড</th>
                                                <th>প্রেরক নাম্বার</th>
                                                <th>Trx ID</th>
                                                <th>স্ট্যাটাস</th>
                                                <th>তারিখ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recent_deposits as $dep)
                                                <tr>
                                                    <td class="fw-bold text-success">৳{{ number_format($dep->amount ?? $dep->deposit_amount ?? 0, 2) }}</td>
                                                    <td><span class="badge bg-light text-dark border">{{ $dep->method ?? $dep->payment_method ?? 'Bank/MFS' }}</span></td>
                                                    <td>{{ $dep->from_number ?? $dep->pay_from_number ?? '-' }}</td>
                                                    <td><code>{{ $dep->transaction_id ?? $dep->trx_number ?? '-' }}</code></td>
                                                    <td>
                                                        @if(($dep->status ?? '') === 'approved' || ($dep->status ?? '') === 'aproved')
                                                            <span class="badge-status badge-status-approved"><i class="fa fa-check-circle"></i> অনুমোদিত</span>
                                                        @elseif(($dep->status ?? '') === 'pending')
                                                            <span class="badge-status badge-status-pending"><i class="fa fa-clock-o"></i> পেন্ডিং</span>
                                                        @else
                                                            <span class="badge-status badge-status-rejected">{{ $dep->status ?? '-' }}</span>
                                                        @endif
                                                    </td>
                                                    <td><small class="text-muted">{{ $dep->created_at ? $dep->created_at->format('d M, Y') : '-' }}</small></td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4 text-muted">
                                                        <i class="fa fa-money fa-2x mb-2 text-secondary opacity-50 d-block"></i>
                                                        কোনো ডিপোজিট রেকর্ড পাওয়া যায়নি।
                                                        <div class="mt-2">
                                                            <a href="{{ route('user.deposit.index') }}" class="btn btn-sm btn-success rounded-pill px-3">
                                                                টাকা ডিপোজিট করুন
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="p-3 text-end bg-light border-top">
                                    <a href="{{ route('user.wallet.ledger') }}" class="small fw-bold text-dark text-decoration-none">
                                        সম্পূর্ণ ওয়ালেট হিস্ট্রি দেখুন <i class="fa fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- Tab 4: Recent Property Inquiries & Visits -->
                            <div class="tab-pane fade" id="property-tab-pane" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table custom-dash-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>প্রপার্টি</th>
                                                <th>আবেদনের ধরন</th>
                                                <th>পছন্দের তারিখ</th>
                                                <th>স্ট্যাটাস</th>
                                                <th>তারিখ</th>
                                                <th class="text-end">অ্যাকশন</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recent_property_inquiries as $inq)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="me-2 text-success">
                                                                <i class="fa fa-map-signs fa-lg"></i>
                                                            </div>
                                                            <div>
                                                                <strong class="d-block text-dark">{{ $inq->property ? $inq->property->property_name : 'সাধারণ অনুসন্ধান' }}</strong>
                                                                <small class="text-muted font-monospace">{{ $inq->inquiry_code }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($inq->inquiry_type === 'site_visit')
                                                            <span class="badge bg-info bg-opacity-10 text-info border border-info">সাইট পরিদর্শন</span>
                                                        @elseif($inq->inquiry_type === 'booking_request')
                                                            <span class="badge bg-success bg-opacity-10 text-success border border-success">বুকিং আবেদন</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ ucfirst($inq->inquiry_type) }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($inq->preferred_date)
                                                            <small class="fw-bold text-dark">{{ date('d M, Y', strtotime($inq->preferred_date)) }}</small>
                                                        @else
                                                            <small class="text-muted">-</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($inq->status === 'confirmed')
                                                            <span class="badge-status badge-status-approved"><i class="fa fa-check-circle"></i> নিশ্চিত</span>
                                                        @elseif($inq->status === 'scheduled')
                                                            <span class="badge-status badge-status-approved" style="background: #e0f2fe; color: #0284c7;"><i class="fa fa-calendar"></i> শিডিউলড</span>
                                                        @elseif($inq->status === 'pending')
                                                            <span class="badge-status badge-status-pending"><i class="fa fa-clock-o"></i> অপেক্ষমাণ</span>
                                                        @else
                                                            <span class="badge-status badge-status-cancelled">{{ ucfirst($inq->status) }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">{{ $inq->created_at->format('d M, Y') }}</small>
                                                    </td>
                                                    <td class="text-end">
                                                        <a href="{{ route('user.properties.my_bookings') }}" class="btn btn-sm btn-outline-success">
                                                            দেখুন
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4 text-muted">
                                                        <i class="fa fa-map-signs fa-2x mb-2 text-secondary opacity-50 d-block"></i>
                                                        বর্তমানে কোনো প্রপার্টি আবেদন নেই।
                                                        <div class="mt-2">
                                                            <a href="{{ route('properties.index') }}" target="_blank" class="btn btn-sm btn-success rounded-pill px-3">
                                                                প্রপার্টি ব্রাউজ করুন
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="p-3 text-end bg-light border-top">
                                    <a href="{{ route('user.properties.my_bookings') }}" class="small fw-bold text-dark text-decoration-none">
                                        সকল প্রপার্টি আবেদন ও বুকিং দেখুন <i class="fa fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Support & Profile Center (col-lg-4) -->
                <div class="col-lg-4">
                    <!-- Live Support Card -->
                    <div class="support-promo-card mb-4 shadow-sm">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success text-white rounded-circle p-2 me-3" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                                <i class="fa fa-comments"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0 text-white">২৪/৭ কাস্টমার সাপোর্ট</h5>
                                <small class="text-success fw-semibold"><i class="fa fa-circle" style="font-size: 8px;"></i> সার্বক্ষণিক সহায়তা সেন্টার</small>
                            </div>
                        </div>
                        <p class="text-white-50 small mb-3">
                            স্টক ক্রয়, মাসিক বাজার বা ওয়ালেট সংক্রান্ত যেকোনো প্রশ্নে আমাদের সাপোর্ট প্রতিনিধির সাথে সরাসরি কথা বলুন।
                        </p>
                        <button type="button" onclick="toggleKrishiChat()" class="btn btn-success w-100 py-2 fw-bold rounded-pill mb-2 shadow-sm">
                            <i class="fa fa-commenting me-1"></i> লাইভ চ্যাট শুরু করুন
                        </button>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top border-secondary border-opacity-25 text-white-50 small">
                            <span><i class="fa fa-phone me-1 text-warning"></i> হটলাইন: ০১৭XXXXXXX</span>
                            <span><i class="fa fa-whatsapp me-1 text-success"></i> হোয়াটসঅ্যাপ</span>
                        </div>
                    </div>

                    <!-- Profile & Security Quick Center -->
                    <div class="content-card-modern p-3">
                        <h6 class="fw-bold text-dark mb-3">
                            <i class="fa fa-cog text-secondary me-2"></i> একাউন্ট ও নিরাপত্তা সেটিংস
                        </h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <a href="{{ route('profile.index') }}" class="d-flex align-items-center justify-content-between p-2 rounded text-decoration-none text-dark bg-light hover-shadow">
                                    <span class="small fw-semibold"><i class="fa fa-user-circle text-primary me-2"></i> প্রোফাইল তথ্য পরিবর্তন</span>
                                    <i class="fa fa-angle-right text-muted"></i>
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('profile.index') }}" class="d-flex align-items-center justify-content-between p-2 rounded text-decoration-none text-dark bg-light hover-shadow">
                                    <span class="small fw-semibold"><i class="fa fa-lock text-warning me-2"></i> পাসওয়ার্ড পরিবর্তন</span>
                                    <i class="fa fa-angle-right text-muted"></i>
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('user.wallet.ledger') }}" class="d-flex align-items-center justify-content-between p-2 rounded text-decoration-none text-dark bg-light hover-shadow">
                                    <span class="small fw-semibold"><i class="fa fa-file-text-o text-info me-2"></i> অ্যাকাউন্ট স্টেটমেন্ট</span>
                                    <i class="fa fa-angle-right text-muted"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.monthly_bazaar.index') }}" class="d-flex align-items-center justify-content-between p-2 rounded text-decoration-none text-dark bg-light hover-shadow">
                                    <span class="small fw-semibold"><i class="fa fa-shopping-basket text-success me-2"></i> মাসিক বাজার পণ্য তালিকা</span>
                                    <i class="fa fa-angle-right text-muted"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </section>
    </div>
</div>
@endsection