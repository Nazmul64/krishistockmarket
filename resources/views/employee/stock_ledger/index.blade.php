@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title">আমার লাইভ স্টক ও লেনদেনের হিসাব (Live Stock & Ledger)</h4>
                    <p class="text-muted mb-0">আপনার কাছে থাকা প্রতিষ্ঠানের লাইভ মজুদ স্টক এবং বিক্রির হিসাব</p>
                </div>
                <div>
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#sellStockModal">
                        <i class="fa fa-cart-plus me-1"></i> স্টক বিক্রি এন্ট্রি করুন
                    </button>
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

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Stat Cards -->
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm bg-primary text-white h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-uppercase mb-1 opacity-75">লাইভ অবশিষ্ট স্টক</h6>
                                <h3 class="fw-bold mb-0">{{ $available_stock }} টি</h3>
                                <small class="opacity-75">মোট বরাদ্দ: {{ $total_allocated }} টি</small>
                            </div>
                            <i class="fa fa-cubes fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm bg-success text-white h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-uppercase mb-1 opacity-75">আজকের বিক্রি (Today)</h6>
                                <h3 class="fw-bold mb-0">৳{{ number_format($today_sales, 2) }}</h3>
                                <small class="opacity-75">আজকের মোট বিক্রয় মূল্য</small>
                            </div>
                            <i class="fa fa-shopping-cart fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm bg-info text-white h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-uppercase mb-1 opacity-75">চলতি মাসের বিক্রি (Monthly)</h6>
                                <h3 class="fw-bold mb-0">৳{{ number_format($monthly_sales, 2) }}</h3>
                                <small class="opacity-75">এই মাসের মোট লেনদেন</small>
                            </div>
                            <i class="fa fa-calendar fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm bg-dark text-white h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-uppercase mb-1 opacity-75">চলতি বছরের বিক্রি (Yearly)</h6>
                                <h3 class="fw-bold mb-0">৳{{ number_format($yearly_sales, 2) }}</h3>
                                <small class="opacity-75">মোট বিক্রি: {{ $total_sold }} টি</small>
                            </div>
                            <i class="fa fa-trophy fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agent Stock Inventory Breakdown -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-boxes me-2 text-warning"></i> আমার কাছে থাকা বর্তমান মালামাল/স্টক মজুদ বিবরণী</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>স্টক/পণ্য নাম</th>
                                            <th class="text-center">একক মূল্য (৳)</th>
                                            <th class="text-center">মোট বরাদ্দপ্রাপ্ত</th>
                                            <th class="text-center">মোট বিক্রিত</th>
                                            <th class="text-center">অবশিষ্ট লাইভ স্টক</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($agent_stocks as $k => $st)
                                            @php $rem = $st->allocated_quantity - $st->sold_quantity; @endphp
                                            <tr>
                                                <td>{{ $k+1 }}</td>
                                                <td class="fw-bold text-dark">{{ $st->stock_name }}</td>
                                                <td class="text-center">৳{{ number_format($st->unit_price, 2) }}</td>
                                                <td class="text-center fw-bold text-primary">{{ $st->allocated_quantity }} টি</td>
                                                <td class="text-center fw-bold text-warning">{{ $st->sold_quantity }} টি</td>
                                                <td class="text-center">
                                                    <span class="badge bg-success py-2 px-3 fs-6">{{ $rem }} টি</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-3 text-muted">আপনার নিকট বর্তমানে কোনো স্টক মালামাল বরাদ্দ নেই।</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Ledger Table -->
            <div class="row">
                <div class="col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                            <h4 class="box-title text-dark mb-0"><i class="fa fa-history me-2 text-primary"></i> লাইভ লেনদেন হিসেব খতিয়ান</h4>
                            <div class="btn-group">
                                <a href="{{ route('employee.stock_ledger.index') }}" class="btn btn-sm btn-outline-secondary {{ !request('filter') ? 'active' : '' }}">সব</a>
                                <a href="{{ route('employee.stock_ledger.index', ['filter' => 'today']) }}" class="btn btn-sm btn-outline-success {{ request('filter') == 'today' ? 'active' : '' }}">আজকের (Today)</a>
                                <a href="{{ route('employee.stock_ledger.index', ['filter' => 'month']) }}" class="btn btn-sm btn-outline-info {{ request('filter') == 'month' ? 'active' : '' }}">এই মাসের (Monthly)</a>
                                <a href="{{ route('employee.stock_ledger.index', ['filter' => 'year']) }}" class="btn btn-sm btn-outline-primary {{ request('filter') == 'year' ? 'active' : '' }}">এই বছরের (Yearly)</a>
                            </div>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>SN.</th>
                                            <th>ধরন (Type)</th>
                                            <th>স্টক/পণ্যের নাম</th>
                                            <th class="text-center">পরিমাণ</th>
                                            <th class="text-center">একক মূল্য</th>
                                            <th class="text-center">সর্বমোট (৳)</th>
                                            <th>ক্রেতার নাম ও বিবরণ</th>
                                            <th class="text-center">তারিখ ও সময়</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transactions as $key => $tx)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    @if($tx->transaction_type == 'allocation')
                                                        <span class="badge bg-primary"><i class="fa fa-download me-1"></i>বরাদ্দ প্রাপ্ত</span>
                                                    @else
                                                        <span class="badge bg-success"><i class="fa fa-shopping-cart me-1"></i>বিক্রি (Sale)</span>
                                                    @endif
                                                </td>
                                                <td class="fw-bold text-dark">{{ $tx->stock_name }}</td>
                                                <td class="text-center fw-bold">{{ $tx->quantity }} টি</td>
                                                <td class="text-center">৳{{ number_format($tx->unit_price, 2) }}</td>
                                                <td class="text-center fw-bold text-success">৳{{ number_format($tx->total_price, 2) }}</td>
                                                <td>
                                                    @if($tx->customer_name)
                                                        <strong>{{ $tx->customer_name }}</strong><br>
                                                        <small class="text-muted"><i class="fa fa-phone me-1"></i>{{ $tx->customer_phone ?? 'N/A' }}</small>
                                                    @else
                                                        <span class="text-muted small">{{ $tx->notes ?? 'N/A' }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($tx->created_at)->format('d/m/Y h:i A') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">কোনো লেনদেন রেকর্ড পাওয়া যায়নি।</td>
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

<!-- Direct Sell Stock Modal -->
<div class="modal fade" id="sellStockModal" tabindex="-1" aria-labelledby="sellStockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('employee.stock_ledger.sell') }}">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold" id="sellStockModalLabel"><i class="fa fa-cart-plus me-2"></i> কাস্টমারের নিকট নতুন স্টক বিক্রি এন্ট্রি</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">স্টক/পণ্য নির্বাচন করুন <span class="text-danger">*</span></label>
                        <select name="agent_stock_id" id="agent_stock_select" class="form-control" required>
                            <option value="">-- মজুদ স্টক বেছে নিন --</option>
                            @foreach($agent_stocks as $stk)
                                @php $avail = $stk->allocated_quantity - $stk->sold_quantity; @endphp
                                @if($avail > 0)
                                    <option value="{{ $stk->id }}" data-price="{{ $stk->unit_price }}" data-avail="{{ $avail }}">
                                        {{ $stk->stock_name }} (অবশিষ্ট মজুদ: {{ $avail }} টি | মূল্য: ৳{{ $stk->unit_price }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">বিক্রি পরিমাণ (টি) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="quantity" id="sell_quantity" min="1" value="1" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">একক বিক্রি মূল্য (৳) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="unit_price" id="sell_unit_price" step="0.01" min="0" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">ক্রেতার নাম (Customer Name) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="customer_name" required placeholder="যেমন: আব্দুর রহিম">
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">ক্রেতার ফোন নাম্বার</label>
                            <input type="text" class="form-control" name="customer_phone" placeholder="যেমন: 01711******">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">ক্রেতার কার্ড নাম্বার</label>
                            <input type="text" class="form-control" name="customer_card_number" placeholder="১২-ডিজিটের কার্ড নাম্বার (যদি থাকে)">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">নোট (ঐচ্ছিক)</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="বিক্রি সংক্রান্ত অতিরিক্ত বিবরণ..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success px-4"><i class="fa fa-check me-1"></i> বিক্রি নিশ্চিত করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#agent_stock_select').on('change', function() {
            var selected = $(this).find('option:selected');
            var price = selected.data('price');
            if(price !== undefined) {
                $('#sell_unit_price').val(price);
            }
        });
    });
</script>
@endsection
