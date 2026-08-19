@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title">মাসিক বাজার (Monthly Grocery Packages)</h4>
                    <p class="text-muted mb-0">আপনার প্রয়োজনীয় মাসিক খাদ্য পণ্যের প্যাকেজ নির্বাচন করে অর্ডার করুন</p>
                </div>
                <div>
                    <a href="{{ route('user.monthly_bazaar.my_orders') }}" class="btn btn-outline-success btn-sm"><i class="fa fa-shopping-bag me-1"></i> আমার মাসিক বাজার অর্ডারসমূহ</a>
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

            <div class="row">
                @forelse($items as $item)
                    @php
                        $available = $item->quantity - $item->sold_quantity;
                    @endphp
                    <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0 rounded-3">
                            @if($item->image && file_exists(public_path('upload/monthly_bazaar/'.$item->image)))
                                <img src="{{ asset('upload/monthly_bazaar/'.$item->image) }}" class="card-img-top" style="height: 200px; object-fit: cover; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 180px; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                                    <i class="fa fa-shopping-basket fa-4x text-success opacity-50"></i>
                                </div>
                            @endif
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="card-title fw-bold text-dark mb-0">{{ $item->title }}</h5>
                                    @if($item->is_unlimited)
                                        <span class="badge bg-success small"><i class="fa fa-infinity me-1"></i> Stock Available</span>
                                    @else
                                        <span class="badge bg-info small">মজুদ: {{ $available }} টি</span>
                                    @endif
                                </div>
                                <h6 class="card-subtitle mb-2 text-primary fw-semibold">{{ $item->package_name }}</h6>
                                <p class="card-text text-muted small flex-grow-1" style="white-space: pre-line;">{{ $item->description ?? 'মাসিক প্রয়োজনীয় খাদ্য পণ্যের সমাহার।' }}</p>

                                <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-2">
                                    <div>
                                        <span class="fs-4 fw-bold text-success">৳{{ number_format($item->price, 2) }}</span>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-success px-3 order-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#orderModal"
                                                data-id="{{ $item->id }}"
                                                data-title="{{ $item->title }}"
                                                data-price="{{ $item->price }}"
                                                data-available="{{ $item->is_unlimited ? 999999 : $available }}">
                                            <i class="fa fa-shopping-cart me-1"></i> অর্ডার করুন
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-5 text-center text-muted">
                        <i class="fa fa-info-circle fa-3x mb-3 text-secondary"></i>
                        <h5>বর্তমানে কোনো মাসিক বাজার প্যাকেজ উপলব্ধ নেই।</h5>
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
