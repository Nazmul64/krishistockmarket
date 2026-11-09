@extends('layouts.backend.app')

@section('content')
<style>
    .bazaar-hero-banner {
        background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #059669 100%);
        color: #ffffff;
        border-radius: 18px;
        padding: 24px 28px;
        margin-bottom: 25px;
        box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.3);
        position: relative;
        overflow: hidden;
    }
    .bazaar-hero-banner::after {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 180px;
        height: 180px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        pointer-events: none;
    }
    .bazaar-pkg-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .bazaar-pkg-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 35px -8px rgba(16, 185, 129, 0.22);
        border-color: #10b981;
    }
    .bazaar-img-box {
        position: relative;
        height: 200px;
        background: #f8fafc;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bazaar-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .bazaar-pkg-card:hover .bazaar-img-box img {
        transform: scale(1.05);
    }
    .bazaar-placeholder-box {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #10b981 0%, #059669 60%, #047857 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .bazaar-basket-ring {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid rgba(255, 255, 255, 0.35);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        margin-bottom: 8px;
    }
    .bazaar-stock-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 2;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }
    .bazaar-items-box {
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 12px 14px;
        margin-bottom: 16px;
    }
    .bazaar-item-row {
        display: flex;
        align-items: center;
        font-size: 13px;
        font-weight: 500;
        color: #1e293b;
        margin-bottom: 6px;
    }
    .bazaar-item-row:last-child {
        margin-bottom: 0;
    }
    .btn-bazaar-order {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff !important;
        border: none;
        border-radius: 25px;
        padding: 10px 22px;
        font-weight: 700;
        font-size: 14px;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    .btn-bazaar-order:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(16, 185, 129, 0.4);
    }
</style>

<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header / Banner -->
        <div class="bazaar-hero-banner">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h3 class="fw-bold mb-1 d-flex align-items-center gap-2">
                        <i class="fa fa-shopping-basket text-warning"></i> মাসিক বাজার (Monthly Grocery Packages)
                    </h3>
                    <p class="mb-0 text-white-50" style="font-size: 14px;">
                        সরাসরি খামার ও মিল থেকে সংগৃহীত নিরাপদ ও পুষ্টিকর খাদ্যপণ্যের সাশ্রয়ী মাসিক প্যাকেজ
                    </p>
                </div>
                <div>
                    <a href="{{ route('user.monthly_bazaar.my_orders') }}" class="btn btn-warning text-dark fw-bold rounded-pill shadow-sm px-4 py-2">
                        <i class="fa fa-list-alt me-1"></i> আমার মাসিক বাজার অর্ডারসমূহ
                    </a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content pt-0">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3" role="alert">
                    <i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                @forelse($items as $item)
                    @php
                        $available = $item->quantity - $item->sold_quantity;
                        $lines = array_filter(array_map('trim', explode("\n", str_replace("\r", "", $item->description ?? ''))));
                    @endphp
                    <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                        <div class="bazaar-pkg-card">
                            <!-- Image / Illustration Area -->
                            <div class="bazaar-img-box">
                                <!-- Stock Badge -->
                                <div class="bazaar-stock-badge">
                                    @if($item->is_unlimited)
                                        <span class="badge bg-success px-3 py-2 rounded-pill fw-bold">
                                            <i class="fa fa-infinity me-1"></i> আনলিমিটেড স্টক
                                        </span>
                                    @else
                                        <span class="badge {{ $available > 5 ? 'bg-info' : 'bg-danger' }} px-3 py-2 rounded-pill fw-bold">
                                            <i class="fa fa-cubes me-1"></i> মজুদ: {{ $available }} টি
                                        </span>
                                    @endif
                                </div>

                                @if($item->image && file_exists(public_path('upload/monthly_bazaar/'.$item->image)))
                                    <img src="{{ asset('upload/monthly_bazaar/'.$item->image) }}" alt="{{ $item->title }}" />
                                @else
                                    <div class="bazaar-placeholder-box">
                                        <div class="bazaar-basket-ring">
                                            <i class="fa fa-shopping-basket fa-2x text-white"></i>
                                        </div>
                                        <span class="text-white fw-bold small opacity-90"><i class="fa fa-check-circle me-1 text-warning"></i> প্রিমিয়াম ফ্রেশ প্যাক</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Card Body -->
                            <div class="p-3 d-flex flex-column flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-light text-primary border px-2 py-1" style="font-size: 11px; font-weight: 600;">
                                        <i class="fa fa-tag me-1 text-warning"></i> {{ $item->package_name ?: 'মাসিক খাদ্য প্যাকেজ' }}
                                    </span>
                                    <small class="text-muted"><i class="fa fa-shield text-success me-1"></i> ১০০% খাটি পণ্য</small>
                                </div>

                                <h5 class="fw-bold text-dark mb-2" style="font-size: 18px; line-height: 1.4;">
                                    {{ $item->title }}
                                </h5>

                                <!-- Package Item Details Checklist -->
                                <div class="bazaar-items-box flex-grow-1">
                                    <small class="text-muted fw-bold d-block mb-2" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <i class="fa fa-list-ul me-1 text-success"></i> প্যাকেজের পণ্য তালিকা:
                                    </small>
                                    @if(count($lines) > 0)
                                        @foreach($lines as $line)
                                            <div class="bazaar-item-row">
                                                <i class="fa fa-check-circle text-success me-2 flex-shrink-0" style="font-size: 13px;"></i>
                                                <span class="text-truncate">{{ $line }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="bazaar-item-row text-muted">
                                            <i class="fa fa-check-circle text-success me-2"></i>
                                            <span>মাসিক প্রয়োজনীয় সামগ্রী ও খাদ্যপণ্য সমাহার।</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Card Footer: Price & Order Action -->
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                                    <div>
                                        <small class="text-muted d-block font-weight-bold" style="font-size: 11px;">প্যাকেজ মূল্য (Price)</small>
                                        <span class="fs-4 fw-bold text-success" style="letter-spacing: -0.5px;">৳{{ number_format($item->price, 2) }}</span>
                                    </div>
                                    <div>
                                        <button type="button" class="btn-bazaar-order order-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#orderModal"
                                                data-id="{{ $item->id }}"
                                                data-title="{{ $item->title }}"
                                                data-price="{{ $item->price }}"
                                                data-available="{{ $item->is_unlimited ? 999999 : $available }}">
                                            <i class="fa fa-shopping-cart"></i> অর্ডার করুন
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-5 text-center text-muted">
                        <div class="p-5 bg-white rounded-3 shadow-sm border text-center">
                            <i class="fa fa-shopping-basket fa-4x mb-3 text-secondary opacity-50"></i>
                            <h4 class="fw-bold text-dark">বর্তমানে কোনো মাসিক বাজার প্যাকেজ উপলব্ধ নেই।</h4>
                            <p class="text-muted mb-0">নতুন প্যাকেজ যোগ করা হলে এখানে স্বয়ংক্রিয়ভাবে প্রদর্শিত হবে।</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>

<!-- Order Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('user.monthly_bazaar.order.post') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="item_id" id="modal_item_id">

                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold" id="orderModalLabel"><i class="fa fa-shopping-basket me-2"></i> মাসিক বাজার অর্ডার নিশ্চিত করুন</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- User Registered Card Number Notice -->
                    @php
                        $userCard = GetUserCardNumber(Auth::id());
                    @endphp
                    <div class="alert alert-info py-2 mb-3 d-flex align-items-center justify-content-between">
                        <div>
                            <small class="d-block text-muted">আপনার রেজিস্ট্রেশনকৃত কার্ড নাম্বার:</small>
                            <strong class="text-dark" style="font-family: monospace; font-size: 15px;"><i class="fa fa-credit-card me-1 text-primary"></i> {{ $userCard }}</strong>
                        </div>
                        <span class="badge bg-primary">ID: #{{ Auth::id() }}</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">নির্বাচিত প্যাকেজ:</label>
                        <input type="text" class="form-control bg-light" id="modal_package_title" readonly>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">একক মূল্য:</label>
                            <input type="text" class="form-control bg-light" id="modal_price_display" readonly>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">পরিমাণ (টি):</label>
                            <input type="number" class="form-control" name="quantity" id="modal_quantity" min="1" value="1" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-success fs-5">সর্বমোট মূল্য: <span id="modal_total_price">৳0</span></label>
                    </div>

                    <!-- Area & Agent Point Selection (Dynamic from Admin DB) -->
                    <div class="row bg-light p-2 rounded mb-3 border">
                        <div class="col-12"><small class="text-success fw-bold mb-2 d-block"><i class="fa fa-map-marker me-1"></i> রিকোয়েস্টের এলাকা ও এজেন্ট পয়েন্ট (বাধ্যতামূলক)</small></div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold">রিকোয়েস্টের এলাকা <span class="text-danger">*</span></label>
                            <input type="text" name="request_area" list="area_list" class="form-control" placeholder="যেমন: নওগাঁ, বগুড়া, গাজীপুর" required>
                            <datalist id="area_list">
                                @if(isset($agent_points) && count($agent_points) > 0)
                                    @foreach($agent_points->pluck('area')->unique() as $area)
                                        <option value="{{ $area }}">
                                    @endforeach
                                @else
                                    <option value="নওগাঁ">
                                    <option value="বগুড়া">
                                    <option value="গাজীপুর">
                                    <option value="ঢাকা">
                                @endif
                            </datalist>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold">সংগ্রহের Agent Point <span class="text-danger">*</span></label>
                            <select name="agent_point" class="form-control" required>
                                <option value="">-- Agent Point বেছে নিন --</option>
                                @if(isset($agent_points) && count($agent_points) > 0)
                                    @foreach($agent_points as $ap)
                                        <option value="{{ $ap->name }}">{{ $ap->name }} ({{ $ap->area }})</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">পেমেন্ট মেথড সিলেক্ট করুন <span class="text-danger">*</span></label>
                        <select name="payment_method" id="payment_method_select" class="form-control" required>
                            <option value="">-- পেমেন্ট মেথড বেছে নিন --</option>
                            <option value="Wallet Balance (ওয়ালেট ব্যালেন্স)">Wallet Balance (ওয়ালেট ব্যালেন্স - ৳{{ number_format(Auth::user()->balance ?? 0, 2) }})</option>
                            @if(isset($payment_systems) && count($payment_systems) > 0)
                                @foreach($payment_systems as $sys)
                                    <option value="{{ $sys->pay_s_name }}">{{ $sys->pay_s_name }} ({{ $sys->pay_s_number }})</option>
                                @endforeach
                            @endif
                            <option value="Cash on Delivery (ক্যাশ অন ডেলিভারি)">Cash on Delivery (ক্যাশ অন ডেলিভারি)</option>
                        </select>
                    </div>

                    <!-- Payment instructions dynamically shown -->
                    <div id="payment_info_box" class="alert alert-secondary py-2 mb-3 d-none">
                        <small class="fw-bold d-block text-dark mb-1">পেমেন্ট করার নাম্বারসমূহ:</small>
                        <ul class="mb-0 ps-3 small text-muted">
                            @foreach($payment_systems as $sys)
                                <li><strong>{{ $sys->pay_s_name }}:</strong> {{ $sys->pay_s_number }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">যে নাম্বার থেকে পেমেন্ট করেছেন (Pay From Number)</label>
                        <input type="text" class="form-control" name="pay_from_number" placeholder="যেমন: 01711****** (ক্যাশ অন ডেলিভারি হলে খালি রাখুন)">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">ট্রানজেকশন আইডি (Trx ID)</label>
                        <input type="text" class="form-control" name="trx_number" placeholder="যেমন: 9J76HGTR (ক্যাশ অন ডেলিভারি হলে খালি রাখুন)">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">পেমেন্ট স্ক্রিনশট (Screenshot Image)</label>
                        <input type="file" class="form-control" name="screenshot">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success px-4"><i class="fa fa-paper-plane me-1"></i> রিকোয়েস্ট পাঠান</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        var currentPrice = 0;

        $('.order-btn').on('click', function() {
            var id = $(this).data('id');
            var title = $(this).data('title');
            var price = parseFloat($(this).data('price'));
            currentPrice = price;

            $('#modal_item_id').val(id);
            $('#modal_package_title').val(title);
            $('#modal_price_display').val('৳' + price.toFixed(2));
            $('#modal_quantity').val(1);
            updateTotal();
        });

        $('#modal_quantity').on('input change', function() {
            updateTotal();
        });

        function updateTotal() {
            var qty = parseInt($('#modal_quantity').val()) || 1;
            var total = currentPrice * qty;
            $('#modal_total_price').text('৳' + total.toFixed(2));
        }

        $('#payment_method_select').on('change', function() {
            var val = $(this).val();
            if(val && val.indexOf('Cash') === -1) {
                $('#payment_info_box').removeClass('d-none');
            } else {
                $('#payment_info_box').addClass('d-none');
            }
        });
    });
</script>
@endsection
