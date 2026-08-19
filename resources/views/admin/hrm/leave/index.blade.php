@extends('layouts.backend.app')

@section('title', 'ছুটি অনুমোদন & তালিকা')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="font-weight-bold mb-0" style="color: #2e7d32;">
                        <i class="ti-calendar mr-2"></i> ছুটি ম্যানেজমেন্ট (Leave Requests & Approval)
                    </h3>
                    <p class="text-muted mb-0">কর্মীদের ছুটির আবেদন পর্যালোচনা ও অনুমোদন</p>
                </div>
                <button type="button" class="btn btn-success btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addLeaveTypeModal" data-toggle="modal" data-target="#addLeaveTypeModal">
                    <i class="ti-plus mr-1"></i> নতুন ছুটির ধরন যোগ করুন
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="ti-check-box mr-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" data-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Status Tabs -->
    <div class="card border-0 shadow-sm rounded-lg mb-4">
        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('admin.hrm.leave.index', ['status' => 'all']) }}" class="btn btn-sm rounded-pill {{ $status == 'all' ? 'btn-success font-weight-bold' : 'btn-outline-secondary' }} mr-2">সকল আবেদন</a>
                    <a href="{{ route('admin.hrm.leave.index', ['status' => 'pending']) }}" class="btn btn-sm rounded-pill {{ $status == 'pending' ? 'btn-warning font-weight-bold' : 'btn-outline-warning' }} mr-2">অপেক্ষমাণ (Pending)</a>
                    <a href="{{ route('admin.hrm.leave.index', ['status' => 'approved']) }}" class="btn btn-sm rounded-pill {{ $status == 'approved' ? 'btn-success font-weight-bold' : 'btn-outline-success' }} mr-2">অনুমোদিত (Approved)</a>
                    <a href="{{ route('admin.hrm.leave.index', ['status' => 'rejected']) }}" class="btn btn-sm rounded-pill {{ $status == 'rejected' ? 'btn-danger font-weight-bold' : 'btn-outline-danger' }}">বাতিলকৃত (Rejected)</a>
                </div>
                <div>
                    <small class="text-muted font-weight-bold">মোট বিষয়শ্রেণী: {{ count($leaveTypes) }} টি</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Requests Table -->
    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>কর্মী</th>
                            <th>ছুটির ধরন</th>
                            <th>তারিখ সীমা</th>
                            <th>মোট দিন</th>
                            <th>কারণ</th>
                            <th>অনুমোদনকারী</th>
                            <th>স্ট্যাটাস</th>
                            <th class="text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaveRequests as $req)
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $req->user->name ?? 'N/A' }}</td>
                            <td><span class="badge badge-info">{{ $req->leaveType->name ?? 'সাধারণ' }}</span></td>
                            <td>{{ $req->start_date }} হতে {{ $req->end_date }}</td>
                            <td><span class="font-weight-bold text-dark">{{ $req->total_days }} দিন</span></td>
                            <td>{{ Str::limit($req->reason, 40) }}</td>
                            <td>{{ $req->approver->name ?? '--' }}</td>
                            <td>
                                @if($req->status == 'approved')
                                    <span class="badge badge-success">অনুমোদিত</span>
                                @elseif($req->status == 'pending')
                                    <span class="badge badge-warning">অপেক্ষমাণ</span>
                                @else
                                    <span class="badge badge-danger">বাতিল</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if($req->status == 'pending')
                                <form action="{{ route('admin.hrm.leave.update_status', $req->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 mr-1">অনুমোদন</button>
                                </form>
                                <form action="{{ route('admin.hrm.leave.update_status', $req->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3">বাতিল</button>
                                </form>
                                @else
                                    <span class="text-muted">সম্পন্ন</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">কোনো ছুটির আবেদন পাওয়া যায়নি।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $leaveRequests->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add Leave Type Modal -->
<div class="modal fade" id="addLeaveTypeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <form action="{{ route('admin.hrm.leave.store_type') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-header-title text-white mb-0"><i class="ti-plus mr-2"></i> নতুন ছুটির ধরন যোগ করুন</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">ছুটির ক্যাটাগরি নাম <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="যেমন: ক্যাজুয়াল লিভ, শিক লিভ, মাতৃত্বকালীন ছুটি" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">বছরে সর্বোচ্চ দিন <span class="text-danger">*</span></label>
                        <input type="number" name="days_allowed" class="form-control" value="14" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal" data-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success rounded-pill">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
