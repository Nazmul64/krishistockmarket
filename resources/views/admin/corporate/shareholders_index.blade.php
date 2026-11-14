@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-pie-chart text-primary me-2"></i> শেয়ারহোল্ডার ও মূলধন ব্যবস্থাপনা (Share Capital)</h4>
                    <p class="text-muted small mb-0">প্রতিষ্ঠানের সর্বমোট ১০০টি শেয়ারের বরাদ্দ, শেয়ার মূলধন হিসাব, অফিস খরচ ও লভ্যাংশ বণ্টন।</p>
                </div>
                <button class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addShareholderModal">
                    <i class="fa fa-plus me-1"></i> নতুন শেয়ারহোল্ডার বরাদ্দ
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- 4 Capital Structure KPI Cards (Section 4.2) -->
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <span class="text-muted small d-block">মোট মূলধন কাঠামো</span>
                        <h4 class="fw-bold text-dark mt-1 mb-0">{{ number_format($shareSetting->total_share_capital) }} ৳</h4>
                        <small class="text-muted">মোট শেয়ার: {{ $shareSetting->total_shares }}টি (৫,০০০৳/শেয়ার)</small>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <span class="text-muted small d-block">বরাদ্দকৃত শেয়ার</span>
                        <h4 class="fw-bold text-primary mt-1 mb-0">{{ $allocatedShares }} টি</h4>
                        <small class="text-success">অবশিষ্ট উন্মুক্ত: {{ $shareSetting->total_shares - $allocatedShares }} টি</small>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <span class="text-muted small d-block">সংগৃহীত শেয়ার মূলধন</span>
                        <h4 class="fw-bold text-success mt-1 mb-0">{{ number_format($totalCapital) }} ৳</h4>
                        <small class="text-muted">মোট শেয়ারহোল্ডার: {{ $shareholders->total() }} জন</small>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <span class="text-muted small d-block">অফিস খরচ কালেকশন</span>
                        <h4 class="fw-bold text-danger mt-1 mb-0">{{ number_format($totalOfficeFee) }} ৳</h4>
                        <small class="text-muted">প্রতি শেয়ারে ৩,০০০ টাকা</small>
                    </div>
                </div>
            </div>

            <!-- Shareholders Table -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>শেয়ারহোল্ডার কোড</th>
                                <th>নাম ও মোবাইল</th>
                                <th>বরাদ্দকৃত শেয়ার</th>
                                <th>মূলধন বিনিয়োগ</th>
                                <th>অফিস খরচ</th>
                                <th>মোট লভ্যাংশ</th>
                                <th>নমিনি ও এনআইডি</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shareholders as $sh)
                            <tr>
                                <td class="fw-bold font-monospace text-primary">{{ $sh->shareholder_code }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $sh->user->name ?? 'শেয়ারহোল্ডার' }}</div>
                                    <small class="text-muted">{{ $sh->user->phone ?? '' }}</small>
                                </td>
                                <td><span class="badge bg-purple-subtle text-purple fw-bold font-size-12" style="background: rgba(147, 51, 234, 0.1); color: #9333ea;">{{ $sh->shares_count }} টি শেয়ার</span></td>
                                <td class="fw-bold text-dark font-size-14">{{ number_format($sh->total_investment, 2) }} ৳</td>
                                <td class="text-danger fw-semibold">{{ number_format($sh->office_expense_paid, 2) }} ৳</td>
                                <td>
                                    <span class="text-success fw-bold d-block">{{ number_format($sh->total_dividend_earned, 2) }} ৳</span>
                                    <small class="text-muted">ব্যালেন্স: {{ number_format($sh->available_dividend_balance, 2) }} ৳</small>
                                </td>
                                <td>
                                    <div>{{ $sh->nominee_name ?: 'নির্ধারিত নয়' }} ({{ $sh->nominee_relation ?: '' }})</div>
                                    <small class="text-muted font-monospace">{{ $sh->nid_number ?: '' }}</small>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-outline-success btn-xs rounded-pill px-2" data-bs-toggle="modal" data-bs-target="#divModal{{ $sh->id }}">
                                        <i class="fa fa-plus-circle me-1"></i> লভ্যাংশ
                                    </button>
                                </td>
                            </tr>

                            <!-- Dividend Modal -->
                            <div class="modal fade" id="divModal{{ $sh->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold text-dark">শেয়ারহোল্ডার লভ্যাংশ প্রদান</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.corporate.shareholders.dividend', $sh->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <p class="small text-muted mb-2">শেয়ারহোল্ডার: <strong>{{ $sh->user->name ?? '' }}</strong> (শেয়ার: {{ $sh->shares_count }} টি)</p>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold text-muted">ঘোষিত লভ্যাংশের পরিমাণ (টাকা) *</label>
                                                    <input type="number" name="amount" class="form-control" placeholder="যেমন: ৫০০০" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">বাতিল</button>
                                                <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">প্রদান নিশ্চিত করুন</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">কোনো শেয়ারহোল্ডার রেকর্ড পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $shareholders->links() }}
                </div>
            </div>

        </section>
    </div>
</div>

<!-- Add Shareholder Modal -->
<div class="modal fade" id="addShareholderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">নতুন শেয়ারহোল্ডার বরাদ্দ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.corporate.shareholders.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">সদস্য / ব্যবহারকারী নির্বাচন করুন *</label>
                        <select name="user_id" class="form-select" required>
                            @php
                                $usersList = \App\Models\User::where('status', 'active')->take(50)->get();
                            @endphp
                            @foreach($usersList as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->phone ?: $u->username }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">বরাদ্দকৃত শেয়ারের সংখ্যা *</label>
                        <input type="number" name="shares_count" class="form-control" min="1" max="{{ $shareSetting->total_shares - $allocatedShares }}" value="1" required>
                        <small class="text-muted">প্রতি শেয়ার ৫,০০০ টাকা + অফিস খরচ ৩,০০০ টাকা</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">এনআইডি নম্বর (NID)</label>
                        <input type="text" name="nid_number" class="form-control" placeholder="জাতীয় পরিচয়পত্র নম্বর">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-muted">নমিনির নাম</label>
                            <input type="text" name="nominee_name" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-muted">সম্পর্ক</label>
                            <input type="text" name="nominee_relation" class="form-control" placeholder="স্ত্রী / সন্তান">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">বরাদ্দ নিশ্চিত করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

