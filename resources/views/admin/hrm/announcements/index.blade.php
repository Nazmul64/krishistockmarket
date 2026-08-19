@extends('layouts.backend.app')

@section('title', 'ঘোষণা & নোটিশ')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="font-weight-bold mb-0" style="color: #2e7d32;">
                        <i class="ti-announcement mr-2"></i> ঘোষণা & নোটিশ বোর্ড (Announcements)
                    </h3>
                    <p class="text-muted mb-0">কোম্পানির অভ্যন্তরীণ নোটিশ, নির্দেশনা ও ঘোষণা প্রচার</p>
                </div>
                <button type="button" class="btn btn-success btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal" data-toggle="modal" data-target="#addAnnouncementModal">
                    <i class="ti-plus mr-1"></i> নতুন নোটিশ প্রকাশ করুন
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

    <div class="row">
        @forelse($announcements as $ann)
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge badge-success px-3 py-1">{{ $ann->targetDepartment->name ?? 'সকল বিভাগ' }}</span>
                        <small class="text-muted"><i class="ti-calendar mr-1"></i> {{ $ann->publish_date }}</small>
                    </div>
                    <h4 class="font-weight-bold text-dark mb-2">{{ $ann->title }}</h4>
                    <p class="text-muted mb-0" style="white-space: pre-line;">{{ $ann->description }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="col-md-12">
            <div class="card border-0 shadow-sm text-center py-5">
                <p class="text-muted mb-0">কোনো সক্রিয় ঘোষণা বা নোটিশ নেই।</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addAnnouncementModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <form action="{{ route('admin.hrm.announcements.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-header-title text-white mb-0"><i class="ti-plus mr-2"></i> নতুন নোটিশ প্রকাশ করুন</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">শিরোনাম <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="যেমন: ঈদুল ফিতর উপলক্ষে অফিস ছুটি সংক্রান্ত নোটিশ" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">টার্গেট ডিপার্টমেন্ট (ঐচ্ছিক)</label>
                        <select name="target_department_id" class="form-control">
                            <option value="">-- সকল ডিপার্টমেন্ট (All Staff) --</option>
                            @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">প্রকাশের তারিখ</label>
                        <input type="date" name="publish_date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">নোটিশের বিবরণ <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="4" placeholder="নোটিশের বিস্তারিত বার্তা..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal" data-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success rounded-pill">প্রকাশ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
