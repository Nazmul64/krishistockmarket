@extends('layouts.backend.app')

@section('content')
@php
    $partnerships = $partnerships ?? collect([]);
    $user = $user ?? Auth::user();
@endphp
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content py-3">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-handshake-o text-success me-2"></i> পার্টনারশিপ ও যৌথ ব্যবসায়িক অংশীদারিত্ব</h4>
                    <p class="text-muted small mb-0">ব্যবসায়িক পার্টনার প্রোফাইল, চুক্তিপত্র, মূলধন অবদান এবং লাভ-লোকসান হিসাব।</p>
                </div>
                <a href="{{ route('user.corporate.community') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                    <i class="fa fa-comments me-1"></i> কমিউনিটি ফোরাম
                </a>
            </div>

            <div class="row g-4">
                @forelse($partnerships as $part)
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge bg-dark font-monospace mb-1">{{ $part->partner_code }}</span>
                                <h5 class="fw-bold text-dark mb-0">{{ $part->partner_name }}</h5>
                                <small class="text-muted">{{ $part->business_name ?: 'প্রাতিষ্ঠানিক পার্টনার' }}</small>
                            </div>
                            <span class="badge {{ $part->status === 'active' ? 'bg-success' : 'bg-warning text-dark' }} px-3 py-1 rounded-pill">
                                {{ $part->status === 'active' ? 'সক্রিয় পার্টনারশিপ' : 'প্রক্রিয়াধীন' }}
                            </span>
                        </div>

                        <div class="table-responsive small mb-3">
                            <table class="table table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <th class="bg-light" style="width: 40%;">অংশীদারিত্বের ধরন:</th>
                                        <td class="text-capitalize">{{ str_replace('_', ' ', $part->partnership_type) }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">চুক্তি নম্বর (Contract):</th>
                                        <td class="font-monospace fw-bold">{{ $part->contract_number ?: 'CT-' . rand(1000, 9999) }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">মূলধন অবদান (Capital):</th>
                                        <td class="text-primary fw-bold">{{ number_format($part->capital_contribution, 2) }} ৳</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">লাভের শতকরা হার:</th>
                                        <td class="text-success fw-bold">{{ $part->profit_share_percentage }}%</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">মোট বণ্টনকৃত মুনাফা:</th>
                                        <td class="text-success fw-bold font-size-14">{{ number_format($part->total_profit_shared, 2) }} ৳</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">চুক্তির মেয়াদ:</th>
                                        <td>{{ $part->start_date ? \Carbon\Carbon::parse($part->start_date)->format('d/m/Y') : 'শুরু' }} থেকে {{ $part->end_date ? \Carbon\Carbon::parse($part->end_date)->format('d/m/Y') : 'চলমান' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        @if($part->terms_and_conditions)
                        <div class="p-3 bg-light rounded-3 small mb-3">
                            <strong class="text-dark d-block mb-1">চুক্তির বিশেষ শর্তাবলী:</strong>
                            <p class="text-muted mb-0">{{ $part->terms_and_conditions }}</p>
                        </div>
                        @endif

                        @if($part->agreement_file)
                        <div class="mt-auto pt-2">
                            <a href="{{ asset($part->agreement_file) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill w-100 fw-bold">
                                <i class="fa fa-file-pdf-o me-1"></i> স্বাক্ষরিত চুক্তিপত্র ডাউনলোড
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <div class="card border-0 shadow-sm rounded-4 p-5 bg-white">
                        <i class="fa fa-handshake-o text-muted fa-3x mb-3"></i>
                        <h5 class="text-muted">আপনার কোনো সক্রিয় পার্টনারশিপ চুক্তি পাওয়া যায়নি।</h5>
                        <p class="text-secondary small mb-3">প্রতিষ্ঠানের সাথে জয়েন্ট ভেঞ্চার বা ব্যবসায়িক অংশীদারিত্বে যুক্ত হতে আবেদন করুন।</p>
                        <a href="{{ route('apply.card', ['type' => 'partner']) }}" class="btn btn-success btn-sm rounded-pill px-4 fw-bold">
                            পার্টনারশিপের জন্য আবেদন করুন
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

        </section>
    </div>
</div>
@endsection
