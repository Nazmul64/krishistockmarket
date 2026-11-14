@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-handshake-o text-primary me-2"></i> ক্রয় ও সরবরাহকারী ব্যবস্থাপনা (Procurement & Suppliers)</h4>
                    <p class="text-muted small mb-0">সরবরাহকারী প্রোফাইল, পারচেজ অর্ডার (PO), গুডস রিসিভ নোট (GRN) ও মান নিয়ন্ত্রণ পরিদর্শন।</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#createPoModal">
                        <i class="fa fa-file-text me-1"></i> নতুন পারচেজ অর্ডার (PO)
                    </button>
                    <button class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                        <i class="fa fa-user-plus me-1"></i> নতুন সরবরাহকারী
                    </button>
                    <button class="btn btn-sm btn-success rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#createGrnModal">
                        <i class="fa fa-check-square-o me-1"></i> পণ্য গ্রহণ (GRN)
                    </button>
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

            <!-- 1. Purchase Orders Section -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fa fa-shopping-cart text-primary me-2"></i> পারচেজ অর্ডার তালিকা (Purchase Orders)
                    </h5>
                    <span class="badge bg-primary">{{ count($purchaseOrders) }} টি অর্ডার</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>PO নম্বর</th>
                                <th>সরবরাহকারী</th>
                                <th>আইটেম ও পরিমাণ</th>
                                <th>তারিখ</th>
                                <th>ডেলিভারি প্রত্যাশিত</th>
                                <th>মোট মূল্য</th>
                                <th>পেমেন্ট শর্ত</th>
                                <th>স্ট্যাটাস</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchaseOrders as $po)
                            <tr>
                                <td class="fw-bold text-primary">{{ $po->po_number }}</td>
                                <td class="fw-bold text-dark">{{ optional($po->supplier)->supplier_name }}</td>
                                <td>
                                    <ul class="list-unstyled mb-0 font-size-11">
                                        @foreach($po->items as $pi)
                                            <li>{{ optional($pi->item)->name_bn ?? optional($pi->item)->name }}: {{ number_format($pi->quantity) }} টি (গৃহীত: {{ number_format($pi->received_qty) }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{ $po->po_date }}</td>
                                <td>{{ $po->delivery_due_date ?? '-' }}</td>
                                <td class="fw-bold text-dark">৳ {{ number_format($po->total_amount, 2) }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $po->payment_terms }}</span></td>
                                <td>
                                    @if($po->status === 'received')
                                        <span class="badge bg-success"><i class="fa fa-check-circle me-1"></i> গৃহীত (Received)</span>
                                    @elseif($po->status === 'approved')
                                        <span class="badge bg-primary">অনুমোদিত (Pending GRN)</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $po->status }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">কোনো পারচেজ অর্ডার পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. Suppliers Section -->
            <div class="row g-3 mb-4">
                <div class="col-xl-7 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                            <i class="fa fa-users text-success me-2"></i> সরবরাহকারী ও ভেন্ডর তালিকা (Suppliers)
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle small mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>কোড</th>
                                        <th>সরবরাহকারীর নাম</th>
                                        <th>যোগাযোগ</th>
                                        <th>বকেয়া দেনা</th>
                                        <th>শর্ত</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($suppliers as $sup)
                                    <tr>
                                        <td class="fw-bold text-primary">{{ $sup->supplier_code }}</td>
                                        <td>
                                            <span class="fw-bold text-dark d-block">{{ $sup->supplier_name }}</span>
                                            <small class="text-muted">{{ $sup->contact_person }}</small>
                                        </td>
                                        <td>
                                            <div>{{ $sup->phone }}</div>
                                            <small class="text-muted">{{ $sup->email }}</small>
                                        </td>
                                        <td class="fw-bold text-danger">৳ {{ number_format($sup->current_balance, 2) }}</td>
                                        <td><span class="badge bg-secondary">{{ $sup->payment_terms }}</span></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">কোনো ভেন্ডর পাওয়া যায়নি।</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 3. Goods Received Notes (GRN) -->
                <div class="col-xl-5 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                            <i class="fa fa-clipboard-check text-info me-2"></i> গুডস রিসিভ নোট (GRN)
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle small mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>GRN নং</th>
                                        <th>চালান নং</th>
                                        <th>সরবরাহকারী</th>
                                        <th>কোয়ালিটি স্ট্যাটাস</th>
                                        <th>তারিখ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($grns as $grn)
                                    <tr>
                                        <td class="fw-bold text-primary">{{ $grn->grn_number }}</td>
                                        <td>{{ $grn->challan_number ?? 'N/A' }}</td>
                                        <td>{{ optional($grn->supplier)->supplier_name }}</td>
                                        <td>
                                            @if($grn->quality_status === 'inspected_passed')
                                                <span class="badge bg-success"><i class="fa fa-check me-1"></i> Passed</span>
                                            @else
                                                <span class="badge bg-danger">{{ $grn->quality_status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $grn->received_date }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">কোনো GRN রেকর্ড পাওয়া যায়নি।</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
</div>

<!-- Add Supplier Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="fa fa-user-plus text-primary me-2"></i> নতুন সরবরাহকারী প্রোফাইল</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.erp.procurement.supplier.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">সরবরাহকারী প্রতিষ্ঠানের নাম *</label>
                        <input type="text" name="supplier_name" class="form-control" placeholder="उदा: মেঘনা পেপার অ্যান্ড স্টেশনারি" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">যোগাযোগের ব্যক্তি (Contact Person)</label>
                        <input type="text" name="contact_person" class="form-control" placeholder="নাম">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small">মোবাইল নম্বর *</label>
                            <input type="text" name="phone" class="form-control" placeholder="017...">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small">ইমেইল</label>
                            <input type="email" name="email" class="form-control" placeholder="info@...">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">ঠিকানা</label>
                        <textarea name="address" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small">টিন / ভ্যাট নম্বর</label>
                            <input type="text" name="tax_tin" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small">পেমেন্ট শর্ত</label>
                            <select name="payment_terms" class="form-select">
                                <option value="Net 30">Net 30 Days</option>
                                <option value="Net 15">Net 15 Days</option>
                                <option value="COD">ক্যাশ অন ডেলিভারি (COD)</option>
                                <option value="Advance">অগ্রিম পেমেন্ট (Advance)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Purchase Order Modal -->
<div class="modal fade" id="createPoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="fa fa-shopping-cart text-primary me-2"></i> নতুন পারচেজ অর্ডার (Purchase Order) তৈরি</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.erp.procurement.po.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">সরবরাহকারী নির্বাচন করুন *</label>
                            <select name="supplier_id" class="form-select" required>
                                <option value="">-- সরবরাহকারী সিলেক্ট করুন --</option>
                                @foreach($suppliers as $sp)
                                    <option value="{{ $sp->id }}">{{ $sp->supplier_name }} ({{ $sp->supplier_code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">অর্ডার তারিখ *</label>
                            <input type="date" name="po_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">ডেলিভারি প্রত্যাশিত তারিখ</label>
                            <input type="date" name="delivery_due_date" class="form-control" value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">পেমেন্ট শর্ত</label>
                            <select name="payment_terms" class="form-select">
                                <option value="Net 30">Net 30 Days</option>
                                <option value="Net 15">Net 15 Days</option>
                                <option value="COD">ক্যাশ অন ডেলিভারি (COD)</option>
                                <option value="Advance">অগ্রিম পেমেন্ট (Advance)</option>
                            </select>
                        </div>
                    </div>

                    <h6 class="fw-bold text-dark border-top pt-3 mb-2"><i class="fa fa-list me-1"></i> আইটেম ও দর বিবরণ:</h6>
                    <div id="poItemsContainer">
                        <div class="row g-2 mb-2 po-item-row">
                            <div class="col-6">
                                <select name="item_ids[]" class="form-select" required>
                                    <option value="">-- আইটেম নির্বাচন করুন --</option>
                                    @foreach($items as $it)
                                        <option value="{{ $it->id }}">{{ $it->name_bn ?? $it->name }} (একক দর: ৳{{ $it->purchase_price }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <input type="number" step="1" min="1" name="quantities[]" class="form-control" placeholder="পরিমাণ" value="1" required>
                            </div>
                            <div class="col-3">
                                <input type="number" step="0.01" min="0" name="unit_prices[]" class="form-control" placeholder="একক মূল্য" required>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-xs btn-outline-primary rounded-pill mt-2" onclick="addPoRow()">
                        <i class="fa fa-plus me-1"></i> আরও আইটেম যোগ করুন
                    </button>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">অর্ডার কনফার্ম করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create GRN Modal -->
<div class="modal fade" id="createGrnModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-success"><i class="fa fa-check-square-o me-2"></i> গুডস রিসিভ নোট (GRN) এন্ট্রি</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.erp.procurement.grn.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">পারচেজ অর্ডার (PO) নির্বাচন করুন *</label>
                        <select name="po_id" class="form-select" required>
                            <option value="">-- PO নির্বাচন করুন --</option>
                            @foreach($purchaseOrders as $po)
                                <option value="{{ $po->id }}">{{ $po->po_number }} - {{ optional($po->supplier)->supplier_name }} (৳{{ number_format($po->total_amount) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">গুদাম (যেখানে মালামাল রাখা হবে) *</label>
                        <select name="warehouse_id" class="form-select" required>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name_bn ?? $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small">চালান নম্বর</label>
                            <input type="text" name="challan_number" class="form-control" placeholder="CH-...">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small">গ্রহণের তারিখ *</label>
                            <input type="date" name="received_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">কোয়ালিটি ইন্সপেকশন স্ট্যাটাস *</label>
                        <select name="quality_status" class="form-select" required>
                            <option value="inspected_passed">মান যাচাই সফল ও গৃহীত (Inspected & Passed)</option>
                            <option value="rejected">মান নিম্নমানের হওয়ায় বাতিল (Rejected)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">পরিদর্শন মন্তব্য</label>
                        <textarea name="inspection_notes" class="form-control" rows="2" placeholder="প্যাকেজিং ঠিক আছে..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">পণ্য গ্রহণ ও স্টক আপডেট</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function addPoRow() {
    const container = document.getElementById('poItemsContainer');
    const firstRow = container.querySelector('.po-item-row');
    const clone = firstRow.cloneNode(true);
    clone.querySelector('input[type="number"]').value = 1;
    clone.querySelectorAll('input')[1].value = '';
    container.appendChild(clone);
}
</script>
@endsection

