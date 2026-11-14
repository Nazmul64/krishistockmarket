@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="ti-truck text-success me-2"></i>সেন্ট্রাল প্রকিউরমেন্ট ও সাপ্লায়ার ম্যানেজমেন্ট
                    </h3>
                    <p class="text-muted mb-0">সাপ্লায়ার ডিরেক্টরি, পারচেজ অর্ডার (PO) ও পণ্য গ্রহণ নোট (GRN) এর মাধ্যমে স্বয়ংক্রিয় স্টক বৃদ্ধি</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-info fw-bold" data-bs-toggle="modal" data-bs-target="#createSupplierModal">
                        <i class="ti-plus me-1"></i> নতুন সাপ্লায়ার
                    </button>
                    <button type="button" class="btn btn-sm btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#createPoModal">
                        <i class="ti-file me-1"></i> নতুন পারচেজ অর্ডার (PO)
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-circle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- 1. Purchase Orders List -->
            <div class="box shadow-sm mb-4">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title fw-bold text-dark mb-0"><i class="ti-receipt text-primary me-2"></i>পারচেজ অর্ডার তালিকা (Purchase Orders)</h4>
                    <span class="badge bg-primary-light text-primary">সর্বমোট {{ $purchaseOrders->total() }} টি PO</span>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>PO নম্বর</th>
                                    <th>সাপ্লায়ার</th>
                                    <th>গন্তব্য ব্রাঞ্চ</th>
                                    <th>অর্ডারের তারিখ</th>
                                    <th>প্রত্যাশিত ডেলিভারি</th>
                                    <th class="text-center">আইটেম সংখ্যা</th>
                                    <th class="text-end">মোট মূল্য</th>
                                    <th class="text-center">স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchaseOrders as $po)
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-primary">{{ $po->po_number }}</span>
                                        </td>
                                        <td>
                                            <strong class="text-dark">{{ $po->supplier ? $po->supplier->company_name ?: $po->supplier->name : 'N/A' }}</strong>
                                            <div class="small text-muted">{{ $po->supplier ? $po->supplier->phone : '' }}</div>
                                        </td>
                                        <td>
                                            @php
                                                $destBranch = $branches->firstWhere('id', $po->destination_id);
                                            @endphp
                                            <span class="badge bg-dark">{{ $destBranch ? $destBranch->code : 'Hub' }}</span>
                                            <span class="small ms-1">{{ $destBranch ? $destBranch->name : 'Central Warehouse' }}</span>
                                        </td>
                                        <td class="small">{{ $po->order_date }}</td>
                                        <td class="small text-muted">{{ $po->expected_delivery_date ?: '-' }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border">{{ $po->items->count() }} টি</span>
                                        </td>
                                        <td class="text-end fw-bold text-success">
                                            ৳ {{ number_format($po->total_amount, 2) }}
                                        </td>
                                        <td class="text-center">
                                            @if($po->status == 'received')
                                                <span class="badge bg-success">পণ্য গৃহীত (Received)</span>
                                            @elseif($po->status == 'approved')
                                                <span class="badge bg-info">অনুমোদিত (Approved)</span>
                                            @else
                                                <span class="badge bg-warning text-dark">{{ $po->status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($po->status !== 'received')
                                                <button type="button" class="btn btn-xs btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#grnModal{{ $po->id }}">
                                                    <i class="ti-check-box me-1"></i> GRN রিসিভ
                                                </button>
                                            @else
                                                <span class="badge bg-success-light text-success"><i class="ti-check"></i> সম্পূর্ণ</span>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- GRN Modal for this PO -->
                                    <div class="modal fade" id="grnModal{{ $po->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <form method="POST" action="{{ route('admin.supershop.procurement.grn.store') }}">
                                                @csrf
                                                <input type="hidden" name="po_id" value="{{ $po->id }}">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-success text-white">
                                                        <h5 class="modal-title fw-bold text-white">পণ্য রিসিভ ও স্টক বৃদ্ধি (GRN) – {{ $po->po_number }}</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="alert alert-info py-2 small mb-3">
                                                            সাপ্লায়ার: <strong>{{ $po->supplier ? $po->supplier->name : '' }}</strong> | গন্তব্য: <strong>{{ $destBranch ? $destBranch->name : '' }}</strong>
                                                        </div>

                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-sm align-middle mb-3">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>পণ্য</th>
                                                                        <th class="text-center">অর্ডারকৃত পরিমাণ</th>
                                                                        <th class="text-center" style="width: 140px;">গৃহীত পরিমাণ (Received)</th>
                                                                        <th class="text-center" style="width: 120px;">ত্রুটিপূর্ণ / ড্যামেজ</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($po->items as $item)
                                                                        <tr>
                                                                            <td>
                                                                                <strong>{{ $item->product ? $item->product->name : 'পণ্য #'.$item->product_id }}</strong>
                                                                                <div class="smaller text-muted">দর: ৳{{ $item->purchase_price }}</div>
                                                                            </td>
                                                                            <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                                                            <td>
                                                                                <input type="number" name="received_qty[{{ $item->id }}]" class="form-control form-control-sm text-center fw-bold" value="{{ $item->quantity }}" min="0" max="{{ $item->quantity }}">
                                                                            </td>
                                                                            <td>
                                                                                <input type="number" name="damaged_qty[{{ $item->id }}]" class="form-control form-control-sm text-center text-danger" value="0" min="0">
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">চালান নোট / মন্তব্য</label>
                                                            <input type="text" name="notes" class="form-control" placeholder="যেমন: পণ্য সম্পূর্ণ ফ্রেশ অবস্থায় রিসিভ করা হয়েছে।">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                                                        <button type="submit" class="btn btn-success fw-bold">স্টকে যুক্ত করুন (Approve GRN)</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">কোনো পারচেজ অর্ডার পাওয়া যায়নি।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($purchaseOrders->hasPages())
                    <div class="box-footer">
                        {{ $purchaseOrders->links() }}
                    </div>
                @endif
            </div>

            <!-- 2. Suppliers Directory -->
            <div class="box shadow-sm">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title fw-bold text-dark mb-0"><i class="ti-id-badge text-info me-2"></i>অনুমোদিত সাপ্লায়ার ও ভেন্ডর তালিকা (Suppliers)</h4>
                    <span class="badge bg-info-light text-info">{{ $suppliers->count() }} টি রেজিস্টার্ড সাপ্লায়ার</span>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>কোম্পানি / সাপ্লায়ার নাম</th>
                                    <th>কন্টাক্ট পারসন</th>
                                    <th>ফোন ও ইমেইল</th>
                                    <th>ঠিকানা</th>
                                    <th>ট্যাক্স / ট্রেড লাইসেন্স</th>
                                    <th class="text-center">মোট PO</th>
                                    <th class="text-center">স্ট্যাটাস</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($suppliers as $sup)
                                    <tr>
                                        <td>
                                            <strong class="text-dark">{{ $sup->company_name ?: $sup->name }}</strong>
                                            @if($sup->company_name && $sup->name)
                                                <div class="small text-muted">{{ $sup->name }}</div>
                                            @endif
                                        </td>
                                        <td>{{ $sup->contact_person ?: '-' }}</td>
                                        <td class="small">
                                            <div><i class="ti-mobile text-success me-1"></i> {{ $sup->phone }}</div>
                                            @if($sup->email)
                                                <div class="text-muted"><i class="ti-email me-1"></i> {{ $sup->email }}</div>
                                            @endif
                                        </td>
                                        <td class="small text-muted">{{ Str::limit($sup->address, 30) ?: '-' }}</td>
                                        <td class="small">{{ $sup->tax_number ?: ($sup->trade_license ?: '-') }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border">{{ $sup->purchase_orders_count }} টি</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">সক্রিয়</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">কোনো সাপ্লায়ার নিবন্ধিত নেই।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- Create Supplier Modal -->
        <div class="modal fade" id="createSupplierModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('admin.supershop.procurement.suppliers.store') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-info text-white">
                            <h5 class="modal-title fw-bold text-white"><i class="ti-plus me-1"></i> নতুন সাপ্লায়ার যোগ করুন</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">সাপ্লায়ার / ভেন্ডরের নাম <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="যেমন: ACI Consumer Foods" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">প্রতিষ্ঠানের নাম</label>
                                <input type="text" name="company_name" class="form-control" placeholder="যেমন: ACI Limited">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">কন্টাক্ট পারসন</label>
                                <input type="text" name="contact_person" class="form-control" placeholder="দায়িত্বপ্রাপ্ত কর্মকর্তার নাম">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">মোবাইল নম্বর <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" placeholder="017xxxxxxxx" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ইমেইল ঠিকানা</label>
                                <input type="email" name="email" class="form-control" placeholder="supplier@example.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ট্যাক্স / ভ্যাট নম্বর (BIN/TIN)</label>
                                <input type="text" name="tax_number" class="form-control" placeholder="BIN-123456789">
                            </div>
                            <div class="col-12">
                                <label class="form-label">ঠিকানা</label>
                                <textarea name="address" class="form-control" rows="2" placeholder="সাপ্লায়ারের হেড অফিস বা ডিপো ঠিকানা..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                            <button type="submit" class="btn btn-info fw-bold text-white">সাপ্লায়ার সংরক্ষণ করুন</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Create Purchase Order Modal -->
        <div class="modal fade" id="createPoModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('admin.supershop.procurement.po.store') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title fw-bold text-white"><i class="ti-file me-1"></i> নতুন পারচেজ অর্ডার (PO) তৈরি করুন</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">সাপ্লায়ার নির্বাচন করুন <span class="text-danger">*</span></label>
                                <select name="supplier_id" class="form-select" required>
                                    <option value="">-- সাপ্লায়ার নির্বাচন করুন --</option>
                                    @foreach($suppliers as $sup)
                                        <option value="{{ $sup->id }}">{{ $sup->company_name ?: $sup->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">ডেলিভারি গন্তব্য ব্রাঞ্চ <span class="text-danger">*</span></label>
                                <select name="destination_id" class="form-select" required>
                                    @foreach($branches as $b)
                                        <option value="{{ $b->id }}">{{ $b->code }} - {{ $b->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">অর্ডারের তারিখ <span class="text-danger">*</span></label>
                                <input type="date" name="order_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">প্রত্যাশিত ডেলিভারি তারিখ</label>
                                <input type="date" name="expected_delivery_date" class="form-control" value="{{ date('Y-m-d', strtotime('+3 days')) }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">অর্ডারকৃত পণ্য ও পরিমাণ (Product Items) <span class="text-danger">*</span></label>
                                <div class="border rounded p-2 bg-light">
                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <select name="product_id[]" class="form-select form-select-sm" required>
                                                <option value="">-- পণ্য ১ --</option>
                                                @foreach($products as $prod)
                                                    <option value="{{ $prod->id }}">{{ $prod->name }} (৳{{ $prod->price }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <input type="number" name="quantity[]" class="form-control form-control-sm" placeholder="পরিমাণ" value="100" min="1" required>
                                        </div>
                                        <div class="col-3">
                                            <input type="number" step="0.01" name="purchase_price[]" class="form-control form-control-sm" placeholder="ক্রয়মূল্য (Rate)" value="150" min="0" required>
                                        </div>
                                    </div>

                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <select name="product_id[]" class="form-select form-select-sm">
                                                <option value="">-- পণ্য ২ (ঐচ্ছিক) --</option>
                                                @foreach($products as $prod)
                                                    <option value="{{ $prod->id }}">{{ $prod->name }} (৳{{ $prod->price }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <input type="number" name="quantity[]" class="form-control form-control-sm" placeholder="পরিমাণ" value="50" min="1">
                                        </div>
                                        <div class="col-3">
                                            <input type="number" step="0.01" name="purchase_price[]" class="form-control form-control-sm" placeholder="ক্রয়মূল্য (Rate)" value="220" min="0">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">অর্ডার নোট</label>
                                <input type="text" name="notes" class="form-control" placeholder="যেমন: আগামী ৩ দিনের মধ্যে তেজগাঁও সেন্ট্রাল ডিপোতে পৌঁছাতে হবে।">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                            <button type="submit" class="btn btn-success fw-bold">PO তৈরি ও ইস্যু করুন</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

