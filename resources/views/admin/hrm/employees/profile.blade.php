@extends('layouts.backend.app')

@section('title', 'কর্মী প্রোফাইল')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="font-weight-bold mb-0" style="color: #2e7d32;">
                        <i class="ti-user mr-2"></i> {{ $user->name }}-এর বিস্তারিত প্রোফাইল
                    </h3>
                    <p class="text-muted mb-0">আইডি: {{ $user->hrmProfile->employee_code ?? 'EMP-' . str_pad($user->id, 4, '0', STR_PAD_LEFT) }} | পোস্ট: {{ $user->hrmProfile->designation->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <a href="{{ route('admin.hrm.employees.index') }}" class="btn btn-secondary btn-sm rounded-pill shadow-sm">
                        <i class="ti-arrow-left mr-1"></i> কর্মী তালিকায় ফিরে যান
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="ti-check-box mr-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" data-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Sidebar Profile Card -->
        <div class="col-md-4 grid-margin">
            <div class="card border-0 shadow-sm rounded-lg text-center p-4">
                <div class="avatar-circle mx-auto mb-3 bg-success text-white font-weight-bold d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 32px; border-radius: 50%;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h4 class="font-weight-bold mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-2">{{ $user->hrmProfile->designation->name ?? 'কর্মী' }} ({{ $user->hrmProfile->department->name ?? 'সাধারণ' }})</p>
                <div class="mb-3">
                    <span class="badge badge-success px-3 py-2 rounded-pill">কর্মচারী অ্যাকাউন্ট</span>
                </div>
                <hr>
                <div class="text-left">
                    <p class="mb-2"><strong><i class="ti-email text-muted mr-2"></i> ইমেইল:</strong> {{ $user->email }}</p>
                    <p class="mb-2"><strong><i class="ti-mobile text-muted mr-2"></i> ফোন:</strong> {{ $user->number }}</p>
                    <p class="mb-2"><strong><i class="ti-location-pin text-muted mr-2"></i> ব্রাঞ্চ:</strong> {{ $user->hrmProfile->branch->name ?? 'প্রধান শাখা' }}</p>
                    <p class="mb-2"><strong><i class="ti-time text-muted mr-2"></i> শিফট:</strong> {{ $user->hrmProfile->shift->name ?? 'ডে শিফট' }}</p>
                    <p class="mb-0"><strong><i class="ti-calendar text-muted mr-2"></i> যোগদানের তারিখ:</strong> {{ $user->hrmProfile->joining_date ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Detail Tabs -->
        <div class="col-md-8 grid-margin">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-header bg-white border-0 pt-3">
                    <ul class="nav nav-tabs card-header-tabs" id="profileTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active font-weight-bold" id="info-tab" data-toggle="tab" href="#info" role="tab">ব্যক্তিগত & স্যালারি তথ্য</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" id="assets-tab" data-toggle="tab" href="#assets" role="tab">বরাদ্দকৃত অ্যাসেট ({{ count($assets) }})</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" id="leaves-tab" data-toggle="tab" href="#leaves" role="tab">ছুটির হিস্ট্রি</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" id="payroll-tab" data-toggle="tab" href="#payroll" role="tab">পে-রোল হিস্ট্রি</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="profileTabContent">
                        <!-- Info Tab -->
                        <div class="tab-pane fade show active" id="info" role="tabpanel">
                            <form action="{{ route('admin.hrm.employees.update_profile', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold">নাম</label>
                                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold">এমপ্লয়ী কোড</label>
                                        <input type="text" name="employee_code" class="form-control" value="{{ $user->hrmProfile->employee_code ?? '' }}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold">ইমেইল</label>
                                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold">ফোন</label>
                                        <input type="text" name="number" class="form-control" value="{{ $user->number }}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold">ডিপার্টমেন্ট</label>
                                        <select name="department_id" class="form-control">
                                            <option value="">-- নির্বাচন করুন --</option>
                                            @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}" {{ optional($user->hrmProfile)->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold">পদবী</label>
                                        <select name="designation_id" class="form-control">
                                            <option value="">-- নির্বাচন করুন --</option>
                                            @foreach($designations as $desig)
                                            <option value="{{ $desig->id }}" {{ optional($user->hrmProfile)->designation_id == $desig->id ? 'selected' : '' }}>{{ $desig->name }}</option>
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
                                            <option value="{{ $b->id }}" {{ optional($user->hrmProfile)->branch_id == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold">শিফট</label>
                                        <select name="shift_id" class="form-control">
                                            <option value="">-- নির্বাচন করুন --</option>
                                            @foreach($shifts as $s)
                                            <option value="{{ $s->id }}" {{ optional($user->hrmProfile)->shift_id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <h6 class="font-weight-bold text-success mb-3"><i class="ti-money mr-1"></i> বেতন কাঠামো & ব্যাংক তথ্য</h6>
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label class="font-weight-bold">মূল বেতন (Basic)</label>
                                        <input type="number" name="basic_salary" class="form-control" value="{{ optional($user->hrmProfile)->basic_salary ?? 0 }}">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="font-weight-bold">ব্যাংকের নাম</label>
                                        <input type="text" name="bank_name" class="form-control" value="{{ optional($user->hrmProfile)->bank_name }}">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="font-weight-bold">অ্যাকাউন্ট নাম্বার</label>
                                        <input type="text" name="bank_account" class="form-control" value="{{ optional($user->hrmProfile)->bank_account }}">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success rounded-pill px-4">প্রোফাইল তথ্য আপডেট করুন</button>
                            </form>
                        </div>

                        <!-- Assets Tab -->
                        <div class="tab-pane fade" id="assets" role="tabpanel">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>অ্যাসেট নাম</th>
                                        <th>কোড</th>
                                        <th>ক্যাটাগরি</th>
                                        <th>অর্পণের তারিখ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($assets as $ast)
                                    <tr>
                                        <td class="font-weight-bold text-dark">{{ $ast->name }}</td>
                                        <td><span class="badge badge-outline-primary">{{ $ast->asset_code }}</span></td>
                                        <td>{{ ucfirst($ast->category) }}</td>
                                        <td>{{ $ast->assigned_date ?? 'N/A' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">এই কর্মকর্তার নামে কোনো কোম্পানি অ্যাসেট বরাদ্দ নেই।</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Leaves Tab -->
                        <div class="tab-pane fade" id="leaves" role="tabpanel">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>ছুটির ধরন</th>
                                        <th>তারিখ</th>
                                        <th>মোট দিন</th>
                                        <th>স্ট্যাটাস</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($leaves as $lv)
                                    <tr>
                                        <td class="font-weight-bold text-dark">{{ $lv->leaveType->name ?? 'সাধারণ' }}</td>
                                        <td>{{ $lv->start_date }} হতে {{ $lv->end_date }}</td>
                                        <td>{{ $lv->total_days }} দিন</td>
                                        <td>
                                            @if($lv->status == 'approved')
                                                <span class="badge badge-success">অনুমোদিত</span>
                                            @elseif($lv->status == 'pending')
                                                <span class="badge badge-warning">অপেক্ষমাণ</span>
                                            @else
                                                <span class="badge badge-danger">বাতিল</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">কোনো ছুটির আবেদন রেকর্ড পাওয়া যায়নি।</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Payroll Tab -->
                        <div class="tab-pane fade" id="payroll" role="tabpanel">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>মাস/বছর</th>
                                        <th>মূল বেতন</th>
                                        <th>কর্তন</th>
                                        <th>নীট বেতন</th>
                                        <th>স্ট্যাটাস</th>
                                        <th class="text-right">পে-স্লিপ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payrolls as $pr)
                                    <tr>
                                        <td class="font-weight-bold text-dark">{{ $pr->payroll->month_year ?? 'N/A' }}</td>
                                        <td>৳{{ number_format($pr->basic_salary) }}</td>
                                        <td class="text-danger">-৳{{ number_format($pr->absent_deduction + $pr->loan_deduction + $pr->advance_deduction) }}</td>
                                        <td class="font-weight-bold text-success">৳{{ number_format($pr->net_salary) }}</td>
                                        <td>
                                            @if($pr->status == 'paid')
                                                <span class="badge badge-success">পরিশোধিত</span>
                                            @else
                                                <span class="badge badge-warning">অপরিশোধিত</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('admin.hrm.payroll.payslip', $pr->id) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill">
                                                <i class="ti-printer"></i> স্লিপ
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">কোনো পে-রোল বেতন রেকর্ড পাওয়া যায়নি।</td>
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
@endsection
