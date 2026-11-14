@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1"><i class="fa fa-shopping-bag text-success me-2"></i>অর্ডার ম্যানেজমেন্ট (Order Management)</h3>
                    <p class="text-muted mb-0">সকল কাস্টমার অর্ডার, ডেলিভারি ট্র্যাকিং, কুরিয়ার ডিসপ্যাচ ও ইনভয়েস পরিচালনা</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#addOrderModal">
                        <i class="fa fa-plus-circle me-1"></i> Add Order
                    </button>
                    <a href="{{ route('admin.ecommerce.courier.steadfast') }}" class="btn btn-outline-success">
                        <i class="fa fa-truck me-1"></i> Steadfast Setup
                    </a>
                    <a href="{{ route('admin.ecommerce.courier.pathao') }}" class="btn btn-outline-primary">
                        <i class="fa fa-paper-plane me-1"></i> Pathao Setup
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
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- 4 Metric Cards (Image 4 Reference) -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box shadow-sm mb-0 border-start border-warning border-4" style="border-radius: 10px;">
                        <div class="box-body d-flex align-items-center justify-content-between p-3">
                            <div>
                                <div class="text-muted fw-semibold small text-uppercase">Total Pending Entry</div>
                                <h2 class="fw-bold mb-0 text-dark">{{ $counts['pending_entry'] ?? 0 }}</h2>
                                <span class="text-warning small"><i class="fa fa-clock-o me-1"></i>নতুন অপেক্ষমান অর্ডার</span>
                            </div>
                            <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                                <i class="fa fa-hourglass-half fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box shadow-sm mb-0 border-start border-success border-4" style="border-radius: 10px;">
                        <div class="box-body d-flex align-items-center justify-content-between p-3">
                            <div>
                                <div class="text-muted fw-semibold small text-uppercase">Total Delivered</div>
                                <h2 class="fw-bold mb-0 text-dark">{{ $counts['delivered'] ?? 0 }}</h2>
                                <span class="text-success small"><i class="fa fa-check-circle me-1"></i>সফলভাবে ডেলিভার্ড</span>
                            </div>
                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                                <i class="fa fa-check fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box shadow-sm mb-0 border-start border-info border-4" style="border-radius: 10px;">
                        <div class="box-body d-flex align-items-center justify-content-between p-3">
                            <div>
                                <div class="text-muted fw-semibold small text-uppercase">Total Pending Payment</div>
                                <h2 class="fw-bold mb-0 text-dark">{{ $counts['pending_payment'] ?? 0 }}</h2>
                                <span class="text-info small"><i class="fa fa-credit-card me-1"></i>বকেয়া / COD পেমেন্ট</span>
                            </div>
                            <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                                <i class="fa fa-money fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box shadow-sm mb-0 border-start border-danger border-4" style="border-radius: 10px;">
                        <div class="box-body d-flex align-items-center justify-content-between p-3">
                            <div>
                                <div class="text-muted fw-semibold small text-uppercase">Total Canceled</div>
                                <h2 class="fw-bold mb-0 text-dark">{{ $counts['canceled'] ?? 0 }}</h2>
                                <span class="text-danger small"><i class="fa fa-ban me-1"></i>বাতিলকৃত অর্ডার</span>
                            </div>
                            <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                                <i class="fa fa-times fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Status Pills -->
            <div class="d-flex flex-wrap gap-2 mb-3">
                <a href="{{ route('admin.ecommerce.orders') }}" class="btn btn-sm {{ !request('status') || request('status') === 'all' ? 'btn-dark' : 'btn-light border' }}">
                    All Orders <span class="badge bg-secondary ms-1">{{ $counts['all'] }}</span>
                </a>
                <a href="{{ route('admin.ecommerce.orders', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning text-dark fw-bold' : 'btn-light border' }}">
                    Pending <span class="badge bg-warning text-dark ms-1">{{ $counts['pending_entry'] }}</span>
                </a>
                <a href="{{ route('admin.ecommerce.orders', ['status' => 'processing']) }}" class="btn btn-sm {{ request('status') === 'processing' ? 'btn-info text-white' : 'btn-light border' }}">
                    Processing <span class="badge bg-info text-white ms-1">{{ $counts['processing'] }}</span>
                </a>
                <a href="{{ route('admin.ecommerce.orders', ['status' => 'in_transport']) }}" class="btn btn-sm {{ request('status') === 'in_transport' ? 'btn-primary' : 'btn-light border' }}">
                    <i class="fa fa-truck me-1"></i> Pending in Transport <span class="badge bg-primary ms-1">{{ $counts['in_transport'] }}</span>
                </a>
                <a href="{{ route('admin.ecommerce.orders', ['status' => 'delivered']) }}" class="btn btn-sm {{ request('status') === 'delivered' ? 'btn-success' : 'btn-light border' }}">
                    <i class="fa fa-check me-1"></i> Delivered <span class="badge bg-success ms-1">{{ $counts['delivered'] }}</span>
                </a>
                <a href="{{ route('admin.ecommerce.orders', ['status' => 'hold']) }}" class="btn btn-sm {{ request('status') === 'hold' ? 'btn-secondary text-white' : 'btn-light border' }}">
                    Hold <span class="badge bg-secondary text-white ms-1">{{ $counts['hold'] }}</span>
                </a>
                <a href="{{ route('admin.ecommerce.orders', ['status' => 'pending_payment']) }}" class="btn btn-sm {{ request('status') === 'pending_payment' ? 'btn-info' : 'btn-light border' }}">
                    Pending Payment <span class="badge bg-info ms-1">{{ $counts['pending_payment'] }}</span>
                </a>
                <a href="{{ route('admin.ecommerce.orders', ['status' => 'cancelled']) }}" class="btn btn-sm {{ request('status') === 'cancelled' ? 'btn-danger' : 'btn-light border' }}">
                    Canceled <span class="badge bg-danger ms-1">{{ $counts['canceled'] }}</span>
                </a>
            </div>

            <!-- Main Order Box -->
            <div class="box shadow-sm" style="border-radius: 12px; overflow: hidden;">
                <!-- Action Toolbar (Image 4 Reference) -->
                <div class="box-header bg-white py-3 border-bottom">
                    <form id="bulkActionForm" action="{{ route('admin.ecommerce.orders.bulk_action') }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" id="bulkActionType" value="">
                        
                        <div class="row align-items-center gy-2">
                            <!-- Left: Bulk Buttons -->
                            <div class="col-lg-7 col-12 d-flex flex-wrap align-items-center gap-2">
                                <button type="button" class="btn btn-success btn-sm fw-bold px-3" data-bs-toggle="modal" data-bs-target="#addOrderModal">
                                    <i class="fa fa-plus me-1"></i> Add Order
                                </button>

                                <div class="input-group input-group-sm w-auto">
                                    <select name="bulk_status" id="bulkStatusSelect" class="form-select form-select-sm" style="min-width: 130px;">
                                        <option value="">Select Status ▾</option>
                                        <option value="hold">Hold</option>
                                        <option value="processing">Processing</option>
                                        <option value="pending">Pending</option>
                                        <option value="in_transport">Pending in Transport</option>
                                        <option value="delivered">Delivered</option>
                                        <option value="pending_payment">Pending Payment</option>
                                        <option value="cancelled">Canceled</option>
                                    </select>
                                    <button type="button" class="btn btn-dark btn-sm" onclick="submitBulkAction('status')">
                                        Apply
                                    </button>
                                </div>

                                <button type="button" class="btn btn-danger btn-sm" onclick="submitBulkAction('delete')" title="Delete selected">
                                    <i class="fa fa-trash me-1"></i> Delete
                                </button>

                                <button type="button" class="btn btn-secondary btn-sm" onclick="window.print()" title="Print orders">
                                    <i class="fa fa-print me-1"></i> Print
                                </button>

                                <button type="button" class="btn btn-outline-success btn-sm" onclick="exportOrdersToCSV()" title="Export CSV">
                                    <i class="fa fa-download me-1"></i> Export
                                </button>

                                <button type="button" class="btn btn-sm text-white" style="background-color: #10b981;" onclick="submitBulkAction('send_steadfast')" title="Send to Steadfast">
                                    <i class="fa fa-truck me-1"></i> Send to Steadfast
                                </button>

                                <button type="button" class="btn btn-sm text-white" style="background-color: #0284c7;" onclick="submitBulkAction('send_pathao')" title="Send to Pathao">
                                    <i class="fa fa-paper-plane me-1"></i> Send to Pathao
                                </button>
                            </div>

                            <!-- Right: Date Range & Search Filters -->
                            <div class="col-lg-5 col-12">
                                <div class="d-flex flex-wrap align-items-center justify-content-lg-end gap-2">
                                    <div class="input-group input-group-sm w-auto">
                                        <input type="date" form="searchForm" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}" title="From Date">
                                        <span class="input-group-text bg-light text-muted">to</span>
                                        <input type="date" form="searchForm" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}" title="To Date">
                                    </div>
                                    <div class="input-group input-group-sm" style="max-width: 200px;">
                                        <input type="text" form="searchForm" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}">
                                        <button type="submit" form="searchForm" class="btn btn-primary btn-sm">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Orders Table (Image 4 exact layout) -->
                        <div class="table-responsive mt-3">
                            <table class="table table-hover align-middle mb-0" id="ordersDataTable">
                                <thead class="table-light text-muted small" style="background-color: #f8fafc; text-transform: uppercase; font-size: 11px;">
                                    <tr>
                                        <th style="width: 30px;">
                                            <input type="checkbox" id="selectAllCheckbox" class="form-check-input">
                                        </th>
                                        <th style="width: 45px;">SL.</th>
                                        <th>Order ID</th>
                                        <th style="min-width: 200px;">Customer</th>
                                        <th style="min-width: 220px;">Products</th>
                                        <th style="min-width: 170px;">Account</th>
                                        <th>Date</th>
                                        <th>Platform</th>
                                        <th>Status</th>
                                        <th>Note</th>
                                        <th>কুরিয়ার</th>
                                        <th class="text-end" style="min-width: 100px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $index => $ord)
                                        <tr>
                                            <!-- Checkbox -->
                                            <td>
                                                <input type="checkbox" name="order_ids[]" value="{{ $ord->id }}" class="form-check-input order-row-checkbox">
                                            </td>

                                            <!-- SL -->
                                            <td class="text-muted small">
                                                {{ ($orders->currentPage() - 1) * $orders->perPage() + $index + 1 }}
                                            </td>

                                            <!-- Order ID -->
                                            <td>
                                                <a href="{{ route('admin.ecommerce.orders.show', $ord->id) }}" class="fw-bold text-primary font-monospace text-decoration-none">
                                                    {{ $ord->order_number }}
                                                </a>
                                            </td>

                                            <!-- Customer -->
                                            <td>
                                                <div class="fw-bold text-dark">{{ $ord->first_name }} {{ $ord->last_name }}</div>
                                                <div class="d-flex align-items-center gap-1 my-1">
                                                    <span class="text-muted small font-monospace"><i class="fa fa-phone text-success me-1"></i>{{ $ord->phone }}</span>
                                                    <button type="button" class="btn btn-xs btn-outline-primary py-0 px-1" style="font-size: 10px;" onclick="checkCustomerFraud('{{ $ord->phone }}', '{{ $ord->first_name }}')">
                                                        Check
                                                    </button>
                                                </div>
                                                <div class="text-muted small text-truncate" style="max-width: 190px;" title="{{ $ord->address }}, {{ $ord->city }}">
                                                    {{ $ord->address }}
                                                </div>
                                                <div class="d-flex align-items-center gap-2 mt-1">
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success" style="font-size: 9px; padding: 1px 5px;">
                                                        New Customer
                                                    </span>
                                                    @if($ord->ip_address)
                                                        <span class="text-muted" style="font-size: 9px;">
                                                            IP: {{ $ord->ip_address }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>

                                            <!-- Products -->
                                            <td>
                                                @foreach($ord->items->take(2) as $item)
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <img src="{{ $item->product && $item->product->image ? asset($item->product->image) : asset('assets/images/placeholder.png') }}" alt="{{ $item->product_name }}" class="rounded border" style="width: 36px; height: 36px; object-fit: cover;">
                                                        <div class="small lh-sm text-truncate" style="max-width: 160px;">
                                                            <div class="fw-semibold text-dark text-truncate" title="{{ $item->product_name }}">{{ $item->product_name }}</div>
                                                            <span class="badge bg-light text-muted border font-monospace" style="font-size: 9px;">SKU: {{ $item->sku ?: ($item->product ? $item->product->sku : 'N/A') }}</span>
                                                            <span class="fw-bold text-primary" style="font-size: 10px;">x {{ $item->quantity }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                @if($ord->items->count() > 2)
                                                    <span class="badge bg-light text-muted border small">+ আরও {{ $ord->items->count() - 2 }} টি আইটেম</span>
                                                @endif
                                            </td>

                                            <!-- Account -->
                                            <td class="small lh-base">
                                                <div class="text-muted">Sub Total: <span class="fw-semibold text-dark">৳ {{ number_format($ord->subtotal, 0) }}</span></div>
                                                <div class="text-muted">Shipping Cost: <span class="fw-semibold text-dark">৳ {{ number_format($ord->shipping_charge, 0) }}</span></div>
                                                @if($ord->discount_amount > 0)
                                                    <div class="text-success">Discount: <span class="fw-semibold">-৳ {{ number_format($ord->discount_amount, 0) }}</span></div>
                                                @endif
                                                <div class="text-muted">Payment: <span class="badge bg-light text-uppercase border font-monospace" style="font-size: 9px;">{{ $ord->payment_method ?: 'COD' }}</span></div>
                                                <div class="fw-bold text-dark pt-1 border-top mt-1">Total Collectables: ৳ {{ number_format($ord->total_amount, 0) }}</div>
                                                <div class="fw-bold text-success">Total Amount: ৳ {{ number_format($ord->total_amount, 0) }}</div>
                                            </td>

                                            <!-- Date -->
                                            <td class="small text-nowrap">
                                                <div class="fw-semibold text-dark">{{ $ord->created_at->format('d M, Y') }}</div>
                                                <div class="text-muted" style="font-size: 11px;">{{ $ord->created_at->format('h:i A') }}</div>
                                            </td>

                                            <!-- Platform -->
                                            <td>
                                                @php
                                                    $platform = strtoupper($ord->platform ?: 'WEBSITE');
                                                    $platformBadges = [
                                                        'TIKTOK ADS' => 'background: #000; color: #ff0050; border: 1px solid #ff0050;',
                                                        'FACEBOOK ADS' => 'background: #1877f2; color: #fff;',
                                                        'WEBSITE' => 'background: #10b981; color: #fff;',
                                                        'DIRECT WEB' => 'background: #8b5cf6; color: #fff;',
                                                        'PHONE' => 'background: #f59e0b; color: #fff;',
                                                    ];
                                                    $style = $platformBadges[$platform] ?? 'background: #64748b; color: #fff;';
                                                @endphp
                                                <span class="badge rounded-pill fw-bold" style="{{ $style }}; font-size: 10px; padding: 4px 8px; letter-spacing: 0.5px;">
                                                    {{ $platform }}
                                                </span>
                                            </td>

                                            <!-- Status (Interactive Dropdown Select) -->
                                            <td>
                                                <select class="form-select form-select-sm status-dropdown-select fw-bold" 
                                                        data-order-id="{{ $ord->id }}"
                                                        onchange="updateSingleOrderStatus({{ $ord->id }}, this.value)"
                                                        style="min-width: 120px; font-size: 11px;">
                                                    <option value="hold" {{ $ord->order_status === 'hold' ? 'selected' : '' }}>Hold</option>
                                                    <option value="processing" {{ $ord->order_status === 'processing' ? 'selected' : '' }}>Processing</option>
                                                    <option value="pending" {{ $ord->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="in_transport" {{ in_array($ord->order_status, ['in_transport', 'shipping']) ? 'selected' : '' }}>Pending in Transport</option>
                                                    <option value="delivered" {{ $ord->order_status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                                    <option value="pending_payment" {{ $ord->order_status === 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
                                                    <option value="cancelled" {{ in_array($ord->order_status, ['cancelled', 'canceled']) ? 'selected' : '' }}>Canceled</option>
                                                </select>
                                            </td>

                                            <!-- Note -->
                                            <td class="small text-muted" style="max-width: 120px;">
                                                @if($ord->notes)
                                                    <span class="text-truncate d-inline-block" style="max-width: 110px;" title="{{ $ord->notes }}">
                                                        <i class="fa fa-commenting-o text-primary me-1"></i>{{ $ord->notes }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>

                                            <!-- কুরিয়ার (Courier) -->
                                            <td>
                                                @if($ord->courier_name)
                                                    <div class="d-flex flex-column gap-1">
                                                        <span class="badge {{ $ord->courier_name === 'Steadfast' ? 'bg-success' : 'bg-primary' }} text-white font-monospace" style="font-size: 10px;">
                                                            {{ $ord->courier_name }}
                                                        </span>
                                                        @if($ord->tracking_code)
                                                            <a href="https://steadfast.com.bd/t/{{ $ord->tracking_code }}" target="_blank" class="text-decoration-none small text-primary font-monospace" style="font-size: 9px;" title="Track on Courier">
                                                                {{ $ord->tracking_code }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="dropdown">
                                                        <button class="btn btn-xs btn-outline-success dropdown-toggle py-1 px-2" type="button" data-bs-toggle="dropdown" style="font-size: 10px;">
                                                            কুরিয়ার
                                                        </button>
                                                        <ul class="dropdown-menu shadow-sm dropdown-menu-end" style="font-size: 12px;">
                                                            <li>
                                                                <button class="dropdown-item text-success" type="button" onclick="dispatchOrderCourier({{ $ord->id }}, 'Steadfast')">
                                                                    <i class="fa fa-truck me-1"></i> Send to Steadfast
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <button class="dropdown-item text-primary" type="button" onclick="dispatchOrderCourier({{ $ord->id }}, 'Pathao')">
                                                                    <i class="fa fa-paper-plane me-1"></i> Send to Pathao
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                @endif
                                            </td>

                                            <!-- Action -->
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-1">
                                                    <a href="{{ route('admin.ecommerce.orders.show', $ord->id) }}" class="btn btn-xs btn-outline-primary" title="View details">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.ecommerce.orders.invoice', $ord->id) }}" target="_blank" class="btn btn-xs btn-outline-info" title="Print Invoice">
                                                        <i class="fa fa-print"></i>
                                                    </a>
                                                    <a href="{{ route('admin.ecommerce.orders.destroy', $ord->id) }}" class="btn btn-xs btn-outline-danger" onclick="return confirm('আপনি কি নিশ্চিত এই অর্ডারটি মুছে ফেলতে চান?')" title="Delete Order">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center py-5 text-muted">
                                                <i class="fa fa-shopping-basket fs-1 d-block mb-2 text-warning"></i>
                                                কোনো অর্ডার খুঁজে পাওয়া যায়নি!
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>

                @if($orders->hasPages())
                    <div class="box-footer bg-white d-flex justify-content-between align-items-center py-2 px-3">
                        <div class="text-muted small">
                            দেখানো হচ্ছে {{ $orders->firstItem() }} থেকে {{ $orders->lastItem() }} (মোট {{ $orders->total() }} টি অর্ডার)
                        </div>
                        <div>
                            {{ $orders->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>

<!-- Hidden GET Search Form -->
<form id="searchForm" action="{{ route('admin.ecommerce.orders') }}" method="GET" style="display: none;">
    @if(request('status'))
        <input type="hidden" name="status" value="{{ request('status') }}">
    @endif
</form>

<!-- Modal: + Add Order (Manual Order Entry) -->
<div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold" id="addOrderModalLabel"><i class="fa fa-plus-circle me-2"></i>নতুন ম্যানুয়াল অর্ডার যোগ করুন (Add Order)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.ecommerce.orders.create_manual') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">গ্রাহকের নাম (Customer Name) <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control" placeholder="যেমন: মো: আরিফুল ইসলাম" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ফোন নম্বর (Phone Number) <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" placeholder="যেমন: 01712345678" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">পূর্ণ ঠিকানা (Delivery Address) <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" rows="2" placeholder="বাড়ি নং, রোড নং, এলাকা, থানা..." required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">শহর / জেলা (City)</label>
                            <input type="text" name="city" class="form-control" value="Dhaka">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">প্ল্যাটফর্ম (Platform)</label>
                            <select name="platform" class="form-select">
                                <option value="DIRECT WEB">DIRECT WEB</option>
                                <option value="TIKTOK ADS">TIKTOK ADS</option>
                                <option value="FACEBOOK ADS">FACEBOOK ADS</option>
                                <option value="PHONE">PHONE ORDER</option>
                                <option value="WEBSITE">WEBSITE</option>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label fw-bold">পণ্য নির্বাচন করুন (Select Product) <span class="text-danger">*</span></label>
                            <select name="product_id" class="form-select" required>
                                <option value="">-- পণ্য বেছে নিন --</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} (৳{{ number_format($p->price, 0) }}) - SKU: {{ $p->sku }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">পরিমাণ (Qty) <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">ডেলিভারি চার্জ</label>
                            <input type="number" name="shipping_charge" class="form-control" value="60">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">অর্ডার স্ট্যাটাস</label>
                            <select name="order_status" class="form-select">
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="hold">Hold</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">অর্ডার নোট (Optional)</label>
                            <input type="text" name="notes" class="form-control" placeholder="বিশেষ কোনো নির্দেশনা...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success fw-bold"><i class="fa fa-check me-1"></i> অর্ডার তৈরি করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Customer Fraud / Order History Check -->
<div class="modal fade" id="customerCheckModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fa fa-shield me-2"></i>কাস্টমার ভেরিফিকেশন ও ট্র্যাকিং হিস্ট্রি</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center" id="customerCheckBody">
                <div class="spinner-border text-primary" role="status"></div>
                <div class="mt-2 text-muted">তথ্য লোড হচ্ছে...</div>
            </div>
            <div class="modal-footer bg-light justify-content-center">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">বন্ধ করুন</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Select all checkboxes toggle
    document.getElementById('selectAllCheckbox')?.addEventListener('change', function() {
        const checked = this.checked;
        document.querySelectorAll('.order-row-checkbox').forEach(cb => cb.checked = checked);
    });

    // Bulk action submission
    function submitBulkAction(actionType) {
        const checkedBoxes = document.querySelectorAll('.order-row-checkbox:checked');
        if (checkedBoxes.length === 0) {
            alert('অনুগ্রহ করে অন্তত একটি অর্ডার সিলেক্ট করুন!');
            return;
        }

        if (actionType === 'delete') {
            if (!confirm(`আপনি কি নিশ্চিত নির্বাচিত ${checkedBoxes.length} টি অর্ডার ডিলিট করতে চান?`)) {
                return;
            }
        } else if (actionType === 'status') {
            const statusVal = document.getElementById('bulkStatusSelect').value;
            if (!statusVal) {
                alert('অনুগ্রহ করে স্ট্যাটাস সিলেক্ট করুন!');
                return;
            }
        }

        document.getElementById('bulkActionType').value = actionType;
        document.getElementById('bulkActionForm').submit();
    }

    // Single order status update via AJAX
    function updateSingleOrderStatus(orderId, newStatus) {
        const csrfToken = '{{ csrf_token() }}';
        fetch(`{{ url('admin/ecommerce/orders/status') }}/${orderId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                order_status: newStatus,
                comment: `অ্যাডমিন প্যানেল টেবিল থেকে স্ট্যাটাস আপডেট: ${newStatus}`
            })
        })
        .then(res => res.json())
        .then(data => {
            // Flash a toast or reload
            window.location.reload();
        })
        .catch(err => {
            window.location.reload();
        });
    }

    // Send single order to courier
    function dispatchOrderCourier(orderId, courierName) {
        if (!confirm(`অর্ডারটি কি ${courierName} কুরিয়ারে পাঠাতে চান?`)) {
            return;
        }

        const csrfToken = '{{ csrf_token() }}';
        fetch(`{{ url('admin/ecommerce/orders') }}/${orderId}/send-courier`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                courier: courierName
            })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || 'সফলভাবে কুরিয়ারে পাঠানো হয়েছে!');
            window.location.reload();
        })
        .catch(err => {
            window.location.reload();
        });
    }

    // Customer check fraud & history
    function checkCustomerFraud(phone, name) {
        const modal = new bootstrap.Modal(document.getElementById('customerCheckModal'));
        modal.show();
        const body = document.getElementById('customerCheckBody');
        body.innerHTML = `
            <div class="spinner-border text-primary mb-2" role="status"></div>
            <div class="text-muted small">ফোন নম্বর: ${phone} যাচাই করা হচ্ছে...</div>
        `;

        fetch(`{{ url('admin/ecommerce/orders/check-customer') }}/${phone}`)
            .then(res => res.json())
            .then(data => {
                let badgeClass = 'bg-success';
                if (data.risk_color === 'danger') badgeClass = 'bg-danger';
                if (data.risk_color === 'warning') badgeClass = 'bg-warning text-dark';
                if (data.risk_color === 'info') badgeClass = 'bg-info text-white';

                body.innerHTML = `
                    <div class="text-center mb-3">
                        <div class="avatar avatar-lg bg-light-primary text-primary rounded-circle mx-auto mb-2" style="width: 50px; height: 50px; line-height: 50px;">
                            <i class="fa fa-user fs-4"></i>
                        </div>
                        <h5 class="fw-bold mb-0 text-dark">${name}</h5>
                        <div class="text-muted font-monospace">${phone}</div>
                        <div class="mt-2">
                            <span class="badge ${badgeClass} px-3 py-2 fs-6 rounded-pill">${data.risk_level}</span>
                        </div>
                    </div>
                    <div class="row g-2 text-start mt-2 border-top pt-3">
                        <div class="col-6">
                            <div class="p-2 border rounded bg-light">
                                <div class="text-muted small">মোট অর্ডার সংখ্যা</div>
                                <div class="fs-5 fw-bold text-dark">${data.total_orders} টি</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 border rounded bg-light">
                                <div class="text-muted small">সফল ডেলিভারি</div>
                                <div class="fs-5 fw-bold text-success">${data.delivered_orders} টি</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 border rounded bg-light">
                                <div class="text-muted small">বাতিলকৃত অর্ডার</div>
                                <div class="fs-5 fw-bold text-danger">${data.cancelled_orders} টি</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 border rounded bg-light">
                                <div class="text-muted small">সর্বশেষ অর্ডার তারিখ</div>
                                <div class="fw-semibold text-dark small">${data.last_order_date}</div>
                            </div>
                        </div>
                    </div>
                `;
            })
            .catch(err => {
                body.innerHTML = `<div class="text-danger">তথ্য লোড করতে সমস্যা হয়েছে।</div>`;
            });
    }

    // Export Table to CSV
    function exportOrdersToCSV() {
        let csv = [];
        const rows = document.querySelectorAll("#ordersDataTable tr");
        for (let i = 0; i < rows.length; i++) {
            let row = [], cols = rows[i].querySelectorAll("td, th");
            for (let j = 1; j < cols.length - 1; j++) {
                let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, " ").trim();
                data = data.replace(/"/g, '""');
                row.push('"' + data + '"');
            }
            csv.push(row.join(","));
        }
        const csvFile = new Blob([csv.join("\n")], { type: "text/csv;charset=utf-8;" });
        const downloadLink = document.createElement("a");
        downloadLink.download = "orders_export_" + new Date().toISOString().slice(0, 10) + ".csv";
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = "none";
        document.body.appendChild(downloadLink);
        downloadLink.click();
    }
</script>
@endsection
