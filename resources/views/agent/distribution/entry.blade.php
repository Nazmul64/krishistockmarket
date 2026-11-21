@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-qrcode text-primary me-2"></i>Customer Market Collection & Distribution Entry
                    </h3>
                    <p class="text-muted mb-0">এজেন্ট পয়েন্ট: <strong>{{ $point->name }}</strong> ({{ $point->code }}) | কার্ড স্ক্যান, গ্রাহক যাচাই ও বাজার বিতরণ</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('agent.distribution.daily_register') }}" class="btn btn-outline-info btn-sm">
                        <i class="fa fa-book me-1"></i> আজকের বিতরণ রেজিস্টার
                    </a>
                    <a href="{{ route('agent.distribution.customers') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-users me-1"></i> গ্রাহক তালিকা
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

            <!-- Step 1: Customer Search & Verification Bar (Section 2 & 2.1) -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="card-title fw-bold text-white mb-0 font-size-15">
                        <i class="fa fa-search me-2 text-white"></i>ধাপ ১: কার্ডহোল্ডার গ্রাহক শনাক্তকরণ ও ভেরিফিকেশন (Customer Identification)
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row align-items-center g-3">
                        <div class="col-lg-8">
                            <label class="form-label fw-semibold text-dark">
                                Card Number / Customer ID / Phone / QR Code Scan <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light"><i class="fa fa-id-card text-primary"></i></span>
                                <input type="text" id="customerSearchInput" class="form-control" 
                                       placeholder="কার্ড নম্বর (CH-XXXXX), কাস্টমার আইডি অথবা মোবাইল নম্বর লিখুন..." 
                                       value="{{ $preloadedCard ?? '' }}" autofocus>
                                <button type="button" id="btnVerifyCustomer" class="btn btn-primary px-4 fw-semibold">
                                    <i class="fa fa-check-circle me-1"></i> যাচাই করুন (Verify)
                                </button>
                            </div>
                            <small class="text-muted mt-1 d-block">
                                <i class="fa fa-info-circle me-1"></i> কিউআর কোড স্ক্যানার বা কীবোর্ডে টাইপ করে এন্টার চাপুন।
                            </small>
                        </div>
                        <div class="col-lg-4 text-center border-start">
                            <div class="p-2">
                                <i class="fa fa-shield fa-2x text-success mb-1"></i>
                                <div class="fw-bold font-size-13 text-dark">Zero-Trust Duplicate Protection</div>
                                <small class="text-muted font-size-11">শুধুমাত্র এই পয়েন্টে নির্ধারিত সক্রিয় কার্ডহোল্ডারদের বাজার প্রদান করা সম্ভব।</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Profile Info Card (Hidden by default, shown upon AJAX verification) -->
            <div id="customerInfoCard" class="card border-0 shadow-sm rounded-3 mb-4" style="display: none;">
                <div class="card-body p-4 bg-light border-start border-4 border-success">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center mb-3 mb-md-0">
                            <img id="custPhoto" src="{{ asset('frontend/images/default-avatar.png') }}" 
                                 class="rounded-circle shadow-sm border border-3 border-white" style="width: 100px; height: 100px; object-fit: cover;" alt="Photo">
                        </div>
                        <div class="col-md-6">
                            <h4 id="custName" class="fw-bold text-dark mb-1">Customer Name</h4>
                            <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                                <span class="badge bg-primary px-3 py-1 font-size-12 fw-bold" id="custCardNumber">CH-0000</span>
                                <span class="badge bg-info-light text-info font-size-12" id="custCustomerId">ID: 0000</span>
                                <span class="badge bg-success font-size-12" id="custMembership">Active Member</span>
                            </div>
                            <div class="text-muted font-size-13">
                                <div><i class="fa fa-phone text-success me-1"></i> <span id="custPhone">017XXXXXXXX</span></div>
                                <div><i class="fa fa-map-marker text-danger me-1"></i> <span id="custAddress">Address</span> (এলাকা: <strong id="custArea">Area</strong>)</div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <div class="p-3 bg-white rounded-3 shadow-sm text-start font-size-13">
                                <div class="text-muted font-size-11 text-uppercase fw-semibold mb-1">Current Month Status:</div>
                                <div id="custMonthStatusBadge" class="fw-bold font-size-14 mb-2">Not Collected</div>
                                <div class="border-top pt-2 text-muted font-size-12">
                                    <div>পূর্বে মোট বাজার সংগ্রহ: <strong id="custTotalCollections" class="text-dark">0</strong> বার</div>
                                    <div>সর্বশেষ সংগ্রহের তারিখ: <strong id="custLastDate" class="text-dark">None</strong></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Distribution Entry Form (Hidden until Customer Verified) -->
            <form id="distributionForm" action="{{ route('agent.distribution.store') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="user_id" id="hiddenUserId">
                <input type="hidden" name="request_id" id="hiddenRequestId">

                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h5 class="card-title fw-bold text-dark mb-0 font-size-15">
                                <i class="fa fa-shopping-basket text-primary me-2"></i>ধাপ ২: বাজার নির্বাচন ও পণ্যের বিবরণ (Market & Products Entry)
                            </h5>
                            <span class="badge bg-success-light text-success px-3 py-2 font-size-12 fw-bold">
                                <i class="fa fa-check-circle me-1"></i> গ্রাহক ভেরিফাইড
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Top Market Attributes -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Market Type (বাজারের ধরন) <span class="text-danger">*</span></label>
                                <select name="market_type" id="marketTypeSelect" class="form-select" required>
                                    <option value="monthly_market" selected>Monthly Market (মাসিক বাজার)</option>
                                    <option value="weekly_market">Weekly Market (সাপ্তাহিক বাজার)</option>
                                    <option value="special_market">Special Market (বিশেষ বাজার)</option>
                                    <option value="emergency_market">Emergency Market (জরুরী সহায়তা বাজার)</option>
                                    <option value="additional_collection">Additional Collection (অতিরিক্ত সংগ্রহ)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Collection Date (সংগ্রহের তারিখ) <span class="text-danger">*</span></label>
                                <input type="date" name="collection_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Delivery / Benefit Scheme <span class="text-danger">*</span></label>
                                <select name="delivery_type" class="form-select" required>
                                    <option value="subsidized" selected>Subsidized (ভর্তুকিযুক্ত বাজার)</option>
                                    <option value="free">Free / Gift (সম্পূর্ণ বিনামূল্যে)</option>
                                    <option value="paid">Paid (নগদ মূল্যে ক্রয়)</option>
                                    <option value="due">Credit / Due (বাকিতে সরবরাহ)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Verification Method <span class="text-danger">*</span></label>
                                <select name="verification_method" class="form-select" required>
                                    <option value="physical_card" selected>শারীরিক কার্ড প্রদর্শন (Physical Card)</option>
                                    <option value="qr_code">QR Code Scan (কিউআর কোড স্ক্যান)</option>
                                    <option value="otp_sms">Mobile / OTP (মোবাইল যাচাই)</option>
                                    <option value="cardholder_signature">গ্রাহকের স্বাক্ষর (Signature)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Product Selection Table -->
                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                            <i class="fa fa-cubes text-primary me-1"></i> বিতরণকৃত পণ্যের তালিকা (Products to Deliver)
                        </h6>

                        <div class="table-responsive mb-3">
                            <table class="table table-bordered align-middle font-size-13 mb-0" id="productsTable">
                                <thead class="table-light font-size-12 text-uppercase">
                                    <tr>
                                        <th style="min-width: 200px;">পণ্যের নাম (Product Name)</th>
                                        <th style="width: 110px;">একক</th>
                                        <th class="text-end" style="width: 120px;">অনুমোদিত (Approved)</th>
                                        <th class="text-end" style="width: 140px;">বিতরণ (Deliver Qty) <span class="text-danger">*</span></th>
                                        <th class="text-end" style="width: 120px;">মূল্য হার (Rate ৳)</th>
                                        <th class="text-end" style="width: 130px;">মোট মূল্য (Total ৳)</th>
                                        <th class="text-end" style="width: 120px;">অবশিষ্ট (Remaining)</th>
                                        <th class="text-center" style="width: 60px;">অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody id="productsTableBody">
                                    <!-- Dynamic rows populated via verifyCustomer or manually added -->
                                </tbody>
                                <tfoot class="table-light fw-bold font-size-14">
                                    <tr>
                                        <td colspan="5" class="text-end text-dark">Grand Total Distribution Value (মোট বাজার মূল্য):</td>
                                        <td class="text-end text-primary fw-bold font-size-16" id="grandTotalText">৳০.০০</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="mb-4">
                            <button type="button" id="btnAddManualProduct" class="btn btn-outline-primary btn-sm">
                                <i class="fa fa-plus me-1"></i> তালিকায় আরও পণ্য যোগ করুন
                            </button>
                        </div>

                        <!-- Collector & Settlement Details -->
                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                            <i class="fa fa-user-check text-primary me-1"></i> ডেলিভারি ও গ্রহণকারীর তথ্য
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Collected By (পণ্য গ্রহণকারী ব্যক্তির নাম) <span class="text-danger">*</span></label>
                                <input type="text" name="collected_by" id="collectedByName" class="form-control" required>
                                <small class="text-muted">গ্রাহক নিজে অথবা অনুমোদিত প্রতিনিধি</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Collector Phone (গ্রহণকারীর মোবাইল নম্বর)</label>
                                <input type="text" name="collector_phone" id="collectorPhone" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Partial Collection Flag</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_partial" id="isPartialCheck" value="1">
                                    <label class="form-check-label fw-semibold text-warning" for="isPartialCheck">আংশিক বিতরণ (Partially Collected)</label>
                                </div>
                                <small class="text-muted">বাকি পণ্য পরবর্তীতে সংগ্রহ করবে</small>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Delivery Remarks / Instructions (মন্তব্য)</label>
                                <input type="text" name="remarks" class="form-control" placeholder="যেমন: পণ্য মানসম্মত অবস্থায় সঠিকভাবে বুঝিয়া দেওয়া হইল...">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <button type="reset" class="btn btn-light px-4" onclick="location.reload();">রিসেট</button>
                            <button type="submit" class="btn btn-success px-5 py-2 fw-bold font-size-15 shadow">
                                <i class="fa fa-check-circle me-1"></i> বাজার বিতরণ সম্পন্ন করুন ও রসিদ প্রিন্ট করুন
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('customerSearchInput');
    const btnVerify = document.getElementById('btnVerifyCustomer');
    const customerCard = document.getElementById('customerInfoCard');
    const distForm = document.getElementById('distributionForm');
    const tableBody = document.getElementById('productsTableBody');
    const grandTotalText = document.getElementById('grandTotalText');

    let rowIndex = 0;

    function calculateGrandTotal() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.item-delivered-qty').value) || 0;
            const rate = parseFloat(row.querySelector('.item-rate').value) || 0;
            const lineTotal = qty * rate;
            row.querySelector('.item-total').value = lineTotal.toFixed(2);
            row.querySelector('.item-total-text').innerText = '৳' + lineTotal.toFixed(2);
            
            // Calculate remaining
            const appQty = parseFloat(row.querySelector('.item-approved-qty').value) || 0;
            const prevDel = parseFloat(row.querySelector('.item-prev-del').value) || 0;
            const rem = Math.max(0, appQty - (prevDel + qty));
            row.querySelector('.item-remaining').value = rem.toFixed(2);
            row.querySelector('.item-remaining-text').innerText = rem.toFixed(2);

            total += lineTotal;
        });
        grandTotalText.innerText = '৳' + total.toFixed(2);
    }

    function addProductRow(item = {}) {
        const appQty = item.approved_quantity !== undefined ? item.approved_quantity : 0;
        const prevDel = item.previously_delivered !== undefined ? item.previously_delivered : 0;
        const delQty = item.delivered_quantity !== undefined ? item.delivered_quantity : appQty;
        const rate = item.unit_rate !== undefined ? item.unit_rate : 0;
        const unit = item.unit || 'kg';
        const remQty = Math.max(0, appQty - (prevDel + delQty));

        const tr = document.createElement('tr');
        tr.className = 'item-row';
        tr.innerHTML = `
            <td>
                <input type="text" name="items[${rowIndex}][product_name]" class="form-control form-control-sm fw-bold text-dark" 
                       value="${item.product_name || ''}" placeholder="পণ্যের নাম..." required>
                <input type="hidden" name="items[${rowIndex}][request_item_id]" value="${item.request_item_id || ''}">
                <input type="hidden" name="items[${rowIndex}][category]" value="${item.category || 'Grocery'}">
                <input type="hidden" class="item-prev-del" value="${prevDel}">
            </td>
            <td>
                <select name="items[${rowIndex}][unit]" class="form-select form-select-sm">
                    <option value="kg" ${unit === 'kg' ? 'selected' : ''}>kg</option>
                    <option value="liter" ${unit === 'liter' ? 'selected' : ''}>liter</option>
                    <option value="pcs" ${unit === 'pcs' ? 'selected' : ''}>pcs</option>
                    <option value="packet" ${unit === 'packet' ? 'selected' : ''}>packet</option>
                </select>
            </td>
            <td class="text-end">
                <input type="number" step="0.1" name="items[${rowIndex}][approved_quantity]" 
                       class="form-control form-control-sm text-end item-approved-qty bg-light" 
                       value="${appQty}" readonly>
            </td>
            <td class="text-end">
                <input type="number" step="0.1" name="items[${rowIndex}][delivered_qty]" 
                       class="form-control form-control-sm text-end fw-bold text-success item-delivered-qty" 
                       value="${delQty}" min="0.1" required>
            </td>
            <td class="text-end">
                <input type="number" step="0.1" name="items[${rowIndex}][unit_rate]" 
                       class="form-control form-control-sm text-end item-rate" 
                       value="${rate}">
            </td>
            <td class="text-end fw-bold text-dark item-total-text">
                ৳0.00
            </td>
            <input type="hidden" name="items[${rowIndex}][total_amount]" class="item-total" value="0">
            <td class="text-end text-muted">
                <span class="item-remaining-text">${remQty}</span>
                <input type="hidden" name="items[${rowIndex}][remaining_quantity]" class="item-remaining" value="${remQty}">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-danger btn-sm py-0 remove-item-btn">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        `;

        tableBody.appendChild(tr);
        rowIndex++;
        calculateGrandTotal();
    }

    // Trigger verification
    function performVerification() {
        const query = searchInput.value.trim();
        if (!query) {
            alert('অনুগ্রহ করে কার্ড নম্বর বা কাস্টমার আইডি লিখুন।');
            return;
        }

        btnVerify.disabled = true;
        btnVerify.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> যাচাই চলছে...';

        fetch("{{ route('agent.distribution.verify_customer') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ search: query })
        })
        .then(res => res.json())
        .then(data => {
            btnVerify.disabled = false;
            btnVerify.innerHTML = '<i class="fa fa-check-circle me-1"></i> যাচাই করুন (Verify)';

            if (!data.success) {
                alert(data.message || 'গ্রাহক তথ্য পাওয়া যায়নি।');
                customerCard.style.display = 'none';
                distForm.style.display = 'none';
                return;
            }

            const c = data.customer;
            document.getElementById('hiddenUserId').value = c.id;
            document.getElementById('custName').innerText = c.name;
            document.getElementById('custCardNumber').innerText = c.card_number;
            document.getElementById('custCustomerId').innerText = 'ID: ' + c.customer_id;
            document.getElementById('custMembership').innerText = c.membership_status;
            document.getElementById('custPhone').innerText = c.phone;
            document.getElementById('custAddress').innerText = c.address;
            document.getElementById('custArea').innerText = c.area;
            document.getElementById('custTotalCollections').innerText = c.total_collections_count;
            document.getElementById('custLastDate').innerText = c.last_collection_date;
            document.getElementById('custPhoto').src = c.photo;

            document.getElementById('collectedByName').value = c.name;
            document.getElementById('collectorPhone').value = c.phone;

            const badge = document.getElementById('custMonthStatusBadge');
            badge.innerText = c.current_month_status;
            if (c.current_month_status === 'Fully Collected') {
                badge.className = 'badge bg-danger font-size-13';
            } else if (c.current_month_status === 'Partially Collected') {
                badge.className = 'badge bg-warning text-dark font-size-13';
            } else {
                badge.className = 'badge bg-success font-size-13';
            }

            if (data.market_request) {
                document.getElementById('hiddenRequestId').value = data.market_request.id;
            } else {
                document.getElementById('hiddenRequestId').value = '';
            }

            // Populate Products
            tableBody.innerHTML = '';
            if (data.items && data.items.length > 0) {
                data.items.forEach(item => addProductRow(item));
            } else {
                // Add default standard items (Rice, Oil, Lentil, Sugar, Flour)
                addProductRow({ product_name: 'চাল (Rice)', unit: 'kg', approved_quantity: 10, unit_rate: 60, delivered_quantity: 10 });
                addProductRow({ product_name: 'ডাল (Lentil)', unit: 'kg', approved_quantity: 2, unit_rate: 120, delivered_quantity: 2 });
                addProductRow({ product_name: 'সোয়াবিন তেল (Soybean Oil)', unit: 'liter', approved_quantity: 2, unit_rate: 160, delivered_quantity: 2 });
                addProductRow({ product_name: 'চিনি (Sugar)', unit: 'kg', approved_quantity: 1, unit_rate: 130, delivered_quantity: 1 });
            }

            customerCard.style.display = 'block';
            distForm.style.display = 'block';
            distForm.scrollIntoView({ behavior: 'smooth' });
        })
        .catch(err => {
            btnVerify.disabled = false;
            btnVerify.innerHTML = '<i class="fa fa-check-circle me-1"></i> যাচাই করুন (Verify)';
            alert('সার্ভার এরর! পুনরায় চেষ্টা করুন।');
            console.error(err);
        });
    }

    btnVerify.addEventListener('click', performVerification);
    searchInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            performVerification();
        }
    });

    document.getElementById('btnAddManualProduct').addEventListener('click', function () {
        addProductRow();
    });

    tableBody.addEventListener('input', function (e) {
        if (e.target.classList.contains('item-delivered-qty') || e.target.classList.contains('item-rate')) {
            calculateGrandTotal();
        }
    });

    tableBody.addEventListener('click', function (e) {
        if (e.target.closest('.remove-item-btn')) {
            const row = e.target.closest('.item-row');
            if (document.querySelectorAll('.item-row').length > 1) {
                row.remove();
                calculateGrandTotal();
            } else {
                alert('কমপক্ষে একটি পণ্য থাকা আবশ্যক!');
            }
        }
    });

    // Auto verify if preloadedCard present
    if (searchInput.value.trim().length > 0) {
        performVerification();
    }
});
</script>
@endsection
