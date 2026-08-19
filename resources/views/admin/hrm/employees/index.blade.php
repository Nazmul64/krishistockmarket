@extends('layouts.backend.app')

@section('title', 'কর্মী ডিরেক্টরি')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="font-weight-bold mb-0" style="color: #2e7d32;">
                        <i class="ti-id-badge mr-2"></i> কর্মী ডিরেক্টরি (Employee Management)
                    </h3>
                    <p class="text-muted mb-0">প্রতিষ্ঠানের সকল কর্মকর্তা/কর্মচারীর তালিকা ও প্রোফাইল ব্যবস্থাপনা</p>
                </div>
                <button type="button" class="btn btn-success btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addEmployeeModal" data-toggle="modal" data-target="#addEmployeeModal">
                    <i class="ti-plus mr-1"></i> নতুন কর্মী নিবন্ধন করুন
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

    <!-- Search & Filter Bar -->
    <div class="card border-0 shadow-sm rounded-lg mb-4">
        <div class="card-body py-3">
            <form action="{{ route('admin.hrm.employees.index') }}" method="GET" class="row align-items-center">
                <div class="col-md-4 my-1">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-0"><i class="ti-search"></i></span>
                        </div>
                        <input type="text" name="search" class="form-control bg-light border-0" placeholder="নাম, ইমেইল বা ফোন দিয়ে খুঁজুন..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4 my-1">
                    <select name="department_id" class="form-control bg-light border-0">
                        <option value="">-- সকল ডিপার্টমেন্ট --</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 my-1">
                    <button type="submit" class="btn btn-success btn-block rounded-pill">
                        <i class="ti-filter mr-1"></i> ফিল্টার করুন
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Employee Table -->
    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>আইডি/কোড</th>
                            <th>নাম & প্রোফাইল</th>
                            <th>যোগাযোগ</th>
                            <th>ডিপার্টমেন্ট & পদবী</th>
                            <th>ব্রাঞ্চ & শিফট</th>
                            <th>মূল বেতন</th>
                            <th>স্ট্যাটাস</th>
                            <th class="text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $emp)
                        <tr>
                            <td><span class="badge badge-outline-success font-weight-bold">{{ $emp->hrmProfile->employee_code ?? 'EMP-' . str_pad($emp->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle mr-2 bg-success text-white font-weight-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; rounded-circle;">
                                        {{ strtoupper(substr($emp->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark">{{ $emp->name }}</div>
                                        <small class="text-muted">{{ $emp->hrmProfile->employment_type ?? 'Full Time' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div><i class="ti-email text-muted mr-1"></i> {{ $emp->email }}</div>
                                <small class="text-muted"><i class="ti-mobile text-muted mr-1"></i> {{ $emp->number }}</small>
                            </td>
                            <td>
                                <div class="font-weight-bold text-dark">{{ $emp->hrmProfile->department->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $emp->hrmProfile->designation->name ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <div>{{ $emp->hrmProfile->branch->name ?? 'প্রধান অফিস' }}</div>
                                <small class="text-muted">{{ $emp->hrmProfile->shift->name ?? 'ডে শিফট' }}</small>
                            </td>
                            <td><span class="font-weight-bold text-success">৳{{ number_format($emp->hrmProfile->basic_salary ?? 0) }}</span></td>
                            <td>
                                @if($emp->status == 1)
                                    <span class="badge badge-success">সক্রিয়</span>
                                @else
                                    <span class="badge badge-danger">নিষ্ক্রিয়</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.hrm.employees.show', $emp->id) }}" class="btn btn-info btn-sm rounded-circle px-2" title="সম্পূর্ণ প্রোফাইল দেখুন">
                                    <i class="ti-eye"></i>
                                </a>
                                <a href="{{ route('admin.employee.permissions', $emp->id) }}" class="btn btn-warning btn-sm rounded-circle px-2" title="পারমিশন কনফিগার করুন">
                                    <i class="ti-lock"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">কোনো কর্মকর্তার তথ্য পাওয়া যায়নি।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Register Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <form action="{{ route('admin.hrm.employees.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-header-title text-white mb-0"><i class="ti-user mr-2"></i> নতুন কর্মী নিবন্ধন ফরম</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">পূর্ণ নাম <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="যেমন: মো: রফিকুল ইসলাম" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">কর্মচারী কোড (ID) <span class="text-danger">*</span></label>
                            <input type="text" name="employee_code" class="form-control" value="EMP-{{ rand(1000, 9999) }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">ইমেইল ঠিকানা <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="employee@domain.com" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">মোবাইল নাম্বার <span class="text-danger">*</span></label>
                            <input type="text" name="number" class="form-control" placeholder="01700000000" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">লগইন পাসওয়ার্ড <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="সর্বনিম্ন ৬ অক্ষর" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">যোগদানের তারিখ</label>
                            <input type="date" name="joining_date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">ডিপার্টমেন্ট</label>
                            <select name="department_id" class="form-control">
                                <option value="">-- নির্বাচন করুন --</option>
                                @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">পদবী</label>
                            <select name="designation_id" class="form-control">
                                <option value="">-- নির্বাচন করুন --</option>
                                @foreach($designations as $desig)
                                <option value="{{ $desig->id }}">{{ $desig->name }} ({{ $desig->department->name ?? 'General' }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">ব্রাঞ্চ</label>
                            <select name="branch_id" class="form-control">
                                <option value="">-- নির্বাচন করুন --</option>
                                @foreach($branches as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">শিফট</label>
                            <select name="shift_id" class="form-control">
                                <option value="">-- নির্বাচন করুন --</option>
                                @foreach($shifts as $s)
                                <option value="{{ $s->id }}">{{ $s->name }} ({{ date('h:i A', strtotime($s->start_time)) }} - {{ date('h:i A', strtotime($s->end_time)) }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">মূল বেতন (Basic Salary)</label>
                            <input type="number" name="basic_salary" class="form-control" placeholder="যেমন: 25000">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">রিপোর্টিং ম্যানেজার</label>
                            <select name="manager_id" class="form-control">
                                <option value="">-- নির্বাচন করুন --</option>
                                @foreach($managers as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal" data-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success rounded-pill">নিবন্ধন সম্পন্ন করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
