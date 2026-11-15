@extends('layouts.frontend.shop')

@section('title', 'অর্ডার ট্র্যাকিং - Track Your Order | ' . ($settings['shop_name'] ?? 'Asmi Supershop'))

@section('shop_content')
@section('content')
<div class="shop-track-page py-3">
    <div class="row justify-content-center">
        <div class="col-12 col-md-11 col-lg-9 col-xl-8">

            <!-- Main Tracking Card (Image 2 Reference) -->
            <div class="card border-0 shadow-lg mx-auto" style="border-radius: 24px; overflow: hidden; background: #ffffff;">
                <div class="card-body p-4 p-md-5">
                    <!-- Top Orange Package Icon -->
                    <div class="text-center mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center shadow-sm" 
                             style="width: 76px; height: 76px; border-radius: 22px; background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);">
                            <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        </div>
                        <h2 class="fw-bold text-dark mt-3 mb-1" style="font-size: 26px; letter-spacing: -0.5px;">Track Your Order</h2>
                        <p class="text-muted small mb-0">Enter your order details below to see its status</p>
                    </div>

                    <!-- 3-Step Pill Bar (Image 2) -->
                    <div class="d-flex align-items-center justify-content-between p-2 mb-4 rounded-pill" style="background-color: #fff7ed; border: 1px solid #fed7aa;">
                        <div class="d-flex align-items-center gap-1">
                            <span class="badge rounded-circle bg-warning text-white fw-bold d-inline-flex align-items-center justify-content-center" style="width: 22px; height: 22px; font-size: 11px; background-color: #f97316 !important;">1</span>
                            <span class="fw-semibold text-dark" style="font-size: 11px;">Order ID</span>
                        </div>
                        <div class="text-muted" style="font-size: 10px;">➔</div>
                        <div class="d-flex align-items-center gap-1">
                            <span class="badge rounded-circle bg-warning text-white fw-bold d-inline-flex align-items-center justify-content-center" style="width: 22px; height: 22px; font-size: 11px; background-color: #f97316 !important;">2</span>
                            <span class="fw-semibold text-dark" style="font-size: 11px;">Phone number</span>
                        </div>
                        <div class="text-muted" style="font-size: 10px;">➔</div>
                        <div class="d-flex align-items-center gap-1">
                            <span class="badge rounded-circle {{ $order ? 'bg-success' : 'bg-secondary' }} text-white fw-bold d-inline-flex align-items-center justify-content-center" style="width: 22px; height: 22px; font-size: 11px;">3</span>
                            <span class="fw-semibold {{ $order ? 'text-success' : 'text-muted' }}" style="font-size: 11px;">See status</span>
                        </div>
                    </div>

                        <!-- Search / Input Form -->
                        <form action="{{ route('shop.track_order') }}" method="GET" id="trackOrderForm" onsubmit="return validateTrackForm(event);">
                            <!-- Order ID Input -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-dark mb-1 d-flex justify-content-between">
                                    <span>Order ID</span>
                                    <small class="text-muted fw-normal">(আইডি অথবা ফোন নম্বর দিন)</small>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 12px 0 0 12px; border: 1.5px solid #e5e7eb; border-right: none;">
                                        <span class="fw-bold font-monospace" style="color: #f97316; font-size: 16px;">#</span>
                                    </span>
                                    <input type="text" name="order_id" id="trackOrderId" class="form-control border-start-0 py-2" 
                                           style="border-radius: 0 12px 12px 0; border: 1.5px solid #e5e7eb; border-left: none;" 
                                           placeholder="Enter order id (e.g. ASMI-... or 44621)" 
                                           value="{{ $orderId }}">
                                </div>
                            </div>

                            <!-- Phone Number Input -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-dark mb-1 d-flex justify-content-between">
                                    <span>Phone number</span>
                                    <small class="text-muted fw-normal">(বাধ্যতামূলক নয় যদি অর্ডার আইডি থাকে)</small>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 12px 0 0 12px; border: 1.5px solid #e5e7eb; border-right: none;">
                                        <i class="fa fa-phone" style="color: #f97316;"></i>
                                    </span>
                                    <input type="text" name="phone" id="trackPhone" class="form-control border-start-0 py-2" 
                                           style="border-radius: 0 12px 12px 0; border: 1.5px solid #e5e7eb; border-left: none;" 
                                           placeholder="Enter your phone number (e.g. 01712...)" 
                                           value="{{ $phone }}">
                                </div>
                            </div>

                            <!-- Cloudflare Turnstile Verification Badge (Image 2) -->
                            <div class="p-2 mb-4 rounded-3 border d-flex align-items-center justify-content-between" style="background-color: #fafafa; border-color: #e5e7eb !important;">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-success fs-5">
                                        <i class="fa fa-check-circle"></i>
                                    </span>
                                    <span class="fw-bold small text-dark">Success!</span>
                                </div>
                                <div class="d-flex align-items-center gap-1 text-muted" style="font-size: 10px;">
                                    <span class="badge bg-warning bg-opacity-10 text-dark fw-bold border">CLOUDFLARE</span>
                                    <span>Privacy • Help</span>
                                </div>
                            </div>

                            <!-- Submit Button (Orange Gradient) -->
                            <button type="submit" class="btn w-100 text-white fw-bold py-3 shadow-sm d-flex align-items-center justify-content-center gap-2" 
                                    style="border-radius: 14px; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); border: none; font-size: 16px;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                                Track Order
                            </button>

                            <!-- Security Footnote -->
                            <div class="text-center mt-3">
                                <span class="text-muted" style="font-size: 11px;">
                                    <i class="fa fa-shield text-success me-1"></i> Your information is safe and used only to fetch your order status
                                </span>
                            </div>
                        </form>
                    </div>
                </div>

                @if(!empty($orderList) && $orderList->count() > 1)
                    <!-- Multiple Orders Found Notice -->
                    <div class="card border-0 shadow-sm mt-4" style="border-radius: 16px; background: #ffffff;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="fw-bold text-dark mb-0 small">
                                    <i class="fa fa-list text-warning me-1"></i>আপনার নম্বরে মোট {{ $orderList->count() }} টি অর্ডার পাওয়া গেছে:
                                </h6>
                                <span class="badge bg-light text-muted small border">সাম্প্রতিক অর্ডার</span>
                            </div>
                            <div class="list-group list-group-flush">
                                @foreach($orderList as $o)
                                    <a href="{{ route('shop.track_order', ['order_id' => $o->order_number, 'phone' => $phone]) }}" 
                                       class="list-group-item list-group-item-action py-2 px-2 d-flex justify-content-between align-items-center rounded-2 mb-1 {{ ($order && $order->id == $o->id) ? 'bg-warning bg-opacity-10 border border-warning' : 'border-0' }}">
                                        <div>
                                            <span class="fw-bold text-dark font-monospace" style="font-size: 13px;">#{{ $o->order_number }}</span>
                                            <small class="text-muted d-block" style="font-size: 11px;">{{ $o->created_at->format('d M, Y - h:i A') }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge {{ in_array($o->order_status, ['delivered', 'completed']) ? 'bg-success' : 'bg-primary' }} mb-1" style="font-size: 10px;">
                                                {{ ucfirst($o->order_status) }}
                                            </span>
                                            <div class="fw-bold text-dark font-monospace" style="font-size: 12px;">৳ {{ number_format($o->total_amount, 0) }}</div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- ORDER TRACKING RESULT DETAILS -->
                @if($order)
                    <div class="card border-0 shadow-lg mt-4" style="border-radius: 24px; overflow: hidden; background: #ffffff;">
                        <div class="card-header bg-dark text-white p-4">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <span class="badge bg-warning text-dark fw-bold mb-1">অর্ডার পাওয়া গেছে</span>
                                    <div class="d-flex align-items-center gap-2">
                                        <h4 class="fw-bold mb-0 font-monospace text-warning">#{{ $order->order_number }}</h4>
                                        <button type="button" class="btn btn-sm btn-outline-light py-0 px-2 font-size-11" onclick="navigator.clipboard.writeText('{{ $order->order_number }}'); this.innerText='কপি হয়েছে!'; setTimeout(() => this.innerText='কপি', 1500);" title="কপি করুন">
                                            <i class="fa fa-copy"></i> কপি
                                        </button>
                                    </div>
                                    <small class="text-white-50"><i class="fa fa-calendar me-1"></i>{{ $order->created_at->format('d M, Y - h:i A') }}</small>
                                </div>
                                <div class="text-end">
                                    <div class="text-white-50 small">সর্বমোট মূল্য</div>
                                    <div class="fs-4 fw-bold text-warning font-monospace">৳ {{ number_format($order->total_amount, 0) }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Stepper Status Timeline -->
                            @php
                                $status = $order->order_status;
                                $step = 1;
                                if (in_array($status, ['processing'])) $step = 2;
                                if (in_array($status, ['in_transport', 'shipping'])) $step = 3;
                                if (in_array($status, ['delivered', 'completed'])) $step = 4;
                                if (in_array($status, ['cancelled', 'canceled'])) $step = 0;
                            @endphp

                            @if($step === 0)
                                <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
                                    <i class="fa fa-times-circle fs-3"></i>
                                    <div>
                                        <div class="fw-bold">অর্ডারটি বাতিল করা হয়েছে (Cancelled)</div>
                                        <small>কোনো তথ্যের জন্য আমাদের কাস্টমার সার্ভিসে যোগাযোগ করুন।</small>
                                    </div>
                                </div>
                            @else
                                <div class="order-status-steps mb-4">
                                    <div class="d-flex justify-content-between position-relative mb-2">
                                        <!-- Step 1 -->
                                        <div class="text-center" style="width: 25%;">
                                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center text-white fw-bold mb-1 {{ $step >= 1 ? 'bg-success' : 'bg-light text-muted border' }}" style="width: 36px; height: 36px;">
                                                <i class="fa fa-check"></i>
                                            </div>
                                            <div class="fw-bold text-dark" style="font-size: 11px;">অর্ডার গ্রহণ</div>
                                            <small class="text-muted d-none d-sm-block" style="font-size: 9px;">Confirmed</small>
                                        </div>

                                        <!-- Step 2 -->
                                        <div class="text-center" style="width: 25%;">
                                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center text-white fw-bold mb-1 {{ $step >= 2 ? 'bg-success' : ($step == 1 ? 'bg-warning text-dark' : 'bg-light text-muted border') }}" style="width: 36px; height: 36px;">
                                                <i class="fa {{ $step >= 2 ? 'fa-check' : 'fa-hourglass-half' }}"></i>
                                            </div>
                                            <div class="fw-bold text-dark" style="font-size: 11px;">প্রসেসিং</div>
                                            <small class="text-muted d-none d-sm-block" style="font-size: 9px;">Packaging</small>
                                        </div>

                                        <!-- Step 3 -->
                                        <div class="text-center" style="width: 25%;">
                                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center text-white fw-bold mb-1 {{ $step >= 3 ? 'bg-success' : ($step == 2 ? 'bg-warning text-dark' : 'bg-light text-muted border') }}" style="width: 36px; height: 36px;">
                                                <i class="fa fa-truck"></i>
                                            </div>
                                            <div class="fw-bold text-dark" style="font-size: 11px;">কুরিয়ারে পাঠানো</div>
                                            <small class="text-muted d-none d-sm-block" style="font-size: 9px;">In Transport</small>
                                        </div>

                                        <!-- Step 4 -->
                                        <div class="text-center" style="width: 25%;">
                                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center text-white fw-bold mb-1 {{ $step >= 4 ? 'bg-success' : 'bg-light text-muted border' }}" style="width: 36px; height: 36px;">
                                                <i class="fa fa-home"></i>
                                            </div>
                                            <div class="fw-bold text-dark" style="font-size: 11px;">ডেলিভারি সম্পন্ন</div>
                                            <small class="text-muted d-none d-sm-block" style="font-size: 9px;">Delivered</small>
                                        </div>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $step * 25 }}%;"></div>
                                    </div>
                                </div>
                            @endif

                            <!-- Live Status Highlight Box -->
                            <div class="p-3 rounded-3 mb-4 d-flex align-items-center gap-3" 
                                 style="background-color: {{ $status === 'delivered' || $status === 'completed' ? '#f0fdf4' : ($status === 'cancelled' ? '#fef2f2' : '#eff6ff') }}; 
                                        border: 1.5px solid {{ $status === 'delivered' || $status === 'completed' ? '#86efac' : ($status === 'cancelled' ? '#fca5a5' : '#bfdbfe') }};">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" 
                                     style="width: 46px; height: 46px; background: {{ $status === 'delivered' || $status === 'completed' ? '#16a34a' : ($status === 'cancelled' ? '#dc2626' : ($status === 'shipping' || $status === 'in_transport' ? '#2563eb' : '#f97316')) }};">
                                    @if($status === 'delivered' || $status === 'completed')
                                        <i class="fa fa-check fs-5"></i>
                                    @elseif($status === 'shipping' || $status === 'in_transport')
                                        <i class="fa fa-truck fs-5"></i>
                                    @elseif($status === 'processing')
                                        <i class="fa fa-cogs fs-5"></i>
                                    @elseif($status === 'cancelled')
                                        <i class="fa fa-times fs-5"></i>
                                    @else
                                        <i class="fa fa-clock-o fs-5"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="small text-muted fw-semibold mb-1">অর্ডারের বর্তমান অবস্থা (Live Status):</div>
                                    <div class="fw-bold font-size-16" style="color: {{ $status === 'delivered' || $status === 'completed' ? '#16a34a' : ($status === 'cancelled' ? '#dc2626' : '#1e293b') }};">
                                        @if($status === 'pending')
                                            <span class="badge bg-warning text-dark me-1" style="font-size: 13px;">পেন্ডিং (Pending)</span> - অর্ডার সফলভাবে গ্রহণ করা হয়েছে
                                        @elseif($status === 'processing')
                                            <span class="badge bg-info text-white me-1" style="font-size: 13px;">প্রসেসিং (Processing)</span> - পণ্য প্রস্তুত ও প্যাকেজিং চলছে
                                        @elseif($status === 'shipping' || $status === 'in_transport')
                                            <span class="badge bg-primary text-white me-1" style="font-size: 13px;">ডেলিভারিতে আছে (Shipping)</span> - কুরিয়ারে পাঠানো হয়েছে
                                        @elseif($status === 'delivered' || $status === 'completed')
                                            <span class="badge bg-success text-white me-1" style="font-size: 13px;">ডেলিভারি সম্পন্ন (Delivered)</span> - গ্রাহক পণ্য বুঝে পেয়েছেন
                                        @elseif($status === 'cancelled')
                                            <span class="badge bg-danger text-white me-1" style="font-size: 13px;">বাতিল (Cancelled)</span> - অর্ডারটি বাতিল করা হয়েছে
                                        @else
                                            <span class="badge bg-secondary me-1" style="font-size: 13px;">{{ ucfirst($status) }}</span>
                                        @endif
                                    </div>
                                    <small class="text-secondary d-block mt-1">
                                        @if($status === 'pending')
                                            আপনার অর্ডারটি আমাদের ডাটাবেসে সফলভাবে পেন্ডিং অবস্থায় রয়েছে। শীঘ্রই আমাদের সেলস প্রতিনিধি এটি নিশ্চিত করবেন।
                                        @elseif($status === 'processing')
                                            আপনার পণ্যের মান পরীক্ষা ও প্যাকিং সম্পন্ন হচ্ছে। দ্রুতই এটি কুরিয়ারে হস্তান্তর করা হবে।
                                        @elseif($status === 'shipping' || $status === 'in_transport')
                                            পণ্যটি ডেলিভারি এজেন্টের দায়িত্বে আছে এবং আপনার ঠিকানার উদ্দেশ্যে রওনা হয়েছে।
                                        @elseif($status === 'delivered' || $status === 'completed')
                                            সফলভাবে ডেলিভারি সম্পন্ন হয়েছে। আমাদের সাথে কেনাকাটা করার জন্য ধন্যবাদ!
                                        @endif
                                    </small>
                                </div>
                            </div>

                            <!-- Courier Details (If Dispatched) -->
                            @if($order->courier_name)
                                <div class="p-3 mb-4 rounded-3 border" style="background-color: #f0fdf4; border-color: #bbf7d0 !important;">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                                <i class="fa fa-truck fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="text-muted small">কুরিয়ার সার্ভিস</div>
                                                <h5 class="fw-bold text-success mb-0">{{ $order->courier_name }} Courier</h5>
                                                @if($order->tracking_code)
                                                    <div class="font-monospace small text-dark mt-1">ট্র্যাকিং কোড: <strong>{{ $order->tracking_code }}</strong></div>
                                                @endif
                                            </div>
                                        </div>
                                        @if($order->tracking_code)
                                            <a href="https://steadfast.com.bd/t/{{ $order->tracking_code }}" target="_blank" class="btn btn-sm btn-success fw-bold px-3">
                                                <i class="fa fa-external-link me-1"></i> লাইভ ট্র্যাক করুন
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Customer & Delivery Information -->
                            <div class="row g-3 mb-4">
                                <div class="col-sm-6">
                                    <div class="p-3 rounded-3 bg-light border h-100">
                                        <div class="text-muted small mb-1"><i class="fa fa-user text-primary me-1"></i>গ্রাহকের তথ্য</div>
                                        <div class="fw-bold text-dark">{{ $order->first_name }} {{ $order->last_name }}</div>
                                        <div class="text-muted small font-monospace"><i class="fa fa-phone me-1"></i>{{ $order->phone }}</div>
                                        @if($order->email)
                                            <div class="text-muted small"><i class="fa fa-envelope-o me-1"></i>{{ $order->email }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-3 rounded-3 bg-light border h-100">
                                        <div class="text-muted small mb-1"><i class="fa fa-map-marker text-danger me-1"></i>ডেলিভারি ঠিকানা</div>
                                        <div class="fw-semibold text-dark small">{{ $order->address }}</div>
                                        <div class="text-muted small">{{ $order->city }}</div>
                                        <span class="badge bg-light text-dark border mt-1 font-monospace" style="font-size: 10px;">
                                            পেমেন্ট: {{ strtoupper($order->payment_method ?: 'COD') }} ({{ $order->payment_status === 'paid' ? 'পরিশোধিত' : 'বকেয়া' }})
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Ordered Items List -->
                            <div class="border rounded-3 p-3 mb-4">
                                <div class="fw-bold text-dark small text-uppercase mb-2 border-bottom pb-2">
                                    অর্ডারকৃত পণ্য তালিকা ({{ $order->items->count() }} টি)
                                </div>
                                @foreach($order->items as $item)
                                    <div class="d-flex align-items-center justify-content-between py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $item->product && $item->product->image ? asset($item->product->image) : asset('assets/images/placeholder.png') }}" 
                                                 alt="{{ $item->product_name }}" class="rounded border" style="width: 48px; height: 48px; object-fit: contain !important; background: #ffffff; padding: 2px;">
                                            <div>
                                                <div class="fw-semibold text-dark small">{{ $item->product_name }}</div>
                                                <small class="text-muted font-monospace">৳{{ number_format($item->price, 0) }} × {{ $item->quantity }}</small>
                                            </div>
                                        </div>
                                        <div class="fw-bold text-dark small font-monospace">
                                            ৳ {{ number_format($item->total, 0) }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Order Pricing Summary -->
                            <div class="p-3 rounded-3 bg-light border">
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <span>সাব টোটাল</span>
                                    <span class="fw-semibold text-dark">৳ {{ number_format($order->subtotal, 0) }}</span>
                                </div>
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <span>ডেলিভারি চার্জ</span>
                                    <span class="fw-semibold text-dark">৳ {{ number_format($order->shipping_charge, 0) }}</span>
                                </div>
                                @if($order->discount_amount > 0)
                                    <div class="d-flex justify-content-between small text-success mb-1">
                                        <span>ডিসকাউন্ট</span>
                                        <span class="fw-semibold">-৳ {{ number_format($order->discount_amount, 0) }}</span>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between fw-bold text-dark fs-6 pt-2 border-top mt-2">
                                    <span>মোট প্রদেয় মূল্য</span>
                                    <span class="text-success font-monospace">৳ {{ number_format($order->total_amount, 0) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white p-3 text-center border-top">
                            <span class="text-muted small me-2">যেকোনো সহায়তায় কল করুন:</span>
                            <a href="tel:{{ $settings['hotline'] ?? '09617900200' }}" class="btn btn-outline-success btn-sm rounded-pill px-3">
                                <i class="fa fa-phone me-1"></i> {{ $settings['hotline'] ?? '09617900200' }}
                            </a>
                        </div>
                    </div>
                @elseif(request('order_id') || request('phone'))
                    <!-- Not Found Alert -->
                    <div class="alert alert-warning border-warning shadow-sm mt-4 text-center" style="border-radius: 16px;">
                        <i class="fa fa-exclamation-circle fs-3 text-warning mb-2 d-block"></i>
                        <h5 class="fw-bold text-dark">কোনো অর্ডার খুঁজে পাওয়া যায়নি!</h5>
                        <p class="text-muted small mb-0">অনুগ্রহ করে সঠিক অর্ডার আইডি এবং ফোন নম্বর দিয়ে পুনরায় চেষ্টা করুন।</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function validateTrackForm(e) {
    var oid = (document.getElementById('trackOrderId').value || '').trim();
    var ph = (document.getElementById('trackPhone').value || '').trim();
    if (!oid && !ph) {
        if (e) e.preventDefault();
        alert('অনুগ্রহ করে আপনার অর্ডার আইডি অথবা ফোন নম্বর দিন।');
        return false;
    }
    return true;
}
</script>
@endpush

