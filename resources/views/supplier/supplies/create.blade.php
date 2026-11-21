@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
    <div class="content-header">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h3 class="page-title">নতুন পণ্য সরবরাহ পোস্টিং / মাল্টি-প্রোডাক্ট চালান এন্ট্রি</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('supplier.dashboard') }}"><i class="mdi mdi-home-outline"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('supplier.supplies.index') }}">পণ্য সরবরাহ তালিকা</a></li>
                    <li class="breadcrumb-item active">নতুন চালান এন্ট্রি</li>
                </ol>
            </div>
            <div>
                <a href="{{ route('supplier.supplies.index') }}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i> ফিরে যান</a>
            </div>
        </div>
    </div>

    <section class="content">
        <form action="{{ route('supplier.supplies.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Invoice Header Card -->
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border bg-primary">
                            <h4 class="box-title text-white"><i class="fa fa-file-text-o me-2"></i> চালান ও সরবরাহের বিবরণ (Invoice & Supply Header)</h4>
                        </div>
                        <div class="box-body">
                            @if (isset($errors) && $errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-4 col-12 mb-3">
                                    <label class="form-label fw-bold">ইনভয়েস / চালান নম্বর <span class="text-danger">*</span></label>
                                    <input type="text" name="invoice_no" class="form-control" placeholder="যেমন: CH-2026-0042" value="{{ old('invoice_no', 'CH-' . date('ym') . '-' . rand(100, 999)) }}" required>
                                </div>
                                <div class="col-md-4 col-12 mb-3">
                                    <label class="form-label fw-bold">সরবরাহের তারিখ <span class="text-danger">*</span></label>
                                    <input type="date" name="supply_date" class="form-control" value="{{ old('supply_date', date('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-4 col-12 mb-3">
                                    <label class="form-label fw-bold">চালানের ছবি বা PDF কপি</label>
                                    <input type="file" name="invoice_file" class="form-control" accept="image/*,application/pdf">
                                    <small class="text-muted">JPG, PNG, PDF ফাইল (সর্বোচ্চ 10MB)</small>
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <label class="form-label">ডেলিভারি নোট / বাহক ও গাড়ির তথ্য (ঐচ্ছিক)</label>
                                    <input type="text" name="delivery_note" class="form-control" placeholder="যেমন: ট্রাক নম্বর, ড্রাইভারের নাম ও ফোন নম্বর">
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <label class="form-label">মন্তব্য / বিশেষ নোট</label>
                                    <input type="text" name="note" class="form-control" placeholder="পণ্য সম্পর্কে বিশেষ কোনো নির্দেশনা থাকলে লিখুন">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Multi-Product Table Card -->
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border d-flex justify-content-between align-items-center bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-cubes me-2"></i> সরবরাহকৃত পণ্যের তালিকা (Multiple Products Entry)</h4>
                            <button type="button" class="btn btn-sm btn-success" onclick="addRow()">
                                <i class="fa fa-plus me-1"></i> আরো পণ্য যোগ করুন (Add Row)
                            </button>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mb-0 text-center" id="productsTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 30%; text-align: left;">পণ্যের নাম (Product Name) <span class="text-danger">*</span></th>
                                            <th style="width: 15%;">ক্যাটাগরি</th>
                                            <th style="width: 12%;">পরিমাণ (Quantity) <span class="text-danger">*</span></th>
                                            <th style="width: 13%;">একক (Unit) <span class="text-danger">*</span></th>
                                            <th style="width: 12%;">দর / রেট (৳ Rate) <span class="text-danger">*</span></th>
                                            <th style="width: 13%;">মোট টাকা (Total ৳)</th>
                                            <th style="width: 5%;">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsTbody">
                                        <!-- Row 1 -->
                                        <tr id="row_0">
                                            <td class="align-middle fw-bold">1</td>
                                            <td>
                                                <input type="text" name="items[0][product_name]" class="form-control text-start" placeholder="যেমন: মিনিকেট চাল / চিনি / ডাল / তেল" required>
                                            </td>
                                            <td>
                                                <input type="text" name="items[0][category]" class="form-control" value="খাদ্যদ্রব্য ও বাজার">
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="items[0][quantity]" class="form-control text-end row-qty" placeholder="1" required oninput="calculateRow(0)">
                                            </td>
                                            <td>
                                                <select name="items[0][unit]" class="form-select">
                                                    <option value="Metric Ton (MT)">মেট্রিক টন (MT)</option>
                                                    <option value="KG" selected>কেজি (KG)</option>
                                                    <option value="Gram (GM)">গ্রাম (GM)</option>
                                                    <option value="Bag">বস্তা (Bag)</option>
                                                    <option value="Sack">বস্তা/বস্তা (Sack)</option>
                                                    <option value="Piece (PCS)">পিস (PCS)</option>
                                                    <option value="Liter (LTR)">লিটার (LTR)</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" name="items[0][rate]" class="form-control text-end row-rate" placeholder="0.00" required oninput="calculateRow(0)">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control text-end fw-bold bg-light row-total" readonly value="৳0.00">
                                            </td>
                                            <td class="align-middle">
                                                <button type="button" class="btn btn-sm btn-danger disabled" title="প্রথম সারি ডিলিট করা যাবে না"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="3" class="text-end fw-bold fs-16 align-middle">সর্বমোট হিসাব (Grand Total):</th>
                                            <th class="text-end fw-bold fs-16 align-middle text-primary" id="totalQtyDisplay">0</th>
                                            <th>আইটেমস</th>
                                            <th class="text-end fw-bold fs-16 align-middle">মোট প্রদেয় মূল্য:</th>
                                            <th class="text-end fw-bold fs-18 text-success align-middle" id="grandTotalDisplay">৳0.00</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="box-footer d-flex justify-content-between align-items-center">
                            <span class="text-muted"><i class="fa fa-info-circle text-primary me-1"></i> নোট: চালানটি সাবমিট করার পর অ্যাডমিন ভেরিফাই করে অনুমোদন দিলে স্বয়ংক্রিয়ভাবে আপনার লেজারে জমা হবে।</span>
                            <button type="submit" class="btn btn-success btn-lg px-4">
                                <i class="fa fa-check-circle me-1"></i> চালান ও পণ্য সাবমিট করুন (Submit Supply)
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
    let rowIndex = 1;

    function addRow() {
        const tbody = document.getElementById('itemsTbody');
        const tr = document.createElement('tr');
        tr.id = 'row_' + rowIndex;
        tr.innerHTML = `
            <td class="align-middle fw-bold">${rowIndex + 1}</td>
            <td>
                <input type="text" name="items[${rowIndex}][product_name]" class="form-control text-start" placeholder="পণ্যের নাম লিখুন" required>
            </td>
            <td>
                <input type="text" name="items[${rowIndex}][category]" class="form-control" value="খাদ্যদ্রব্য ও বাজার">
            </td>
            <td>
                <input type="number" step="0.01" name="items[${rowIndex}][quantity]" class="form-control text-end row-qty" placeholder="1" required oninput="calculateRow(${rowIndex})">
            </td>
            <td>
                <select name="items[${rowIndex}][unit]" class="form-select">
                    <option value="Metric Ton (MT)">মেট্রিক টন (MT)</option>
                    <option value="KG" selected>কেজি (KG)</option>
                    <option value="Gram (GM)">গ্রাম (GM)</option>
                    <option value="Bag">বস্তা (Bag)</option>
                    <option value="Sack">বস্তা (Sack)</option>
                    <option value="Piece (PCS)">পিস (PCS)</option>
                    <option value="Liter (LTR)">লিটার (LTR)</option>
                </select>
            </td>
            <td>
                <input type="number" step="0.01" name="items[${rowIndex}][rate]" class="form-control text-end row-rate" placeholder="0.00" required oninput="calculateRow(${rowIndex})">
            </td>
            <td>
                <input type="text" class="form-control text-end fw-bold bg-light row-total" readonly value="৳0.00">
            </td>
            <td class="align-middle">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(${rowIndex})"><i class="fa fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(tr);
        rowIndex++;
        calculateGrandTotal();
    }

    function removeRow(index) {
        const row = document.getElementById('row_' + index);
        if (row) {
            row.remove();
            calculateGrandTotal();
        }
    }

    function calculateRow(index) {
        const row = document.getElementById('row_' + index);
        if (!row) return;
        const qty = parseFloat(row.querySelector('.row-qty').value) || 0;
        const rate = parseFloat(row.querySelector('.row-rate').value) || 0;
        const total = qty * rate;
        row.querySelector('.row-total').value = '৳' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        let totalQty = 0;
        const rows = document.querySelectorAll('#itemsTbody tr');
        rows.forEach(row => {
            const qty = parseFloat(row.querySelector('.row-qty')?.value) || 0;
            const rate = parseFloat(row.querySelector('.row-rate')?.value) || 0;
            totalQty += qty;
            grandTotal += (qty * rate);
        });
        document.getElementById('totalQtyDisplay').innerText = totalQty.toLocaleString('en-US');
        document.getElementById('grandTotalDisplay').innerText = '৳' + grandTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
</script>
@endsection
