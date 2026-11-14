@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-line-chart text-primary me-2"></i> সম্পদের অবচয় ও নিষ্পত্তি ব্যবস্থাপনা (Depreciation & Disposal)</h4>
                    <p class="text-muted small mb-0">স্থায়ী সম্পদের বার্ষিক মূল্যহ্রাস (Straight Line / Written Down) গণনা এবং মেয়াদোত্তীর্ণ/বিক্রয়কৃত সম্পদ নিষ্পত্তি।</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-purple rounded-pill px-4 fw-bold shadow-sm text-white" style="background: #8b5cf6;" data-bs-toggle="modal" data-bs-target="#calcDepreciationModal">
                        <i class="fa fa-calculator me-1"></i> অবচয় গণনা (Run Depreciation)
                    </button>
                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#disposalModal">
                        <i class="fa fa-trash-o me-1"></i> সম্পদ নিষ্পত্তি / বিক্রয় (Disposal)
                    </button>
                    <a href="{{ route('admin.erp.assets') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="fa fa-desktop me-1"></i> সম্পদ রেজিস্টার
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

            <!-- 1. Depreciation History Section -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                    <i class="fa fa-history text-purple me-2" style="color: #8b5cf6;"></i> অবচয় ট্রানজ্যাকশন হিস্ট্রি (Depreciation Ledger)
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>অর্থবছর</th>
                                <th>সম্পদ কোড ও নাম</th>
                                <th>পদ্ধতি</th>
                                <th>প্রারম্ভিক বুক ভ্যালু</th>
                                <th>অবচয়ের পরিমাণ</th>
                                <th>সমাপনী বুক ভ্যালু</th>
                                <th>গণনার তারিখ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($depreciations as $dep)
                            <tr>
                                <td class="fw-bold text-dark"><span class="badge bg-secondary">{{ $dep->fiscal_year }}</span></td>
                                <td>
                                    <span class="fw-bold text-primary">{{ optional($dep->asset)->asset_code }}</span>
                                    <span class="text-dark d-block">{{ optional($dep->asset)->asset_name }}</span>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $dep->method }}</span></td>
                                <td class="fw-bold">৳ {{ number_format($dep->opening_value, 2) }}</td>
                                <td class="fw-bold text-danger">- ৳ {{ number_format($dep->depreciation_amount, 2) }}</td>
                                <td class="fw-bold text-purple" style="color: #8b5cf6;">৳ {{ number_format($dep->closing_value, 2) }}</td>
                                <td>{{ $dep->depreciation_date }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">কোনো অবচয় হিস্ট্রি পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. Disposals Section -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                    <i class="fa fa-trash text-danger me-2"></i> সম্পদ নিষ্পত্তি ও বিক্রয় হিস্ট্রি (Asset Disposal Log)
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>সম্পদ কোড ও নাম</th>
                                <th>নিষ্পত্তির কারণ</th>
                                <th>মূল ক্রয়মূল্য</th>
                                <th>পুঞ্জীভূত অবচয়</th>
                                <th>বুক ভ্যালু</th>
                                <th>বিক্রয় মূল্য</th>
                                <th>মুনাফা / লোকসান</th>
                                <th>তারিখ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($disposals as $disp)
                            <tr>
                                <td>
                                    <span class="fw-bold text-primary">{{ optional($disp->asset)->asset_code }}</span>
                                    <span class="text-dark d-block">{{ optional($disp->asset)->asset_name }}</span>
                                </td>
                                <td><span class="badge bg-secondary">{{ $disp->reason }}</span></td>
                                <td>৳ {{ number_format($disp->original_cost, 2) }}</td>
                                <td>৳ {{ number_format($disp->accumulated_depreciation, 2) }}</td>
                                <td class="fw-bold">৳ {{ number_format($disp->book_value_at_disposal, 2) }}</td>
                                <td class="fw-bold text-success">৳ {{ number_format($disp->sale_value, 2) }}</td>
                                <td>
                                    @if($disp->gain_loss >= 0)
                                        <span class="badge bg-success">+ ৳ {{ number_format($disp->gain_loss, 2) }} (লাভ)</span>
                                    @else
                                        <span class="badge bg-danger">- ৳ {{ number_format(abs($disp->gain_loss), 2) }} (ক্ষতি)</span>
                                    @endif
                                </td>
                                <td>{{ $disp->disposal_date }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">কোনো সম্পদ নিষ্পত্তি রেকর্ড পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
    </div>
</div>

<!-- Calc Depreciation Modal -->
<div class="modal fade" id="calcDepreciationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="fa fa-calculator text-purple me-2" style="color: #8b5cf6;"></i> অবচয় গণনা ইঞ্জিন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.erp.assets.depreciation.calculate') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">সম্পদ নির্বাচন করুন *</label>
                        <select name="asset_id" class="form-select" required>
                            <option value="">-- সম্পদ নির্বাচন করুন --</option>
                            @foreach($assets as $ast)
                                <option value="{{ $ast->id }}">{{ $ast->asset_code }} - {{ $ast->asset_name }} (বর্তমান বুক ভ্যালু: ৳{{ number_format($ast->current_book_value) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">অর্থবছর *</label>
                        <input type="text" name="fiscal_year" class="form-control" value="2026-2027" required>
                    </div>
                    <div class="alert alert-info rounded-4 small mb-0">
                        <i class="fa fa-info-circle me-1"></i> সিস্টেম সম্পদের ক্রয়মূল্য, উদ্ধারযোগ্য মূল্য (Salvage Value) এবং কার্যকালের ওপর ভিত্তি করে স্বয়ংক্রিয়ভাবে বাৎসরিক অবচয় গণনা করে বুক ভ্যালু আপডেট করবে।
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-purple rounded-pill px-4 fw-bold text-white" style="background: #8b5cf6;">অবচয় গণনা ও সেভ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Disposal Modal -->
<div class="modal fade" id="disposalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger"><i class="fa fa-trash-o me-2"></i> সম্পদ নিষ্পত্তি / বিক্রয় এন্ট্রি</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.erp.assets.disposal.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">সম্পদ নির্বাচন করুন *</label>
                        <select name="asset_id" class="form-select" required>
                            <option value="">-- সম্পদ সিলেক্ট করুন --</option>
                            @foreach($assets as $ast)
                                <option value="{{ $ast->id }}">{{ $ast->asset_code }} - {{ $ast->asset_name }} (বুক ভ্যালু: ৳{{ number_format($ast->current_book_value) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small">নিষ্পত্তির তারিখ *</label>
                            <input type="date" name="disposal_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small">বিক্রয়মূল্য (যদি বিক্রি হয়)</label>
                            <input type="number" step="0.01" min="0" name="sale_value" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">নিষ্পত্তির কারণ *</label>
                        <select name="reason" class="form-select" required>
                            <option value="damaged">অমেরামতযোগ্য নষ্ট (Permanently Damaged)</option>
                            <option value="obsolete">প্রযুক্তিতে অপ্রচলিত (Obsolete Technology)</option>
                            <option value="sold">নিলামে বা স্ক্র্যাপ বিক্রয় (Sold / Auctioned)</option>
                            <option value="donated">দানকৃত (Donated)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">অনুমোদন নোট ও বিবরণ</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="ম্যানেজমেন্টের সিদ্ধান্ত অনুযায়ী..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">নিষ্পত্তি নিশ্চিত করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

