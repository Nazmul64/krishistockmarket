@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title">আমার মাসিক বাজার অর্ডারসমূহ (My Monthly Grocery Orders)</h4>
                    <p class="text-muted mb-0">আপনার মাসিক বাজার খাদ্য প্যাকেজ অর্ডারের বর্তমান অবস্থা</p>
                </div>
                <div>
                    <a href="{{ route('user.monthly_bazaar.index') }}" class="btn btn-success btn-sm"><i class="fa fa-shopping-basket me-1"></i> নতুন অর্ডার করুন</a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h3 class="box-title text-dark"><i class="fa fa-history me-2 text-info"></i> অর্ডার তালিকা ({{ count($orders) }})</h3>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>SN.</th>
                                            <th>মাসিক বাজার প্যাকেজ</th>
                                            <th class="text-center">একক মূল্য</th>
                                            <th class="text-center">পরিমাণ</th>
                                            <th class="text-center">সর্বমোট মূল্য</th>
                                            <th class="text-center">পেমেন্ট মেথড</th>
                                            <th class="text-center">তারিখ</th>
                                            <th class="text-center">স্ট্যাটাস</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($orders as $key => $item)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>
                                                    <strong class="text-dark">{{ $item->package_title }}</strong>
                                                </td>
                                                <td class="text-center">৳{{ number_format($item->price, 2) }}</td>
                                                <td class="text-center">{{ $item->quantity }} টি</td>
                                                <td class="text-center fw-bold text-success">৳{{ number_format($item->total_price, 2) }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-info">{{ $item->payment_method }}</span><br>
                                                    @if($item->trx_number)
                                                        <small class="text-muted">Trx: <code>{{ $item->trx_number }}</code></small>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y h:i A') }}</td>
                                                <td class="text-center">
                                                    @if($item->status == 'pending')
                                                        <span class="badge bg-warning text-dark py-2 px-3"><i class="fa fa-clock-o me-1"></i>Pending</span>
                                                    @elseif($item->status == 'approved')
                                                        <span class="badge bg-success py-2 px-3"><i class="fa fa-check-circle me-1"></i>Approved</span>
                                                    @else
                                                        <span class="badge bg-danger py-2 px-3"><i class="fa fa-times-circle me-1"></i>Rejected</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">আপনার কোনো মাসিক বাজার অর্ডার রিকোয়েস্ট নেই।</td>
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
