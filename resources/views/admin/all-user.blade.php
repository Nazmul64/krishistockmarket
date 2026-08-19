@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title"><i class="ti-user me-2"></i>Card Holders & User Management</h4>
                    <p class="text-muted mb-0">Manage registered members, golden/standard card balances, and frozen fee unlocks.</p>
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

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="ti-alert me-1"></i>Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="box">
                <div class="box-header with-border d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h4 class="box-title">Registered Card Holders List</h4>
                    
                    <!-- Search & Filter Form -->
                    <form action="{{ route('alluser') }}" method="GET" class="d-flex gap-2 flex-wrap">
                        <select name="lock_status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All Lock Status</option>
                            <option value="locked" {{ request('lock_status') == 'locked' ? 'selected' : '' }}>🔒 Locked Balance</option>
                            <option value="unlocked" {{ request('lock_status') == 'unlocked' ? 'selected' : '' }}>🔓 Unlocked Balance</option>
                        </select>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name/phone..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-sm btn-secondary">Search</button>
                    </form>
                </div>

                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover no-margin min-pad-table">
                            <thead>
                                <tr>
                                    <th>SN.</th>
                                    <th>Member Details</th>
                                    <th>Phone / Username</th>
                                    <th>Membership Type</th>
                                    <th>Total Balance</th>
                                    <th>Locked Balance</th>
                                    <th>Withdrawable Balance</th>
                                    <th>Status</th>
                                    @if (Auth::user()->role == "admin")
                                        <th class="text-center">Action / Freeze Control</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($all_user as $key => $item)
                                    @php
                                        $locked = (float)($item->locked_balance ?? 0.00);
                                        $total = (float)($item->balance ?? 0.00);
                                        $avail = max(0, $total - $locked);
                                    @endphp
                                    <tr>
                                        <td>{{ $all_user->firstItem() + $key }}</td>
                                        <td>
                                            <strong class="text-dark">{{ $item->name }}</strong>
                                        </td>
                                        <td>
                                            <div>{{ $item->phone }}</div>
                                            <div class="small text-muted">{{ $item->username }}</div>
                                        </td>
                                        <td>
                                            @if(($item->membership_card_type ?? '') === 'golden' || $locked >= 1000)
                                                <span class="badge bg-warning text-dark font-weight-bold">👑 Golden Card (৳1,000)</span>
                                            @else
                                                <span class="badge bg-info text-white">⚪ Standard Card (৳300)</span>
                                            @endif
                                        </td>
                                        <td><strong class="text-primary">৳{{ number_format($total, 2) }}</strong></td>
                                        <td>
                                            @if($locked > 0)
                                                <span class="text-danger font-weight-bold">৳{{ number_format($locked, 2) }}</span>
                                            @else
                                                <span class="text-muted">৳0.00</span>
                                            @endif
                                        </td>
                                        <td><strong class="text-success">৳{{ number_format($avail, 2) }}</strong></td>
                                        <td>
                                            @if($locked > 0)
                                                <span class="badge bg-danger text-white">🔒 Frozen / Locked</span>
                                            @else
                                                <span class="badge bg-success text-white">🔓 Unlocked</span>
                                            @endif
                                        </td>
                                        @if (Auth::user()->role == "admin")
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-1">
                                                    @if($locked > 0)
                                                        <form action="{{ route('admin.user.unlock_balance', $item->id) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে আপনি এই ইউজারের ৳{{ number_format($locked, 2) }} ফ্রিজকৃত ব্যালেন্স আনলক করতে চান?');">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-xs font-weight-bold">
                                                                <i class="ti-unlock me-1"></i> আনলক করুন (Unlock)
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('admin.user.lock_balance', $item->id) }}" method="POST" onsubmit="return confirm('এই ইউজারের ব্যালেন্স লক করতে চান?');">
                                                            @csrf
                                                            <input type="hidden" name="amount" value="{{ $item->membership_card_type === 'golden' ? 1000 : 300 }}">
                                                            <button type="submit" class="btn btn-warning btn-xs font-weight-bold text-dark">
                                                                <i class="ti-lock me-1"></i> লক করুন (Lock)
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <a href="{{ route('admin.user.destroy', $item->id) }}" 
                                                       onclick="return confirm('Are you sure you want to delete this user?')" 
                                                       class="btn btn-danger btn-xs ms-1">
                                                        Delete
                                                    </a>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">No card holders found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($all_user->hasPages())
                    <div class="box-footer">
                        {{ $all_user->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>

        </section>
    </div>
</div>
@endsection
