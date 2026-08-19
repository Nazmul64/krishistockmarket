@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title"><i class="ti-credit-card me-2"></i>12-Digit Membership Card Generator</h4>
                    <p class="text-muted mb-0">Generate Standard (৳300) & Golden (৳1,000) membership card numbers for user registration.</p>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="box bg-primary">
                        <div class="box-body text-center py-3">
                            <h3 class="mb-0 text-white font-weight-bold">{{ $total_count }}</h3>
                            <span class="text-white">Total Cards</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="box" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <div class="box-body text-center py-3">
                            <h3 class="mb-0 text-white font-weight-bold">👑 {{ $golden_available_count }}</h3>
                            <span class="text-white">Golden Available (৳1,000)</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="box bg-success">
                        <div class="box-body text-center py-3">
                            <h3 class="mb-0 text-white font-weight-bold">{{ $standard_available_count }}</h3>
                            <span class="text-white">Standard Available (৳300)</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="box bg-secondary">
                        <div class="box-body text-center py-3">
                            <h3 class="mb-0 text-white font-weight-bold">{{ $used_count }}</h3>
                            <span class="text-white">Used Cards</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Generator Box -->
            <div class="box border-top border-4 border-warning">
                <div class="box-header with-border">
                    <h4 class="box-title"><i class="ti-plus text-warning me-2"></i>Generate New Membership Cards</h4>
                </div>
                <div class="box-body">
                    <form action="{{ route('admin.card_numbers.store') }}" method="POST">
                        @csrf
                        <div class="row align-items-end g-3">
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">Select Card Type / Value</label>
                                <div class="d-flex gap-3 mt-1">
                                    <div class="form-check custom-radio">
                                        <input class="form-check-input" type="radio" name="card_type" id="card_type_standard" value="standard" checked onchange="toggleCustomAmount(false)">
                                        <label class="form-check-input-label font-weight-600" for="card_type_standard">
                                            ⚪ Standard Card (৳300)
                                        </label>
                                    </div>
                                    <div class="form-check custom-radio">
                                        <input class="form-check-input" type="radio" name="card_type" id="card_type_golden" value="golden" onchange="toggleCustomAmount(false)">
                                        <label class="form-check-input-label text-warning font-weight-bold" for="card_type_golden">
                                            👑 Golden Card (৳1,000)
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="quantity" class="form-label font-weight-bold">Quantity (How many?)</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="500" required>
                            </div>

                            <div class="col-md-3" id="custom_amount_container" style="display: none;">
                                <label for="amount" class="form-label font-weight-bold">Custom Value (TK)</label>
                                <input type="number" step="0.01" name="amount" id="amount" class="form-control" placeholder="e.g. 1000">
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-warning w-100 font-weight-bold text-dark">
                                    <i class="ti-plus me-1"></i> Generate
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Numbers List Table -->
            <div class="box">
                <div class="box-header with-border d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h4 class="box-title">Generated Membership Cards</h4>
                    
                    <!-- Filter Form -->
                    <form action="{{ route('admin.card_numbers.index') }}" method="GET" class="d-flex gap-2 flex-wrap">
                        <select name="card_type" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All Card Types</option>
                            <option value="golden" {{ request('card_type') == 'golden' ? 'selected' : '' }}>👑 Golden (৳1,000)</option>
                            <option value="standard" {{ request('card_type') == 'standard' ? 'selected' : '' }}>⚪ Standard (৳300)</option>
                        </select>
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="used" {{ request('status') == 'used' ? 'selected' : '' }}>Used</option>
                        </select>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search code/user/phone..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-sm btn-secondary">Search</button>
                    </form>
                </div>

                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover no-margin">
                            <thead>
                                <tr>
                                    <th>SN.</th>
                                    <th>12-Digit Code</th>
                                    <th>Card Type</th>
                                    <th>Value</th>
                                    <th>Status</th>
                                    <th>Used By (User)</th>
                                    <th>Used At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($card_numbers as $key => $item)
                                    <tr>
                                        <td>{{ $card_numbers->firstItem() + $key }}</td>
                                        <td><strong class="text-primary" style="letter-spacing: 1px;">{{ $item->number }}</strong></td>
                                        <td>
                                            @if(($item->card_type ?? '') === 'golden' || $item->amount >= 1000)
                                                <span class="badge bg-warning text-dark font-weight-bold">👑 Golden Card</span>
                                            @else
                                                <span class="badge bg-info text-white">⚪ Standard Card</span>
                                            @endif
                                        </td>
                                        <td><strong>৳{{ number_format($item->amount, 2) }}</strong></td>
                                        <td>
                                            @if ($item->is_used)
                                                <span class="badge bg-danger">Used</span>
                                            @else
                                                <span class="badge bg-success">Available</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->user)
                                                <div>
                                                    <strong>{{ $item->user->name ?? $item->user->username }}</strong>
                                                    <div class="small text-muted">{{ $item->user->phone }}</div>
                                                    @if(($item->user->locked_balance ?? 0) > 0)
                                                        <span class="badge bg-danger text-white py-1 mt-1">🔒 Locked: ৳{{ number_format($item->user->locked_balance, 0) }}</span>
                                                    @else
                                                        <span class="badge bg-success text-white py-1 mt-1">🔓 Unlocked</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->used_at ? \Carbon\Carbon::parse($item->used_at)->format('d M Y, h:i A') : 'N/A' }}</td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-xs me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                                                Edit
                                            </button>
                                            <a href="{{ route('admin.card_numbers.destroy', $item->id) }}" 
                                               onclick="return confirm('Are you sure you want to delete this card code?')" 
                                               class="btn btn-danger btn-xs">
                                                Delete
                                            </a>

                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title text-dark" id="editModalLabel{{ $item->id }}">Edit Card Number</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('admin.card_numbers.update', $item->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body text-start text-dark">
                                                                <div class="mb-3">
                                                                    <label for="number{{ $item->id }}" class="form-label">12-Digit Code</label>
                                                                    <input type="text" name="number" id="number{{ $item->id }}" class="form-control" maxlength="12" value="{{ $item->number }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="card_type{{ $item->id }}" class="form-label">Card Type</label>
                                                                    <select name="card_type" id="card_type{{ $item->id }}" class="form-select">
                                                                        <option value="standard" {{ $item->card_type == 'standard' ? 'selected' : '' }}>⚪ Standard (৳300)</option>
                                                                        <option value="golden" {{ $item->card_type == 'golden' ? 'selected' : '' }}>👑 Golden (৳1,000)</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="amount{{ $item->id }}" class="form-label">Value (TK)</label>
                                                                    <input type="number" step="0.01" name="amount" id="amount{{ $item->id }}" class="form-control" value="{{ $item->amount }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">No 12-digit membership cards generated yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($card_numbers->hasPages())
                    <div class="box-footer">
                        {{ $card_numbers->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>

        </section>
    </div>
</div>

<script>
function toggleCustomAmount(show) {
    var container = document.getElementById('custom_amount_container');
    if (container) {
        container.style.display = show ? 'block' : 'none';
    }
}
</script>
@endsection
