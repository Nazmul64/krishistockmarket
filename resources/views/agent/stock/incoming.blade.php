@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-truck text-warning me-2"></i>Incoming Stock & Receive Management (আগত পণ্যের চালান গ্রহণ)
                    </h3>
                    <p class="text-muted mb-0">এজেন্ট পয়েন্ট: <strong>{{ $point->name }}</strong> ({{ $point->code }}) | পণ্য গ্রহণ নিশ্চিত করুন ও স্টকে তুলুন</p>
                </div>
                <div>
                    <a href="{{ route('agent.inventory') }}" class="btn btn-outline-success btn-sm">
                        <i class="fa fa-cubes me-1"></i> পয়েন্ট লাইভ স্টক
                    </a>
                    <a href="{{ route('agent.dashboard') }}" class="btn btn-outline-secondary btn-sm ms-1">
                        <i class="fa fa-dashboard me-1"></i> ড্যাশবোর্ড
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

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="card-title fw-bold text-dark mb-0 font-size-15">
                            <i class="fa fa-list text-primary me-2"></i>আগত চালানের তালিকা (Incoming Dispatches)
                        </h5>
                        <form method="GET" action="{{ route('agent.stock.incoming') }}" class="d-flex gap-2">
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">-- All Status (সকল স্ট্যাটাস) --</option>
                                <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>In Transit (পথে রয়েছে)</option>
                                <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Received (গৃহীত হয়েছে)</option>
                                <option value="partially_received" {{ request('status') == 'partially_received' ? 'selected' : '' }}>Partially Received (আংশিক গ্রহণ)</option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted font-size-12 text-uppercase">
                                <tr>
                                    <th class="ps-4">Challan / Dispatch No</th>
                                    <th>Source & Flow</th>
                                    <th>Carrier & Driver</th>
                                    <th>Dispatch Date</th>
                                    <th class="text-center">Total Items</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-end pe-4">Action</th>
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
                                        @if($dsp->supply_flow == 'direct_agent_supply')
                                            <span class="badge bg-success-light text-success font-size-11">Direct Supplier</span>
                                            @if($dsp->supplier)
                                                <div class="text-dark font-size-12 fw-semibold mt-1">{{ $dsp->supplier->company_name }}</div>
                                            @endif
                                        @else
                                            <span class="badge bg-info-light text-info font-size-11">Central Warehouse</span>
                                            <div class="text-muted font-size-11 mt-1">কেন্দ্রীয় ওয়্যারহাউজ</div>
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
                                        <span class="badge bg-light text-dark font-size-12 px-2 py-1">
                                            {{ $dsp->items->count() }} টি পণ্য
                                        </span>
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
                                        @if($dsp->status == 'received')
                                            <button type="button" class="btn btn-outline-success btn-sm py-1 font-size-12" data-bs-toggle="modal" data-bs-target="#viewDetailsModal{{ $dsp->id }}">
                                                <i class="fa fa-check-circle me-1"></i> চালানের বিবরণ
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-warning btn-sm py-1 font-size-12 fw-semibold text-dark shadow-sm" data-bs-toggle="modal" data-bs-target="#receiveModal{{ $dsp->id }}">
                                                <i class="fa fa-download me-1"></i> পণ্য গ্রহণ কনফার্ম করুন
                                            </button>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Modal: Confirm Receive -->
                                <div class="modal fade" id="receiveModal{{ $dsp->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content text-start">
                                            <form action="{{ route('agent.stock.receive', $dsp->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-header bg-warning-light">
                                                    <h5 class="modal-title fw-bold text-dark">
                                                        <i class="fa fa-check-square-o text-warning me-2"></i> চালান নং [{{ $dsp->dispatch_no }}] - পণ্য গ্রহণ নিশ্চিতকরণ
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="alert alert-info py-2 font-size-13 mb-3">
                                                        <i class="fa fa-info-circle me-1"></i> চালানের পণ্যসমূহ গুনে গ্রহণকৃত পরিমাণ (Received Qty) নিশ্চিত করুন। কোনো ঘাটতি বা নষ্ট পণ্য থাকলে তা যথাযথ ঘরে লিখুন।
                                                    </div>

                                                    <div class="row g-3 mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">Receive Date (গ্রহণের তারিখ) <span class="text-danger">*</span></label>
                                                            <input type="date" name="receive_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">Received By (গ্রহণকারীর নাম) <span class="text-danger">*</span></label>
                                                            <input type="text" name="received_by" class="form-control" value="{{ Auth::user()->name }}" required>
                                                        </div>
                                                    </div>

                                                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-2">পণ্যের তালিকা ও পরিমাণ যাচাই</h6>
                                                    <div class="table-responsive mb-3">
                                                        <table class="table table-bordered align-middle font-size-13 mb-0">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>পণ্যের নাম</th>
                                                                    <th class="text-center" style="width: 110px;">প্রেরিত পরিমাণ</th>
                                                                    <th class="text-center" style="width: 120px;">গ্রহণকৃত (Received)</th>
                                                                    <th class="text-center" style="width: 110px;">নষ্ট (Damaged)</th>
                                                                    <th class="text-center" style="width: 110px;">ঘাটতি (Short)</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($dsp->items as $item)
                                                                <tr>
                                                                    <td class="fw-semibold">
                                                                        {{ $item->product_name }} ({{ $item->unit }})
                                                                    </td>
                                                                    <td class="text-center bg-light fw-bold text-primary">
                                                                        {{ $item->sent_quantity }} {{ $item->unit }}
                                                                    </td>
                                                                    <td>
                                                                        <input type="number" step="0.1" name="items[{{ $item->id }}][received_quantity]" 
                                                                               class="form-control form-control-sm text-center fw-bold text-success" 
                                                                               value="{{ $item->sent_quantity }}" min="0" required>
                                                                    </td>
                                                                    <td>
                                                                        <input type="number" step="0.1" name="items[{{ $item->id }}][damaged_quantity]" 
                                                                               class="form-control form-control-sm text-center text-danger" 
                                                                               value="0" min="0">
                                                                    </td>
                                                                    <td>
                                                                        <input type="number" step="0.1" name="items[{{ $item->id }}][short_quantity]" 
                                                                               class="form-control form-control-sm text-center text-muted" 
                                                                               value="0" min="0">
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">Upload Challan / Receipt Copy (চালান/রসিদের ছবি বা পিডিএফ)</label>
                                                            <input type="file" name="receive_attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">Receiving Notes (কোনো বিশেষ মন্তব্য থাকলে)</label>
                                                            <textarea name="notes" rows="2" class="form-control" placeholder="মন্তব্য লিখুন..."></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                                                    <button type="submit" class="btn btn-success fw-semibold">
                                                        <i class="fa fa-check-circle me-1"></i> রিসিভ সম্পন্ন করুন ও স্টকে যোগ করুন
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal: View Details -->
                                <div class="modal fade" id="viewDetailsModal{{ $dsp->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content text-start">
                                            <div class="modal-header bg-light">
                                                <h6 class="modal-title fw-bold text-dark">
                                                    চালান নং: {{ $dsp->dispatch_no }} (গৃহীত)
                                                </h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-0">
                                                <table class="table table-bordered mb-0 font-size-13">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>পণ্য</th>
                                                            <th class="text-end">প্রেরিত</th>
                                                            <th class="text-end">গৃহীত</th>
                                                            <th class="text-end">নষ্ট/ঘাটতি</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($dsp->items as $it)
                                                        <tr>
                                                            <td class="fw-semibold">{{ $it->product_name }}</td>
                                                            <td class="text-end text-primary">{{ $it->sent_quantity }} {{ $it->unit }}</td>
                                                            <td class="text-end text-success fw-bold">{{ $it->received_quantity }} {{ $it->unit }}</td>
                                                            <td class="text-end text-danger">{{ $it->damaged_quantity + $it->short_quantity }} {{ $it->unit }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="p-3 bg-light border-top font-size-12 text-muted">
                                                    <div>রিসিভ তারিখ: <strong>{{ $dsp->received_date }}</strong> | রিসিভকারী: <strong>{{ optional($dsp->receiver)->name ?? 'Agent' }}</strong></div>
                                                    @if($dsp->receive_note)
                                                        <div class="mt-1">মন্তব্য: {{ $dsp->receive_note }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">বন্ধ করুন</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        আপনার এজেন্ট পয়েন্টের জন্য কোনো আগত চালান পাওয়া যায়নি
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
        </section>
    </div>
</div>
@endsection
