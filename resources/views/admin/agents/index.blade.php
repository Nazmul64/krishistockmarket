@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-users text-primary me-2"></i>Agent & Point Management (এজেন্ট ও পয়েন্ট ব্যবস্থাপনা)
                    </h3>
                    <p class="text-muted mb-0">এলাকাভিত্তিক এজেন্ট পয়েন্ট, কার্ডহোল্ডার ডিস্ট্রিবিউশন ও স্টক নিয়ন্ত্রণ</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.agents.assign_cardholders') }}" class="btn btn-outline-info btn-sm">
                        <i class="fa fa-id-card me-1"></i> কার্ডহোল্ডার অ্যাসাইন
                    </a>
                    <a href="{{ route('admin.agents.supply_allocation') }}" class="btn btn-outline-warning btn-sm">
                        <i class="fa fa-truck me-1"></i> পণ্য ডিস্ট্রিবিউশন
                    </a>
                    <a href="{{ route('admin.agents.create') }}" class="btn btn-primary btn-sm px-3 shadow-sm">
                        <i class="fa fa-plus-circle me-1"></i> + Add Agent (নতুন এজেন্ট)
                    </a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <!-- Alert Messages -->
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

            <!-- KPI Summary Cards -->
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                        <div class="card-body d-flex align-items-center justify-content-between p-3">
                            <div>
                                <span class="text-muted text-uppercase fw-semibold font-size-12">Total Agents (মোট এজেন্ট)</span>
                                <h3 class="fw-bold mb-0 text-dark mt-1">{{ number_format($totalAgents) }}</h3>
                            </div>
                            <div class="rounded-circle bg-primary-light p-3 text-primary">
                                <i class="fa fa-user-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                        <div class="card-body d-flex align-items-center justify-content-between p-3">
                            <div>
                                <span class="text-muted text-uppercase fw-semibold font-size-12">Active Agents (সক্রিয়)</span>
                                <h3 class="fw-bold mb-0 text-success mt-1">{{ number_format($activeAgents) }}</h3>
                            </div>
                            <div class="rounded-circle bg-success-light p-3 text-success">
                                <i class="fa fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                        <div class="card-body d-flex align-items-center justify-content-between p-3">
                            <div>
                                <span class="text-muted text-uppercase fw-semibold font-size-12">Inactive (নিষ্ক্রিয়)</span>
                                <h3 class="fw-bold mb-0 text-danger mt-1">{{ number_format($inactiveAgents) }}</h3>
                            </div>
                            <div class="rounded-circle bg-danger-light p-3 text-danger">
                                <i class="fa fa-ban fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                        <div class="card-body d-flex align-items-center justify-content-between p-3">
                            <div>
                                <span class="text-muted text-uppercase fw-semibold font-size-12">Assigned Cardholders</span>
                                <h3 class="fw-bold mb-0 text-info mt-1">{{ number_format($totalAssignedCardHolders) }}</h3>
                            </div>
                            <div class="rounded-circle bg-info-light p-3 text-info">
                                <i class="fa fa-id-card fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agents List Card (Matching Reference Design) -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <form method="GET" action="{{ route('admin.agents.index') }}" class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa fa-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control bg-light border-start-0" 
                                       placeholder="Search by Agent ID, Point Name, Person, Phone, Email..." 
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="district" class="form-select bg-light">
                                <option value="">-- All Districts (সকল জেলা) --</option>
                                @foreach($districts as $dst)
                                    <option value="{{ $dst }}" {{ request('district') == $dst ? 'selected' : '' }}>{{ $dst }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select bg-light">
                                <option value="">-- All Status --</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active (সক্রিয়)</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive (নিষ্ক্রিয়)</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-1">
                            <button type="submit" class="btn btn-secondary btn-sm flex-fill">
                                <i class="fa fa-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('admin.agents.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset">
                                <i class="fa fa-refresh"></i>
                            </a>
                        </div>
                    </form>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted font-size-12 text-uppercase">
                                <tr>
                                    <th class="ps-4">Agent ID</th>
                                    <th>Agent Point & Company</th>
                                    <th>Agent Name & Contact</th>
                                    <th>Location / Area</th>
                                    <th class="text-center">Assigned Cards</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($agents as $agent)
                                <tr>
                                    <td class="ps-4">
                                        <span class="badge bg-primary-light text-primary fw-bold font-size-12 px-2 py-1">
                                            {{ $agent->code }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark font-size-14">{{ $agent->name }}</div>
                                        @if($agent->company_name)
                                            <div class="text-muted font-size-12"><i class="fa fa-building-o me-1"></i> {{ $agent->company_name }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $agent->agent_name }}</div>
                                        <div class="text-muted font-size-12">
                                            <i class="fa fa-phone me-1 text-success"></i> {{ $agent->contact_number }}
                                            @if($agent->agentUser && $agent->agentUser->email)
                                                <span class="ms-2"><i class="fa fa-envelope-o me-1 text-info"></i> {{ $agent->agentUser->email }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-dark">{{ $agent->area }}</div>
                                        <div class="text-muted font-size-12">
                                            {{ $agent->thana_upazila ? $agent->thana_upazila . ', ' : '' }}{{ $agent->district }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.agents.assign_cardholders', ['agent_point_id' => $agent->id]) }}" class="badge bg-info-light text-info rounded-pill px-3 py-1 font-size-12 text-decoration-none">
                                            <i class="fa fa-users me-1"></i> {{ $agent->assigned_card_holders_count }} Cards
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.agents.toggle', $agent->id) }}" 
                                           class="btn btn-sm btn-outline-{{ $agent->status == 'active' ? 'success' : 'danger' }} rounded-pill px-3 py-0 font-size-11"
                                           title="Click to toggle status">
                                            <i class="fa fa-circle me-1 {{ $agent->status == 'active' ? 'text-success' : 'text-danger' }}"></i>
                                            {{ ucfirst($agent->status) }}
                                        </a>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.agents.show', $agent->id) }}" class="btn btn-outline-info" title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.agents.edit', $agent->id) }}" class="btn btn-outline-primary" title="Edit Agent">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#resetPasswordModal{{ $agent->id }}" title="Reset Password">
                                                <i class="fa fa-key"></i>
                                            </button>
                                            <a href="{{ route('admin.agents.destroy', $agent->id) }}" class="btn btn-outline-danger" onclick="return confirm('আপনি কি নিশ্চিতভাবে এই এজেন্ট পয়েন্ট মুছে ফেলতে চান?');" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>

                                        <!-- Reset Password Modal -->
                                        <div class="modal fade" id="resetPasswordModal{{ $agent->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content text-start">
                                                    <form method="POST" action="{{ route('admin.agents.reset_password', $agent->id) }}">
                                                        @csrf
                                                        <div class="modal-header bg-warning-light">
                                                            <h5 class="modal-title fw-bold text-dark">
                                                                <i class="fa fa-key text-warning me-2"></i> পাসওয়ার্ড রিসেট: {{ $agent->agent_name }}
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="text-muted font-size-13 mb-3">
                                                                এজেন্টের নতুন লগইন পাসওয়ার্ড সেট করুন (Login ID: {{ $agent->agentUser->email ?? $agent->contact_number }})
                                                            </p>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">New Password (নতুন পাসওয়ার্ড) <span class="text-danger">*</span></label>
                                                                <input type="password" name="new_password" class="form-control" placeholder="ন্যূনতম ৬ অক্ষর লিখুন" required minlength="6">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">Confirm Password (পাসওয়ার্ড নিশ্চিতকরণ) <span class="text-danger">*</span></label>
                                                                <input type="password" name="new_password_confirmation" class="form-control" placeholder="পুনরায় লিখুন" required minlength="6">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                                                            <button type="submit" class="btn btn-warning fw-semibold">পাসওয়ার্ড আপডেট করুন</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fa fa-users fa-3x mb-3 text-secondary"></i>
                                            <p class="font-size-16 mb-2">কোনো এজেন্ট পাওয়া যায়নি</p>
                                            <a href="{{ route('admin.agents.create') }}" class="btn btn-primary btn-sm">
                                                <i class="fa fa-plus-circle me-1"></i> প্রথম এজেন্ট যুক্ত করুন
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($agents->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <span class="text-muted font-size-13">Showing {{ $agents->firstItem() }} to {{ $agents->lastItem() }} of {{ $agents->total() }} agents</span>
                        {{ $agents->links() }}
                    </div>
                </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
