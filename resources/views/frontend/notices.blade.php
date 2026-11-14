@extends(Auth::check() ? 'layouts.backend.app' : 'layouts.frontend.app')

@section('content')
@if(Auth::check())
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">
@endif
<div class="corporate-page-wrap py-5 bg-light">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5">
            <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fw-bold font-size-14 mb-2">
                <i class="fa fa-bell me-1"></i> অফিসিয়াল নোটিশ ও প্রশাসনিক ঘোষণা
            </span>
            <h2 class="fw-bold display-6 text-dark mt-2">নোটিশ বোর্ড (Notice Board)</h2>
            <p class="text-muted max-w-700 mx-auto font-size-16">
                প্রতিষ্ঠানের নিয়মিত নোটিশ, জরুরি নিরাপত্তা ঘোষণা, প্রকল্প আপডেট ও তারিখভিত্তিক নোটিশ আর্কাইভ।
            </p>
        </div>

        <!-- Filter Category Pills -->
        <div class="d-flex justify-content-center mb-4">
            <div class="btn-group p-1 bg-white rounded-pill shadow-sm flex-wrap" role="group">
                <a href="{{ route('corporate.notices', ['type' => 'all']) }}" 
                   class="btn rounded-pill px-3 fw-bold {{ ($type ?? 'all') === 'all' ? 'btn-success text-white' : 'btn-light text-muted' }}">
                    সকল নোটিশ
                </a>
                <a href="{{ route('corporate.notices', ['type' => 'announcement']) }}" 
                   class="btn rounded-pill px-3 fw-bold {{ ($type ?? 'all') === 'announcement' ? 'btn-success text-white' : 'btn-light text-muted' }}">
                    প্রশাসনিক ঘোষণা
                </a>
                <a href="{{ route('corporate.notices', ['type' => 'urgent']) }}" 
                   class="btn rounded-pill px-3 fw-bold {{ ($type ?? 'all') === 'urgent' ? 'btn-danger text-white' : 'btn-light text-muted' }}">
                    জরুরি নোটিশ
                </a>
                <a href="{{ route('corporate.notices', ['type' => 'project_update']) }}" 
                   class="btn rounded-pill px-3 fw-bold {{ ($type ?? 'all') === 'project_update' ? 'btn-warning text-dark' : 'btn-light text-muted' }}">
                    প্রকল্প ও বিনিয়োগ আপডেট
                </a>
                <a href="{{ route('corporate.notices', ['type' => 'general']) }}" 
                   class="btn rounded-pill px-3 fw-bold {{ ($type ?? 'all') === 'general' ? 'btn-secondary text-white' : 'btn-light text-muted' }}">
                    সাধারণ নোটিশ
                </a>
            </div>
        </div>

        <!-- Notices List -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="list-group list-group-flush">
                        @forelse($notices as $n)
                        <div class="list-group-item p-4 transition-all hover-bg-light {{ $n->is_pinned ? 'bg-light-subtle border-start border-4 border-warning' : '' }}">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    @if($n->is_pinned)
                                        <span class="badge bg-warning text-dark"><i class="fa fa-thumb-tack me-1"></i> পিন করা নোটিশ</span>
                                    @endif
                                    <span class="badge {{ match($n->notice_type) {
                                        'urgent' => 'bg-danger',
                                        'announcement' => 'bg-primary',
                                        'project_update' => 'bg-success',
                                        default => 'bg-secondary',
                                    } }} px-3 py-1 rounded-pill">
                                        {{ match($n->notice_type) {
                                            'urgent' => 'জরুরি নোটিশ',
                                            'announcement' => 'প্রশাসনিক ঘোষণা',
                                            'project_update' => 'প্রকল্প আপডেট',
                                            default => 'সাধারণ নোটিশ',
                                        } }}
                                    </span>
                                </div>
                                <span class="text-muted small"><i class="fa fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($n->publish_date)->isoFormat('D MMMM, YYYY') }}</span>
                            </div>

                            <h5 class="fw-bold text-dark mb-2">
                                <a href="{{ route('corporate.notice.show', $n->id) }}" class="text-dark text-decoration-none hover-primary">
                                    {{ $n->title }}
                                </a>
                            </h5>

                            <p class="text-secondary small mb-3" style="line-height: 1.6;">
                                {{ Str::limit(strip_tags($n->content), 180) }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('corporate.notice.show', $n->id) }}" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold">
                                    সম্পূর্ণ নোটিশ পড়ুন <i class="fa fa-arrow-right ms-1"></i>
                                </a>
                                @if($n->attachment_file)
                                <a href="{{ asset($n->attachment_file) }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                    <i class="fa fa-download me-1"></i> সংযুক্তি ডাউনলোড
                                </a>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <i class="fa fa-bell-slash-o text-muted fa-3x mb-3"></i>
                            <h5 class="text-muted">কোনো নোটিশ পাওয়া যায়নি।</h5>
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $notices->links() }}
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
