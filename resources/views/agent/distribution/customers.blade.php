@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-users text-primary me-2"></i>Assigned Cardholders List (নির্ধারিত গ্রাহক তালিকা)
                    </h3>
                    <p class="text-muted mb-0">এজেন্ট পয়েন্ট: <strong>{{ $point->name }}</strong> ({{ $point->code }}) | শুধুমাত্র আপনার পয়েন্টে নির্ধারিত কার্ডহোল্ডারদের তালিকা</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('agent.distribution.entry') }}" class="btn btn-primary btn-sm px-3 fw-semibold shadow-sm">
                        <i class="fa fa-qrcode me-1"></i> + বাজার বিতরণ (Scan Card)
                    </a>
                    <a href="{{ route('agent.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-dashboard me-1"></i> ড্যাশবোর্ড
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <form method="GET" action="{{ route('agent.distribution.customers') }}" class="d-flex gap-2 flex-grow-1" style="max-width: 500px;">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa fa-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control bg-light border-start-0" 
                                       placeholder="Search by Card Number, Name, Phone, Area..." 
                                       value="{{ request('search') }}">
                            </div>
                            <button type="submit" class="btn btn-secondary btn-sm"><i class="fa fa-filter"></i></button>
                        </form>
                        <div class="text-muted font-size-13">
                            চলতি মাস: <strong>{{ $currentMonth }} {{ $currentYear }}</strong>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 font-size-13">
                            <thead class="table-light text-muted font-size-11 text-uppercase">
                                <tr>
                                    <th class="ps-4">Card No. & Photo</th>
                                    <th>Customer Details</th>
                                    <th>Contact & Address</th>
                                    <th>Membership</th>
                                    <th class="text-center">{{ $currentMonth }} Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $c)
                                @php
                                    $hasCollectedThisMonth = in_array($c->id, $collectedUserIds);
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $c->avatar ? asset($c->avatar) : asset('frontend/images/default-avatar.png') }}" 
                                                 class="rounded-circle border" style="width: 38px; height: 38px; object-fit: cover;" alt="Avatar">
                                            <div>
                                                <span class="badge bg-primary-light text-primary font-size-12 fw-bold">
                                                    {{ $c->card_number ?? ('CH-' . $c->id) }}
                                                </span>
                                                <div class="text-muted font-size-10">{{ $c->customer_id ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark font-size-14">{{ $c->name }}</div>
                                        <div class="text-muted font-size-11">{{ $c->email }}</div>
                                    </td>
                                    <td>
                                        <div><i class="fa fa-phone text-success me-1"></i> {{ $c->phone ?? 'N/A' }}</div>
                                        <div class="text-muted font-size-11"><i class="fa fa-map-marker text-danger me-1"></i> {{ $c->present_address ?? $c->area ?? 'General' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $c->status == 'active' ? 'success' : 'secondary' }}-light text-{{ $c->status == 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($c->status ?? 'active') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($hasCollectedThisMonth)
                                            <span class="badge bg-success-light text-success font-size-11 px-3 py-1 rounded-pill">
                                                <i class="fa fa-check-circle me-1"></i> Collected
                                            </span>
                                        @else
                                            <span class="badge bg-warning-light text-warning font-size-11 px-3 py-1 rounded-pill">
                                                <i class="fa fa-clock-o me-1"></i> Not Collected
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('agent.distribution.entry', ['card_number' => $c->card_number]) }}" class="btn btn-outline-success font-size-11" title="বাজার বিতরণ করুন">
                                                <i class="fa fa-shopping-basket me-1"></i> বাজার দিন
                                            </a>
                                            <a href="{{ route('agent.distribution.customer.history', $c->id) }}" class="btn btn-outline-info font-size-11" title="সংগ্রহের ইতিহাস দেখুন">
                                                <i class="fa fa-history"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        আপনার পয়েন্টে কোনো কার্ডহোল্ডার গ্রাহক পাওয়া যায়নি।
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($customers->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <span class="text-muted font-size-13">Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} cardholders</span>
                        {{ $customers->links() }}
                    </div>
                </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
