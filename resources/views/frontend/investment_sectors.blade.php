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
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold font-size-14 mb-2">
                <i class="fa fa-line-chart me-1"></i> লাভজনক ও নিরাপদ যৌথ খামার উদ্যোগ
            </span>
            <h2 class="fw-bold display-6 text-dark mt-2">বিনিয়োগ খাতসমূহ (Investment Sectors)</h2>
            <p class="text-muted max-w-700 mx-auto font-size-16">
                প্রতিষ্ঠানের বিভিন্ন ব্যবসায়িক ও উন্নয়নমূলক ১০টি নির্ধারিত খাতে অংশ নিন। প্রতিটি খাত সম্পূর্ণ নিরীক্ষিত, লাভ-লোকসান ভিত্তিক ও আইনি চুক্তি দ্বারা সুরক্ষিত।
            </p>
            <div class="alert alert-info py-2 px-4 d-inline-block rounded-pill font-size-13 fw-semibold border-0 shadow-sm mt-2">
                <i class="fa fa-info-circle me-1"></i> বিশেষ দ্রষ্টব্য: কোনো নির্দিষ্ট বা ফিক্সড মুনাফার নিশ্চয়তা দেওয়া হয় না। প্রকৃত ব্যবসায়িক ফলাফল ও আইন অনুযায়ী লাভ-লোকসান বণ্টনযোগ্য।
            </div>
        </div>

        <!-- 10 Sectors Grid -->
        <div class="row g-4 mb-5">
            @foreach($sectors as $sec)
            <div class="col-md-6 col-lg-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white p-4 transition-all hover-shadow">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-success rounded-circle p-3 font-size-18 shadow-sm">
                                <i class="{{ $sec->icon ?: 'fa fa-briefcase' }} text-white"></i>
                            </span>
                            <div>
                                <span class="badge bg-light text-muted fw-bold mb-1">খাত নং {{ $sec->sector_no }}</span>
                                <h5 class="fw-bold text-dark mb-0">{{ $sec->name_bn }}</h5>
                                <small class="text-muted">{{ $sec->name_en }}</small>
                            </div>
                        </div>
                    </div>

                    <p class="text-secondary small mb-3" style="line-height: 1.6;">
                        {{ $sec->description }}
                    </p>

                    <div class="row g-2 p-3 bg-light rounded-3 mb-3 font-size-13">
                        <div class="col-6">
                            <span class="text-muted d-block">বিনিয়োগের সীমা:</span>
                            <strong class="text-success font-size-14">{{ $sec->investment_range }}</strong>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block">বিনিয়োগের মেয়াদ:</span>
                            <strong class="text-dark font-size-14">{{ $sec->duration }}</strong>
                        </div>
                        <div class="col-12 mt-2 pt-2 border-top">
                            <span class="text-muted d-block font-size-11">লাভ-লোকসান বণ্টন নীতি:</span>
                            <span class="text-secondary small">{{ $sec->profit_loss_policy ?: 'প্রকৃত ব্যবসায়িক অডিট সাপেক্ষে বণ্টনযোগ্য।' }}</span>
                        </div>
                    </div>

                    <!-- Associated Active Projects under this Sector -->
                    @if($sec->projects && $sec->projects->count() > 0)
                    <div class="mb-3">
                        <span class="text-muted small fw-bold d-block mb-1">চলমান প্রকল্পসমূহ:</span>
                        @foreach($sec->projects as $p)
                        <div class="d-flex justify-content-between align-items-center p-2 mb-1 bg-white border rounded-2 small">
                            <span class="text-dark fw-semibold"><i class="fa fa-folder-open text-warning me-1"></i> {{ $p->project_name }}</span>
                            <a href="{{ route('investment.project.show', $p->id) }}" class="btn btn-outline-success btn-xs px-2 py-0 rounded">বিস্তারিত</a>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div class="mt-auto pt-2 d-flex gap-2">
                        <a href="{{ route('apply.card', ['type' => 'property']) }}" class="btn btn-success rounded-pill px-4 btn-sm fw-bold flex-grow-1">
                            প্রপার্টি কার্ড দিয়ে বিনিয়োগ করুন <i class="fa fa-arrow-right ms-1"></i>
                        </a>
                        <a href="{{ route('corporate.investment_policy') }}" class="btn btn-outline-secondary rounded-pill btn-sm px-3" title="নীতিমালা দেখুন">
                            পলিসি
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Featured Active Investment Projects -->
        @if(isset($featuredProjects) && $featuredProjects->count() > 0)
        <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1 rounded-pill mb-1">সরাসরি অংশ নিন</span>
                    <h3 class="fw-bold text-dark mb-0">বর্তমানে উন্মুক্ত বিনিয়োগ প্রকল্পসমূহ</h3>
                </div>
                <a href="{{ route('corporate.activities') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                    সকল কার্যক্রম দেখুন
                </a>
            </div>

            <div class="row g-4">
                @foreach($featuredProjects as $fp)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border rounded-4 overflow-hidden p-3 shadow-none hover-shadow transition-all">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-dark text-white">{{ $fp->project_id_code }}</span>
                            <span class="badge bg-success-subtle text-success">{{ $fp->duration }}</span>
                        </div>
                        <h6 class="fw-bold text-dark mb-2">{{ $fp->project_name }}</h6>
                        <p class="text-muted small mb-3 flex-grow-1">{{ Str::limit($fp->description, 90) }}</p>

                        <div class="mb-2">
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span>অগ্রগতি:</span>
                                <strong class="text-success">{{ $fp->progress_percentage }}%</strong>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: {{ $fp->progress_percentage }}%;"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-2 border-top small text-muted mb-3">
                            <span>সর্বনিম্ন: <strong>{{ number_format($fp->min_investment) }}৳</strong></span>
                            <span>বিনিয়োগকারী: <strong>{{ $fp->investors_count }} জন</strong></span>
                        </div>

                        <a href="{{ route('investment.project.show', $fp->id) }}" class="btn btn-outline-success btn-sm rounded-pill w-100 fw-bold">
                            প্রকল্পের বিস্তারিত ও আবেদন <i class="fa fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@if(Auth::check())
        </section>
    </div>
</div>
@endif
@endsection
