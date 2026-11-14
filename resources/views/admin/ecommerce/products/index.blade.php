@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1"><i class="ti-package text-primary me-2"></i>প্রোডাক্ট ক্যাটালগ ম্যানেজমেন্ট</h3>
                    <p class="text-muted mb-0">সকল সুপারশপ প্রোডাক্ট, স্টক, ভ্যারিয়েন্ট ও কালেকশন নিয়ন্ত্রণ করুন</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.ecommerce.products.create') }}" class="btn btn-primary">
                        <i class="ti-plus me-1"></i> নতুন পণ্য যোগ করুন
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Filter & Search Box -->
            <div class="box shadow-sm mb-4">
                <div class="box-body">
                    <form action="{{ route('admin.ecommerce.products') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-4 col-12">
                            <label class="form-label small fw-bold text-muted">নাম অথবা SKU দিয়ে খুঁজুন</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti-search"></i></span>
                                <input type="text" name="search" class="form-control" placeholder="Search by name, SKU..." value="{{ request('search') }}">
                            </div>
                        </div>

                        <div class="col-md-3 col-6">
                            <label class="form-label small fw-bold text-muted">ক্যাটাগরি ফিল্টার</label>
                            <select name="category_id" class="form-select">
                                <option value="">-- সকল ক্যাটাগরি --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 col-6">
                            <label class="form-label small fw-bold text-muted">স্টক অবস্থা</label>
                            <select name="stock_status" class="form-select">
                                <option value="">-- সকল স্টক --</option>
                                <option value="in" {{ request('stock_status') == 'in' ? 'selected' : '' }}>পর্যাপ্ত স্টক (> 10)</option>
                                <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>স্বল্প স্টক (১-১০ টি)</option>
                                <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>স্টক আউট (০ টি)</option>
                            </select>
                        </div>

                        <div class="col-md-2 col-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti-filter me-1"></i> ফিল্টার
                            </button>
                            <a href="{{ route('admin.ecommerce.products') }}" class="btn btn-light" title="রিসেট">
                                <i class="ti-reload"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Table -->
            <div class="box shadow-sm">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title fw-bold">পণ্য তালিকা (মোট: {{ $products->total() }} টি)</h4>
                    <span class="text-muted small">পৃষ্ঠা {{ $products->currentPage() }} এর {{ $products->lastPage() }}</span>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 70px;">ছবি</th>
                                    <th>পণ্যের বিবরণ</th>
                                    <th>ক্যাটাগরি / ব্র্যান্ড</th>
                                    <th>বিক্রয় মূল্য</th>
                                    <th style="width: 140px;">স্টক পরিমাণ</th>
                                    <th>কালেকশন ট্যাগ</th>
                                    <th>অবস্থা</th>
                                    <th class="text-end" style="width: 130px;">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $prod)
                                    <tr>
                                        <td>
                                            @if($prod->image)
                                                <img src="{{ asset($prod->image) }}" alt="{{ $prod->name }}" class="rounded border" style="width: 54px; height: 54px; object-fit: contain; background: #f8fafc;">
                                            @else
                                                <div class="rounded bg-light border d-flex align-items-center justify-content-center text-muted" style="width: 54px; height: 54px;">
                                                    <i class="ti-image fs-4"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.ecommerce.products.edit', $prod->id) }}" class="fw-bold text-dark text-decoration-none">
                                                {{ $prod->name }}
                                            </a>
                                            @if($prod->name_bn)
                                                <div class="text-muted small">{{ $prod->name_bn }}</div>
                                            @endif
                                            <div class="small mt-1">
                                                <span class="badge bg-light text-secondary border">SKU: {{ $prod->sku }}</span>
                                                @if($prod->has_variants)
                                                    <span class="badge bg-info-subtle text-info border border-info ms-1">
                                                        <i class="ti-layers"></i> {{ $prod->variants->count() }} ভ্যারিয়েন্ট
                                                    </span>
                                                @endif
                                                <span class="text-muted ms-1">({{ $prod->unit ?: '1 Pc' }})</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-primary">{{ optional($prod->category)->name ?: 'N/A' }}</div>
                                            @if($prod->subcategory)
                                                <div class="text-muted small"><i class="ti-angle-right small"></i> {{ $prod->subcategory->name }}</div>
                                            @endif
                                            @if($prod->brand)
                                                <div class="badge bg-secondary-subtle text-secondary border mt-1">{{ $prod->brand }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark fs-6">৳ {{ number_format($prod->price, 0) }}</div>
                                            @if($prod->old_price && $prod->old_price > $prod->price)
                                                <div class="text-decoration-line-through text-muted small">৳ {{ number_format($prod->old_price, 0) }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1">
                                                <input type="number" 
                                                       class="form-control form-control-sm text-center fw-bold quick-stock-input" 
                                                       data-id="{{ $prod->id }}" 
                                                       value="{{ $prod->stock_quantity }}" 
                                                       style="width: 70px;">
                                                <button type="button" class="btn btn-sm btn-outline-success p-1 px-2 btn-save-stock" data-id="{{ $prod->id }}" title="স্টক সেভ করুন">
                                                    <i class="ti-check"></i>
                                                </button>
                                            </div>
                                            <div class="mt-1">
                                                @if($prod->stock_quantity <= 0)
                                                    <span class="badge bg-danger">স্টক আউট</span>
                                                @elseif($prod->stock_quantity <= 10)
                                                    <span class="badge bg-warning text-dark">স্বল্প স্টক ({{ $prod->stock_quantity }})</span>
                                                @else
                                                    <span class="badge bg-success-subtle text-success">ইন স্টক ({{ $prod->stock_quantity }})</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1" style="max-width: 220px;">
                                                @if($prod->is_exclusive)<span class="badge bg-warning text-dark small">Exclusive</span>@endif
                                                @if($prod->is_featured)<span class="badge bg-info small">Featured</span>@endif
                                                @if($prod->is_new_arrival)<span class="badge bg-primary small">New Arrival</span>@endif
                                                @if($prod->is_trending)<span class="badge bg-danger small">Trending</span>@endif
                                                @if($prod->is_popular)<span class="badge bg-secondary small">Popular</span>@endif
                                                @if($prod->is_best_selling)<span class="badge bg-success small">Best Seller</span>@endif
                                                @if($prod->is_winter_essential)<span class="badge bg-info-subtle text-info border small">Winter</span>@endif
                                                @if($prod->is_monthly_bazar)<span class="badge bg-success-subtle text-success border small">Bazar</span>@endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($prod->is_active)
                                                <span class="badge bg-success">সক্রিয়</span>
                                            @else
                                                <span class="badge bg-secondary">নিষ্ক্রিয়</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-1">
                                                <a href="{{ route('products.show', $prod->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="লাইভ দেখুন">
                                                    <i class="ti-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.ecommerce.products.edit', $prod->id) }}" class="btn btn-sm btn-outline-primary" title="সম্পাদনা">
                                                    <i class="ti-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.ecommerce.products.destroy', $prod->id) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই পণ্যটি মুছে ফেলতে চান?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="মুছে ফেলুন">
                                                        <i class="ti-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <i class="ti-package fs-1 d-block mb-2 text-warning"></i>
                                            কোনো পণ্য পাওয়া যায়নি! "নতুন পণ্য যোগ করুন" বাটনে ক্লিক করে পণ্য তৈরি করুন।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($products->hasPages())
                    <div class="box-footer d-flex justify-content-end">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const stockButtons = document.querySelectorAll('.btn-save-stock');
    stockButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const input = document.querySelector(`.quick-stock-input[data-id="${id}"]`);
            const stockVal = input ? input.value : 0;
            const originalIcon = this.innerHTML;

            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
            this.disabled = true;

            fetch(`{{ url('admin/ecommerce/products') }}/${id}/quick-stock`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ stock: stockVal })
            })
            .then(res => res.json())
            .then(data => {
                this.innerHTML = '<i class="ti-check text-success"></i>';
                setTimeout(() => {
                    this.innerHTML = originalIcon;
                    this.disabled = false;
                }, 1200);
            })
            .catch(err => {
                alert('স্টক আপডেট করতে ব্যর্থ হয়েছে');
                this.innerHTML = originalIcon;
                this.disabled = false;
            });
        });
    });
});
</script>
@endsection

