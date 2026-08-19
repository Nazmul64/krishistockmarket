@extends('layouts.backend.app')

@section('content')
<div class="container-full">
    <div class="content-header">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h3 class="page-title">নতুন পণ্য সরবরাহ পোস্টিং / ইনভয়েস এন্ট্রি</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('supplier.dashboard') }}"><i class="mdi mdi-home-outline"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('supplier.supplies.index') }}">পণ্য তালিকা</a></li>
                    <li class="breadcrumb-item active">নতুন এন্ট্রি</li>
                </ol>
            </div>
            <div>
                <a href="{{ route('supplier.supplies.index') }}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i> ফিরে যান</a>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-md-10 offset-md-1 col-12">
                <div class="box">
                    <div class="box-header with-border bg-primary">
                        <h4 class="box-title text-white">পণ্যের সরবরাহ ও ইনভয়েস তথ্য প্রদান করুন</h4>
                    </div>
                    <form action="{{ route('supplier.supplies.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6 col-12 mb-3">
                                    <label class="form-label">ইনভয়েস / চালান নম্বর <span class="text-danger">*</span></label>
                                    <input type="text" name="invoice_no" class="form-control" placeholder="যেমন: INV-2026-0012" value="{{ old('invoice_no') }}" required>
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <label class="form-label">সরবরাহের তারিখ <span class="text-danger">*</span></label>
                                    <input type="date" name="supply_date" class="form-control" value="{{ old('supply_date', date('Y-m-d')) }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-12 mb-3">
                                    <label class="form-label">পণ্যের নাম <span class="text-danger">*</span></label>
                                    <input type="text" name="product_name" class="form-control" placeholder="যেমন: চাল, চিনি, আটা, ডাল" value="{{ old('product_name') }}" required>
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <label class="form-label">পণ্যের ক্যাটাগরি (ঐচ্ছিক)</label>
                                    <input type="text" name="category" class="form-control" placeholder="যেমন: খাদ্যদ্রব্য / শস্য" value="{{ old('category') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-12 mb-3">
                                    <label class="form-label">পরিমাণ (Quantity) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="quantity" id="quantityInput" class="form-control" placeholder="যেমন: 1, 5, 10" value="{{ old('quantity') }}" required oninput="calculateTotal()">
                                </div>
                                <div class="col-md-4 col-12 mb-3">
                                    <label class="form-label">একক / ইউনিট (Unit) <span class="text-danger">*</span></label>
                                    <select name="unit" class="form-control" required>
                                        <option value="Metric Ton (MT)">Metric Ton (মেট্রিক টন)</option>
                                        <option value="KG">KG (কেজি)</option>
                                        <option value="Bag">Bag (বস্তা)</option>
                                        <option value="Piece">Piece (পিস/সংখ্যা)</option>
                                        <option value="Litre">Litre (লিটার)</option>
                                        <option value="Mon">Mon (মন)</option>
                                    </select>
                                </div>
                                <div class="col-md-4 col-12 mb-3">
                                    <label class="form-label">প্রতি ইউনিটের রেট (Rate ৳) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="rate" id="rateInput" class="form-control" placeholder="0.00" value="{{ old('rate') }}" required oninput="calculateTotal()">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-12 mb-3">
                                    <label class="form-label">মোট টাকা (Auto Total Amount ৳)</label>
                                    <input type="text" id="totalAmountDisplay" class="form-control bg-light fw-bold text-success fs-16" readonly value="৳0.00">
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <label class="form-label">চালানের ছবি বা PDF আপলোড (Image/PDF File)</label>
                                    <input type="file" name="invoice_file" class="form-control" accept="image/*,application/pdf">
                                    <small class="text-muted">JPG, PNG, PDF ফাইল সাপোর্টেড (সর্বোচ্চ 5MB)</small>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">অতিরিক্ত নোট / বিবরণ</label>
                                <textarea name="note" class="form-control" rows="3" placeholder="পণ্যের ব্র্যান্ড, ডেলিভারি কন্ডিশন বা কোনো বিশেষ মন্তব্য থাকলে লিখুন">{{ old('note') }}</textarea>
                            </div>
                        </div>

                        <div class="box-footer text-end">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fa fa-paper-plane me-1"></i> পোস্ট করুন (Submit Supply)
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function calculateTotal() {
        var qty = parseFloat(document.getElementById('quantityInput').value) || 0;
        var rate = parseFloat(document.getElementById('rateInput').value) || 0;
        var total = qty * rate;
        document.getElementById('totalAmountDisplay').value = '৳' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
</script>
@endsection
