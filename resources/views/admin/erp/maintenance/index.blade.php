@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-wrench text-warning me-2"></i> রক্ষণাবেক্ষণ ও মেরামত ব্যবস্থাপনা (Maintenance Management)</h4>
                    <p class="text-muted small mb-0">অফিসের স্থায়ী সম্পদ, এসি, জেনারেটর ও যানবাহনের সমস্যা রিপোর্টিং, অনুমোদন, টেকনিশিয়ান নিয়োগ, খরচ হিসাব ও প্রিভেন্টিভ সিডিউলিং।</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addRequestModal">
                        <i class="fa fa-plus me-1"></i> নতুন মেরামত রিকোয়েস্ট
                    </button>
                    <button class="btn btn-sm btn-outline-warning rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                        <i class="fa fa-calendar-check-o me-1"></i> নিয়মিত সিডিউল তৈরি
                    </button>
                </div>
            </div>

            <!-- KPI Metric Badges -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-primary">
                        <small class="text-muted text-uppercase fw-bold font-size-11">মোট রিকোয়েস্ট</small>
                        <h4 class="fw-bold text-dark my-1">{{ number_format($totalRequests) }} টি</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-warning">
                        <small class="text-muted text-uppercase fw-bold font-size-11">পেন্ডিং ও চলমান</small>
                        <h4 class="fw-bold text-warning my-1">{{ number_format($pendingCount) }} টি</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-success">
                        <small class="text-muted text-uppercase fw-bold font-size-11">মেরামত সম্পন্ন</small>
                        <h4 class="fw-bold text-success my-1">{{ number_format($completedCount) }} টি</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-danger">
                        <small class="text-muted text-uppercase fw-bold font-size-11">মোট মেরামত ব্যয়</small>
                        <h4 class="fw-bold text-danger my-1">৳ {{ number_format($totalCost, 2) }}</h4>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Tabs: Maintenance Requests vs Schedules -->
            <ul class="nav nav-pills mb-3 gap-2" id="maintenanceTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active rounded-pill px-4 fw-bold" id="requests-tab" data-bs-toggle="pill" data-bs-target="#requests-pane" type="button" role="tab">
                        <i class="fa fa-wrench me-1"></i> মেরামত রিকোয়েস্টসমূহ ({{ $requests->total() }})
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link rounded-pill px-4 fw-bold" id="schedules-tab" data-bs-toggle="pill" data-bs-target="#schedules-pane" type="button" role="tab">
                        <i class="fa fa-calendar me-1"></i> নিয়মিত প্রিভেন্টিভ সিডিউল ({{ $schedules->count() }})
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Tab 1: Requests -->
                <div class="tab-pane fade show active" id="requests-pane" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr class="text-muted small text-uppercase">
                                        <th class="ps-4">রিকোয়েস্ট নম্বর ও তারিখ</th>
                                        <th>সম্পদ / গাড়ি</th>
                                        <th>বিভাগ ও আবেদনকারী</th>
                                        <th>অগ্রাধিকার</th>
                                        <th>সমস্যার বিবরণ</th>
                                        <th>টেকনিশিয়ান / সার্ভিস</th>
                                        <th>খরচ</th>
                                        <th>স্ট্যাটাস</th>
                                        <th class="pe-4 text-end">অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($requests as $req)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-bold text-dark">{{ $req->request_number }}</div>
                                                <small class="text-muted">{{ $req->request_date ? \Carbon\Carbon::parse($req->request_date)->format('d M, Y') : '-' }}</small>
                                            </td>
                                            <td>
                                                @if($req->asset)
                                                    <span class="badge bg-primary-light text-primary"><i class="fa fa-desktop me-1"></i> {{ $req->asset->asset_name }}</span>
                                                    <div class="small text-muted font-monospace">{{ $req->asset->asset_code }}</div>
                                                @elseif($req->vehicle)
                                                    <span class="badge bg-info-light text-info"><i class="fa fa-car me-1"></i> {{ $req->vehicle->vehicle_number }}</span>
                                                    <div class="small text-muted">{{ $req->vehicle->brand }} {{ $req->vehicle->model }}</div>
                                                @else
                                                    <span class="text-muted">জেনারেল মেইনটেন্যান্স</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark font-size-13">{{ $req->department_name ?? 'সাধারণ বিভাগ' }}</div>
                                                <small class="text-muted">{{ $req->requested_by }}</small>
                                            </td>
                                            <td>
                                                @if($req->priority == 'emergency')
                                                    <span class="badge bg-danger">জরুরি (Emergency)</span>
                                                @elseif($req->priority == 'high')
                                                    <span class="badge bg-warning text-dark">উচ্চ (High)</span>
                                                @else
                                                    <span class="badge bg-light text-muted border">সাধারণ (Normal)</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-dark font-size-13">{{ Str::limit($req->problem_description, 35) }}</span>
                                            </td>
                                            <td>
                                                <div class="small fw-bold text-dark">{{ $req->technician_name ?? '-' }}</div>
                                                <small class="text-muted">{{ $req->service_provider ?? '' }}</small>
                                            </td>
                                            <td>
                                                @if($req->status == 'completed')
                                                    <span class="fw-bold text-success font-size-13">৳ {{ number_format($req->total_cost, 2) }}</span>
                                                    <div class="font-size-10 text-muted">পার্টস: ৳{{ number_format($req->spare_parts_cost) }} | লেবার: ৳{{ number_format($req->labor_cost) }}</div>
                                                @else
                                                    <span class="text-muted font-size-12">আনুমানিক: ৳{{ number_format($req->estimated_cost, 2) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($req->status == 'pending')
                                                    <span class="badge bg-warning-light text-warning"><i class="fa fa-clock-o me-1"></i> অপেক্ষমান</span>
                                                @elseif($req->status == 'approved')
                                                    <span class="badge bg-primary-light text-primary"><i class="fa fa-thumbs-up me-1"></i> অনুমোদিত</span>
                                                @elseif($req->status == 'in_progress')
                                                    <span class="badge bg-info-light text-info"><i class="fa fa-cogs me-1"></i> কাজ চলছে</span>
                                                @elseif($req->status == 'completed')
                                                    <span class="badge bg-success-light text-success"><i class="fa fa-check-circle me-1"></i> সম্পন্ন</span>
                                                @else
                                                    <span class="badge bg-danger-light text-danger"><i class="fa fa-times-circle me-1"></i> বাতিল</span>
                                                @endif
                                            </td>
                                            <td class="pe-4 text-end">
                                                @if($req->status == 'pending')
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-light border dropdown-toggle" data-bs-toggle="dropdown">অ্যাকশন</button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li>
                                                                <form action="{{ route('admin.erp.maintenance.requests.status', $req->id) }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="action" value="approve">
                                                                    <button type="submit" class="dropdown-item text-primary"><i class="fa fa-check me-2"></i> অনুমোদন করুন</button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('admin.erp.maintenance.requests.status', $req->id) }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="action" value="reject">
                                                                    <button type="submit" class="dropdown-item text-danger"><i class="fa fa-ban me-2"></i> বাতিল করুন</button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                @elseif($req->status == 'approved')
                                                    <form action="{{ route('admin.erp.maintenance.requests.status', $req->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="action" value="start">
                                                        <button type="submit" class="btn btn-sm btn-info text-white rounded-pill px-3"><i class="fa fa-play me-1"></i> কাজ শুরু</button>
                                                    </form>
                                                @elseif($req->status == 'in_progress')
                                                    <button class="btn btn-sm btn-success rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#completeModal{{ $req->id }}">
                                                        <i class="fa fa-check me-1"></i> সম্পন্ন করুন
                                                    </button>
                                                @else
                                                    <span class="text-muted font-size-12"><i class="fa fa-check text-success"></i> ক্লোজড</span>
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Modal: Complete Maintenance -->
                                        @if($req->status == 'in_progress')
                                        <div class="modal fade" id="completeModal{{ $req->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content rounded-4 border-0">
                                                    <form action="{{ route('admin.erp.maintenance.requests.status', $req->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="action" value="complete">
                                                        <div class="modal-header border-0 bg-light">
                                                            <h5 class="modal-title fw-bold"><i class="fa fa-check-circle text-success me-2"></i> মেরামত কাজ সমাপ্তি ঘোষণা ({{ $req->request_number }})</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            <div class="row g-3">
                                                                <div class="col-6">
                                                                    <label class="form-label fw-bold">স্পেয়ার পার্টস খরচ (৳)</label>
                                                                    <input type="number" step="0.01" name="spare_parts_cost" class="form-control" value="0.00" required>
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="form-label fw-bold">লেবার / সার্ভিস বিল (৳)</label>
                                                                    <input type="number" step="0.01" name="labor_cost" class="form-control" value="0.00" required>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="form-label fw-bold">সম্পন্নের তারিখ</label>
                                                                    <input type="date" name="completion_date" class="form-control" value="{{ date('Y-m-d') }}">
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="form-label fw-bold">সার্ভিস নোট / রিমার্কস</label>
                                                                    <textarea name="remarks" class="form-control" rows="2" placeholder="মেরামত বা পার্টস পরিবর্তনের বিবরণ..."></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                                                            <button type="submit" class="btn btn-success px-4 fw-bold">কাজ সম্পন্ন নিশ্চিত করুন</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-5 text-muted">
                                                <i class="fa fa-wrench fa-3x mb-3 text-secondary opacity-50"></i>
                                                <p class="mb-0">কোনো মেরামত রিকোয়েস্ট পাওয়া যায়নি।</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($requests->hasPages())
                            <div class="p-3 border-top">
                                {{ $requests->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tab 2: Schedules -->
                <div class="tab-pane fade" id="schedules-pane" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr class="text-muted small text-uppercase">
                                        <th class="ps-4">সার্ভিসের নাম</th>
                                        <th>সম্পদ / যানবাহন</th>
                                        <th>বিরতি (Interval)</th>
                                        <th>পূর্ববর্তী সার্ভিসের তারিখ</th>
                                        <th>পরবর্তী ডিউ তারিখ</th>
                                        <th>সতর্কবার্তা</th>
                                        <th>স্ট্যাটাস</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($schedules as $sch)
                                        <tr>
                                            <td class="ps-4">
                                                <span class="fw-bold text-dark font-size-14">{{ $sch->service_name }}</span>
                                            </td>
                                            <td>
                                                @if($sch->asset)
                                                    <span class="badge bg-primary-light text-primary">{{ $sch->asset->asset_name }}</span>
                                                @elseif($sch->vehicle)
                                                    <span class="badge bg-info-light text-info">{{ $sch->vehicle->vehicle_number }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                প্রতি {{ $sch->interval_value }} {{ ucfirst($sch->interval_type) }}
                                            </td>
                                            <td>
                                                {{ $sch->last_service_date ? \Carbon\Carbon::parse($sch->last_service_date)->format('d M, Y') : '-' }}
                                            </td>
                                            <td>
                                                @php $nextDue = $sch->next_due_date ? \Carbon\Carbon::parse($sch->next_due_date) : null; @endphp
                                                <span class="fw-bold {{ $nextDue && $nextDue->isPast() ? 'text-danger' : 'text-primary' }}">
                                                    {{ $nextDue ? $nextDue->format('d M, Y') : '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $sch->alert_before_days }} দিন পূর্বে
                                            </td>
                                            <td>
                                                <span class="badge bg-success-light text-success"><i class="fa fa-check-circle me-1"></i> সক্রিয়</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                <i class="fa fa-calendar-times-o fa-3x mb-3 text-secondary opacity-50"></i>
                                                <p class="mb-0">কোনো নিয়মিত সিডিউল এন্ট্রি নেই।</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
</div>

<!-- Modal: Add New Maintenance Request -->
<div class="modal fade" id="addRequestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <form action="{{ route('admin.erp.maintenance.requests.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title fw-bold"><i class="fa fa-wrench text-warning me-2"></i> নতুন মেরামত বা সার্ভিসিং রিকোয়েস্ট</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">অফিস সম্পদ নির্বাচন (ল্যাপটপ, এসি ইত্যাদি)</label>
                            <select name="asset_id" class="form-select">
                                <option value="">-- সম্পদ নির্বাচন করুন (প্রযোজ্য হলে) --</option>
                                @foreach($assets as $a)
                                    <option value="{{ $a->id }}">{{ $a->asset_name }} ({{ $a->asset_code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">যানবাহন নির্বাচন (গাড়ি, বাইক ইত্যাদি)</label>
                            <select name="vehicle_id" class="form-select">
                                <option value="">-- যানবাহন নির্বাচন করুন (প্রযোজ্য হলে) --</option>
                                @foreach($vehicles as $v)
                                    <option value="{{ $v->id }}">{{ $v->vehicle_number }} ({{ $v->brand }} {{ $v->model }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">বিভাগের নাম</label>
                            <input type="text" name="department_name" class="form-control" placeholder="যেমন: আইটি বিভাগ">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="request_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">অগ্রাধিকার (Priority) <span class="text-danger">*</span></label>
                            <select name="priority" class="form-select" required>
                                <option value="normal">সাধারণ (Normal)</option>
                                <option value="high">জরুরি (High)</option>
                                <option value="emergency">অতীব জরুরি (Emergency)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">নির্ধারিত টেকনিশিয়ান</label>
                            <input type="text" name="technician_name" class="form-control" placeholder="টেকনিশিয়ানের নাম">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">সার্ভিস ভেন্ডর / ওয়ার্কশপ</label>
                            <input type="text" name="service_provider" class="form-control" placeholder="সার্ভিস সেন্টারের নাম">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">আনুমানিক বাজেট (৳)</label>
                            <input type="number" step="0.01" name="estimated_cost" class="form-control" placeholder="0.00">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">সমস্যার বিস্তারিত বিবরণ <span class="text-danger">*</span></label>
                            <textarea name="problem_description" class="form-control" rows="3" placeholder="সমস্যা কি, কখন শুরু হয়েছে এবং কি মেরামতের প্রয়োজন বিস্তারিত লিখুন..." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">রিকোয়েস্ট জমা দিন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Add Schedule -->
<div class="modal fade" id="addScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <form action="{{ route('admin.erp.maintenance.schedules.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title fw-bold"><i class="fa fa-calendar-plus-o text-warning me-2"></i> নিয়মিত প্রিভেন্টিভ সিডিউল নিবন্ধন</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">সার্ভিস / রক্ষণাবেক্ষণ নাম <span class="text-danger">*</span></label>
                            <input type="text" name="service_name" class="form-control" placeholder="যেমন: এসি গ্যাস রিফিল ও জেনারেল সার্ভিসিং" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">সম্পদ নির্বাচন</label>
                            <select name="asset_id" class="form-select">
                                <option value="">-- প্রযোজ্য সম্পদ নির্বাচন --</option>
                                @foreach($assets as $a)
                                    <option value="{{ $a->id }}">{{ $a->asset_name }} ({{ $a->asset_code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">যানবাহন নির্বাচন</label>
                            <select name="vehicle_id" class="form-select">
                                <option value="">-- প্রযোজ্য গাড়ি নির্বাচন --</option>
                                @foreach($vehicles as $v)
                                    <option value="{{ $v->id }}">{{ $v->vehicle_number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">বিরতির ধরন</label>
                            <select name="interval_type" class="form-select">
                                <option value="days">দিন পরপর (Days)</option>
                                <option value="months" selected>মাস পরপর (Months)</option>
                                <option value="kilometers">কিলোমিটার অতিক্রান্তে (km)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">বিরতির সংখ্যা / ব্যবধান <span class="text-danger">*</span></label>
                            <input type="number" name="interval_value" class="form-control" value="6" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">সর্বশেষ সার্ভিসের তারিখ</label>
                            <input type="date" name="last_service_date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">পরবর্তী ডিউ তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="next_due_date" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-warning text-dark px-4 fw-bold">সিডিউল সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

