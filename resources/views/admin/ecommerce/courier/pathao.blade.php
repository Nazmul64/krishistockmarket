@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Breadcrumb & Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="fa fa-paper-plane text-primary me-2"></i>Pathao courier setup
                    </h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 py-0" style="background: transparent;">
                            <li class="breadcrumb-item"><a href="{{ route('admin.ecommerce.dashboard') }}"><i class="fa fa-home"></i> Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.ecommerce.orders') }}">Courier</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pathao</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.ecommerce.courier.steadfast') }}" class="btn btn-outline-success btn-sm">
                        <i class="fa fa-truck me-1"></i> Steadfast Courier Setup
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
                    <div class="box shadow-sm" style="border-radius: 12px; border-top: 4px solid #0284c7;">
                        <div class="box-header bg-white py-3 border-bottom">
                            <h4 class="box-title fw-bold text-dark mb-0">Pathao courier setup</h4>
                        </div>
                        <div class="box-body p-4">
                            <form action="{{ route('admin.ecommerce.courier.pathao.update') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-dark">Client ID <span class="text-danger">*</span></label>
                                        <input type="text" name="client_id" class="form-control" value="{{ $settings['pathao_client_id'] ?? '' }}" placeholder="Pathao Client ID..." required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-dark">Client Secret <span class="text-danger">*</span></label>
                                        <input type="text" name="client_secret" class="form-control" value="{{ $settings['pathao_client_secret'] ?? '' }}" placeholder="Pathao Client Secret..." required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-dark">Username / Email <span class="text-danger">*</span></label>
                                        <input type="text" name="username" class="form-control" value="{{ $settings['pathao_username'] ?? '' }}" placeholder="Merchant Email..." required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-dark">Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control" value="{{ $settings['pathao_password'] ?? '' }}" placeholder="Merchant Password..." required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-dark">Store ID <span class="text-danger">*</span></label>
                                        <input type="text" name="store_id" class="form-control" value="{{ $settings['pathao_store_id'] ?? '' }}" placeholder="Store ID (e.g. 12345)" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-dark">URL <span class="text-danger">*</span></label>
                                        <input type="url" name="url" class="form-control" value="{{ $settings['pathao_url'] ?? 'https://api-hermes.pathao.com' }}" required>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check form-switch fs-6 mt-2">
                                            <input class="form-check-input" type="checkbox" name="status" id="pathaoStatus" value="1" {{ ($settings['pathao_status'] ?? 'active') === 'active' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold text-dark ms-2" for="pathaoStatus">
                                                Status (Active)
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary fw-bold px-4 py-2" style="background-color: #0284c7; border-color: #0284c7;">
                                            Submit
                                        </button>
                                    </div>
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

