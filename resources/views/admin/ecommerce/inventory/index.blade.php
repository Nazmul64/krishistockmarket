@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1"><i class="ti-archive text-warning me-2"></i>স্টক ও ইনভেন্টরি ম্যানেজমেন্ট</h3>
                    <p class="text-muted mb-0">পণ্য মজুদ তদারকি, দ্রুত স্টক আপডেট এবং লো-স্টক সতর্কতা</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.ecommerce.products.create') }}" class="btn btn-primary">
                        <i class="ti-plus me-1"></i> নতুন পণ্য যোগ
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

            <!-- Quick Filter Tabs -->
            <div class="row g-3 mb-4">
                <div class="col-md-4 col-12">
                    <a href="{{ route('admin.ecommerce.inventory') }}" class="card border-0 shadow-sm text-decoration-none {{ !request('filter') ? 'border-primary border-2' : '' }}">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small">সকল পণ্য স্টক</div>
                                <h4 class="fw-bold mb-0 text-dark">ইনভেন্টরি তালিকা</h4>
                            </div>
                            <div class="bg-primary-subtle text-primary p-3 rounded-circle fs-4">
                                <i class="ti-package"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-4 col-12">
                    <a href="{{ route('admin.ecommerce.inventory', ['filter' => 'low']) }}" class="card border-0 shadow-sm text-decoration-none {{ request('filter') === 'low' ? 'border-warning border-2' : '' }}">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small">স্বল্প স্টক সতর্কতা (১-১০ টি)</div>
                                <h4 class="fw-bold mb-0 text-warning">লো স্টক ফিল্টার</h4>
                            </div>
                            <div class="bg-warning-subtle text-warning p-3 rounded-circle fs-4">
                                <i class="ti-alert"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-4 col-12">
                    <a href="{{ route('admin.ecommerce.inventory', ['filter' => 'out']) }}" class="card border-0 shadow-sm text-decoration-none {{ request('filter') === 'out' ? 'border-danger border-2' : '' }}">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small">স্টক শেষ (০ টি মজুদ)</div>
                                <h4 class="fw-bold mb-0 text-danger">স্টক-আউট পণ্য</h4>
                            </div>
                            <div class="bg-danger-subtle text-danger p-3 rounded-circle fs-4">
                                <i class="ti-close"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Search Box -->
            <div class="box shadow-sm mb-4">
                <div class="box-body">
                    <form action="{{ route('admin.ecommerce.inventory') }}" method="GET" class="row g-2">
                        @if(request('filter'))
                            <input type="hidden" name="filter" value="{{ request('filter') }}">
                        @endif
                        <div class="col-md-10 col-12">
                            <input type="text" name="search" class="form-control" placeholder="পণ্যের নাম অথবা SKU দিয়ে সার্চ করুন..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2 col-12">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti-search me-1"></i> অনুসন্ধান
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Inventory Table -->
            <div class="box shadow-sm">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title fw-bold">স্টক তালিকা (মোট: {{ $products->total() }} টি)</h4>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;">ছবি</th>
                                    <th>পণ্য ও SKU</th>
                                    <th>ক্যাটাগরি</th>
                                    <th>মূল্য</th>
                                    <th>স্টক অবস্থা</th>
                                    <th style="width: 200px;">বর্তমান স্টক (তাত্ক্ষণিক এডিট)</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $p)
                                    <tr>
                                        <td>
                                            @if($p->image)
                                                <img src="{{ asset($p->image) }}" class="rounded border" style="width: 48px; height: 48px; object-fit: contain;">
                                            @else
                                                <div class="rounded bg-light border d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                    <i class="ti-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.ecommerce.products.edit', $p->id) }}" class="fw-bold text-dark text-decoration-none">
                                                {{ $p->name }}
                                            </a>
                                            <div class="text-muted small">SKU: <code>{{ $p->sku }}</code> | {{ $p->unit }}</div>
                                        </td>
                                        <td>{{ optional($p->category)->name ?: '-' }}</td>
                                        <td class="fw-bold">৳ {{ number_format($p->price, 0) }}</td>
                                        <td>
                                            @if($p->stock_quantity <= 0)
                                                <span class="badge bg-danger">স্টক আউট</span>
                                            @elseif($p->stock_quantity <= 10)
                                                <span class="badge bg-warning text-dark">স্বল্প স্টক (Low)</span>
                                            @else
                                                <span class="badge bg-success">পর্যাপ্ত স্টক</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <input type="number" 
                                                       class="form-control text-center fw-bold quick-stock-input" 
                                                       data-id="{{ $p->id }}" 
                                                       value="{{ $p->stock_quantity }}" 
                                                       min="0">
                                                <button type="button" class="btn btn-success btn-save-stock" data-id="{{ $p->id }}" title="স্টক সেভ করুন">
                                                    <i class="ti-save me-1"></i> সেভ
                                                </button>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.ecommerce.products.edit', $p->id) }}" class="btn btn-sm btn-outline-primary" title="সম্পাদনা">
                                                <i class="ti-pencil"></i> এডিট
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            কোনো পণ্য পাওয়া যায়নি!
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
            const originalText = this.innerHTML;

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
                this.innerHTML = '<i class="ti-check text-white"></i> সেভ হয়েছে';
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 1200);
            })
            .catch(err => {
                alert('স্টক আপডেট করতে ব্যর্থ হয়েছে');
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    });
});
</script>
@endsection

