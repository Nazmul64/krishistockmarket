@extends('layouts.backend.app')

@section('title', 'ডিপার্টমেন্ট ম্যানেজমেন্ট')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="font-weight-bold mb-0" style="color: #2e7d32;">
                        <i class="ti-layers mr-2"></i> ডিপার্টমেন্ট ম্যানেজমেন্ট (Departments)
                    </h3>
                    <p class="text-muted mb-0">প্রতিষ্ঠানের সকল বিভাগ ও বিভাগীয় প্রধান তালিকা</p>
                </div>
                <button type="button" class="btn btn-success btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addDepartmentModal" data-toggle="modal" data-target="#addDepartmentModal">
                    <i class="ti-plus mr-1"></i> নতুন ডিপার্টমেন্ট যোগ করুন
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

    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>কোড</th>
                            <th>ডিপার্টমেন্ট নাম</th>
                            <th>বিভাগীয় প্রধান (Head)</th>
                            <th>পদবী সংখ্যা</th>
                            <th>কর্মী সংখ্যা</th>
                            <th>স্ট্যাটাস</th>
                            <th class="text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $index => $dept)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><span class="badge badge-outline-dark font-weight-bold">{{ $dept->code ?? 'N/A' }}</span></td>
                            <td class="font-weight-bold text-dark">{{ $dept->name }}</td>
                            <td>{{ $dept->head->name ?? 'নির্ধারিত নয়' }}</td>
                            <td><span class="badge badge-info">{{ count($dept->designations) }} টি</span></td>
                            <td><span class="badge badge-success">{{ count($dept->employeeProfiles) }} জন</span></td>
                            <td>
                                @if($dept->status == 'active')
                                    <span class="badge badge-success">সক্রিয়</span>
                                @else
                                    <span class="badge badge-danger">নিষ্ক্রিয়</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <button type="button" class="btn btn-primary btn-sm rounded-circle px-2" data-bs-toggle="modal" data-bs-target="#editDepartmentModal{{ $dept->id }}" data-toggle="modal" data-target="#editDepartmentModal{{ $dept->id }}">
                                    <i class="ti-pencil"></i>
                                </button>
                                <form action="{{ route('admin.hrm.departments.destroy', $dept->id) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই ডিপার্টমেন্টটি মুছে ফেলতে চান?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm rounded-circle px-2">
                                        <i class="ti-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editDepartmentModal{{ $dept->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content border-0 shadow-lg rounded-lg">
                                    <form action="{{ route('admin.hrm.departments.update', $dept->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-header-title text-white mb-0"><i class="ti-pencil mr-2"></i> ডিপার্টমেন্ট সম্পাদনা</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label class="font-weight-bold">ডিপার্টমেন্ট নাম <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control" value="{{ $dept->name }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">ডিপার্টমেন্ট কোড</label>
                                                <input type="text" name="code" class="form-control" value="{{ $dept->code }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">বিভাগীয় প্রধান (Head)</label>
                                                <select name="head_id" class="form-control">
                                                    <option value="">-- নির্বাচন করুন --</option>
                                                    @foreach($employees as $emp)
                                                    <option value="{{ $emp->id }}" {{ $dept->head_id == $emp->id ? 'selected' : '' }}>{{ $emp->name }} ({{ $emp->email }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">বিবরণ</label>
                                                <textarea name="description" class="form-control" rows="3">{{ $dept->description }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">স্ট্যাটাস</label>
                                                <select name="status" class="form-control">
                                                    <option value="active" {{ $dept->status == 'active' ? 'selected' : '' }}>সক্রিয় (Active)</option>
                                                    <option value="inactive" {{ $dept->status == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয় (Inactive)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal" data-dismiss="modal">বাতিল</button>
                                            <button type="submit" class="btn btn-primary rounded-pill">আপডেট করুন</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">কোনো ডিপার্টমেন্ট তথ্য পাওয়া যায়নি।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addDepartmentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <form action="{{ route('admin.hrm.departments.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-header-title text-white mb-0"><i class="ti-plus mr-2"></i> নতুন ডিপার্টমেন্ট যোগ করুন</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">ডিপার্টমেন্ট নাম <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="যেমন: আইটি, সেলস, ফাইনান্স" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">ডিপার্টমেন্ট কোড</label>
                        <input type="text" name="code" class="form-control" placeholder="যেমন: IT, HR, FIN">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">বিভাগীয় প্রধান (Head)</label>
                        <select name="head_id" class="form-control">
                            <option value="">-- নির্বাচন করুন --</option>
                            @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">বিবরণ</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="ডিপার্টমেন্টের কাজের সংক্ষিপ্ত বিবরণ..."></textarea>
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
