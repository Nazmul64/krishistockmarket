@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 no-print">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-bar-chart text-primary me-2"></i> কর্পোরেট ERP রিপোর্টিং ও অ্যানালিটিক্স হাব (ERP Reports)</h4>
                    <p class="text-muted small mb-0">স্টক ভ্যালুয়েশন, স্থায়ী সম্পদ রেজিস্টার, যানবাহন ব্যয় ও অভ্যন্তরীণ স্টোর অডিট ট্রেইলের প্রিন্টযোগ্য পূর্ণাঙ্গ প্রতিবেদন।</p>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-sm btn-dark rounded-pill px-3 shadow-sm">
                        <i class="fa fa-print me-1"></i> প্রতিবেদন প্রিন্ট (Print)
                    </button>
                    <a href="{{ route('admin.erp.dashboard') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="fa fa-dashboard me-1"></i> ড্যাশবোর্ড
                    </a>
                </div>
            </div>

            <!-- Report Navigation Pills -->
            <div class="d-flex flex-wrap gap-2 mb-4 no-print">
                <a href="{{ route('admin.erp.reports', ['type' => 'stock_valuation']) }}" class="btn btn-sm rounded-pill px-4 fw-bold {{ $reportType == 'stock_valuation' ? 'btn-primary' : 'btn-light border text-dark' }}">
                    <i class="fa fa-cubes me-1"></i> স্টক ভ্যালুয়েশন রিপোর্ট
                </a>
                <a href="{{ route('admin.erp.reports', ['type' => 'asset_register']) }}" class="btn btn-sm rounded-pill px-4 fw-bold {{ $reportType == 'asset_register' ? 'btn-primary' : 'btn-light border text-dark' }}">
                    <i class="fa fa-desktop me-1"></i> স্থায়ী সম্পদ রেজিস্টার
                </a>
                <a href="{{ route('admin.erp.reports', ['type' => 'vehicle_expenses']) }}" class="btn btn-sm rounded-pill px-4 fw-bold {{ $reportType == 'vehicle_expenses' ? 'btn-primary' : 'btn-light border text-dark' }}">
                    <i class="fa fa-car me-1"></i> ফ্লিট ও যানবাহন ব্যয়
                </a>
                <a href="{{ route('admin.erp.reports', ['type' => 'audit_trail']) }}" class="btn btn-sm rounded-pill px-4 fw-bold {{ $reportType == 'audit_trail' ? 'btn-primary' : 'btn-light border text-dark' }}">
                    <i class="fa fa-shield me-1"></i> স্টোর অডিট ট্রেইল
                </a>
            </div>

            <!-- Print Header (Visible only in Print) -->
            <div class="text-center mb-4 d-none d-print-block">
                <h3 class="fw-bold mb-1">{{ $company->company_name ?? 'ই-কৃষি সেবা কর্পোরেট লিমিটেড' }}</h3>
                <div class="text-muted small">{{ $company->address ?? 'হেড অফিস, ঢাকা' }} | ফোন: {{ $company->phone ?? '০১৭xxxxxxxx' }}</div>
                <h5 class="fw-bold text-uppercase border-top border-bottom py-2 mt-2">
                    @if($reportType == 'stock_valuation')
                        স্টক ভ্যালুয়েশন ও স্টোর ইনভেন্টরি মূল্যায়ন প্রতিবেদন
                    @elseif($reportType == 'asset_register')
                        ফিক্সড অ্যাসেট রেজিস্টার ও পুঞ্জীভূত অবচয় বিবরণী
                    @elseif($reportType == 'vehicle_expenses')
                        যানবাহন ফ্লিট মাইলেজ ও মোট জ্বালানি/রক্ষণাবেক্ষণ ব্যয় প্রতিবেদন
                    @else
                        স্টোর ইনভেন্টরি অডিট ট্রেইল ও মুভমেন্ট লগ
                    @endif
                </h5>
                <div class="small text-muted">প্রতিবেদন প্রকাশের তারিখ: {{ date('d F, Y (h:i A)') }}</div>
            </div>

            <!-- ========================================== -->
            <!-- 1. STOCK VALUATION REPORT -->
            <!-- ========================================== -->
            @if($reportType == 'stock_valuation')
                <div class="row g-3 mb-4 no-print">
                    <div class="col-md-6 col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-primary">
                            <small class="text-muted text-uppercase fw-bold font-size-11">বর্তমান মোট মজুদকৃত মালামাল</small>
                            <h4 class="fw-bold text-dark my-1">{{ number_format($data['totalQuantity'] ?? 0) }} টি</h4>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-success">
                            <small class="text-muted text-uppercase fw-bold font-size-11">মোট ইনভেন্টরি ভ্যালু / পোর্টফোলিও মূল্য</small>
                            <h4 class="fw-bold text-success my-1">৳ {{ number_format($data['totalValuation'] ?? 0, 2) }}</h4>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="text-muted small text-uppercase">
                                    <th class="ps-4">পণ্য কোড</th>
                                    <th>পণ্যের নাম</th>
                                    <th>ক্যাটাগরি</th>
                                    <th>ডিফল্ট ওয়্যারহাউস</th>
                                    <th class="text-center">বর্তমান স্টক</th>
                                    <th class="text-end">গড় ক্রয়মূল্য (৳)</th>
                                    <th class="text-end pe-4">মোট ভ্যালুয়েশন (৳)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['items'] ?? [] as $item)
                                    @php $rowVal = $item->current_stock * $item->purchase_price; @endphp
                                    <tr>
                                        <td class="ps-4 font-monospace text-primary fw-bold">{{ $item->item_code }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->name }}</div>
                                            <small class="text-muted">{{ $item->sku ?? '-' }}</small>
                                        </td>
                                        <td><span class="badge bg-light text-dark border">{{ $item->category->name ?? 'জেনারেল' }}</span></td>
                                        <td>{{ $item->defaultWarehouse->name ?? 'সেন্ট্রাল স্টোর' }}</td>
                                        <td class="text-center fw-bold text-dark">
                                            {{ number_format($item->current_stock, 2) }} {{ $item->unit->name ?? '' }}
                                        </td>
                                        <td class="text-end">৳ {{ number_format($item->purchase_price, 2) }}</td>
                                        <td class="text-end pe-4 fw-bold text-success">৳ {{ number_format($rowVal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold bg-light font-size-15">
                                    <td colspan="4" class="text-end ps-4">সর্বমোট ইনভেন্টরি মূল্যায়ন:</td>
                                    <td class="text-center text-dark">{{ number_format($data['totalQuantity'] ?? 0, 2) }}</td>
                                    <td></td>
                                    <td class="text-end pe-4 text-success font-size-16">৳ {{ number_format($data['totalValuation'] ?? 0, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            <!-- ========================================== -->
            <!-- 2. FIXED ASSET REGISTER REPORT -->
            <!-- ========================================== -->
            @elseif($reportType == 'asset_register')
                <div class="row g-3 mb-4 no-print">
                    <div class="col-md-4 col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-info">
                            <small class="text-muted text-uppercase fw-bold font-size-11">মূল ঐতিহাসিক ক্রয়মূল্য</small>
                            <h4 class="fw-bold text-info my-1">৳ {{ number_format($data['totalPurchase'] ?? 0, 2) }}</h4>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-danger">
                            <small class="text-muted text-uppercase fw-bold font-size-11">মোট পুঞ্জীভূত অবচয়</small>
                            <h4 class="fw-bold text-danger my-1">৳ {{ number_format($data['totalDepreciation'] ?? 0, 2) }}</h4>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-success">
                            <small class="text-muted text-uppercase fw-bold font-size-11">বর্তমান প্রকৃত বুক ভ্যালু</small>
                            <h4 class="fw-bold text-success my-1">৳ {{ number_format($data['totalBookValue'] ?? 0, 2) }}</h4>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="text-muted small text-uppercase">
                                    <th class="ps-4">সম্পদ কোড ও নাম</th>
                                    <th>ক্যাটাগরি</th>
                                    <th>ক্রয়ের তারিখ</th>
                                    <th>অবচয় মেথড</th>
                                    <th class="text-end">ক্রয়মূল্য (৳)</th>
                                    <th class="text-end">পুঞ্জীভূত অবচয় (৳)</th>
                                    <th class="text-end pe-4">বর্তমান বুক ভ্যালু (৳)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['assets'] ?? [] as $ast)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $ast->asset_name }}</div>
                                            <small class="text-muted font-monospace">{{ $ast->asset_code }}</small>
                                        </td>
                                        <td><span class="badge bg-light text-dark border">{{ $ast->category->name ?? 'স্থায়ী সম্পদ' }}</span></td>
                                        <td>{{ $ast->purchase_date ? \Carbon\Carbon::parse($ast->purchase_date)->format('d/m/Y') : '-' }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $ast->depreciation_method)) }} ({{ $ast->depreciation_rate }}%)</td>
                                        <td class="text-end fw-bold">৳ {{ number_format($ast->purchase_price, 2) }}</td>
                                        <td class="text-end text-danger fw-bold">- ৳ {{ number_format($ast->accumulated_depreciation, 2) }}</td>
                                        <td class="text-end pe-4 fw-bold text-success">৳ {{ number_format($ast->current_book_value, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            <!-- ========================================== -->
            <!-- 3. VEHICLE EXPENSES REPORT -->
            <!-- ========================================== -->
            @elseif($reportType == 'vehicle_expenses')
                <div class="row g-3 mb-4 no-print">
                    <div class="col-md-6 col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-warning">
                            <small class="text-muted text-uppercase fw-bold font-size-11">মোট জ্বালানি খরচ</small>
                            <h4 class="fw-bold text-warning my-1">৳ {{ number_format($data['totalFuelCost'] ?? 0, 2) }}</h4>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-danger">
                            <small class="text-muted text-uppercase fw-bold font-size-11">মোট ফ্লিট মেরামত ও সার্ভিসিং ব্যয়</small>
                            <h4 class="fw-bold text-danger my-1">৳ {{ number_format($data['totalMaintenanceCost'] ?? 0, 2) }}</h4>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="text-muted small text-uppercase">
                                    <th class="ps-4">গাড়ির নম্বর ও মডেল</th>
                                    <th>চালক</th>
                                    <th class="text-center">মোট মাইলেজ</th>
                                    <th class="text-center">ট্রিপ সংখ্যা</th>
                                    <th class="text-end">জ্বালানি ব্যয় (৳)</th>
                                    <th class="text-end pe-4">মেরামত ব্যয় (৳)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['vehicles'] ?? [] as $v)
                                    @php
                                        $vFuel = $v->fuels->sum('total_amount');
                                        $vMnt = $v->maintenances->sum('total_cost');
                                    @endphp
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $v->vehicle_number }}</div>
                                            <small class="text-muted">{{ $v->brand }} {{ $v->model }}</small>
                                        </td>
                                        <td>{{ $v->assigned_driver ?? '-' }}</td>
                                        <td class="text-center fw-bold">{{ number_format($v->current_mileage) }} কি.মি.</td>
                                        <td class="text-center">{{ $v->trips->count() }} টি</td>
                                        <td class="text-end fw-bold text-warning">৳ {{ number_format($vFuel, 2) }}</td>
                                        <td class="text-end pe-4 fw-bold text-danger">৳ {{ number_format($vMnt, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            <!-- ========================================== -->
            <!-- 4. STORE AUDIT TRAIL REPORT -->
            <!-- ========================================== -->
            @else
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="fw-bold text-dark mb-0"><i class="fa fa-shield text-primary me-2"></i> সাম্প্রতিক ৫০টি মালামাল আদান-প্রদান অডিট ট্রেইল</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="text-muted small text-uppercase">
                                    <th class="ps-4">তারিখ ও সময়</th>
                                    <th>পণ্য আইটেম</th>
                                    <th>ওয়্যারহাউস</th>
                                    <th>লেনদেনের ধরন</th>
                                    <th class="text-center">পরিমাণ</th>
                                    <th>অনুমোদনকারী</th>
                                    <th class="pe-4">বিবরণ / রেফারেন্স</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['transactions'] ?? [] as $tr)
                                    <tr>
                                        <td class="ps-4 text-muted small">
                                            {{ $tr->created_at ? $tr->created_at->format('d/m/Y h:i A') : '-' }}
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $tr->item->name ?? 'Item' }}</div>
                                            <small class="text-muted font-monospace">{{ $tr->item->item_code ?? '' }}</small>
                                        </td>
                                        <td>{{ $tr->warehouse->name ?? 'সেন্ট্রাল স্টোর' }}</td>
                                        <td>
                                            @if($tr->transaction_type == 'stock_in')
                                                <span class="badge bg-success-light text-success fw-bold">ইনভেন্টরি ইন (Stock In)</span>
                                            @elseif($tr->transaction_type == 'stock_out')
                                                <span class="badge bg-warning-light text-warning fw-bold">ইস্যু / বিতরণ (Stock Out)</span>
                                            @else
                                                <span class="badge bg-info-light text-info fw-bold">{{ strtoupper($tr->transaction_type) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center fw-bold {{ $tr->quantity >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $tr->quantity > 0 ? '+' : '' }}{{ number_format($tr->quantity, 2) }}
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $tr->authorized_by ?? 'System' }}</span>
                                        </td>
                                        <td class="pe-4 text-muted font-size-12">
                                            {{ $tr->notes ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </section>
    </div>
</div>
@endsection

