@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="ti-time text-primary me-2"></i>স্টক মুভমেন্ট অডিট ট্রেইল (Stock Movement Audit Log)
                    </h3>
                    <p class="text-muted mb-0">সকল ইনকামিং, সেলস, ড্যামেজ ও ব্রাঞ্চ ট্রান্সফারের পুঙ্খানুপুঙ্খ অডিট রেকর্ড</p>
                </div>
                <div>
                    <a href="{{ route('admin.supershop.inventory', ['branch_id' => $selectedBranchId]) }}" class="btn btn-sm btn-outline-primary">
                        <i class="ti-arrow-left me-1"></i> ইনভেন্টরি তালিকায় ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <!-- Filter Bar -->
            <div class="box shadow-sm mb-3">
                <div class="box-body py-2">
                    <form method="GET" action="{{ route('admin.supershop.inventory.movements') }}" class="row g-2 align-items-center">
                        <div class="col-md-4 col-12">
                            <select name="branch_id" class="form-select form-select-sm fw-bold" onchange="this.form.submit()">
                                <option value="">🏢 সকল ব্রাঞ্চ (All Branches)</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}" {{ $selectedBranchId == $b->id ? 'selected' : '' }}>
                                        {{ $b->code }} - {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Movements Table -->
            <div class="box shadow-sm">
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>মুভমেন্টের ধরন</th>
                                    <th>পণ্য (Product)</th>
                                    <th>ব্রাঞ্চ</th>
                                    <th class="text-center">পরিমাণ পরিবর্তন</th>
                                    <th class="text-center">অবশিষ্ট ব্যালেন্স</th>
                                    <th>রেফারেন্স নম্বর</th>
                                    <th>অপারেটর (User)</th>
                                    <th>নোট / কারণ</th>
                                    <th class="text-end">তারিখ ও সময়</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($movements as $m)
                                    <tr>
                                        <td>
                                            @if($m->type == 'pos_sale')
                                                <span class="badge bg-danger">POS বিক্রয় (Sale)</span>
                                            @elseif($m->type == 'purchase_in' || $m->type == 'grn_in')
                                                <span class="badge bg-success">ক্রয় ইন (Purchase)</span>
                                            @elseif($m->type == 'transfer_in')
                                                <span class="badge bg-info">ব্রাঞ্চ রিসিভ (Transfer In)</span>
                                            @elseif($m->type == 'transfer_out')
                                                <span class="badge bg-warning text-dark">ব্রাঞ্চ ডিসপ্যাচ (Transfer Out)</span>
                                            @elseif($m->type == 'return_in')
                                                <span class="badge bg-primary">কাস্টমার রিটার্ন (Return)</span>
                                            @elseif($m->type == 'adjustment_add')
                                                <span class="badge bg-success-light text-success">সমন্বয় যোগ (+Adj)</span>
                                            @elseif($m->type == 'adjustment_subtract')
                                                <span class="badge bg-danger-light text-danger">সমন্বয় বিয়োগ (-Adj)</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $m->type }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong class="text-dark">{{ $m->product ? $m->product->name : 'Product #'.$m->product_id }}</strong>
                                            <div class="small text-muted">SKU: {{ $m->product ? $m->product->sku : 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-dark">{{ $m->branch ? $m->branch->code : '' }}</span>
                                            <span class="small ms-1">{{ $m->branch ? $m->branch->name : '' }}</span>
                                        </td>
                                        <td class="text-center fw-bold {{ $m->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $m->quantity > 0 ? '+' : '' }}{{ $m->quantity }}
                                        </td>
                                        <td class="text-center fw-bold text-dark">
                                            {{ $m->balance_after }}
                                        </td>
                                        <td>
                                            <span class="small fw-bold text-muted">{{ $m->reference_no ?: 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="small">{{ $m->user ? $m->user->name : 'System' }}</span>
                                        </td>
                                        <td class="small text-muted">
                                            {{ $m->notes ?: '-' }}
                                        </td>
                                        <td class="text-end small text-muted">
                                            {{ \Carbon\Carbon::parse($m->created_at)->format('d M Y, h:i A') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">কোনো স্টক মুভমেন্ট রেকর্ড পাওয়া যায়নি।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($movements->hasPages())
                    <div class="box-footer">
                        {{ $movements->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection

