@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title">12-Digit Admin Number Generator</h4>
                    <p class="text-muted mb-0">Generate unique 12-digit numbers worth 300 TK for user registration.</p>
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

            <!-- Stats & Generation Card -->
            <div class="row">
                <div class="col-md-4 col-12">
                    <div class="box bg-primary">
                        <div class="box-body text-center">
                            <h3 class="mb-0 text-white">{{ $total_count }}</h3>
                            <span class="text-white">Total Numbers</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="box bg-success">
                        <div class="box-body text-center">
                            <h3 class="mb-0 text-white">{{ $available_count }}</h3>
                            <span class="text-white">Available (Unused)</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="box bg-warning">
                        <div class="box-body text-center">
                            <h3 class="mb-0 text-white">{{ $used_count }}</h3>
                            <span class="text-white">Used Numbers</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Generator Box -->
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Generate New 12-Digit Numbers</h4>
                </div>
                <div class="box-body">
                    <form action="{{ route('admin.card_numbers.store') }}" method="POST" class="row align-items-center g-3">
                        @csrf
                        <div class="col-md-4">
                            <label for="quantity" class="form-label">How many numbers to generate?</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="500" required>
                        </div>
                        <div class="col-md-4 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti-plus me-1"></i> Generate Numbers (300 TK each)
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Numbers List Table -->
            <div class="box">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title">Generated Numbers List</h4>
                    
                    <!-- Filter Form -->
                    <form action="{{ route('admin.card_numbers.index') }}" method="GET" class="d-flex gap-2">
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="used" {{ request('status') == 'used' ? 'selected' : '' }}>Used</option>
                        </select>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search number..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-sm btn-secondary">Search</button>
                    </form>
                </div>

                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover no-margin">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>12-Digit Code</th>
                                    <th>Value</th>
                                    <th>Status</th>
                                    <th>Used By (User)</th>
                                    <th>Used At</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($card_numbers as $key => $item)
                                    <tr>
                                        <td>{{ $card_numbers->firstItem() + $key }}</td>
                                        <td><strong class="text-primary" style="letter-spacing: 1px;">{{ $item->number }}</strong></td>
                                        <td>৳{{ number_format($item->amount, 2) }}</td>
                                        <td>
                                            @if ($item->is_used)
                                                <span class="badge bg-danger">Used</span>
                                            @else
                                                <span class="badge bg-success">Available</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->user)
                                                <strong>{{ $item->user->name ?? $item->user->username }}</strong> ({{ $item->user->phone }})
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->used_at ? \Carbon\Carbon::parse($item->used_at)->format('d M Y, h:i A') : 'N/A' }}</td>
                                        <td>{{ $item->created_at ? $item->created_at->format('d M Y, h:i A') : 'N/A' }}</td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-xs me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                                                Edit
                                            </button>
                                            <a href="{{ route('admin.card_numbers.destroy', $item->id) }}" 
                                               onclick="return confirm('Are you sure you want to delete this code?')" 
                                               class="btn btn-danger btn-xs">
                                                Delete
                                            </a>

                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title text-dark" id="editModalLabel{{ $item->id }}">Edit 12-Digit Number</h5>
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
                                        <td colspan="8" class="text-center py-4 text-muted">No 12-digit numbers generated yet.</td>
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
@endsection
