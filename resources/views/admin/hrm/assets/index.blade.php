@extends('layouts.backend.app')

@section('title', 'কোম্পানি অ্যাসেট ম্যানেজমেন্ট')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="font-weight-bold mb-0" style="color: #2e7d32;">
                        <i class="ti-desktop mr-2"></i> কোম্পানি অ্যাসেট ট্র্যাকিং (Asset Management)
                    </h3>
                    <p class="text-muted mb-0">প্রতিষ্ঠানের মালামাল (ল্যাপটপ, আইডি কার্ড, মোবাইল, সিম) রেজিস্টার & অর্পণ</p>
                </div>
                <button type="button" class="btn btn-success btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addAssetModal" data-toggle="modal" data-target="#addAssetModal">
                    <i class="ti-plus mr-1"></i> নতুন অ্যাসেট যোগ করুন
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
                            <th>অ্যাসেট কোড</th>
                            <th>অ্যাসেটের নাম</th>
                            <th>ক্যাটাগরি</th>
                            <th>সিরিয়াল নাম্বার</th>
                            <th>বরাদ্দকৃত কর্মী</th>
                            <th>স্ট্যাটাস</th>
                            <th class="text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $index => $ast)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><span class="badge badge-outline-dark font-weight-bold">{{ $ast->asset_code }}</span></td>
                            <td class="font-weight-bold text-dark">{{ $ast->name }}</td>
                            <td><span class="badge badge-info">{{ ucfirst($ast->category) }}</span></td>
                            <td>{{ $ast->serial_number ?? '--' }}</td>
                            <td>
                                @if($ast->assignedUser)
                                    <span class="font-weight-bold text-success">{{ $ast->assignedUser->name }}</span>
                                @else
                                    <span class="text-muted">অব্যবহৃত</span>
                                @endif
                            </td>
                            <td>
                                @if($ast->status == 'assigned')
                                    <span class="badge badge-success">বরাদ্দকৃত</span>
                                @else
                                    <span class="badge badge-primary">মজুদ (Available)</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if($ast->status == 'available')
                                <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#assignAssetModal{{ $ast->id }}" data-toggle="modal" data-target="#assignAssetModal{{ $ast->id }}">
                                    অর্পণ করুন
                                </button>
                                @else
                                    <span class="text-muted">অর্পিত</span>
                                @endif
                            </td>
                        </tr>

                        <!-- Assign Modal -->
                        <div class="modal fade" id="assignAssetModal{{ $ast->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content border-0 shadow-lg rounded-lg">
                                    <form action="{{ route('admin.hrm.assets.assign', $ast->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-header-title text-white mb-0"><i class="ti-desktop mr-2"></i> কর্মী অর্পণ করুন</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="font-weight-bold">অ্যাসেট: {{ $ast->name }} ({{ $ast->asset_code }})</p>
                                            <div class="form-group">
                                                <label class="font-weight-bold">কর্মী নির্বাচন করুন <span class="text-danger">*</span></label>
                                                <select name="assigned_user_id" class="form-control" required>
                                                    <option value="">-- নির্বাচন করুন --</option>
                                                    @foreach($employees as $emp)
                                                    <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->email }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal" data-dismiss="modal">বাতিল</button>
                                            <button type="submit" class="btn btn-primary rounded-pill">অর্পণ নিশ্চিত করুন</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">কোনো অ্যাসেট রেকর্ড পাওয়া যায়নি।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addAssetModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <form action="{{ route('admin.hrm.assets.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-header-title text-white mb-0"><i class="ti-plus mr-2"></i> নতুন কোম্পানি অ্যাসেট যোগ করুন</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">অ্যাসেটের নাম <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="যেমন: HP ProBook 450 G8" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">অ্যাসেট কোড/ট্যাগ <span class="text-danger">*</span></label>
                        <input type="text" name="asset_code" class="form-control" value="AST-{{ rand(1000, 9999) }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">ক্যাটাগরি <span class="text-danger">*</span></label>
                            <select name="category" class="form-control" required>
                                <option value="laptop">ল্যাপটপ / কম্পিউটার</option>
                                <option value="mobile">মোবাইল হ্যান্ডসেট</option>
                                <option value="sim">অফিসিয়াল সিম</option>
                                <option value="id_card">আইডি কার্ড / কি-কার্ড</option>
                                <option value="equipment">অন্যান্য যন্ত্রপাতি</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">সিরিয়াল নাম্বার</label>
                            <input type="text" name="serial_number" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">প্রাথমিক অর্পণ (ঐচ্ছিক)</label>
                        <select name="assigned_user_id" class="form-control">
                            <option value="">-- অর্পণ করবেন না (Available) --</option>
                            @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
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
