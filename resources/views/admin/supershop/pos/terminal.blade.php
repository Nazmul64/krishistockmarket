@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper p-2 bg-light">
    <div class="container-fluid">
        <!-- Top POS Header Bar -->
        <div class="card mb-2 shadow-sm border-0">
            <div class="card-body py-2 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <h4 class="mb-0 fw-bold text-success d-flex align-items-center">
                        <i class="ti-shopping-cart-full me-2"></i> POS টার্মিনাল
                    </h4>
                    
                    <!-- Branch Selector -->
                    <form method="GET" action="{{ route('admin.supershop.pos') }}" class="d-flex align-items-center gap-1">
                        <select name="branch_id" class="form-select form-select-sm fw-bold border-success" onchange="this.form.submit()">
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}" {{ $selectedBranchId == $b->id ? 'selected' : '' }}>
                                    🏢 {{ $b->code }} - {{ $b->name }}
                                </option>
                            @endforeach
                        </select>
                        <select name="counter_id" class="form-select form-select-sm">
                            @foreach($counters as $c)
                                <option value="{{ $c->id }}" {{ $selectedCounterId == $c->id ? 'selected' : '' }}>
                                    🖥️ {{ $c->counter_name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    @if($activeShift)
                        <span class="badge bg-success-light text-success border border-success py-2 px-3">
                            <i class="fa fa-circle text-success me-1"></i> শিফট চালু (Shift #{{ $activeShift->id }})
                        </span>
                    @else
                        <a href="{{ route('admin.supershop.shifts') }}" class="btn btn-sm btn-outline-danger py-1">
                            <i class="ti-lock me-1"></i> শিফট শুরু করুন (Open Shift)
                        </a>
                    @endif

                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#shortcutsModal">
                        <i class="ti-help-alt me-1"></i> শর্টকাট কি
                    </button>
                    <a href="{{ route('admin.supershop.dashboard') }}" class="btn btn-sm btn-secondary">
                        <i class="ti-arrow-left me-1"></i> ড্যাশবোর্ড
                    </a>
                </div>
            </div>
        </div>

        <!-- POS Workspace Layout: Left Catalog (7 cols) + Right Cart (5 cols) -->
        <div class="row g-2">
            <!-- Left Side: Scanner & Products -->
            <div class="col-lg-7 col-12">
                <div class="card shadow-sm border-0 mb-2">
                    <div class="card-body p-2">
                        <!-- Barcode Scanner Input (Always Focused for HID guns) -->
                        <div class="input-group input-group-lg mb-2">
                            <span class="input-group-text bg-success text-white fw-bold"><i class="fa fa-barcode me-1"></i> স্ক্যানার (F1)</span>
                            <input type="text" id="barcodeInput" class="form-control form-control-lg fw-bold" placeholder="বারকোড স্ক্যান করুন অথবা লিখুন (Enter)..." autofocus autocomplete="off">
                            <button class="btn btn-success" type="button" id="barcodeBtn"><i class="ti-search"></i> যোগ করুন</button>
                        </div>

                        <!-- Live Search & Categories Filter -->
                        <div class="row g-1 align-items-center mb-2">
                            <div class="col-md-7 col-12">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="ti-search"></i></span>
                                    <input type="text" id="searchProductInput" class="form-control" placeholder="পণ্যের নাম দিয়ে দ্রুত খুঁজুন (F2)...">
                                </div>
                            </div>
                            <div class="col-md-5 col-12">
                                <select id="categoryFilter" class="form-select form-select-sm">
                                    <option value="">সকল ক্যাটাগরি (All)</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Product Grid Container -->
                        <div id="productGrid" class="row g-2 overflow-auto" style="max-height: 520px; min-height: 400px;">
                            @foreach($initialProducts as $invItem)
                                @php
                                    $p = $invItem->product;
                                    $price = $invItem->selling_price ?: ($p ? $p->price : 0);
                                    $cost = $invItem->purchase_price ?: round($price * 0.8, 2);
                                @endphp
                                <div class="col-xl-3 col-md-4 col-6 product-card-col">
                                    <div class="card h-100 border text-center p-2 product-card shadow-none" 
                                         style="cursor: pointer; transition: transform 0.1s, box-shadow 0.1s;"
                                         data-id="{{ $p->id }}" 
                                         data-name="{{ addslashes($p->name) }}" 
                                         data-sku="{{ $p->sku }}" 
                                         data-price="{{ $price }}" 
                                         data-cost="{{ $cost }}" 
                                         data-stock="{{ $invItem->stock_quantity }}"
                                         onclick="addToCart(this.dataset)">
                                        <div class="position-relative">
                                            <img src="{{ $p->thumbnail ? asset($p->thumbnail) : asset('assets/images/placeholder.png') }}" class="img-fluid rounded mb-1" style="height: 75px; object-fit: contain;" onerror="this.src='https://placehold.co/80x80?text=Shop'">
                                            <span class="badge {{ $invItem->stock_quantity <= 10 ? 'bg-danger' : 'bg-success' }} position-absolute top-0 end-0 small">
                                                {{ $invItem->stock_quantity }}
                                            </span>
                                        </div>
                                        <div class="fw-bold text-dark text-truncate small" title="{{ $p->name }}">{{ $p->name }}</div>
                                        <div class="text-success fw-bold">৳ {{ number_format($price, 2) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Order Cart & Checkout -->
            <div class="col-lg-5 col-12">
                <div class="card shadow-sm border-0 h-100 d-flex flex-column">
                    <!-- Customer Loyalty Info Bar -->
                    <div class="card-header bg-white py-2 px-3 border-bottom">
                        <div class="d-flex align-items-center gap-1">
                            <span class="input-group-text bg-light border-end-0 py-1"><i class="ti-user text-primary"></i></span>
                            <input type="text" id="custPhoneInput" class="form-control form-control-sm border-start-0" placeholder="কাস্টমার মোবাইল নম্বর (F4)...">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="custLookupBtn"><i class="ti-search"></i></button>
                        </div>
                        <div id="loyaltyDisplay" class="d-none mt-1 p-1 bg-light rounded small text-dark d-flex justify-content-between align-items-center">
                            <div>
                                <strong id="loyaltyName" class="text-primary">Member</strong>
                                <span id="loyaltyTier" class="badge bg-warning text-dark ms-1">SILVER</span>
                            </div>
                            <div>
                                পয়েন্ট: <strong id="loyaltyPoints" class="text-success">0</strong>
                                <button type="button" class="btn btn-xs btn-link text-decoration-none text-danger py-0" id="redeemPointsBtn">[Redeem]</button>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Items Table Container -->
                    <div class="card-body p-2 flex-grow-1 overflow-auto" style="max-height: 340px;">
                        <table class="table table-sm table-borderless mb-0 align-middle">
                            <thead class="table-light small">
                                <tr>
                                    <th>পণ্য</th>
                                    <th class="text-center" style="width: 100px;">পরিমাণ</th>
                                    <th class="text-end" style="width: 80px;">মূল্য</th>
                                    <th class="text-end" style="width: 80px;">মোট</th>
                                    <th class="text-center" style="width: 30px;"></th>
                                </tr>
                            </thead>
                            <tbody id="cartTableBody">
                                <!-- Populated dynamically by JS -->
                            </tbody>
                        </table>
                        <div id="emptyCartNotice" class="text-center text-muted py-5">
                            <i class="ti-shopping-cart fs-1 text-black-50 mb-2 d-block"></i>
                            কার্ট খালি আছে। বারকোড স্ক্যান করুন অথবা পণ্য ক্লিক করুন।
                        </div>
                    </div>

                    <!-- Cart Totals & Checkout Summary -->
                    <div class="card-footer bg-white border-top p-3">
                        <div class="d-flex justify-content-between mb-1 small text-muted">
                            <span>সাবটোটাল (Subtotal):</span>
                            <span class="fw-bold text-dark">৳ <span id="lblSubtotal">0.00</span></span>
                        </div>
                        <div class="d-flex justify-content-between mb-1 small text-muted">
                            <span>ডিসকাউন্ট (Discount):</span>
                            <span class="fw-bold text-danger">- ৳ <span id="lblDiscount">0.00</span></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small text-muted">
                            <span>ভ্যাট (VAT/Tax 0%):</span>
                            <span class="fw-bold text-dark">৳ <span id="lblTax">0.00</span></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-success-light rounded">
                            <span class="fs-5 fw-bold text-success">সর্বমোট প্রদেয় (Net Total):</span>
                            <span class="fs-4 fw-bold text-success">৳ <span id="lblGrandTotal">0.00</span></span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-danger w-25 fw-bold" id="btnClearCart" title="কার্ট খালি করুন (F8)">
                                <i class="ti-trash"></i> খালি
                            </button>
                            <button type="button" class="btn btn-success btn-lg w-75 fw-bold" id="btnOpenCheckout" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                                <i class="ti-check me-1"></i> চেকআউট / পেমেন্ট (F10)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Checkout & Payment Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold text-white"><i class="ti-wallet me-1"></i> পেমেন্ট ও বিক্রয় নিশ্চিতকরণ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center p-3 mb-3 bg-light rounded">
                    <span class="text-muted d-block small">প্রদেয় সর্বমোট টাকা (Grand Total):</span>
                    <h2 class="fw-bold text-success mb-0">৳ <span id="modalGrandTotal">0.00</span></h2>
                </div>

                <!-- Payment Method Tabs -->
                <div class="mb-3">
                    <label class="form-label fw-bold small">পেমেন্ট মেথড নির্বাচন করুন:</label>
                    <div class="d-flex gap-2 flex-wrap" id="paymentMethodGroup">
                        <button type="button" class="btn btn-outline-success active btn-pay-method flex-fill fw-bold" data-method="cash">
                            💵 ক্যাশ (Cash)
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-pay-method flex-fill fw-bold" data-method="bkash">
                            📱 বিকাশ (bKash)
                        </button>
                        <button type="button" class="btn btn-outline-warning text-dark btn-pay-method flex-fill fw-bold" data-method="nagad">
                            ⚡ নগদ (Nagad)
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-pay-method flex-fill fw-bold" data-method="card">
                            💳 কার্ড (POS Card)
                        </button>
                    </div>
                    <input type="hidden" id="selectedPaymentMethod" value="cash">
                </div>

                <!-- Cash Quick Buttons -->
                <div class="mb-3" id="quickCashDiv">
                    <label class="form-label fw-bold small">ক্যাশ গ্রহণ (Received Cash):</label>
                    <div class="d-flex gap-1 mb-2">
                        <button type="button" class="btn btn-xs btn-outline-secondary quick-cash-btn" data-amt="exact">সঠিক টাকা</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary quick-cash-btn" data-amt="100">৳১০০</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary quick-cash-btn" data-amt="500">৳৫০০</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary quick-cash-btn" data-amt="1000">৳১০০০</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary quick-cash-btn" data-amt="2000">৳২০০০</button>
                    </div>
                    <input type="number" step="0.01" id="paidAmountInput" class="form-control form-control-lg fw-bold" placeholder="0.00">
                </div>

                <!-- Digital Reference (for bKash / Nagad / Card) -->
                <div class="mb-3 d-none" id="digitalRefDiv">
                    <label class="form-label fw-bold small">ট্রানজ্যাকশন আইডি / রেফারেন্স নম্বর:</label>
                    <input type="text" id="transRefInput" class="form-control" placeholder="যেমন: TRX98124872 / Slip No">
                </div>

                <!-- Change Calculation -->
                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                    <span class="fw-bold text-dark">ফেরতযোগ্য ব্যালেন্স (Change Return):</span>
                    <h4 class="fw-bold text-danger mb-0">৳ <span id="changeReturnAmount">0.00</span></h4>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-success btn-lg fw-bold px-4" id="btnConfirmSale">
                    <i class="ti-printer me-1"></i> সেল সম্পন্ন ও রিসিপ্ট প্রিন্ট
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Shortcuts Modal -->
<div class="modal fade" id="shortcutsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="ti-keyboard me-2"></i> POS কীবোর্ড শর্টকাট গাইড</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <table class="table table-bordered mb-0">
                    <thead class="table-light"><tr><th>শর্টকাট কি</th><th>কাজ</th></tr></thead>
                    <tbody>
                        <tr><td><kbd>F1</kbd></td><td>বারকোড স্ক্যানার ইনপুটে ফোকাস</td></tr>
                        <tr><td><kbd>F2</kbd></td><td>পণ্য সার্চ বক্সে ফোকাস</td></tr>
                        <tr><td><kbd>F4</kbd></td><td>কাস্টমার মোবাইল নম্বর ফোকাস</td></tr>
                        <tr><td><kbd>F8</kbd></td><td>কার্ট সম্পূর্ণ খালি করুন</td></tr>
                        <tr><td><kbd>F10</kbd></td><td>পেমেন্ট ও চেকআউট উইন্ডো ওপেন</td></tr>
                        <tr><td><kbd>Enter</kbd></td><td>বারকোড ইনপুটে এন্টার দিলে পণ্য স্বয়ংক্রিয় যোগ</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
let cart = [];
let customer = null;
let currentBranchId = {{ $selectedBranchId }};
let currentCounterId = {{ $selectedCounterId ?: 'null' }};

// Load saved cart if any
document.addEventListener('DOMContentLoaded', function() {
    focusBarcode();
    renderCart();

    // Barcode input Enter key listener
    document.getElementById('barcodeInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            handleBarcodeScan(this.value.trim());
        }
    });

    document.getElementById('barcodeBtn').addEventListener('click', function() {
        handleBarcodeScan(document.getElementById('barcodeInput').value.trim());
    });

    // Live search input listener
    let searchTimeout;
    document.getElementById('searchProductInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            fetchFilteredProducts();
        }, 300);
    });

    document.getElementById('categoryFilter').addEventListener('change', fetchFilteredProducts);

    // Customer lookup
    document.getElementById('custLookupBtn').addEventListener('click', lookupCustomer);
    document.getElementById('custPhoneInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            lookupCustomer();
        }
    });

    // Clear cart
    document.getElementById('btnClearCart').addEventListener('click', function() {
        if (cart.length === 0) return;
        if (confirm('আপনি কি নিশ্চিত যে সম্পূর্ণ কার্ট খালি করতে চান?')) {
            cart = [];
            renderCart();
        }
    });

    // Payment method buttons
    document.querySelectorAll('.btn-pay-method').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.btn-pay-method').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            let method = this.dataset.method;
            document.getElementById('selectedPaymentMethod').value = method;
            
            if (method === 'cash') {
                document.getElementById('quickCashDiv').classList.remove('d-none');
                document.getElementById('digitalRefDiv').classList.add('d-none');
            } else {
                document.getElementById('quickCashDiv').classList.add('d-none');
                document.getElementById('digitalRefDiv').classList.remove('d-none');
                let gt = calculateTotals().grandTotal;
                document.getElementById('paidAmountInput').value = gt;
                calculateChange();
            }
        });
    });

    // Quick cash buttons
    document.querySelectorAll('.quick-cash-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            let amt = this.dataset.amt;
            let gt = calculateTotals().grandTotal;
            if (amt === 'exact') {
                document.getElementById('paidAmountInput').value = gt;
            } else {
                document.getElementById('paidAmountInput').value = parseFloat(amt);
            }
            calculateChange();
        });
    });

    document.getElementById('paidAmountInput').addEventListener('input', calculateChange);

    // Confirm Sale button
    document.getElementById('btnConfirmSale').addEventListener('click', processCheckout);

    // Global keyboard shortcuts
    window.addEventListener('keydown', function(e) {
        if (e.key === 'F1') {
            e.preventDefault();
            focusBarcode();
        } else if (e.key === 'F2') {
            e.preventDefault();
            document.getElementById('searchProductInput').focus();
        } else if (e.key === 'F4') {
            e.preventDefault();
            document.getElementById('custPhoneInput').focus();
        } else if (e.key === 'F8') {
            e.preventDefault();
            document.getElementById('btnClearCart').click();
        } else if (e.key === 'F10') {
            e.preventDefault();
            document.getElementById('btnOpenCheckout').click();
        }
    });
});

function focusBarcode() {
    let inp = document.getElementById('barcodeInput');
    if (inp) {
        inp.focus();
        inp.select();
    }
}

function handleBarcodeScan(code) {
    if (!code) return;
    fetch(`{{ route('admin.supershop.pos.barcode') }}?barcode=${encodeURIComponent(code)}&branch_id=${currentBranchId}`)
        .then(res => res.json())
        .then(data => {
            if (data.success && data.product) {
                addToCart(data.product);
                document.getElementById('barcodeInput').value = '';
                focusBarcode();
            } else {
                alert(data.message || 'পণ্য পাওয়া যায়নি!');
                document.getElementById('barcodeInput').select();
            }
        })
        .catch(err => {
            alert('সার্ভারে যোগাযোগ করতে ব্যর্থ হয়েছে!');
        });
}

function fetchFilteredProducts() {
    let q = document.getElementById('searchProductInput').value.trim();
    let cat = document.getElementById('categoryFilter').value;

    fetch(`{{ route('admin.supershop.pos.search') }}?q=${encodeURIComponent(q)}&category_id=${cat}&branch_id=${currentBranchId}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderProductGrid(data.products);
            }
        });
}

function renderProductGrid(products) {
    let grid = document.getElementById('productGrid');
    if (products.length === 0) {
        grid.innerHTML = '<div class="col-12 text-center text-muted py-4">কোনো পণ্য পাওয়া যায়নি।</div>';
        return;
    }

    grid.innerHTML = products.map(p => `
        <div class="col-xl-3 col-md-4 col-6 product-card-col">
            <div class="card h-100 border text-center p-2 product-card shadow-none" 
                 style="cursor: pointer;"
                 onclick='addToCart(${JSON.stringify(p)})'>
                <div class="position-relative">
                    <img src="${p.image || 'https://placehold.co/80x80?text=Shop'}" class="img-fluid rounded mb-1" style="height: 75px; object-fit: contain;">
                    <span class="badge ${p.stock <= 10 ? 'bg-danger' : 'bg-success'} position-absolute top-0 end-0 small">
                        ${p.stock}
                    </span>
                </div>
                <div class="fw-bold text-dark text-truncate small">${p.name}</div>
                <div class="text-success fw-bold">৳ ${parseFloat(p.price).toFixed(2)}</div>
            </div>
        </div>
    `).join('');
}

function addToCart(item) {
    let id = parseInt(item.id);
    let existing = cart.find(ci => ci.id === id);

    if (existing) {
        existing.qty += 1;
    } else {
        cart.push({
            id: id,
            name: item.name,
            sku: item.sku || '',
            price: parseFloat(item.price),
            cost_price: parseFloat(item.cost_price || (item.price * 0.8)),
            qty: 1
        });
    }

    renderCart();
}

function updateQty(id, delta) {
    let item = cart.find(ci => ci.id === id);
    if (!item) return;
    item.qty += delta;
    if (item.qty <= 0) {
        cart = cart.filter(ci => ci.id !== id);
    }
    renderCart();
}

function removeFromCart(id) {
    cart = cart.filter(ci => ci.id !== id);
    renderCart();
}

function calculateTotals() {
    let subtotal = 0;
    cart.forEach(ci => {
        subtotal += ci.price * ci.qty;
    });

    let discount = 0;
    let tax = 0;
    let grandTotal = Math.max(0, subtotal - discount + tax);

    return { subtotal, discount, tax, grandTotal };
}

function renderCart() {
    let tbody = document.getElementById('cartTableBody');
    let emptyNotice = document.getElementById('emptyCartNotice');

    if (cart.length === 0) {
        tbody.innerHTML = '';
        emptyNotice.classList.remove('d-none');
    } else {
        emptyNotice.classList.add('d-none');
        tbody.innerHTML = cart.map(item => `
            <tr class="border-bottom">
                <td>
                    <div class="fw-bold small text-dark">${item.name}</div>
                    <div class="text-muted smaller">৳${item.price.toFixed(2)}</div>
                </td>
                <td class="text-center">
                    <div class="btn-group btn-group-xs">
                        <button type="button" class="btn btn-outline-secondary py-0 px-2" onclick="updateQty(${item.id}, -1)">-</button>
                        <span class="btn btn-light py-0 px-2 fw-bold disabled text-dark">${item.qty}</span>
                        <button type="button" class="btn btn-outline-secondary py-0 px-2" onclick="updateQty(${item.id}, 1)">+</button>
                    </div>
                </td>
                <td class="text-end small">৳${item.price.toFixed(2)}</td>
                <td class="text-end fw-bold text-success small">৳${(item.price * item.qty).toFixed(2)}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-link text-danger p-0" onclick="removeFromCart(${item.id})">
                        <i class="ti-close"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    let totals = calculateTotals();
    document.getElementById('lblSubtotal').innerText = totals.subtotal.toFixed(2);
    document.getElementById('lblDiscount').innerText = totals.discount.toFixed(2);
    document.getElementById('lblTax').innerText = totals.tax.toFixed(2);
    document.getElementById('lblGrandTotal').innerText = totals.grandTotal.toFixed(2);

    document.getElementById('modalGrandTotal').innerText = totals.grandTotal.toFixed(2);
    calculateChange();
}

function calculateChange() {
    let gt = calculateTotals().grandTotal;
    let paid = parseFloat(document.getElementById('paidAmountInput').value) || 0;
    let change = Math.max(0, paid - gt);
    document.getElementById('changeReturnAmount').innerText = change.toFixed(2);
}

function lookupCustomer() {
    let phone = document.getElementById('custPhoneInput').value.trim();
    if (!phone) return;

    fetch(`{{ route('admin.supershop.pos.customer_lookup') }}?phone=${encodeURIComponent(phone)}`)
        .then(res => res.json())
        .then(data => {
            let display = document.getElementById('loyaltyDisplay');
            if (data.found) {
                customer = data;
                document.getElementById('loyaltyName').innerText = data.name;
                document.getElementById('loyaltyTier').innerText = data.tier;
                document.getElementById('loyaltyPoints').innerText = data.available_points;
                display.classList.remove('d-none');
            } else {
                customer = { phone: phone, name: 'Guest Customer' };
                document.getElementById('loyaltyName').innerText = phone;
                document.getElementById('loyaltyTier').innerText = 'NEW GUEST';
                document.getElementById('loyaltyPoints').innerText = '0';
                display.classList.remove('d-none');
            }
        });
}

function processCheckout() {
    let totals = calculateTotals();
    if (cart.length === 0) {
        alert('কার্টে কোনো পণ্য নেই!');
        return;
    }

    let method = document.getElementById('selectedPaymentMethod').value;
    let paid = parseFloat(document.getElementById('paidAmountInput').value) || totals.grandTotal;

    if (paid < totals.grandTotal) {
        alert('পরিশোধিত টাকার পরিমাণ সর্বমোট বিলের চেয়ে কম হতে পারবে না!');
        return;
    }

    let btn = document.getElementById('btnConfirmSale');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> প্রসেসিং...';

    let payload = {
        _token: '{{ csrf_token() }}',
        branch_id: currentBranchId,
        counter_id: currentCounterId,
        items: cart,
        subtotal: totals.subtotal,
        discount_amount: totals.discount,
        tax_amount: totals.tax,
        grand_total: totals.grandTotal,
        paid_amount: paid,
        payment_method: method,
        payment_ref: document.getElementById('transRefInput').value,
        customer_phone: customer ? customer.phone : document.getElementById('custPhoneInput').value,
        customer_name: customer ? customer.name : ''
    };

    fetch('{{ route('admin.supershop.pos.checkout') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="ti-printer me-1"></i> সেল সম্পন্ন ও রিসিপ্ট প্রিন্ট';

        if (data.success) {
            // Close checkout modal
            let modalEl = document.getElementById('checkoutModal');
            let modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();

            // Open thermal receipt popup
            window.open(data.receipt_url, '_blank', 'width=400,height=600');

            // Reset cart
            cart = [];
            customer = null;
            document.getElementById('custPhoneInput').value = '';
            document.getElementById('loyaltyDisplay').classList.add('d-none');
            document.getElementById('paidAmountInput').value = '';
            document.getElementById('transRefInput').value = '';
            renderCart();
            focusBarcode();
        } else {
            alert(data.message || 'বিক্রয় প্রক্রিয়াকরণ ব্যর্থ হয়েছে!');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="ti-printer me-1"></i> সেল সম্পন্ন ও রিসিপ্ট প্রিন্ট';
        alert('সার্ভারে যোগাযোগ করতে সমস্যা হয়েছে!');
    });
}
</script>
@endsection

