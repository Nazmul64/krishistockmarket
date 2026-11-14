@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1"><i class="ti-ruler-pencil text-primary me-2"></i>প্রোডাক্ট অ্যাট্রিবিউট ও ভ্যারিয়েন্ট (Attributes & Values)</h3>
                    <p class="text-muted mb-0">সাইজ (Size), কালার (Color), ওজন (Weight) ইত্যাদি অ্যাট্রিবিউট ও ভ্যালু কনফিগার করুন</p>
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
                <!-- Add Attribute Form -->
                <div class="col-lg-4 col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border">
                            <h4 class="box-title"><i class="ti-plus me-1 text-primary"></i>নতুন অ্যাট্রিবিউট যোগ করুন</h4>
                        </div>
                        <div class="box-body">
                            <form action="{{ route('admin.ecommerce.attributes.store') }}" method="POST">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">অ্যাট্রিবিউট নাম <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required placeholder="e.g. Size, Color, Weight, Pack">
                                    <small class="text-muted">উদাহরণ: Size (সাইজ), Color (কালার), Weight (ওজন)</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ডিসপ্লে টাইপ</label>
                                    <select name="type" class="form-select">
                                        <option value="button">Button / Text Badge (যেমন: S, M, L, XL)</option>
                                        <option value="color">Color Swatch (রঙিন পিকার)</option>
                                        <option value="dropdown">Dropdown Select</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti-save me-1"></i> অ্যাট্রিবিউট সংরক্ষণ করুন
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Add Value Form -->
                    <div class="box shadow-sm">
                        <div class="box-header with-border">
                            <h4 class="box-title"><i class="ti-tag me-1 text-success"></i>অ্যাট্রিবিউটের নতুন মান (Value) যোগ</h4>
                        </div>
                        <div class="box-body">
                            <form action="{{ route('admin.ecommerce.attributes.values.store') }}" method="POST">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">অ্যাট্রিবিউট সিলেক্ট করুন <span class="text-danger">*</span></label>
                                    <select name="attribute_id" class="form-select" required>
                                        <option value="">-- নির্বাচন করুন --</option>
                                        @foreach($attributes as $att)
                                            <option value="{{ $att->id }}">{{ $att->name }} ({{ $att->type }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ভ্যালু / নাম <span class="text-danger">*</span></label>
                                    <input type="text" name="value" class="form-control" required placeholder="e.g. XL, 1 Kg, Red, 500ml">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">কালার কোড (শুধুমাত্র কালারের জন্য)</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color" id="colorPicker" value="#0d6efd" onchange="document.getElementById('colorHex').value=this.value">
                                        <input type="text" name="color_code" id="colorHex" class="form-control" placeholder="#0d6efd">
                                    </div>
                                    <small class="text-muted">Color Swatch প্রদর্শনের জন্য হেক্স কোড</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ক্রম নম্বর (Order)</label>
                                    <input type="number" name="order_num" class="form-control" value="0">
                                </div>

                                <button type="submit" class="btn btn-success w-100">
                                    <i class="ti-plus me-1"></i> ভ্যালু যোগ করুন
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Attributes & Values List -->
                <div class="col-lg-8 col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border d-flex justify-content-between align-items-center">
                            <h4 class="box-title"><i class="ti-list me-1 text-info"></i>বিদ্যমান অ্যাট্রিবিউট ও ভ্যালুসমূহ</h4>
                            <span class="badge bg-primary fs-6">{{ $attributes->count() }} টি অ্যাট্রিবিউট</span>
                        </div>
                        <div class="box-body">
                            @if($attributes->isEmpty())
                                <div class="text-center py-5 text-muted">
                                    <i class="ti-alert fs-1 d-block mb-2 text-warning"></i>
                                    কোনো অ্যাট্রিবিউট পাওয়া যায়নি! বামপাশের ফর্ম থেকে সাইজ বা কালার যোগ করুন।
                                </div>
                            @else
                                <div class="row g-3">
                                    @foreach($attributes as $attr)
                                        <div class="col-12">
                                            <div class="card border rounded-3 p-3">
                                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                                                    <div>
                                                        <h5 class="fw-bold mb-0 text-dark">
                                                            <i class="ti-control-record text-primary me-1"></i> {{ $attr->name }}
                                                            <span class="badge bg-light text-dark border ms-2 small">{{ ucfirst($attr->type) }}</span>
                                                        </h5>
                                                    </div>
                                                    <div>
                                                        <form action="{{ route('admin.ecommerce.attributes.destroy', $attr->id) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি এই অ্যাট্রিবিউট এবং এর সমস্ত ভ্যালু মুছে ফেলতে চান?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="ti-trash"></i> ডিলিট
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>

                                                <div>
                                                    <div class="fw-semibold text-muted small mb-2">সংযুক্ত ভ্যালুসমূহ ({{ $attr->values->count() }}টি):</div>
                                                    @if($attr->values->isEmpty())
                                                        <span class="text-muted small fst-italic">কোনো ভ্যালু যোগ করা হয়নি। বামপাশের ফর্ম থেকে ভ্যালু যোগ করুন।</span>
                                                    @else
                                                        <div class="d-flex flex-wrap gap-2">
                                                            @foreach($attr->values as $val)
                                                                <div class="badge bg-white border text-dark p-2 d-flex align-items-center gap-2 shadow-sm rounded-pill">
                                                                    @if($val->color_code)
                                                                        <span class="rounded-circle d-inline-block border" style="width: 14px; height: 14px; background-color: {{ $val->color_code }};"></span>
                                                                    @endif
                                                                    <span class="fw-bold">{{ $val->value }}</span>
                                                                    <form action="{{ route('admin.ecommerce.attributes.values.destroy', $val->id) }}" method="POST" class="d-inline" onsubmit="return confirm('এই মানটি মুছে ফেলতে চান?')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-link p-0 text-danger border-0" style="font-size: 11px;" title="ডিলিট">
                                                                            <i class="fa fa-times"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

