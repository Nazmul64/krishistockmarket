@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content py-3">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-pencil-square-o text-primary me-2"></i> প্রাতিষ্ঠানিক পরিচিতি সিএমএস (Corporate Profile CMS)</h4>
                    <p class="text-muted small mb-0">ইতিহাস, ভিশন, মিশন, উদ্দেশ্য, ভবিষ্যৎ পরিকল্পনা ও অর্জনের তথ্য সম্পাদনা।</p>
                </div>
                <a href="{{ route('corporate.profile') }}" target="_blank" class="btn btn-outline-success btn-sm rounded-pill px-3">
                    <i class="fa fa-external-link me-1"></i> লাইভ পেজ দেখুন
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4">
                @foreach($sections as $sec)
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary-subtle text-primary font-monospace">{{ $sec->section_key }}</span>
                            <span class="badge bg-success">Active</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-3">{{ $sec->title_bn }}</h5>

                        <form action="{{ route('admin.corporate.profile_cms.update', $sec->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">শিরোনাম (বাংলা)</label>
                                <input type="text" name="title_bn" class="form-control" value="{{ $sec->title_bn }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">শিরোনাম (ইংরেজি)</label>
                                <input type="text" name="title_en" class="form-control" value="{{ $sec->title_en }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">বিস্তারিত বিবরণ (বাংলা)</label>
                                <textarea name="content_bn" class="form-control" rows="5" required>{{ $sec->content_bn }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">বিস্তারিত বিবরণ (ইংরেজি)</label>
                                <textarea name="content_en" class="form-control" rows="3">{{ $sec->content_en }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 fw-bold">
                                সংরক্ষণ করুন <i class="fa fa-check ms-1"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

        </section>
    </div>
</div>
@endsection

