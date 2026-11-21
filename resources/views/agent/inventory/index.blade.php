@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-cubes text-success me-2"></i>Point Live Inventory & Stock (পয়েন্ট লাইভ মজুত হিসাব)
                    </h3>
                    <p class="text-muted mb-0">এজেন্ট পয়েন্ট: <strong>{{ $point->name }}</strong> ({{ $point->code }}) | বর্তমান লাইভ স্টক ব্যালেন্স</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('agent.stock.ledger') }}" class="btn btn-primary btn-sm fw-semibold shadow-sm">
                        <i class="fa fa-list-alt me-1"></i> স্টক লেজার ও লেনদেন ইতিহাস
                    </a>
                    <a href="{{ route('agent.stock.incoming') }}" class="btn btn-outline-warning btn-sm text-dark">
                        <i class="fa fa-download me-1"></i> নতুন চালান গ্রহণ
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- 3 Top Metric Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                        <div class="text-muted font-size-12 text-uppercase fw-semibold">Available Stock (বিতরণযোগ্য মজুত)</div>
                        <h3 class="fw-bold text-success mb-0 mt-1">{{ number_format($totalAvailable, 1) }}</h3>
                        <small class="text-muted">কার্ডহোল্ডারদের বিতরণের জন্য পয়েন্টে প্রস্তুত</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                        <div class="text-muted font-size-12 text-uppercase fw-semibold">Total Distributed (বিতরণকৃত মোট পণ্য)</div>
                        <h3 class="fw-bold text-info mb-0 mt-1">{{ number_format($totalDistributed, 1) }}</h3>
                        <small class="text-muted">কার্ডহোল্ডারদের সফলভাবে সরবরাহকৃত</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                        <div class="text-muted font-size-12 text-uppercase fw-semibold">Total Damaged / Shortage (নষ্ট বা ঘাটতি)</div>
                        <h3 class="fw-bold text-danger mb-0 mt-1">{{ number_format($totalDamaged, 1) }}</h3>
                        <small class="text-muted">পরিবহন বা সংরক্ষণজনিত নষ্ট</small>
                    </div>
                </div>
            </div>

            <!-- Inventory Table -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="card-title fw-bold text-dark mb-0 font-size-15">
                            <i class="fa fa-cubes text-success me-2"></i>লাইভ পণ্যের তালিকা ও মজুত স্ট্যাটাস
                        </h5>
                        <form method="GET" action="{{ route('agent.inventory') }}" class="d-flex gap-2">
                            <div class="input-group input-group-sm">
                                <input type="text" name="search" class="form-control" placeholder="পণ্যের নাম দিয়ে খুঁজুন..." value="{{ request('search') }}">
                                <button class="btn btn-secondary" type="submit"><i class="fa fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted font-size-12 text-uppercase">
                                <tr>
                                    <th class="ps-4">Product Name</th>
                                    <th>Unit</th>
                                    <th class="text-end">Opening Stock</th>
                                    <th class="text-end">Total Received</th>
                                    <th class="text-end">Total Distributed</th>
                                    <th class="text-end">Damaged</th>
                                    <th class="text-end">Available Stock</th>
                                    <th class="text-center pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inventories as $inv)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark font-size-14">{{ $inv->product_name }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark font-size-12">{{ $inv->unit }}</span>
                                    </td>
                                    <td class="text-end text-muted">
                                        {{ number_format($inv->opening_stock, 1) }}
                                    </td>
                                    <td class="text-end text-primary fw-semibold">
                                        {{ number_format($inv->received_stock, 1) }}
                                    </td>
                                    <td class="text-end text-info fw-semibold">
                                        {{ number_format($inv->distributed_stock, 1) }}
                                    </td>
                                    <td class="text-end text-danger">
                                        {{ number_format($inv->damaged_stock, 1) }}
                                    </td>
                                    <td class="text-end">
                                        <span class="font-size-16 fw-bold text-{{ $inv->available_stock > 10 ? 'success' : ($inv->available_stock > 0 ? 'warning' : 'danger') }}">
                                            {{ number_format($inv->available_stock, 1) }} {{ $inv->unit }}
                                        </span>
                                    </td>
                                    <td class="text-center pe-4">
                                        @if($inv->available_stock > 10)
                                            <span class="badge bg-success-light text-success font-size-11 px-3 py-1 rounded-pill">
                                                <i class="fa fa-check-circle me-1"></i> In Stock
                                            </span>
                                        @elseif($inv->available_stock > 0)
                                            <span class="badge bg-warning-light text-warning font-size-11 px-3 py-1 rounded-pill">
                                                <i class="fa fa-exclamation-triangle me-1"></i> Low Stock
                                            </span>
                                        @else
                                            <span class="badge bg-danger-light text-danger font-size-11 px-3 py-1 rounded-pill">
                                                <i class="fa fa-times-circle me-1"></i> Stock Out
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        আপনার পয়েন্টে এখনো কোনো পণ্য স্টকে তোলা হয়নি। 
                                        <br>
                                        <a href="{{ route('agent.stock.incoming') }}" class="btn btn-warning btn-sm mt-2 text-dark">
                                            <i class="fa fa-download me-1"></i> আগত চালান গ্রহণ করুন
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($inventories->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <span class="text-muted font-size-13">Showing {{ $inventories->firstItem() }} to {{ $inventories->lastItem() }} of {{ $inventories->total() }} items</span>
                        {{ $inventories->links() }}
                    </div>
                </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
