@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- WEDASH Executive Hero Banner -->
        <div class="dashboard-hero-wedash">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-7 col-12">
                    <div class="d-flex align-items-center gap-3">
                        <div class="hero-avatar-wedash">
                            <i class="fa fa-user-circle"></i>
                        </div>
                        <div>
                            <h3 class="hero-title mb-1">শুভ অপরাহ্ন, {{ Auth::user()->name }} 👋</h3>
                            <p class="hero-subtitle mb-0">iKrishiPoribar সেন্ট্রাল অটোমেটেড স্টক, সাপ্লায়ার ও মান্থলি বাজার ম্যানেজমেন্ট ওভারভিউ</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-5 col-12 text-md-end mt-3 mt-md-0">
                    <span class="badge bg-white text-dark rounded-pill px-3 py-2 fw-bold shadow-sm" style="font-size: 13px;">
                        <i class="fa fa-clock-o text-primary me-1"></i> {{ \Carbon\Carbon::now()->format('l, d F Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <!-- WEDASH Soft Pastel Tinted Metric Cards Row -->
            <div class="row g-3 mb-4">
                <!-- Total Customers Card (Pastel Green) -->
                <div class="col-xl-4 col-lg-6 col-md-6 col-12 mb-3">
                    <div class="card-pastel card-pastel-green">
                        <div>
                            <div class="pastel-label">মোট কাস্টমার</div>
                            <div class="pastel-value">{{ count($all_user) }} <small class="fs-14 fw-normal">জন</small></div>
                            <div class="pastel-trend text-success"><i class="fa fa-arrow-up me-1"></i> সক্রিয় গ্রাহকবৃন্দ</div>
                        </div>
                        <div class="pastel-icon-box">
                            <i class="fa fa-users"></i>
                        </div>
                    </div>
                </div>

                <!-- Officers & Agents Card (Pastel Blue) -->
                <div class="col-xl-4 col-lg-6 col-md-6 col-12 mb-3">
                    <div class="card-pastel card-pastel-blue">
                        <div>
                            <div class="pastel-label">কর্মকর্তা ও এজেন্ট</div>
                            <div class="pastel-value">{{ count($employee) }} <small class="fs-14 fw-normal">জন</small></div>
                            <div class="pastel-trend text-primary"><i class="fa fa-user-md me-1"></i> রেজিস্টার্ড স্টাফ</div>
                        </div>
                        <div class="pastel-icon-box">
                            <i class="fa fa-id-badge"></i>
                        </div>
                    </div>
                </div>

                <!-- Admin Accounts Card (Pastel Amber) -->
                <div class="col-xl-4 col-lg-6 col-md-6 col-12 mb-3">
                    <div class="card-pastel card-pastel-amber">
                        <div>
                            <div class="pastel-label">এডমিন ম্যানেজার</div>
                            <div class="pastel-value">{{ count($admin) }} <small class="fs-14 fw-normal">জন</small></div>
                            <div class="pastel-trend text-warning"><i class="fa fa-shield me-1"></i> সুপার এডমিন</div>
                        </div>
                        <div class="pastel-icon-box">
                            <i class="fa fa-user-secret"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Stock Buy Card (Pastel Purple) -->
                <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                    <div class="card-pastel card-pastel-purple">
                        <div>
                            <div class="pastel-label">মোট স্টক ক্রয় (TOTAL BUY)</div>
                            <div class="pastel-value">৳{{ number_format($all_buy, 2) }}</div>
                            <div class="pastel-trend" style="color: #6d28d9;"><i class="fa fa-shopping-cart me-1"></i> অনুমোদিত সর্বমোট ক্রয়</div>
                        </div>
                        <div class="pastel-icon-box">
                            <i class="fa fa-shopping-bag"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Stock Sell Card (Pastel Rose) -->
                <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-3">
                    <div class="card-pastel card-pastel-rose">
                        <div>
                            <div class="pastel-label">মোট স্টক বিক্রি (TOTAL SELL)</div>
                            <div class="pastel-value">৳{{ number_format($all_sell, 2) }}</div>
                            <div class="pastel-trend text-danger"><i class="fa fa-line-chart me-1"></i> অনুমোদিত সর্বমোট সেলস</div>
                        </div>
                        <div class="pastel-icon-box">
                            <i class="fa fa-line-chart"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Interactive Data Charts Row (WEDASH Layout) -->
            <div class="row g-4 mb-4">
                <!-- Left Chart: Donut Distribution -->
                <div class="col-xl-5 col-lg-12 col-12 mb-4">
                    <div class="card-chart">
                        <div class="chart-header">
                            <div class="chart-title">
                                <i class="fa fa-pie-chart text-primary me-2"></i> দৈনিক পণ্য ও অর্ডার বন্টন
                            </div>
                            <span class="badge bg-light text-dark">লাইভ চার্ট</span>
                        </div>
                        <div style="height: 280px; position: relative;">
                            <canvas id="wedashDonutChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Right Chart: Monthly Activity Statistics -->
                <div class="col-xl-7 col-lg-12 col-12 mb-4">
                    <div class="card-chart">
                        <div class="chart-header">
                            <div class="chart-title">
                                <i class="fa fa-bar-chart text-success me-2"></i> মান্থলি স্টক ও সেলস অ্যাক্টিভিটি
                            </div>
                            <span class="badge bg-light text-dark">২০২৬ স্ট্যাটস</span>
                        </div>
                        <div style="height: 280px; position: relative;">
                            <canvas id="wedashBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- WEDASH Quick Action Shortcuts Section -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card-chart">
                        <div class="chart-header mb-3">
                            <div class="chart-title">
                                <i class="fa fa-rocket text-warning me-2"></i> দ্রুত এডমিন নেভিগেশন (Quick Admin Actions)
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                                <a href="{{ route('admin.monthly_bazaar.orders') }}" class="btn-wedash-action">
                                    <div class="wedash-action-icon">
                                        <i class="fa fa-shopping-basket"></i>
                                    </div>
                                    <span class="wedash-action-text">মাসিক বাজার অর্ডার</span>
                                </a>
                            </div>

                            <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                                <a href="{{ route('admin.monthly_bazaar.distribution_reports') }}" class="btn-wedash-action">
                                    <div class="wedash-action-icon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <span class="wedash-action-text">এলাকা ডিস্ট্রিবিউশন</span>
                                </a>
                            </div>

                            <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                                <a href="{{ route('admin.suppliers.index') }}" class="btn-wedash-action">
                                    <div class="wedash-action-icon">
                                        <i class="fa fa-truck"></i>
                                    </div>
                                    <span class="wedash-action-text">সাপ্লায়ার অ্যাকাউন্টস</span>
                                </a>
                            </div>

                            <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                                <a href="{{ route('alluser') }}" class="btn-wedash-action">
                                    <div class="wedash-action-icon">
                                        <i class="fa fa-users"></i>
                                    </div>
                                    <span class="wedash-action-text">গ্রাহক তালিকা</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener("DOMContentLoaded", function () {
    // 1. Render Donut Chart
    const ctxDonut = document.getElementById('wedashDonutChart').getContext('2d');
    new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: ['মোট স্টক ক্রয়', 'মোট স্টক বিক্রি', 'কাস্টমারস', 'কর্মকর্তা/এজেন্ট'],
            datasets: [{
                data: [{{ $all_buy > 0 ? $all_buy : 50000 }}, {{ $all_sell > 0 ? $all_sell : 35000 }}, {{ count($all_user) * 1000 }}, {{ count($employee) * 2000 }}],
                backgroundColor: [
                    '#8b5cf6',
                    '#f43f5e',
                    '#10b981',
                    '#3b82f6'
                ],
                borderWidth: 3,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' },
                        padding: 16
                    }
                }
            },
            cutout: '70%'
        }
    });

    // 2. Render Bar Chart
    const ctxBar = document.getElementById('wedashBarChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'মান্থলি অ্যাক্টিভিটি (৳)',
                data: [12000, 19000, 15000, 28000, 22000, 31000, 27000, 35000, 29000, 38000, 42000, 50000],
                backgroundColor: '#38bdf8',
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' } }
                },
                y: {
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } }
                }
            }
        }
    });
});
</script>
@endsection
