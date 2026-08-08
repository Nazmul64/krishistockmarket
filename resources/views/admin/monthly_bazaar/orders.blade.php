@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title">Monthly Grocery Orders (মাসিক বাজার রিকোয়েস্ট তালিকা)</h4>
                    <p class="text-muted mb-0">গ্রাহকদের মাসিক বাজার অর্ডার রিকোয়েস্ট পর্যালোচনা ও অ্যাপ্রুভ করুন</p>
                </div>
                <div>
                    <a href="{{ route('admin.monthly_bazaar.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-list me-1"></i> প্যাকেজ তালিকা</a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h3 class="box-title text-dark"><i class="fa fa-shopping-basket me-2 text-success"></i> সকল রিকোয়েস্ট ({{ count($orders) }})</h3>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>SN.</th>
                                            <th class="text-center">গ্রাহকের আইডি ও কাস্টমার কার্ড নাম্বার</th>
                                            <th class="text-center">মাসিক বাজার প্যাকেজ</th>
                                            <th class="text-center">মূল্য ও পরিমাণ</th>
                                            <th class="text-center">পেমেন্ট মেথড ও ট্রানজেকশন</th>
                                            <th class="text-center">স্ক্রিনশট</th>
                                            <th class="text-center">তারিখ</th>
                                            <th class="text-center">স্ট্যাটাস ও অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($orders as $key => $item)
                                            @php
                                                $cardNumber = GetUserCardNumber($item->user_id);
                                            @endphp
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary mb-1">User ID: #{{ $item->user_id }}</span><br>
                                                    <span class="badge bg-dark mb-1" style="font-family: monospace; font-size: 12px; letter-spacing: 1px;"><i class="fa fa-credit-card me-1"></i>Card: {{ $cardNumber }}</span><br>
                                                    <strong>{{ $item->user->name ?? 'N/A' }}</strong><br>
                                                    <small class="text-muted"><i class="fa fa-phone me-1"></i>{{ $item->user->phone ?? $item->user->username ?? 'N/A' }}</small>
                                                </td>
                                                <td class="text-center">
                                                    <strong class="text-dark">{{ $item->package_title }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <span class="fw-bold text-success">৳{{ number_format($item->total_price, 2) }}</span><br>
                                                    <small class="text-muted">Qty: {{ $item->quantity }} টি</small>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-info mb-1">{{ $item->payment_method }}</span><br>
                                                    @if($item->pay_from_number)
                                                        <small>From: <strong>{{ $item->pay_from_number }}</strong></small><br>
                                                    @endif
                                                    @if($item->trx_number)
                                                        <small>Trx: <code class="text-dark fw-bold">{{ $item->trx_number }}</code></small>
                                                    @endif
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
                                                        <a href="{{ route('admin.monthly_bazaar.order.approve', $item->id) }}"
                                                            class="waves-effect waves-light btn btn-success btn-xs mb-1"><i class="fa fa-check me-1"></i>Approve</a>
                                                        <a href="{{ route('admin.monthly_bazaar.order.reject', $item->id) }}"
                                                            class="waves-effect waves-light btn btn-danger btn-xs mb-1" onclick="return confirm('রিকোয়েস্টটি রিজেক্ট করতে চান?')"><i class="fa fa-times me-1"></i>Reject</a>
                                                    @elseif($item->status == 'approved')
                                                        <span class="badge bg-success py-2 px-3"><i class="fa fa-check-circle me-1"></i>Approved</span>
                                                    @else
                                                        <span class="badge bg-danger py-2 px-3"><i class="fa fa-times-circle me-1"></i>Rejected</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">কোনো মাসিক বাজার রিকোয়েস্ট পাওয়া যায়নি।</td>
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
