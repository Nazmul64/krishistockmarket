@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-id-card text-info me-2"></i>Cardholder to Agent Point Assignment (কার্ডহোল্ডার পয়েন্ট ম্যাপিং)
                    </h3>
                    <p class="text-muted mb-0">নির্দিষ্ট এলাকার কার্ডহোল্ডারদের সংশ্লিষ্ট এজেন্ট পয়েন্টে সংযুক্ত করুন</p>
                </div>
                <div>
                    <a href="{{ route('admin.agents.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> এজেন্ট তালিকায় ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Top Counters -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                        <div class="text-muted font-size-12 text-uppercase fw-semibold">Total Cardholders (মোট কার্ডহোল্ডার)</div>
                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ number_format($totalCardHolders) }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                        <div class="text-muted font-size-12 text-uppercase fw-semibold">Assigned (পয়েন্ট নির্ধারিত)</div>
                        <h3 class="fw-bold text-success mb-0 mt-1">{{ number_format($assignedCount) }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                        <div class="text-muted font-size-12 text-uppercase fw-semibold">Unassigned (পয়েন্ট নির্ধারিত নেই)</div>
                        <h3 class="fw-bold text-danger mb-0 mt-1">{{ number_format($unassignedCount) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Cardholders Table with Bulk Assignment -->
            <form id="bulkAssignForm" method="POST" action="{{ route('admin.agents.assign_cardholders.store') }}">
                @csrf
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="row g-2 align-items-center">
                            <!-- Filter Section -->
                            <div class="col-lg-6">
                                <div class="d-flex gap-2">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="fa fa-search text-muted"></i></span>
                                        <input type="text" name="search" form="filterForm" class="form-control bg-light border-start-0" 
                                               placeholder="Search by Card No, Name, Phone, Area..." 
                                               value="{{ request('search') }}">
                                    </div>
                                    <select name="agent_point_id" form="filterForm" class="form-select bg-light" style="max-width: 200px;">
                                        <option value="">-- All Points --</option>
                                        <option value="unassigned" {{ request('agent_point_id') == 'unassigned' ? 'selected' : '' }}>-- শুধুমাত্র Unassigned --</option>
                                        @foreach($agentPoints as $p)
                                            <option value="{{ $p->id }}" {{ request('agent_point_id') == $p->id ? 'selected' : '' }}>{{ $p->name }} ({{ $p->area }})</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" form="filterForm" class="btn btn-secondary btn-sm">
                                        <i class="fa fa-filter"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Bulk Assignment Action Bar -->
                            <div class="col-lg-6 text-lg-end mt-2 mt-lg-0">
                                <div class="d-inline-flex gap-2 align-items-center">
                                    <span class="text-muted font-size-12 fw-semibold">Target Point:</span>
                                    <select name="agent_point_id" class="form-select form-select-sm" required style="min-width: 220px;">
                                        <option value="">-- সিলেক্ট করুন এজেন্ট পয়েন্ট --</option>
                                        @foreach($agentPoints as $point)
                                            <option value="{{ $point->id }}">{{ $point->name }} ({{ $point->area }})</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-success btn-sm px-3 text-nowrap fw-semibold shadow-sm">
                                        <i class="fa fa-check-circle me-1"></i> নির্বাচিতদের অ্যাসাইন করুন
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-muted font-size-12 text-uppercase">
                                    <tr>
                                        <th class="ps-4" style="width: 40px;">
                                            <input type="checkbox" id="selectAllCheckbox" class="form-check-input">
                                        </th>
                                        <th>Card Number</th>
                                        <th>Cardholder Name</th>
                                        <th>Mobile Phone</th>
                                        <th>Area / District</th>
                                        <th>Assigned Agent Point</th>
                                        <th class="text-end pe-4">Quick Assign</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cardHolders as $ch)
                                    <tr>
                                        <td class="ps-4">
                                            <input type="checkbox" name="user_ids[]" value="{{ $ch->id }}" class="form-check-input user-checkbox">
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-light text-primary fw-bold font-size-12 px-2 py-1">
                                                {{ $ch->card_number ?? 'No Card' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $ch->name }}</div>
                                            <div class="text-muted font-size-11">{{ $ch->email }}</div>
                                        </td>
                                        <td>
                                            <i class="fa fa-phone me-1 text-success"></i> {{ $ch->phone ?? 'N/A' }}
                                        </td>
                                        <td>
                                            <div class="text-dark">{{ $ch->area ?? 'General' }}</div>
                                            <div class="text-muted font-size-11">{{ $ch->district ?? '' }}</div>
                                        </td>
                                        <td>
                                            @if($ch->assignedAgentPoint)
                                                <span class="badge bg-success-light text-success font-size-12 px-2 py-1">
                                                    <i class="fa fa-map-marker me-1"></i> {{ $ch->assignedAgentPoint->name }}
                                                </span>
                                            @else
                                                <span class="badge bg-danger-light text-danger font-size-11">
                                                    <i class="fa fa-clock-o me-1"></i> Unassigned
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <button type="button" class="btn btn-outline-primary btn-sm py-0 font-size-11" 
                                                    onclick="singleAssign({{ $ch->id }})">
                                                অ্যাসাইন
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            কোনো কার্ডহোল্ডার গ্রাহক পাওয়া যায়নি
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($cardHolders->hasPages())
                    <div class="card-footer bg-white border-top py-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <span class="text-muted font-size-13">Showing {{ $cardHolders->firstItem() }} to {{ $cardHolders->lastItem() }} of {{ $cardHolders->total() }} cardholders</span>
                            {{ $cardHolders->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </form>

            <!-- Separate Filter Form so GET doesn't conflict with POST -->
            <form id="filterForm" method="GET" action="{{ route('admin.agents.assign_cardholders') }}" class="d-none"></form>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('selectAllCheckbox');
    const checkboxes = document.querySelectorAll('.user-checkbox');

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        });
    }
});

function singleAssign(userId) {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(cb => cb.checked = (cb.value == userId));
    document.getElementById('bulkAssignForm').scrollIntoView({ behavior: 'smooth' });
}
</script>
@endsection
