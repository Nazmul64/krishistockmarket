@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1"><i class="ti-truck text-success me-2"></i>শিপিং ও ডেলিভারি জোন (Shipping Management)</h3>
                    <p class="text-muted mb-0">ডেলিভারি এরিয়া, ফ্রি শিপিং রুলস এবং ডেলিভারি চার্জ কনফিগারেশন</p>
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
                <!-- Add Shipping Zone Form -->
                <div class="col-lg-4 col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border">
                            <h4 class="box-title"><i class="ti-plus me-1 text-primary"></i>নতুন শিপিং জোন যোগ করুন</h4>
                        </div>
                        <div class="box-body">
                            <form action="{{ route('admin.ecommerce.shipping.store') }}" method="POST">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">জোনের নাম <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required placeholder="e.g. Free Shipping (Dhaka City Only)">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ডেলিভারি চার্জ (৳) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text fw-bold">৳</span>
                                        <input type="number" step="0.01" name="charge" class="form-control" required placeholder="0.00">
                                    </div>
                                    <small class="text-muted">ফ্রি শিপিং হলে 0 দিন</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">আনুমানিক সময়</label>
                                    <input type="text" name="estimated_time" class="form-control" value="১-২ কার্যদিবস" placeholder="যেমন: ১-২ কার্যদিবস">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">সর্বনিম্ন অর্ডার মূল্য (ঐচ্ছিক)</label>
                                    <input type="number" step="0.01" name="min_order_amount" class="form-control" placeholder="0.00">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ফ্রি শিপিং যোগ্যতা মূল্য (৳)</label>
                                    <input type="number" step="0.01" name="free_shipping_min_amount" class="form-control" placeholder="যেমন: ১০০০ টাকার বেশি অর্ডারে ফ্রি">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ক্রম নম্বর (Order)</label>
                                    <input type="number" name="order_num" class="form-control" value="0">
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="chkActive" value="1" checked>
                                    <label class="form-check-label fw-bold" for="chkActive">জোনটি সক্রিয় রাখুন</label>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti-save me-1"></i> জোন সংরক্ষণ করুন
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Shipping Zones Table -->
                <div class="col-lg-8 col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border">
                            <h4 class="box-title"><i class="ti-list me-1 text-success"></i>বিদ্যমান শিপিং জোনসমূহ</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>জোনের নাম</th>
                                            <th>চার্জ (৳)</th>
                                            <th>ডেলিভারি সময়</th>
                                            <th>ফ্রি শিপিং শর্ত</th>
                                            <th>অবস্থা</th>
                                            <th class="text-end">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($zones as $z)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold text-dark">{{ $z->name }}</div>
                                                    <div class="text-muted small">সিরিয়াল: {{ $z->order_num }}</div>
                                                </td>
                                                <td>
                                                    @if($z->charge == 0)
                                                        <span class="badge bg-success-subtle text-success fs-6 border border-success">ফ্রি শিপিং (৳০)</span>
                                                    @else
                                                        <span class="fw-bold text-dark fs-6">৳ {{ number_format($z->charge, 0) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $z->estimated_time }}</td>
                                                <td>
                                                    @if($z->free_shipping_min_amount)
                                                        <span class="badge bg-info-subtle text-info">৳{{ number_format($z->free_shipping_min_amount, 0) }}+ অর্ডারে ফ্রি</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge {{ $z->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $z->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <button type="button" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editZoneModal_{{ $z->id }}">
                                                        <i class="ti-pencil"></i>
                                                    </button>
                                                    <form action="{{ route('admin.ecommerce.shipping.destroy', $z->id) }}" method="POST" class="d-inline" onsubmit="return confirm('শিপিং জোনটি মুছে ফেলতে চান?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="মুছে ফেলুন">
                                                            <i class="ti-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>

                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="editZoneModal_{{ $z->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('admin.ecommerce.shipping.update', $z->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-header">
                                                                <h5 class="modal-title fw-bold">শিপিং জোন সম্পাদনা</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group mb-3">
                                                                    <label class="form-label fw-bold">জোনের নাম <span class="text-danger">*</span></label>
                                                                    <input type="text" name="name" class="form-control" required value="{{ $z->name }}">
                                                                </div>
                                                                <div class="form-group mb-3">
                                                                    <label class="form-label fw-bold">ডেলিভারি চার্জ (৳) <span class="text-danger">*</span></label>
                                                                    <input type="number" step="0.01" name="charge" class="form-control" required value="{{ $z->charge }}">
                                                                </div>
                                                                <div class="form-group mb-3">
                                                                    <label class="form-label fw-bold">আনুমানিক সময়</label>
                                                                    <input type="text" name="estimated_time" class="form-control" value="{{ $z->estimated_time }}">
                                                                </div>
                                                                <div class="form-group mb-3">
                                                                    <label class="form-label fw-bold">ফ্রি শিপিং যোগ্যতা মূল্য (৳)</label>
                                                                    <input type="number" step="0.01" name="free_shipping_min_amount" class="form-control" value="{{ $z->free_shipping_min_amount }}">
                                                                </div>
                                                                <div class="form-group mb-3">
                                                                    <label class="form-label fw-bold">ক্রম নম্বর</label>
                                                                    <input type="number" name="order_num" class="form-control" value="{{ $z->order_num }}">
                                                                </div>
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" name="is_active" id="chkActiveModal_{{ $z->id }}" value="1" {{ $z->is_active ? 'checked' : '' }}>
                                                                    <label class="form-check-label fw-bold" for="chkActiveModal_{{ $z->id }}">সক্রিয় রাখুন</label>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                                                                <button type="submit" class="btn btn-primary">আপডেট করুন</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-5 text-muted">
                                                    কোনো শিপিং জোন পাওয়া যায়নি!
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

