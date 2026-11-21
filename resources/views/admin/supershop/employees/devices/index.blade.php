@extends('layouts.backend.app')

@section('title', 'বায়োমেট্রিক ফিঙ্গারপ্রিন্ট উপস্থিতি মেশিন ইন্টিগ্রেশন (Biometric Integration)')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content">

    <!-- Header & Action -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">
                <i class="ti-thumb-up text-primary me-2"></i> বায়োমেট্রিক ও ফিঙ্গারপ্রিন্ট মেশিন ইন্টিগ্রেশন হাব
            </h4>
            <p class="text-muted small mb-0">ZKTeco, TCP/IP, API Push ও ক্লাউড সিঙ্ক কনফিগারেশন এবং ডুপ্লিকেট পাঞ্চ ফিল্টারিং সিস্টেম।</p>
        </div>
        <div>
            <button type="button" class="btn btn-success fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addDeviceModal">
                <i class="ti-plus me-1"></i> নতুন ডিভাইস কনফিগার করুন
            </button>
        </div>
    </div>

    <!-- Machine Overview Alert -->
    <div class="alert alert-secondary border-0 shadow-sm rounded-3 p-3 mb-4 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <i class="ti-harddrives text-primary fa-2x me-3"></i>
            <div>
                <strong class="text-dark d-block">অটোমেটিক হাজিরা সিঙ্ক ইঞ্জিন ও ডুপ্লিকেট পাঞ্চ প্রতিরোধ (Section 8.2 & 8.5)</strong>
                <span class="text-muted small">মেশিন থেকে পাঞ্চ ডেটা গ্রহণ করার পর স্বয়ংক্রিয়ভাবে Employee ID ও ব্রাঞ্চ শনাক্ত করে হাজিরা সংরক্ষণ করা হয়। একই পাঞ্চ একাধিকবার সিঙ্ক হলেও ডুপ্লিকেট বাদ দেওয়া হয়।</span>
            </div>
        </div>
        <div class="text-end d-none d-md-block">
            <span class="badge bg-primary px-3 py-2"><i class="ti-pulse"></i> Engine Ready</span>
        </div>
    </div>

    <!-- Device Cards Grid (Section 8.1) -->
    <div class="row g-4 mb-4">
        @forelse($devices as $device)
            <div class="col-lg-6 col-12">
                <div class="card border-0 shadow-sm rounded-3 h-100 p-4" style="border-left: 5px solid {{ $device->status === 'connected' || $device->status === 'synced' ? '#10b981' : '#ef4444' }} !important;">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge bg-dark mb-1">{{ $device->device_id }}</span>
                            <h5 class="fw-bold text-dark mb-0">{{ $device->device_name }}</h5>
                            <small class="text-muted"><i class="ti-home"></i> {{ $device->branch->name ?? '' }} ({{ $device->branch->code ?? '' }})</small>
                        </div>
                        <div>
                            @if($device->status === 'connected' || $device->status === 'synced')
                                <span class="badge bg-success px-3 py-2"><i class="ti-link me-1"></i> Connected</span>
                            @elseif($device->status === 'syncing')
                                <span class="badge bg-warning text-dark px-3 py-2"><i class="ti-reload fa-spin me-1"></i> Syncing...</span>
                            @else
                                <span class="badge bg-danger px-3 py-2"><i class="ti-unlink me-1"></i> Disconnected</span>
                            @endif
                        </div>
                    </div>

                    <div class="row g-2 mb-3 bg-light p-3 rounded">
                        <div class="col-6">
                            <span class="text-muted small d-block">মডেল ও ব্র্যান্ড:</span>
                            <strong class="text-dark">{{ $device->model ?: 'ZKTeco' }}</strong>
                        </div>
                        <div class="col-6">
                            <span class="text-muted small d-block">আইপি ও পোর্ট:</span>
                            <span class="font-monospace fw-bold text-primary">{{ $device->ip_address }}:{{ $device->port }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted small d-block">কানেকশন মেথড:</span>
                            <span class="badge bg-info text-dark">{{ $device->connection_type }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted small d-block">সর্বশেষ সফল সিঙ্ক:</span>
                            <span class="text-dark small fw-bold">{{ $device->last_successful_sync ? date('d M, h:i A', strtotime($device->last_successful_sync)) : 'কখনো হয়নি' }}</span>
                        </div>
                    </div>

                    @if($device->last_error_message)
                        <div class="alert alert-danger py-1 px-2 small mb-3">
                            <i class="ti-alert me-1"></i> {{ $device->last_error_message }}
                        </div>
                    @endif

                    <div class="d-flex flex-wrap gap-2 mt-auto">
                        <!-- Test Connection Form -->
                        <form action="{{ route('admin.supershop.employee_system.devices.test', $device->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary fw-bold">
                                <i class="ti-pulse me-1"></i> টেস্ট কানেকশন
                            </button>
                        </form>

                        <!-- Trigger Sync Form -->
                        <form action="{{ route('admin.supershop.employee_system.devices.sync', $device->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success fw-bold">
                                <i class="ti-reload me-1"></i> এখনি সিঙ্ক করুন
                            </button>
                        </form>

                        <!-- Edit Button -->
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editModal{{ $device->id }}">
                            <i class="ti-pencil me-1"></i> এডিট
                        </button>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal{{ $device->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('admin.supershop.employee_system.devices.update', $device->id) }}" method="POST">
                            @csrf
                            <div class="modal-header bg-light">
                                <h6 class="modal-title fw-bold">ডিভাইস এডিট: {{ $device->device_name }}</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">ডিভাইসের নাম</label>
                                    <input type="text" name="device_name" class="form-control" value="{{ $device->device_name }}" required>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-8">
                                        <label class="form-label fw-bold">IP Address</label>
                                        <input type="text" name="ip_address" class="form-control" value="{{ $device->ip_address }}" required>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label fw-bold">Port</label>
                                        <input type="number" name="port" class="form-control" value="{{ $device->port }}" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">মডেল ও প্রস্তুতকারক</label>
                                    <input type="text" name="model" class="form-control" value="{{ $device->model }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">ডিভাইস পাসওয়ার্ড (পরিবর্তন করতে চাইলে)</label>
                                    <input type="password" name="password" class="form-control" placeholder="Leave empty to keep current">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                                <button type="submit" class="btn btn-primary fw-bold">আপডেট করুন</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="ti-harddrives fa-3x d-block mb-3 text-secondary"></i>
                <p class="text-muted">কোনো বায়োমেট্রিক উপস্থিতি মেশিন যুক্ত করা নেই। উপরের বাটনে ক্লিক করে নতুন ডিভাইস কনফিগার করুন।</p>
            </div>
        @endforelse
    </div>

    <!-- Recent Sync Logs Table (Section 8.1) -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark"><i class="ti-notepad text-primary me-2"></i> সাম্প্রতিক মেশিন সিঙ্ক হিস্ট্রি ও অডিট লগ</h6>
            <small class="text-muted">ডুপ্লিকেট পাঞ্চ স্বয়ংক্রিয়ভাবে ফিল্টার করা হয়</small>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ডিভাইস ও ব্রাঞ্চ</th>
                        <th>সিঙ্ক টাইপ</th>
                        <th>প্রাপ্ত রেকর্ড</th>
                        <th>সফল সিঙ্ক</th>
                        <th>ডুপ্লিকেট বাতিল</th>
                        <th>স্ট্যাটাস</th>
                        <th class="pe-4">সিঙ্ক সময়</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLogs as $log)
                        <tr>
                            <td class="ps-4">
                                <strong class="text-dark">{{ $log->device->device_name ?? 'Device' }}</strong>
                                <small class="text-muted d-block">{{ $log->branch->name ?? '' }}</small>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ ucfirst($log->sync_type) }}</span></td>
                            <td class="fw-bold">{{ $log->records_received }}</td>
                            <td class="fw-bold text-success">{{ $log->records_synced }}</td>
                            <td class="fw-bold text-muted">{{ $log->records_duplicate }}</td>
                            <td>
                                @if($log->status === 'success')
                                    <span class="badge bg-success-subtle text-success">সফল</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">ব্যর্থ</span>
                                @endif
                            </td>
                            <td class="pe-4 text-muted small">
                                {{ date('d M Y, h:i:s A', strtotime($log->synced_at)) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">কোনো সিঙ্ক লগ পাওয়া যায়নি।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add New Device Modal (Section 8.1 & 8.3) -->
    <div class="modal fade" id="addDeviceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.supershop.employee_system.devices.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h6 class="modal-title fw-bold text-white"><i class="ti-plus me-1"></i> নতুন ফিঙ্গারপ্রিন্ট উপস্থিতি মেশিন যুক্ত করুন</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">মেশিনের নাম <span class="text-danger">*</span></label>
                                <input type="text" name="device_name" class="form-control" placeholder="যেমন: ZKTeco Main Gate BR-01" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Device ID (Unique) <span class="text-danger">*</span></label>
                                <input type="text" name="device_id" class="form-control font-monospace" placeholder="DEV-BR01-01" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">সংশ্লিষ্ট ব্রাঞ্চ <span class="text-danger">*</span></label>
                                @if($isAdmin)
                                    <select name="branch_id" class="form-select fw-bold" required>
                                        @foreach($branches as $b)
                                            <option value="{{ $b->id }}">{{ $b->code }} - {{ $b->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="hidden" name="branch_id" value="{{ $userBranchId }}">
                                    <input type="text" class="form-control bg-light" value="{{ $branches->first()->name ?? '' }}" readonly>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">মেশিন মডেল</label>
                                <input type="text" name="model" class="form-control" placeholder="ZKTeco MB10 / UA760">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">IP Address <span class="text-danger">*</span></label>
                                <input type="text" name="ip_address" class="form-control font-monospace" placeholder="192.168.1.201" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Port <span class="text-danger">*</span></label>
                                <input type="number" name="port" class="form-control font-monospace" value="4370" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">কানেকশন টাইপ</label>
                                <select name="connection_type" class="form-select">
                                    <option value="LAN / TCP-IP">LAN / Local Network (TCP/IP)</option>
                                    <option value="Device API">Device REST API</option>
                                    <option value="SDK Integration">SDK Integration</option>
                                    <option value="Cloud Push">Supported Cloud Push</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">সিরিয়াল নম্বর (Serial Number)</label>
                                <input type="text" name="serial_number" class="form-control" placeholder="ZK8837192">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">কমিউনিকেশন পাসওয়ার্ড</label>
                                <input type="password" name="password" class="form-control" placeholder="Device password if any">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                        <button type="submit" class="btn btn-success fw-bold px-4">সংরক্ষণ করুন</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

        </section>
    </div>
</div>
@endsection
