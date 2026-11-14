@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-cubes text-primary me-2"></i> এজেন্ট ও ডিলার ইনভেন্টরি ব্যালেন্স (Agent & Dealer Management)</h4>
                    <p class="text-muted small mb-0">এজেন্টভিত্তিক পণ্য সরবরাহ, পেমেন্ট ব্যালেন্স, কাস্টমার বকেয়া ও বিক্রয় কমিশন ব্যবস্থাপনা।</p>
                </div>
                <button class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addInvModal">
                    <i class="fa fa-plus me-1"></i> পণ্য ব্যালেন্স এন্ট্রি
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
                    <table class="table table-bordered table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>এজেন্ট / ডিলার</th>
                                <th>পণ্যের নাম</th>
                                <th>পণ্যের মূল্য</th>
                                <th>পেমেন্ট ব্যালেন্স</th>
                                <th>কাস্টমার ব্যালেন্স</th>
                                <th>স্টক পরিমাণ</th>
                                <th>মোট বিক্রয়</th>
                                <th>কমিশন</th>
                                <th>বিস্তারিত বিবরণ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventories as $inv)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $inv->agent->name ?? 'এজেন্ট' }}</div>
                                    <small class="text-muted">{{ $inv->agent->agent_shop_name ?: ($inv->agent->phone ?? '') }}</small>
                                </td>
                                <td class="fw-bold">{{ $inv->product_name }}</td>
                                <td>{{ number_format($inv->product_price, 2) }} ৳</td>
                                <td class="text-success fw-bold">{{ number_format($inv->payment_balance, 2) }} ৳</td>
                                <td class="text-danger fw-bold">{{ number_format($inv->customer_balance, 2) }} ৳</td>
                                <td><span class="badge bg-light text-dark font-size-12">{{ $inv->stock_quantity }} একক</span></td>
                                <td>{{ number_format($inv->total_sold_amount, 2) }} ৳</td>
                                <td class="text-success fw-semibold">{{ number_format($inv->commission_earned, 2) }} ৳</td>
                                <td class="text-muted">{{ $inv->details ?: '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">মেসার্স ভাই ভাই এন্টারপ্রাইজ</div>
                                    <small class="text-muted">ধামরাই বাজার</small>
                                </td>
                                <td class="fw-bold">ধান ও চাল</td>
                                <td>২০০ টাকা</td>
                                <td class="text-success fw-bold">১৫০ টাকা</td>
                                <td class="text-danger fw-bold">৫০ টাকা</td>
                                <td><span class="badge bg-light text-dark">১০০ কেজি</span></td>
                                <td>২০,০০০ টাকা</td>
                                <td class="text-success fw-semibold">১,০০০ টাকা</td>
                                <td class="text-muted">৫০ টাকা কাস্টমার ব্যালেন্স সমন্বিত</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $inventories->links() }}
                </div>
            </div>

        </section>
    </div>
</div>

<!-- Add Inventory Modal -->
<div class="modal fade" id="addInvModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">নতুন প্রোডাক্ট ব্যালেন্স এন্ট্রি</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.corporate.agent_inventory.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">এজেন্ট / ডিলার নির্বাচন করুন *</label>
                        <select name="agent_id" class="form-select" required>
                            @foreach($agents as $ag)
                                <option value="{{ $ag->id }}">{{ $ag->name }} ({{ $ag->agent_shop_name ?: $ag->phone }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">পণ্যের নাম *</label>
                        <input type="text" name="product_name" class="form-control" placeholder="যেমন: ধান ও চাল" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-muted">পণ্যের মূল্য (টাকা) *</label>
                            <input type="number" name="product_price" class="form-control" placeholder="২০০" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-muted">স্টক পরিমাণ *</label>
                            <input type="number" name="stock_quantity" class="form-control" value="10" required>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-muted">পেমেন্ট ব্যালেন্স (টাকা)</label>
                            <input type="number" name="payment_balance" class="form-control" placeholder="১৫০" value="0">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold text-muted">কাস্টমার ব্যালেন্স (বকেয়া)</label>
                            <input type="number" name="customer_balance" class="form-control" placeholder="৫০" value="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">বিস্তারিত বিবরণ</label>
                        <input type="text" name="details" class="form-control" placeholder="যেমন: ৫০ টাকা কাস্টমার ব্যালেন্স">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

