@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1"><i class="ti-user text-primary me-2"></i>গ্রাহক তালিকা (Customer Management)</h3>
                    <p class="text-muted mb-0">অনলাইন শপের নিবন্ধিত গ্রাহক ও তাদের মোট অর্ডার হিস্ট্রি</p>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="box shadow-sm">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title fw-bold">নিবন্ধিত গ্রাহক (মোট: {{ $customers->total() }} জন)</h4>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>গ্রাহকের নাম ও ইমেইল</th>
                                    <th>মোবাইল নম্বর</th>
                                    <th>যোগদানের তারিখ</th>
                                    <th class="text-center">মোট অর্ডার</th>
                                    <th class="text-end">মোট কেনাকাটা (৳)</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $idx => $user)
                                    <tr>
                                        <td>{{ $customers->firstItem() + $idx }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $user->name }}</div>
                                                    <div class="text-muted small">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $user->phone ?: '-' }}</span>
                                        </td>
                                        <td>
                                            <div class="small text-muted">{{ $user->created_at->format('d M Y') }}</div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $user->shop_orders_count > 0 ? 'bg-primary' : 'bg-light text-dark border' }} fs-6">
                                                {{ $user->shop_orders_count }} টি
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold text-dark fs-6">
                                            ৳ {{ number_format($user->shop_orders_sum_total_amount ?? 0, 0) }}
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.ecommerce.orders', ['search' => $user->phone ?: $user->name]) }}" class="btn btn-sm btn-outline-primary" title="অর্ডার সমূহ দেখুন">
                                                <i class="ti-shopping-cart"></i> অর্ডার দেখুন
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            কোনো গ্রাহক পাওয়া যায়নি!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($customers->hasPages())
                    <div class="box-footer d-flex justify-content-end">
                        {{ $customers->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection

