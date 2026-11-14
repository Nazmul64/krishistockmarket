@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1"><i class="ti-gift text-danger me-2"></i>কম্বো অফার ম্যানেজমেন্ট (Combo Offers)</h3>
                    <p class="text-muted mb-0">একাধিক পণ্য একত্রে বিশেষ প্যাকেজ মূল্যে গ্রাহকদের অফার করুন</p>
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
                <!-- Add Combo Offer Form -->
                <div class="col-lg-5 col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border">
                            <h4 class="box-title"><i class="ti-plus me-1 text-primary"></i>নতুন কম্বো অফার তৈরি করুন</h4>
                        </div>
                        <div class="box-body">
                            <form action="{{ route('admin.ecommerce.combo_offers.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">অফারের শিরোনাম <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" required placeholder="e.g. রমজান স্পেশাল বাজার প্যাকেজ">
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label fw-bold">নিয়মিত মূল্য (৳) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="regular_price" class="form-control" required placeholder="0.00">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-bold">অফার মূল্য (৳) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="offer_price" class="form-control" required placeholder="0.00">
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">প্যাকেজের সাথে পণ্যসমূহ নির্বাচন করুন</label>
                                    <select name="product_ids[]" class="form-select" multiple size="4">
                                        @foreach($products as $prod)
                                            <option value="{{ $prod->id }}">{{ $prod->name }} (৳{{ number_format($prod->price, 0) }})</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Ctrl চেপে ধরে একাধিক পণ্য সিলেক্ট করতে পারেন</small>
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

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">কম্বো অফার ছবি</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    <small class="text-muted">ফোল্ডার: <code>public/uploads/combo-offer/</code></small>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">অফারের সংক্ষিপ্ত বিবরণ</label>
                                    <textarea name="description" rows="3" class="form-control" placeholder="এই অফারের আওতায় কী কী পণ্য রয়েছে ও শর্তাবলী..."></textarea>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="chkActive" value="1" checked>
                                    <label class="form-check-label fw-bold" for="chkActive">অফারটি লাইভ সক্রিয় রাখুন</label>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti-save me-1"></i> কম্বো অফার প্রকাশ করুন
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Combo Offers List -->
                <div class="col-lg-7 col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border">
                            <h4 class="box-title"><i class="ti-list me-1 text-success"></i>বিদ্যমান কম্বো অফারসমূহ</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ছবি</th>
                                            <th>অফার নাম</th>
                                            <th>মূল্য</th>
                                            <th>মেয়াদ</th>
                                            <th>অবস্থা</th>
                                            <th class="text-end">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($combos as $c)
                                            <tr>
                                                <td>
                                                    @if($c->image)
                                                        <img src="{{ asset($c->image) }}" class="rounded border" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded bg-light border d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                                            <i class="ti-gift fs-4"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-dark">{{ $c->title }}</div>
                                                    @if(is_array($c->product_ids) && count($c->product_ids) > 0)
                                                        <span class="badge bg-light text-secondary border small">{{ count($c->product_ids) }} টি পণ্য সংযুক্ত</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-success">৳ {{ number_format($c->offer_price, 0) }}</div>
                                                    <div class="text-muted small text-decoration-line-through">৳ {{ number_format($c->regular_price, 0) }}</div>
                                                </td>
                                                <td>
                                                    <div class="small">
                                                        {{ $c->start_date ? date('d M Y', strtotime($c->start_date)) : 'N/A' }} 
                                                        @if($c->end_date)
                                                            <br>হতে {{ date('d M Y', strtotime($c->end_date)) }}
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $c->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $c->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <form action="{{ route('admin.ecommerce.combo_offers.destroy', $c->id) }}" method="POST" class="d-inline" onsubmit="return confirm('কম্বো অফারটি মুছে ফেলতে চান?')">
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
                                                <td colspan="6" class="text-center py-5 text-muted">
                                                    <i class="ti-gift fs-1 d-block mb-2 text-warning"></i>
                                                    কোনো কম্বো অফার পাওয়া যায়নি!
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

