@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-shopping-basket text-primary me-2"></i>Cardholder Monthly Market Orders (কার্ডহোল্ডার অর্ডার ব্যবস্থাপনা)
                    </h3>
                    <p class="text-muted mb-0">এজেন্ট পয়েন্ট: <strong>{{ $point->name }}</strong> ({{ $point->code }}) | অর্ডার যাচাই, প্রস্তুতকরণ ও পণ্য বিতরণ</p>
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
                        <form method="GET" action="{{ route('agent.orders') }}" class="d-flex gap-2 flex-grow-1" style="max-width: 600px;">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa fa-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control bg-light border-start-0" 
                                       placeholder="Search Order No, Card No, Customer Name, Phone..." 
                                       value="{{ request('search') }}">
                            </div>
                            <select name="status" class="form-select bg-light" style="max-width: 180px;" onchange="this.form.submit()">
                                <option value="">-- All Status --</option>
                                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Pending / Submitted</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="ready_for_collection" {{ request('status') == 'ready_for_collection' ? 'selected' : '' }}>Ready for Collection</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Delivered / Completed</option>
                            </select>
                            <button type="submit" class="btn btn-secondary btn-sm"><i class="fa fa-filter"></i></button>
                        </form>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted font-size-12 text-uppercase">
                                <tr>
                                    <th class="ps-4">Order ID & Date</th>
                                    <th>Cardholder Info</th>
                                    <th>Requested Products & Stock Check</th>
                                    <th>Estimated Value</th>
                                    <th class="text-center">Order Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-primary font-size-14">{{ $order->request_no }}</div>
                                        <div class="text-muted font-size-11">
                                            {{ $order->month }} {{ $order->year }} | {{ date('d M, Y', strtotime($order->request_date ?? $order->created_at)) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark font-size-13">
                                            <span class="badge bg-primary-light text-primary me-1">{{ $order->card_number }}</span>
                                            {{ optional($order->user)->name ?? 'Customer' }}
                                        </div>
                                        <div class="text-muted font-size-11 mt-1">
                                            <i class="fa fa-phone me-1 text-success"></i> {{ optional($order->user)->phone ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            @foreach($order->items as $item)
                                                @php
                                                    $avail = $stockMap[$item->product_name] ?? 0;
                                                    $hasStock = ($avail >= $item->quantity);
                                                @endphp
                                                <div class="font-size-12 d-flex align-items-center gap-1">
                                                    <span class="fw-semibold text-dark">{{ $item->product_name }}:</span>
                                                    <span>{{ $item->quantity }} {{ $item->unit }}</span>
                                                    @if($order->status != 'completed')
                                                        @if($hasStock)
                                                            <span class="badge bg-success-light text-success font-size-10 py-0 ms-1" title="Current Point Stock: {{ $avail }}">
                                                                <i class="fa fa-check"></i> Stock Available
                                                            </span>
                                                        @else
                                                            <span class="badge bg-danger-light text-danger font-size-10 py-0 ms-1" title="Current Point Stock: {{ $avail }}">
                                                                <i class="fa fa-warning"></i> Stock Short (মজুত: {{ $avail }})
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark font-size-14">৳{{ number_format($order->total_estimated_amount, 2) }}</div>
                                        <div class="text-muted font-size-11">{{ $order->items->count() }} টি আইটেম</div>
                                    </td>
                                    <td class="text-center">
                                        @if($order->status == 'completed')
                                            <span class="badge bg-success px-3 py-1 font-size-11 rounded-pill">
                                                <i class="fa fa-check-circle me-1"></i> Delivered
                                            </span>
                                        @elseif($order->status == 'ready_for_collection')
                                            <span class="badge text-white px-3 py-1 font-size-11 rounded-pill" style="background-color: #6f42c1;">
                                                <i class="fa fa-bell-o me-1"></i> Ready for Collection
                                            </span>
                                        @elseif($order->status == 'approved' || $order->status == 'processing')
                                            <span class="badge bg-primary px-3 py-1 font-size-11 rounded-pill">
                                                <i class="fa fa-cogs me-1"></i> Processing
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark px-3 py-1 font-size-11 rounded-pill">
                                                <i class="fa fa-clock-o me-1"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($order->status == 'completed')
                                            <span class="text-success font-size-12 fw-semibold">
                                                <i class="fa fa-check-circle"></i> সরবরাহ সম্পন্ন
                                            </span>
                                        @elseif($order->status == 'ready_for_collection')
                                            <button type="button" class="btn btn-success btn-sm font-size-12 fw-semibold shadow-sm" 
                                                    data-bs-toggle="modal" data-bs-target="#deliveryModal{{ $order->id }}">
                                                <i class="fa fa-handshake-o me-1"></i> পণ্য ডেলিভারি করুন
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-outline-primary btn-sm font-size-12" 
                                                    data-bs-toggle="modal" data-bs-target="#processOrderModal{{ $order->id }}">
                                                <i class="fa fa-check me-1"></i> প্রসেস / প্রস্তুত করুন
                                            </button>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Modal: Process Order (Approve or Ready for Collection) -->
                                <div class="modal fade" id="processOrderModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content text-start">
                                            <form action="{{ route('agent.orders.process', $order->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header bg-primary-light">
                                                    <h5 class="modal-title fw-bold text-dark">
                                                        <i class="fa fa-cogs text-primary me-2"></i> অর্ডার প্রস্তুত ও নোটিফিকেশন: {{ $order->request_no }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Select Action / Status (স্ট্যাটাস নির্ধারণ) <span class="text-danger">*</span></label>
                                                        <select name="status" class="form-select" required>
                                                            <option value="approved" {{ $order->status == 'approved' ? 'selected' : '' }}>১. Approve (অর্ডার অনুমোদন করুন)</option>
                                                            <option value="ready_for_collection" selected>২. Ready for Collection (পণ্য সংগ্রহে প্রস্তুত - গ্রাহককে জানান)</option>
                                                            <option value="processing">৩. In Processing (প্রস্তুত চলছে)</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Collection Instruction (গ্রাহকের জন্য নির্দেশনা)</label>
                                                        <textarea name="collection_instruction" rows="2" class="form-control" 
                                                                  placeholder="আপনার মাসিক বাজার প্রস্তুত রয়েছে। অনুগ্রহ করে {{ $point->name }} পয়েন্ট থেকে কার্ড দেখিয়ে আপনার পণ্য সংগ্রহ করুন।"></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Internal Note (অভ্যন্তরীণ নোট)</label>
                                                        <input type="text" name="admin_note" class="form-control" placeholder="কোনো বিশেষ মন্তব্য থাকলে লিখুন">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                                                    <button type="submit" class="btn btn-primary fw-semibold">আপডেট করুন</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal: Confirm Delivery to Cardholder -->
                                <div class="modal fade" id="deliveryModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content text-start">
                                            <form action="{{ route('agent.orders.delivery', $order->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title fw-bold text-white">
                                                        <i class="fa fa-handshake-o me-2 text-white"></i> কার্ডহোল্ডারকে পণ্য ডেলিভারি কনফার্মেশন
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="alert alert-success py-2 font-size-13 mb-3">
                                                        <i class="fa fa-check-circle me-1"></i> কার্ডহোল্ডার উপস্থিত হয়েছেন। পণ্য বুঝিয়ে দিয়ে ডেলিভারি কনফার্ম করুন। কনফার্ম করার সাথে সাথে লোকাল স্টক স্বয়ংক্রিয়ভাবে কর্তন হবে।
                                                    </div>

                                                    <div class="bg-light p-3 rounded-3 mb-3 font-size-13">
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <span>অর্ডার নং:</span> <strong>{{ $order->request_no }}</strong>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <span>কার্ড নম্বর:</span> <strong class="text-primary">{{ $order->card_number }}</strong>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <span>গ্রাহকের নাম:</span> <strong>{{ optional($order->user)->name ?? 'Customer' }} ({{ optional($order->user)->phone ?? '' }})</strong>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Verification Method (ভেরিফিকেশন মাধ্যম) <span class="text-danger">*</span></label>
                                                        <select name="verification_method" class="form-select" required>
                                                            <option value="physical_card">শারীরিক কার্ড প্রদর্শন (Physical Card Verified)</option>
                                                            <option value="otp_sms">মোবাইল নম্বর ও এসএমএস (Phone / SMS)</option>
                                                            <option value="cardholder_signature">গ্রাহকের স্বাক্ষর (Cardholder Signature)</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Collected By (পণ্য গ্রহণকারী ব্যক্তির নাম) <span class="text-danger">*</span></label>
                                                        <input type="text" name="collected_by" class="form-control" 
                                                               value="{{ optional($order->user)->name ?? '' }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Remarks (মন্তব্য)</label>
                                                        <input type="text" name="remarks" class="form-control" placeholder="যেমন: সকল পণ্য সঠিক পরিমাণে বুঝিয়া পাইলাম">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                                                    <button type="submit" class="btn btn-success fw-semibold">
                                                        <i class="fa fa-check-circle me-1"></i> ডেলিভারি নিশ্চিত করুন ও স্টক সমন্বয় করুন
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        আপনার এজেন্ট পয়েন্টের জন্য কোনো কার্ডহোল্ডার অর্ডার পাওয়া যায়নি
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($orders->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <span class="text-muted font-size-13">Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders</span>
                        {{ $orders->links() }}
                    </div>
                </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
