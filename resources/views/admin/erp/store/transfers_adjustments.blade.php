@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-random text-primary me-2"></i> গুদাম স্থানান্তর ও স্টক সমন্বয় (Transfers & Adjustments)</h4>
                    <p class="text-muted small mb-0">এক গুদাম থেকে অন্য গুদামে মালামাল ট্রান্সফার এবং ফিজিক্যাল অডিট অনুযায়ী স্টক অ্যাডজাস্টমেন্ট।</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#transferModal">
                        <i class="fa fa-exchange me-1"></i> নতুন গুদাম স্থানান্তর
                    </button>
                    <button class="btn btn-sm btn-warning rounded-pill px-3 fw-bold text-dark" data-bs-toggle="modal" data-bs-target="#adjustmentModal">
                        <i class="fa fa-sliders me-1"></i> স্টক অ্যাডজাস্টমেন্ট
                    </button>
                    <a href="{{ route('admin.erp.store.items') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="fa fa-boxes me-1"></i> ইনভেন্টরি
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
                    <i class="fa fa-exclamation-triangle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Transfers Section -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                    <i class="fa fa-truck text-primary me-2"></i> গুদাম-টু-গুদাম স্থানান্তর হিস্ট্রি (Warehouse Transfers)
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ট্রান্সফার নং</th>
                                <th>উৎস গুদাম (From)</th>
                                <th>গন্তব্য গুদাম (To)</th>
                                <th>আইটেম ও পরিমাণ</th>
                                <th>তারিখ</th>
                                <th>স্ট্যাটাস</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transfers as $trf)
                            <tr>
                                <td class="fw-bold text-primary">{{ $trf->transfer_number }}</td>
                                <td>{{ optional($trf->fromWarehouse)->name_bn ?? optional($trf->fromWarehouse)->name }}</td>
                                <td class="text-success fw-bold">{{ optional($trf->toWarehouse)->name_bn ?? optional($trf->toWarehouse)->name }}</td>
                                <td>
                                    @foreach($trf->items as $ti)
                                        <span class="badge bg-light text-dark border">
                                            {{ optional($ti->item)->name_bn ?? optional($ti->item)->name }}: {{ number_format($ti->quantity) }} {{ optional(optional($ti->item)->unit)->symbol }}
                                        </span>
                                    @endforeach
                                </td>
                                <td>{{ $trf->transfer_date }}</td>
                                <td><span class="badge bg-success"><i class="fa fa-check-circle me-1"></i> স্থানান্তরিত</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">কোনো স্থানান্তর রেকর্ড পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Adjustments Section -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                    <i class="fa fa-balance-scale text-warning me-2"></i> ফিজিক্যাল স্টক সমন্বয় হিস্ট্রি (Stock Adjustments)
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>সমন্বয় নং</th>
                                <th>গুদাম</th>
                                <th>আইটেম</th>
                                <th>সমন্বয়ের ধরন</th>
                                <th>পূর্বের মজুত</th>
                                <th>নতুন মজুত</th>
                                <th>পার্থক্য</th>
                                <th>কারণ (Reason)</th>
                                <th>মন্তব্য</th>
                                <th>তারিখ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($adjustments as $adj)
                            <tr>
                                <td class="fw-bold text-dark">{{ $adj->adjustment_number }}</td>
                                <td>{{ optional($adj->warehouse)->name_bn ?? optional($adj->warehouse)->name }}</td>
                                <td class="fw-bold text-primary">{{ optional($adj->item)->name_bn ?? optional($adj->item)->name }}</td>
                                <td>
                                    @if($adj->type === 'increase')
                                        <span class="badge bg-success"><i class="fa fa-plus me-1"></i> বৃদ্ধি</span>
                                    @else
                                        <span class="badge bg-danger"><i class="fa fa-minus me-1"></i> হ্রাস</span>
                                    @endif
                                </td>
                                <td>{{ number_format($adj->previous_qty) }}</td>
                                <td class="fw-bold text-dark">{{ number_format($adj->adjusted_qty) }}</td>
                                <td class="fw-bold {{ $adj->type === 'increase' ? 'text-success' : 'text-danger' }}">
                                    {{ $adj->type === 'increase' ? '+' : '-' }}{{ number_format($adj->diff_qty) }}
                                </td>
                                <td><span class="badge bg-secondary">{{ $adj->reason }}</span></td>
                                <td>{{ $adj->remarks ?? '-' }}</td>
                                <td>{{ $adj->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-3">কোনো স্টক সমন্বয় রেকর্ড পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
    </div>
</div>

<!-- Transfer Modal -->
<div class="modal fade" id="transferModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="fa fa-exchange text-primary me-2"></i> গুদাম-টু-গুদাম মালামাল স্থানান্তর</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.erp.store.transfers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">উৎস গুদাম (From Warehouse) *</label>
                        <select name="from_warehouse_id" class="form-select" required>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name_bn ?? $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">গন্তব্য গুদাম (To Warehouse) *</label>
                        <select name="to_warehouse_id" class="form-select" required>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name_bn ?? $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">আইটেম নির্বাচন করুন *</label>
                        <select name="item_id" class="form-select" required>
                            <option value="">-- আইটেম সিলেক্ট করুন --</option>
                            @foreach($items as $itm)
                                <option value="{{ $itm->id }}">{{ $itm->name_bn ?? $itm->name }} (মজুত: {{ $itm->current_stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">স্থানান্তরের পরিমাণ *</label>
                        <input type="number" step="0.01" min="0.01" name="quantity" class="form-control" placeholder="পরিমাণ" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">নোট / কারণ</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="স্থানান্তরের বিবরণ..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">স্থানান্তর সম্পন্ন করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Adjustment Modal -->
<div class="modal fade" id="adjustmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="fa fa-sliders text-warning me-2"></i> ফিজিক্যাল স্টক সমন্বয় (Stock Adjustment)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.erp.store.adjustments.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">গুদাম *</label>
                        <select name="warehouse_id" class="form-select" required>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name_bn ?? $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">আইটেম *</label>
                        <select name="item_id" class="form-select" required>
                            <option value="">-- আইটেম সিলেক্ট করুন --</option>
                            @foreach($items as $itm)
                                <option value="{{ $itm->id }}">{{ $itm->name_bn ?? $itm->name }} (বর্তমান সিস্টেম মজুত: {{ $itm->current_stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">বাস্তব গণনা অনুযায়ী নতুন মজুত (Physical Count) *</label>
                        <input type="number" step="0.01" min="0" name="adjusted_qty" class="form-control" placeholder="নতুন সঠিক পরিমাণ" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">সমন্বয়ের কারণ (Reason) *</label>
                        <select name="reason" class="form-select" required>
                            <option value="physical_count_difference">ফিজিক্যাল অডিটে পার্থক্য (Physical Count Difference)</option>
                            <option value="damaged">নষ্ট বা ক্ষতিগ্রস্ত মালামাল (Damaged Item)</option>
                            <option value="lost">হারানো মালামাল (Lost Item)</option>
                            <option value="expired">মেয়াদোত্তীর্ণ সামগ্রী (Expired Item)</option>
                            <option value="data_correction">সিস্টেম এন্ট্রি সংশোধন (Data Entry Correction)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">অডিট বিবরণ ও অনুমোদন নোট</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="অডিট রেফারেন্স ও কারণ..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold text-dark">সমন্বয় অনুমোদন ও সেভ</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

