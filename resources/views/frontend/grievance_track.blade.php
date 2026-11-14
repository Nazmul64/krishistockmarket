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
            <h2 class="fw-bold display-6 text-dark mt-2">আবেদনের অগ্রগতি ট্র্যাকিং</h2>
            <p class="text-muted max-w-700 mx-auto font-size-16">
                আপনার অভিযোগ বা পরামর্শের প্রশাসনিক সমাধান ও অগ্রগতি বিবরণী।
            </p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Search Box -->
                <form action="{{ route('grievance.track') }}" method="GET" class="card border-0 shadow-sm rounded-pill p-2 bg-white d-flex flex-row align-items-center mb-4">
                    <input type="text" name="code" class="form-control border-0 ps-4 bg-transparent" placeholder="ট্র্যাকিং কোড (যেমন: GRV-...)" value="{{ $code ?? '' }}" required>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="fa fa-search me-1"></i> ট্র্যাক করুন
                    </button>
                </form>

                @if(!empty($code))
                    @if($grievance)
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                            <div>
                                <span class="badge bg-dark font-monospace px-3 py-1">{{ $grievance->tracking_code }}</span>
                                <h4 class="fw-bold text-dark mt-2 mb-0">{{ $grievance->subject }}</h4>
                            </div>
                            <span class="badge {{ match($grievance->status) {
                                'resolved' => 'bg-success',
                                'investigating' => 'bg-warning text-dark',
                                'rejected' => 'bg-danger',
                                default => 'bg-secondary',
                            } }} px-3 py-2 rounded-pill font-size-13">
                                {{ match($grievance->status) {
                                    'resolved' => 'সমাধানকৃত (Resolved)',
                                    'investigating' => 'তদন্তাধীন (Under Investigation)',
                                    'rejected' => 'বাতিল (Rejected)',
                                    default => 'অপেক্ষমাণ (Pending)',
                                } }}
                            </span>
                        </div>

                        <div class="row g-3 small mb-4">
                            <div class="col-6"><strong>আবেদনকারী:</strong> {{ $grievance->name }}</div>
                            <div class="col-6"><strong>মোবাইল:</strong> {{ $grievance->phone }}</div>
                            <div class="col-6"><strong>ধরন:</strong> {{ ucfirst($grievance->type) }}</div>
                            <div class="col-6"><strong>তারিখ:</strong> {{ \Carbon\Carbon::parse($grievance->created_at)->isoFormat('D MMMM, YYYY h:mm A') }}</div>
                        </div>

                        <div class="mb-4">
                            <strong class="text-dark d-block mb-1">অভিযোগের বিবরণ:</strong>
                            <div class="p-3 bg-light rounded-3 text-secondary font-size-14" style="line-height: 1.7;">
                                {{ $grievance->description }}
                            </div>
                        </div>

                        <!-- Administrative Response -->
                        <div class="p-4 rounded-4 {{ $grievance->admin_response ? 'bg-success-subtle border border-success' : 'bg-light' }}">
                            <h6 class="fw-bold text-dark mb-2"><i class="fa fa-reply me-2"></i> প্রশাসনিক প্রতিকার ও মন্তব্য:</h6>
                            @if($grievance->admin_response)
                                <p class="text-dark mb-0 font-size-14" style="line-height: 1.7;">
                                    {{ $grievance->admin_response }}
                                </p>
                                @if($grievance->resolved_at)
                                <small class="text-success d-block mt-2">
                                    <i class="fa fa-clock-o me-1"></i> সমাধান প্রদানের সময়: {{ \Carbon\Carbon::parse($grievance->resolved_at)->isoFormat('D MMMM, YYYY h:mm A') }}
                                </small>
                                @endif
                            @else
                                <p class="text-muted small mb-0">আপনার বিষয়টি কর্তৃপক্ষের বিবেচনাধীন রয়েছে। দ্রুততম সময়ে প্রশাসনিক উত্তর প্রদান করা হবে।</p>
                            @endif
                        </div>

                    </div>
                    @else
                    <div class="card border-0 shadow-sm rounded-4 p-5 bg-white text-center">
                        <i class="fa fa-search-minus text-muted fa-3x mb-3"></i>
                        <h5 class="text-muted">প্রদত্ত কোড "{{ $code }}" এর বিপরীতে কোনো রেকর্ড পাওয়া যায়নি।</h5>
                    </div>
                    @endif
                @endif

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
