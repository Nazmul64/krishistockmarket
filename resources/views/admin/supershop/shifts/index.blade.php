@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="ti-lock text-success me-2"></i>ক্যাশিয়ার শিফট ও ড্রয়ার রিকনসিলিয়েশন (Cashier Shifts)
                    </h3>
                    <p class="text-muted mb-0">প্রতিটি শিফটের ওপেনিং ব্যালেন্স, সেলস ক্যাশ, ড্রয়ার লেনদেন ও ক্লোজিং ঘাটতি/উদ্বৃত্ত অডিট</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-info fw-bold" data-bs-toggle="modal" data-bs-target="#drawerModal">
                        <i class="ti-wallet me-1"></i> ড্রয়ার ক্যাশ ইন/আউট
                    </button>
                    <button type="button" class="btn btn-sm btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#openShiftModal">
                        <i class="ti-plus me-1"></i> নতুন শিফট শুরু করুন
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fa fa-info-circle me-1"></i> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Branch Filter -->
            <div class="box shadow-sm mb-3">
                <div class="box-body py-2">
                    <form method="GET" action="{{ route('admin.supershop.shifts') }}" class="row g-2 align-items-center">
                        <div class="col-md-4 col-12">
                            <select name="branch_id" class="form-select form-select-sm fw-bold" onchange="this.form.submit()">
                                <option value="">🏢 সকল ব্রাঞ্চ (All Branches)</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}" {{ $selectedBranchId == $b->id ? 'selected' : '' }}>
                                        {{ $b->code }} - {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Shifts Table -->
            <div class="box shadow-sm">
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>শিফট #</th>
                                    <th>ব্রাঞ্চ ও কাউন্টার</th>
                                    <th>ক্যাশিয়ার</th>
                                    <th>শুরু / শেষ সময়</th>
                                    <th class="text-end">ওপেনিং ক্যাশ</th>
                                    <th class="text-end">ক্যাশ বিক্রয়</th>
                                    <th class="text-end">প্রত্যাশিত ক্যাশ</th>
                                    <th class="text-end">প্রকৃত ক্যাশ</th>
                                    <th class="text-center">পার্থক্য (Difference)</th>
                                    <th class="text-center">স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shifts as $shift)
                                    @php
                                        $diff = (float)$shift->cash_difference;
                                        $isOpen = $shift->status === 'open';
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-primary">#SH-{{ str_pad($shift->id, 4, '0', STR_PAD_LEFT) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-dark">{{ $shift->branch ? $shift->branch->code : '' }}</span>
                                            <span class="small fw-bold ms-1">{{ $shift->branch ? $shift->branch->name : '' }}</span>
                                            <div class="small text-muted">{{ $shift->counter ? $shift->counter->counter_name : 'Counter 01' }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold small text-dark">{{ $shift->cashier ? $shift->cashier->name : 'N/A' }}</div>
                                            <div class="small text-muted">{{ $shift->cashier ? $shift->cashier->email : '' }}</div>
                                        </td>
                                        <td class="small">
                                            <div><i class="ti-time text-success me-1"></i> {{ \Carbon\Carbon::parse($shift->opened_at)->format('d M, h:i A') }}</div>
                                            @if($shift->closed_at)
                                                <div class="text-muted"><i class="ti-check text-muted me-1"></i> {{ \Carbon\Carbon::parse($shift->closed_at)->format('d M, h:i A') }}</div>
                                            @endif
                                        </td>
                                        <td class="text-end fw-bold text-dark">
                                            ৳ {{ number_format($shift->opening_cash, 2) }}
                                        </td>
                                        <td class="text-end text-success fw-bold">
                                            ৳ {{ number_format($shift->total_cash_sales, 2) }}
                                            <div class="smaller text-muted">ডিজিটাল: ৳{{ number_format($shift->total_digital_sales, 2) }}</div>
                                        </td>
                                        <td class="text-end fw-bold text-primary">
                                            ৳ {{ number_format($shift->expected_cash, 2) }}
                                        </td>
                                        <td class="text-end fw-bold">
                                            @if($isOpen)
                                                <span class="text-muted">হিসাব চলমান</span>
                                            @else
                                                ৳ {{ number_format($shift->actual_cash, 2) }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($isOpen)
                                                <span class="badge bg-light text-muted">-</span>
                                            @elseif($diff == 0)
                                                <span class="badge bg-success">সঠিক (Balanced)</span>
                                            @elseif($diff < 0)
                                                <span class="badge bg-danger">ঘাটতি -৳{{ abs($diff) }}</span>
                                            @else
                                                <span class="badge bg-warning text-dark">উদ্বৃত্ত +৳{{ $diff }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($isOpen)
                                                <span class="badge bg-success">চালু (Open)</span>
                                            @else
                                                <span class="badge bg-secondary">বন্ধ (Closed)</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($isOpen)
                                                <button type="button" class="btn btn-xs btn-danger fw-bold" data-bs-toggle="modal" data-bs-target="#closeShiftModal{{ $shift->id }}">
                                                    <i class="ti-power-off me-1"></i> শিফট ক্লোজ
                                                </button>
                                            @else
                                                <span class="text-muted small"><i class="ti-check text-success"></i> ক্লোজড</span>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Close Shift Modal -->
                                    @if($isOpen)
                                        <div class="modal fade" id="closeShiftModal{{ $shift->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form method="POST" action="{{ route('admin.supershop.shifts.close', $shift->id) }}">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title fw-bold text-white"><i class="ti-power-off me-1"></i> শিফট ক্লোজ ও ক্যাশ রিকনসিলিয়েশন</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="p-3 bg-light rounded mb-3">
                                                                <div class="d-flex justify-content-between mb-1">
                                                                    <span>ওপেনিং ক্যাশ:</span>
                                                                    <strong>৳ {{ number_format($shift->opening_cash, 2) }}</strong>
                                                                </div>
                                                                <div class="d-flex justify-content-between mb-1">
                                                                    <span>মোট ক্যাশ বিক্রয়:</span>
                                                                    <strong class="text-success">৳ {{ number_format($shift->total_cash_sales, 2) }}</strong>
                                                                </div>
                                                                <hr class="my-1">
                                                                <div class="d-flex justify-content-between fs-6 fw-bold">
                                                                    <span>প্রত্যাশিত ক্যাশ ড্রয়ার ব্যালেন্স:</span>
                                                                    <span class="text-primary">৳ {{ number_format($shift->expected_cash, 2) }}</span>
                                                                </div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">প্রকৃত ক্যাশ গণনা (Physical Cash Counted) <span class="text-danger">*</span></label>
                                                                <input type="number" step="0.01" name="actual_cash" class="form-control form-control-lg fw-bold" placeholder="ক্যাশ ড্রয়ারে গুনে পাওয়া টাকা" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">ক্লোজিং মন্তব্য / অডিট নোট</label>
                                                                <textarea name="closing_notes" class="form-control" rows="2" placeholder="কোনো ঘাটতি বা অতিরিক্ত থাকলে ব্যাখ্যা লিখুন..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                                                            <button type="submit" class="btn btn-danger fw-bold">শিফট ক্লোজ নিশ্চিত করুন</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-4 text-muted">কোনো ক্যাশিয়ার শিফট রেকর্ড পাওয়া যায়নি।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($shifts->hasPages())
                    <div class="box-footer">
                        {{ $shifts->links() }}
                    </div>
                @endif
            </div>
        </section>

        <!-- Open Shift Modal -->
        <div class="modal fade" id="openShiftModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('admin.supershop.shifts.open') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title fw-bold text-white"><i class="ti-plus me-1"></i> নতুন ক্যাশিয়ার শিফট শুরু করুন</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">ব্রাঞ্চ নির্বাচন <span class="text-danger">*</span></label>
                                <select name="branch_id" class="form-select" required>
                                    @foreach($branches as $b)
                                        <option value="{{ $b->id }}">{{ $b->code }} - {{ $b->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">কাউন্টার নম্বর</label>
                                <select name="pos_counter_id" class="form-select">
                                    <option value="">কাউন্টার ০১ (Counter 01)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">ওপেনিং ক্যাশ ফ্লট (Opening Cash Float) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="opening_cash" class="form-control form-control-lg fw-bold" placeholder="যেমন: 2000" value="2000" required>
                                <small class="text-muted">ভাঙতি ও প্রাথমিক লেনদেনের জন্য ড্রয়ারে রাখা প্রারম্ভিক ক্যাশ।</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                            <button type="submit" class="btn btn-success fw-bold">শিফট চালু করুন</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Drawer Transaction Modal -->
        <div class="modal fade" id="drawerModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('admin.supershop.shifts.drawer_transaction') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-info text-white">
                            <h5 class="modal-title fw-bold text-white"><i class="ti-wallet me-1"></i> ক্যাশ ড্রয়ার লেনদেন (Cash In / Out)</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">সক্রিয় শিফট নির্বাচন <span class="text-danger">*</span></label>
                                <select name="shift_id" class="form-select" required>
                                    @foreach($shifts->where('status', 'open') as $os)
                                        <option value="{{ $os->id }}">
                                            #SH-{{ $os->id }} - {{ $os->branch ? $os->branch->name : '' }} ({{ $os->cashier ? $os->cashier->name : '' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">লেনদেনের ধরন <span class="text-danger">*</span></label>
                                <select name="type" class="form-select" required>
                                    <option value="cash_in">➕ ক্যাশ ইন (অতিরিক্ত ভাঙতি ড্রয়ারে জমা)</option>
                                    <option value="cash_out">➖ ক্যাশ আউট (ভল্ট বা ম্যানেজারের কাছে জমা)</option>
                                    <option value="expense">➖ ড্রয়ার থেকে স্থানীয় খরচ (Petty Expense)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">টাকার পরিমাণ <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="amount" class="form-control form-control-lg fw-bold" placeholder="0.00" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">কারণ / বিবরণ <span class="text-danger">*</span></label>
                                <input type="text" name="reason" class="form-control" placeholder="যেমন: সেন্ট্রাল ভল্টে ক্যাশ ট্রান্সফার" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                            <button type="submit" class="btn btn-info fw-bold text-white">সংরক্ষণ করুন</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

