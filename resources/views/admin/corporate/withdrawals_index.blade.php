@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content py-3">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-credit-card text-danger me-2"></i> বিনিয়োগ ও মূলধন উত্তোলন অনুমোদন (Withdrawals)</h4>
                    <p class="text-muted small mb-0">গ্রাহকদের ফান্ড ও লভ্যাংশ উত্তোলনের আবেদন যাচাই, অনুমোদন ও পেমেন্ট ট্র্যাকিং।</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>উত্তোলন কোড</th>
                                <th>গ্রাহকের নাম ও ফোন</th>
                                <th>উত্তোলনের খাত</th>
                                <th>পরিমাণ (টাকা)</th>
                                <th>পেমেন্ট মেথড ও একাউন্ট</th>
                                <th>আবেদনের তারিখ</th>
                                <th>স্ট্যাটাস</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($withdrawals as $w)
                            <tr>
                                <td class="fw-bold font-monospace">{{ $w->withdrawal_code }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $w->user->name ?? 'গ্রাহক' }}</div>
                                    <small class="text-muted">{{ $w->user->phone ?? '' }}</small>
                                </td>
                                <td><span class="badge bg-light text-dark text-capitalize">{{ $w->withdrawal_type }}</span></td>
                                <td class="fw-bold text-danger font-size-14">{{ number_format($w->amount, 2) }} ৳</td>
                                <td>
                                    <div class="text-uppercase fw-semibold">{{ $w->payment_method }}</div>
                                    <small class="text-muted">{{ $w->account_number }}</small>
                                </td>
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
                                            default => 'অপেক্ষমাণ',
                                        } }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    @if($w->status === 'pending')
                                        <button class="btn btn-success btn-xs rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#approveModal{{ $w->id }}">
                                            অনুমোদন
                                        </button>
                                        <button class="btn btn-outline-danger btn-xs rounded-pill px-2" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $w->id }}">
                                            বাতিল
                                        </button>
                                    @else
                                        <span class="text-muted font-monospace small">{{ $w->transaction_reference ?: 'সম্পন্ন' }}</span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Approve Modal -->
                            <div class="modal fade" id="approveModal{{ $w->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold text-dark">উত্তোলন অনুমোদন ও পেমেন্ট রসিদ</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.corporate.withdrawals.status', $w->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="approved">
                                            <div class="modal-body">
                                                <p class="small text-muted mb-3">আপনি কি <strong>{{ $w->user->name ?? '' }}</strong> এর <strong>{{ number_format($w->amount) }} টাকা</strong> উত্তোলন অনুমোদন করতে চান?</p>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold text-muted">পেমেন্ট ট্রানজাকশন রেফারেন্স / ভাউচার নং *</label>
                                                    <input type="text" name="transaction_reference" class="form-control" placeholder="যেমন: BANK-TRX-102934" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold text-muted">প্রশাসনিক মন্তব্য</label>
                                                    <input type="text" name="admin_remarks" class="form-control" value="পেমেন্ট সফলভাবে সম্পন্ন হয়েছে।">
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">বাতিল</button>
                                                <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">অনুমোদন নিশ্চিত করুন</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal{{ $w->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold text-dark">আবেদন বাতিল</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.corporate.withdrawals.status', $w->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold text-muted">বাতিলের কারণ লিখুন *</label>
                                                    <input type="text" name="admin_remarks" class="form-control" placeholder="যেমন: একাউন্ট নম্বর ভুল" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">ফিরে যান</button>
                                                <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">বাতিল করুন</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">কোনো উত্তোলনের রেকর্ড পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $withdrawals->links() }}
                </div>
            </div>

        </section>
    </div>
</div>
@endsection

