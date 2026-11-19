@extends('layouts.backend.app')

@section('content')
<style>
    .ch-dash-wrap {
        background-color: #f4f6fa;
        min-height: calc(100vh - 65px);
        padding: 16px 20px 40px 20px;
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
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
    .ch-pill {
        font-size: 11px;
        font-weight: 700;
        border-radius: 14px;
        padding: 3px 9px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .ch-pill-green { background: #eafaf1; color: #10b981; }
    .ch-pill-amber { background: #fef3c7; color: #d97706; }
    .ch-pill-orange { background: #ffedd5; color: #ea580c; }
    .ch-pill-blue { background: #e0f2fe; color: #0284c7; }

    .ch-mini-bars {
        display: flex;
        align-items: flex-end;
        gap: 3px;
        height: 32px;
    }
    .ch-bar-col { width: 4px; border-radius: 2px; }
    .ch-bar-blue { background: #3b82f6; }
    .ch-bar-orange { background: #f97316; }

    .ch-sparkline-svg {
        width: 90px;
        height: 35px;
        overflow: visible;
    }

    .ch-table th {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        background: #f8fafc;
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
    }
    .ch-table td {
        font-size: 13.5px;
        color: #334155;
        vertical-align: middle;
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
    }
</style>

<div class="content-wrapper" style="background-color: #f4f6fa !important; margin-top: 0 !important; padding-top: 60px !important;">
    <div class="container-full">
        <div class="ch-dash-wrap">

            <!-- Content Header -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-1" style="font-size: 20px;">
                        <i class="fa fa-briefcase text-primary me-2"></i> ম্যানেজার কন্ট্রোল প্যানেল (Manager Hub)
                    </h3>
                    <p class="text-muted font-size-13 mb-0">টিম সদস্য, কাজের অগ্রগতি, উপস্থিতি ও ছুটির অনুমোদন ড্যাশবোর্ড</p>
                </div>
                <div>
                    <span class="ch-pill ch-pill-blue px-3 py-2 fw-bold font-size-12">
                        <i class="fa fa-building me-1"></i> {{ optional(Auth::user()->department)->name ?? 'General Management' }}
                    </span>
                </div>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-xl mb-4" role="alert">
                <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Top 4 Stat KPI Cards (Screenshot matching style) -->
            <div class="row g-3">
                <!-- 1. Team Members -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="ch-stat-card">
                        <div class="ch-stat-top">
                            <span class="ch-stat-label">টিম সদস্য সংখ্যা</span>
                            <span class="ch-pill ch-pill-green">+ Active <i class="fa fa-arrow-up font-size-10"></i></span>
                        </div>
                        <div class="ch-stat-bottom">
                            <div class="ch-stat-val">{{ $teamCount }} <span class="font-size-16 fw-normal text-muted">জন</span></div>
                            <svg class="ch-sparkline-svg" viewBox="0 0 100 35">
                                <path d="M0,24 C20,24 30,5 50,5 C70,5 80,30 100,18" fill="none" stroke="#f43f5e" stroke-width="2.5" stroke-linecap="round"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- 2. Today Attendance -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="ch-stat-card">
                        <div class="ch-stat-top">
                            <span class="ch-stat-label">আজকের উপস্থিতি</span>
                            <span class="ch-pill ch-pill-blue">উপস্থিত</span>
                        </div>
                        <div class="ch-stat-bottom">
                            <div class="ch-stat-val" style="color: #0284c7;">{{ $todayAttendance }} <span class="font-size-16 fw-normal text-muted">জন</span></div>
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

                <!-- 3. Pending Leave Approvals -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="ch-stat-card">
                        <div class="ch-stat-top">
                            <span class="ch-stat-label">অনুমোদন অপেক্ষারত ছুটি</span>
                            <span class="ch-pill ch-pill-orange">Action Needed</span>
                        </div>
                        <div class="ch-stat-bottom">
                            <div class="ch-stat-val" style="color: #ea580c;">{{ $pendingLeaves->count() }} <span class="font-size-16 fw-normal text-muted">টি</span></div>
                            <svg class="ch-sparkline-svg" viewBox="0 0 100 35">
                                <path d="M0,22 C22,22 35,6 55,6 C75,6 88,26 100,16" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- 4. Active Marketing Employees -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="ch-stat-card">
                        <div class="ch-stat-top">
                            <span class="ch-stat-label">সক্রিয় বিপণন কর্মী</span>
                            <span class="ch-pill ch-pill-amber">Circulating</span>
                        </div>
                        <div class="ch-stat-bottom">
                            <div class="ch-stat-val" style="color: #d97706;">{{ $activeEmployeesCount }} <span class="font-size-16 fw-normal text-muted">জন</span></div>
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

            <!-- Middle Row: Team Performance Chart & Attendance Donut -->
            <div class="row g-3">
                <div class="col-xl-8 col-lg-7 col-12">
                    <div class="ch-card h-100" style="min-height: 380px;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0 font-size-16">টিম পারফরম্যান্স ও কাজের অগ্রগতি (Weekly Performance)</h5>
                            <span class="text-muted"><i class="fa fa-ellipsis-h"></i></span>
                        </div>
                        <div style="position: relative; height: 300px; width: 100%;">
                            <canvas id="managerSplineChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-5 col-12">
                    <div class="ch-card h-100" style="min-height: 380px;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0 font-size-16">উপস্থিতি অনুপাত (Today's Attendance)</h5>
                            <span class="text-muted"><i class="fa fa-ellipsis-h"></i></span>
                        </div>
                        <div class="d-flex align-items-center justify-content-center" style="position: relative; height: 260px;">
                            <div style="position: relative; width: 180px; height: 180px;">
                                <canvas id="managerDonutChart"></canvas>
                                <div class="position-absolute text-center" style="top: 50%; left: 50%; transform: translate(-50%, -50%); pointer-events: none;">
                                    <div class="fw-bold" style="font-size: 24px; color: #1e293b;">{{ $teamCount > 0 ? round(($todayAttendance / $teamCount) * 100) : 100 }}%</div>
                                    <small class="text-muted font-size-11">উপস্থিতি হার</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Row: Team Members Table & Pending Approvals -->
            <div class="row g-3 mt-1">
                <!-- Team Members Table -->
                <div class="col-xl-7 col-12">
                    <div class="ch-card p-0">
                        <div class="p-4 border-bottom border-light d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold text-dark mb-0 font-size-16">
                                <i class="fa fa-users text-primary me-2"></i> নিজের টিম সদস্যবৃন্দ (My Team Members)
                            </h5>
                            <span class="ch-pill ch-pill-blue">{{ $teamCount }} জন</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table ch-table">
                                <thead>
                                    <tr>
                                        <th>কর্মী নাম</th>
                                        <th>যোগাযোগ</th>
                                        <th>রোল (Role)</th>
                                        <th>স্ট্যাটাস</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($teamMembers as $m)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ !empty($m->avatar) && file_exists(public_path('upload/images/user/' . $m->avatar)) ? asset('upload/images/user/' . $m->avatar) : asset('assets/images/avatar/avatar-1.png') }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;" alt="">
                                                <div>
                                                    <span class="fw-bold text-dark d-block">{{ $m->name }}</span>
                                                    <small class="text-muted">{{ $m->username }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>{{ $m->phone }}</div>
                                            <small class="text-muted">{{ $m->email }}</small>
                                        </td>
                                        <td><span class="ch-pill ch-pill-blue">{{ optional($m->roleRelation)->name ?? strtoupper($m->role) }}</span></td>
                                        <td>
                                            @if($m->status === 'active')
                                                <span class="ch-pill ch-pill-green">সক্রিয়</span>
                                            @else
                                                <span class="ch-pill ch-pill-orange">{{ $m->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">আপনার অধীনে এখনও কোনো টিম মেম্বার নির্ধারিত হয়নি।</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pending Approvals & Leave Requests -->
                <div class="col-xl-5 col-12">
                    <div class="ch-card p-0">
                        <div class="p-4 border-bottom border-light d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold text-dark mb-0 font-size-16">
                                <i class="fa fa-clock-o text-warning me-2"></i> পেন্ডিং ছুটির আবেদন (Leave Approvals)
                            </h5>
                            <span class="ch-pill ch-pill-orange">{{ $pendingLeaves->count() }} টি</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table ch-table">
                                <thead>
                                    <tr>
                                        <th>আবেদনকারী</th>
                                        <th>সময়সীমা</th>
                                        <th class="text-end">অনুমোদন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendingLeaves as $leave)
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-dark d-block">{{ optional($leave->employee)->name ?? 'কর্মী' }}</span>
                                            <small class="text-muted">{{ $leave->reason }}</small>
                                        </td>
                                        <td>
                                            <span class="ch-pill ch-pill-amber">{{ $leave->days }} দিন</span>
                                            <div class="text-muted font-size-11 mt-1">{{ $leave->start_date }}</div>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('manager.leave.approve', $leave->id) }}" class="btn btn-xs btn-success rounded-pill px-3" onclick="return confirm('এই ছুটির আবেদন অনুমোদন করতে চান?')">
                                                <i class="fa fa-check me-1"></i> অনুমোদন
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">কোনো ছুটির আবেদন অপেক্ষমান নেই।</td>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart === 'undefined') return;

    // Manager Spline Chart
    const splineCtx = document.getElementById('managerSplineChart');
    if (splineCtx) {
        const ctx = splineCtx.getContext('2d');
        const grad = ctx.createLinearGradient(0, 0, 0, 280);
        grad.addColorStop(0, 'rgba(59, 130, 246, 0.22)');
        grad.addColorStop(1, 'rgba(59, 130, 246, 0.00)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['শনিবার', 'রবিবার', 'সোমবার', 'মঙ্গলবার', 'বুধবার', 'বৃহস্পতিবার'],
                datasets: [{
                    label: 'কাজের পারফরম্যান্স',
                    data: [65, 82, 78, 92, 88, 95],
                    borderColor: '#3b82f6',
                    backgroundColor: grad,
                    borderWidth: 2.8,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointBackgroundColor: '#3b82f6'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { min: 0, max: 100, grid: { color: '#f1f5f9' } }
                }
            }
        });
    }

    // Manager Attendance Donut Chart
    const donutCtx = document.getElementById('managerDonutChart');
    if (donutCtx) {
        const present = {{ $todayAttendance > 0 ? $todayAttendance : 1 }};
        const absent = Math.max(0, {{ $teamCount }} - present);

        new Chart(donutCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['উপস্থিত', 'অনুপস্থিত'],
                datasets: [{
                    data: [present, absent > 0 ? absent : 1],
                    backgroundColor: ['#10b981', '#e2e8f0'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '76%',
                plugins: { legend: { display: false } }
            }
        });
    }
});
</script>
@endsection
