@extends('layouts.backend.app')

@section('content')
@php
    $totalInvested = $totalInvested ?? 0;
    $totalReturns = $totalReturns ?? 0;
    $paidDividends = $paidDividends ?? 0;
    $pendingDividends = $pendingDividends ?? 0;
    $withdrawals = $withdrawals ?? collect([]);
@endphp
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content py-3">

            <!-- Title & Top Action -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-line-chart text-success me-2"></i> প্রপার্টি কার্ড ও বিনিয়োগ ব্যবস্থাপনা</h4>
                    <p class="text-muted small mb-0">আপনার বিনিয়োগকৃত মূলধন, প্রকল্প অগ্রগতি, লভ্যাংশ ও ফান্ড উত্তোলন ড্যাশবোর্ড।</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#withdrawModal">
                        <i class="fa fa-money me-1"></i> ফান্ড উত্তোলনের আবেদন
                    </button>
                    <a href="{{ route('investment.sectors') }}" class="btn btn-success btn-sm rounded-pill px-4 fw-bold shadow-sm">
                        <i class="fa fa-plus me-1"></i> নতুন বিনিয়োগ করুন
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- 4 Counter KPI Cards -->
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small d-block">মোট বিনিয়োগ (Capital)</span>
                                <h4 class="fw-bold text-dark mt-1 mb-0">{{ number_format($totalInvested, 2) }} ৳</h4>
                            </div>
                            <span class="badge bg-primary-subtle text-primary rounded-circle p-3"><i class="fa fa-briefcase fa-lg"></i></span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small d-block">মোট অর্জিত লভ্যাংশ</span>
                                <h4 class="fw-bold text-success mt-1 mb-0">{{ number_format($totalReturns, 2) }} ৳</h4>
                            </div>
                            <span class="badge bg-success-subtle text-success rounded-circle p-3"><i class="fa fa-arrow-up fa-lg"></i></span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small d-block">পরিশোধিত লভ্যাংশ</span>
                                <h4 class="fw-bold text-info mt-1 mb-0">{{ number_format($paidDividends, 2) }} ৳</h4>
                            </div>
                            <span class="badge bg-info-subtle text-info rounded-circle p-3"><i class="fa fa-check-circle fa-lg"></i></span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small d-block">প্রদেয় / পেন্ডিং লভ্যাংশ</span>
                                <h4 class="fw-bold text-warning mt-1 mb-0">{{ number_format($pendingDividends, 2) }} ৳</h4>
                            </div>
                            <span class="badge bg-warning-subtle text-warning rounded-circle p-3"><i class="fa fa-clock-o fa-lg"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Investments List -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="fa fa-folder-open text-warning me-2"></i> আমার বিনিয়োগসমূহ (Capital & Investment Overview)</h5>
                    <span class="badge bg-light text-muted">মোট {{ $investments->count() }}টি বিনিয়োগ</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>বিনিয়োগ কোড</th>
                                <th>প্রকল্প ও খাত</th>
                                <th>বিনিয়োগকৃত মূলধন</th>
                                <th>মোট লভ্যাংশ</th>
                                <th>বিনিয়োগের তারিখ</th>
                                <th>মেয়াদপূর্তি</th>
                                <th>স্ট্যাটাস</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($investments as $inv)
                            <tr>
                                <td class="fw-bold font-monospace text-primary">{{ $inv->investment_code }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $inv->project->project_name ?? 'সাধারণ বিনিয়োগ' }}</div>
                                    <small class="text-muted">{{ $inv->sector->name_bn ?? '' }}</small>
                                </td>
                                <td class="fw-bold text-dark font-size-14">{{ number_format($inv->amount, 2) }} ৳</td>
                                <td>
                                    <span class="text-success fw-bold d-block">{{ number_format($inv->return_earned, 2) }} ৳</span>
                                    <small class="text-muted">পেইড: {{ number_format($inv->paid_dividend, 2) }} ৳</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($inv->investment_date)->format('d/m/Y') }}</td>
                                <td>{{ $inv->maturity_date ? \Carbon\Carbon::parse($inv->maturity_date)->format('d/m/Y') : 'নির্ধারিত' }}</td>
                                <td>
                                    <span class="badge {{ match($inv->status) {
                                        'active' => 'bg-success',
                                        'completed' => 'bg-info',
                                        'rejected' => 'bg-danger',
                                        default => 'bg-warning text-dark',
                                    } }} px-2 py-1">
                                        {{ match($inv->status) {
                                            'active' => 'চলমান',
                                            'completed' => 'সম্পন্ন',
                                            'rejected' => 'বাতিল',
                                            default => 'অপেক্ষমাণ',
                                        } }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">আপনার কোনো সক্রিয় বিনিয়োগ পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Fund Withdrawal History -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="fa fa-history text-primary me-2"></i> উত্তোলন ইতিহাস (Withdrawal Management)</h5>
                    <button class="btn btn-outline-primary btn-xs rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#withdrawModal">
                        নতুন আবেদন
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>উত্তোলন কোড</th>
                                <th>উত্তোলনের ধরন</th>
                                <th>পরিমাণ</th>
                                <th>পেমেন্ট মেথড</th>
                                <th>অ্যাকাউন্ট নং</th>
                                <th>আবেদনের তারিখ</th>
                                <th>স্ট্যাটাস</th>
                                <th>রেফারেন্স</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($withdrawals as $w)
                            <tr>
                                <td class="fw-bold font-monospace">{{ $w->withdrawal_code }}</td>
                                <td>
                                    <span class="badge bg-light text-dark text-capitalize">{{ $w->withdrawal_type }}</span>
                                </td>
                                <td class="fw-bold text-danger">{{ number_format($w->amount, 2) }} ৳</td>
                                <td class="text-uppercase">{{ $w->payment_method }}</td>
                                <td>{{ $w->account_number }}</td>
                                <td>{{ \Carbon\Carbon::parse($w->created_at)->format('d/m/Y h:i A') }}</td>
                                <td>
                                    <span class="badge {{ match($w->status) {
                                        'approved' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                        default => 'bg-warning text-dark',
                                    } }} px-2 py-1">
                                        {{ match($w->status) {
                                            'approved' => 'অনুমোদিত ও পরিশোধিত',
                                            'rejected' => 'বাতিল',
                                            default => 'প্রক্রিয়াধীন',
                                        } }}
                                    </span>
                                </td>
                                <td class="text-muted font-monospace">{{ $w->transaction_reference ?: '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">কোনো ফান্ড উত্তোলনের রেকর্ড পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
    </div>
</div>

<!-- Fund Withdrawal Modal -->
<div class="modal fade" id="withdrawModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">ফান্ড উত্তোলনের আবেদন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.corporate.investments.withdraw') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">উত্তোলনের খাত *</label>
                        <select name="withdrawal_type" class="form-select" required>
                            <option value="profit">অর্জিত লভ্যাংশ উত্তোলন (Profit/Dividend)</option>
                            <option value="capital">মূলধন ফেরত আবেদন (Capital Refund)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">উত্তোলনের পরিমাণ (টাকা) *</label>
                        <input type="number" name="amount" class="form-control" min="500" placeholder="সর্বনিম্ন ৫০০ টাকা" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">পেমেন্ট মেথড *</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="bkash">বিকাশ (bKash)</option>
                            <option value="nagad">নগদ (Nagad)</option>
                            <option value="rocket">রকেট (Rocket)</option>
                            <option value="bank">ব্যাংক একাউন্ট (Bank Transfer)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">একাউন্ট নম্বর / ব্যাংক বিবরণী *</label>
                        <textarea name="account_number" class="form-control" rows="2" placeholder="মোবাইল ব্যাংকিং নম্বর অথবা ব্যাংকের নাম, শাখা ও একাউন্ট নম্বর লিখুন..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">উত্তোলনের কারণ *</label>
                        <input type="text" name="reason" class="form-control" placeholder="যেমন: নিয়মিত মাসিক লাভ উত্তোলন" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">আবেদন জমা দিন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
