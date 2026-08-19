@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title"><i class="ti-money me-2"></i>Withdraw Requests</h4>
                    <p class="text-muted mb-0">Approve or reject member withdrawal requests and manage frozen balance unlocks.</p>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><i class="ti-check me-1"></i>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-12">
                    <div class="box">
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover no-margin min-pad-table">
                                    <thead>
                                        <tr>
                                            <th>SN.</th>
                                            <th>Member Name</th>
                                            <th>Payment Method</th>
                                            <th>Receive Number</th>
                                            <th>Requested Amount</th>
                                            <th>Frozen Balance</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $all_withdraw = App\Models\Withdraw::with('user')->orderBy('id', 'desc')->get();
                                        @endphp
                                        @forelse ($all_withdraw as $key => $item)
                                            @php
                                                $method = WithdrawMethod($item->method_id);
                                                $user = $item->user ?? App\Models\User::find($item->user_id);
                                                $locked = (float)($user->locked_balance ?? 0.00);
                                            @endphp
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>
                                                    <strong>{{ $user->name ?? 'N/A' }}</strong>
                                                    <div class="small text-muted">{{ $user->phone ?? '' }}</div>
                                                </td>
                                                <td>{{ $method->pay_s_name ?? 'N/A' }}</td>
                                                <td><strong>{{ $method->pay_s_number ?? 'N/A' }}</strong></td>
                                                <td><strong class="text-primary">৳{{ number_format($item->amount, 2) }}</strong></td>
                                                <td>
                                                    @if($locked > 0)
                                                        <span class="badge bg-danger text-white">🔒 ৳{{ number_format($locked, 0) }} Locked</span>
                                                    @else
                                                        <span class="badge bg-success text-white">🔓 Unlocked</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item->status == "pending")
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                    @elseif ($item->status == "approved" || $item->status == "aproved")
                                                        <span class="badge bg-success">Approved</span>
                                                    @else
                                                        <span class="badge bg-danger">Rejected</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item->status == "pending")
                                                        <div class="d-flex gap-1">
                                                            <a href="{{ route('admin.all.withdraw.aprove', $item->id) }}"
                                                               onclick="return confirm('Are you sure you want to approve this withdraw request?')"
                                                               class="btn btn-success btn-xs">Approve</a>

                                                            <a href="{{ route('admin.all.withdraw.reject', $item->id) }}"
                                                               onclick="return confirm('Are you sure you want to reject this withdraw request?')"
                                                               class="btn btn-danger btn-xs">Reject</a>

                                                            @if($user && $locked > 0)
                                                                <form action="{{ route('admin.user.unlock_balance', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Unlock ৳{{ number_format($locked, 2) }} frozen balance for {{ $user->name }}?');">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-warning btn-xs font-weight-bold text-dark">
                                                                        <i class="ti-unlock me-1"></i> Unlock Fee
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="text-muted">Processed</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">No withdrawal requests found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
