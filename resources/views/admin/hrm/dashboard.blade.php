@extends('layouts.backend.app')

@section('title', 'HRM ড্যাশবোর্ড')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="font-weight-bold mb-0" style="color: #2e7d32;">
                        <i class="ti-user mr-2"></i> Human Resource Management (HRM) ড্যাশবোর্ড
                    </h3>
                    <p class="text-muted mb-0">প্রতিষ্ঠানের কর্মী, উপস্থিতি, ছুটি, পে-রোল ও কর্মক্ষমতা ব্যবস্থাপনা</p>
                </div>
                <div>
                    <a href="{{ route('admin.hrm.employees.index') }}" class="btn btn-success btn-sm rounded-pill shadow-sm">
                        <i class="ti-plus mr-1"></i> নতুন কর্মী যোগ করুন
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row">
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card card-tale border-0 shadow-sm rounded-lg" style="background: linear-gradient(135deg, #2e7d32, #4caf50); color: #fff;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-2" style="opacity: 0.9;">মোট কর্মকর্তা/কর্মচারী</p>
                            <h2 class="mb-0 font-weight-bold">{{ number_format($totalEmployees) }}</h2>
                        </div>
                        <i class="ti-id-badge icon-lg" style="opacity: 0.8;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 grid-margin stretch-card">
            <div class="card card-dark-blue border-0 shadow-sm rounded-lg" style="background: linear-gradient(135deg, #1565c0, #1e88e5); color: #fff;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-2" style="opacity: 0.9;">আজকের উপস্থিতি (Present)</p>
                            <h2 class="mb-0 font-weight-bold">{{ number_format($todayPresent) }}</h2>
                        </div>
                        <i class="ti-check-box icon-lg" style="opacity: 0.8;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 grid-margin stretch-card">
            <div class="card card-light-blue border-0 shadow-sm rounded-lg" style="background: linear-gradient(135deg, #e65100, #ff9800); color: #fff;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-2" style="opacity: 0.9;">আজকে অনুপস্থিত (Absent)</p>
                            <h2 class="mb-0 font-weight-bold">{{ number_format($todayAbsent) }}</h2>
                        </div>
                        <i class="ti-close icon-lg" style="opacity: 0.8;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 grid-margin stretch-card">
            <div class="card card-light-danger border-0 shadow-sm rounded-lg" style="background: linear-gradient(135deg, #6a1b9a, #ab47bc); color: #fff;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-2" style="opacity: 0.9;">অপেক্ষমাণ ছুটির আবেদন</p>
                            <h2 class="mb-0 font-weight-bold">{{ number_format($pendingLeaves) }}</h2>
                        </div>
                        <i class="ti-calendar icon-lg" style="opacity: 0.8;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Modules -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 font-weight-bold text-dark"><i class="ti-layout-grid2 text-success mr-2"></i> HRM মডিউলসমূহ</h5>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-md-2 col-6 text-center mb-3">
                            <a href="{{ route('admin.hrm.departments.index') }}" class="btn btn-outline-success btn-block py-3 rounded-lg shadow-sm">
                                <i class="ti-layers d-block mb-2 font-weight-bold" style="font-size: 24px;"></i>
                                <span class="font-weight-bold">ডিপার্টমেন্ট</span>
                            </a>
                        </div>
                        <div class="col-md-2 col-6 text-center mb-3">
                            <a href="{{ route('admin.hrm.designations.index') }}" class="btn btn-outline-primary btn-block py-3 rounded-lg shadow-sm">
                                <i class="ti-medall d-block mb-2 font-weight-bold" style="font-size: 24px;"></i>
                                <span class="font-weight-bold">পদবীসমূহ</span>
                            </a>
                        </div>
                        <div class="col-md-2 col-6 text-center mb-3">
                            <a href="{{ route('admin.hrm.branches.index') }}" class="btn btn-outline-info btn-block py-3 rounded-lg shadow-sm">
                                <i class="ti-location-pin d-block mb-2 font-weight-bold" style="font-size: 24px;"></i>
                                <span class="font-weight-bold">ব্রাঞ্চসমূহ</span>
                            </a>
                        </div>
                        <div class="col-md-2 col-6 text-center mb-3">
                            <a href="{{ route('admin.hrm.shifts.index') }}" class="btn btn-outline-warning btn-block py-3 rounded-lg shadow-sm">
                                <i class="ti-time d-block mb-2 font-weight-bold" style="font-size: 24px;"></i>
                                <span class="font-weight-bold">শিফট শিডিউল</span>
                            </a>
                        </div>
                        <div class="col-md-2 col-6 text-center mb-3">
                            <a href="{{ route('admin.hrm.attendance.index') }}" class="btn btn-outline-danger btn-block py-3 rounded-lg shadow-sm">
                                <i class="ti-alarm-clock d-block mb-2 font-weight-bold" style="font-size: 24px;"></i>
                                <span class="font-weight-bold">উপস্থিতি</span>
                            </a>
                        </div>
                        <div class="col-md-2 col-6 text-center mb-3">
                            <a href="{{ route('admin.hrm.leave.index') }}" class="btn btn-outline-dark btn-block py-3 rounded-lg shadow-sm">
                                <i class="ti-calendar d-block mb-2 font-weight-bold" style="font-size: 24px;"></i>
                                <span class="font-weight-bold">ছুটি অনুমোদন</span>
                            </a>
                        </div>
                        <div class="col-md-2 col-6 text-center mb-3">
                            <a href="{{ route('admin.hrm.payroll.index') }}" class="btn btn-outline-success btn-block py-3 rounded-lg shadow-sm">
                                <i class="ti-money d-block mb-2 font-weight-bold" style="font-size: 24px;"></i>
                                <span class="font-weight-bold">পে-রোল & বেতন</span>
                            </a>
                        </div>
                        <div class="col-md-2 col-6 text-center mb-3">
                            <a href="{{ route('admin.hrm.loans.index') }}" class="btn btn-outline-primary btn-block py-3 rounded-lg shadow-sm">
                                <i class="ti-hand-point-right d-block mb-2 font-weight-bold" style="font-size: 24px;"></i>
                                <span class="font-weight-bold">লোন ও এডভান্স</span>
                            </a>
                        </div>
                        <div class="col-md-2 col-6 text-center mb-3">
                            <a href="{{ route('admin.hrm.recruitment.index') }}" class="btn btn-outline-info btn-block py-3 rounded-lg shadow-sm">
                                <i class="ti-briefcase d-block mb-2 font-weight-bold" style="font-size: 24px;"></i>
                                <span class="font-weight-bold">রিক্রুটমেন্ট</span>
                            </a>
                        </div>
                        <div class="col-md-2 col-6 text-center mb-3">
                            <a href="{{ route('admin.hrm.assets.index') }}" class="btn btn-outline-secondary btn-block py-3 rounded-lg shadow-sm">
                                <i class="ti-desktop d-block mb-2 font-weight-bold" style="font-size: 24px;"></i>
                                <span class="font-weight-bold">কোম্পানি অ্যাসেট</span>
                            </a>
                        </div>
                        <div class="col-md-2 col-6 text-center mb-3">
                            <a href="{{ route('admin.hrm.announcements.index') }}" class="btn btn-outline-warning btn-block py-3 rounded-lg shadow-sm">
                                <i class="ti-announcement d-block mb-2 font-weight-bold" style="font-size: 24px;"></i>
                                <span class="font-weight-bold">ঘোষণা ও নোটিশ</span>
                            </a>
                        </div>
                        <div class="col-md-2 col-6 text-center mb-3">
                            <a href="{{ route('admin.hrm.reports.index') }}" class="btn btn-outline-dark btn-block py-3 rounded-lg shadow-sm">
                                <i class="ti-bar-chart d-block mb-2 font-weight-bold" style="font-size: 24px;"></i>
                                <span class="font-weight-bold">এইচআর রিপোর্ট</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Attendances & Announcements Table -->
    <div class="row">
        <div class="col-md-7 grid-margin stretch-card">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold text-dark mb-3"><i class="ti-time text-primary mr-2"></i> আজকের সাম্প্রতিক উপস্থিতি রেকর্ড</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>কর্মী</th>
                                    <th>চেক-ইন</th>
                                    <th>চেক-আউট</th>
                                    <th>স্ট্যাটাস</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentAttendances as $att)
                                <tr>
                                    <td class="font-weight-bold text-dark">{{ $att->user->name ?? 'N/A' }}</td>
                                    <td>{{ $att->check_in ? date('h:i A', strtotime($att->check_in)) : '--' }}</td>
                                    <td>{{ $att->check_out ? date('h:i A', strtotime($att->check_out)) : '--' }}</td>
                                    <td>
                                        @if($att->status == 'present')
                                            <span class="badge badge-success">উপস্থিত</span>
                                        @elseif($att->status == 'late')
                                            <span class="badge badge-warning">দেরি</span>
                                        @else
                                            <span class="badge badge-danger">অনুপস্থিত</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">আজকে এখনও কোনো উপস্থিতি এন্ট্রি করা হয়নি।</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5 grid-margin stretch-card">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold text-dark mb-3"><i class="ti-bell text-warning mr-2"></i> সাম্প্রতিক নোটিশ & ঘোষণা</h5>
                    <ul class="list-group list-group-flush">
                        @forelse($latestAnnouncements as $ann)
                        <li class="list-group-item px-0 py-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="font-weight-bold text-dark" style="font-size: 15px;">{{ $ann->title }}</span>
                                <small class="text-muted">{{ $ann->publish_date }}</small>
                            </div>
                            <p class="text-muted mb-0 small">{{ Str::limit($ann->description, 80) }}</p>
                        </li>
                        @empty
                        <li class="list-group-item px-0 py-4 text-center text-muted border-0">কোনো সাম্প্রতিক নোটিশ নেই।</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
