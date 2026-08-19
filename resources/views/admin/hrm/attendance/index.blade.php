@extends('layouts.backend.app')

@section('title', 'উপস্থিতি রেজিস্টার')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="font-weight-bold mb-0" style="color: #2e7d32;">
                        <i class="ti-alarm-clock mr-2"></i> দৈনিক উপস্থিতি রেজিস্টার (Daily Attendance)
                    </h3>
                    <p class="text-muted mb-0">কর্মকর্তা/কর্মচারীদের চেক-ইন, চেক-আউট ও কাজের সময়সূচি</p>
                </div>
                <button type="button" class="btn btn-success btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#markAttendanceModal" data-toggle="modal" data-target="#markAttendanceModal">
                    <i class="ti-plus mr-1"></i> ম্যানুয়াল উপস্থিতি এন্ট্রি
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

    <!-- Date Picker Filter -->
    <div class="card border-0 shadow-sm rounded-lg mb-4">
        <div class="card-body py-3">
            <form action="{{ route('admin.hrm.attendance.index') }}" method="GET" class="row align-items-center">
                <div class="col-md-6 my-1">
                    <label class="font-weight-bold mb-0 mr-2">উপস্থিতির তারিখ:</label>
                    <input type="date" name="date" class="form-control d-inline-block w-auto bg-light border-0" value="{{ $date }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-6 text-right my-1">
                    <span class="badge badge-success px-3 py-2">তারিখ: {{ date('d F, Y', strtotime($date)) }}</span>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>কর্মী</th>
                            <th>ডিপার্টমেন্ট & পদবী</th>
                            <th>চেক-ইন</th>
                            <th>চেক-আউট</th>
                            <th>মোট কাজের সময়</th>
                            <th>স্ট্যাটাস</th>
                            <th>মন্তব্য</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $att)
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $att->user->name ?? 'N/A' }}</td>
                            <td>
                                <div>{{ $att->user->hrmProfile->department->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $att->user->hrmProfile->designation->name ?? 'N/A' }}</small>
                            </td>
                            <td>
                                @if($att->check_in)
                                    <span class="badge badge-outline-success font-weight-bold"><i class="ti-time mr-1"></i> {{ date('h:i A', strtotime($att->check_in)) }}</span>
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>
                            <td>
                                @if($att->check_out)
                                    <span class="badge badge-outline-primary font-weight-bold"><i class="ti-time mr-1"></i> {{ date('h:i A', strtotime($att->check_out)) }}</span>
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>
                            <td><span class="font-weight-bold text-dark">{{ $att->working_hours }} ঘন্টা</span></td>
                            <td>
                                @if($att->status == 'present')
                                    <span class="badge badge-success">উপস্থিত</span>
                                @elseif($att->status == 'late')
                                    <span class="badge badge-warning">দেরি (Late)</span>
                                @elseif($att->status == 'half_day')
                                    <span class="badge badge-info">হাফ ডে</span>
                                @elseif($att->status == 'leave')
                                    <span class="badge badge-dark">ছুটিতে</span>
                                @else
                                    <span class="badge badge-danger">অনুপস্থিত</span>
                                @endif
                            </td>
                            <td>{{ $att->remarks ?? '--' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">এই তারিখে কোনো উপস্থিতি এন্ট্রি করা হয়নি। নিচে বোতামে ক্লিক করে নতুন এন্ট্রি দিন।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Manual Entry Modal -->
<div class="modal fade" id="markAttendanceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <form action="{{ route('admin.hrm.attendance.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-header-title text-white mb-0"><i class="ti-alarm-clock mr-2"></i> ম্যানুয়াল উপস্থিতি এন্ট্রি</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">কর্মী নির্বাচন করুন <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-control" required>
                            <option value="">-- নির্বাচন করুন --</option>
                            @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->hrmProfile->department->name ?? 'General' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">তারিখ <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control" value="{{ $date }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">চেক-ইন সময়</label>
                            <input type="time" name="check_in" class="form-control" value="09:00">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">চেক-আউট সময়</label>
                            <input type="time" name="check_out" class="form-control" value="17:00">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">উপস্থিতি স্ট্যাটাস <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="present">উপস্থিত (Present)</option>
                            <option value="late">দেরি (Late)</option>
                            <option value="half_day">হাফ ডে (Half Day)</option>
                            <option value="absent">অনুপস্থিত (Absent)</option>
                            <option value="leave">ছুটিতে (On Leave)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">মন্তব্য</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="প্রয়োজনীয় মন্তব্য..."></textarea>
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
