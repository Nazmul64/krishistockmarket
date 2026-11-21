@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-dashboard text-primary me-2"></i>Agent Point Portal: {{ $point->name }}
                        <span class="badge bg-primary-light text-primary font-size-13 ms-2">{{ $point->code }}</span>
                    </h3>
                    <p class="text-muted mb-0">দায়িত্বপ্রাপ্ত এজেন্ট: <strong>{{ $point->agent_name }}</strong> | এলাকা: {{ $point->area }} ({{ $point->district }})</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('agent.stock.incoming') }}" class="btn btn-warning btn-sm text-dark fw-semibold shadow-sm">
                        <i class="fa fa-truck me-1"></i> আগত চালান গ্রহণ
                    </a>
                    <a href="{{ route('agent.orders') }}" class="btn btn-primary btn-sm fw-semibold shadow-sm">
                        <i class="fa fa-shopping-basket me-1"></i> কার্ডহোল্ডার অর্ডার
                    </a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <!-- Alert Messages -->
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

            <!-- 9 Core KPI Metrics (SRD Section 2) -->
            <div class="row g-3 mb-4">
                <!-- KPI 1: Total Assigned Card Holder -->
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                        <div class="card-body d-flex align-items-center justify-content-between p-3">
                            <div>
                                <span class="text-muted text-uppercase fw-semibold font-size-12">1. Total Assigned Cardholders</span>
                                <h3 class="fw-bold mb-0 text-dark mt-1">{{ number_format($totalCardHolders) }}</h3>
                                <small class="text-muted">নির্ধারিত কার্ডহোল্ডার গ্রাহক</small>
                            </div>
                            <div class="rounded-circle bg-primary-light p-3 text-primary">
                                <i class="fa fa-id-card fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI 2: Total Monthly Order -->
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                        <div class="card-body d-flex align-items-center justify-content-between p-3">
                            <div>
                                <span class="text-muted text-uppercase fw-semibold font-size-12">2. Total Monthly Orders</span>
                                <h3 class="fw-bold mb-0 text-info mt-1">{{ number_format($totalOrders) }}</h3>
                                <small class="text-muted">মোট প্রাপ্ত বাজার অর্ডার</small>
                            </div>
                            <div class="rounded-circle bg-info-light p-3 text-info">
                                <i class="fa fa-shopping-cart fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI 3: Total Pending Order -->
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                        <div class="card-body d-flex align-items-center justify-content-between p-3">
                            <div>
                                <span class="text-muted text-uppercase fw-semibold font-size-12">3. Total Pending Orders</span>
                                <h3 class="fw-bold mb-0 text-warning mt-1">{{ number_format($pendingOrders) }}</h3>
                                <small class="text-warning fw-semibold">যাচাই ও অনুমোদনের অপেক্ষায়</small>
                            </div>
                            <div class="rounded-circle bg-warning-light p-3 text-warning">
                                <i class="fa fa-clock-o fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI 4: Total Approved Order -->
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                        <div class="card-body d-flex align-items-center justify-content-between p-3">
                            <div>
                                <span class="text-muted text-uppercase fw-semibold font-size-12">4. Total Approved Orders</span>
                                <h3 class="fw-bold mb-0 text-success mt-1">{{ number_format($approvedOrders) }}</h3>
                                <small class="text-muted">অনুমোদিত ও প্রস্তুতকৃত</small>
                            </div>
                            <div class="rounded-circle bg-success-light p-3 text-success">
                                <i class="fa fa-check-square-o fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI 5: Total Received Product -->
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                        <div class="card-body d-flex align-items-center justify-content-between p-3">
                            <div>
                                <span class="text-muted text-uppercase fw-semibold font-size-12">5. Total Received Stock</span>
                                <h3 class="fw-bold mb-0 text-primary mt-1">{{ number_format($totalReceivedStock, 1) }}</h3>
                                <small class="text-muted">পণ্য গ্রহণের মোট পরিমাণ (একক/কেজি)</small>
                            </div>
                            <div class="rounded-circle bg-primary-light p-3 text-primary">
                                <i class="fa fa-download fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI 6: Total Available Stock -->
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                        <div class="card-body d-flex align-items-center justify-content-between p-3">
                            <div>
                                <span class="text-muted text-uppercase fw-semibold font-size-12">6. Total Available Stock</span>
                                <h3 class="fw-bold mb-0 text-success mt-1">{{ number_format($availableStock, 1) }}</h3>
                                <small class="text-success fw-semibold">বর্তমানে বিতরণের জন্য প্রস্তুত</small>
                            </div>
                            <div class="rounded-circle bg-success-light p-3 text-success">
                                <i class="fa fa-cubes fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI 7: Total Distributed Product -->
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                        <div class="card-body d-flex align-items-center justify-content-between p-3">
                            <div>
                                <span class="text-muted text-uppercase fw-semibold font-size-12">7. Total Distributed Product</span>
                                <h3 class="fw-bold mb-0 text-danger mt-1">{{ number_format($distributedStock, 1) }}</h3>
                                <small class="text-muted">কার্ডহোল্ডারদের বিতরণকৃত</small>
                            </div>
                            <div class="rounded-circle bg-danger-light p-3 text-danger">
                                <i class="fa fa-share-square-o fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI 8: Remaining Stock -->
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                        <div class="card-body d-flex align-items-center justify-content-between p-3">
                            <div>
                                <span class="text-muted text-uppercase fw-semibold font-size-12">8. Remaining Stock (অবশিষ্ট মজুত)</span>
                                <h3 class="fw-bold mb-0 text-dark mt-1">{{ number_format($remainingStock, 1) }}</h3>
                                <small class="text-muted">পয়েন্টে বিদ্যমান অবশিষ্টাংশ</small>
                            </div>
                            <div class="rounded-circle bg-light p-3 text-secondary">
                                <i class="fa fa-archive fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI 9: Pending Delivery -->
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                        <div class="card-body d-flex align-items-center justify-content-between p-3">
                            <div>
                                <span class="text-muted text-uppercase fw-semibold font-size-12">9. Pending Delivery</span>
                                <h3 class="fw-bold mb-0 text-purple mt-1" style="color: #6f42c1;">{{ number_format($pendingDelivery) }}</h3>
                                <small class="text-muted">পণ্য সংগ্রহে প্রস্তুত (ডেলিভারির অপেক্ষায়)</small>
                            </div>
                            <div class="rounded-circle p-3" style="background-color: #f3ebfc; color: #6f42c1;">
                                <i class="fa fa-handshake-o fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Main Data Panels: Recent Orders & Recent Stock Receives -->
            <div class="row g-3">
                <!-- Panel 1: Recent Cardholder Orders -->
                <div class="col-xl-7">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bold text-dark mb-0 font-size-15">
                                <i class="fa fa-shopping-basket text-primary me-2"></i>সাম্প্রতিক কার্ডহোল্ডার অর্ডারসমূহ (Recent Orders)
                            </h5>
                            <a href="{{ route('agent.orders') }}" class="btn btn-outline-primary btn-sm py-0 font-size-12">সবগুলো দেখুন &rarr;</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 font-size-13">
                                    <thead class="table-light text-muted font-size-11 text-uppercase">
                                        <tr>
                                            <th class="ps-3">Order No</th>
                                            <th>Card No & Name</th>
                                            <th>Items</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-end pe-3">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentOrders as $order)
                                        <tr>
                                            <td class="ps-3 fw-bold text-primary">{{ $order->request_no }}</td>
                                            <td>
                                                <div class="fw-semibold text-dark">{{ $order->card_number }}</div>
                                                <div class="text-muted font-size-11">{{ optional($order->user)->name ?? 'Cardholder' }}</div>
                                            </td>
                                            <td>{{ $order->items->count() }} টি পণ্য</td>
                                            <td class="text-center">
                                                @if($order->status == 'completed')
                                                    <span class="badge bg-success font-size-11">Delivered</span>
                                                @elseif($order->status == 'ready_for_collection')
                                                    <span class="badge bg-purple font-size-11" style="background-color: #6f42c1;">Ready for Collect</span>
                                                @elseif($order->status == 'approved')
                                                    <span class="badge bg-primary font-size-11">Approved</span>
                                                @else
                                                    <span class="badge bg-warning text-dark font-size-11">Pending</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-3">
                                                <a href="{{ route('agent.orders', ['search' => $order->request_no]) }}" class="btn btn-outline-info btn-sm py-0 font-size-11">
                                                    বিস্তারিত
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">কোনো সাম্প্রতিক অর্ডার নেই</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel 2: Recent Stock Receives -->
                <div class="col-xl-5">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bold text-dark mb-0 font-size-15">
                                <i class="fa fa-truck text-warning me-2"></i>সাম্প্রতিক চালান আগমন ও রিসিভ (Stock Dispatches)
                            </h5>
                            <a href="{{ route('agent.stock.incoming') }}" class="btn btn-outline-warning btn-sm py-0 font-size-12 text-dark">সব চালান &rarr;</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 font-size-13">
                                    <thead class="table-light text-muted font-size-11 text-uppercase">
                                        <tr>
                                            <th class="ps-3">Challan No</th>
                                            <th>Source</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-end pe-3">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentDispatches as $dsp)
                                        <tr>
                                            <td class="ps-3 fw-bold text-dark">{{ $dsp->dispatch_no }}</td>
                                            <td>
                                                <span class="font-size-12">
                                                    {{ $dsp->supply_flow == 'direct_agent_supply' ? 'Direct Supplier' : 'Central Warehouse' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($dsp->status == 'received')
                                                    <span class="badge bg-success font-size-11">Received</span>
                                                @else
                                                    <span class="badge bg-warning text-dark font-size-11">In Transit</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-3 text-muted font-size-12">
                                                {{ $dsp->dispatch_date ? date('d M', strtotime($dsp->dispatch_date)) : 'N/A' }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">কোনো চালান তথ্য নেই</td>
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
@endsection
