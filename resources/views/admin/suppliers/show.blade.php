@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
<div class="container-full">
    <div class="content-header">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h3 class="page-title">সাপ্লায়ার অ্যাকাউন্ট স্টেটমেন্ট ও লেজার</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home-outline"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.suppliers.index') }}">সাপ্লায়ার তালিকা</a></li>
                    <li class="breadcrumb-item active">{{ $supplier->supplierProfile->company_name ?? $supplier->name }}</li>
                </ol>
            </div>
            <div>
                <button type="button" class="btn btn-success btn-sm me-2" data-bs-toggle="modal" data-bs-target="#paymentModal">
                    <i class="fa fa-plus-circle me-1"></i> পেমেন্ট এন্ট্রি করুন
                </button>
                <a href="{{ route('admin.suppliers.statement.print', $supplier->id) }}" target="_blank" class="btn btn-primary btn-sm me-2">
                    <i class="fa fa-print me-1"></i> স্টেটমেন্ট প্রিন্ট / PDF
                </a>
                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i> তালিকা</a>
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
                <strong>ত্রুটি!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Supplier Info & Metrics Card -->
        <div class="row">
            <div class="col-lg-4 col-12">
                <div class="box box-solid bg-dark">
                    <div class="box-header with-border">
                        <h4 class="box-title text-white"><i class="fa fa-user me-2"></i> সাপ্লায়ার প্রোফাইল</h4>
                    </div>
                    <div class="box-body">
                        <h3 class="mb-1 text-warning">{{ $supplier->supplierProfile->company_name ?? 'N/A' }}</h3>
                        <p class="mb-1"><strong>আইডি:</strong> <span class="badge badge-info">{{ $supplier->supplierProfile->supplier_code ?? 'N/A' }}</span></p>
                        <p class="mb-1"><strong>সাপ্লায়ারের নাম:</strong> {{ $supplier->name }}</p>
                        <p class="mb-1"><strong>মোবাইল:</strong> {{ $supplier->phone }}</p>
                        <p class="mb-1"><strong>ইমেইল:</strong> {{ $supplier->email }}</p>
                        <p class="mb-1"><strong>ঠিকানা:</strong> {{ $supplier->supplierProfile->district_thana ?? '' }}, {{ $supplier->supplierProfile->address ?? '' }}</p>
                        <p class="mb-0"><strong>হিসাব খোলার তারিখ:</strong> {{ $supplier->supplierProfile->opening_date ?? $supplier->created_at->format('Y-m-d') }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-12">
                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="box bg-secondary-light">
                            <div class="box-body text-center">
                                <h5 class="text-muted">প্রারম্ভিক জের (Opening)</h5>
                                <h3 class="fw-bold">৳{{ number_format($openingBalance, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="box bg-info-light">
                            <div class="box-body text-center">
                                <h5 class="text-muted">মোট সরবরাহ (Product Supply)</h5>
                                <h3 class="fw-bold text-info">৳{{ number_format($totalSupply, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="box bg-success-light">
                            <div class="box-body text-center">
                                <h5 class="text-muted">মোট পরিশোধ (Total Paid)</h5>
                                <h3 class="fw-bold text-success">৳{{ number_format($totalPaid, 2) }}</h3>
                                <small class="text-muted">(Cash: ৳{{ number_format($cashPaid) }} | Bank: ৳{{ number_format($bankPaid) }})</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box bg-danger">
                    <div class="box-body d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 text-white">বর্তমান বকেয়া / পাওনা (Current Balance / Due)</h4>
                            <p class="mb-0 text-white-50">Opening Balance + Total Supply - Total Paid</p>
                        </div>
                        <div>
                            <h2 class="mb-0 text-white fw-bold">৳{{ number_format($currentBalance, 2) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Box -->
        <div class="box collapsed-box">
            <div class="box-header with-border">
                <h4 class="box-title"><i class="fa fa-filter me-2"></i> ফিল্টার অপশন</h4>
                <div class="box-controls pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="ti-plus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <form action="{{ route('admin.suppliers.show', $supplier->id) }}" method="GET" class="row">
                    <div class="col-md-3 col-12">
                        <label class="form-label">শুরুর তারিখ</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3 col-12">
                        <label class="form-label">শেষ তারিখ</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3 col-12">
                        <label class="form-label">পণ্যের নাম</label>
                        <input type="text" name="product_name" class="form-control" placeholder="যেমন: চাল" value="{{ request('product_name') }}">
                    </div>
                    <div class="col-md-3 col-12">
                        <label class="form-label">পেমেন্ট মেথড</label>
                        <select name="payment_method" class="form-control">
                            <option value="">সকল মেথড</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank" {{ request('payment_method') == 'bank' ? 'selected' : '' }}>Bank</option>
                        </select>
                    </div>
                    <div class="col-12 mt-3 text-end">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> ফিল্টার করুন</button>
                        <a href="{{ route('admin.suppliers.show', $supplier->id) }}" class="btn btn-secondary btn-sm">রিসেট</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Product-wise Summary Table -->
        <div class="row">
            <div class="col-12 col-xl-5">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title"><i class="fa fa-th-list me-2"></i> প্রোডাক্ট ভিত্তিক হিসাব (Product Summary)</h4>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>পণ্য</th>
                                        <th>পরিমাণ</th>
                                        <th>গড় রেট</th>
                                        <th>মোট টাকা</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($productSummary as $item)
                                        <tr>
                                            <td><strong>{{ $item['product_name'] }}</strong></td>
                                            <td>{{ floatval($item['total_quantity']) }} {{ $item['unit'] }}</td>
                                            <td>৳{{ number_format($item['avg_rate'], 2) }}</td>
                                            <td>৳{{ number_format($item['total_amount'], 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">কোন অনুমোদিত পণ্য সরবরাহ রেকর্ড নেই</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Full Transaction Statement Table -->
            <div class="col-12 col-xl-7">
                <div class="box">
                    <div class="box-header with-border d-flex justify-content-between align-items-center">
                        <h4 class="box-title"><i class="fa fa-file-text-o me-2"></i> অ্যাকাউন্ট স্টেটমেন্ট ট্রানজেকশন লেজার</h4>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th>তারিখ</th>
                                        <th>বিবরণ (Description)</th>
                                        <th>রেফারেন্স / ইনভয়েস</th>
                                        <th>ডেবিট (+Supply)</th>
                                        <th>ক্রেডিট (-Paid)</th>
                                        <th>ব্যালেন্স (Balance)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ledger as $row)
                                        <tr>
                                            <td>{{ $row['date'] }}</td>
                                            <td>
                                                {{ $row['description'] }}
                                                @if(isset($row['id']))
                                                    <a href="{{ route('admin.suppliers.invoice.print', $row['id']) }}" target="_blank" class="ms-1 badge badge-sm badge-info" title="ইনভয়েস প্রিন্ট">
                                                        <i class="fa fa-print"></i> চালান
                                                    </a>
                                                @endif
                                            </td>
                                            <td>{{ $row['ref'] }}</td>
                                            <td class="text-danger fw-bold">
                                                {{ $row['debit'] > 0 ? '৳' . number_format($row['debit'], 2) : '-' }}
                                            </td>
                                            <td class="text-success fw-bold">
                                                {{ $row['credit'] > 0 ? '৳' . number_format($row['credit'], 2) : '-' }}
                                            </td>
                                            <td class="fw-bold">৳{{ number_format($row['balance'], 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">কোন ট্রানজেকশন পাওয়া যায়নি!</td>
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

<!-- Modal: Add Payment -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fa fa-money me-2"></i> সাপ্লায়ারকে পেমেন্ট প্রদান করুন</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.suppliers.payment.store') }}" method="POST">
                @csrf
                <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">পেমেন্টের তারিখ <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">পেমেন্ট মেথড <span class="text-danger">*</span></label>
                        <select name="payment_method" id="paymentMethodSelect" class="form-control" required onchange="toggleBankFields()">
                            <option value="cash">Cash (ক্যাশ পেমেন্ট)</option>
                            <option value="bank">Bank (ব্যাংক ট্রান্সফার/চেক)</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">টাকার পরিমাণ (Amount ৳) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" class="form-control" placeholder="0.00" required>
                    </div>

                    <div id="bankFields" style="display: none;">
                        <div class="form-group mb-3">
                            <label class="form-label">ব্যাংকের নাম <span class="text-danger">*</span></label>
                            <input type="text" name="bank_name" class="form-control" placeholder="যেমন: ইসলামী ব্যাংক বাংলাদেশ লিমিটেড">
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">অ্যাকাউন্ট নাম</label>
                            <input type="text" name="account_name" class="form-control" placeholder="অ্যাকাউন্ট হোল্ডার নাম">
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">অ্যাকাউন্ট নম্বর / চেক নম্বর</label>
                            <input type="text" name="account_number" class="form-control" placeholder="অ্যাকাউন্ট বা চেক নম্বর">
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">ট্রানজেকশন আইডি / রেফারেন্স</label>
                            <input type="text" name="transaction_id" class="form-control" placeholder="TxID / Ref No">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">পেমেন্ট নোট / বিবরণ</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="প্রয়োজনীয় মন্তব্য"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> পেমেন্ট নিশ্চিত করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleBankFields() {
        var method = document.getElementById('paymentMethodSelect').value;
        var bankFields = document.getElementById('bankFields');
        if (method === 'bank') {
            bankFields.style.display = 'block';
        } else {
            bankFields.style.display = 'none';
        }
    }
</script>
@endsection
