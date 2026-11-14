@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-piggy-bank text-primary me-2"></i> এইচআরএম পে-রোল ও প্রভিডেন্ট ফান্ড (HRM & Provident Fund Management)</h4>
                    <p class="text-muted small mb-0">কর্মকর্তা-কর্মচারীদের মাসিক প্রভিডেন্ট ফান্ড কর্তন, প্রতিষ্ঠানের যৌথ অনুদান ও সামগ্রিক পিএফ ব্যালেন্স মনিটরিং।</p>
                </div>
                <button class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addPfModal">
                    <i class="fa fa-plus me-1"></i> মাসিক পিএফ এন্ট্রি
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Stats Overview -->
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary">
                                <i class="fa fa-user-check fa-2x"></i>
                            </div>
                            <div>
                                <div class="text-muted small fw-semibold">মোট কর্মচারী অংশ</div>
                                <h3 class="fw-bold text-primary mb-0">{{ number_format($totalEmployeeShare, 2) }} ৳</h3>
                                <small class="text-muted">মাসিক বেতন থেকে কর্তনকৃত</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success">
                                <i class="fa fa-building fa-2x"></i>
                            </div>
                            <div>
                                <div class="text-muted small fw-semibold">মোট প্রাতিষ্ঠানিক অনুদান</div>
                                <h3 class="fw-bold text-success mb-0">{{ number_format($totalCompanyShare, 2) }} ৳</h3>
                                <small class="text-muted">কোম্পানি ম্যাচিং কন্ট্রিবিউশন</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-info bg-opacity-10 p-3 text-info">
                                <i class="fa fa-vault fa-2x"></i>
                            </div>
                            <div>
                                <div class="text-muted small fw-semibold">সর্বমোট পিএফ সঞ্চিতি</div>
                                <h3 class="fw-bold text-dark mb-0">{{ number_format($grandTotalPf, 2) }} ৳</h3>
                                <small class="text-info fw-semibold">সেন্ট্রাল প্রভিডেন্ট ফান্ড ট্রাস্ট</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PF Table -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">পিএফ লেনদেন ও মাসিক স্টেটমেন্ট</h5>
                    <span class="badge bg-light text-secondary font-size-12">মোট এন্ট্রি: {{ $records->total() }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>কর্মকর্তা / কর্মচারী</th>
                                <th>মাস ও বছর</th>
                                <th>কর্মচারী কর্তন</th>
                                <th>প্রতিষ্ঠান অনুদান</th>
                                <th>মোট জমা</th>
                                <th>স্ট্যাটাস</th>
                                <th>বিবরণ ও মন্তব্য</th>
                                <th>তারিখ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($records as $rec)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $rec->user->name ?? 'কর্মচারী' }}</div>
                                    <small class="text-muted">{{ $rec->user->phone ?? ($rec->user->email ?? '') }}</small>
                                </td>
                                <td><span class="badge bg-secondary-light text-dark fw-bold">{{ $rec->month_year }}</span></td>
                                <td class="fw-semibold text-primary">{{ number_format($rec->employee_contribution, 2) }} ৳</td>
                                <td class="fw-semibold text-success">{{ number_format($rec->company_contribution, 2) }} ৳</td>
                                <td class="fw-bold text-dark">{{ number_format($rec->total_amount, 2) }} ৳</td>
                                <td>
                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1">
                                        <i class="fa fa-check me-1"></i> {{ ucfirst($rec->status) }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $rec->remarks ?: 'মাসিক পিএফ জমা' }}</td>
                                <td>{{ $rec->created_at->format('d M, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">কোনো প্রভিডেন্ট ফান্ড এন্ট্রি পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $records->links() }}
                </div>
            </div>

        </section>
    </div>
</div>

<!-- Add PF Modal -->
<div class="modal fade" id="addPfModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="fa fa-piggy-bank text-primary me-2"></i> মাসিক পিএফ অনুদান এন্ট্রি</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.corporate.provident_fund.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">কর্মকর্তা / কর্মচারী নির্বাচন করুন *</label>
                        <select name="user_id" class="form-select rounded-3" required>
                            <option value="">-- কর্মচারী নির্বাচন করুন --</option>
                            @if(isset($employeeUsers))
                                @foreach($employeeUsers as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->role }} - {{ $u->phone }})</option>
                                @endforeach
                            @elseif(isset($employees))
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->user_id ?? $emp->id }}">{{ $emp->user->name ?? $emp->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">মাস ও বছর (উদাঃ Nov 2026 / 11-2026) *</label>
                        <input type="text" name="month_year" class="form-control rounded-3" value="{{ date('F Y') }}" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">কর্মচারী অনুদান (টাকা) *</label>
                            <input type="number" step="0.01" name="employee_contribution" id="emp_contrib" class="form-control rounded-3" placeholder="১০০০" required oninput="calcPfTotal()">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">কোম্পানি অনুদান (টাকা) *</label>
                            <input type="number" step="0.01" name="company_contribution" id="comp_contrib" class="form-control rounded-3" placeholder="১০০০" required oninput="calcPfTotal()">
                        </div>
                    </div>
                    <div class="mb-3 bg-light rounded-3 p-3 text-center">
                        <span class="small text-muted">সর্বমোট মাসিক সঞ্চয়:</span>
                        <div class="h4 fw-bold text-primary mb-0" id="total_pf_display">০.০০ ৳</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">মন্তব্য বা রেফারেন্স</label>
                        <textarea name="remarks" rows="2" class="form-control rounded-3" placeholder="নভেম্বর মাসের পিএফ কন্ট্রিবিউশন"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">পিএফ জমা সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function calcPfTotal() {
    let emp = parseFloat(document.getElementById('emp_contrib').value) || 0;
    let comp = parseFloat(document.getElementById('comp_contrib').value) || 0;
    document.getElementById('total_pf_display').innerText = (emp + comp).toFixed(2) + ' ৳';
}
</script>
@endsection

