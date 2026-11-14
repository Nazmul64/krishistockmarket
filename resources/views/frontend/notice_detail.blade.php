@extends(Auth::check() ? 'layouts.backend.app' : 'layouts.frontend.app')

@section('content')
@if(Auth::check())
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">
@endif
<div class="corporate-page-wrap py-5 bg-light">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('/') }}">হোম</a></li>
                <li class="breadcrumb-item"><a href="{{ route('corporate.notices') }}">নোটিশ বোর্ড</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($notice->title, 40) }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- Notice Content -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <span class="badge {{ match($notice->notice_type) {
                            'urgent' => 'bg-danger',
                            'announcement' => 'bg-primary',
                            'project_update' => 'bg-success',
                            default => 'bg-secondary',
                        } }} px-3 py-1 rounded-pill">
                            {{ match($notice->notice_type) {
                                'urgent' => 'জরুরি নোটিশ',
                                'announcement' => 'প্রশাসনিক ঘোষণা',
                                'project_update' => 'প্রকল্প আপডেট',
                                default => 'সাধারণ নোটিশ',
                            } }}
                        </span>
                        <span class="text-muted small"><i class="fa fa-calendar me-1"></i> প্রকাশের তারিখ: {{ \Carbon\Carbon::parse($notice->publish_date)->isoFormat('D MMMM, YYYY') }}</span>
                    </div>

                    <h3 class="fw-bold text-dark mb-4">{{ $notice->title }}</h3>

                    <div class="notice-body text-secondary leading-relaxed font-size-15 mb-4" style="line-height: 1.8; text-align: justify;">
                        {!! nl2br(e($notice->content)) !!}
                    </div>

                    @if($notice->attachment_file)
                    <div class="p-3 bg-light rounded-3 mb-4 d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fa fa-paperclip text-muted fa-lg me-2"></i>
                            <span class="fw-semibold text-dark">সংযুক্তি ফাইল (Official Attachment)</span>
                        </div>
                        <a href="{{ asset($notice->attachment_file) }}" target="_blank" class="btn btn-primary rounded-pill btn-sm px-4 fw-bold">
                            <i class="fa fa-download me-1"></i> ডাউনলোড
                        </a>
                    </div>
                    @endif

                    <div class="pt-3 border-top d-flex justify-content-between">
                        <a href="{{ route('corporate.notices') }}" class="btn btn-outline-secondary rounded-pill btn-sm px-4">
                            <i class="fa fa-arrow-left me-1"></i> নোটিশ বোর্ডে ফিরে যান
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Notices Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa fa-clock-o text-muted me-1"></i> সাম্প্রতিক নোটিশ</h5>
                    <div class="list-group list-group-flush small">
                        @foreach($recentNotices as $rn)
                        <a href="{{ route('corporate.notice.show', $rn->id) }}" class="list-group-item list-group-item-action px-0 py-2 border-0">
                            <span class="text-muted d-block font-size-11">{{ \Carbon\Carbon::parse($rn->publish_date)->format('d M, Y') }}</span>
                            <span class="fw-semibold text-dark">{{ Str::limit($rn->title, 60) }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@if(Auth::check())
        </section>
    </div>
</div>
@endif
@endsection

