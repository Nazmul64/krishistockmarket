@extends('layouts.backend.app')

@section('title', 'লোন & স্যালারি এডভান্স')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="font-weight-bold mb-0" style="color: #2e7d32;">
                        <i class="ti-hand-point-right mr-2"></i> লোন & স্যালারি এডভান্স (Loans & Advance)
                    </h3>
                    <p class="text-muted mb-0">কর্মীদের পার্সোনাল লোন বরাদ্দ ও মাসিক স্যালারি অগ্রিম হিসাব</p>
                </div>
                <div>
                    <button type="button" class="btn btn-primary btn-sm rounded-pill shadow-sm mr-2" data-bs-toggle="modal" data-bs-target="#addLoanModal" data-toggle="modal" data-target="#addLoanModal">
                        <i class="ti-plus mr-1"></i> নতুন লোন মঞ্জুর করুন
                    </button>
                    <button type="button" class="btn btn-success btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addAdvanceModal" data-toggle="modal" data-target="#addAdvanceModal">
                        <i class="ti-plus mr-1"></i> স্যালারি এডভান্স মঞ্জুর করুন
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="ti-check-box mr-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" data-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Loans List -->
        <div class="col-md-7 grid-margin stretch-card">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-body">
                    <h5 class="font-weight-bold text-dark mb-3"><i class="ti-credit-card text-primary mr-2"></i> চলমান লোন তালিকা</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>কর্মী</th>
                                    <th>মোট লোন</th>
                                    <th>মাসিক কিস্তি</th>
                                    <th>অবশিষ্ট</th>
                                    <th>স্ট্যাটাস</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loans as $loan)
                                <tr>
                                    <td class="font-weight-bold text-dark">{{ $loan->user->name ?? 'N/A' }}</td>
                                    <td>৳{{ number_format($loan->loan_amount) }}</td>
                                    <td><span class="text-danger font-weight-bold">৳{{ number_format($loan->monthly_deduction) }}</span></td>
                                    <td>৳{{ number_format($loan->remaining_amount) }}</td>
                                    <td><span class="badge badge-success">{{ ucfirst($loan->status) }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">কোনো লোন রেকর্ড পাওয়া যায়নি।</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advance List -->
        <div class="col-md-5 grid-margin stretch-card">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-body">
                    <h5 class="font-weight-bold text-dark mb-3"><i class="ti-wallet text-success mr-2"></i> স্যালারি এডভান্স তালিকা</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>কর্মী</th>
                                    <th>পরিমাণ</th>
                                    <th>কর্তনের মাস</th>
                                    <th>স্ট্যাটাস</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($advances as $adv)
                                <tr>
                                    <td class="font-weight-bold text-dark">{{ $adv->user->name ?? 'N/A' }}</td>
                                    <td><span class="text-success font-weight-bold">৳{{ number_format($adv->amount) }}</span></td>
                                    <td>{{ $adv->deduction_month }}</td>
                                    <td><span class="badge badge-info">{{ ucfirst($adv->status) }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">কোনো এডভান্স রেকর্ড পাওয়া যায়নি।</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loan Modal -->
<div class="modal fade" id="addLoanModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <form action="{{ route('admin.hrm.loans.store_loan') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-header-title text-white mb-0"><i class="ti-plus mr-2"></i> নতুন লোন মঞ্জুর করুন</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">কর্মী নির্বাচন করুন <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-control" required>
                            <option value="">-- নির্বাচন করুন --</option>
                            @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">মোট লোনের পরিমাণ (৳) <span class="text-danger">*</span></label>
                        <input type="number" name="loan_amount" class="form-control" placeholder="যেমন: 50000" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">মাসিক কিস্তি কর্তন (৳) <span class="text-danger">*</span></label>
                        <input type="number" name="monthly_deduction" class="form-control" placeholder="যেমন: 5000" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal" data-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary rounded-pill">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Advance Modal -->
<div class="modal fade" id="addAdvanceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <form action="{{ route('admin.hrm.loans.store_advance') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-header-title text-white mb-0"><i class="ti-plus mr-2"></i> স্যালারি এডভান্স মঞ্জুর করুন</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">কর্মী নির্বাচন করুন <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-control" required>
                            <option value="">-- নির্বাচন করুন --</option>
                            @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">এডভান্স পরিমাণ (৳) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control" placeholder="যেমন: 10000" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">কোন মাসের বেতন থেকে কর্তন হবে? <span class="text-danger">*</span></label>
                        <input type="month" name="deduction_month" class="form-control" value="{{ date('Y-m') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">কারণ</label>
                        <textarea name="reason" class="form-control" rows="2" placeholder="জরুরী প্রয়োজন..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal" data-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success rounded-pill">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
