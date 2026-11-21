@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-building-o text-primary me-2"></i>{{ $agentPoint->name }}
                        <span class="badge bg-primary-light text-primary font-size-14 ms-2">{{ $agentPoint->code }}</span>
                    </h3>
                    <p class="text-muted mb-0">এজেন্ট পয়েন্ট প্রোফাইল, লাইভ স্টক ও অর্ডার সারাংশ</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.agents.edit', $agentPoint->id) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-pencil me-1"></i> এডিট করুন
                    </a>
                    <a href="{{ route('admin.agents.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> তালিকায় ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Profile Top Stats Card -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle bg-primary-light p-3 text-primary">
                                    <i class="fa fa-map-marker fa-3x"></i>
                                </div>
                                <div>
                                    <h4 class="fw-bold text-dark mb-1">{{ $agentPoint->name }}</h4>
                                    <div class="text-muted mb-2">
                                        <i class="fa fa-user me-1"></i> দায়িত্বপ্রাপ্ত: <strong>{{ $agentPoint->agent_name }}</strong>
                                        @if($agentPoint->company_name)
                                            <span class="ms-3"><i class="fa fa-building-o me-1"></i> {{ $agentPoint->company_name }}</span>
                                        @endif
                                    </div>
                                    <div class="text-muted font-size-13">
                                        <i class="fa fa-phone me-1 text-success"></i> {{ $agentPoint->contact_number }}
                                        @if($agentPoint->alt_phone)
                                            <span class="ms-2">/ {{ $agentPoint->alt_phone }}</span>
                                        @endif
                                        @if($agentPoint->agentUser && $agentPoint->agentUser->email)
                                            <span class="ms-3"><i class="fa fa-envelope-o me-1 text-info"></i> {{ $agentPoint->agentUser->email }}</span>
                                        @endif
                                    </div>
                                    <div class="text-muted font-size-13 mt-1">
                                        <i class="fa fa-map-pin me-1 text-danger"></i> {{ $agentPoint->area }}, {{ $agentPoint->thana_upazila }}, {{ $agentPoint->district }}
                                        @if($agentPoint->address)
                                            ({{ $agentPoint->address }})
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 text-md-end mt-3 mt-md-0">
                            <span class="badge bg-{{ $agentPoint->status == 'active' ? 'success' : 'danger' }} px-3 py-2 font-size-13 rounded-pill mb-2">
                                <i class="fa fa-circle me-1"></i> {{ strtoupper($agentPoint->status) }}
                            </span>
                            <div class="text-muted font-size-12">
                                উদ্বোধনের তারিখ: {{ $agentPoint->opening_date ? date('d M, Y', strtotime($agentPoint->opening_date)) : 'N/A' }}
                            </div>
                            <div class="text-muted font-size-12">
                                কার্ড ধারণক্ষমতা: {{ number_format($agentPoint->capacity ?? 500) }} জন
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4 Metric Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                        <div class="text-muted font-size-12 text-uppercase fw-semibold">Available Stock (মজুত স্টক)</div>
                        <h3 class="fw-bold text-success mb-0 mt-1">{{ number_format($totalStockAvailable, 1) }}</h3>
                        <small class="text-muted">পয়েন্টে বর্তমানে মজুত আছে</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                        <div class="text-muted font-size-12 text-uppercase fw-semibold">Total Received (মোট গ্রহণ)</div>
                        <h3 class="fw-bold text-primary mb-0 mt-1">{{ number_format($totalStockReceived, 1) }}</h3>
                        <small class="text-muted">কেন্দ্রীয় ওয়্যারহাউজ ও সাপ্লায়ার থেকে</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                        <div class="text-muted font-size-12 text-uppercase fw-semibold">Total Distributed (বিতরণকৃত)</div>
                        <h3 class="fw-bold text-info mb-0 mt-1">{{ number_format($totalStockDistributed, 1) }}</h3>
                        <small class="text-muted">কার্ডহোল্ডারদের সরবরাহকৃত</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                        <div class="text-muted font-size-12 text-uppercase fw-semibold">Assigned Cardholders</div>
                        <h3 class="fw-bold text-warning mb-0 mt-1">{{ number_format($agentPoint->assigned_card_holders_count) }}</h3>
                        <small class="text-muted">নির্ধারিত কার্ডহোল্ডার গ্রাহক</small>
                    </div>
                </div>
            </div>

            <!-- Tabbed Detailed Tables -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom p-0">
                    <ul class="nav nav-tabs nav-bordered px-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active py-3 fw-semibold" data-bs-toggle="tab" href="#inventoryTab" role="tab">
                                <i class="fa fa-cubes me-1 text-success"></i> লাইভ ইনভেন্টরি স্টক ({{ $agentPoint->inventories->count() }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3 fw-semibold" data-bs-toggle="tab" href="#cardholdersTab" role="tab">
                                <i class="fa fa-id-card me-1 text-info"></i> অ্যাসাইনকৃত কার্ডহোল্ডার ({{ $agentPoint->assigned_card_holders_count }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3 fw-semibold" data-bs-toggle="tab" href="#dispatchesTab" role="tab">
                                <i class="fa fa-truck me-1 text-warning"></i> পণ্যের চালান/ডেসপ্যাচ ({{ $agentPoint->dispatches_count }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3 fw-semibold" data-bs-toggle="tab" href="#ordersTab" role="tab">
                                <i class="fa fa-shopping-cart me-1 text-danger"></i> মাসিক বাজার অর্ডার ({{ $agentPoint->market_requests_count }})
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-3">
                    <div class="tab-content">
                        <!-- Tab 1: Inventory -->
                        <div class="tab-pane active" id="inventoryTab" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light text-muted font-size-12">
                                        <tr>
                                            <th>পণ্যের নাম (Product Name)</th>
                                            <th>একক (Unit)</th>
                                            <th class="text-end">মোট গ্রহণ (Received)</th>
                                            <th class="text-end">বিতরণ (Distributed)</th>
                                            <th class="text-end">নষ্ট/ঘাটতি (Damaged)</th>
                                            <th class="text-end">বর্তমান মজুত (Available)</th>
                                            <th class="text-center">স্ট্যাটাস</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($agentPoint->inventories as $inv)
                                        <tr>
                                            <td class="fw-bold text-dark">{{ $inv->product_name }}</td>
                                            <td><span class="badge bg-light text-dark">{{ $inv->unit }}</span></td>
                                            <td class="text-end text-primary fw-semibold">{{ number_format($inv->received_stock, 1) }}</td>
                                            <td class="text-end text-info fw-semibold">{{ number_format($inv->distributed_stock, 1) }}</td>
                                            <td class="text-end text-danger">{{ number_format($inv->damaged_stock, 1) }}</td>
                                            <td class="text-end text-success fw-bold font-size-15">{{ number_format($inv->available_stock, 1) }}</td>
                                            <td class="text-center">
                                                @if($inv->available_stock > 10)
                                                    <span class="badge bg-success-light text-success font-size-11">In Stock</span>
                                                @elseif($inv->available_stock > 0)
                                                    <span class="badge bg-warning-light text-warning font-size-11">Low Stock</span>
                                                @else
                                                    <span class="badge bg-danger-light text-danger font-size-11">Stock Out</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">কোনো স্টক পণ্য পাওয়া যায়নি</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab 2: Assigned Cardholders -->
                        <div class="tab-pane" id="cardholdersTab" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0">অ্যাসাইনকৃত কার্ডহোল্ডার গ্রাহকবৃন্দ</h6>
                                <a href="{{ route('admin.agents.assign_cardholders', ['agent_point_id' => $agentPoint->id]) }}" class="btn btn-outline-info btn-sm">
                                    <i class="fa fa-user-plus me-1"></i> আরো কার্ডহোল্ডার অ্যাসাইন করুন
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light text-muted font-size-12">
                                        <tr>
                                            <th>কার্ড নম্বর</th>
                                            <th>গ্রাহকের নাম</th>
                                            <th>ফোন নম্বর</th>
                                            <th>ইমেইল</th>
                                            <th>এলাকা</th>
                                            <th>স্ট্যাটাস</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($agentPoint->assignedCardHolders as $ch)
                                        <tr>
                                            <td><span class="badge bg-primary-light text-primary font-size-12 fw-bold">{{ $ch->card_number ?? 'N/A' }}</span></td>
                                            <td class="fw-semibold text-dark">{{ $ch->name }}</td>
                                            <td>{{ $ch->phone ?? 'N/A' }}</td>
                                            <td>{{ $ch->email ?? 'N/A' }}</td>
                                            <td>{{ $ch->area ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $ch->status == 'active' ? 'success' : 'secondary' }}-light text-{{ $ch->status == 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($ch->status ?? 'active') }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">এখনো কোনো কার্ডহোল্ডার এই পয়েন্টে অ্যাসাইন করা হয়নি</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab 3: Dispatches -->
                        <div class="tab-pane" id="dispatchesTab" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light text-muted font-size-12">
                                        <tr>
                                            <th>চালান নং (Dispatch No)</th>
                                            <th>ডিস্ট্রিবিউশন মাস</th>
                                            <th>সাপ্লাই ফ্লো</th>
                                            <th>প্রেরণের তারিখ</th>
                                            <th>স্ট্যাটাস</th>
                                            <th>রিসিভ তারিখ ও গ্রহণকারী</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($agentPoint->dispatches as $dsp)
                                        <tr>
                                            <td><span class="badge bg-warning-light text-dark fw-bold">{{ $dsp->dispatch_no }}</span></td>
                                            <td>{{ $dsp->distribution_month }} {{ $dsp->distribution_year }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    {{ $dsp->supply_flow == 'direct_agent_supply' ? 'Direct Supplier' : 'Central Warehouse' }}
                                                </span>
                                            </td>
                                            <td>{{ $dsp->dispatch_date ? date('d M, Y', strtotime($dsp->dispatch_date)) : 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $dsp->status == 'received' ? 'success' : ($dsp->status == 'in_transit' ? 'warning' : 'info') }} text-white">
                                                    {{ ucfirst($dsp->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($dsp->received_date)
                                                    <span class="text-success"><i class="fa fa-check-circle me-1"></i> {{ date('d M, Y', strtotime($dsp->received_date)) }} ({{ $dsp->received_by }})</span>
                                                @else
                                                    <span class="text-muted">অপেক্ষমাণ</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">কোনো চালান পাওয়া যায়নি</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab 4: Orders -->
                        <div class="tab-pane" id="ordersTab" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light text-muted font-size-12">
                                        <tr>
                                            <th>অর্ডার নং</th>
                                            <th>কার্ড নম্বর</th>
                                            <th>মাস</th>
                                            <th>আইটেম সংখ্যা</th>
                                            <th>আনুমানিক মূল্য</th>
                                            <th>অর্ডার স্ট্যাটাস</th>
                                            <th>কালেকশন স্ট্যাটাস</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($agentPoint->marketRequests as $req)
                                        <tr>
                                            <td><span class="badge bg-info-light text-info fw-bold">{{ $req->request_no }}</span></td>
                                            <td>{{ $req->card_number }}</td>
                                            <td>{{ $req->month }} {{ $req->year }}</td>
                                            <td>{{ $req->total_items }} টি</td>
                                            <td class="fw-bold">৳{{ number_format($req->total_estimated_amount, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $req->status == 'completed' ? 'success' : ($req->status == 'approved' ? 'primary' : 'warning') }}-light text-dark">
                                                    {{ ucfirst($req->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $req->collection_status == 'collected' ? 'success' : 'secondary' }}-light text-dark">
                                                    {{ ucfirst($req->collection_status ?? 'pending') }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">কোনো মাসিক বাজার অর্ডার পাওয়া যায়নি</td>
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
