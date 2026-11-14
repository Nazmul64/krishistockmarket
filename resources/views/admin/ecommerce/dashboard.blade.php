@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header & Breadcrumbs -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1"><i class="ti-shopping-cart text-warning me-2"></i>ই-কমার্স ড্যাশবোর্ড (E-commerce Overview)</h3>
                    <p class="text-muted mb-0">অনলাইন শপের সম্পূর্ণ সেলস, ইনভেন্টরি, অর্ডার ও কাস্টমার ম্যানেজমেন্ট</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.ecommerce.products.create') }}" class="btn btn-sm btn-primary">
                        <i class="ti-plus me-1"></i> নতুন পণ্য যোগ করুন
                    </a>
                    <a href="{{ route('products.index') }}" target="_blank" class="btn btn-sm btn-outline-info">
                        <i class="ti-new-window me-1"></i> লাইভ শপ দেখুন
                    </a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- 1. Top Stat Cards -->
            <div class="row">
                <!-- Total Sales -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box bg-gradient-primary text-white shadow-sm">
                        <div class="box-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="fw-bold mb-1 text-white">৳ {{ number_format($metrics['total_sales'], 0) }}</h4>
                                    <div class="text-white-50">মোট বিক্রয় (Total Sales)</div>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle p-3 fs-3">
                                    <i class="ti-money"></i>
                                </div>
                            </div>
                            <div class="mt-3 pt-2 border-top border-white border-opacity-25 small d-flex justify-content-between">
                                <span>আজকের বিক্রয়:</span>
                                <strong>৳ {{ number_format($metrics['today_sales'], 0) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box bg-gradient-success text-white shadow-sm">
                        <div class="box-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="fw-bold mb-1 text-white">{{ $metrics['total_orders'] }} টি</h4>
                                    <div class="text-white-50">মোট অর্ডার (Total Orders)</div>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle p-3 fs-3">
                                    <i class="ti-receipt"></i>
                                </div>
                            </div>
                            <div class="mt-3 pt-2 border-top border-white border-opacity-25 small d-flex justify-content-between">
                                <span>পেন্ডিং অর্ডার:</span>
                                <span class="badge bg-warning text-dark">{{ $metrics['pending_orders'] }} টি</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Products -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box bg-gradient-info text-white shadow-sm">
                        <div class="box-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="fw-bold mb-1 text-white">{{ $metrics['total_products'] }} টি</h4>
                                    <div class="text-white-50">মোট প্রোডাক্ট (Products)</div>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle p-3 fs-3">
                                    <i class="ti-package"></i>
                                </div>
                            </div>
                            <div class="mt-3 pt-2 border-top border-white border-opacity-25 small d-flex justify-content-between">
                                <span>সক্রিয় পণ্য:</span>
                                <strong>{{ $metrics['active_products'] }} টি</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Low Stock Alert -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box bg-gradient-danger text-white shadow-sm">
                        <div class="box-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="fw-bold mb-1 text-white">{{ $metrics['low_stock_count'] }} টি</h4>
                                    <div class="text-white-50">লো স্টক অ্যালার্ট (Low Stock)</div>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle p-3 fs-3">
                                    <i class="ti-alert"></i>
                                </div>
                            </div>
                            <div class="mt-3 pt-2 border-top border-white border-opacity-25 small d-flex justify-content-between">
                                <span>স্টক আউট:</span>
                                <strong>{{ $metrics['out_of_stock_count'] }} টি</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Order Lifecycle Pills -->
            <div class="box shadow-sm mb-4">
                <div class="box-body py-3">
                    <div class="row text-center g-2">
                        <div class="col-md-2 col-4">
                            <a href="{{ route('admin.ecommerce.orders', ['status' => 'pending']) }}" class="text-decoration-none">
                                <div class="p-2 rounded bg-light border border-warning">
                                    <div class="text-warning fs-4 fw-bold">{{ $metrics['pending_orders'] }}</div>
                                    <div class="small text-muted fw-bold">১. পেন্ডিং (Pending)</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-4">
                            <a href="{{ route('admin.ecommerce.orders', ['status' => 'processing']) }}" class="text-decoration-none">
                                <div class="p-2 rounded bg-light border border-primary">
                                    <div class="text-primary fs-4 fw-bold">{{ $metrics['processing_orders'] }}</div>
                                    <div class="small text-muted fw-bold">২. প্রসেসিং (Processing)</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-4">
                            <a href="{{ route('admin.ecommerce.orders', ['status' => 'shipping']) }}" class="text-decoration-none">
                                <div class="p-2 rounded bg-light border border-info">
                                    <div class="text-info fs-4 fw-bold">{{ $metrics['shipping_orders'] }}</div>
                                    <div class="small text-muted fw-bold">৩. শিপিং (Shipping)</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-4">
                            <a href="{{ route('admin.ecommerce.orders', ['status' => 'delivered']) }}" class="text-decoration-none">
                                <div class="p-2 rounded bg-light border border-success">
                                    <div class="text-success fs-4 fw-bold">{{ $metrics['delivered_orders'] }}</div>
                                    <div class="small text-muted fw-bold">৪. ডেলিভার্ড (Delivered)</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-4">
                            <a href="{{ route('admin.ecommerce.orders', ['status' => 'completed']) }}" class="text-decoration-none">
                                <div class="p-2 rounded bg-light border border-success">
                                    <div class="text-success fs-4 fw-bold">{{ $metrics['completed_orders'] }}</div>
                                    <div class="small text-muted fw-bold">৫. সম্পন্ন (Completed)</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-4">
                            <a href="{{ route('admin.ecommerce.orders', ['status' => 'cancelled']) }}" class="text-decoration-none">
                                <div class="p-2 rounded bg-light border border-danger">
                                    <div class="text-danger fs-4 fw-bold">{{ $metrics['cancelled_orders'] }}</div>
                                    <div class="small text-muted fw-bold">বাতিল (Cancelled)</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Recent Orders & Quick Stock Alerts -->
            <div class="row">
                <!-- Recent Orders Table -->
                <div class="col-lg-8 col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border d-flex justify-content-between align-items-center">
                            <h4 class="box-title"><i class="ti-time text-primary me-2"></i>সাম্প্রতিক অর্ডারসমূহ (Recent Orders)</h4>
                            <a href="{{ route('admin.ecommerce.orders') }}" class="btn btn-sm btn-outline-primary">সব অর্ডার দেখুন</a>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>অর্ডার নং</th>
                                            <th>কাস্টমার</th>
                                            <th>মোট মূল্য</th>
                                            <th>পেমেন্ট</th>
                                            <th>স্ট্যাটাস</th>
                                            <th class="text-end">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentOrders as $ord)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.ecommerce.orders.show', $ord->id) }}" class="fw-bold text-primary">
                                                        {{ $ord->order_number }}
                                                    </a>
                                                    <div class="text-muted small">{{ $ord->created_at->format('d M Y, h:i A') }}</div>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-dark">{{ $ord->first_name }} {{ $ord->last_name }}</div>
                                                    <div class="text-muted small">{{ $ord->phone }}</div>
                                                </td>
                                                <td>
                                                    <strong class="text-dark">৳ {{ number_format($ord->total_amount, 0) }}</strong>
                                                    <div class="small text-muted">{{ $ord->items->count() }} টি পণ্য</div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border text-uppercase">{{ $ord->payment_method }}</span>
                                                    <div class="small {{ $ord->payment_status == 'paid' ? 'text-success' : 'text-danger' }}">
                                                        {{ ucfirst($ord->payment_status) }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($ord->order_status == 'pending')
                                                        <span class="badge bg-warning text-dark">পেন্ডিং</span>
                                                    @elseif($ord->order_status == 'processing')
                                                        <span class="badge bg-primary">প্রসেসিং</span>
                                                    @elseif($ord->order_status == 'shipping')
                                                        <span class="badge bg-info">শিপিং</span>
                                                    @elseif($ord->order_status == 'delivered')
                                                        <span class="badge bg-success">ডেলিভার্ড</span>
                                                    @elseif($ord->order_status == 'completed')
                                                        <span class="badge bg-success">সম্পন্ন</span>
                                                    @else
                                                        <span class="badge bg-danger">বাতিল</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('admin.ecommerce.orders.show', $ord->id) }}" class="btn btn-xs btn-outline-info" title="View Details">
                                                        <i class="ti-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.ecommerce.orders.invoice', $ord->id) }}" target="_blank" class="btn btn-xs btn-outline-secondary" title="Invoice">
                                                        <i class="ti-printer"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">কোনো অর্ডার পাওয়া যায়নি।</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Low Stock Products -->
                <div class="col-lg-4 col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border d-flex justify-content-between align-items-center">
                            <h4 class="box-title text-danger"><i class="ti-alert me-2"></i>লো স্টক প্রোডাক্ট</h4>
                            <a href="{{ route('admin.ecommerce.inventory', ['filter' => 'low']) }}" class="btn btn-sm btn-outline-danger">সব দেখুন</a>
                        </div>
                        <div class="box-body p-0">
                            <ul class="list-group list-group-flush">
                                @forelse($lowStockProducts as $low)
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $low->image ? asset($low->image) : 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=100' }}" style="width: 40px; height: 40px; object-fit: contain; border-radius: 4px; border: 1px solid #e2e8f0;">
                                            <div>
                                                <a href="{{ route('admin.ecommerce.products.edit', $low->id) }}" class="fw-bold text-dark text-decoration-none small d-block" title="{{ $low->name }}">
                                                    {{ Str::limit($low->name, 22) }}
                                                </a>
                                                <span class="text-muted small">৳ {{ number_format($low->price, 0) }}</span>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge {{ $low->stock_quantity <= 0 ? 'bg-danger' : 'bg-warning text-dark' }}">
                                                {{ $low->stock_quantity }} {{ $low->unit }}
                                            </span>
                                        </div>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center py-4 text-muted">লো স্টকের কোনো পণ্য নেই।</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

