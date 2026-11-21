@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-users text-primary me-2"></i>Central Cardholders Distribution Directory
                    </h3>
                    <p class="text-muted mb-0">কেন্দ্রীয় অ্যাডমিন প্যানেল: সকল এজেন্ট পয়েন্টের গ্রাহকদের বাজার সংগ্রহ স্ট্যাটাস</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.distribution.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ডে ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <form method="GET" action="{{ route('admin.distribution.customer_list') }}" class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa fa-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control bg-light border-start-0" 
                                       placeholder="Search by Card Number, Customer Name, Phone, Area..." 
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select name="agent_point_id" class="form-select bg-light" onchange="this.form.submit()">
                                <option value="">-- All Agent Points (সকল এজেন্ট পয়েন্ট) --</option>
                                @foreach($agentPoints as $p)
                                    <option value="{{ $p->id }}" {{ request('agent_point_id') == $p->id ? 'selected' : '' }}>{{ $p->name }} ({{ $p->area }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-1">
                            <button type="submit" class="btn btn-secondary btn-sm flex-fill">
                                <i class="fa fa-filter me-1"></i> ফিল্টার
                            </button>
                            <a href="{{ route('admin.distribution.customer_list') }}" class="btn btn-outline-secondary btn-sm" title="রিসেট">
                                <i class="fa fa-refresh"></i>
                            </a>
                        </div>
                    </form>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 font-size-13">
                            <thead class="table-light text-muted font-size-11 text-uppercase">
                                <tr>
                                    <th class="ps-4">Card No.</th>
                                    <th>Customer Name</th>
                                    <th>Phone & Area</th>
                                    <th>Assigned Agent Point</th>
                                    <th>Last Market Type</th>
                                    <th class="text-end">Last Amount</th>
                                    <th>Collection Date</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $c)
                                @php
                                    $lastCol = $c->cardHolderCollections->first();
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <span class="badge bg-primary-light text-primary font-size-12 fw-bold">
                                            {{ $c->card_number ?? ('CH-' . $c->id) }}
                                        </span>
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
                                        @if($c->assignedAgentPoint)
                                            <a href="{{ route('admin.distribution.point_summary', $c->assignedAgentPoint->id) }}" class="fw-semibold text-primary">
                                                {{ $c->assignedAgentPoint->name }}
                                            </a>
                                            <div class="text-muted font-size-10">{{ $c->assignedAgentPoint->area }}</div>
                                        @else
                                            <span class="badge bg-danger-light text-danger font-size-11">Unassigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($lastCol)
                                            <span class="badge bg-light text-dark font-size-11">
                                                {{ ucfirst(str_replace('_', ' ', $lastCol->market_type)) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold text-primary">
                                        {{ $lastCol ? '৳' . number_format($lastCol->total_amount, 2) : '-' }}
                                    </td>
                                    <td class="text-muted font-size-12">
                                        {{ $lastCol ? date('d M, Y', strtotime($lastCol->collection_date)) : 'কখনো নেননি' }}
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($lastCol)
                                            <a href="{{ route('admin.distribution.transaction_details', $lastCol->id) }}" class="btn btn-outline-info btn-sm py-0 font-size-11">
                                                <i class="fa fa-eye me-1"></i> বিবরণ
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        কোনো গ্রাহক পাওয়া যায়নি।
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
