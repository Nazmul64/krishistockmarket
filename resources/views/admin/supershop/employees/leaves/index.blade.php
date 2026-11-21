@extends('layouts.backend.app')

@section('title', 'কর্মচারী ছুটি ব্যবস্থাপনা (Employee Leave Management)')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content">

    <!-- Header & Action -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">
                <i class="ti-time text-primary me-2"></i> কর্মচারী ছুটি ব্যবস্থাপনা ও অনুমোদন (Leave Management)
            </h4>
            <p class="text-muted small mb-0">নৈমিত্তিক, অসুস্থতাজনিত, বাৎসরিক ও জরুরি ছুটির আবেদন, মঞ্জুরি এবং উপস্থিতির সাথে স্বয়ংক্রিয় সংযোগ।</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#newLeaveModal">
                <i class="ti-plus me-1"></i> নতুন ছুটির আবেদন / মঞ্জুরি
            </button>
        </div>
    </div>

    <!-- Leave Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white" style="border-left: 5px solid #10b981 !important;">
                <span class="text-muted small fw-bold">অনুমোদিত ছুটি (Approved)</span>
                <h3 class="fw-bold text-success mt-1 mb-0">{{ $leaves->where('status', 'approved')->count() }} টি</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white" style="border-left: 5px solid #f59e0b !important;">
                <span class="text-muted small fw-bold">অপেক্ষমাণ আবেদন (Pending)</span>
                <h3 class="fw-bold text-warning mt-1 mb-0">{{ $leaves->where('status', 'pending')->count() }} টি</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white" style="border-left: 5px solid #ef4444 !important;">
                <span class="text-muted small fw-bold">প্রত্যাখ্যাত আবেদন (Rejected)</span>
                <h3 class="fw-bold text-danger mt-1 mb-0">{{ $leaves->where('status', 'rejected')->count() }} টি</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white" style="border-left: 5px solid #6366f1 !important;">
                <span class="text-muted small fw-bold">ছুটির প্রকারভেদ</span>
                <h5 class="fw-bold text-primary mt-1 mb-0">নৈমিত্তিক, অসুস্থতা, বাৎসরিক</h5>
            </div>
        </div>
    </div>

    <!-- Leaves List Table -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark"><i class="ti-list text-primary me-2"></i> ছুটির আবেদন ও মঞ্জুরি রেকর্ড</h6>
            <span class="badge bg-light text-dark border">Section 16 Specification</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">কর্মচারী</th>
                        <th>ছুটির ধরন</th>
                        <th>শুরুর তারিখ</th>
                        <th>শেষ তারিখ</th>
                        <th class="text-center">মোট দিন</th>
                        <th>ছুটির কারণ</th>
                        <th>অনুমোদনকারী</th>
                        <th>স্ট্যাটাস</th>
                        <th class="text-center pe-4">একশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $lv)
                        <tr>
                            <td class="ps-4">
                                <a href="{{ route('admin.supershop.employee_system.employees.show', $lv->employee_id) }}" class="fw-bold text-dark text-decoration-none">
                                    {{ $lv->employee->name ?? 'Staff' }}
                                </a>
                                <small class="text-muted d-block">{{ $lv->branch->name ?? '' }} ({{ $lv->employee->designation ?? '' }})</small>
                            </td>
                            <td><span class="badge bg-primary-subtle text-primary fw-bold">{{ ucfirst($lv->leave_type) }} Leave</span></td>
                            <td>{{ date('d M Y', strtotime($lv->start_date)) }}</td>
                            <td>{{ date('d M Y', strtotime($lv->end_date)) }}</td>
                            <td class="text-center fw-bold">{{ $lv->total_days }} দিন</td>
                            <td>{{ $lv->reason }}</td>
                            <td><small class="text-muted">{{ $lv->approver->name ?? 'কর্তৃপক্ষ' }}</small></td>
                            <td>
                                @if($lv->status === 'approved')
                                    <span class="badge bg-success-subtle text-success fw-bold">মঞ্জুরকৃত (Approved)</span>
                                @elseif($lv->status === 'pending')
                                    <span class="badge bg-warning-subtle text-warning fw-bold">অপেক্ষমাণ</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger fw-bold">বাতিলকৃত</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                @if($lv->status === 'pending')
                                    <div class="btn-group btn-group-sm">
                                        <form action="{{ route('admin.supershop.employee_system.leaves.status', $lv->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="btn btn-success" title="মঞ্জুর করুন">
                                                <i class="ti-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.supershop.employee_system.leaves.status', $lv->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="btn btn-danger" title="প্রত্যাখ্যান করুন">
                                                <i class="ti-close"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted small">সম্পন্ন</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">কোনো ছুটির আবেদন নেই।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($leaves->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $leaves->links() }}
            </div>
        @endif
    </div>

    <!-- New Leave Modal (Section 16) -->
    <div class="modal fade" id="newLeaveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.supershop.employee_system.leaves.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h6 class="modal-title fw-bold text-white"><i class="ti-plus me-1"></i> ছুটির আবেদন / মঞ্জুরি ফরম</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">কর্মচারী নির্বাচন করুন <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-select fw-bold" required>
                                <option value="">-- কর্মচারী নির্বাচন করুন --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_id }}) - {{ $emp->designation }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">ছুটির ধরন <span class="text-danger">*</span></label>
                            <select name="leave_type" class="form-select" required>
                                <option value="casual">নৈমিত্তিক ছুটি (Casual Leave)</option>
                                <option value="sick">অসুস্থতাজনিত ছুটি (Sick Leave)</option>
                                <option value="annual">বাৎসরিক ছুটি (Annual Leave)</option>
                                <option value="emergency">জরুরি ছুটি (Emergency Leave)</option>
                                <option value="unpaid">অবৈতনিক ছুটি (Unpaid Leave)</option>
                            </select>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-bold">শুরুর তারিখ <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">শেষ তারিখ <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">ছুটির কারণ <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control" rows="2" placeholder="ছুটির সুনির্দিষ্ট কারণ লিখুন..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                        <button type="submit" class="btn btn-primary fw-bold px-4">ছুটি মঞ্জুর করুন</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

        </section>
    </div>
</div>
@endsection
