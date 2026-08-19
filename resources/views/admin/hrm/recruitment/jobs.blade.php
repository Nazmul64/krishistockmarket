@extends('layouts.backend.app')

@section('title', 'রিক্রুটমেন্ট & সার্কুলার')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="font-weight-bold mb-0" style="color: #2e7d32;">
                        <i class="ti-briefcase mr-2"></i> রিক্রুটমেন্ট & সার্কুলার (Recruitment Management)
                    </h3>
                    <p class="text-muted mb-0">নিয়োগ বিজ্ঞপ্তি প্রকাশ ও আবেদনকারী ট্র্যাকিং</p>
                </div>
                <button type="button" class="btn btn-success btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addJobModal" data-toggle="modal" data-target="#addJobModal">
                    <i class="ti-plus mr-1"></i> নতুন সার্কুলার প্রকাশ করুন
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
                            <th>পদের নাম</th>
                            <th>ডিপার্টমেন্ট</th>
                            <th>পদসংখ্যা</th>
                            <th>বেতন স্কেল</th>
                            <th>শেষ তারিখ</th>
                            <th>আবেদনকারী সংখ্যা</th>
                            <th>স্ট্যাটাস</th>
                            <th class="text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobPosts as $index => $job)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="font-weight-bold text-dark">{{ $job->title }}</td>
                            <td><span class="badge badge-info">{{ $job->department->name ?? 'General' }}</span></td>
                            <td>{{ $job->vacancy }} জন</td>
                            <td>{{ $job->salary_range ?? 'আলোচনা সাপেক্ষে' }}</td>
                            <td>{{ $job->deadline ?? 'N/A' }}</td>
                            <td><span class="badge badge-success px-3 py-1 font-weight-bold">{{ count($job->applicants) }} জন</span></td>
                            <td>
                                @if($job->status == 'active')
                                    <span class="badge badge-success">চলমান</span>
                                @else
                                    <span class="badge badge-secondary">বন্ধ</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('admin.hrm.recruitment.applicants', $job->id) }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                    <i class="ti-eye mr-1"></i> আবেদনসমূহ দেখুন
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">কোনো নিয়োগ বিজ্ঞপ্তি প্রকাশ করা হয়নি।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addJobModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <form action="{{ route('admin.hrm.recruitment.store_job') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-header-title text-white mb-0"><i class="ti-plus mr-2"></i> নতুন সার্কুলার যোগ করুন</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">বিজ্ঞপ্তির শিরোনাম <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="যেমন: ফিল্ড ম্যানেজার নিয়োগ" required>
                    </div>
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
                            <label class="font-weight-bold">পদসংখ্যা <span class="text-danger">*</span></label>
                            <input type="number" name="vacancy" class="form-control" value="1" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">বেতন স্কেল</label>
                            <input type="text" name="salary_range" class="form-control" placeholder="যেমন: 20,000 - 30,000 ৳">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">আবেদনের শেষ তারিখ</label>
                            <input type="date" name="deadline" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">বিবরণ & যোগ্যতা</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="যোগ্যতা ও প্রয়োজনীয় তথ্যাবলী..."></textarea>
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
