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
        <div class="text-center mb-4">
            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold font-size-14 mb-2">
                <i class="fa fa-id-card me-1"></i> কর্মকর্তা-কর্মচারী পরিচিতি
            </span>
            <h2 class="fw-bold display-6 text-dark mt-2">কর্মকর্তা-কর্মচারী ডিরেক্টরি (Employee Directory)</h2>
            <p class="text-muted max-w-700 mx-auto font-size-16">
                মাঠপর্যায় থেকে শুরু করে হেড অফিস পর্যন্ত প্রতিষ্ঠানের প্রতিটি বিভাগ ও কর্মস্থলের নিবেদিতপ্রাণ কর্মীদের তালিকা।
            </p>
        </div>

        <!-- Filter & Search Bar -->
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
            <form action="{{ route('corporate.employees') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="fa fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-0 bg-light" placeholder="কর্মচারীর নাম, মোবাইল বা ইমেইল দিয়ে খুঁজুন..." value="{{ $search ?? '' }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="department_id" class="form-select bg-light border-0">
                        <option value="">সকল বিভাগ (All Departments)</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ ($departmentId ?? '') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-success rounded-pill px-4 flex-grow-1 fw-bold">
                        ফিল্টার করুন
                    </button>
                    @if(!empty($search) || !empty($departmentId))
                        <a href="{{ route('corporate.employees') }}" class="btn btn-outline-secondary rounded-pill px-3">রিসেট</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Employees Grid -->
        <div class="row g-4">
            @forelse($employees as $emp)
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden text-center bg-white transition-all hover-shadow">
                    <div class="card-body p-4">
                        <div class="position-relative d-inline-block mb-3">
                            <img src="{{ !empty($emp->user->avatar) && file_exists(public_path($emp->user->avatar)) ? asset($emp->user->avatar) : asset('frontend/assets/images/team/member3.jpg') }}" 
                                 alt="{{ $emp->user->name ?? 'Employee' }}" 
                                 class="rounded-circle shadow-sm border border-3 border-success-subtle" 
                                 style="width: 100px; height: 100px; object-fit: cover;">
                            <span class="badge bg-success position-absolute bottom-0 end-0 rounded-circle p-1" title="সক্রিয় কর্মী">
                                <i class="fa fa-check text-white" style="font-size: 10px;"></i>
                            </span>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">{{ $emp->user->name ?? 'কর্মকর্তা' }}</h6>
                        <p class="text-primary fw-semibold small mb-1">{{ $emp->designation->name ?? 'পদবী নির্ধারিত নয়' }}</p>
                        <span class="badge bg-light text-muted fw-normal px-2 py-1 mb-2">{{ $emp->department->name ?? 'সাধারণ বিভাগ' }}</span>

                        <div class="bg-light p-2 rounded-3 text-start small mb-2 text-muted" style="font-size: 12px;">
                            <div><i class="fa fa-map-marker text-danger me-1"></i> <strong>কর্মস্থল:</strong> {{ $emp->branch->name ?? 'প্রধান কার্যালয়' }}</div>
                            <div><i class="fa fa-id-badge text-info me-1"></i> <strong>কোড:</strong> {{ $emp->employee_code }}</div>
                        </div>

                        @if(!empty($emp->user->phone))
                        <a href="tel:{{ $emp->user->phone }}" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 font-size-12">
                            <i class="fa fa-phone me-1"></i> {{ $emp->user->phone }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="bg-white rounded-4 p-5 shadow-sm">
                    <i class="fa fa-users text-muted fa-3x mb-3"></i>
                    <h5 class="text-muted">কোনো কর্মকর্তা-কর্মচারীর তথ্য পাওয়া যায়নি।</h5>
                    <a href="{{ route('corporate.employees') }}" class="btn btn-outline-primary btn-sm rounded-pill mt-2">সকল কর্মী দেখুন</a>
                </div>
            </div>
            @endforelse
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $employees->links() }}
        </div>
    </div>
</div>
@if(Auth::check())
        </section>
    </div>
</div>
@endif
@endsection
