@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title text-danger"><i class="fa fa-shield me-2"></i> Security Hub & Cyber Alerts (নিরাপত্তা ও ফ্রড নিয়ন্ত্রণ)</h3>
                    <p class="text-muted mb-0">লাইভ সাইবার অ্যালার্ট, ফ্রড চেক, কাস্টমার ডিটেকশন ও আইপি ব্লকলিস্ট</p>
                </div>
                <div>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addBlockModal">
                        <i class="fa fa-ban me-1"></i> আইপি/অ্যাকাউন্ট ব্লক করুন (Add to Blocklist)
                    </button>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Summary KPI Badges -->
            <div class="row g-3 mb-3">
                <div class="col-md-4 col-12">
                    <div class="box box-body p-3 bg-danger-light mb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">সক্রিয় সাইবার অ্যালার্ট</span>
                                <h3 class="mb-0 fw-bold text-danger">{{ $activeAlertsCount }}</h3>
                            </div>
                            <i class="fa fa-bell text-danger font-size-24"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="box box-body p-3 bg-warning-light mb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">উন্মুক্ত ফ্রড কেস (Open Fraud Cases)</span>
                                <h3 class="mb-0 fw-bold text-warning">{{ $openFraudCasesCount }}</h3>
                            </div>
                            <i class="fa fa-exclamation-circle text-warning font-size-24"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="box box-body p-3 bg-dark text-white mb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">ব্লকলিস্টেড আইপি/ইউজার</span>
                                <h3 class="mb-0 fw-bold text-white">{{ $activeBlocksCount }}</h3>
                            </div>
                            <i class="fa fa-ban text-danger font-size-24"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 1. Live Cyber Alerts Section -->
            <div class="box mb-4">
                <div class="box-header with-border">
                    <h4 class="box-title text-danger"><i class="fa fa-exclamation-triangle me-2"></i> Cyber Alerts (লাইভ নিরাপত্তা হুমকি)</h4>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 font-size-13">
                            <thead class="bg-light">
                                <tr>
                                    <th>সময়</th>
                                    <th>শিরোনাম / থ্রেট টাইপ</th>
                                    <th>থ্রেট লেভেল (Severity)</th>
                                    <th>সোর্স আইপি (Source IP)</th>
                                    <th>টার্গেট এন্ডপয়েন্ট</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alerts as $alert)
                                <tr>
                                    <td class="text-muted">{{ $alert->created_at->diffForHumans() }}</td>
                                    <td>
                                        <span class="fw-bold text-dark d-block">{{ $alert->title }}</span>
                                        <small class="text-muted">{{ Str::limit($alert->details, 60) }}</small>
                                    </td>
                                    <td>
                                        @if($alert->severity === 'critical')
                                            <span class="badge bg-danger"><i class="fa fa-fire me-1"></i> Critical</span>
                                        @elseif($alert->severity === 'high')
                                            <span class="badge bg-warning text-dark"><i class="fa fa-warning me-1"></i> High</span>
                                        @elseif($alert->severity === 'medium')
                                            <span class="badge bg-info">Medium</span>
                                        @else
                                            <span class="badge bg-secondary">Low</span>
                                        @endif
                                    </td>
                                    <td><code>{{ $alert->source_ip ?? 'Unknown' }}</code></td>
                                    <td><code>{{ $alert->target ?? '/' }}</code></td>
                                    <td>
                                        <span class="badge {{ $alert->status === 'active' ? 'bg-danger-light text-danger' : 'bg-success-light text-success' }}">
                                            {{ ucfirst($alert->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        @if($alert->status === 'active')
                                        <a href="{{ route('admin.rbac.alert.ack', $alert->id) }}" class="btn btn-xs btn-outline btn-success">
                                            <i class="fa fa-check me-1"></i> Acknowledge
                                        </a>
                                        @else
                                        <span class="text-muted font-size-11"><i class="fa fa-check-circle text-success"></i> Handled</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-3">কোন সক্রিয় সাইবার অ্যালার্ট নেই।</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 2. Fraud Detection Cases & IP Blocklist Grid -->
            <div class="row">
                <!-- Fraud Cases -->
                <div class="col-xl-6 col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title text-warning"><i class="fa fa-user-secret me-2"></i> Fraud Checks (সন্দেহভাজন লেনদেন ও ফ্রড কেস)</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 font-size-13">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>কেস নং</th>
                                            <th>কাস্টমার / অ্যাকাউন্ট</th>
                                            <th>রিস্ক স্কোর</th>
                                            <th>স্ট্যাটাস</th>
                                            <th class="text-end">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($fraudCases as $case)
                                        <tr>
                                            <td><span class="fw-bold text-dark">#{{ $case->case_number }}</span></td>
                                            <td>
                                                <div class="fw-bold">{{ $case->customer_name }}</div>
                                                <small class="text-muted">{{ Str::limit($case->title, 35) }}</small>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="fw-bold me-2 {{ $case->risk_score >= 75 ? 'text-danger' : ($case->risk_score >= 50 ? 'text-warning' : 'text-success') }}">{{ $case->risk_score }}%</span>
                                                    <div class="progress flex-grow-1" style="height: 6px;">
                                                        <div class="progress-bar {{ $case->risk_score >= 75 ? 'bg-danger' : ($case->risk_score >= 50 ? 'bg-warning' : 'bg-success') }}" role="progressbar" style="width: {{ $case->risk_score }}%"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge {{ $case->status === 'open' ? 'bg-danger-light text-danger' : ($case->status === 'investigating' ? 'bg-warning-light text-warning' : 'bg-success-light text-success') }}">
                                                    {{ ucfirst($case->status) }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-xs btn-outline btn-primary" data-bs-toggle="modal" data-bs-target="#fraudModal{{ $case->id }}">
                                                    <i class="fa fa-pencil me-1"></i> আপডেট
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Fraud Case Update Modal -->
                                        <div class="modal fade" id="fraudModal{{ $case->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.rbac.fraud.update', $case->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header bg-warning text-dark">
                                                            <h5 class="modal-title"><i class="fa fa-shield me-2"></i> ফ্রড কেস আপডেট (#{{ $case->case_number }})</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">কেস স্ট্যাটাস</label>
                                                                <select name="status" class="form-select">
                                                                    <option value="open" {{ $case->status === 'open' ? 'selected' : '' }}>Open (অমীমাংসিত)</option>
                                                                    <option value="investigating" {{ $case->status === 'investigating' ? 'selected' : '' }}>Investigating (অনুসন্ধানাধীন)</option>
                                                                    <option value="resolved" {{ $case->status === 'resolved' ? 'selected' : '' }}>Resolved (সমাধানকৃত)</option>
                                                                    <option value="escalated" {{ $case->status === 'escalated' ? 'selected' : '' }}>Escalated (উর্ধ্বতন শাখায় প্রেরিত)</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">তদন্ত নোট (Investigation Notes)</label>
                                                                <textarea name="notes" class="form-control" rows="3" placeholder="কেস অনুসন্ধান ও সিদ্ধান্তের বিবরণ লিখুন..."></textarea>
                                                            </div>
                                                            @if($case->notes)
                                                            <div class="bg-light p-2 rounded font-size-12">
                                                                <span class="fw-bold d-block mb-1">পূর্ববর্তী নোট:</span>
                                                                <pre class="mb-0 text-muted">{{ $case->notes }}</pre>
                                                            </div>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                                                            <button type="submit" class="btn btn-warning"><i class="fa fa-save me-1"></i> আপডেট সংরক্ষণ করুন</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">কোন ফ্রড কেস রেকর্ড নেই।</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- IP & Account Blocklist -->
                <div class="col-xl-6 col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title text-dark"><i class="fa fa-ban text-danger me-2"></i> Blocklist & IP Block (ব্লকলিস্ট তালিকা)</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 font-size-13">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>টাইপ</th>
                                            <th>ভ্যালু (IP / Phone)</th>
                                            <th>কারণ</th>
                                            <th>ব্লকার</th>
                                            <th class="text-end">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($blocklists as $b)
                                        <tr>
                                            <td>
                                                <span class="badge bg-dark font-size-11">{{ strtoupper($b->type) }}</span>
                                            </td>
                                            <td><code>{{ $b->value }}</code></td>
                                            <td>{{ Str::limit($b->reason, 30) }}</td>
                                            <td><small class="text-muted">{{ optional($b->blocker)->name ?? 'System' }}</small></td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.rbac.blocklist.remove', $b->id) }}" class="btn btn-xs btn-outline btn-danger" onclick="return confirm('আপনি কি নিশ্চিত যে ব্লকলিস্ট থেকে মুক্ত করতে চান?')">
                                                    <i class="fa fa-unlock me-1"></i> Unblock
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">ব্লকলিস্টে কোন তথ্য নেই।</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add to Blocklist Modal -->
            <div class="modal fade" id="addBlockModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('admin.rbac.blocklist.store') }}" method="POST">
                            @csrf
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title"><i class="fa fa-ban me-2"></i> নতুন এন্ট্রি ব্লকলিস্টে যুক্ত করুন</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">ব্লক টাইপ <span class="text-danger">*</span></label>
                                    <select name="type" class="form-select" required>
                                        <option value="ip" selected>IP Address (আইপি এড্রেস)</option>
                                        <option value="phone">Phone Number (মোবাইল নম্বর)</option>
                                        <option value="email">Email Address (ইমেইল ঠিকানা)</option>
                                        <option value="account">Account / Username (অ্যাকাউন্ট)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">ব্লককৃত ভ্যালু <span class="text-danger">*</span></label>
                                    <input type="text" name="value" class="form-control" placeholder="যেমন: 192.168.1.50 অথবা 01712345678" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">ব্লক করার কারণ (Reason) <span class="text-danger">*</span></label>
                                    <textarea name="reason" class="form-control" rows="3" placeholder="নির্দিষ্ট কারণ ও রেফারেন্স লিখুন (বাধ্যতামূলক)..." required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                                <button type="submit" class="btn btn-danger"><i class="fa fa-ban me-1"></i> ব্লকলিস্টে সংরক্ষণ করুন</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </section>
    </div>
</div>
@endsection
