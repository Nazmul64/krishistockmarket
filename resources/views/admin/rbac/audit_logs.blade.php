@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title text-primary"><i class="fa fa-history me-2"></i> System Audit Trail & Logs (অডিট লগ ও পরিবর্তন ট্র্যাকার)</h3>
                    <p class="text-muted mb-0">অপরিবর্তনীয় (Tamper-evident) প্রশাসনিক কার্যকলাপ ও প্রিভিলেজড অ্যাকশন লগ</p>
                </div>
                <div>
                    <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="btn btn-success">
                        <i class="fa fa-file-excel-o me-1"></i> এক্সপোর্ট অডিট লগ (CSV/Excel)
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Filter box -->
            <div class="box mb-3">
                <div class="box-body py-3">
                    <form action="{{ route('admin.rbac.audit_logs') }}" method="GET" class="row g-2 align-items-center">
                        <div class="col-lg-3 col-md-6">
                            <input type="text" name="action" class="form-control" placeholder="অ্যাকশন (e.g. USER_CREATED, ROLE_UPDATED)..." value="{{ request('action') }}">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <input type="text" name="actor" class="form-control" placeholder="অ্যাক্টরের নাম, রোল বা আইপি..." value="{{ request('actor') }}">
                        </div>
                        <div class="col-lg-2 col-md-3 col-6">
                            <input type="date" name="date_from" class="form-control" placeholder="শুরুর তারিখ" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-lg-2 col-md-3 col-6">
                            <input type="date" name="date_to" class="form-control" placeholder="শেষ তারিখ" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-lg-2 col-md-6 col-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="fa fa-filter me-1"></i> ফিল্টার</button>
                            <a href="{{ route('admin.rbac.audit_logs') }}" class="btn btn-outline btn-secondary" title="রিসেট"><i class="fa fa-refresh"></i></a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Audit Logs Table -->
            <div class="box">
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0 font-size-13">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 70px;">Log ID</th>
                                    <th>সময় (UTC / স্থানীয়)</th>
                                    <th>অ্যাক্টর (সম্পাদনকারী)</th>
                                    <th>অ্যাকশন (Action)</th>
                                    <th>টার্গেট সাবজেক্ট</th>
                                    <th>আইপি ও ডিভাইস</th>
                                    <th>কারণ / বিস্তারিত</th>
                                    <th class="text-end">ডিফ (Diff)</th>
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
                                    <td>
                                        <span class="badge bg-info-light text-info fw-bold py-1 px-2 font-size-12">{{ $log->action }}</span>
                                    </td>
                                    <td>
                                        @if($log->subject_type)
                                            <small class="text-muted d-block">{{ class_basename($log->subject_type) }}</small>
                                            <span class="badge bg-light text-dark">ID: #{{ $log->subject_id }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold"><code>{{ $log->ip_address }}</code></div>
                                        <small class="text-muted" title="{{ $log->user_agent }}">{{ Str::limit($log->user_agent, 25) }}</small>
                                    </td>
                                    <td>{{ $log->reason ?? '—' }}</td>
                                    <td class="text-end">
                                        @if($log->before_state || $log->after_state)
                                        <button type="button" class="btn btn-xs btn-outline btn-primary" data-bs-toggle="modal" data-bs-target="#diffModal{{ $log->id }}">
                                            <i class="fa fa-code me-1"></i> দেখুন
                                        </button>
                                        @else
                                        <span class="text-muted font-size-11">None</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Diff Modal -->
                                @if($log->before_state || $log->after_state)
                                <div class="modal fade" id="diffModal{{ $log->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark text-white">
                                                <h5 class="modal-title font-size-14"><i class="fa fa-code-fork me-2"></i> State Change Diff (#{{ $log->id }} - {{ $log->action }})</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6 class="fw-bold text-danger border-bottom pb-2"><i class="fa fa-minus-circle me-1"></i> পরিবর্তনের পূর্বে (Before State)</h6>
                                                        <pre class="bg-light p-3 rounded font-size-12 text-dark max-h-300 overflow-auto">{{ $log->before_state ? json_encode(json_decode($log->before_state) ?? $log->before_state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : 'null (New Record)' }}</pre>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6 class="fw-bold text-success border-bottom pb-2"><i class="fa fa-plus-circle me-1"></i> পরিবর্তনের পরে (After State)</h6>
                                                        <pre class="bg-light p-3 rounded font-size-12 text-dark max-h-300 overflow-auto">{{ $log->after_state ? json_encode(json_decode($log->after_state) ?? $log->after_state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : 'null' }}</pre>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <i class="fa fa-history font-size-36 text-muted mb-2 d-block"></i>
                                        কোন অডিট লগ পাওয়া যায়নি।
                                    </td>
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
