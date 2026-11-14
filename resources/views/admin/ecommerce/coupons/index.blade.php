@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1"><i class="ti-ticket text-info me-2"></i>কুপন ও ডিসকাউন্ট ম্যানেজমেন্ট</h3>
                    <p class="text-muted mb-0">প্রমোশনাল কুপন কোড, ডিসকাউন্ট শতাংশ এবং বিশেষ ছাড় কনফিগারেশন</p>
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

            <div class="row">
                <!-- Add Coupon Form -->
                <div class="col-lg-4 col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border">
                            <h4 class="box-title"><i class="ti-plus me-1 text-primary"></i>নতুন কুপন যোগ করুন</h4>
                        </div>
                        <div class="box-body">
                            <form action="{{ route('admin.ecommerce.coupons.store') }}" method="POST">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">কুপন কোড <span class="text-danger">*</span></label>
                                    <input type="text" name="code" class="form-control text-uppercase" required placeholder="e.g. EID50, ASMI10, SAVE100">
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label fw-bold">ডিসকাউন্ট টাইপ</label>
                                        <select name="discount_type" class="form-select">
                                            <option value="percentage">শতাংশ (%)</option>
                                            <option value="fixed">নির্দিষ্ট টাকা (৳)</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-bold">পরিমাণ <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="discount_amount" class="form-control" required placeholder="e.g. 10 or 100">
                                    </div>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label fw-bold">সর্বনিম্ন অর্ডার মূল্য (৳)</label>
                                        <input type="number" step="0.01" name="min_order_amount" class="form-control" placeholder="0.00" value="0">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-bold">সর্বোচ্চ ছাড় (৳)</label>
                                        <input type="number" step="0.01" name="max_discount" class="form-control" placeholder="সীমাহীন হলে ফাঁকা">
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">মোট ব্যবহার সীমা (Usage Limit)</label>
                                    <input type="number" name="usage_limit" class="form-control" value="100">
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label fw-bold">শুরুর তারিখ</label>
                                        <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-bold">শেষের তারিখ</label>
                                        <input type="date" name="end_date" class="form-control">
                                    </div>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="chkActive" value="1" checked>
                                    <label class="form-check-label fw-bold" for="chkActive">কুপনটি সক্রিয় রাখুন</label>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti-save me-1"></i> কুপন তৈরি করুন
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Coupons List -->
                <div class="col-lg-8 col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border">
                            <h4 class="box-title"><i class="ti-list me-1 text-success"></i>বিদ্যমান কুপনসমূহ</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>কুপন কোড</th>
                                            <th>ডিসকাউন্ট</th>
                                            <th>সর্বনিম্ন অর্ডার</th>
                                            <th>ব্যবহার হয়েছে</th>
                                            <th>মেয়াদ</th>
                                            <th>অবস্থা</th>
                                            <th class="text-end">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($coupons as $cp)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-primary fs-6 px-3 py-2 text-uppercase font-monospace">{{ $cp->code }}</span>
                                                </td>
                                                <td>
                                                    @if($cp->discount_type === 'percentage')
                                                        <span class="fw-bold text-success">{{ $cp->discount_amount }}% ছাড়</span>
                                                        @if($cp->max_discount)
                                                            <div class="small text-muted">(সর্বোচ্চ ৳{{ number_format($cp->max_discount, 0) }})</div>
                                                        @endif
                                                    @else
                                                        <span class="fw-bold text-success">৳ {{ number_format($cp->discount_amount, 0) }} ছাড়</span>
                                                    @endif
                                                </td>
                                                <td>৳ {{ number_format($cp->min_order_amount, 0) }}</td>
                                                <td>
                                                    <span class="badge bg-light text-dark border">{{ $cp->used_count }} / {{ $cp->usage_limit ?: '∞' }}</span>
                                                </td>
                                                <td>
                                                    <div class="small">
                                                        {{ $cp->start_date ? date('d M Y', strtotime($cp->start_date)) : 'N/A' }}
                                                        @if($cp->end_date)
                                                            <br>হতে {{ date('d M Y', strtotime($cp->end_date)) }}
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $cp->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $cp->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <form action="{{ route('admin.ecommerce.coupons.destroy', $cp->id) }}" method="POST" class="d-inline" onsubmit="return confirm('কুপনটি মুছে ফেলতে চান?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="মুছে ফেলুন">
                                                            <i class="ti-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-5 text-muted">
                                                    কোনো কুপন পাওয়া যায়নি!
                                                </td>
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

