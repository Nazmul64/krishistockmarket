@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-desktop text-primary me-2"></i> স্থায়ী সম্পদ ব্যবস্থাপনা (Fixed Asset Register)</h4>
                    <p class="text-muted small mb-0">প্রতিষ্ঠানের সকল স্থায়ী সম্পদ, কম্পিউটার, ল্যাপটপ, এসি, জেনারেটর, আসবাবপত্র ও সরঞ্জামের পূর্ণাঙ্গ ডিজিটাল রেকর্ড ও QR ট্র্যাকিং।</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addAssetModal">
                        <i class="fa fa-plus me-1"></i> নতুন স্থায়ী সম্পদ নিবন্ধন
                    </button>
                    <a href="{{ route('admin.erp.assets.assignments') }}" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                        <i class="fa fa-user-plus me-1"></i> সম্পদ হস্তান্তর
                    </a>
                    <a href="{{ route('admin.erp.assets.depreciation') }}" class="btn btn-sm btn-outline-purple rounded-pill px-3" style="color: #8b5cf6; border-color: #8b5cf6;">
                        <i class="fa fa-line-chart me-1"></i> অবচয় ও মূল্যহ্রাস
                    </a>
                </div>
            </div>

            <!-- KPI Metric Badges -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-primary">
                        <small class="text-muted text-uppercase fw-bold font-size-11">মোট সম্পদ সংখ্যা</small>
                        <h4 class="fw-bold text-dark my-1">{{ number_format($totalAssetsCount) }} টি</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-info">
                        <small class="text-muted text-uppercase fw-bold font-size-11">মূল ক্রয়মূল্য</small>
                        <h4 class="fw-bold text-info my-1">৳ {{ number_format($totalPurchaseValue, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-purple" style="border-left-color: #8b5cf6 !important;">
                        <small class="text-muted text-uppercase fw-bold font-size-11">বর্তমান বুক ভ্যালু</small>
                        <h4 class="fw-bold my-1" style="color: #8b5cf6;">৳ {{ number_format($totalBookValue, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-success">
                        <small class="text-muted text-uppercase fw-bold font-size-11">ব্যবহারকারীর কাছে বরাদ্দ</small>
                        <h4 class="fw-bold text-success my-1">{{ number_format($assignedCount) }} টি</h4>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Asset Filter Form -->
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
                <form action="{{ route('admin.erp.assets') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="সম্পদ নাম, কোড, সিরিয়াল বা ব্যবহারকারী দিয়ে খুঁজুন..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="category_id" class="form-select form-select-sm">
                            <option value="">-- সকল ক্যাটাগরি --</option>
                            @foreach($categories as $ac)
                                <option value="{{ $ac->id }}" {{ request('category_id') == $ac->id ? 'selected' : '' }}>{{ $ac->name_bn ?? $ac->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">-- সকল স্ট্যাটাস --</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>উপলব্ধ (Available)</option>
                            <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>বরাদ্দকৃত (Assigned)</option>
                            <option value="under_maintenance" {{ request('status') == 'under_maintenance' ? 'selected' : '' }}>মেরামতে রয়েছে (Under Maintenance)</option>
                            <option value="damaged" {{ request('status') == 'damaged' ? 'selected' : '' }}>নষ্ট (Damaged)</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary rounded-pill w-100 fw-bold">সার্চ</button>
                        <a href="{{ route('admin.erp.assets') }}" class="btn btn-sm btn-light rounded-pill">রিসেট</a>
                    </div>
                </form>
            </div>

            <!-- Assets Table -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>অ্যাসেট কোড</th>
                                <th>সম্পদের নাম ও মডেল</th>
                                <th>ক্যাটাগরি</th>
                                <th>সিরিয়াল নং</th>
                                <th>ক্রয়মূল্য</th>
                                <th>বর্তমান বুক ভ্যালু</th>
                                <th>ব্যবহারকারী / অবস্থান</th>
                                <th>অবস্থা</th>
                                <th>স্ট্যাটাস</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assets as $asset)
                            <tr>
                                <td class="fw-bold text-primary">
                                    {{ $asset->asset_code }}
                                    <button type="button" class="btn btn-link btn-xs p-0 text-dark ms-1" data-bs-toggle="modal" data-bs-target="#qrModal{{ $asset->id }}" title="QR Code দেখুন">
                                        <i class="fa fa-qrcode text-primary font-size-14"></i>
                                    </button>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($asset->image)
                                            <img src="{{ asset($asset->image) }}" class="rounded-3 border" style="width: 35px; height: 35px; object-fit: cover;">
                                        @else
                                            <div class="rounded-3 bg-light border d-flex align-items-center justify-content-center text-muted" style="width: 35px; height: 35px;">
                                                <i class="fa fa-desktop"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <span class="fw-bold text-dark d-block">{{ $asset->asset_name_bn ?? $asset->asset_name }}</span>
                                            <small class="text-muted">{{ $asset->brand }} {{ $asset->model }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ optional($asset->category)->name_bn ?? optional($asset->category)->name }}</td>
                                <td><code>{{ $asset->serial_number ?? 'N/A' }}</code></td>
                                <td class="fw-bold">৳ {{ number_format($asset->purchase_price, 2) }}</td>
                                <td class="fw-bold text-purple" style="color: #8b5cf6;">৳ {{ number_format($asset->current_book_value, 2) }}</td>
                                <td>
                                    @if($asset->assigned_employee_name)
                                        <span class="fw-bold text-dark d-block"><i class="fa fa-user text-primary me-1"></i> {{ $asset->assigned_employee_name }}</span>
                                        <small class="text-muted">{{ $asset->location_detail ?? $asset->department_name }}</small>
                                    @else
                                        <span class="text-muted"><i class="fa fa-archive me-1"></i> স্টোরে সংরক্ষিত ({{ $asset->location_detail ?? 'Main Store' }})</span>
                                    @endif
                                </td>
                                <td>
                                    @if($asset->condition === 'excellent')
                                        <span class="badge bg-success">চমৎকার</span>
                                    @elseif($asset->condition === 'good')
                                        <span class="badge bg-info">ভালো</span>
                                    @elseif($asset->condition === 'needs_repair')
                                        <span class="badge bg-warning text-dark">মেরামত প্রয়োজন</span>
                                    @else
                                        <span class="badge bg-danger">{{ $asset->condition }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($asset->status === 'available')
                                        <span class="badge bg-success bg-opacity-20 text-success">উপলব্ধ</span>
                                    @elseif($asset->status === 'assigned' || $asset->status === 'in_use')
                                        <span class="badge bg-primary bg-opacity-20 text-primary">ব্যবহারাধীন</span>
                                    @elseif($asset->status === 'under_maintenance')
                                        <span class="badge bg-warning bg-opacity-20 text-warning">সার্ভিসিং চলছে</span>
                                    @elseif($asset->status === 'disposed')
                                        <span class="badge bg-dark">নিষ্পত্তি/বিক্রয়</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $asset->status }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-xs btn-outline-primary rounded-pill px-2" data-bs-toggle="modal" data-bs-target="#editAssetModal{{ $asset->id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- QR Code Preview Modal -->
                            <div class="modal fade" id="qrModal{{ $asset->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-sm modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow text-center p-3">
                                        <div class="modal-header border-0 pb-0 justify-content-end">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="bg-light p-3 rounded-4 border d-inline-block mb-3">
                                                <i class="fa fa-qrcode fa-5x text-dark"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark mb-1">{{ $asset->asset_name }}</h6>
                                            <code class="font-size-14 fw-bold text-primary">{{ $asset->asset_code }}</code>
                                            <hr class="my-2">
                                            <small class="text-muted d-block">সিরিয়াল: {{ $asset->serial_number ?? 'N/A' }}</small>
                                            <small class="text-muted d-block">ক্রয়মূল্য: ৳{{ number_format($asset->purchase_price) }}</small>
                                            <small class="text-muted d-block">ব্যবহারকারী: {{ $asset->assigned_employee_name ?? 'Unassigned' }}</small>
                                            <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3 mt-3 w-100" onclick="window.print()">
                                                <i class="fa fa-print me-1"></i> QR লেবেল প্রিন্ট
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Asset Modal -->
                            <div class="modal fade" id="editAssetModal{{ $asset->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold text-dark"><i class="fa fa-edit text-primary me-2"></i> সম্পদ তথ্য সম্পাদন: {{ $asset->asset_code }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.erp.assets.update', $asset->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold small">সম্পদের নাম (ইংরেজি) *</label>
                                                        <input type="text" name="asset_name" class="form-control" value="{{ $asset->asset_name }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold small">সম্পদের নাম (বাংলা)</label>
                                                        <input type="text" name="asset_name_bn" class="form-control" value="{{ $asset->asset_name_bn }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold small">ক্যাটাগরি *</label>
                                                        <select name="category_id" class="form-select" required>
                                                            @foreach($categories as $cat)
                                                                <option value="{{ $cat->id }}" {{ $asset->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name_bn ?? $cat->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold small">সিরিয়াল নম্বর</label>
                                                        <input type="text" name="serial_number" class="form-control" value="{{ $asset->serial_number }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-bold small">অবস্থা (Condition)</label>
                                                        <select name="condition" class="form-select">
                                                            <option value="excellent" {{ $asset->condition == 'excellent' ? 'selected' : '' }}>চমৎকার (Excellent)</option>
                                                            <option value="good" {{ $asset->condition == 'good' ? 'selected' : '' }}>ভালো (Good)</option>
                                                            <option value="fair" {{ $asset->condition == 'fair' ? 'selected' : '' }}>মোটামুটি (Fair)</option>
                                                            <option value="needs_repair" {{ $asset->condition == 'needs_repair' ? 'selected' : '' }}>মেরামত প্রয়োজন (Needs Repair)</option>
                                                            <option value="damaged" {{ $asset->condition == 'damaged' ? 'selected' : '' }}>নষ্ট (Damaged)</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold small">অবস্থান ও রুম বিবরণ</label>
                                                        <input type="text" name="location_detail" class="form-control" value="{{ $asset->location_detail }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold small">ওয়ারেন্টি সমাপ্তির তারিখ</label>
                                                        <input type="date" name="warranty_expiry" class="form-control" value="{{ $asset->warranty_expiry }}">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label fw-bold small">ছবি পরিবর্তন</label>
                                                        <input type="file" name="image" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">বন্ধ করুন</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">আপডেট করুন</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">কোনো স্থায়ী সম্পদ পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $assets->links() }}
                </div>
            </div>

        </section>
    </div>
</div>

<!-- Add Asset Modal -->
<div class="modal fade" id="addAssetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="fa fa-desktop text-primary me-2"></i> নতুন স্থায়ী সম্পদ নিবন্ধন (Asset Registration)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.erp.assets.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">সম্পদের নাম (ইংরেজি) *</label>
                            <input type="text" name="asset_name" class="form-control" placeholder="उदा: Dell OptiPlex 7000 PC" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">সম্পদের নাম (বাংলা)</label>
                            <input type="text" name="asset_name_bn" class="form-control" placeholder="उदा: ডেল ডেস্কটপ কম্পিউটার">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">ক্যাটাগরি *</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- নির্বাচন করুন --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name_bn ?? $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">ব্র্যান্ড ও মডেল</label>
                            <input type="text" name="brand" class="form-control" placeholder="HP / Gree / Hatil">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">সিরিয়াল নম্বর (Serial No)</label>
                            <input type="text" name="serial_number" class="form-control" placeholder="S/N: ...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">ক্রয়ের তারিখ *</label>
                            <input type="date" name="purchase_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">ক্রয়মূল্য (৳) *</label>
                            <input type="number" step="0.01" min="0" name="purchase_price" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">কার্যকাল (Useful Life - Years)</label>
                            <input type="number" name="useful_life_years" class="form-control" value="5">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">অবচয় পদ্ধতি (Depreciation Method)</label>
                            <select name="depreciation_method" class="form-select">
                                <option value="straight_line">সরলরৈখিক (Straight Line Method)</option>
                                <option value="written_down">হ্রাসমান জের (Written Down Value)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">অবস্থা (Condition)</label>
                            <select name="condition" class="form-select">
                                <option value="excellent">চমৎকার (Excellent)</option>
                                <option value="good" selected>ভালো (Good)</option>
                                <option value="fair">মোটামুটি (Fair)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">অবস্থান ও রুম নং</label>
                            <input type="text" name="location_detail" class="form-control" placeholder="লেভেল ৫, রুম ৫০২">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">সরবরাহকারী (Supplier)</label>
                            <input type="text" name="supplier_name" class="form-control" placeholder="সরবরাহকারীর নাম">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">ওয়ারেন্টি সমাপ্তির তারিখ</label>
                            <input type="date" name="warranty_expiry" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold small">সম্পদের ছবি</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">নিবন্ধন সম্পন্ন করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

