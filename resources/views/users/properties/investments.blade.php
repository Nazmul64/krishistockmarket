@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="fa fa-pie-chart text-success me-2"></i> আমার শেয়ার ও রিয়েল এস্টেট বিনিয়োগ (Property Investments & Shares)
                    </h3>
                    <p class="text-muted mb-0">যৌথ জমি ক্রয়, ল্যান্ড শেয়ারিং এবং এগ্রো ফার্ম প্রজেক্টে বিনিয়োগ পোর্টফোলিও</p>
                </div>
                <div>
                    <a href="{{ route('properties.index') }}" target="_blank" class="btn btn-success fw-bold">
                        <i class="fa fa-plus-circle me-1"></i> নতুন বিনিয়োগ সুযোগ খুঁজুন
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Info Callout Banner -->
            <div class="card border-0 rounded-4 shadow-sm mb-4" style="background: linear-gradient(135deg, #064e3b, #047857); color: #ffffff;">
                <div class="card-body p-4 p-md-5">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold mb-2">
                                <i class="fa fa-shield me-1"></i> ১০০% নিরাপদ ল্যান্ড শেয়ারিং
                            </span>
                            <h2 class="fw-bold mb-2 text-white">স্বল্প পুঁজিতে জমি ও খামারের মালিকানা</h2>
                            <p class="mb-0" style="color: #a7f3d0; font-size: 15px; line-height: 1.6;">
                                যৌথ মালিকানায় জমি ক্রয় করে দীর্ঘমেয়াদী ক্যাপিটাল গ্রোথ ও এগ্রো ফার্মিং থেকে নিয়মিত রিটার্ন নিশ্চিত করুন। আপনার সকল বিনিয়োগ সাফ-কবলা ও নিবন্ধিত চুক্তিনামার মাধ্যমে সুরক্ষিত।
                            </p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <a href="{{ route('user.properties.my_bookings') }}" class="btn btn-warning fw-bold px-4 py-2 rounded-pill">
                                <i class="fa fa-list me-1"></i> আমার আবেদনসমূহ দেখুন
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active LandShare Projects -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="fw-bold text-dark mb-0">চলমান ল্যান্ড প্রজেক্ট ও বিনিয়োগ সুযোগ</h4>
                <a href="{{ route('properties.index') }}" class="text-success fw-bold text-decoration-none">সবগুলো দেখুন <i class="fa fa-arrow-right"></i></a>
            </div>

            <div class="row g-4 mb-4">
                @forelse($activeProjects as $project)
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden bg-white">
                            <div class="position-relative" style="height: 180px; background: #e2e8f0;">
                                <img src="{{ $project->featured_image ?: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=600' }}" 
                                     alt="{{ $project->name }}" 
                                     class="w-100 h-100" 
                                     style="object-fit: cover;">
                                <span class="badge bg-success position-absolute top-0 end-0 m-3 px-3 py-1 rounded-pill">
                                    {{ ucfirst($project->status) }}
                                </span>
                            </div>
                            <div class="card-body p-4 d-flex flex-column">
                                <h5 class="fw-bold text-dark mb-1">{{ $project->name }}</h5>
                                <div class="small text-muted mb-3"><i class="fa fa-map-marker text-danger me-1"></i>{{ $project->location ?: 'ঢাকা ও সাভার' }}</div>
                                
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small text-muted mb-1">
                                        <span>প্রজেক্ট অগ্রগতি</span>
                                        <strong>{{ $project->progress_percentage }}%</strong>
                                    </div>
                                    <div class="progress" style="height: 7px; border-radius: 10px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $project->progress_percentage }}%;"></div>
                                    </div>
                                </div>

                                <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="small text-muted">মোট বাজেট</div>
                                        <strong class="text-dark">৳ {{ number_format($project->total_budget) }}</strong>
                                    </div>
                                    <a href="{{ route('properties.index') }}" class="btn btn-outline-success btn-sm rounded-pill px-3">
                                        বিস্তারিত দেখুন
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="p-4 bg-white rounded-4 border text-center text-muted">
                            <i class="fa fa-folder-open-o fa-2x mb-2"></i>
                            <p class="mb-0">বর্তমানে কোনো নতুন ইনভেস্টমেন্ট প্রজেক্ট সক্রিয় নেই। খুব শীঘ্রই নতুন ল্যান্ড প্রজেক্ট যুক্ত হবে।</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection

