@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title">Deposit Management (ডিপোজিট ভেরিফিকেশন ও অ্যাপ্রুভাল)</h4>
                    <p class="text-muted mb-0">গ্রাহকদের ডিপোজিট রিকোয়েস্টসমূহ যাচাই করে অ্যাপ্রুভ বা রিজেক্ট করুন</p>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            @if(session('success') || session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') ?? session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-money me-2 text-success"></i> সকল ডিপোজিট রিকোয়েস্ট ({{ count($deposits) }})</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>SN.</th>
                                            <th class="text-center">গ্রাহকের আইডি ও কার্ড নাম্বার</th>
                                            <th class="text-center">ডিপোজিট পরিমাণ (৳)</th>
                                            <th class="text-center">পেমেন্ট মেথড</th>
                                            <th class="text-center">প্রেরক নাম্বার & Trx ID</th>
                                            <th class="text-center">স্ক্রিনশট</th>
                                            <th class="text-center">তারিখ</th>
                                            <th class="text-center">স্ট্যাটাস ও অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($deposits as $key => $item)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary mb-1">ID: #{{ $item->user_id }}</span><br>
                                                    <span class="badge bg-dark mb-1" style="font-family: monospace;"><i class="fa fa-credit-card me-1"></i>Card: {{ $item->card_number ?? GetUserCardNumber($item->user_id) }}</span><br>
                                                    <strong>{{ $item->user->name ?? 'N/A' }}</strong><br>
                                                    <small class="text-muted"><i class="fa fa-phone me-1"></i>{{ $item->user->phone ?? $item->user->username ?? 'N/A' }}</small>
                                                </td>
                                                <td class="text-center fw-bold text-success fs-6">৳{{ number_format($item->deposit_amount, 2) }}</td>
                                                <td class="text-center"><span class="badge bg-info">{{ $item->payment_method }}</span></td>
                                                <td class="text-center">
                                                    <small class="d-block text-dark">From: <strong>{{ $item->pay_from_number ?? 'N/A' }}</strong></small>
                                                    <small class="text-muted">Trx: <code class="text-dark fw-bold">{{ $item->trx_number ?? 'N/A' }}</code></small>
                                                </td>
                                                <td class="text-center">
                                                    @if(!empty($item->screenshot) && file_exists(public_path('upload/payment/'.$item->screenshot)))
                                                        <a href="{{ asset('upload/payment/'.$item->screenshot) }}" target="_blank">
                                                            <img src="{{ asset('upload/payment/'.$item->screenshot) }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #ccc;">
                                                        </a>
                                                    @else
                                                        <span class="text-muted small">No Image</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y h:i A') }}</td>
                                                <td class="text-center">
                                                    @if($item->status == 'pending')
                                                        <a href="{{ route('admin.deposit.approve', $item->id) }}" class="btn btn-success btn-xs me-1 mb-1"><i class="fa fa-check me-1"></i>Approve</a>
                                                        <a href="{{ route('admin.deposit.reject', $item->id) }}" class="btn btn-danger btn-xs mb-1" onclick="return confirm('ডিপোজিট রিজেক্ট করতে চান?')"><i class="fa fa-times me-1"></i>Reject</a>
                                                    @elseif($item->status == 'approved')
                                                        <span class="badge bg-success py-2 px-3"><i class="fa fa-check-circle me-1"></i>Approved</span>
                                                    @else
                                                        <span class="badge bg-danger py-2 px-3"><i class="fa fa-times-circle me-1"></i>Rejected</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">কোনো ডিপোজিট রিকোয়েস্ট পাওয়া যায়নি।</td>
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
