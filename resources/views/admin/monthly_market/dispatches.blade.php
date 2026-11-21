@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title">সেন্ট্রাল ডিসপ্যাচ ও চালান ব্যবস্থাপনা (Central Dispatch & Challan)</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.monthly_market.requests') }}">মাসিক বাজার</a></li>
                        <li class="breadcrumb-item active">ডিসপ্যাচ তালিকা</li>
                    </ol>
                </div>
                <div>
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#newDispatchModal">
                        <i class="fa fa-truck me-1"></i> নতুন ডিসপ্যাচ চালান তৈরি করুন
                    </button>
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

            <!-- Table Card -->
            <div class="box">
                <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                    <h4 class="box-title text-dark"><i class="fa fa-list me-2 text-primary"></i> সেন্ট্রাল স্টক থেকে প্রেরিত চালান ও ডেলিভারি রিপোর্টসমূহ</h4>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0 text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>চালান নং (Dispatch ID)</th>
                                    <th>মাস ও বছর</th>
                                    <th>এজেন্ট পয়েন্ট ও এরিয়া</th>
                                    <th>প্রেরিত পণ্যসমূহ</th>
                                    <th>গাড়ি ও চালকের তথ্য</th>
                                    <th>প্রেরণের তারিখ</th>
                                    <th>চালান স্ট্যাটাস</th>
                                    <th>রিসিভ তথ্য</th>
                                    <th>অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dispatches as $dsp)
                                    <tr>
                                        <td class="align-middle fw-bold text-primary">
                                            {{ $dsp->dispatch_no }}
                                        </td>
                                        <td class="align-middle fw-bold">
                                            {{ $dsp->distribution_month }} {{ $dsp->distribution_year }}
                                        </td>
                                        <td class="align-middle text-start">
                                            <strong class="text-dark">{{ $dsp->agentPoint->name ?? 'N/A' }}</strong><br>
                                            <span class="badge bg-info">{{ $dsp->area }}</span>
                                        </td>
                                        <td class="align-middle text-start">
                                            <ul class="list-unstyled mb-0" style="font-size: 11px;">
                                                @foreach($dsp->items as $it)
                                                    <li>• <strong>{{ $it->product_name }}:</strong> {{ floatval($it->sent_quantity) }} {{ $it->unit }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="align-middle text-muted">
                                            {{ $dsp->vehicle_info ?? '-' }}<br>
                                            <small>{{ $dsp->driver_info ?? '' }}</small>
                                        </td>
                                        <td class="align-middle">{{ $dsp->dispatch_date }}</td>
                                        <td class="align-middle">
                                            @if($dsp->status == 'in_transit')
                                                <span class="badge bg-warning text-dark"><i class="fa fa-truck"></i> ট্রানজিটে রয়েছে</span>
                                            @elseif($dsp->status == 'received')
                                                <span class="badge bg-success"><i class="fa fa-check"></i> গ্রহণ নিশ্চিতকৃত</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($dsp->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-start">
                                            @if($dsp->status == 'received')
                                                <small class="text-success fw-bold"><i class="fa fa-calendar-check-o"></i> {{ $dsp->received_date }}</small><br>
                                                <small class="text-muted">গ্রহীতা: {{ $dsp->received_by }}</small>
                                            @else
                                                <span class="text-muted">অপেক্ষমাণ</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <div class="btn-group-vertical btn-group-sm">
                                                <a href="{{ route('admin.monthly_market.dispatch.print', $dsp->id) }}" target="_blank" class="btn btn-outline-primary mb-1">
                                                    <i class="fa fa-print"></i> গেটপাস / চালান
                                                </a>
                                                @if($dsp->status == 'in_transit')
                                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#receiveModal_{{ $dsp->id }}">
                                                        <i class="fa fa-check"></i> রিসিভ নিশ্চিত করুন
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Receive Confirmation Modal for this Dispatch -->
                                    @if($dsp->status == 'in_transit')
                                        <div class="modal fade" id="receiveModal_{{ $dsp->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <form action="{{ route('admin.monthly_market.dispatch.receive', $dsp->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-success">
                                                            <h5 class="modal-title text-white"><i class="fa fa-check-circle me-1"></i> এজেন্ট পয়েন্টে পণ্য গ্রহণ নিশ্চিতকরণ (Challan: {{ $dsp->dispatch_no }})</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row mb-3">
                                                                <div class="col-md-6 col-12">
                                                                    <label class="form-label fw-bold">গ্রহণের তারিখ <span class="text-danger">*</span></label>
                                                                    <input type="date" name="received_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                                                </div>
                                                                <div class="col-md-6 col-12">
                                                                    <label class="form-label fw-bold">গ্রহণকারী কর্মকর্তা / এজেন্টের নাম <span class="text-danger">*</span></label>
                                                                    <input type="text" name="received_by" class="form-control" value="{{ $dsp->agentPoint->agent_name ?? Auth::user()->name }}" required>
                                                                </div>
                                                            </div>

                                                            <h6 class="fw-bold text-dark mb-2">প্রেরিত পণ্য যাচাই ও শর্টেজ/ড্যামেজ রিপোর্ট:</h6>
                                                            <table class="table table-bordered table-sm text-center">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>পণ্যের নাম</th>
                                                                        <th>প্রেরিত পরিমাণ</th>
                                                                        <th style="width: 25%;">প্রাপ্ত পরিমাণ (Received Qty)</th>
                                                                        <th style="width: 20%;">ড্যামেজ (Damaged Qty)</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($dsp->items as $item)
                                                                        <tr>
                                                                            <td class="text-start fw-bold">{{ $item->product_name }}</td>
                                                                            <td>{{ floatval($item->sent_quantity) }} {{ $item->unit }}</td>
                                                                            <td>
                                                                                <input type="number" step="0.1" name="received_qty_{{ $item->id }}" class="form-control form-control-sm text-end" value="{{ floatval($item->sent_quantity) }}" required>
                                                                            </td>
                                                                            <td>
                                                                                <input type="number" step="0.1" name="damaged_qty_{{ $item->id }}" class="form-control form-control-sm text-end" value="0">
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>

                                                            <div class="mb-3">
                                                                <label class="form-label">রিসিভ নোট / মন্তব্য</label>
                                                                <textarea name="receive_note" class="form-control" rows="2" placeholder="পণ্য অক্ষত অবস্থায় গ্রহণ করা হয়েছে কিনা"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                                                            <button type="submit" class="btn btn-success"><i class="fa fa-check me-1"></i> পণ্য গ্রহণ নিশ্চিত করুন</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">
                                            কোনো ডিসপ্যাচ রেকর্ড পাওয়া যায়নি।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- New Dispatch Modal -->
<div class="modal fade" id="newDispatchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form action="{{ route('admin.monthly_market.store_dispatch') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white"><i class="fa fa-truck me-2"></i> সেন্ট্রাল স্টক থেকে নতুন ডিসপ্যাচ চালান তৈরি করুন</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 col-12 mb-3">
                            <label class="form-label fw-bold">প্রাপক এজেন্ট পয়েন্ট <span class="text-danger">*</span></label>
                            <select name="agent_point_id" class="form-select" required>
                                <option value="">-- এজেন্ট পয়েন্ট নির্বাচন করুন --</option>
                                @foreach($agentPoints as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} (এরিয়া: {{ $p->area }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <label class="form-label fw-bold">মাস <span class="text-danger">*</span></label>
                            <select name="distribution_month" class="form-select" required>
                                @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $m)
                                    <option value="{{ $m }}" {{ date('F') == $m ? 'selected' : '' }}>{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <label class="form-label fw-bold">বছর <span class="text-danger">*</span></label>
                            <select name="distribution_year" class="form-select" required>
                                @foreach([date('Y'), date('Y')+1] as $y)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-12 mb-3">
                            <label class="form-label fw-bold">ডিসপ্যাচের তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="dispatch_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12 mb-3">
                            <label class="form-label">যানবাহনের বিবরণ (Vehicle Info)</label>
                            <input type="text" name="vehicle_info" class="form-control" placeholder="যেমন: ঢাকা মেট্রো-ট-১২-৩৪৫৬ (পিকআপ / ট্রাক)">
                        </div>
                        <div class="col-md-6 col-12 mb-3">
                            <label class="form-label">চালকের নাম ও মোবাইল (Driver Info)</label>
                            <input type="text" name="driver_info" class="form-control" placeholder="যেমন: চালক মো: রফিকুল ইসলাম, ০১৭XXXXXXXX">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold text-dark mb-0">প্রেরিত পণ্যের তালিকা (Sent Products):</h6>
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="addDispatchRow()">
                            + আরও পণ্য যোগ করুন
                        </button>
                    </div>

                    <table class="table table-bordered table-sm text-center" id="dispatchItemsTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40%; text-align: left;">পণ্যের নাম <span class="text-danger">*</span></th>
                                <th style="width: 25%;">প্রেরিত পরিমাণ <span class="text-danger">*</span></th>
                                <th style="width: 25%;">একক</th>
                                <th style="width: 10%;">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody id="dispatchTbody">
                            <tr id="d_row_0">
                                <td>
                                    <input type="text" name="items[0][product_name]" class="form-control form-control-sm text-start" placeholder="যেমন: চাল, ডাল, চিনি, তেল" required>
                                </td>
                                <td>
                                    <input type="number" step="0.1" name="items[0][sent_quantity]" class="form-control form-control-sm text-end" placeholder="0.0" required>
                                </td>
                                <td>
                                    <select name="items[0][unit]" class="form-select form-select-sm">
                                        <option value="KG">KG (কেজি)</option>
                                        <option value="Liter">Liter (লিটার)</option>
                                        <option value="Metric Ton (MT)">মেট্রিক টন (MT)</option>
                                        <option value="Bag">বস্তা (Bag)</option>
                                        <option value="Piece">পিস (PCS)</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger disabled"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-md-6 col-12 mb-2">
                            <label class="form-label">ডেলিভারি নোট</label>
                            <input type="text" name="delivery_note" class="form-control" placeholder="চালানের সাথে বিশেষ কোনো ডকুমেন্ট বা নোট">
                        </div>
                        <div class="col-md-6 col-12 mb-2">
                            <label class="form-label">গেটপাস নোট</label>
                            <input type="text" name="dispatch_note" class="form-control" placeholder="সেন্ট্রাল সিকিউরিটি ও ওয়্যারহাউস নোট">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane me-1"></i> সেন্ট্রাল স্টক হ্রাস করে চালান প্রেরণ করুন</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let dIndex = 1;
    function addDispatchRow() {
        const tbody = document.getElementById('dispatchTbody');
        const tr = document.createElement('tr');
        tr.id = 'd_row_' + dIndex;
        tr.innerHTML = `
            <td>
                <input type="text" name="items[${dIndex}][product_name]" class="form-control form-control-sm text-start" placeholder="পণ্যের নাম" required>
            </td>
            <td>
                <input type="number" step="0.1" name="items[${dIndex}][sent_quantity]" class="form-control form-control-sm text-end" placeholder="0.0" required>
            </td>
            <td>
                <select name="items[${dIndex}][unit]" class="form-select form-select-sm">
                    <option value="KG">KG (কেজি)</option>
                    <option value="Liter">Liter (লিটার)</option>
                    <option value="Metric Ton (MT)">মেট্রিক টন (MT)</option>
                    <option value="Bag">বস্তা (Bag)</option>
                    <option value="Piece">পিস (PCS)</option>
                </select>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-danger" onclick="document.getElementById('d_row_${dIndex}').remove()"><i class="fa fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(tr);
        dIndex++;
    }
</script>
@endsection
