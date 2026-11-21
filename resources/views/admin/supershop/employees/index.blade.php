@extends('layouts.backend.app')

@section('title', 'কর্মচারী তালিকা ও প্রোফাইল ডিরেক্টরি (Employee Directory)')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content">

    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">
                <i class="ti-user text-primary me-2"></i> কর্মচারী তালিকা ও বায়োডাটা ডিরেক্টরি
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.supershop.employee_system.dashboard') }}">ড্যাশবোর্ড</a></li>
                    <li class="breadcrumb-item active" aria-current="page">কর্মচারী ব্যবস্থাপনা</li>
                </ol>
            </nav>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.supershop.employee_system.employees.create') }}" class="btn btn-success fw-bold shadow-sm">
                <i class="ti-plus me-1"></i> নতুন কর্মচারী যুক্ত করুন
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.supershop.employee_system.employees.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="ti-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="নাম, আইডি, ফোন বা এনআইডি দিয়ে খুঁজুন..." value="{{ request('search') }}">
                    </div>
                </div>

                @if($isAdmin)
                    <div class="col-md-3">
                        <select name="branch_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- সকল ব্রাঞ্চ (All Branches) --</option>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->code }} - {{ $b->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="col-md-2">
                    <select name="designation" class="form-select" onchange="this.form.submit()">
                        <option value="">-- সকল পদবি --</option>
                        @foreach($designations as $des)
                            <option value="{{ $des }}" {{ request('designation') == $des ? 'selected' : '' }}>{{ $des }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">-- সকল স্ট্যাটাস --</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>সক্রিয় (Active)</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয় (Inactive)</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100"><i class="ti-filter"></i></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Employee Directory Table -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="ti-list text-primary me-2"></i> মোট কর্মচারী: {{ $employees->total() }} জন
            </h6>
            @if(request('search') || request('branch_id') || request('designation') || request('status'))
                <a href="{{ route('admin.supershop.employee_system.employees.index') }}" class="btn btn-sm btn-outline-danger">
                    <i class="ti-reload me-1"></i> ফিল্টার রিসেট
                </a>
            @endif
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">কর্মচারী</th>
                        <th>আইডি ও ব্রাঞ্চ</th>
                        <th>পদবি ও বিভাগ</th>
                        <th>যোগাযোগ ও ঠিকানা</th>
                        <th>NID নম্বর (Masked)</th>
                        <th>মাসিক বেতন</th>
                        <th>স্ট্যাটাস</th>
                        <th class="text-center pe-4">একশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $emp->profile_photo_url }}" class="rounded-circle border me-3 object-fit-cover shadow-sm" width="45" height="45" alt="{{ $emp->name }}">
                                    <div>
                                        <a href="{{ route('admin.supershop.employee_system.employees.show', $emp->id) }}" class="fw-bold text-dark text-decoration-none">
                                            {{ $emp->name }}
                                        </a>
                                        <div class="text-muted small">রক্ত: <span class="badge bg-danger-subtle text-danger">{{ $emp->blood_group ?: 'N/A' }}</span></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-dark px-2 py-1">{{ $emp->employee_id }}</span>
                                <div class="text-muted small mt-1">
                                    <i class="ti-home"></i> {{ $emp->branch->code ?? '' }} - {{ $emp->branch->name ?? '' }}
                                </div>
                            </td>
                            <td>
                                <strong class="text-primary d-block">{{ $emp->designation }}</strong>
                                <span class="text-muted small">{{ $emp->department ?: 'সাধারণ' }}</span>
                            </td>
                            <td>
                                <div><i class="ti-mobile text-success me-1"></i> {{ $emp->phone }}</div>
                                <small class="text-muted"><i class="ti-location-pin me-1"></i> {{ $emp->district ?? 'ঢাকা' }}</small>
                            </td>
                            <td>
                                <span class="font-monospace text-secondary fw-bold bg-light px-2 py-1 rounded border">
                                    {{ $emp->masked_nid }}
                                </span>
                            </td>
                            <td>
                                <strong class="text-dark">৳{{ number_format($emp->monthly_salary, 2) }}</strong>
                                <small class="text-muted d-block">{{ ucfirst($emp->salary_payment_method) }}</small>
                            </td>
                            <td>
                                @if($emp->employment_status === 'active')
                                    <span class="badge bg-success px-2 py-1"><i class="ti-check me-1"></i> সক্রিয়</span>
                                @else
                                    <span class="badge bg-danger px-2 py-1"><i class="ti-close me-1"></i> নিষ্ক্রিয়</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.supershop.employee_system.employees.show', $emp->id) }}" class="btn btn-outline-primary" title="সম্পূর্ণ প্রোফাইল দেখুন">
                                        <i class="ti-eye"></i> প্রোফাইল
                                    </a>
                                    <a href="{{ route('admin.supershop.employee_system.employees.edit', $emp->id) }}" class="btn btn-outline-secondary" title="এডিট করুন">
                                        <i class="ti-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.supershop.employee_system.employees.toggle_status', $emp->id) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি এই কর্মচারীর স্ট্যাটাস পরিবর্তন করতে চান?');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-{{ $emp->employment_status === 'active' ? 'danger' : 'success' }}" title="{{ $emp->employment_status === 'active' ? 'নিষ্ক্রিয় করুন' : 'সক্রিয় করুন' }}">
                                            <i class="ti-power-off"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="ti-user fa-3x d-block mb-2 text-secondary"></i>
                                কোনো কর্মচারী পাওয়া যায়নি। নতুন কর্মচারী যুক্ত করতে উপরের বাটনে ক্লিক করুন।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($employees->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $employees->links() }}
            </div>
        @endif
    </div>

        </section>
    </div>
</div>
@endsection
