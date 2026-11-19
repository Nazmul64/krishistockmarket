@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header pb-2">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="ti-package text-success me-2"></i>সুপার শপ ইনভেন্টরি ও স্টক ম্যানেজমেন্ট
                    </h3>
                    <p class="text-muted mb-0">ই-কমার্স ক্যাটালগের সাথে রিয়েল-টাইম স্টক সিঙ্ক, স্বয়ংক্রিয় বিক্রয় হিসাব ও অডিট লগ</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.supershop.inventory.sync', ['branch_id' => $selectedBranchId]) }}" class="btn btn-sm btn-primary fw-bold shadow-sm">
                        <i class="ti-reload me-1"></i> ক্যাটালগ সিঙ্ক (Sync Now)
                    </a>
                    <a href="{{ route('admin.supershop.inventory.movements', ['branch_id' => $selectedBranchId]) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ti-time me-1"></i> স্টক মুভমেন্ট অডিট লগ
                    </a>
                    <a href="{{ route('admin.supershop.pos', ['branch_id' => $selectedBranchId]) }}" class="btn btn-sm btn-success fw-bold shadow-sm">
                        <i class="ti-shopping-cart me-1"></i> POS কাউন্টার খুলুন
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fa fa-exclamation-circle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- 1. Executive Summary KPI Cards -->
            <div class="row g-3 mb-4">
                <div class="col-xl-2 col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <div class="text-muted small fw-semibold mb-1">মোট ক্যাটালগ আইটেম</div>
                        <div class="fs-4 fw-bold text-dark">{{ number_format($totalItems) }}</div>
                        <div class="small text-muted mt-1"><i class="fa fa-check text-success me-1"></i>ই-কমার্স অটো-সিঙ্ক</div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <div class="text-muted small fw-semibold mb-1">বর্তমান মজুত ইউনিট</div>
                        <div class="fs-4 fw-bold text-primary">{{ number_format($totalStockUnits) }}</div>
                        <div class="small text-muted mt-1">সব ক্যাটাগরি মিলিয়ে</div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <div class="text-muted small fw-semibold mb-1">মোট বিক্রিত ইউনিট</div>
                        <div class="fs-4 fw-bold text-info">{{ number_format($totalUnitsSold) }}</div>
                        <div class="small text-muted mt-1">POS + অনলাইন বিক্রয়</div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <div class="text-muted small fw-semibold mb-1">পর্যাপ্ত স্টক পণ্য</div>
                        <div class="fs-4 fw-bold text-success">{{ number_format($inStockCount) }}</div>
                        <div class="small text-success mt-1"><i class="fa fa-arrow-up me-1"></i>নরমাল স্টক</div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <div class="text-muted small fw-semibold mb-1">সীমিত স্টক অ্যালার্ট</div>
                        <div class="fs-4 fw-bold text-warning">{{ number_format($totalLowStock) }}</div>
                        <div class="small text-warning mt-1"><i class="fa fa-exclamation-triangle me-1"></i>রিঅর্ডার প্রয়োজন</div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <div class="text-muted small fw-semibold mb-1">স্টক আউট (শেষ)</div>
                        <div class="fs-4 fw-bold text-danger">{{ number_format($outOfStock) }}</div>
                        <div class="small text-danger mt-1"><i class="fa fa-times-circle me-1"></i>অবিলম্বে রিস্টক</div>
                    </div>
                </div>
            </div>

            <!-- 2. Branch Selector & Filter Bar -->
            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('admin.supershop.inventory') }}" class="row g-2 align-items-center">
                        <!-- Branch Selection -->
                        <div class="col-lg-3 col-md-4 col-12">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light text-dark fw-bold"><i class="ti-home me-1"></i>ব্রাঞ্চ:</span>
                                <select name="branch_id" class="form-select form-select-sm fw-bold border-success" onchange="this.form.submit()">
                                    @foreach($branches as $b)
                                        <option value="{{ $b->id }}" {{ $selectedBranchId == $b->id ? 'selected' : '' }}>
                                            {{ $b->code }} - {{ $b->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Search -->
                        <div class="col-lg-3 col-md-4 col-12">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white"><i class="ti-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="পণ্যের নাম, বাংলা নাম বা SKU..." value="{{ $search }}">
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div class="col-lg-2 col-md-4 col-6">
                            <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">সকল ক্যাটাগরি (All)</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $selectedCatId == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Stock Status Filter -->
                        <div class="col-lg-2 col-md-6 col-6">
                            <select name="stock_filter" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="all" {{ $stockFilter === 'all' ? 'selected' : '' }}>সকল স্টক স্ট্যাটাস</option>
                                <option value="in_stock" {{ $stockFilter === 'in_stock' ? 'selected' : '' }}>পর্যাপ্ত স্টক (>10)</option>
                                <option value="low_stock" {{ $stockFilter === 'low_stock' ? 'selected' : '' }}>সীমিত স্টক (<=10)</option>
                                <option value="out_of_stock" {{ $stockFilter === 'out_of_stock' ? 'selected' : '' }}>স্টক শেষ (0)</option>
                            </select>
                        </div>

                        <!-- Submit & Reset -->
                        <div class="col-lg-2 col-md-6 col-12 d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary w-100 fw-bold">
                                <i class="ti-filter me-1"></i> ফিল্টার
                            </button>
                            <a href="{{ route('admin.supershop.inventory', ['branch_id' => $selectedBranchId]) }}" class="btn btn-sm btn-light border" title="ফিল্টার রিসেট">
                                <i class="ti-reload"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 3. Inventory Table -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-3 py-3">পণ্য বিবরণ (Product)</th>
                                    <th>ক্যাটাগরি ও ব্র্যান্ড</th>
                                    <th class="text-end">ক্রয়মূল্য (Cost)</th>
                                    <th class="text-end">বিক্রয়মূল্য (MRP)</th>
                                    <th class="text-center">মোট বিক্রয়</th>
                                    <th class="text-center">বর্তমান মজুত</th>
                                    <th class="text-center">রিঅর্ডার লেভেল</th>
                                    <th class="text-center">স্ট্যাটাস</th>
                                    <th class="text-center">শেল্ফ</th>
                                    <th class="text-end pe-3">স্টক অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inventories as $inv)
                                    @php
                                        $stock = (int)($inv->branch_stock ?? $inv->stock_quantity);
                                        $reorder = (int)($inv->branch_reorder_level ?: 10);
                                        $isLow = $stock <= $reorder && $stock > 0;
                                        $isOut = $stock <= 0;
                                        $soldCount = $inv->total_sold;
                                        $img = $inv->image ?: $inv->thumbnail;
                                        $costPrice = $inv->branch_purchase_price ?: ($inv->purchase_price ?: round($inv->price * 0.8, 2));
                                        $sellingPrice = $inv->branch_selling_price ?: $inv->price;
                                    @endphp
                                    <tr class="{{ $isOut ? 'table-danger' : ($isLow ? 'table-warning' : '') }}">
                                        <!-- Product Info -->
                                        <td class="ps-3 py-3">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $img ? asset($img) : asset('assets/images/placeholder.png') }}" class="rounded-3 me-2 border shadow-none" style="width: 44px; height: 44px; object-fit: cover;" onerror="this.src='https://placehold.co/44x44?text=Shop'">
                                                <div>
                                                    <div class="fw-bold text-dark fs-6">{{ $inv->name }}</div>
                                                    @if($inv->name_bn)
                                                        <div class="small text-muted">{{ $inv->name_bn }}</div>
                                                    @endif
                                                    <div class="small text-muted mt-1">
                                                        <span class="badge bg-light text-dark border">SKU: {{ $inv->sku ?: 'N/A' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Category & Brand -->
                                        <td>
                                            <span class="badge bg-light text-dark border px-2 py-1 mb-1 d-inline-block">
                                                {{ $inv->category ? $inv->category->name : 'General' }}
                                            </span>
                                            @if($inv->brand)
                                                <div class="small text-muted"><i class="ti-tag me-1"></i>{{ is_string($inv->brand) ? $inv->brand : ($inv->brand->name ?? '') }}</div>
                                            @endif
                                        </td>

                                        <!-- Cost Price -->
                                        <td class="text-end text-muted">
                                            ৳ {{ number_format($costPrice, 2) }}
                                        </td>

                                        <!-- Selling Price -->
                                        <td class="text-end fw-bold text-dark">
                                            ৳ {{ number_format($sellingPrice, 2) }}
                                        </td>

                                        <!-- Sold Count -->
                                        <td class="text-center">
                                            <span class="badge bg-info-light text-info fw-bold px-2 py-1 fs-6">
                                                {{ $soldCount }} {{ $inv->unit ?: 'টি' }}
                                            </span>
                                        </td>

                                        <!-- Current Available Stock -->
                                        <td class="text-center">
                                            <div class="fs-6 fw-bolder {{ $isOut ? 'text-danger' : ($isLow ? 'text-warning-dark' : 'text-success') }}">
                                                {{ $stock }} {{ $inv->unit ?: 'টি' }}
                                            </div>
                                            <div class="small text-muted">মজুত আছে</div>
                                        </td>

                                        <!-- Reorder Level -->
                                        <td class="text-center text-muted small">
                                            <span class="badge bg-light text-secondary">{{ $reorder }}</span>
                                        </td>

                                        <!-- Status Badge -->
                                        <td class="text-center">
                                            @if($isOut)
                                                <span class="badge bg-danger rounded-pill px-3 py-1">স্টক শেষ (Stockout)</span>
                                            @elseif($isLow)
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1">সীমিত (Low Stock)</span>
                                            @else
                                                <span class="badge bg-success rounded-pill px-3 py-1">পর্যাপ্ত (In Stock)</span>
                                            @endif
                                        </td>

                                        <!-- Shelf Location -->
                                        <td class="text-center">
                                            <span class="badge bg-light text-muted border">{{ $inv->shelf_location ?: 'নির্ধারিত নয়' }}</span>
                                        </td>

                                        <!-- Stock Adjustment Action -->
                                        <td class="text-end pe-3">
                                            <button type="button" class="btn btn-xs btn-outline-primary rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#adjustModal{{ $inv->id }}">
                                                <i class="ti-exchange-vertical me-1"></i> স্টক সমন্বয় / রিস্টক
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Stock Adjustment Modal -->
                                    <div class="modal fade" id="adjustModal{{ $inv->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="POST" action="{{ route('admin.supershop.inventory.adjust', $inv->id) }}">
                                                @csrf
                                                <input type="hidden" name="branch_id" value="{{ $selectedBranchId }}">
                                                <div class="modal-content rounded-4 border-0 shadow">
                                                    <div class="modal-header bg-light rounded-top-4">
                                                        <h5 class="modal-title fw-bold text-dark">
                                                            <i class="ti-package text-success me-1"></i> স্টক সমন্বয় – {{ $inv->name }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <!-- Info Alert -->
                                                        <div class="d-flex justify-content-between p-3 bg-light rounded-3 mb-3">
                                                            <div>
                                                                <div class="text-muted small">বর্তমান মজুত স্টক:</div>
                                                                <div class="fs-5 fw-bold text-primary">{{ $stock }} {{ $inv->unit ?: 'টি' }}</div>
                                                            </div>
                                                            <div class="text-end">
                                                                <div class="text-muted small">মোট বিক্রিত:</div>
                                                                <div class="fs-5 fw-bold text-dark">{{ $soldCount }} {{ $inv->unit ?: 'টি' }}</div>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">সমন্বয়ের ধরন (Adjustment Action) <span class="text-danger">*</span></label>
                                                            <select name="adjustment_type" class="form-select form-select-lg" required>
                                                                <option value="add">➕ স্টক যোগ করুন (রিস্টক / নতুন ক্রয়)</option>
                                                                <option value="subtract">➖ স্টক কমান (ড্যামেজ / নষ্ট / মেয়াদোত্তীর্ণ)</option>
                                                                <option value="set">🔄 নির্দিষ্ট সংখ্যা সেট করুন (Physical Count Set)</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">পরিমাণ (Quantity) <span class="text-danger">*</span></label>
                                                            <input type="number" name="quantity" class="form-control form-control-lg fw-bold" min="0" required placeholder="পরিমাণ লিখুন (যেমন: 10, 50, 100)">
                                                        </div>

                                                        <div class="row g-2 mb-3">
                                                            <div class="col-6">
                                                                <label class="form-label small fw-bold">শেল্ফ অবস্থান (Shelf)</label>
                                                                <input type="text" name="shelf_location" class="form-control form-control-sm" value="{{ $inv->shelf_location }}" placeholder="যেমন: Aisle 3, Rack B">
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="form-label small fw-bold">রিঅর্ডার অ্যালার্ট লেভেল</label>
                                                                <input type="number" name="reorder_level" class="form-control form-control-sm" value="{{ $reorder }}" min="1">
                                                            </div>
                                                        </div>

                                                        <div class="row g-2 mb-3">
                                                            <div class="col-6">
                                                                <label class="form-label small fw-bold">শাখা ক্রয়মূল্য (৳)</label>
                                                                <input type="number" step="0.01" name="purchase_price" class="form-control form-control-sm" value="{{ $costPrice }}" placeholder="ক্রয়মূল্য">
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="form-label small fw-bold">শাখা বিক্রয়মূল্য (৳)</label>
                                                                <input type="number" step="0.01" name="selling_price" class="form-control form-control-sm" value="{{ $sellingPrice }}" placeholder="বিক্রয়মূল্য">
                                                            </div>
                                                        </div>

                                                        <div class="mb-2">
                                                            <label class="form-label fw-bold">সমন্বয়ের কারণ / অডিট নোট <span class="text-danger">*</span></label>
                                                            <input type="text" name="reason" class="form-control" placeholder="যেমন: নতুন চালানের রিস্টক, ফিজিক্যাল অডিট সংশোধন" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light rounded-bottom-4">
                                                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">বাতিল</button>
                                                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">স্টক আপডেট নিশ্চিত করুন</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="ti-package font-size-36 d-block mb-2 text-secondary"></i>
                                                কোনো পণ্য পাওয়া যায়নি।
                                            </div>
                                            <a href="{{ route('admin.supershop.inventory.sync', ['branch_id' => $selectedBranchId]) }}" class="btn btn-sm btn-primary mt-2">
                                                <i class="ti-reload me-1"></i> ক্যাটালগ থেকে সব পণ্য অটো-সিঙ্ক করুন
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($inventories->hasPages())
                    <div class="card-footer bg-white border-0 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="text-muted small">
                            মোট {{ number_format($inventories->total()) }} টির মধ্যে {{ number_format($inventories->firstItem()) }} - {{ number_format($inventories->lastItem()) }} টি দেখানো হচ্ছে
                        </div>
                        <div>
                            {{ $inventories->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
