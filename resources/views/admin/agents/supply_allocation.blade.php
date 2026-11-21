@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-truck text-warning me-2"></i>Product Distribution & Dispatch Management
                    </h3>
                    <p class="text-muted mb-0">সাপ্লায়ার অথবা কেন্দ্রীয় ওয়্যারহাউজ থেকে এজেন্ট পয়েন্টে পণ্য বরাদ্দ ও চালান ব্যবস্থাপনা</p>
                </div>
                <div>
                    <button type="button" class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#newDispatchModal">
                        <i class="fa fa-plus-circle me-1"></i> + New Dispatch (নতুন চালান ইস্যু করুন)
                    </button>
                    <a href="{{ route('admin.agents.index') }}" class="btn btn-outline-secondary btn-sm ms-1">
                        <i class="fa fa-arrow-left me-1"></i> এজেন্ট পয়েন্ট তালিকা
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Supply Flow Guide Card -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white border-start border-4 border-info h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-info-light p-3 text-info">
                                    <i class="fa fa-warehouse fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Option 1: Central Warehouse Supply Flow</h6>
                                    <p class="text-muted font-size-13 mb-0">
                                        সাপ্লায়ার &rarr; কেন্দ্রীয় ওয়্যারহাউজ &rarr; এজেন্ট পয়েন্ট &rarr; কার্ডহোল্ডার
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white border-start border-4 border-success h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-success-light p-3 text-success">
                                    <i class="fa fa-truck fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Option 2: Direct Agent Supply Flow</h6>
                                    <p class="text-muted font-size-13 mb-0">
                                        সাপ্লায়ার সরাসরি &rarr; নির্ধারিত এজেন্ট পয়েন্ট &rarr; কার্ডহোল্ডার
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dispatches List -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title fw-bold text-dark mb-0">
                        <i class="fa fa-list-alt text-primary me-2"></i> প্রেরিত চালানের তালিকা (Dispatches to Agent Points)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted font-size-12 text-uppercase">
                                <tr>
                                    <th class="ps-4">Challan / Dispatch No</th>
                                    <th>Destination Agent Point</th>
                                    <th>Supply Flow</th>
                                    <th>Vehicle & Driver</th>
                                    <th>Dispatch Date</th>
                                    <th class="text-center">Items & Qty</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-end pe-4">Receive Info</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dispatches as $dsp)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark font-size-14">{{ $dsp->dispatch_no }}</div>
                                        <div class="text-muted font-size-11">{{ $dsp->distribution_month }} {{ $dsp->distribution_year }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-primary">{{ optional($dsp->agentPoint)->name ?? 'Unknown Point' }}</div>
                                        <div class="text-muted font-size-12">{{ optional($dsp->agentPoint)->area }}</div>
                                    </td>
                                    <td>
                                        @if($dsp->supply_flow == 'direct_agent_supply')
                                            <span class="badge bg-success-light text-success font-size-11">
                                                <i class="fa fa-arrow-circle-right me-1"></i> Direct Supplier
                                            </span>
                                            @if($dsp->supplier)
                                                <div class="text-muted font-size-11 mt-1">{{ $dsp->supplier->company_name }}</div>
                                            @endif
                                        @else
                                            <span class="badge bg-info-light text-info font-size-11">
                                                <i class="fa fa-building me-1"></i> Central Warehouse
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-dark font-size-13">{{ $dsp->vehicle_info ?? 'Standard Carrier' }}</div>
                                        @if($dsp->driver_info)
                                            <div class="text-muted font-size-11">
                                                <i class="fa fa-user me-1"></i> {{ $dsp->driver_info }} ({{ $dsp->driver_mobile ?? '' }})
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-dark">{{ $dsp->dispatch_date ? date('d M, Y', strtotime($dsp->dispatch_date)) : 'N/A' }}</div>
                                        <div class="text-muted font-size-11">By: {{ optional($dsp->dispatcher)->name ?? 'Admin' }}</div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-secondary btn-sm py-0 font-size-11" 
                                                data-bs-toggle="modal" data-bs-target="#viewItemsModal{{ $dsp->id }}">
                                            <i class="fa fa-eye me-1"></i> {{ $dsp->items->count() }} আইটেম দেখুন
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        @if($dsp->status == 'received')
                                            <span class="badge bg-success px-3 py-1 font-size-11 rounded-pill">Received</span>
                                        @elseif($dsp->status == 'partially_received')
                                            <span class="badge bg-warning text-dark px-3 py-1 font-size-11 rounded-pill">Partial</span>
                                        @else
                                            <span class="badge bg-primary px-3 py-1 font-size-11 rounded-pill">In Transit</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($dsp->received_date)
                                            <div class="text-success font-size-12 fw-semibold">
                                                <i class="fa fa-check-circle me-1"></i> {{ date('d M, Y', strtotime($dsp->received_date)) }}
                                            </div>
                                            <div class="text-muted font-size-11">By: {{ optional($dsp->receiver)->name ?? 'Agent' }}</div>
                                        @else
                                            <span class="text-muted font-size-12">পণ্য গ্রহণের অপেক্ষায়</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- View Items Modal -->
                                <div class="modal fade" id="viewItemsModal{{ $dsp->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-light">
                                                <h6 class="modal-title fw-bold">
                                                    চালান নং: {{ $dsp->dispatch_no }} - পণ্যের বিবরণ
                                                </h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-0">
                                                <table class="table table-bordered mb-0">
                                                    <thead class="table-light font-size-12">
                                                        <tr>
                                                            <th>পণ্য (Product)</th>
                                                            <th class="text-end">প্রেরিত (Sent)</th>
                                                            <th class="text-end">গৃহীত (Received)</th>
                                                            <th class="text-end">ঘাটতি (Short)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($dsp->items as $item)
                                                        <tr>
                                                            <td class="fw-semibold">{{ $item->product_name }}</td>
                                                            <td class="text-end text-primary fw-bold">{{ $item->sent_quantity }} {{ $item->unit }}</td>
                                                            <td class="text-end text-success fw-bold">{{ $item->received_quantity }} {{ $item->unit }}</td>
                                                            <td class="text-end text-danger">{{ $item->short_quantity }} {{ $item->unit }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">বন্ধ করুন</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        কোনো প্রেরিত চালানের তথ্য পাওয়া যায়নি
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($dispatches->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <span class="text-muted font-size-13">Showing {{ $dispatches->firstItem() }} to {{ $dispatches->lastItem() }} of {{ $dispatches->total() }} dispatches</span>
                        {{ $dispatches->links() }}
                    </div>
                </div>
                @endif
            </div>

            <!-- New Dispatch Modal -->
            <div class="modal fade" id="newDispatchModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('admin.agents.supply_allocation.store') }}" method="POST">
                            @csrf
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title fw-bold text-white">
                                    <i class="fa fa-truck me-2 text-white"></i> নতুন চালান ও পণ্য বরাদ্দ (Create Dispatch)
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Destination Agent Point (গন্তব্য এজেন্ট পয়েন্ট) <span class="text-danger">*</span></label>
                                        <select name="agent_point_id" class="form-select" required>
                                            <option value="">-- এজেন্ট পয়েন্ট সিলেক্ট করুন --</option>
                                            @foreach($agentPoints as $point)
                                                <option value="{{ $point->id }}">{{ $point->name }} - {{ $point->area }} ({{ $point->district }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Supply Flow (সরবরাহের মাধ্যম) <span class="text-danger">*</span></label>
                                        <select name="supply_flow" id="supplyFlowSelect" class="form-select" required>
                                            <option value="central_warehouse">Option 1: Central Warehouse (কেন্দ্রীয় ওয়্যারহাউজ)</option>
                                            <option value="direct_agent_supply">Option 2: Direct Supplier Supply (সাপ্লায়ার সরাসরি পয়েন্টে)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6" id="supplierSelectDiv" style="display: none;">
                                        <label class="form-label fw-semibold">Select Supplier (সাপ্লায়ার)</label>
                                        <select name="supplier_id" class="form-select">
                                            <option value="">-- সাপ্লায়ার সিলেক্ট করুন --</option>
                                            @foreach($suppliers as $sup)
                                                <option value="{{ $sup->id }}">{{ $sup->company_name }} ({{ $sup->contact_person }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Vehicle Info (গাড়ির নম্বর/তথ্য)</label>
                                        <input type="text" name="vehicle_info" class="form-control" placeholder="যেমন: ঢাকা মেট্রো-ন ১২-৩৪৫৬">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Driver Info (ড্রাইভারের নাম)</label>
                                        <input type="text" name="driver_info" class="form-control" placeholder="ড্রাইভারের নাম">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Driver Mobile (ড্রাইভারের মোবাইল)</label>
                                        <input type="text" name="driver_mobile" class="form-control" placeholder="017XXXXXXXX">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Dispatch Note (চালানের মন্তব্য)</label>
                                        <textarea name="dispatch_note" rows="2" class="form-control" placeholder="চালানের বিশেষ কোনো নির্দেশনা..."></textarea>
                                    </div>
                                </div>

                                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="fa fa-cubes text-primary me-1"></i> বরাদ্দের পণ্যের তালিকা (Products to Dispatch)
                                </h6>

                                <div id="itemsContainer">
                                    <div class="row g-2 mb-2 item-row">
                                        <div class="col-md-6">
                                            <input type="text" name="items[0][product_name]" class="form-control" placeholder="পণ্যের নাম (যেমন: মিনিকেট চাল)" required>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" step="0.1" name="items[0][quantity]" class="form-control" placeholder="পরিমাণ" required>
                                        </div>
                                        <div class="col-md-2">
                                            <select name="items[0][unit]" class="form-select">
                                                <option value="kg">kg (কেজি)</option>
                                                <option value="liter">liter (লিটার)</option>
                                                <option value="pcs">pcs (পিস)</option>
                                                <option value="packet">packet (প্যাকেট)</option>
                                                <option value="bag">bag (বস্তা)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1 text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-row-btn" disabled>
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" id="addRowBtn" class="btn btn-outline-primary btn-sm mt-2">
                                    <i class="fa fa-plus me-1"></i> আরও পণ্য যোগ করুন
                                </button>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                                <button type="submit" class="btn btn-primary fw-semibold">
                                    <i class="fa fa-paper-plane me-1"></i> চালান ইস্যু ও প্রেরণ করুন
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const supplyFlowSelect = document.getElementById('supplyFlowSelect');
    const supplierDiv = document.getElementById('supplierSelectDiv');

    if (supplyFlowSelect) {
        supplyFlowSelect.addEventListener('change', function () {
            if (this.value === 'direct_agent_supply') {
                supplierDiv.style.display = 'block';
            } else {
                supplierDiv.style.display = 'none';
            }
        });
    }

    let rowIndex = 1;
    const addRowBtn = document.getElementById('addRowBtn');
    const itemsContainer = document.getElementById('itemsContainer');

    if (addRowBtn && itemsContainer) {
        addRowBtn.addEventListener('click', function () {
            const rowHtml = `
                <div class="row g-2 mb-2 item-row">
                    <div class="col-md-6">
                        <input type="text" name="items[${rowIndex}][product_name]" class="form-control" placeholder="পণ্যের নাম (যেমন: সোয়াবিন তেল)" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.1" name="items[${rowIndex}][quantity]" class="form-control" placeholder="পরিমাণ" required>
                    </div>
                    <div class="col-md-2">
                        <select name="items[${rowIndex}][unit]" class="form-select">
                            <option value="kg">kg (কেজি)</option>
                            <option value="liter">liter (লিটার)</option>
                            <option value="pcs">pcs (পিস)</option>
                            <option value="packet">packet (প্যাকেট)</option>
                            <option value="bag">bag (বস্তা)</option>
                        </select>
                    </div>
                    <div class="col-md-1 text-center">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-row-btn">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            itemsContainer.insertAdjacentHTML('beforeend', rowHtml);
            rowIndex++;
        });

        itemsContainer.addEventListener('click', function (e) {
            if (e.target.closest('.remove-row-btn')) {
                const row = e.target.closest('.item-row');
                if (document.querySelectorAll('.item-row').length > 1) {
                    row.remove();
                }
            }
        });
    }
});
</script>
@endsection
