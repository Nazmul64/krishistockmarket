@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title text-primary"><i class="fa fa-shield me-2"></i> Super Admin & Admin Platform Overview</h3>
                    <p class="text-muted mb-0">আন্তর্জাতিক মানের RBAC, ডাটা স্কোপ ও রিয়েল-টাইম সিস্টেম কন্ট্রোল প্যানেল</p>
                </div>
                <div>
                    <span class="badge badge-lg bg-success-light text-success fw-bold px-3 py-2"><i class="fa fa-circle text-success me-1"></i> System Online</span>
                    <span class="badge badge-lg bg-primary-light text-primary fw-bold px-3 py-2 ms-2"><i class="fa fa-user-circle me-1"></i> {{ Auth::user()->roleRelation->name ?? strtoupper(Auth::user()->role) }}</span>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <!-- Top Summary KPI Cards -->
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

            <!-- Quick Access RBAC Modules -->
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
@endsection
