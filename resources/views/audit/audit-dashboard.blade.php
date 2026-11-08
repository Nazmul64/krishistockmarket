@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title text-success"><i class="fa fa-balance-scale me-2"></i> Auditor Dashboard (কমপ্লায়েন্স ও অডিট ড্যাশবোর্ড)</h3>
                    <p class="text-muted mb-0">সিস্টেম অডিট ট্রেইল, প্রিভিলেজড অ্যাকশন হিস্ট্রি ও কমপ্লায়েন্স রিপোর্ট (Scope: Read-only Inspection)</p>
                </div>
                <div>
                    <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="btn btn-success">
                        <i class="fa fa-file-excel-o me-1"></i> অডিট ট্রেইল এক্সপোর্ট (CSV)
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- KPI Cards -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body p-3 bg-primary-light mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">মোট অডিট ইভেন্ট</span>
                                <h3 class="mb-0 fw-bold text-primary">{{ number_format($totalLogsCount) }} টি</h3>
                            </div>
                            <i class="fa fa-database text-primary font-size-28"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body p-3 bg-danger-light mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">প্রিভিলেজড অ্যাকশন</span>
                                <h3 class="mb-0 fw-bold text-danger">{{ $privilegedActionsCount }} টি</h3>
                            </div>
                            <i class="fa fa-key text-danger font-size-28"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body p-3 bg-warning-light mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">সক্রিয় অ্যাক্টর (Unique Actors)</span>
                                <h3 class="mb-0 fw-bold text-warning">{{ $uniqueActorsCount }} জন</h3>
                            </div>
                            <i class="fa fa-users text-warning font-size-28"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box box-body p-3 bg-success-light mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted font-size-12 fw-bold text-uppercase">আজকের রেকর্ড (Today)</span>
                                <h3 class="mb-0 fw-bold text-success">{{ $todayLogsCount }} টি</h3>
                            </div>
                            <i class="fa fa-clock-o text-success font-size-28"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter box -->
            <div class="box mb-3">
                <div class="box-body py-3">
                    <form action="{{ route('audit.dashboard') }}" method="GET" class="row g-2 align-items-center">
                        <div class="col-lg-3 col-md-6">
                            <input type="text" name="action" class="form-control" placeholder="অ্যাকশন (e.g. ROLE_UPDATED, USER_STATUS_CHANGED)..." value="{{ request('action') }}">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <input type="text" name="actor" class="form-control" placeholder="অ্যাক্টরের নাম, রোল বা আইপি..." value="{{ request('actor') }}">
                        </div>
                        <div class="col-lg-2 col-md-3 col-6">
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-lg-2 col-md-3 col-6">
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-lg-2 col-md-6 col-12 d-flex gap-2">
                            <button type="submit" class="btn btn-success w-100"><i class="fa fa-filter me-1"></i> ফিল্টার</button>
                            <a href="{{ route('audit.dashboard') }}" class="btn btn-outline btn-secondary"><i class="fa fa-refresh"></i></a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Audit Trail Table -->
            <div class="box">
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 font-size-13">
                            <thead class="bg-light">
                                <tr>
                                    <th>লগ আইডি</th>
                                    <th>সময় (UTC)</th>
                                    <th>অ্যাক্টর (Actor)</th>
                                    <th>অ্যাকশন (Action)</th>
                                    <th>টার্গেট সাবজেক্ট</th>
                                    <th>আইপি এড্রেস</th>
                                    <th>কারণ/বিবরণ</th>
                                    <th class="text-end">স্টেট ডিফ (Diff)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($auditLogs as $log)
                                <tr>
                                    <td><code>#{{ $log->id }}</code></td>
                                    <td>
                                        <div class="fw-bold">{{ $log->created_at->format('d M, Y h:i A') }}</div>
                                        <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark d-block">{{ $log->actor_name }}</span>
                                        <span class="badge bg-primary-light text-primary font-size-11">{{ $log->actor_role }}</span>
                                    </td>
                                    <td><span class="badge bg-info-light text-info fw-bold">{{ $log->action }}</span></td>
                                    <td>
                                        @if($log->subject_type)
                                            <span class="badge bg-light text-dark">{{ class_basename($log->subject_type) }} #{{ $log->subject_id }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td><code>{{ $log->ip_address }}</code></td>
                                    <td>{{ $log->reason ?? '—' }}</td>
                                    <td class="text-end">
                                        @if($log->before_state || $log->after_state)
                                        <button type="button" class="btn btn-xs btn-outline btn-primary" data-bs-toggle="modal" data-bs-target="#auditDiffModal{{ $log->id }}">
                                            <i class="fa fa-eye me-1"></i> Diff
                                        </button>
                                        @else
                                        <span class="text-muted font-size-11">—</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Diff Modal -->
                                @if($log->before_state || $log->after_state)
                                <div class="modal fade" id="auditDiffModal{{ $log->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark text-white">
                                                <h5 class="modal-title font-size-14"><i class="fa fa-code-fork me-2"></i> Audit State Diff (#{{ $log->id }})</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6 class="fw-bold text-danger"><i class="fa fa-minus-circle me-1"></i> Before State</h6>
                                                        <pre class="bg-light p-3 rounded font-size-12 max-h-300 overflow-auto">{{ $log->before_state ? json_encode(json_decode($log->before_state) ?? $log->before_state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : 'null' }}</pre>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6 class="fw-bold text-success"><i class="fa fa-plus-circle me-1"></i> After State</h6>
                                                        <pre class="bg-light p-3 rounded font-size-12 max-h-300 overflow-auto">{{ $log->after_state ? json_encode(json_decode($log->after_state) ?? $log->after_state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : 'null' }}</pre>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">কোন অডিট রেকর্ড নেই।</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($auditLogs->hasPages())
                <div class="box-footer clearfix">
                    {{ $auditLogs->links() }}
                </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
