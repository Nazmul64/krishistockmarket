@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title text-primary"><i class="fa fa-briefcase me-2"></i> Manager Dashboard (ম্যানেজার কন্ট্রোল প্যানেল)</h3>
                    <p class="text-muted mb-0">টিম সদস্য, অনুমোদন, পারফরম্যান্স ও ডিপার্টমেন্টাল কার্যকলাপ (Scope: Own Team / Branch)</p>
                </div>
                <div>
                    <span class="badge badge-lg bg-primary-light text-primary fw-bold px-3 py-2">
                        <i class="fa fa-building me-1"></i> {{ optional(Auth::user()->department)->name ?? 'General Management' }}
                    </span>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- KPI Cards -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body p-3 bg-primary-light mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">টিম সদস্য সংখ্যা</span>
                                <h3 class="mb-0 fw-bold text-primary">{{ $teamCount }} জন</h3>
                            </div>
                            <i class="fa fa-users text-primary font-size-28"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body p-3 bg-success-light mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">আজকের টিম উপস্থিতি</span>
                                <h3 class="mb-0 fw-bold text-success">{{ $todayAttendance }} জন</h3>
                            </div>
                            <i class="fa fa-check-circle text-success font-size-28"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body p-3 bg-warning-light mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">অনুমোদন অপেক্ষারত ছুটি</span>
                                <h3 class="mb-0 fw-bold text-warning">{{ $pendingLeaves->count() }} টি</h3>
                            </div>
                            <i class="fa fa-calendar-times-o text-warning font-size-28"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body p-3 bg-info-light mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">সক্রিয় বিপণন কর্মী</span>
                                <h3 class="mb-0 fw-bold text-info">{{ $activeEmployeesCount }} জন</h3>
                            </div>
                            <i class="fa fa-id-badge text-info font-size-28"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Team Members Scope Table -->
                <div class="col-xl-7 col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title text-primary"><i class="fa fa-users me-2"></i> নিজের টিম সদস্যবৃন্দ (My Team Scope)</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 font-size-13">
                                    <thead class="bg-light">
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
                                                    <img src="{{ !empty($m->avatar) && file_exists(public_path('upload/images/user/' . $m->avatar)) ? asset('upload/images/user/' . $m->avatar) : asset('assets/images/avatar/avatar-1.png') }}" class="avatar avatar-sm rounded-circle me-2" alt="">
                                                    <div>
                                                        <span class="fw-bold text-dark d-block">{{ $m->name }}</span>
                                                        <small class="text-muted">Username: {{ $m->username }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>{{ $m->phone }}</div>
                                                <small class="text-muted">{{ $m->email }}</small>
                                            </td>
                                            <td><span class="badge bg-primary-light text-primary">{{ optional($m->roleRelation)->name ?? strtoupper($m->role) }}</span></td>
                                            <td>
                                                @if($m->status === 'active')
                                                    <span class="badge bg-success">সক্রিয়</span>
                                                @else
                                                    <span class="badge bg-danger">{{ $m->status }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">আপনার অধীনে এখনও কোনো টিম মেম্বার নির্ধারিত হয়নি।</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Approvals & Leave Requests -->
                <div class="col-xl-5 col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title text-warning"><i class="fa fa-clock-o me-2"></i> পেন্ডিং ছুটির আবেদন (Leave Approvals)</h4>
                        </div>
                        <div class="box-body p-0">
                            @if($pendingLeaves->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 font-size-13">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>আবেদনকারী</th>
                                            <th>সময়সীমা</th>
                                            <th class="text-end">অনুমোদন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingLeaves as $leave)
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-dark d-block">{{ optional($leave->employee)->name ?? 'কর্মী' }}</span>
                                                <small class="text-muted">{{ $leave->reason }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $leave->days }} দিন</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('manager.leave.approve', $leave->id) }}" class="btn btn-xs btn-success" onclick="return confirm('ছুটি অনুমোদন করতে চান?')">
                                                    <i class="fa fa-check me-1"></i> Approve
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-5 text-muted">
                                <i class="fa fa-check-circle-o font-size-36 text-success mb-2 d-block"></i>
                                বর্তমানে অনুমোদনের জন্য কোনো ছুটির আবেদন পেন্ডিং নেই।
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
