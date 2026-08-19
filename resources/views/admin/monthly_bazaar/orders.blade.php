@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title">Monthly Grocery Orders (মাসিক বাজার রিকোয়েস্ট ও ডিস্ট্রিবিউশন)</h4>
                    <p class="text-muted mb-0">এলাকা (Area) ও Agent Point ভিত্তিক রিকোয়েস্ট পর্যালোচনা, পণ্য বরাদ্দ ও কাস্টমার দিক-নির্দেশনা</p>
                </div>
                <div>
                    <a href="{{ route('admin.monthly_bazaar.distribution_reports') }}" class="btn btn-info btn-sm me-2"><i class="fa fa-pie-chart me-1"></i> Area Demand & Distribution Report</a>
                    <a href="{{ route('admin.monthly_bazaar.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-list me-1"></i> প্যাকেজ তালিকা</a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <!-- Area & Agent Summary Metrics (Section 3 Requirement) -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card bg-light border shadow-sm">
                        <div class="card-body py-3">
                            <h6 class="fw-bold text-success mb-2"><i class="fa fa-map-marker me-1"></i> এলাকা ভিত্তিক চাহিদা সংক্ষেপ (Area-wise Demand Summary):</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @forelse($areaSummaries as $areaName => $sum)
                                    <div class="border rounded p-2 bg-white flex-grow-1 shadow-2n" style="min-width: 180px;">
                                        <small class="text-muted d-block fw-bold">{{ $sum['area'] }}</small>
                                        <div class="d-flex justify-content-between align-items-center mt-1">
                                            <span class="badge bg-primary">{{ $sum['total_requests'] }} Requests</span>
                                            <span class="badge bg-success">{{ $sum['total_quantity'] }} Pkts Demand</span>
                                        </div>
                                    </div>
                                @empty
                                    <span class="text-muted small">কোনো এলাকার তথ্য পাওয়া যায়নি।</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card border shadow-sm">
                        <div class="card-body py-2">
                            <form method="GET" action="{{ route('admin.monthly_bazaar.orders') }}" class="row g-2 align-items-center">
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold mb-0">এলাকা দ্বারা ফিল্টার:</label>
                                    <select name="request_area" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="">-- সকল এলাকা (All Areas) --</option>
                                        @foreach($areas as $ar)
                                            <option value="{{ $ar }}" {{ request('request_area') == $ar ? 'selected' : '' }}>{{ $ar }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold mb-0">Agent Point দ্বারা ফিল্টার:</label>
                                    <select name="agent_point" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="">-- সকল Agent Point --</option>
                                        @foreach($agentPoints as $ap)
                                            <option value="{{ $ap }}" {{ request('agent_point') == $ap ? 'selected' : '' }}>{{ $ap }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold mb-0">স্ট্যাটাস:</label>
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="">-- সকল স্ট্যাটাস --</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved / Allocated</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-3 text-end pt-3">
                                    <a href="{{ route('admin.monthly_bazaar.orders') }}" class="btn btn-secondary btn-sm"><i class="fa fa-refresh me-1"></i> রিসেট</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h3 class="box-title text-dark"><i class="fa fa-shopping-basket me-2 text-success"></i> মাসিক বাজার রিকোয়েস্টসমূহ ({{ count($orders) }})</h3>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>SN.</th>
                                            <th>গ্রাহকের আইডি ও কার্ড নাম্বার</th>
                                            <th>প্যাকেজ ও পরিমাণ</th>
                                            <th>রিকোয়েস্ট এরিয়া ও Agent Point</th>
                                            <th class="text-center">বরাদ্দ (Allocated Qty)</th>
                                            <th class="text-center">পেমেন্ট মেথড</th>
                                            <th class="text-center">স্ট্যাটাস ও কালেকশন নোট</th>
                                            <th class="text-center">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($orders as $key => $item)
                                            @php
                                                $cardNumber = GetUserCardNumber($item->user_id);
                                            @endphp
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>
                                                    <span class="badge bg-primary mb-1">User ID: #{{ $item->user_id }}</span><br>
                                                    <span class="badge bg-dark mb-1" style="font-family: monospace; font-size: 11px; letter-spacing: 1px;"><i class="fa fa-credit-card me-1"></i>{{ $cardNumber }}</span><br>
                                                    <strong class="text-dark">{{ $item->user->name ?? 'N/A' }}</strong><br>
                                                    <small class="text-muted"><i class="fa fa-phone me-1"></i>{{ $item->user->phone ?? $item->user->username ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <strong class="text-dark">{{ $item->package_title }}</strong><br>
                                                    <span class="fw-bold text-success">৳{{ number_format($item->total_price, 2) }}</span>
                                                    <small class="text-muted">({{ $item->quantity }} টি)</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary mb-1"><i class="fa fa-map-marker me-1"></i> {{ $item->request_area ?? 'সাধারণ' }}</span><br>
                                                    <span class="badge bg-dark"><i class="fa fa-building me-1"></i> {{ $item->agent_point ?? 'Central Point' }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-success fs-14">{{ $item->allocated_quantity }} / {{ $item->quantity }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-info mb-1">{{ $item->payment_method }}</span><br>
                                                    @if($item->pay_from_number)
                                                        <small>From: <strong>{{ $item->pay_from_number }}</strong></small><br>
                                                    @endif
                                                    @if($item->trx_number)
                                                        <small>Trx: <code class="text-dark fw-bold">{{ $item->trx_number }}</code></small>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($item->status == 'pending')
                                                        <span class="badge bg-warning text-dark py-1 px-2 mb-1"><i class="fa fa-clock-o me-1"></i>Pending</span>
                                                    @elseif($item->status == 'approved')
                                                        <span class="badge bg-success py-1 px-2 mb-1"><i class="fa fa-check-circle me-1"></i>Approved</span>
                                                    @else
                                                        <span class="badge bg-danger py-1 px-2 mb-1"><i class="fa fa-times-circle me-1"></i>Rejected</span>
                                                    @endif
                                                    <br>
                                                    <small class="text-muted d-block text-truncate" style="max-width: 180px;" title="{{ $item->collection_note }}">
                                                        {{ $item->collection_note ?? 'কোথা থেকে সংগ্রহ করতে হবে তথ্য দেওয়া হয়নি' }}
                                                    </small>
                                                </td>
                                                <td class="text-center">
                                                    @if($item->status == 'pending')
                                                        <a href="{{ route('admin.monthly_bazaar.order.approve', $item->id) }}"
                                                            class="waves-effect waves-light btn btn-success btn-xs mb-1"><i class="fa fa-check me-1"></i>Approve</a>
                                                        <a href="{{ route('admin.monthly_bazaar.order.reject', $item->id) }}"
                                                            class="waves-effect waves-light btn btn-danger btn-xs mb-1" onclick="return confirm('রিকোয়েস্টটি রিজেক্ট করতে চান?')"><i class="fa fa-times me-1"></i>Reject</a>
                                                    @endif
                                                    <button type="button" class="btn btn-primary btn-xs mb-1 edit-alloc-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#allocModal"
                                                            data-id="{{ $item->id }}"
                                                            data-area="{{ $item->request_area }}"
                                                            data-agent="{{ $item->agent_point }}"
                                                            data-qty="{{ $item->quantity }}"
                                                            data-alloc="{{ $item->allocated_quantity }}"
                                                            data-status="{{ $item->distribution_status }}"
                                                            data-note="{{ $item->collection_note }}">
                                                        <i class="fa fa-pencil me-1"></i> বরাদ্দ ও নির্দেশ
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">কোনো মাসিক বাজার রিকোয়েস্ট পাওয়া যায়নি।</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Allocation Modal -->
<div class="modal fade" id="allocModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="allocForm" method="POST" action="">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="fa fa-boxes me-2"></i> পণ্য বরাদ্দ ও কালেকশন পয়েন্ট আপডেট</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">পণ্য চাহিদার পরিমাণ (Requested Qty):</label>
                        <input type="text" id="modal_req_qty" class="form-control bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">বরাদ্দকৃত পণ্যের পরিমাণ (Allocated Quantity) <span class="text-danger">*</span></label>
                        <input type="number" name="allocated_quantity" id="modal_alloc_qty" class="form-control" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">ডিস্ট্রিবিউশন স্ট্যাটাস (Distribution Status) <span class="text-danger">*</span></label>
                        <select name="distribution_status" id="modal_dist_status" class="form-select" required>
                            <option value="pending">পেন্ডিং (Pending Allocation)</option>
                            <option value="allocated">বরাদ্দকৃত (Allocated)</option>
                            <option value="ready_for_collection">সংগ্রহের জন্য প্রস্তুত (Ready for Collection)</option>
                            <option value="distributed">সম্পূর্ণ বিতরণকৃত (Distributed)</option>
                            <option value="rejected">বাতিলকৃত (Rejected)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">কার্ডহোল্ডারের জন্য সংগ্রহের নির্দেশনা (Collection Instruction Note)</label>
                        <textarea name="collection_note" id="modal_coll_note" class="form-control" rows="3" placeholder="যেমন: নওগাঁ Agent Point থেকে মাসিক বাজার সংগ্রহ করুন।"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-save me-1"></i> আপডেট করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('.edit-alloc-btn').on('click', function() {
            var id = $(this).data('id');
            var qty = $(this).data('qty');
            var alloc = $(this).data('alloc');
            var status = $(this).data('status');
            var note = $(this).data('note');
            var agent = $(this).data('agent');
            var area = $(this).data('area');

            $('#modal_req_qty').val(qty + ' টি');
            $('#modal_alloc_qty').val(alloc || qty);
            $('#modal_dist_status').val(status || 'allocated');
            
            if(!note) {
                note = 'আপনি যদি ' + (agent || 'Agent Point') + '-এর আওতাভুক্ত হন, তবে আপনার মাসিক বাজার ' + (agent || 'Agent Point') + ' (এলাকা: ' + (area || 'আপনার এলাকা') + ') থেকে সংগ্রহ করতে হবে।';
            }
            $('#modal_coll_note').val(note);

            var actionUrl = "{{ url('admin/monthly-bazaar/order/update-allocation') }}/" + id;
            $('#allocForm').attr('action', actionUrl);
        });
    });
</script>
@endsection
