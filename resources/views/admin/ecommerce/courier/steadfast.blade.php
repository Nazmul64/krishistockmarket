@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Breadcrumb & Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="fa fa-truck text-success me-2"></i>Steadfast courier setup
                    </h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 py-0" style="background: transparent;">
                            <li class="breadcrumb-item"><a href="{{ route('admin.ecommerce.dashboard') }}"><i class="fa fa-home"></i> Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.ecommerce.orders') }}">Courier</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Steadfast</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.ecommerce.courier.pathao') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-paper-plane me-1"></i> Pathao Courier Setup
                    </a>
                    <a href="{{ route('admin.ecommerce.orders') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> Back to Orders
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-8 col-md-10 col-12 mx-auto">
                    <div class="box shadow-sm" style="border-radius: 12px; border-top: 4px solid #10b981;">
                        <div class="box-header bg-white py-3 border-bottom">
                            <h4 class="box-title fw-bold text-dark mb-0">Steadfast courier setup</h4>
                        </div>
                        <div class="box-body p-4">
                            <form action="{{ route('admin.ecommerce.courier.steadfast.update') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark">
                                        API key <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="api_key" class="form-control" placeholder="Steadfast API Key..." value="{{ $settings['steadfast_api_key'] ?? '' }}" required>
                                    <div class="form-text text-muted">Steadfast Courier portal থেকে প্রাপ্ত আপনার API Key টি দিন।</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark">
                                        Secret key <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="secret_key" class="form-control" placeholder="Steadfast Secret Key..." value="{{ $settings['steadfast_secret_key'] ?? '' }}" required>
                                    <div class="form-text text-muted">Steadfast Courier portal থেকে প্রাপ্ত আপনার Secret Key টি দিন।</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark">
                                        URL <span class="text-danger">*</span>
                                    </label>
                                    <input type="url" name="url" class="form-control" value="{{ $settings['steadfast_url'] ?? 'https://portal.packzy.com/api/v1' }}" required>
                                    <div class="form-text text-muted">ডিফল্ট API এন্ডপয়েন্ট: <code>https://portal.packzy.com/api/v1</code></div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check form-switch fs-6">
                                        <input class="form-check-input" type="checkbox" name="status" id="courierStatus" value="1" {{ ($settings['steadfast_status'] ?? 'active') === 'active' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold text-dark ms-2" for="courierStatus">
                                            Status (Active)
                                        </label>
                                    </div>
                                    <div class="form-text text-muted">সক্রিয় রাখলে অর্ডার লিস্ট থেকে সরাসরি ১-ক্লিকে Steadfast-এ পার্সেল পাঠানো যাবে।</div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success fw-bold px-4 py-2" style="background-color: #10b981; border-color: #10b981;">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

