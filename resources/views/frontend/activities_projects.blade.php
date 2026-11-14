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
            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold font-size-14 mb-2">
                <i class="fa fa-tasks me-1"></i> প্রকল্প বাস্তবায়ন ও মাঠপর্যায়ের কার্যক্রম
            </span>
            <h2 class="fw-bold display-6 text-dark mt-2">প্রাতিষ্ঠানিক কার্যক্রম ও প্রকল্প বাস্তবায়ন</h2>
            <p class="text-muted max-w-700 mx-auto font-size-16">
                প্রকল্পের অগ্রগতি, বাস্তবায়ন হার, মাঠপর্যায়ের নিরীক্ষিত প্রতিবেদন এবং সম্পন্ন, চলমান ও পরিকল্পনাধীন প্রকল্পের সার্বিক চিত্র।
            </p>
        </div>

        <!-- Filter Tabs (Completed, Ongoing, Planned) -->
        <div class="d-flex justify-content-center mb-4">
            <div class="btn-group p-1 bg-white rounded-pill shadow-sm" role="group">
                <a href="{{ route('corporate.activities', ['category' => 'all']) }}" 
                   class="btn rounded-pill px-4 fw-bold {{ ($category ?? 'all') === 'all' ? 'btn-success text-white' : 'btn-light text-muted' }}">
                    সকল প্রকল্প ({{ ($ongoingCount ?? 0) + ($completedCount ?? 0) + ($plannedCount ?? 0) }})
                </a>
                <a href="{{ route('corporate.activities', ['category' => 'ongoing']) }}" 
                   class="btn rounded-pill px-4 fw-bold {{ ($category ?? 'all') === 'ongoing' ? 'btn-success text-white' : 'btn-light text-muted' }}">
                    চলমান প্রকল্প ({{ $ongoingCount ?? 0 }})
                </a>
                <a href="{{ route('corporate.activities', ['category' => 'completed']) }}" 
                   class="btn rounded-pill px-4 fw-bold {{ ($category ?? 'all') === 'completed' ? 'btn-success text-white' : 'btn-light text-muted' }}">
                    সম্পন্ন প্রকল্প ({{ $completedCount ?? 0 }})
                </a>
                <a href="{{ route('corporate.activities', ['category' => 'planned']) }}" 
                   class="btn rounded-pill px-4 fw-bold {{ ($category ?? 'all') === 'planned' ? 'btn-success text-white' : 'btn-light text-muted' }}">
                    পরিকল্পনাধীন ({{ $plannedCount ?? 0 }})
                </a>
            </div>
        </div>

        <!-- Activities / Projects Grid -->
        <div class="row g-4">
            @forelse($activities as $act)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white transition-all hover-shadow">
                    <div class="position-relative">
                        <img src="{{ !empty($act->featured_image) && file_exists(public_path($act->featured_image)) ? asset($act->featured_image) : asset('frontend/assets/images/about/about-img.jpg') }}" 
                             alt="{{ $act->title }}" 
                             class="card-img-top" 
                             style="height: 220px; object-fit: cover;">
                        <span class="badge {{ match($act->category) {
                            'completed' => 'bg-success',
                            'ongoing' => 'bg-warning text-dark',
                            'planned' => 'bg-info text-dark',
                            default => 'bg-secondary',
                        } }} position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill fw-bold shadow-sm">
                            {{ match($act->category) {
                                'completed' => 'সম্পন্ন প্রকল্প',
                                'ongoing' => 'চলমান প্রকল্প',
                                'planned' => 'পরিকল্পনাধীন',
                                default => $act->category,
                            } }}
                        </span>
                        <span class="badge bg-dark text-white position-absolute bottom-0 start-0 m-3 px-2 py-1 rounded small">
                            {{ $act->project_code }}
                        </span>
                    </div>

                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-center gap-2 mb-2 text-muted small">
                            <i class="fa fa-map-marker text-danger"></i> {{ $act->location ?: 'ঢাকা ও সংশ্লিষ্ট অঞ্চল' }}
                        </div>
                        <h5 class="fw-bold text-dark mb-2">{{ $act->title }}</h5>
                        <p class="text-muted small mb-3 flex-grow-1" style="line-height: 1.6;">
                            {{ Str::limit($act->description, 120) }}
                        </p>

                        <!-- Progress Bar (0 to 100%) -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small fw-bold mb-1">
                                <span class="text-dark">বাস্তবায়ন অগ্রগতি:</span>
                                <span class="{{ $act->progress_percentage == 100 ? 'text-success' : 'text-primary' }}">{{ $act->progress_percentage }}%</span>
                            </div>
                            <div class="progress" style="height: 8px; border-radius: 10px;">
                                <div class="progress-bar {{ $act->progress_percentage == 100 ? 'bg-success' : 'bg-primary' }} progress-bar-striped progress-bar-animated" 
                                     role="progressbar" 
                                     style="width: {{ $act->progress_percentage }}%;" 
                                     aria-valuenow="{{ $act->progress_percentage }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                        </div>

                        <!-- Field Report Snippet -->
                        @if($act->field_report)
                        <div class="p-2 bg-light rounded-3 small mb-3 border-start border-3 border-warning">
                            <strong class="text-dark d-block font-size-11 mb-1"><i class="fa fa-file-text-o me-1"></i> মাঠপর্যায়ের প্রতিবেদন:</strong>
                            <span class="text-muted font-size-12">{{ Str::limit($act->field_report, 85) }}</span>
                        </div>
                        @endif

                        <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                            <span class="text-muted small">বাজেট: <strong>{{ number_format($act->budget) }} টাকা</strong></span>
                            @if($act->video_url)
                                <a href="{{ $act->video_url }}" target="_blank" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                    <i class="fa fa-play me-1"></i> ভিডিও
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="bg-white rounded-4 p-5 shadow-sm">
                    <i class="fa fa-folder-open text-muted fa-3x mb-3"></i>
                    <h5 class="text-muted">এই ক্যাটাগরিতে কোনো প্রকল্প পাওয়া যায়নি।</h5>
                    <a href="{{ route('corporate.activities', ['category' => 'all']) }}" class="btn btn-outline-success btn-sm rounded-pill mt-2">সকল প্রকল্প দেখুন</a>
                </div>
            </div>
            @endforelse
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $activities->links() }}
        </div>
    </div>
</div>
@if(Auth::check())
        </section>
    </div>
</div>
@endif
@endsection
