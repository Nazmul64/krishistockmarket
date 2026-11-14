@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-shopping-basket text-primary me-2"></i> স্টোর রিকুইজিশন ও বিতরণ ব্যবস্থাপনা</h4>
                    <p class="text-muted small mb-0">বিভাগভিত্তিক মালামালের চাহিদা, বিভাগীয় প্রধানের অনুমোদন এবং স্টোর থেকে সামগ্রী বিতরণ (Stock Issue)।</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createRequisitionModal">
                        <i class="fa fa-plus me-1"></i> নতুন রিকুইজিশন দাখিল
                    </button>
                    <a href="{{ route('admin.erp.store.items') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="fa fa-boxes me-1"></i> ইনভেন্টরি স্টক
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
                    <i class="fa fa-exclamation-triangle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>রিকুইজিশন নং</th>
                                <th>বিভাগ ও আবেদনকারী</th>
                                <th>আইটেম ও চাহিদার বিবরণ</th>
                                <th>তারিখ</th>
                                <th>অগ্রাধিকার</th>
                                <th>স্ট্যাটাস</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requisitions as $req)
                            <tr>
                                <td class="fw-bold text-primary">{{ $req->requisition_number }}</td>
                                <td>
                                    <span class="fw-bold text-dark d-block">{{ $req->department_name }}</span>
                                    <small class="text-muted">{{ $req->requesting_employee_name }}</small>
                                </td>
                                <td>
                                    <ul class="list-unstyled mb-0 font-size-12">
                                        @foreach($req->items as $item)
                                            <li>
                                                <i class="fa fa-caret-right text-primary me-1"></i>
                                                <span class="fw-bold">{{ optional($item->item)->name_bn ?? optional($item->item)->name }}</span>: 
                                                চাহিদা: <strong>{{ number_format($item->requested_qty) }}</strong> | বিতরণ: <strong>{{ number_format($item->issued_qty) }}</strong>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{ $req->request_date ? \Carbon\Carbon::parse($req->request_date)->format('d/m/Y') : ($req->created_at ? $req->created_at->format('d/m/Y') : '-') }}</td>
                                <td>
                                    @if($req->priority === 'urgent')
                                        <span class="badge bg-danger">জরুরি (Urgent)</span>
                                    @elseif($req->priority === 'high')
                                        <span class="badge bg-warning text-dark">উচ্চ (High)</span>
                                    @else
                                        <span class="badge bg-secondary">স্বাভাবিক</span>
                                    @endif
                                </td>
                                <td>
                                    @if($req->status === 'pending')
                                        <span class="badge bg-warning text-dark"><i class="fa fa-clock-o me-1"></i> অনুমোদনের অপেক্ষায়</span>
                                    @elseif($req->status === 'approved')
                                        <span class="badge bg-info"><i class="fa fa-check me-1"></i> অনুমোদিত (বিতরণ বাকি)</span>
                                    @elseif($req->status === 'issued')
                                        <span class="badge bg-success"><i class="fa fa-check-circle me-1"></i> মালামাল বিতরণ সম্পন্ন</span>
                                    @elseif($req->status === 'rejected')
                                        <span class="badge bg-danger">বাতিলকৃত</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($req->status === 'pending')
                                        <form action="{{ route('admin.erp.store.requisitions.status', $req->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="btn btn-xs btn-outline-success rounded-pill px-2" title="অনুমোদন করুন">
                                                <i class="fa fa-check"></i> অনুমোদন
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.erp.store.requisitions.status', $req->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="action" value="reject">
                                            <button type="submit" class="btn btn-xs btn-outline-danger rounded-pill px-2" title="বাতিল করুন" onclick="return confirm('আপনি কি এই রিকুইজিশন বাতিল করতে চান?')">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </form>
                                    @elseif($req->status === 'approved')
                                        <form action="{{ route('admin.erp.store.requisitions.status', $req->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="action" value="issue">
                                            <button type="submit" class="btn btn-xs btn-primary rounded-pill px-3 fw-bold" onclick="return confirm('স্টক থেকে মালামাল বিতরণ নিশ্চিত করতে চান?')">
                                                <i class="fa fa-arrow-circle-right me-1"></i> মালামাল বিতরণ (Issue)
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted"><i class="fa fa-lock"></i> ক্লোজড</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">কোনো রিকুইজিশন রেকর্ড পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $requisitions->links() }}
                </div>
            </div>

        </section>
    </div>
</div>

<!-- Create Requisition Modal -->
<div class="modal fade" id="createRequisitionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="fa fa-shopping-basket text-primary me-2"></i> নতুন স্টোর রিকুইজিশন তৈরি</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.erp.store.requisitions.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">বিভাগের নাম *</label>
                            <input type="text" name="department_name" class="form-control" placeholder="उदा: আইটি ডিপার্টমেন্ট / প্রশাসন" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">চাহিদাকারী কর্মকর্তার নাম *</label>
                            <input type="text" name="requesting_employee_name" class="form-control" placeholder="उदा: মোঃ কামরুল হাসান" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">স্টোর / গুদাম *</label>
                            <select name="warehouse_id" class="form-select" required>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->id }}">{{ $wh->name_bn ?? $wh->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">প্রয়োজনের তারিখ</label>
                            <input type="date" name="required_date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">অগ্রাধিকার</label>
                            <select name="priority" class="form-select">
                                <option value="medium">স্বাভাবিক (Medium)</option>
                                <option value="high">জরুরি (High)</option>
                                <option value="urgent">অতি জরুরি (Urgent)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">ব্যবহারের উদ্দেশ্য (Purpose)</label>
                            <textarea name="purpose" class="form-control" rows="2" placeholder="মালামাল কি কাজে ব্যবহার হবে..."></textarea>
                        </div>
                    </div>

                    <h6 class="fw-bold text-dark border-top pt-3 mb-2"><i class="fa fa-list me-1"></i> চাহিদাকৃত আইটেমসমূহ নির্বাচন করুন:</h6>
                    <div id="requisitionItemsContainer">
                        <div class="row g-2 mb-2 item-row">
                            <div class="col-8">
                                <select name="item_ids[]" class="form-select" required>
                                    <option value="">-- আইটেম সিলেক্ট করুন --</option>
                                    @foreach($items as $itm)
                                        <option value="{{ $itm->id }}">{{ $itm->name_bn ?? $itm->name }} (মজুত: {{ $itm->current_stock }} {{ optional($itm->unit)->symbol }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <input type="number" step="1" min="1" name="quantities[]" class="form-control" placeholder="পরিমাণ" value="1" required>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-xs btn-outline-primary rounded-pill mt-2" onclick="addRequisitionRow()">
                        <i class="fa fa-plus me-1"></i> আরও আইটেম যোগ করুন
                    </button>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">রিকুইজিশন দাখিল করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function addRequisitionRow() {
    const container = document.getElementById('requisitionItemsContainer');
    const firstRow = container.querySelector('.item-row');
    const clone = firstRow.cloneNode(true);
    clone.querySelector('input').value = 1;
    container.appendChild(clone);
}
</script>
@endsection

