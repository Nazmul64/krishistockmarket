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
                <li class="breadcrumb-item"><a href="{{ route('investment.sectors') }}">বিনিয়োগ খাতসমূহ</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $project->project_name }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- Project Main Info -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
                    <img src="{{ !empty($project->featured_image) && file_exists(public_path($project->featured_image)) ? asset($project->featured_image) : asset('frontend/assets/images/about/about-img.jpg') }}" 
                         alt="{{ $project->project_name }}" 
                         class="card-img-top" 
                         style="height: 340px; object-fit: cover;">
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <span class="badge bg-dark text-white px-3 py-2 font-size-13">{{ $project->project_id_code }}</span>
                            <span class="badge bg-success-subtle text-success px-3 py-2 font-size-13 fw-bold">
                                {{ $project->sector->name_bn ?? 'বিনিয়োগ খাত' }}
                            </span>
                            <span class="badge bg-warning text-dark px-3 py-2 font-size-13">
                                স্ট্যাটাস: {{ $project->status === 'running' ? 'চলমান' : 'উন্মুক্ত' }}
                            </span>
                        </div>

                        <h3 class="fw-bold text-dark mb-3">{{ $project->project_name }}</h3>
                        <p class="text-secondary leading-relaxed font-size-15 mb-4" style="line-height: 1.8;">
                            {{ $project->description }}
                        </p>

                        <!-- Progress Bar Tracker -->
                        <div class="p-3 bg-light rounded-4 mb-4">
                            <div class="d-flex justify-content-between small fw-bold mb-2">
                                <span class="text-dark">প্রকল্প বাস্তবায়ন অগ্রগতি:</span>
                                <span class="text-success font-size-15">{{ $project->progress_percentage }}% সম্পন্ন</span>
                            </div>
                            <div class="progress" style="height: 10px; border-radius: 10px;">
                                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                                     style="width: {{ $project->progress_percentage }}%;"></div>
                            </div>
                        </div>

                        <!-- Progress Report / Field Updates -->
                        @if($project->progress_report)
                        <div class="mb-4">
                            <h5 class="fw-bold text-dark mb-2"><i class="fa fa-newspaper-o text-success me-2"></i> মাঠপর্যায়ের অগ্রগতি প্রতিবেদন</h5>
                            <div class="p-3 bg-light rounded-3 text-secondary font-size-14" style="line-height: 1.7;">
                                {{ $project->progress_report }}
                            </div>
                        </div>
                        @endif

                        <!-- Profit/Loss Policy -->
                        <div class="mb-4">
                            <h5 class="fw-bold text-dark mb-2"><i class="fa fa-balance-scale text-warning me-2"></i> লাভ-লোকসান বণ্টন নীতিমালা</h5>
                            <div class="p-3 rounded-3 font-size-14 bg-warning-subtle text-dark border-start border-4 border-warning">
                                {{ $project->profit_policy ?: 'প্রকল্পের বাস্তব ফসল/পণ্য বিক্রয় পরবর্তী নিট আয়ের ওপর ভিত্তি করে নিরীক্ষিত পদ্ধতিতে লভ্যাংশ বণ্টনযোগ্য।' }}
                            </div>
                        </div>

                        <!-- Legal Disclaimer -->
                        <div class="alert alert-secondary small mb-0">
                            <strong>আইনি সতর্কতা:</strong> এই প্ল্যাটফর্মের কোনো প্রকল্পে নিশ্চিত বা নির্দিষ্ট মুনাফার কোনো প্রতিশ্রুতি দেওয়া হয় না। সকল বিনিয়োগের রিটার্ন বাস্তব ব্যবসায়িক লাভ-ক্ষতির ওপর নির্ভরশীল।
                        </div>
                    </div>
                </div>
            </div>

            <!-- Investment Sidebar & Application Form -->
            <div class="col-lg-4">
                <!-- Investment Limits & Summary Card -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h5 class="fw-bold text-dark mb-3">বিনিয়োগের কাঠামো ও তথ্য</h5>
                    <ul class="list-group list-group-flush small mb-3">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">সর্বনিম্ন বিনিয়োগ:</span>
                            <strong class="text-success">{{ number_format($project->min_investment) }} টাকা</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">সর্বোচ্চ বিনিয়োগ:</span>
                            <strong class="text-dark">{{ number_format($project->max_investment) }} টাকা</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">প্রকল্পের মেয়াদ:</span>
                            <strong>{{ $project->duration }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">মোট বাজেট:</span>
                            <strong>{{ number_format($project->target_budget) }} টাকা</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">সংগৃহীত মূলধন:</span>
                            <strong class="text-primary">{{ number_format($project->total_raised) }} টাকা</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">মোট বিনিয়োগকারী:</span>
                            <strong class="text-dark">{{ $project->investors_count }} জন</strong>
                        </li>
                    </ul>

                    @auth
                    <!-- Instant Investment Form for Logged in Users -->
                    <div class="pt-3 border-top">
                        <h6 class="fw-bold text-dark mb-2">এই প্রকল্পে বিনিয়োগের আবেদন</h6>
                        <form action="{{ route('investment.project.invest', $project->id) }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <label class="small text-muted mb-1">বিনিয়োগের পরিমাণ (টাকা):</label>
                                <input type="number" name="amount" class="form-control form-control-sm" min="{{ $project->min_investment }}" max="{{ $project->max_investment }}" value="{{ $project->min_investment }}" required>
                            </div>
                            <div class="mb-2">
                                <label class="small text-muted mb-1">পেমেন্ট মেথড:</label>
                                <select name="payment_method" class="form-select form-select-sm" required>
                                    <option value="bank_transfer">ব্যাংক ট্রান্সফার (Bank Transfer)</option>
                                    <option value="bkash">বিকাশ (bKash)</option>
                                    <option value="nagad">নগদ (Nagad)</option>
                                    <option value="rocket">রকেট (Rocket)</option>
                                    <option value="wallet">অ্যাকাউন্ট ওয়ালেট ব্যালেন্স</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="small text-muted mb-1">ট্রানজাকশন আইডি (TrxID) / স্লিপ নং:</label>
                                <input type="text" name="transaction_id" class="form-control form-control-sm" placeholder="যেমন: TXN-12345678" required>
                            </div>
                            <button type="submit" class="btn btn-success rounded-pill w-100 fw-bold btn-sm py-2">
                                বিনিয়োগের আবেদন নিশ্চিত করুন <i class="fa fa-check-circle ms-1"></i>
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="text-center p-3 bg-light rounded-3">
                        <p class="text-muted small mb-2">প্রকল্পে বিনিয়োগ করতে অথবা প্রপার্টি কার্ডে অংশ নিতে অনুগ্রহ করে লগইন করুন।</p>
                        <a href="{{ route('login') }}" class="btn btn-primary rounded-pill btn-sm px-4 fw-bold">লগইন করুন</a>
                        <a href="{{ route('apply.card', ['type' => 'property']) }}" class="btn btn-outline-success rounded-pill btn-sm px-3 mt-2">প্রপার্টি কার্ড আবেদন</a>
                    </div>
                    @endauth
                </div>

                <!-- Assistance Helpline Card -->
                <div class="card border-0 shadow-sm rounded-4 p-4 text-white" style="background: linear-gradient(135deg, #064e3b, #047857);">
                    <h6 class="fw-bold text-white mb-2"><i class="fa fa-phone-square me-1"></i> বিনিয়োগ সহায়তায় যোগাযোগ</h6>
                    <p class="small text-light opacity-90 mb-3">প্রকল্প পরিদর্শন বা নথিপত্র যাচাই সংক্রান্ত যেকোনো পরামর্শে আমাদের অফিসিয়াল ইনভেস্টর ডেস্কে কল করুন।</p>
                    <a href="tel:{{ setting('phone1') ?: '01700000000' }}" class="btn btn-warning text-dark rounded-pill btn-sm fw-bold w-100">
                        <i class="fa fa-phone me-1"></i> হেল্পলাইন: {{ setting('phone1') ?: '০১৭০০-০০০০০০' }}
                    </a>
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

