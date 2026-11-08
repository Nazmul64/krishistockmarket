@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title text-success"><i class="fa fa-user-circle me-2"></i> Employee Dashboard (কর্মী/এজেন্ট ড্যাশবোর্ড)</h3>
                    <p class="text-muted mb-0">ব্যক্তিগত প্রোফাইল, রেফারেল লিংক, স্টক লেজার ও অর্পিত দায়িত্ব (Scope: Own Records)</p>
                </div>
                <div>
                    <span class="badge badge-lg bg-success-light text-success fw-bold px-3 py-2">
                        <i class="fa fa-check-circle me-1"></i> সক্রিয় কর্মী (Active Staff)
                    </span>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Referral Link Banner -->
            <div class="box bg-gradient-primary text-white mb-4">
                <div class="box-body py-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8 col-12">
                            <h4 class="fw-bold mb-1"><i class="fa fa-share-alt me-2"></i> আপনার ব্যক্তিগত কাস্টমার রেফারেল লিংক</h4>
                            <p class="text-white-50 mb-2">এই লিংকের মাধ্যমে যুক্ত হওয়া সকল কাস্টমার আপনার রেফারেল অ্যাকাউন্টে যুক্ত হবে।</p>
                            <div class="input-group">
                                <input type="text" id="employeeRefInput" class="form-control bg-white text-dark fw-bold" value="{{ url('/register?ref=' . $employee->username) }}" readonly>
                                <button class="btn btn-warning fw-bold text-dark px-4" type="button" onclick="copyRefLink()">
                                    <i class="fa fa-copy me-1"></i> কপি লিংক
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-4 col-12 text-lg-end mt-3 mt-lg-0">
                            <span class="font-size-36 fw-bold d-block text-warning">{{ $referralCount }}</span>
                            <span class="text-uppercase font-size-12 fw-bold text-white-50">মোট রেফারেল সদস্য</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary KPI Cards -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body p-3 bg-primary-light mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">ব্যক্তিগত ব্যালেন্স (Wallet)</span>
                                <h3 class="mb-0 fw-bold text-primary">৳{{ number_format($employee->balance, 2) }}</h3>
                            </div>
                            <i class="fa fa-money text-primary font-size-28"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body p-3 bg-success-light mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">বরাদ্দকৃত স্টক আইটেম</span>
                                <h3 class="mb-0 fw-bold text-success">{{ $agentStocks->count() }} টি পণ্য</h3>
                            </div>
                            <i class="fa fa-cubes text-success font-size-28"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body p-3 bg-info-light mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">আওয়ার প্যাকেজসমূহ</span>
                                <h4 class="mb-1 fw-bold text-info">{{ $packagesCount ?? 0 }} টি প্যাকেজ</h4>
                                <a href="{{ route('employee.packages') }}" class="small text-info fw-bold text-decoration-none">
                                    <i class="fa fa-external-link me-1"></i> ছবি ও কনটেন্ট
                                </a>
                            </div>
                            <i class="fa fa-cube text-info font-size-28"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body p-3 bg-warning-light mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">অফিসিয়াল পদবি / দায়িত্ব</span>
                                <h4 class="mb-0 fw-bold text-warning">{{ optional($employee->roleRelation)->name ?? 'Marketing Agent' }}</h4>
                            </div>
                            <i class="fa fa-id-card text-warning font-size-28"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stocks & Announcements -->
            <div class="row">
                <!-- Agent Stock Ledger -->
                <div class="col-xl-8 col-12">
                    <div class="box">
                        <div class="box-header with-border d-flex justify-content-between align-items-center">
                            <h4 class="box-title text-success"><i class="fa fa-cube me-2"></i> আমার লাইভ স্টক লেজার (Live Stock Inventory)</h4>
                            <a href="{{ route('employee.stock_ledger.index') }}" class="btn btn-xs btn-outline btn-success">পূর্ণ লেজার দেখুন</a>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 font-size-13">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>পণ্য</th>
                                            <th>বর্তমান মজুদ (Stock)</th>
                                            <th>একক মূল্য</th>
                                            <th class="text-end">মোট মূল্য</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($agentStocks as $st)
                                        <tr>
                                            <td class="fw-bold text-dark">{{ optional($st->stock)->name ?? 'Product' }}</td>
                                            <td><span class="badge bg-success font-size-12">{{ $st->quantity }} {{ optional($st->stock)->unit ?? 'Unit' }}</span></td>
                                            <td>৳{{ number_format(optional($st->stock)->price ?? 0, 2) }}</td>
                                            <td class="text-end fw-bold">৳{{ number_format(($st->quantity * (optional($st->stock)->price ?? 0)), 2) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">বর্তমানে আপনার কাছে কোনো অবিক্রিত পণ্য মজুদ নেই।</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notices -->
                <div class="col-xl-4 col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title text-info"><i class="fa fa-bullhorn me-2"></i> অফিসিয়াল নোটিশ বোর্ড</h4>
                        </div>
                        <div class="box-body">
                            @if(isset($siteSetting) && $siteSetting->notice)
                            <div class="alert alert-info py-2 font-size-13">
                                <i class="fa fa-info-circle me-1"></i> {{ $siteSetting->notice }}
                            </div>
                            @endif

                            <div class="list-group list-group-flush">
                                <div class="list-group-item px-0">
                                    <h6 class="fw-bold mb-1 text-dark">দৈনিক উপস্থিতি নিশ্চিতকরণ</h6>
                                    <p class="text-muted font-size-12 mb-0">প্রতিদিন সকাল ৯:৩০ এর মধ্যে সিস্টেমে উপস্থিতি নিশ্চিত করুন।</p>
                                </div>
                                <div class="list-group-item px-0">
                                    <h6 class="fw-bold mb-1 text-dark">মাসিক বাজার সরবরাহ শিডিউল</h6>
                                    <p class="text-muted font-size-12 mb-0">অনুমোদিত কাস্টমার অর্ডারগুলো সংশ্লিষ্ট ডেলিভারি পয়েন্টে হস্তান্তর করুন।</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
function copyRefLink() {
    var copyText = document.getElementById("employeeRefInput");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    alert("রেফারেল লিংক সফলভাবে কপি করা হয়েছে!");
}
</script>
@endsection
