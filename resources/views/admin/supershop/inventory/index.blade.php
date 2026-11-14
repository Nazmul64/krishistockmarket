@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="ti-archive text-success me-2"></i>মাল্টি-ব্রাঞ্চ স্টক ও ইনভেন্টরি ম্যানেজমেন্ট
                    </h3>
                    <p class="text-muted mb-0">সেন্ট্রাল ক্যাটালগের সাথে জিরো-ডুপ্লিকেশন ব্রাঞ্চ স্টক ট্র্যাকিং, রিঅর্ডার অ্যালার্ট ও শেল্ফ লোকেশন</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.supershop.inventory.movements', ['branch_id' => $selectedBranchId]) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ti-time me-1"></i> স্টক মুভমেন্ট হিস্ট্রি
                    </a>
                    <button type="button" class="btn btn-sm btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#assignProductModal">
                        <i class="ti-plus me-1"></i> ক্যাটালগ থেকে পণ্য অ্যাসাইন
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-circle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- 1. Branch Selector & Summary Cards -->
            <div class="row align-items-center mb-3">
                <div class="col-md-4 col-12 mb-2 mb-md-0">
                    <form method="GET" action="{{ route('admin.supershop.inventory') }}" class="d-flex align-items-center gap-2">
                        <label class="fw-bold text-nowrap mb-0 text-dark"><i class="ti-home me-1"></i>ব্রাঞ্চ নির্বাচন:</label>
                        <select name="branch_id" class="form-select form-select-sm fw-bold border-success" onchange="this.form.submit()">
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}" {{ $selectedBranchId == $b->id ? 'selected' : '' }}>
                                    {{ $b->code }} - {{ $b->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="col-md-8 col-12">
                    <div class="d-flex justify-content-md-end gap-2 flex-wrap">
                        <span class="badge bg-primary-light text-primary px-3 py-2 fs-6">
                            মোট আইটেম: <strong>{{ $totalItems }}</strong>
                        </span>
                        <span class="badge bg-success-light text-success px-3 py-2 fs-6">
                            মোট স্টক ইউনিট: <strong>{{ number_format($totalStockUnits) }}</strong>
                        </span>
                        <a href="{{ route('admin.supershop.inventory', ['branch_id' => $selectedBranchId, 'low_stock' => 1]) }}" class="badge {{ $totalLowStock > 0 ? 'bg-warning text-dark' : 'bg-light text-muted' }} px-3 py-2 fs-6 text-decoration-none">
                            লো স্টক অ্যালার্ট: <strong>{{ $totalLowStock }}</strong>
                        </a>
                        @if($outOfStock > 0)
                            <span class="badge bg-danger px-3 py-2 fs-6">
                                স্টক শেষ: <strong>{{ $outOfStock }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 2. Filters & Search Box -->
            <div class="box shadow-sm mb-3">
                <div class="box-body py-2">
                    <form method="GET" action="{{ route('admin.supershop.inventory') }}" class="row g-2 align-items-center">
                        <input type="hidden" name="branch_id" value="{{ $selectedBranchId }}">
                        
                        <div class="col-md-4 col-12">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="ti-search"></i></span>
                                <input type="text" name="search" class="form-control" placeholder="পণ্যের নাম বা বারকোড / SKU দিয়ে খুঁজুন..." value="{{ $search }}">
                            </div>
                        </div>

                        <div class="col-md-3 col-6">
                            <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">সকল ক্যাটাগরি (All Categories)</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $selectedCatId == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 col-6">
                            <div class="form-check form-check-inline mt-1">
                                <input class="form-check-input" type="checkbox" name="low_stock" id="lowStockCheck" value="1" {{ $lowStockOnly ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label small fw-bold text-danger" for="lowStockCheck">শুধুমাত্র স্বল্প স্টক (<= Reorder)</label>
                            </div>
                        </div>

                        <div class="col-md-2 col-12 text-end">
                            <button type="submit" class="btn btn-sm btn-primary w-100">ফিল্টার প্রয়োগ</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 3. Inventory Table -->
            <div class="box shadow-sm">
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>পণ্য (Product)</th>
                                    <th>ক্যাটাগরি</th>
                                    <th>শেল্ফ অবস্থান (Shelf)</th>
                                    <th class="text-end">ক্রয়মূল্য (Cost)</th>
                                    <th class="text-end">বিক্রয়মূল্য (MRP)</th>
                                    <th class="text-center">বর্তমান স্টক</th>
                                    <th class="text-center">রিঅর্ডার লেভেল</th>
                                    <th class="text-center">স্ট্যাটাস</th>
                                    <th class="text-end">স্টক সমন্বয়</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inventories as $inv)
                                    @php
                                        $prod = $inv->product;
                                        $isLow = $inv->stock_quantity <= $inv->reorder_level;
                                        $isOut = $inv->stock_quantity <= 0;
                                    @endphp
                                    <tr class="{{ $isOut ? 'table-danger' : ($isLow ? 'table-warning' : '') }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $prod && $prod->thumbnail ? asset($prod->thumbnail) : asset('assets/images/placeholder.png') }}" class="rounded me-2 border" style="width: 40px; height: 40px; object-fit: cover;" onerror="this.src='https://placehold.co/40x40?text=Shop'">
                                                <div>
                                                    <strong class="text-dark">{{ $prod ? $prod->name : 'Unknown Product' }}</strong>
                                                    <div class="small text-muted">
                                                        SKU: <span class="fw-bold">{{ $prod ? $prod->sku : 'N/A' }}</span>
                                                        @if($prod && $prod->barcode)
                                                            | <i class="fa fa-barcode"></i> {{ $prod->barcode }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $prod && $prod->category ? $prod->category->name : 'General' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-muted">{{ $inv->shelf_location ?: 'অ্যাসাইন হয়নি' }}</span>
                                        </td>
                                        <td class="text-end text-muted">
                                            ৳ {{ number_format($inv->purchase_price ?: ($prod ? $prod->price * 0.8 : 0), 2) }}
                                        </td>
                                        <td class="text-end fw-bold text-dark">
                                            ৳ {{ number_format($inv->selling_price ?: ($prod ? $prod->price : 0), 2) }}
                                        </td>
                                        <td class="text-center">
                                            <span class="fs-6 fw-bold {{ $isOut ? 'text-danger' : ($isLow ? 'text-warning-dark' : 'text-success') }}">
                                                {{ $inv->stock_quantity }} {{ $prod ? $prod->unit : 'টি' }}
                                            </span>
                                        </td>
                                        <td class="text-center text-muted small">
                                            {{ $inv->reorder_level }}
                                        </td>
                                        <td class="text-center">
                                            @if($isOut)
                                                <span class="badge bg-danger">স্টক শেষ (Stockout)</span>
                                            @elseif($isLow)
                                                <span class="badge bg-warning text-dark">স্বল্প স্টক (Low)</span>
                                            @else
                                                <span class="badge bg-success">পর্যাপ্ত (In Stock)</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-xs btn-outline-primary fw-bold" data-bs-toggle="modal" data-bs-target="#adjustModal{{ $inv->id }}">
                                                <i class="ti-exchange-vertical me-1"></i> সমন্বয়
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Stock Adjustment Modal -->
                                    <div class="modal fade" id="adjustModal{{ $inv->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="POST" action="{{ route('admin.supershop.inventory.adjust', $inv->id) }}">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold">স্টক সমন্বয় – {{ $prod ? $prod->name : '' }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="alert alert-info py-2 small mb-3">
                                                            বর্তমান স্টক: <strong>{{ $inv->stock_quantity }}</strong> {{ $prod ? $prod->unit : '' }} | ব্রাঞ্চ: <strong>{{ $inv->branch ? $inv->branch->name : '' }}</strong>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">সমন্বয়ের ধরন (Adjustment Type) <span class="text-danger">*</span></label>
                                                            <select name="adjustment_type" class="form-select" required>
                                                                <option value="add">➕ স্টক যোগ করুন (Add to Stock)</option>
                                                                <option value="subtract">➖ স্টক হ্রাস করুন (Reduce/Damage/Loss)</option>
                                                                <option value="set">🔁 নির্দিষ্ট সংখ্যায় সেট করুন (Physical Count Match)</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">পরিমাণ (Quantity) <span class="text-danger">*</span></label>
                                                            <input type="number" name="quantity" class="form-control" min="0" required placeholder="পরিমাণ লিখুন">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">শেল্ফ অবস্থান (Shelf Location)</label>
                                                            <input type="text" name="shelf_location" class="form-control" value="{{ $inv->shelf_location }}" placeholder="যেমন: Aisle 3, Rack B2">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">রিঅর্ডার লেভেল (Reorder Level)</label>
                                                            <input type="number" name="reorder_level" class="form-control" value="{{ $inv->reorder_level }}" min="1">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">সমন্বয়ের কারণ (Reason / Audit Note) <span class="text-danger">*</span></label>
                                                            <input type="text" name="reason" class="form-control" placeholder="যেমন: ড্যামেজ পণ্য বাদ দেওয়া, ফিজিক্যাল অডিট সংশোধন" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                                                        <button type="submit" class="btn btn-primary fw-bold">স্টক আপডেট নিশ্চিত করুন</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">এই ব্রাঞ্চে কোনো ইনভেন্টরি তথ্য পাওয়া যায়নি।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($inventories->hasPages())
                    <div class="box-footer">
                        {{ $inventories->links() }}
                    </div>
                @endif
            </div>
        </section>

        <!-- Assign Product Modal -->
        <div class="modal fade" id="assignProductModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('admin.supershop.inventory.assign') }}">
                    @csrf
                    <input type="hidden" name="branch_id" value="{{ $selectedBranchId }}">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title fw-bold text-white"><i class="ti-plus me-1"></i> সেন্ট্রাল ক্যাটালগ থেকে ব্রাঞ্চে পণ্য যুক্ত করুন</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold">ক্যাটালগ পণ্য নির্বাচন করুন <span class="text-danger">*</span></label>
                                <select name="product_id" class="form-select" required>
                                    <option value="">-- পণ্য নির্বাচন করুন --</option>
                                    @foreach($unassignedProducts as $up)
                                        <option value="{{ $up->id }}">{{ $up->name }} (SKU: {{ $up->sku }}) - ৳{{ $up->price }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">প্রাথমিক স্টক পরিমাণ (Initial Stock) <span class="text-danger">*</span></label>
                                <input type="number" name="stock_quantity" class="form-control" value="50" min="0" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">রিঅর্ডার লেভেল (Reorder Alert)</label>
                                <input type="number" name="reorder_level" class="form-control" value="10" min="1">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">শেল্ফ অবস্থান (Shelf Location)</label>
                                <input type="text" name="shelf_location" class="form-control" placeholder="যেমন: Aisle 2, Rack C">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">শাখা বিক্রয়মূল্য (ঐচ্ছিক - ডিফল্ট ক্যাটালগ মূল্য ব্যবহৃত হবে)</label>
                                <input type="number" step="0.01" name="selling_price" class="form-control" placeholder="কাস্টম মূল্য না থাকলে খালি রাখুন">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                            <button type="submit" class="btn btn-success fw-bold">পণ্য যুক্ত করুন</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

