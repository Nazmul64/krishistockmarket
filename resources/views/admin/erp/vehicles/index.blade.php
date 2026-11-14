@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-car text-primary me-2"></i> যানবাহন ও পরিবহন ফ্লিট ব্যবস্থাপনা (Fleet Management)</h4>
                    <p class="text-muted small mb-0">কোম্পানির অফিসিয়াল গাড়ি, মাইক্রোবাস, পিকআপ ও মোটরসাইকেলের ডিজিটাল রেকর্ড, চালক তথ্য ও কাগজপত্রের মেয়াদ ট্র্যাকিং।</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                        <i class="fa fa-plus me-1"></i> নতুন গাড়ি সংযোজন
                    </button>
                    <a href="{{ route('admin.erp.vehicles.trips') }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                        <i class="fa fa-road me-1"></i> ট্রিপ ও ফুয়েল লগ
                    </a>
                </div>
            </div>

            <!-- KPI Metric Badges -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-primary">
                        <small class="text-muted text-uppercase fw-bold font-size-11">মোট যানবাহন</small>
                        <h4 class="fw-bold text-dark my-1">{{ number_format($totalVehicles) }} টি</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-success">
                        <small class="text-muted text-uppercase fw-bold font-size-11">চলমান / সক্রিয়</small>
                        <h4 class="fw-bold text-success my-1">{{ number_format($activeVehicles) }} টি</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-info">
                        <small class="text-muted text-uppercase fw-bold font-size-11">মোট মাইলেজ অতিক্রান্ত</small>
                        <h4 class="fw-bold text-info my-1">{{ number_format($totalMileage) }} কি.মি.</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-warning">
                        <small class="text-muted text-uppercase fw-bold font-size-11">মোট জ্বালানি খরচ</small>
                        <h4 class="fw-bold text-warning my-1">৳ {{ number_format($totalFuelCost, 2) }}</h4>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Vehicle Filter Form -->
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
                <form action="{{ route('admin.erp.vehicles') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <input type="text" name="vehicle_number" class="form-control form-control-sm" placeholder="গাড়ির নম্বর দিয়ে খুঁজুন..." value="{{ request('vehicle_number') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="vehicle_type" class="form-select form-select-sm">
                            <option value="">-- সকল যানবাহনের ধরন --</option>
                            <option value="car" {{ request('vehicle_type') == 'car' ? 'selected' : '' }}>কার (Sedan / Car)</option>
                            <option value="microbus" {{ request('vehicle_type') == 'microbus' ? 'selected' : '' }}>মাইক্রোবাস (Microbus)</option>
                            <option value="pickup" {{ request('vehicle_type') == 'pickup' ? 'selected' : '' }}>পিকআপ (Pickup)</option>
                            <option value="motorcycle" {{ request('vehicle_type') == 'motorcycle' ? 'selected' : '' }}>মোটরসাইকেল (Bike)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">-- সকল স্ট্যাটাস --</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>সক্রিয় (Active)</option>
                            <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>মেরামতে রয়েছে (Maintenance)</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয় (Inactive)</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-sm btn-dark w-100 rounded-3"><i class="fa fa-filter me-1"></i> ফিল্টার</button>
                    </div>
                </form>
            </div>

            <!-- Vehicles Table -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-muted small text-uppercase">
                                <th class="ps-4">গাড়ির নম্বর ও মডেল</th>
                                <th>ধরন</th>
                                <th>চালক ও যোগাযোগ</th>
                                <th>বর্তমান মাইলেজ</th>
                                <th>জ্বালানি ধরন</th>
                                <th>কাগজপত্রের মেয়াদ ট্র্যাকার</th>
                                <th>স্ট্যাটাস</th>
                                <th class="text-end pe-4">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vehicles as $veh)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar avatar-sm bg-primary-light text-primary rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="fa fa-car"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $veh->vehicle_number }}</div>
                                                <small class="text-muted">{{ $veh->brand }} {{ $veh->model }} ({{ $veh->manufacturing_year ?? 'N/A' }})</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ strtoupper($veh->vehicle_type) }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark font-size-13">{{ $veh->assigned_driver ?? 'চালক নির্ধারিত নেই' }}</div>
                                        <small class="text-muted"><i class="fa fa-phone me-1"></i>{{ $veh->driver_phone ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-info">{{ number_format($veh->current_mileage) }}</span> কি.মি.
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary-light text-secondary">{{ ucfirst($veh->fuel_type) }}</span>
                                    </td>
                                    <td>
                                        @if($veh->documents->isEmpty())
                                            <span class="badge bg-light text-muted">কোনো ডকুমেন্ট যুক্ত নেই</span>
                                        @else
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($veh->documents as $doc)
                                                    @php
                                                        $badgeClass = 'bg-success';
                                                        $statusText = 'মেয়াদ আছে';
                                                        if($doc->isExpired()) {
                                                            $badgeClass = 'bg-danger';
                                                            $statusText = 'মেয়াদোত্তীর্ণ!';
                                                        } elseif($doc->isExpiringSoon()) {
                                                            $badgeClass = 'bg-warning text-dark';
                                                            $statusText = 'দ্রুত রিনিউ করুন';
                                                        }
                                                    @endphp
                                                    @php $expDate = $doc->expiry_date ? \Carbon\Carbon::parse($doc->expiry_date) : null; @endphp
                                                    <span class="badge {{ $badgeClass }}" title="{{ $expDate ? $expDate->format('d/m/Y') : '' }}">
                                                        {{ strtoupper($doc->document_type) }}: {{ $statusText }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($veh->status == 'active')
                                            <span class="badge bg-success-light text-success"><i class="fa fa-check-circle me-1"></i> সক্রিয়</span>
                                        @elseif($veh->status == 'maintenance')
                                            <span class="badge bg-warning-light text-warning"><i class="fa fa-wrench me-1"></i> সার্ভিসিং/মেরামত</span>
                                        @else
                                            <span class="badge bg-danger-light text-danger"><i class="fa fa-ban me-1"></i> নিষ্ক্রিয়</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-primary rounded-pill px-2" title="নতুন ডকুমেন্ট যুক্ত করুন" data-bs-toggle="modal" data-bs-target="#addDocModal{{ $veh->id }}">
                                                <i class="fa fa-file-text-o me-1"></i> ডকুমেন্ট
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal: Add Document for this vehicle -->
                                <div class="modal fade" id="addDocModal{{ $veh->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0">
                                            <form action="{{ route('admin.erp.vehicles.documents.store') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="vehicle_id" value="{{ $veh->id }}">
                                                <div class="modal-header border-0 bg-light">
                                                    <h5 class="modal-title fw-bold"><i class="fa fa-file-text text-primary me-2"></i> ডকুমেন্ট নবায়ন / নিবন্ধন ({{ $veh->vehicle_number }})</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">ডকুমেন্টের ধরন <span class="text-danger">*</span></label>
                                                        <select name="document_type" class="form-select" required>
                                                            <option value="fitness">ফিটনেস সনদ (Fitness Certificate)</option>
                                                            <option value="tax_token">ট্যাক্স টোকেন (Tax Token)</option>
                                                            <option value="route_permit">রুট পারমিট (Route Permit)</option>
                                                            <option value="insurance">বীমা সনদ (Insurance Policy)</option>
                                                            <option value="registration">রেজিস্ট্রেশন সনদ (Registration)</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">সনদ / লাইসেন্স নম্বর</label>
                                                        <input type="text" name="document_number" class="form-control" placeholder="যেমন: TT-2026-9874">
                                                    </div>
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-6">
                                                            <label class="form-label fw-bold">ইস্যুর তারিখ</label>
                                                            <input type="date" name="issue_date" class="form-control">
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label fw-bold">মেয়াদ উত্তীর্ণের তারিখ <span class="text-danger">*</span></label>
                                                            <input type="date" name="expiry_date" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">কত দিন আগে সতর্কবার্তা পাঠাবে?</label>
                                                        <input type="number" name="alert_days" class="form-control" value="15" min="1" max="90">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">ডকুমেন্ট স্ক্যান কপি (PDF/Image)</label>
                                                        <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                                                    <button type="submit" class="btn btn-primary px-4">সংরক্ষণ করুন</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fa fa-car fa-3x mb-3 text-secondary opacity-50"></i>
                                        <p class="mb-0">কোনো যানবাহনের তথ্য পাওয়া যায়নি।</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($vehicles->hasPages())
                    <div class="p-3 border-top">
                        {{ $vehicles->links() }}
                    </div>
                @endif
            </div>

        </section>
    </div>
</div>

<!-- Modal: Add New Vehicle -->
<div class="modal fade" id="addVehicleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <form action="{{ route('admin.erp.vehicles.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title fw-bold"><i class="fa fa-car text-primary me-2"></i> নতুন যানবাহন ফ্লিটে নিবন্ধন</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">রেজিস্ট্রেশন নম্বর (গাড়ির নম্বর) <span class="text-danger">*</span></label>
                            <input type="text" name="vehicle_number" class="form-control" placeholder="যেমন: ঢাকা মেট্রো-গ-১৫-৮৮৯৯" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">যানবাহনের ধরন <span class="text-danger">*</span></label>
                            <select name="vehicle_type" class="form-select" required>
                                <option value="car">কার (Sedan / Car)</option>
                                <option value="microbus">মাইক্রোবাস (Microbus / Hiace)</option>
                                <option value="pickup">পিকআপ / কাভার্ড ভ্যান (Pickup / Van)</option>
                                <option value="motorcycle">মোটরসাইকেল (Bike)</option>
                                <option value="truck">ট্রাক (Truck / Lorry)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ব্র্যান্ড (Brand) <span class="text-danger">*</span></label>
                            <input type="text" name="brand" class="form-control" placeholder="যেমন: Toyota" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">মডেল (Model)</label>
                            <input type="text" name="model" class="form-control" placeholder="যেমন: Allion A15">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">তৈরির বছর (Year)</label>
                            <input type="number" name="manufacturing_year" class="form-control" placeholder="যেমন: 2020" min="1990" max="{{ date('Y') + 1 }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ক্রয় তারিখ</label>
                            <input type="date" name="purchase_date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ক্রয়মূল্য (৳)</label>
                            <input type="number" name="purchase_price" step="0.01" class="form-control" placeholder="0.00">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">চেসিস নম্বর</label>
                            <input type="text" name="chassis_no" class="form-control" placeholder="Chassis No">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ইঞ্জিন নম্বর</label>
                            <input type="text" name="engine_no" class="form-control" placeholder="Engine No">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">জ্বালানি ধরন</label>
                            <select name="fuel_type" class="form-select">
                                <option value="octane">অকটেন (Octane)</option>
                                <option value="petrol">পেট্রোল (Petrol)</option>
                                <option value="diesel">ডিজেল (Diesel)</option>
                                <option value="cng">সিএনজি (CNG)</option>
                                <option value="electric">বৈদ্যুতিক (EV)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">বর্তমান মাইলেজ (কি.মি.)</label>
                            <input type="number" name="current_mileage" class="form-control" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">স্ট্যাটাস</label>
                            <select name="status" class="form-select">
                                <option value="active">সক্রিয় (Active)</option>
                                <option value="maintenance">মেরামত চলছে (Maintenance)</option>
                                <option value="inactive">নিষ্ক্রিয় (Inactive)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">নির্ধারিত চালকের নাম</label>
                            <input type="text" name="assigned_driver" class="form-control" placeholder="চালকের নাম">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">চালকের ফোন নম্বর</label>
                            <input type="text" name="driver_phone" class="form-control" placeholder="017xxxxxxxx">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ড্রাইভিং লাইসেন্স নম্বর</label>
                            <input type="text" name="driver_license" class="form-control" placeholder="DL-123456">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">গাড়ি নিবন্ধন করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

