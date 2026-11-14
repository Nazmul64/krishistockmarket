@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1"><i class="ti-credit-card text-primary me-2"></i>পেমেন্ট গেটওয়ে ও মেথড কনফিগারেশন</h3>
                    <p class="text-muted mb-0">ক্যাশ অন ডেলিভারি, বিকাশ, নগদ, রকেট ও ব্যাংক একাউন্ট নম্বর পরিচালনা</p>
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

            <div class="row g-4">
                @foreach($payments as $pay)
                    <div class="col-lg-6 col-12">
                        <div class="box shadow-sm border-top border-4 {{ $pay->is_active ? 'border-success' : 'border-secondary' }}">
                            <div class="box-header with-border d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    @if($pay->code === 'bkash')
                                        <span class="badge bg-danger fs-6 text-uppercase">bKash</span>
                                    @elseif($pay->code === 'nagad')
                                        <span class="badge bg-warning text-dark fs-6 text-uppercase">Nagad</span>
                                    @elseif($pay->code === 'rocket')
                                        <span class="badge bg-purple fs-6 text-uppercase" style="background: #8b5cf6; color: white;">Rocket</span>
                                    @elseif($pay->code === 'cod')
                                        <span class="badge bg-success fs-6 text-uppercase">Cash On Delivery</span>
                                    @else
                                        <span class="badge bg-primary fs-6 text-uppercase">{{ $pay->name }}</span>
                                    @endif
                                    <h4 class="box-title mb-0 fw-bold">{{ $pay->name }}</h4>
                                </div>
                                <span class="badge {{ $pay->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $pay->is_active ? 'সক্রিয় (Active)' : 'নিষ্ক্রিয় (Disabled)' }}
                                </span>
                            </div>
                            <div class="box-body">
                                <form action="{{ route('admin.ecommerce.payments.update', $pay->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">ডিসপ্লে নাম</label>
                                        <input type="text" name="name" class="form-control" value="{{ $pay->name }}" required>
                                    </div>

                                    @if($pay->code !== 'cod')
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-bold">মার্চেন্ট / পার্সোনাল একাউন্ট নম্বর</label>
                                            <input type="text" name="merchant_number" class="form-control" value="{{ $pay->merchant_number }}" placeholder="যেমন: 01805020340 (মার্চেন্ট/পার্সোনাল)">
                                        </div>
                                    @endif

                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">গ্রাহকের জন্য পেমেন্ট নির্দেশিকা (Instructions)</label>
                                        <textarea name="instructions" rows="3" class="form-control" placeholder="চেকআউট পেজে কাস্টমারকে কীভাবে টাকা পাঠাতে হবে তার নির্দেশনা">{{ $pay->instructions }}</textarea>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">গেটের লোগো পরিবর্তন (ঐচ্ছিক)</label>
                                        @if($pay->logo)
                                            <div class="mb-2">
                                                <img src="{{ asset($pay->logo) }}" style="max-height: 40px; object-fit: contain;">
                                            </div>
                                        @endif
                                        <input type="file" name="logo" class="form-control" accept="image/*">
                                        <small class="text-muted">ফোল্ডার: <code>public/uploads/payment/</code></small>
                                    </div>

                                    @if(in_array($pay->code, ['bkash', 'nagad']))
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label class="form-label small fw-bold">API Key (PGW লাগলে)</label>
                                                <input type="text" name="api_key" class="form-control form-control-sm" value="{{ $pay->api_key }}">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-bold">Secret Key</label>
                                                <input type="password" name="secret_key" class="form-control form-control-sm" value="{{ $pay->secret_key }}">
                                            </div>
                                        </div>
                                    @endif

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="chkActive_{{ $pay->id }}" value="1" {{ $pay->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="chkActive_{{ $pay->id }}">এই পেমেন্ট মেথড চেকআউটে প্রদর্শন করুন</label>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="ti-save me-1"></i> কনফিগারেশন সেভ করুন
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</div>
@endsection

