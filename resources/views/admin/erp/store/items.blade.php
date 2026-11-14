@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <!-- Breadcrumb & Header -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-boxes text-primary me-2"></i> স্টোর ইনভেন্টরি ও আইটেম ক্যাটালগ</h4>
                    <p class="text-muted small mb-0">স্টোরের মালামাল, ক্যাটাগরি, একক, মজুত সংখ্যা, ক্রয়মূল্য ও রিঅর্ডার লেভেল ব্যবস্থাপনা।</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addItemModal">
                        <i class="fa fa-plus me-1"></i> নতুন আইটেম যোগ করুন
                    </button>
                    <a href="{{ route('admin.erp.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ড
                    </a>
                </div>
            </div>

            <!-- Summary KPI Badges -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-primary">
                        <small class="text-muted text-uppercase fw-bold font-size-11">মোট আইটেম</small>
                        <h4 class="fw-bold text-dark my-1">{{ number_format($totalItemsCount) }} টি</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-info">
                        <small class="text-muted text-uppercase fw-bold font-size-11">মোট মজুত সংখ্যা</small>
                        <h4 class="fw-bold text-info my-1">{{ number_format($totalStockQty) }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-success">
                        <small class="text-muted text-uppercase fw-bold font-size-11">স্টক মূল্যায়ন (ক্রয়মূল্য)</small>
                        <h4 class="fw-bold text-success my-1">৳ {{ number_format($totalValuation, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-danger">
                        <small class="text-muted text-uppercase fw-bold font-size-11">লো স্টক সতর্কতা</small>
                        <h4 class="fw-bold text-danger my-1">{{ number_format($lowStockCount) }} টি</h4>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Filter & Search Bar -->
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
                <form action="{{ route('admin.erp.store.items') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="আইটেম নাম, কোড বা SKU দিয়ে খুঁজুন..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="category_id" class="form-select form-select-sm">
                            <option value="">-- সকল ক্যাটাগরি --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name_bn ?? $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="warehouse_id" class="form-select form-select-sm">
                            <option value="">-- সকল গুদাম --</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>{{ $wh->name_bn ?? $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary rounded-pill w-100 fw-bold">ফিল্টার</button>
                        <a href="{{ route('admin.erp.store.items') }}" class="btn btn-sm btn-light rounded-pill">রিসেট</a>
                    </div>
                </form>
            </div>

            <!-- Items Table -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>কোড</th>
                                <th>আইটেম নাম</th>
                                <th>ক্যাটাগরি</th>
                                <th>ট্র্যাকিং ধরন</th>
                                <th>একক</th>
                                <th>ক্রয়মূল্য</th>
                                <th>বিক্রয়মূল্য</th>
                                <th>বর্তমান মজুত</th>
                                <th>গুদাম</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                            <tr>
                                <td class="fw-bold text-primary">{{ $item->code }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($item->image)
                                            <img src="{{ asset($item->image) }}" class="rounded-3 border" style="width: 35px; height: 35px; object-fit: cover;">
                                        @else
                                            <div class="rounded-3 bg-light border d-flex align-items-center justify-content-center text-muted" style="width: 35px; height: 35px;">
                                                <i class="fa fa-cube"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <span class="fw-bold text-dark d-block">{{ $item->name_bn ?? $item->name }}</span>
                                            <small class="text-muted">{{ $item->brand ?? 'General' }} | {{ $item->name }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ optional($item->category)->name_bn ?? optional($item->category)->name }}</td>
                                <td>
                                    @if($item->tracking_type === 'quantity')
                                        <span class="badge bg-info bg-opacity-20 text-info">Quantity-Based</span>
                                    @elseif($item->tracking_type === 'asset')
                                        <span class="badge bg-warning bg-opacity-20 text-warning">Fixed Asset Tracking</span>
                                    @elseif($item->tracking_type === 'serial')
                                        <span class="badge bg-primary bg-opacity-20 text-primary">Serial-Based</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $item->tracking_type }}</span>
                                    @endif
                                </td>
                                <td>{{ optional($item->unit)->symbol ?? 'PCS' }}</td>
                                <td class="fw-bold">৳ {{ number_format($item->purchase_price, 2) }}</td>
                                <td>৳ {{ number_format($item->selling_price, 2) }}</td>
                                <td>
                                    @if($item->current_stock <= 0)
                                        <span class="badge bg-dark">0 (Stock Out)</span>
                                    @elseif($item->isLowStock())
                                        <span class="badge bg-danger">{{ number_format($item->current_stock) }} (Low)</span>
                                    @else
                                        <span class="badge bg-success">{{ number_format($item->current_stock) }}</span>
                                    @endif
                                </td>
                                <td>{{ optional($item->defaultWarehouse)->name_bn ?? optional($item->defaultWarehouse)->name ?? 'Central Store' }}</td>
                                <td class="text-end">
                                    <button class="btn btn-xs btn-outline-primary rounded-pill px-2" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Edit Item Modal -->
                            <div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold text-dark"><i class="fa fa-edit text-primary me-2"></i> আইটেম তথ্য সম্পাদন: {{ $item->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.erp.store.items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold small">আইটেম নাম (ইংরেজি) *</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold small">আইটেম নাম (বাংলা)</label>
                                                        <input type="text" name="name_bn" class="form-control" value="{{ $item->name_bn }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold small">ক্যাটাগরি *</label>
                                                        <select name="category_id" class="form-select" required>
                                                            @foreach($categories as $cat)
                                                                <option value="{{ $cat->id }}" {{ $item->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name_bn ?? $cat->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold small">পরিমাপের একক (Unit) *</label>
                                                        <select name="unit_id" class="form-select" required>
                                                            @foreach($units as $u)
                                                                <option value="{{ $u->id }}" {{ $item->unit_id == $u->id ? 'selected' : '' }}>{{ $u->symbol }} ({{ $u->name }})</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold small">ডিফল্ট গুদাম</label>
                                                        <select name="default_warehouse_id" class="form-select">
                                                            @foreach($warehouses as $wh)
                                                                <option value="{{ $wh->id }}" {{ $item->default_warehouse_id == $wh->id ? 'selected' : '' }}>{{ $wh->name_bn ?? $wh->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold small">ক্রয়মূল্য (৳)</label>
                                                        <input type="number" step="0.01" name="purchase_price" class="form-control" value="{{ $item->purchase_price }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold small">বিক্রয়মূল্য (৳)</label>
                                                        <input type="number" step="0.01" name="selling_price" class="form-control" value="{{ $item->selling_price }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold small">রিঅর্ডার লেভেল</label>
                                                        <input type="number" name="reorder_level" class="form-control" value="{{ $item->reorder_level }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold small">ব্র্যান্ড ও মডেল</label>
                                                        <input type="text" name="brand" class="form-control" value="{{ $item->brand }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold small">সরবরাহকারী (Supplier)</label>
                                                        <input type="text" name="supplier_name" class="form-control" value="{{ $item->supplier_name }}">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label fw-bold small">ছবির ফাইল পরিবর্তন</label>
                                                        <input type="file" name="image" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">বন্ধ করুন</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">আপডেট করুন</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">কোনো আইটেম পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $items->links() }}
                </div>
            </div>

        </section>
    </div>
</div>

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="fa fa-plus-circle text-primary me-2"></i> নতুন ইনভেন্টরি আইটেম যোগ করুন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.erp.store.items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">আইটেম নাম (ইংরেজি) *</label>
                            <input type="text" name="name" class="form-control" placeholder="उदा: Double A A4 Paper 80GSM" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">আইটেম নাম (বাংলা)</label>
                            <input type="text" name="name_bn" class="form-control" placeholder="उदा: ডাবল এ এ৪ ফটোকপি পেপার">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">আইটেম কোড (ঐচ্ছিক)</label>
                            <input type="text" name="code" class="form-control" placeholder="ফাঁকা রাখলে অটো তৈরি হবে">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">ক্যাটাগরি *</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- নির্বাচন করুন --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name_bn ?? $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">পরিমাপের একক (Unit) *</label>
                            <select name="unit_id" class="form-select" required>
                                <option value="">-- নির্বাচন করুন --</option>
                                @foreach($units as $u)
                                    <option value="{{ $u->id }}">{{ $u->symbol }} ({{ $u->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">ট্র্যাকিং ধরন (Tracking Type) *</label>
                            <select name="tracking_type" class="form-select" required>
                                <option value="quantity">পরিমাণভিত্তিক (Quantity-Based: কলম, কাগজ, ইত্যাদি)</option>
                                <option value="asset">ব্যক্তিগত সম্পদ (Individual Asset: ল্যাপটপ, এসি, চেয়ার)</option>
                                <option value="serial">সিরিয়াল ভিত্তিক (Serial-Based)</option>
                                <option value="batch">ব্যাচ ভিত্তিক (Batch-Based)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">ডিফল্ট গুদাম (Warehouse)</label>
                            <select name="default_warehouse_id" class="form-select">
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->id }}">{{ $wh->name_bn ?? $wh->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">প্রারম্ভিক স্টক (Opening Stock)</label>
                            <input type="number" step="0.01" name="current_stock" class="form-control" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">একক ক্রয়মূল্য (৳) *</label>
                            <input type="number" step="0.01" name="purchase_price" class="form-control" value="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">একক বিক্রয়মূল্য (৳)</label>
                            <input type="number" step="0.01" name="selling_price" class="form-control" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">রিঅর্ডার সতর্কতা লেভেল</label>
                            <input type="number" name="reorder_level" class="form-control" value="10">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">ব্র্যান্ড ও মডেল</label>
                            <input type="text" name="brand" class="form-control" placeholder="उदा: HP, Hatil, Matador">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">সরবরাহকারী (Supplier)</label>
                            <input type="text" name="supplier_name" class="form-control" placeholder="সরবরাহকারীর নাম">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold small">আইটেম ছবি</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold small">বর্ণনা ও স্পেসিফিকেশন</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

