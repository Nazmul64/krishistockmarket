@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-exchange text-primary me-2"></i> স্টক ইন ও স্টক আউট লেনদেন লেজার</h4>
                    <p class="text-muted small mb-0">গুদামে নতুন মালামাল প্রবেশ (Stock In) ও মালামাল ব্যবহার/বিতরণ (Stock Out) ট্র্যাকিং।</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-success rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#stockInModal">
                        <i class="fa fa-arrow-down me-1"></i> সরাসরি স্টক ইন (Stock In)
                    </button>
                    <button class="btn btn-sm btn-danger rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#stockOutModal">
                        <i class="fa fa-arrow-up me-1"></i> স্টক আউট (Stock Out)
                    </button>
                    <a href="{{ route('admin.erp.store.items') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="fa fa-boxes me-1"></i> ইনভেন্টরি
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
                    <i class="fa fa-exclamation-triangle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ট্রানজ্যাকশন নং</th>
                                <th>ধরন</th>
                                <th>আইটেম নাম ও কোড</th>
                                <th>গুদাম</th>
                                <th>পরিমাণ</th>
                                <th>একক দর</th>
                                <th>মোট মূল্য</th>
                                <th>রেফারেন্স / চালান</th>
                                <th>উদ্দেশ্য ও বিবরণ</th>
                                <th>তারিখ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $t)
                            <tr>
                                <td class="fw-bold text-primary">{{ $t->transaction_number }}</td>
                                <td>
                                    @if($t->transaction_type === 'stock_in' || $t->transaction_type === 'purchase_receive')
                                        <span class="badge bg-success"><i class="fa fa-arrow-down me-1"></i> Stock In</span>
                                    @elseif($t->transaction_type === 'stock_out')
                                        <span class="badge bg-danger"><i class="fa fa-arrow-up me-1"></i> Stock Out</span>
                                    @elseif($t->transaction_type === 'transfer')
                                        <span class="badge bg-info"><i class="fa fa-exchange me-1"></i> Transfer</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $t->transaction_type }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold text-dark d-block">{{ optional($t->item)->name_bn ?? optional($t->item)->name }}</span>
                                    <small class="text-muted">কোড: {{ optional($t->item)->code }}</small>
                                </td>
                                <td>{{ optional($t->warehouse)->name_bn ?? optional($t->warehouse)->name ?? 'Central Store' }}</td>
                                <td class="fw-bold font-size-14">{{ number_format($t->quantity) }} {{ optional(optional($t->item)->unit)->symbol }}</td>
                                <td>৳ {{ number_format($t->unit_price, 2) }}</td>
                                <td class="fw-bold text-dark">৳ {{ number_format($t->total_amount, 2) }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $t->reference_no ?? 'N/A' }}</span></td>
                                <td>{{ $t->purpose ?? $t->notes ?? '-' }}</td>
                                <td>{{ $t->created_at->format('d/m/Y h:i A') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">কোনো স্টক ট্রানজ্যাকশন হিস্ট্রি পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            </div>

        </section>
    </div>
</div>

<!-- Stock In Modal -->
<div class="modal fade" id="stockInModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-success"><i class="fa fa-arrow-down me-2"></i> স্টোরে মালামাল এন্ট্রি (Stock In)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.erp.store.stock_in_out.store') }}" method="POST">
                @csrf
                <input type="hidden" name="transaction_type" value="stock_in">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">আইটেম নির্বাচন করুন *</label>
                        <select name="item_id" class="form-select" required>
                            <option value="">-- আইটেম সিলেক্ট করুন --</option>
                            @foreach($items as $it)
                                <option value="{{ $it->id }}">{{ $it->name_bn ?? $it->name }} (বর্তমান মজুত: {{ $it->current_stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">গুদাম (Warehouse) *</label>
                        <select name="warehouse_id" class="form-select" required>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name_bn ?? $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small">পরিমাণ *</label>
                            <input type="number" step="0.01" min="0.01" name="quantity" class="form-control" placeholder="उदा: 10" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small">একক ক্রয় দর (৳)</label>
                            <input type="number" step="0.01" min="0" name="unit_price" class="form-control" placeholder="দর">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">চালান / ইনভয়েস নম্বর</label>
                        <input type="text" name="reference_no" class="form-control" placeholder="उदा: CH-94819">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">উদ্দেশ্য / নোট</label>
                        <textarea name="purpose" class="form-control" rows="2" placeholder="ক্রয়কৃত নতুন মালামাল রিসিভ..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">স্টক ইন সম্পন্ন করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Stock Out Modal -->
<div class="modal fade" id="stockOutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger"><i class="fa fa-arrow-up me-2"></i> স্টোর থেকে মালামাল বের করা (Stock Out)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.erp.store.stock_in_out.store') }}" method="POST">
                @csrf
                <input type="hidden" name="transaction_type" value="stock_out">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">আইটেম নির্বাচন করুন *</label>
                        <select name="item_id" class="form-select" required>
                            <option value="">-- আইটেম সিলেক্ট করুন --</option>
                            @foreach($items as $it)
                                <option value="{{ $it->id }}">{{ $it->name_bn ?? $it->name }} (বর্তমান মজুত: {{ $it->current_stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">গুদাম (Warehouse) *</label>
                        <select name="warehouse_id" class="form-select" required>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name_bn ?? $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">বিতরণ পরিমাণ *</label>
                        <input type="number" step="0.01" min="0.01" name="quantity" class="form-control" placeholder="उदा: 2" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">রেফারেন্স / ভাউচার নং</label>
                        <input type="text" name="reference_no" class="form-control" placeholder="उदा: ISSUE-01">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">বিতরণের উদ্দেশ্য / গ্রহীতার নাম *</label>
                        <textarea name="purpose" class="form-control" rows="2" placeholder="অফিস ব্যবহারের জন্য..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">স্টক আউট করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

