@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title">মাসিক বাজার চাহিদা রিকোয়েস্ট (Monthly Market Request)</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home-outline"></i> ড্যাশবোর্ড</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.monthly_market.my_requests') }}">আমার রিকোয়েস্টসমূহ</a></li>
                        <li class="breadcrumb-item active">নতুন বাজার চাহিদা সাবমিট</li>
                    </ol>
                </div>
                <div>
                    <a href="{{ route('user.monthly_market.my_requests') }}" class="btn btn-outline-success btn-sm">
                        <i class="fa fa-list me-1"></i> আমার সকল মাসিক রিকোয়েস্ট
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>সফল!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>নোটিশ:</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($existingRequest)
                <div class="alert alert-warning d-flex align-items-center mb-4">
                    <i class="fa fa-exclamation-triangle fa-2x me-3"></i>
                    <div>
                        <h5 class="mb-1 fw-bold">আপনি ইতিমধ্যে {{ $currentMonth }} {{ $currentYear }} মাসের বাজার রিকোয়েস্ট জমা দিয়েছেন!</h5>
                        <p class="mb-0">রিকোয়েস্ট নং: <strong>{{ $existingRequest->request_no }}</strong> | স্ট্যাটাস: <strong>{{ strtoupper($existingRequest->status) }}</strong> | এরিয়া: <strong>{{ $existingRequest->request_area }}</strong> (এজেন্ট পয়েন্ট: {{ $existingRequest->agent_point_name }})। আপনি বিস্তারিত স্ট্যাটাস দেখতে <a href="{{ route('user.monthly_market.my_requests') }}" class="fw-bold text-dark text-decoration-underline">এখানে ক্লিক করুন</a>।</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('user.monthly_market.store') }}" method="POST" id="marketRequestForm">
                @csrf
                <div class="row">
                    <!-- Request Header Details -->
                    <div class="col-lg-4 col-12">
                        <div class="box">
                            <div class="box-header with-border bg-success">
                                <h4 class="box-title text-white"><i class="fa fa-id-card-o me-2"></i> সদস্য ও এরিয়া তথ্য</h4>
                            </div>
                            <div class="box-body">
                                <div class="mb-3">
                                    <label class="form-label text-muted">সদস্যের নাম</label>
                                    <input type="text" class="form-control bg-light" value="{{ $user->name }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">কার্ড নম্বর (Card ID)</label>
                                    <input type="text" class="form-control bg-light fw-bold text-success" value="{{ $user->card_number ?? $user->username ?? ('CRD-' . $user->id) }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted">মোবাইল নম্বর</label>
                                    <input type="text" class="form-control bg-light" value="{{ $user->phone }}" readonly>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label fw-bold">মাস (Month) <span class="text-danger">*</span></label>
                                        <select name="month" class="form-select" required>
                                            @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $m)
                                                <option value="{{ $m }}" {{ $m == $currentMonth ? 'selected' : '' }}>{{ $m }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label fw-bold">বছর (Year) <span class="text-danger">*</span></label>
                                        <select name="year" class="form-select" required>
                                            @foreach([date('Y'), date('Y')+1] as $y)
                                                <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">রিকোয়েস্ট এরিয়া (Request Area) <span class="text-danger">*</span></label>
                                    <input type="text" name="request_area" id="requestAreaInput" class="form-control" placeholder="যেমন: নওগাঁ, বগুড়া, গাজীপুর, ঢাকা" value="{{ old('request_area', 'নওগাঁ') }}" required list="areaList">
                                    <datalist id="areaList">
                                        <option value="নওগাঁ">
                                        <option value="বগুড়া">
                                        <option value="গাজীপুর">
                                        <option value="ঢাকা">
                                        <option value="রাজশাহী">
                                        <option value="রংপুর">
                                        <option value="দিনাজপুর">
                                        <option value="কুমিল্লা">
                                    </datalist>
                                    <small class="text-muted"><i class="fa fa-info-circle"></i> প্রতিটি Request-এর Area বাধ্যতামূলক।</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">সংগ্রহের এজেন্ট পয়েন্ট (Agent Point) <span class="text-danger">*</span></label>
                                    <select name="agent_point_id" class="form-select" required>
                                        <option value="">-- এজেন্ট পয়েন্ট নির্বাচন করুন --</option>
                                        @foreach($agentPoints as $point)
                                            <option value="{{ $point->id }}" {{ old('agent_point_id') == $point->id ? 'selected' : '' }}>
                                                {{ $point->name }} ({{ $point->area }} - {{ $point->contact_number }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">আপনার মাসিক বাজার এই এজেন্ট পয়েন্ট থেকেই সংগ্রহ করতে হবে।</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">বিশেষ কোনো চাহিদা / মন্তব্য (ঐচ্ছিক)</label>
                                    <textarea name="additional_note" class="form-control" rows="2" placeholder="পণ্য বা প্যাকেজিং সম্পর্কে কোনো বিশেষ অনুরোধ থাকলে লিখুন">{{ old('additional_note') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Multi-Product Request Table -->
                    <div class="col-lg-8 col-12">
                        <!-- Quick Add Presets -->
                        <div class="box mb-3">
                            <div class="box-body py-2">
                                <label class="form-label fw-bold mb-2 text-dark"><i class="fa fa-bolt text-warning me-1"></i> দ্রুত যোগ করুন (Quick Add Common Items):</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($defaultGroceryItems as $preset)
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addPresetItem('{{ $preset['name'] }}', '{{ $preset['unit'] }}', {{ $preset['default_qty'] }})">
                                            + {{ $preset['name'] }} ({{ $preset['default_qty'] }} {{ $preset['unit'] }})
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="box">
                            <div class="box-header with-border d-flex justify-content-between align-items-center bg-light">
                                <h4 class="box-title text-dark"><i class="fa fa-shopping-basket me-2 text-success"></i> চাহিদাকৃত পণ্যের তালিকা (Requested Products)</h4>
                                <button type="button" class="btn btn-sm btn-success" onclick="addNewProductRow()">
                                    <i class="fa fa-plus me-1"></i> নতুন পণ্য যোগ করুন
                                </button>
                            </div>
                            <div class="box-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mb-0 text-center" id="userRequestTable">
                                        <thead class="table-dark">
                                            <tr>
                                                <th style="width: 5%;">#</th>
                                                <th style="width: 45%; text-align: left;">পণ্যের নাম (Product Name) <span class="text-danger">*</span></th>
                                                <th style="width: 25%;">পরিমাণ (Quantity) <span class="text-danger">*</span></th>
                                                <th style="width: 20%;">একক (Unit) <span class="text-danger">*</span></th>
                                                <th style="width: 5%;">অ্যাকশন</th>
                                            </tr>
                                        </thead>
                                        <tbody id="userItemsTbody">
                                            <!-- Row 1 -->
                                            <tr id="req_row_0">
                                                <td class="align-middle fw-bold">1</td>
                                                <td>
                                                    <input type="text" name="items[0][product_name]" class="form-control text-start" value="চাল (Miniket Rice)" placeholder="যেমন: চাল, ডাল, তেল" required>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.5" name="items[0][quantity]" class="form-control text-end item-qty" value="10" placeholder="1" required oninput="calculateTotalItems()">
                                                </td>
                                                <td>
                                                    <select name="items[0][unit]" class="form-select">
                                                        <option value="KG" selected>KG (কেজি)</option>
                                                        <option value="Liter">Liter (লিটার)</option>
                                                        <option value="Bag">Bag (বস্তা)</option>
                                                        <option value="Piece">Piece (পিস)</option>
                                                        <option value="GM">GM (গ্রাম)</option>
                                                    </select>
                                                </td>
                                                <td class="align-middle">
                                                    <button type="button" class="btn btn-sm btn-danger disabled" title="প্রথম সারি মুছে ফেলা যাবে না"><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                            <!-- Row 2 -->
                                            <tr id="req_row_1">
                                                <td class="align-middle fw-bold">2</td>
                                                <td>
                                                    <input type="text" name="items[1][product_name]" class="form-control text-start" value="মসুর ডাল (Lentil)" placeholder="পণ্যের নাম" required>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.5" name="items[1][quantity]" class="form-control text-end item-qty" value="2" placeholder="1" required oninput="calculateTotalItems()">
                                                </td>
                                                <td>
                                                    <select name="items[1][unit]" class="form-select">
                                                        <option value="KG" selected>KG (কেজি)</option>
                                                        <option value="Liter">Liter (লিটার)</option>
                                                        <option value="Bag">Bag (বস্তা)</option>
                                                        <option value="Piece">Piece (পিস)</option>
                                                        <option value="GM">GM (গ্রাম)</option>
                                                    </select>
                                                </td>
                                                <td class="align-middle">
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeProductRow(1)"><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                            <!-- Row 3 -->
                                            <tr id="req_row_2">
                                                <td class="align-middle fw-bold">3</td>
                                                <td>
                                                    <input type="text" name="items[2][product_name]" class="form-control text-start" value="সয়াবিন তেল (Soybean Oil)" placeholder="পণ্যের নাম" required>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.5" name="items[2][quantity]" class="form-control text-end item-qty" value="2" placeholder="1" required oninput="calculateTotalItems()">
                                                </td>
                                                <td>
                                                    <select name="items[2][unit]" class="form-select">
                                                        <option value="KG">KG (কেজি)</option>
                                                        <option value="Liter" selected>Liter (লিটার)</option>
                                                        <option value="Bag">Bag (বস্তা)</option>
                                                        <option value="Piece">Piece (পিস)</option>
                                                        <option value="GM">GM (গ্রাম)</option>
                                                    </select>
                                                </td>
                                                <td class="align-middle">
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeProductRow(2)"><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <th colspan="2" class="text-end fw-bold fs-16 align-middle">মোট আইটেম ও চাহিদা:</th>
                                                <th class="text-end fw-bold fs-16 align-middle text-success" id="totalQtyDisplay">14</th>
                                                <th colspan="2" class="text-start align-middle"><span id="totalItemsCountDisplay">3</span> টি পণ্য তালিকাভুক্ত</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="box-footer d-flex justify-content-between align-items-center">
                                <span class="text-muted"><i class="fa fa-info-circle text-primary me-1"></i> সাবমিট করার পর অ্যাডমিন চাহিদা পর্যালোচনা করে নির্ধারিত এজেন্ট পয়েন্টে আপনার বাজার বরাদ্দ করবেন।</span>
                                <button type="submit" class="btn btn-success btn-lg px-4 shadow">
                                    <i class="fa fa-check-circle me-1"></i> মাসিক বাজার চাহিদা সাবমিট করুন
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>

<script>
    let productRowIndex = 3;

    function addNewProductRow(name = '', unit = 'KG', qty = 1) {
        const tbody = document.getElementById('userItemsTbody');
        const tr = document.createElement('tr');
        tr.id = 'req_row_' + productRowIndex;
        tr.innerHTML = `
            <td class="align-middle fw-bold">${productRowIndex + 1}</td>
            <td>
                <input type="text" name="items[${productRowIndex}][product_name]" class="form-control text-start" value="${name}" placeholder="পণ্যের নাম লিখুন" required>
            </td>
            <td>
                <input type="number" step="0.5" name="items[${productRowIndex}][quantity]" class="form-control text-end item-qty" value="${qty}" placeholder="1" required oninput="calculateTotalItems()">
            </td>
            <td>
                <select name="items[${productRowIndex}][unit]" class="form-select">
                    <option value="KG" ${unit === 'KG' ? 'selected' : ''}>KG (কেজি)</option>
                    <option value="Liter" ${unit === 'Liter' ? 'selected' : ''}>Liter (লিটার)</option>
                    <option value="Bag" ${unit === 'Bag' ? 'selected' : ''}>Bag (বস্তা)</option>
                    <option value="Piece" ${unit === 'Piece' ? 'selected' : ''}>Piece (পিস)</option>
                    <option value="GM" ${unit === 'GM' ? 'selected' : ''}>GM (গ্রাম)</option>
                </select>
            </td>
            <td class="align-middle">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeProductRow(${productRowIndex})"><i class="fa fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(tr);
        productRowIndex++;
        calculateTotalItems();
    }

    function addPresetItem(name, unit, defaultQty) {
        // Check if already in table
        const inputs = document.querySelectorAll('#userItemsTbody input[type="text"]');
        for (let input of inputs) {
            if (input.value.trim().toLowerCase() === name.trim().toLowerCase()) {
                input.focus();
                return;
            }
        }
        addNewProductRow(name, unit, defaultQty);
    }

    function removeProductRow(index) {
        const row = document.getElementById('req_row_' + index);
        if (row) {
            row.remove();
            calculateTotalItems();
        }
    }

    function calculateTotalItems() {
        let totalQty = 0;
        let count = 0;
        const rows = document.querySelectorAll('#userItemsTbody tr');
        rows.forEach(row => {
            const qty = parseFloat(row.querySelector('.item-qty')?.value) || 0;
            totalQty += qty;
            count++;
        });
        document.getElementById('totalQtyDisplay').innerText = totalQty.toLocaleString('en-US');
        document.getElementById('totalItemsCountDisplay').innerText = count;
    }

    document.addEventListener('DOMContentLoaded', calculateTotalItems);
</script>
@endsection
