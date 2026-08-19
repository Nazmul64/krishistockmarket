@extends('layouts.backend.app')

@section('title', 'পারফরম্যান্স & KPI রিভিউ')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="font-weight-bold mb-0" style="color: #2e7d32;">
                        <i class="ti-star mr-2"></i> পারফরম্যান্স & KPI মূল্যায়ন (Performance Evaluation)
                    </h3>
                    <p class="text-muted mb-0">কর্মকর্তাদের কর্মদক্ষতা, রেটিং ও বোনাস/প্রমোশন রেকর্ড</p>
                </div>
                <button type="button" class="btn btn-success btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addPerformanceModal" data-toggle="modal" data-target="#addPerformanceModal">
                    <i class="ti-plus mr-1"></i> নতুন মূল্যায়ন যোগ করুন
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
                            <th>কর্মী</th>
                            <th>রিভিউয়ের সময়কাল</th>
                            <th>ওভারঅল রেটিং (Out of 5)</th>
                            <th>মূল্যায়নকারী</th>
                            <th>মন্তব্য</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($performances as $index => $perf)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="font-weight-bold text-dark">{{ $perf->user->name ?? 'N/A' }}</td>
                            <td><span class="badge badge-info">{{ $perf->review_period }}</span></td>
                            <td>
                                <span class="badge badge-warning text-dark font-weight-bold px-3 py-1">
                                    <i class="ti-star text-warning"></i> {{ $perf->overall_rating }} / 5.0
                                </span>
                            </td>
                            <td>{{ $perf->reviewer->name ?? 'Admin' }}</td>
                            <td>{{ $perf->comments ?? '--' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">কোনো পারফরম্যান্স রিভিউ পাওয়া যায়নি।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addPerformanceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <form action="{{ route('admin.hrm.performance.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-header-title text-white mb-0"><i class="ti-plus mr-2"></i> নতুন পারফরম্যান্স মূল্যায়ন</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">কর্মী নির্বাচন করুন <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-control" required>
                            <option value="">-- নির্বাচন করুন --</option>
                            @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">রিভিউয়ের সময়কাল <span class="text-danger">*</span></label>
                        <input type="text" name="review_period" class="form-control" placeholder="যেমন: 2026-Q3 বা 2026-August" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">ওভারঅল রেটিং (১ হতে ৫) <span class="text-danger">*</span></label>
                        <select name="overall_rating" class="form-control" required>
                            <option value="5.0">5.0 - চমৎকার (Outstanding)</option>
                            <option value="4.0" selected>4.0 - খুব ভালো (Very Good)</option>
                            <option value="3.0">3.0 - সন্তোষজনক (Good)</option>
                            <option value="2.0">2.0 - মানসম্মত নয় (Needs Improvement)</option>
                            <option value="1.0">1.0 - দুর্বল (Unsatisfactory)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">মন্তব্য & ফিডব্যাক</label>
                        <textarea name="comments" class="form-control" rows="3" placeholder="কর্মদক্ষতা সম্পর্কে মন্তব্য..."></textarea>
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
