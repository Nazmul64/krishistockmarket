@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-lock text-danger me-2"></i>Agent Point Daily Closings & Verification
                    </h3>
                    <p class="text-muted mb-0">কেন্দ্রীয় অ্যাডমিন প্যানেল: সকল এজেন্ট পয়েন্টের দিনশেষে দাখিলকৃত দৈনিক রেজিস্টার, হিসাব ক্লোজিং ও অডিট অনুমোদন</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.distribution.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ডে ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Filter Bar -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('admin.distribution.daily_closings') }}" class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label font-size-12 fw-semibold mb-1">এজেন্ট পয়েন্ট নির্বাচন করুন:</label>
                            <select name="agent_point_id" class="form-select form-select-sm">
                                <option value="">সকল পয়েন্ট (All Points)</option>
                                @foreach($agentPoints as $pt)
                                    <option value="{{ $pt->id }}" {{ request('agent_point_id') == $pt->id ? 'selected' : '' }}>{{ $pt->name }} ({{ $pt->point_code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label font-size-12 fw-semibold mb-1">ক্লোজিং তারিখ:</label>
                            <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="fa fa-filter me-1"></i> ফিল্টার প্রয়োগ
                            </button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.distribution.daily_closings') }}" class="btn btn-light btn-sm border w-100">
                                <i class="fa fa-refresh me-1"></i> রিসেট
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Closings Table -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="fa fa-history text-primary me-2"></i>দাখিলকৃত দৈনিক ক্লোজিং তালিকা
                    </h5>
                    <span class="badge bg-light text-dark border">মোট রেকর্ড: {{ $closings->total() }} টি</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 font-size-13">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">ক্লোজিং তারিখ</th>
                                    <th>এজেন্ট পয়েন্ট ও কোড</th>
                                    <th>দাখিলকারী এজেন্ট</th>
                                    <th class="text-center">বিতরণকৃত গ্রাহক</th>
                                    <th class="text-end">বিতরণ ভলিউম</th>
                                    <th class="text-end">আদায়কৃত অর্থ</th>
                                    <th class="text-center">ফিজিক্যাল স্টক যাচাই</th>
                                    <th class="text-center">স্ট্যাটাস</th>
                                    <th class="text-center pe-3">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($closings as $closing)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bold text-dark">{{ date('d M, Y', strtotime($closing->closing_date)) }}</div>
                                            <small class="text-muted">{{ $closing->created_at->format('h:i A') }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $closing->agentPoint->name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $closing->agentPoint->point_code ?? '' }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $closing->agentUser->name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $closing->agentUser->phone ?? '' }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-light text-info fw-bold font-size-12 px-2 py-1">
                                                {{ number_format($closing->total_customers_served) }} জন
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold text-success font-size-14">
                                            {{ number_format($closing->total_quantity_distributed, 1) }}
                                        </td>
                                        <td class="text-end fw-bold text-primary font-size-14">
                                            ৳{{ number_format($closing->total_cash_collected, 2) }}
                                        </td>
                                        <td class="text-center">
                                            @if($closing->is_physical_stock_verified)
                                                <span class="badge bg-success-light text-success fw-bold font-size-11">
                                                    <i class="fa fa-check me-1"></i>যাচাইকৃত (Matched)
                                                </span>
                                            @else
                                                <span class="badge bg-danger-light text-danger fw-bold font-size-11">
                                                    <i class="fa fa-exclamation-triangle me-1"></i>অমিল পাওয়া গেছে
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($closing->status == 'verified')
                                                <span class="badge bg-success text-white fw-bold font-size-11 px-2 py-1">
                                                    <i class="fa fa-check-circle me-1"></i>ভেরিফাইড (Approved)
                                                </span>
                                                @if($closing->verifier)
                                                    <div class="text-muted font-size-11 mt-1">কর্তৃক: {{ $closing->verifier->name }}</div>
                                                @endif
                                            @else
                                                <span class="badge bg-warning text-dark fw-bold font-size-11 px-2 py-1">
                                                    <i class="fa fa-clock-o me-1"></i>অপেক্ষমাণ (Submitted)
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center pe-3">
                                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#verifyModal{{ $closing->id }}">
                                                <i class="fa fa-eye me-1"></i> বিবরণ ও ভেরিফিকেশন
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade text-start" id="verifyModal{{ $closing->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow">
                                                        <form method="POST" action="{{ route('admin.distribution.verify_daily_closing', $closing->id) }}">
                                                            @csrf
                                                            <div class="modal-header bg-light">
                                                                <h5 class="modal-title fw-bold text-dark">
                                                                    <i class="fa fa-shield text-primary me-2"></i>দৈনিক ক্লোজিং অডিট ও অনুমোদন
                                                                </h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body p-4">
                                                                <div class="mb-3 p-3 bg-light rounded-3">
                                                                    <div class="d-flex justify-content-between mb-1">
                                                                        <span class="text-muted">এজেন্ট পয়েন্ট:</span>
                                                                        <span class="fw-bold text-dark">{{ $closing->agentPoint->name }}</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mb-1">
                                                                        <span class="text-muted">তারিখ:</span>
                                                                        <span class="fw-bold text-dark">{{ date('d M, Y', strtotime($closing->closing_date)) }}</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mb-1">
                                                                        <span class="text-muted">গ্রাহক সেবা প্রদান:</span>
                                                                        <span class="fw-bold text-info">{{ $closing->total_customers_served }} জন</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between mb-1">
                                                                        <span class="text-muted">মোট বিতরণ পরিমাণ:</span>
                                                                        <span class="fw-bold text-success">{{ number_format($closing->total_quantity_distributed, 1) }}</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span class="text-muted">আদায়কৃত নগদ অর্থ:</span>
                                                                        <span class="fw-bold text-primary font-size-15">৳{{ number_format($closing->total_cash_collected, 2) }}</span>
                                                                    </div>
                                                                </div>

                                                                @if($closing->discrepancy_note)
                                                                    <div class="alert alert-warning py-2 mb-3">
                                                                        <div class="fw-bold font-size-12"><i class="fa fa-info-circle me-1"></i>এজেন্ট কর্তৃক স্টক অমিল নোট:</div>
                                                                        <div class="font-size-13">{{ $closing->discrepancy_note }}</div>
                                                                    </div>
                                                                @endif

                                                                @if($closing->agent_remarks)
                                                                    <div class="mb-3">
                                                                        <label class="font-size-12 text-muted fw-semibold mb-1">এজেন্ট মন্তব্য:</label>
                                                                        <p class="font-size-13 text-dark bg-white p-2 border rounded mb-0">{{ $closing->agent_remarks }}</p>
                                                                    </div>
                                                                @endif

                                                                <div class="mb-3">
                                                                    <label class="form-label font-size-12 fw-semibold text-dark">অ্যাডমিন নোট / অনুমোদন মন্তব্য:</label>
                                                                    <textarea name="admin_note" class="form-control font-size-13" rows="3" placeholder="ক্লোজিং অনুমোদনের মন্তব্য বা অডিট নোট লিখুন..." {{ $closing->status == 'verified' ? 'readonly' : '' }}>{{ $closing->admin_note }}</textarea>
                                                                </div>

                                                                @if($closing->status == 'verified')
                                                                    <div class="alert alert-success py-2 mb-0 font-size-13">
                                                                        <i class="fa fa-check-circle me-1"></i>এই ক্লোজিংটি <strong>{{ $closing->verifier->name ?? 'Admin' }}</strong> কর্তৃক {{ $closing->verified_at ? date('d M, Y h:i A', strtotime($closing->verified_at)) : '' }} তারিখে অনুমোদিত হয়েছে।
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer bg-light">
                                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">বন্ধ করুন</button>
                                                                @if($closing->status != 'verified')
                                                                    <button type="submit" class="btn btn-success btn-sm">
                                                                        <i class="fa fa-check me-1"></i> ক্লোজিং অনুমোদন (Verify) করুন
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">
                                            কোনো পয়েন্টের দৈনিক ক্লোজিং রেকর্ড পাওয়া যায়নি।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($closings->hasPages())
                        <div class="p-3 border-top d-flex justify-content-end">
                            {{ $closings->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
