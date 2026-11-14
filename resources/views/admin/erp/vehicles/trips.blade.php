@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-road text-info me-2"></i> যানবাহন ট্রিপ ও জ্বালানি খরচ লগ (Trips & Fuel Log)</h4>
                    <p class="text-muted small mb-0">অফিসিয়াল গাড়ির দৈনিক চলাচল, গন্তব্য, দূরত্ব, টোল ও ফুয়েল ব্যয়ের পুঙ্খানুপুঙ্খ ডিজিটাল হিসাব।</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-info text-white rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addTripModal">
                        <i class="fa fa-plus me-1"></i> নতুন ট্রিপ রেকর্ড
                    </button>
                    <button class="btn btn-sm btn-warning text-dark rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addFuelModal">
                        <i class="fa fa-tint me-1"></i> জ্বালানি ক্রয় রেকর্ড
                    </button>
                    <a href="{{ route('admin.erp.vehicles') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="fa fa-arrow-left me-1"></i> ফ্লিট তালিকা
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Trips Table -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0"><i class="fa fa-list text-primary me-2"></i> সাম্প্রতিক ট্রিপসমূহের বিবরণী</h5>
                    <span class="badge bg-light text-muted">{{ $trips->total() }} টি ট্রিপ সম্পন্ন</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-muted small text-uppercase">
                                <th class="ps-4">ট্রিপ নম্বর ও তারিখ</th>
                                <th>গাড়ি ও চালক</th>
                                <th>রুট (শুরু ➔ গন্তব্য)</th>
                                <th>মাইলেজ হিসেব</th>
                                <th>অতিক্রান্ত দূরত্ব</th>
                                <th>খরচ (জ্বালানি + টোল + অন্যান্য)</th>
                                <th>উদ্দেশ্য</th>
                                <th class="pe-4 text-end">অনুমোদনকারী</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($trips as $trip)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $trip->trip_number }}</div>
                                        <small class="text-muted">{{ $trip->trip_date ? \Carbon\Carbon::parse($trip->trip_date)->format('d M, Y') : '-' }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $trip->vehicle->vehicle_number ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $trip->driver_name ?? $trip->vehicle->assigned_driver ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bold">{{ $trip->start_location }}</span>
                                        <i class="fa fa-arrow-right text-muted mx-1 font-size-11"></i>
                                        <span class="text-primary fw-bold">{{ $trip->destination }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">শুরু: {{ number_format($trip->start_mileage) }} কি.মি.</small><br>
                                        <small class="text-muted">শেষ: {{ number_format($trip->end_mileage) }} কি.মি.</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-light text-info fw-bold font-size-12 px-2 py-1">
                                            {{ number_format($trip->distance_km, 1) }} কি.মি.
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark font-size-13">
                                            ৳ {{ number_format($trip->fuel_cost + $trip->toll_cost + $trip->other_cost, 2) }}
                                        </div>
                                        <small class="text-muted font-size-11">
                                            ফুয়েল: ৳{{ number_format($trip->fuel_cost) }} | টোল: ৳{{ number_format($trip->toll_cost) }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="text-muted font-size-12">{{ Str::limit($trip->purpose ?? 'অফিসিয়াল কার্যক্রম', 25) }}</span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <span class="badge bg-success-light text-success font-size-11">
                                            <i class="fa fa-check me-1"></i> {{ $trip->approved_by ?? 'Approved' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fa fa-road fa-3x mb-3 text-secondary opacity-50"></i>
                                        <p class="mb-0">কোনো ট্রিপ রেকর্ড পাওয়া যায়নি।</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($trips->hasPages())
                    <div class="p-3 border-top">
                        {{ $trips->links() }}
                    </div>
                @endif
            </div>

        </section>
    </div>
</div>

<!-- Modal: Add New Trip -->
<div class="modal fade" id="addTripModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <form action="{{ route('admin.erp.vehicles.trips.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title fw-bold"><i class="fa fa-road text-info me-2"></i> নতুন ট্রিপ রেকর্ড এন্ট্রি</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">যানবাহন নির্বাচন করুন <span class="text-danger">*</span></label>
                            <select name="vehicle_id" class="form-select" required id="tripVehicleSelect">
                                <option value="">-- গাড়ি নির্বাচন করুন --</option>
                                @foreach($vehicles as $v)
                                    <option value="{{ $v->id }}" data-mileage="{{ $v->current_mileage }}" data-driver="{{ $v->assigned_driver }}">
                                        {{ $v->vehicle_number }} ({{ $v->brand }} {{ $v->model }}) - বর্তমান: {{ $v->current_mileage }} কি.মি.
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">চালকের নাম</label>
                            <input type="text" name="driver_name" id="tripDriverName" class="form-control" placeholder="চালকের নাম">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">যাত্রা শুরুর স্থান <span class="text-danger">*</span></label>
                            <input type="text" name="start_location" class="form-control" placeholder="যেমন: ধানমন্ডি হেড অফিস" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">গন্তব্য <span class="text-danger">*</span></label>
                            <input type="text" name="destination" class="form-control" placeholder="যেমন: গাজীপুর প্রজেক্ট সাইট" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ট্রিপ তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="trip_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">যাত্রা শুরুর মাইলেজ (কি.মি.) <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" name="start_mileage" id="startMileageInput" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">যাত্রা শেষের মাইলেজ (কি.মি.) <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" name="end_mileage" id="endMileageInput" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">জ্বালানি খরচ (৳)</label>
                            <input type="number" step="0.01" name="fuel_cost" class="form-control" value="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">টোল / পার্কিং খরচ (৳)</label>
                            <input type="number" step="0.01" name="toll_cost" class="form-control" value="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">অন্যান্য খরচ (৳)</label>
                            <input type="number" step="0.01" name="other_cost" class="form-control" value="0.00">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">ট্রিপের উদ্দেশ্য / নোট</label>
                            <textarea name="purpose" class="form-control" rows="2" placeholder="অফিসিয়াল মিটিং, সাইট ভিজিট ইত্যাদি..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-info text-white px-4 fw-bold">ট্রিপ সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Add New Fuel Record -->
<div class="modal fade" id="addFuelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <form action="{{ route('admin.erp.vehicles.fuel.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title fw-bold"><i class="fa fa-tint text-warning me-2"></i> জ্বালানি ক্রয় ও রিসিট এন্ট্রি</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">যানবাহন <span class="text-danger">*</span></label>
                            <select name="vehicle_id" class="form-select" required>
                                <option value="">-- গাড়ি নির্বাচন করুন --</option>
                                @foreach($vehicles as $v)
                                    <option value="{{ $v->id }}">{{ $v->vehicle_number }} ({{ $v->brand }} {{ $v->model }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">জ্বালানির ধরন <span class="text-danger">*</span></label>
                            <select name="fuel_type" class="form-select" required>
                                <option value="octane">অকটেন (Octane)</option>
                                <option value="petrol">পেট্রোল (Petrol)</option>
                                <option value="diesel">ডিজেল (Diesel)</option>
                                <option value="cng">সিএনজি (CNG)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="fuel_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">পরিমাণ (লিটার/ঘনমিটার) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="quantity_liters" id="fuelQty" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">একক প্রতি দর (৳) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="unit_price" id="fuelRate" class="form-control" placeholder="125.00" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">মোট বিল (৳)</label>
                            <input type="text" id="fuelTotalDisplay" class="form-control bg-light fw-bold text-success" readonly placeholder="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ওডোমিটার রিডিং (বর্তমান কি.মি.)</label>
                            <input type="number" name="odometer_reading" class="form-control" placeholder="Current km">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ফিলিং স্টেশন / পাম্পের নাম</label>
                            <input type="text" name="fuel_station" class="form-control" placeholder="পদ্মা অয়েল স্টেশন">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ভাউচার / মেমো রেফারেন্স নং</label>
                            <input type="text" name="voucher_ref" class="form-control" placeholder="MEMO-9874">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">মানি রিসিট / স্লিপ কপি (PDF/Image)</label>
                            <input type="file" name="slip_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-warning text-dark px-4 fw-bold">ফুয়েল এন্ট্রি সম্পন্ন করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const vehicleSelect = document.getElementById('tripVehicleSelect');
    if (vehicleSelect) {
        vehicleSelect.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            const mileage = opt.getAttribute('data-mileage');
            const driver = opt.getAttribute('data-driver');
            if (mileage) document.getElementById('startMileageInput').value = mileage;
            if (driver) document.getElementById('tripDriverName').value = driver;
        });
    }

    const fuelQty = document.getElementById('fuelQty');
    const fuelRate = document.getElementById('fuelRate');
    const fuelTotal = document.getElementById('fuelTotalDisplay');
    function calcFuel() {
        const q = parseFloat(fuelQty.value) || 0;
        const r = parseFloat(fuelRate.value) || 0;
        fuelTotal.value = '৳ ' + (q * r).toFixed(2);
    }
    if (fuelQty && fuelRate) {
        fuelQty.addEventListener('input', calcFuel);
        fuelRate.addEventListener('input', calcFuel);
    }
});
</script>
@endsection

