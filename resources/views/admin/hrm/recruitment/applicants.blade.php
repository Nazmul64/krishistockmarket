@extends('layouts.backend.app')

@section('title', 'আবেদনকারী তালিকা')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="font-weight-bold mb-0" style="color: #2e7d32;">
                        <i class="ti-user mr-2"></i> {{ $job->title }}-এর আবেদনকারী তালিকা
                    </h3>
                    <p class="text-muted mb-0">মোট আবেদনকারী: {{ count($applicants) }} জন</p>
                </div>
                <a href="{{ route('admin.hrm.recruitment.index') }}" class="btn btn-secondary btn-sm rounded-pill shadow-sm">
                    <i class="ti-arrow-left mr-1"></i> সার্কুলার তালিকায় ফিরে যান
                </a>
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
                            <th>নাম</th>
                            <th>ইমেইল & ফোন</th>
                            <th>অভিজ্ঞতা</th>
                            <th>প্রত্যাশিত বেতন</th>
                            <th>স্ট্যাটাস</th>
                            <th class="text-right">স্ট্যাটাস আপডেট</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applicants as $index => $app)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="font-weight-bold text-dark">{{ $app->name }}</td>
                            <td>
                                <div><i class="ti-email text-muted mr-1"></i> {{ $app->email }}</div>
                                <small class="text-muted"><i class="ti-mobile text-muted mr-1"></i> {{ $app->phone }}</small>
                            </td>
                            <td>{{ $app->experience_years }} বছর</td>
                            <td>৳{{ number_format($app->expected_salary ?? 0) }}</td>
                            <td>
                                @if($app->status == 'hired')
                                    <span class="badge badge-success">নিয়োগপ্রাপ্ত (Hired)</span>
                                @elseif($app->status == 'shortlisted')
                                    <span class="badge badge-primary">শর্টলিস্টেড</span>
                                @elseif($app->status == 'interview')
                                    <span class="badge badge-info">ইন্টারভিউ</span>
                                @elseif($app->status == 'rejected')
                                    <span class="badge badge-danger">বাতিল</span>
                                @else
                                    <span class="badge badge-warning">আবেদনকৃত</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <form action="{{ route('admin.hrm.recruitment.update_applicant_status', $app->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-control form-control-sm d-inline-block w-auto" onchange="this.form.submit()">
                                        <option value="applied" {{ $app->status == 'applied' ? 'selected' : '' }}>Applied</option>
                                        <option value="shortlisted" {{ $app->status == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                        <option value="interview" {{ $app->status == 'interview' ? 'selected' : '' }}>Interview</option>
                                        <option value="hired" {{ $app->status == 'hired' ? 'selected' : '' }}>Hired</option>
                                        <option value="rejected" {{ $app->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">এই বিজ্ঞপ্তিতে কোনো আবেদন জমা পড়েনি।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
