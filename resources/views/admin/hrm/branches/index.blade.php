@extends('layouts.backend.app')

@section('title', 'ব্রাঞ্চসমূহ (Branches)')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="font-weight-bold mb-0" style="color: #2e7d32;">
                        <i class="ti-location-pin mr-2"></i> ব্রাঞ্চসমূহ (Branch Management)
                    </h3>
                    <p class="text-muted mb-0">প্রতিষ্ঠানের সকল শাখা/ব্রাঞ্চ তালিকা ও ব্রাঞ্চ ম্যানেজার</p>
                </div>
                <button type="button" class="btn btn-success btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addBranchModal" data-toggle="modal" data-target="#addBranchModal">
                    <i class="ti-plus mr-1"></i> নতুন ব্রাঞ্চ যোগ করুন
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
                            <th>ব্রাঞ্চ কোড</th>
                            <th>ব্রাঞ্চ নাম</th>
                            <th>ব্রাঞ্চ ম্যানেজার</th>
                            <th>ফোন & ইমেইল</th>
                            <th>কর্মী সংখ্যা</th>
                            <th>স্ট্যাটাস</th>
                            <th class="text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($branches as $index => $branch)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><span class="badge badge-outline-dark font-weight-bold">{{ $branch->code ?? 'N/A' }}</span></td>
                            <td class="font-weight-bold text-dark">{{ $branch->name }}</td>
                            <td>{{ $branch->manager->name ?? 'নির্ধারিত নয়' }}</td>
                            <td>
                                <div><i class="ti-mobile text-muted mr-1"></i> {{ $branch->phone ?? 'N/A' }}</div>
                                <small class="text-muted"><i class="ti-email text-muted mr-1"></i> {{ $branch->email ?? 'N/A' }}</small>
                            </td>
                            <td><span class="badge badge-success">{{ count($branch->employeeProfiles) }} জন</span></td>
                            <td>
                                @if($branch->status == 'active')
                                    <span class="badge badge-success">সক্রিয়</span>
                                @else
                                    <span class="badge badge-danger">নিষ্ক্রিয়</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <button type="button" class="btn btn-primary btn-sm rounded-circle px-2" data-bs-toggle="modal" data-bs-target="#editBranchModal{{ $branch->id }}" data-toggle="modal" data-target="#editBranchModal{{ $branch->id }}">
                                    <i class="ti-pencil"></i>
                                </button>
                                <form action="{{ route('admin.hrm.branches.destroy', $branch->id) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই ব্রাঞ্চটি মুছে ফেলতে চান?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm rounded-circle px-2">
                                        <i class="ti-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editBranchModal{{ $branch->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content border-0 shadow-lg rounded-lg">
                                    <form action="{{ route('admin.hrm.branches.update', $branch->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-header-title text-white mb-0"><i class="ti-pencil mr-2"></i> ব্রাঞ্চ সম্পাদনা</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label class="font-weight-bold">ব্রাঞ্চ নাম <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control" value="{{ $branch->name }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">ব্রাঞ্চ কোড</label>
                                                <input type="text" name="code" class="form-control" value="{{ $branch->code }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">ব্রাঞ্চ ম্যানেজার</label>
                                                <select name="manager_id" class="form-control">
                                                    <option value="">-- নির্বাচন করুন --</option>
                                                    @foreach($employees as $emp)
                                                    <option value="{{ $emp->id }}" {{ $branch->manager_id == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">ফোন নাম্বার</label>
                                                <input type="text" name="phone" class="form-control" value="{{ $branch->phone }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">ইমেইল</label>
                                                <input type="email" name="email" class="form-control" value="{{ $branch->email }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">ঠিকানা</label>
                                                <textarea name="address" class="form-control" rows="2">{{ $branch->address }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">স্ট্যাটাস</label>
                                                <select name="status" class="form-control">
                                                    <option value="active" {{ $branch->status == 'active' ? 'selected' : '' }}>সক্রিয়</option>
                                                    <option value="inactive" {{ $branch->status == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
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
                            <td colspan="8" class="text-center py-4 text-muted">কোনো ব্রাঞ্চ তথ্য পাওয়া যায়নি।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addBranchModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <form action="{{ route('admin.hrm.branches.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-header-title text-white mb-0"><i class="ti-plus mr-2"></i> নতুন ব্রাঞ্চ যোগ করুন</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">ব্রাঞ্চ নাম <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="যেমন: ঢাকা হেড অফিস, চট্টগ্রাম শাখা" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">ব্রাঞ্চ কোড</label>
                        <input type="text" name="code" class="form-control" placeholder="যেমন: DH-01, CTG-02">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">ব্রাঞ্চ ম্যানেজার</label>
                        <select name="manager_id" class="form-control">
                            <option value="">-- নির্বাচন করুন --</option>
                            @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">ফোন নাম্বার</label>
                        <input type="text" name="phone" class="form-control" placeholder="01700000000">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">ইমেইল</label>
                        <input type="email" name="email" class="form-control" placeholder="branch@domain.com">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">ঠিকানা</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="ব্রাঞ্চের সম্পূর্ণ ঠিকানা..."></textarea>
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
