@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-handshake-o text-success me-2"></i> ব্যবসায়িক পার্টনারশিপ ও অংশীদারিত্ব (Partnerships)</h4>
                    <p class="text-muted small mb-0">ব্যবসায়িক অংশীদার নিবন্ধন, চুক্তিনামা, মূলধন অবদান ও মুনাফা বণ্টন ব্যবস্থাপনা।</p>
                </div>
                <button class="btn btn-success btn-sm rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addPartnerModal">
                    <i class="fa fa-plus me-1"></i> নতুন পার্টনার যুক্ত করুন
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>পার্টনার কোড</th>
                                <th>পার্টনার ও প্রতিষ্ঠানের নাম</th>
                                <th>অংশীদারিত্বের ধরন</th>
                                <th>চুক্তি নম্বর</th>
                                <th>মূলধন অবদান</th>
                                <th>মুনাফার শতকরা হার</th>
                                <th>মোট বণ্টনকৃত মুনাফা</th>
                                <th>স্ট্যাটাস</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($partnerships as $p)
                            <tr>
                                <td class="fw-bold font-monospace text-primary">{{ $p->partner_code }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $p->partner_name }}</div>
                                    <small class="text-muted">{{ $p->business_name ?: $p->user->phone ?? '' }}</small>
                                </td>
                                <td><span class="badge bg-light text-dark text-capitalize">{{ str_replace('_', ' ', $p->partnership_type) }}</span></td>
                                <td class="font-monospace">{{ $p->contract_number ?: 'CT-' . $p->id }}</td>
                                <td class="fw-bold text-primary font-size-14">{{ number_format($p->capital_contribution, 2) }} ৳</td>
                                <td class="fw-bold text-success">{{ $p->profit_share_percentage }}%</td>
                                <td class="text-success fw-semibold">{{ number_format($p->total_profit_shared, 2) }} ৳</td>
                                <td>
                                    <span class="badge {{ $p->status === 'active' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ $p->status === 'active' ? 'সক্রিয়' : 'প্রক্রিয়াধীন' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">কোনো পার্টনারশিপ রেকর্ড পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $partnerships->links() }}
                </div>
            </div>

        </section>
    </div>
</div>

<!-- Add Partner Modal -->
<div class="modal fade" id="addPartnerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">নতুন পার্টনারশিপ নিবন্ধন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.corporate.partnerships.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">ব্যবহারকারী একাউন্ট *</label>
                            <select name="user_id" class="form-select" required>
                                @foreach(\App\Models\User::where('status', 'active')->take(50)->get() as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->phone ?: $u->username }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">পার্টনারের নাম *</label>
                            <input type="text" name="partner_name" class="form-control" placeholder="ব্যক্তি বা উদ্যোক্তার নাম" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">প্রতিষ্ঠানের নাম</label>
                            <input type="text" name="business_name" class="form-control" placeholder="কোম্পানি বা ফার্মের নাম">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">অংশীদারিত্বের ধরন *</label>
                            <select name="partnership_type" class="form-select" required>
                                <option value="business_expansion">ব্যবসা সম্প্রসারণ (Business Expansion)</option>
                                <option value="equity">ইকুইটি পার্টনারশিপ (Equity Partnership)</option>
                                <option value="project_joint_venture">প্রকল্পভিত্তিক যৌথ উদ্যোগ (Joint Venture)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted">মূলধন অবদান (টাকা) *</label>
                            <input type="number" name="capital_contribution" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted">মুনাফার শতকরা হার (%) *</label>
                            <input type="number" step="0.1" name="profit_share_percentage" class="form-control" placeholder="যেমন: ১৫" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted">চুক্তি নম্বর (Contract ID)</label>
                            <input type="text" name="contract_number" class="form-control" placeholder="যেমন: CT-2026-001">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">শুরুর তারিখ</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">মেয়াদ শেষ</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted">চুক্তির শর্তাবলী</label>
                            <textarea name="terms_and_conditions" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold text-muted">চুক্তিপত্র ফাইল আপলোড (PDF/Image)</label>
                            <input type="file" name="agreement_file" class="form-control" accept="image/*,application/pdf">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

