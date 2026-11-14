@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-money text-success me-2"></i> গ্রাহক বিনিয়োগ ও লভ্যাংশ ব্যবস্থাপনা</h4>
                    <p class="text-muted small mb-0">বিনিয়োগ অনুমোদন, লভ্যাংশ ঘোষণা, মুনাফা বণ্টন ও প্রজেক্ট ক্যাপিটাল মনিটরিং।</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- 3 Counters -->
            <div class="row g-3 mb-4">
                <div class="col-sm-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <span class="text-muted small d-block">মোট সক্রিয় বিনিয়োগ (Active Capital)</span>
                        <h4 class="fw-bold text-primary mt-1 mb-0">{{ number_format($totalInvested, 2) }} ৳</h4>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <span class="text-muted small d-block">পরিশোধিত লভ্যাংশ (Paid Dividends)</span>
                        <h4 class="fw-bold text-success mt-1 mb-0">{{ number_format($totalDividendsPaid, 2) }} ৳</h4>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <span class="text-muted small d-block">অপেক্ষমাণ আবেদন (Pending)</span>
                        <h4 class="fw-bold text-warning mt-1 mb-0">{{ $pendingCount ?? 0 }} টি</h4>
                    </div>
                </div>
            </div>

            <!-- Investments Table -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>বিনিয়োগ কোড</th>
                                <th>বিনিয়োগকারীর নাম ও ফোন</th>
                                <th>প্রকল্প ও খাত</th>
                                <th>বিনিয়োগের পরিমাণ</th>
                                <th>লভ্যাংশ হিসাব</th>
                                <th>তারিখ ও TrxID</th>
                                <th>স্ট্যাটাস</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($investments as $inv)
                            <tr>
                                <td class="fw-bold font-monospace text-primary">{{ $inv->investment_code }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $inv->user->name ?? 'গ্রাহক' }}</div>
                                    <small class="text-muted">{{ $inv->user->phone ?? '-' }}</small>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $inv->project->project_name ?? 'সাধারণ' }}</div>
                                    <small class="text-muted">{{ $inv->sector->name_bn ?? '' }}</small>
                                </td>
                                <td class="fw-bold text-dark font-size-14">{{ number_format($inv->amount, 2) }} ৳</td>
                                <td>
                                    <span class="text-success fw-bold d-block">অর্জিত: {{ number_format($inv->return_earned, 2) }} ৳</span>
                                    <small class="text-muted">পেইড: {{ number_format($inv->paid_dividend, 2) }} ৳</small>
                                </td>
                                <td>
                                    <div>{{ \Carbon\Carbon::parse($inv->investment_date)->format('d/m/Y') }}</div>
                                    <small class="text-muted font-monospace">{{ $inv->transaction_id ?: $inv->payment_method }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ match($inv->status) {
                                        'active' => 'bg-success',
                                        'completed' => 'bg-info',
                                        'rejected' => 'bg-danger',
                                        default => 'bg-warning text-dark',
                                    } }} px-2 py-1">
                                        {{ match($inv->status) {
                                            'active' => 'অনুমোদিত / সক্রিয়',
                                            'completed' => 'সম্পন্ন',
                                            'rejected' => 'বাতিল',
                                            default => 'অপেক্ষমাণ',
                                        } }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-outline-secondary btn-xs rounded-pill px-3 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            ব্যবস্থাপনা
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 small">
                                            @if($inv->status === 'pending')
                                                <li>
                                                    <form action="{{ route('admin.corporate.investments.status', $inv->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="active">
                                                        <button type="submit" class="dropdown-item text-success"><i class="fa fa-check me-2"></i> বিনিয়োগ অনুমোদন করুন</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.corporate.investments.status', $inv->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" class="dropdown-item text-danger"><i class="fa fa-times me-2"></i> বাতিল করুন</button>
                                                    </form>
                                                </li>
                                            @endif
                                            @if($inv->status === 'active')
                                                <li>
                                                    <a class="dropdown-item text-primary" href="#" data-bs-toggle="modal" data-bs-target="#dividendModal{{ $inv->id }}">
                                                        <i class="fa fa-plus-circle me-2"></i> লভ্যাংশ ঘোষণা করুন
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            <!-- Dividend Declaration Modal -->
                            <div class="modal fade" id="dividendModal{{ $inv->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold text-dark">লভ্যাংশ ঘোষণা: {{ $inv->investment_code }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.corporate.investments.dividend', $inv->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold text-muted">বিনিয়োগকারীর নাম:</label>
                                                    <div class="fw-bold">{{ $inv->user->name ?? '' }} (মূলধন: {{ number_format($inv->amount) }} ৳)</div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold text-muted">ঘোষিত লভ্যাংশের পরিমাণ (টাকা) *</label>
                                                    <input type="number" name="amount" class="form-control" placeholder="যেমন: ৫০০০" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold text-muted">মন্তব্য / বিবরণ</label>
                                                    <input type="text" name="remarks" class="form-control" value="ত্রৈমাসিক ফসল বিক্রয় আয় বণ্টন">
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">বাতিল</button>
                                                <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">ঘোষণা নিশ্চিত করুন</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">কোনো বিনিয়োগের রেকর্ড পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $investments->links() }}
                </div>
            </div>

        </section>
    </div>
</div>
@endsection
