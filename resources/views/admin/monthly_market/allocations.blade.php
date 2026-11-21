@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title">এজেন্ট পয়েন্ট ডিস্ট্রিবিউশন ও বরাদ্দ (Agent Point Distribution Allocation)</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.monthly_market.requests') }}">বাজার রিকোয়েস্ট</a></li>
                        <li class="breadcrumb-item active">এজেন্ট পয়েন্ট বরাদ্দ</li>
                    </ol>
                </div>
                <div>
                    <button type="button" class="btn btn-success btn-sm me-2" data-bs-toggle="modal" data-bs-target="#newAllocationModal">
                        <i class="fa fa-plus-circle me-1"></i> নতুন পণ্য বরাদ্দ করুন (New Allocation)
                    </button>
                    <a href="{{ route('admin.monthly_market.dispatches') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-truck me-1"></i> সেন্ট্রাল ডিসপ্যাচ চালান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>সফল!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Filter Bar -->
            <div class="box">
                <div class="box-body py-3">
                    <form method="GET" action="{{ route('admin.monthly_market.allocations') }}" class="row g-3 align-items-center">
                        <div class="col-md-3 col-6">
                            <label class="form-label fw-bold mb-1">মাস</label>
                            <select name="month" class="form-select">
                                @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $m)
                                    <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label fw-bold mb-1">বছর</label>
                            <select name="year" class="form-select">
                                @foreach([date('Y'), date('Y')+1, date('Y')-1] as $y)
                                    <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label fw-bold mb-1">এজেন্ট পয়েন্ট</label>
                            <select name="agent_point_id" class="form-select">
                                <option value="">সকল এজেন্ট পয়েন্ট</option>
                                @foreach($agentPoints as $point)
                                    <option value="{{ $point->id }}" {{ $selectedAgentPointId == $point->id ? 'selected' : '' }}>{{ $point->name }} ({{ $point->area }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-12 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-filter me-1"></i> ফিল্টার
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Allocations Matrix Table (Section 20) -->
            <div class="box">
                <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                    <h4 class="box-title text-dark"><i class="fa fa-th me-2 text-primary"></i> এজেন্ট পয়েন্টভিত্তিক বরাদ্দ ও বিতরণ স্থিতি ({{ $selectedMonth }} {{ $selectedYear }})</h4>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0 text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 20%; text-align: left;">এজেন্ট পয়েন্ট</th>
                                    <th style="width: 12%;">এরিয়া</th>
                                    <th style="width: 15%; text-align: left;">পণ্য</th>
                                    <th style="width: 10%;">চাহিদা (Req)</th>
                                    <th style="width: 10%;">বরাদ্দ (Allocated)</th>
                                    <th style="width: 10%;">পাঠানো (Dispatched)</th>
                                    <th style="width: 10%;">প্রাপ্ত (Received)</th>
                                    <th style="width: 8%;">অবশিষ্ট</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allocations as $idx => $alloc)
                                    <tr>
                                        <td class="align-middle fw-bold">{{ $idx + 1 }}</td>
                                        <td class="align-middle text-start fw-bold text-dark">
                                            {{ $alloc->agentPoint->name ?? 'N/A' }}
                                        </td>
                                        <td class="align-middle text-muted">{{ $alloc->agentPoint->area ?? '-' }}</td>
                                        <td class="align-middle text-start fw-bold text-primary">
                                            {{ $alloc->product_name }}
                                        </td>
                                        <td class="align-middle fw-bold text-muted">
                                            {{ floatval($alloc->requested_quantity) }} {{ $alloc->unit }}
                                        </td>
                                        <td class="align-middle fw-bold text-success fs-15">
                                            {{ floatval($alloc->allocated_quantity) }} {{ $alloc->unit }}
                                        </td>
                                        <td class="align-middle fw-bold text-primary">
                                            {{ floatval($alloc->dispatched_quantity) }} {{ $alloc->unit }}
                                        </td>
                                        <td class="align-middle fw-bold text-info">
                                            {{ floatval($alloc->received_quantity) }} {{ $alloc->unit }}
                                        </td>
                                        <td class="align-middle fw-bold text-danger">
                                            {{ floatval($alloc->allocated_quantity - $alloc->distributed_quantity) }} {{ $alloc->unit }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">
                                            {{ $selectedMonth }} {{ $selectedYear }} মাসের জন্য এখনো কোনো বরাদ্দ সংরক্ষণ করা হয়নি।<br>
                                            <button type="button" class="btn btn-success btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#newAllocationModal">
                                                <i class="fa fa-plus me-1"></i> এখনই নতুন বরাদ্দ যোগ করুন
                                            </button>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- New Allocation Modal -->
<div class="modal fade" id="newAllocationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.monthly_market.store_allocation') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white"><i class="fa fa-plus-circle me-1"></i> এজেন্ট পয়েন্টে পণ্য বরাদ্দ প্রদান</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">মাস <span class="text-danger">*</span></label>
                            <select name="month" class="form-select" required>
                                @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $m)
                                    <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">বছর <span class="text-danger">*</span></label>
                            <select name="year" class="form-select" required>
                                @foreach([date('Y'), date('Y')+1] as $y)
                                    <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">এজেন্ট পয়েন্ট <span class="text-danger">*</span></label>
                        <select name="agent_point_id" class="form-select" required>
                            <option value="">-- এজেন্ট পয়েন্ট নির্বাচন করুন --</option>
                            @foreach($agentPoints as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->area }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">পণ্যের নাম <span class="text-danger">*</span></label>
                        <input type="text" name="product_name" class="form-control" placeholder="যেমন: চাল / ডাল / চিনি / তেল / আটা" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">বরাদ্দকৃত পরিমাণ <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" name="allocated_quantity" class="form-control text-end" placeholder="0.0" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">একক (Unit) <span class="text-danger">*</span></label>
                            <select name="unit" class="form-select" required>
                                <option value="KG">কেজি (KG)</option>
                                <option value="Liter">লিটার (Liter)</option>
                                <option value="Metric Ton (MT)">মেট্রিক টন (MT)</option>
                                <option value="Bag">বস্তা (Bag)</option>
                                <option value="Piece">পিস (PCS)</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">বরাদ্দের কারণ / মন্তব্য (কম বা বেশি বরাদ্দের ক্ষেত্রে)</label>
                        <input type="text" name="allocation_reason" class="form-control" placeholder="যেমন: নওগাঁ Central চাহিদার শতভাগ বরাদ্দকৃত">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success">বরাদ্দ সংরক্ষণ করুন</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
