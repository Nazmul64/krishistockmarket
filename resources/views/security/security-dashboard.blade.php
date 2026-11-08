@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title text-danger"><i class="fa fa-user-secret me-2"></i> Security Analyst Dashboard (সাইবার ও ফ্রড নিরাপত্তা)</h3>
                    <p class="text-muted mb-0">লাইভ সাইবার অ্যালার্ট, ফ্রড চেক, আইপি ব্লকলিস্ট ও কাস্টমার থ্রেট ডিটেকশন (Scope: Security Cases)</p>
                </div>
                <div>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#secAddBlockModal">
                        <i class="fa fa-ban me-1"></i> আইপি/অ্যাকাউন্ট ব্লক করুন
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

            <!-- KPI Cards -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body p-3 bg-danger text-white mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-white-50 font-size-12 fw-bold text-uppercase">ক্রিটিক্যাল থ্রেট (Critical)</span>
                                <h3 class="mb-0 fw-bold text-white">{{ $criticalAlerts }} টি</h3>
                            </div>
                            <i class="fa fa-fire font-size-28"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body p-3 bg-warning-light mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">উচ্চ ঝুঁকির ফ্রড কেস</span>
                                <h3 class="mb-0 fw-bold text-warning">{{ $highRiskCases->count() }} টি</h3>
                            </div>
                            <i class="fa fa-exclamation-triangle text-warning font-size-28"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body p-3 bg-primary-light mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">চলমান ফ্রড অনুসন্ধান</span>
                                <h3 class="mb-0 fw-bold text-primary">{{ $openFraudCases }} টি</h3>
                            </div>
                            <i class="fa fa-search text-primary font-size-28"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body p-3 bg-dark text-white mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-white-50 font-size-12 fw-bold text-uppercase">ব্লককৃত আইপি ও একাউন্ট</span>
                                <h3 class="mb-0 fw-bold text-white">{{ $activeBlocks }} টি</h3>
                            </div>
                            <i class="fa fa-ban text-danger font-size-28"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Live Cyber Alerts -->
            <div class="box border-top border-4 border-danger mb-4">
                <div class="box-header with-border">
                    <h4 class="box-title text-danger"><i class="fa fa-shield me-2"></i> Cyber Alerts Live Monitor (লাইভ সাইবার আক্রমণ ট্র্যাকার)</h4>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 font-size-13">
                            <thead class="bg-light">
                                <tr>
                                    <th>সময়</th>
                                    <th>থ্রেট শিরোনাম</th>
                                    <th>লেভেল</th>
                                    <th>সোর্স আইপি</th>
                                    <th>টার্গেট</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alerts as $a)
                                <tr>
                                    <td class="text-muted">{{ $a->created_at->diffForHumans() }}</td>
                                    <td>
                                        <span class="fw-bold text-dark d-block">{{ $a->title }}</span>
                                        <small class="text-muted">{{ $a->details }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $a->severity === 'critical' ? 'bg-danger' : ($a->severity === 'high' ? 'bg-warning text-dark' : 'bg-info') }}">
                                            {{ ucfirst($a->severity) }}
                                        </span>
                                    </td>
                                    <td><code>{{ $a->source_ip ?? 'N/A' }}</code></td>
                                    <td><code>{{ $a->target ?? '/' }}</code></td>
                                    <td><span class="badge {{ $a->status === 'active' ? 'bg-danger-light text-danger' : 'bg-success-light text-success' }}">{{ ucfirst($a->status) }}</span></td>
                                    <td class="text-end">
                                        @if($a->status === 'active')
                                        <a href="{{ route('security.alert.ack', $a->id) }}" class="btn btn-xs btn-outline btn-success">
                                            <i class="fa fa-check me-1"></i> Acknowledge
                                        </a>
                                        @else
                                        <span class="text-success"><i class="fa fa-check-circle"></i> Resolved</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-3">বর্তমানে কোনো সক্রিয় সাইবার থ্রেট নেই।</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Fraud Cases & Blocklist -->
            <div class="row">
                <!-- Fraud Cases Investigation -->
                <div class="col-xl-7 col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title text-warning"><i class="fa fa-user-secret me-2"></i> Fraud Checks (সন্দেহজনক ফ্রড কেস অনুসন্ধান)</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 font-size-13">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>কেস আইডি</th>
                                            <th>অ্যাকাউন্ট</th>
                                            <th>রিস্ক স্কোর</th>
                                            <th>স্ট্যাটাস</th>
                                            <th class="text-end">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($fraudCases as $f)
                                        <tr>
                                            <td><span class="fw-bold">#{{ $f->case_number }}</span></td>
                                            <td>
                                                <div class="fw-bold">{{ $f->customer_name }}</div>
                                                <small class="text-muted">{{ Str::limit($f->title, 30) }}</small>
                                            </td>
                                            <td>
                                                <span class="fw-bold {{ $f->risk_score >= 70 ? 'text-danger' : 'text-warning' }}">{{ $f->risk_score }}%</span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $f->status === 'open' ? 'bg-danger-light text-danger' : ($f->status === 'investigating' ? 'bg-warning-light text-warning' : 'bg-success-light text-success') }}">
                                                    {{ ucfirst($f->status) }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-xs btn-outline btn-primary" data-bs-toggle="modal" data-bs-target="#secFraudModal{{ $f->id }}">
                                                    <i class="fa fa-pencil"></i> সমাধান
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Update Modal -->
                                        <div class="modal fade" id="secFraudModal{{ $f->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('security.fraud.update', $f->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header bg-warning text-dark">
                                                            <h5 class="modal-title">কেস আপডেট (#{{ $f->case_number }})</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">স্ট্যাটাস</label>
                                                                <select name="status" class="form-select">
                                                                    <option value="open" {{ $f->status === 'open' ? 'selected' : '' }}>Open</option>
                                                                    <option value="investigating" {{ $f->status === 'investigating' ? 'selected' : '' }}>Investigating</option>
                                                                    <option value="resolved" {{ $f->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                                                    <option value="escalated" {{ $f->status === 'escalated' ? 'selected' : '' }}>Escalated</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">তদন্ত নোট (Audit Trail এ সংরক্ষিত হবে)</label>
                                                                <textarea name="notes" class="form-control" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                                                            <button type="submit" class="btn btn-warning">আপডেট সংরক্ষণ করুন</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">কোন ফ্রড কেস নেই।</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Blocklist -->
                <div class="col-xl-5 col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title text-danger"><i class="fa fa-ban me-2"></i> সক্রিয় ব্লকলিস্ট (Active Blocklist)</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 font-size-13">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>টাইপ ও ভ্যালু</th>
                                            <th>কারণ</th>
                                            <th class="text-end">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($blocklists as $b)
                                        <tr>
                                            <td>
                                                <span class="badge bg-dark font-size-10 me-1">{{ strtoupper($b->type) }}</span>
                                                <code>{{ $b->value }}</code>
                                            </td>
                                            <td>{{ Str::limit($b->reason, 25) }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('security.block.remove', $b->id) }}" class="btn btn-xs btn-outline btn-danger" onclick="return confirm('ব্লকলিস্ট থেকে আনব্লক করতে চান?')">
                                                    <i class="fa fa-unlock"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">কোন ব্লকলিস্ট তথ্য নেই।</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Block Modal -->
            <div class="modal fade" id="secAddBlockModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('security.block.add') }}" method="POST">
                            @csrf
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title"><i class="fa fa-ban me-2"></i> আইপি বা অ্যাকাউন্ট ব্লক করুন</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">টাইপ</label>
                                    <select name="type" class="form-select">
                                        <option value="ip" selected>IP Address</option>
                                        <option value="phone">Phone Number</option>
                                        <option value="email">Email</option>
                                        <option value="account">Username</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">ভ্যালু</label>
                                    <input type="text" name="value" class="form-control" placeholder="192.168.1.100 অথবা মোবাইল নম্বর" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">ব্লক করার কারণ (বাধ্যতামূলক)</label>
                                    <textarea name="reason" class="form-control" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                                <button type="submit" class="btn btn-danger">ব্লকলিস্টে যোগ করুন</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
