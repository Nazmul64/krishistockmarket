@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="ti-exchange-vertical text-warning me-2"></i>ব্রাঞ্চ-টু-ব্রাঞ্চ স্টক ট্রান্সফার (Stock Transfers)
                    </h3>
                    <p class="text-muted mb-0">এক শাখা থেকে অন্য শাখায় বা সেন্ট্রাল ওয়্যারহাউজ থেকে আউটলেটে পণ্য স্থানান্তর ও গ্রহণ</p>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#createTransferModal">
                        <i class="ti-plus me-1"></i> নতুন ট্রান্সফার রিকোয়েস্ট
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
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-circle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Transfers Table -->
            <div class="box shadow-sm">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title fw-bold text-dark mb-0">স্টক ট্রান্সফার তালিকা</h4>
                    <span class="badge bg-warning-light text-dark">রিকোয়েস্ট -> অনুমোদন ও ডিসপ্যাচ -> ইন-ট্রানজিট -> রিসিভ</span>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ট্রান্সফার ট্র্যাকিং #</th>
                                    <th>উৎস ব্রাঞ্চ (Source)</th>
                                    <th>গন্তব্য ব্রাঞ্চ (Destination)</th>
                                    <th>আইটেম ও পরিমাণ</th>
                                    <th>রিকোয়েস্ট তারিখ</th>
                                    <th>ডিসপ্যাচ তারিখ</th>
                                    <th class="text-center">স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transfers as $tr)
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-primary">{{ $tr->transfer_number }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-dark">{{ $tr->sourceBranch ? $tr->sourceBranch->code : '' }}</span>
                                            <strong class="ms-1">{{ $tr->sourceBranch ? $tr->sourceBranch->name : 'N/A' }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ $tr->destinationBranch ? $tr->destinationBranch->code : '' }}</span>
                                            <strong class="ms-1">{{ $tr->destinationBranch ? $tr->destinationBranch->name : 'N/A' }}</strong>
                                        </td>
                                        <td>
                                            @foreach($tr->items as $item)
                                                <div class="small">
                                                    • {{ $item->product ? $item->product->name : 'Product #'.$item->product_id }}:
                                                    <strong>{{ $item->requested_qty }} টি</strong>
                                                </div>
                                            @endforeach
                                        </td>
                                        <td class="small">{{ $tr->request_date }}</td>
                                        <td class="small text-muted">{{ $tr->dispatch_date ?: '-' }}</td>
                                        <td class="text-center">
                                            @if($tr->status == 'pending_approval')
                                                <span class="badge bg-warning text-dark">অনুমোদন অপেক্ষমাণ</span>
                                            @elseif($tr->status == 'in_transit')
                                                <span class="badge bg-info">ইন-ট্রানজিট (পথে আছে)</span>
                                            @elseif($tr->status == 'received')
                                                <span class="badge bg-success">সম্পূর্ণ গৃহীত</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $tr->status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($tr->status == 'pending_approval' || $tr->status == 'approved')
                                                <form method="POST" action="{{ route('admin.supershop.transfers.dispatch', $tr->id) }}" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিত যে পণ্য ডিসপ্যাচ করে গাড়িতে পাঠাতে চান? উৎস ব্রাঞ্চের স্টক কমে যাবে।')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs btn-warning text-dark fw-bold">
                                                        <i class="ti-truck me-1"></i> ডিসপ্যাচ
                                                    </button>
                                                </form>
                                            @elseif($tr->status == 'in_transit')
                                                <form method="POST" action="{{ route('admin.supershop.transfers.receive', $tr->id) }}" class="d-inline" onsubmit="return confirm('পণ্য কি অক্ষত অবস্থায় পৌঁছেছে? গন্তব্য ব্রাঞ্চের স্টক বৃদ্ধি পাবে।')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs btn-success fw-bold">
                                                        <i class="ti-check-box me-1"></i> রিসিভ ও ইনভেন্টরি প্লাস
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-success-light text-success"><i class="ti-check"></i> সম্পন্ন</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">কোনো ব্রাঞ্চ ট্রান্সফার রেকর্ড পাওয়া যায়নি।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($transfers->hasPages())
                    <div class="box-footer">
                        {{ $transfers->links() }}
                    </div>
                @endif
            </div>
        </section>

        <!-- Create Transfer Modal -->
        <div class="modal fade" id="createTransferModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('admin.supershop.transfers.store') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title fw-bold"><i class="ti-exchange-vertical me-1"></i> নতুন ব্রাঞ্চ ট্রান্সফার রিকোয়েস্ট</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">উৎস ব্রাঞ্চ / ডিপো (যেখান থেকে পাঠানো হবে) <span class="text-danger">*</span></label>
                                <select name="source_branch_id" class="form-select" required>
                                    <option value="">-- উৎস ব্রাঞ্চ নির্বাচন করুন --</option>
                                    @foreach($branches as $b)
                                        <option value="{{ $b->id }}">{{ $b->code }} - {{ $b->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">গন্তব্য ব্রাঞ্চ (যেখানে পণ্য যাবে) <span class="text-danger">*</span></label>
                                <select name="destination_branch_id" class="form-select" required>
                                    <option value="">-- গন্তব্য ব্রাঞ্চ নির্বাচন করুন --</option>
                                    @foreach($branches as $b)
                                        <option value="{{ $b->id }}">{{ $b->code }} - {{ $b->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">স্থানান্তরযোগ্য পণ্য ও পরিমাণ <span class="text-danger">*</span></label>
                                <div class="border rounded p-2 bg-light">
                                    <div class="row g-2 mb-2">
                                        <div class="col-8">
                                            <select name="product_id[]" class="form-select form-select-sm" required>
                                                <option value="">-- পণ্য নির্বাচন করুন --</option>
                                                @foreach($products as $prod)
                                                    <option value="{{ $prod->id }}">{{ $prod->name }} (SKU: {{ $prod->sku }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <input type="number" name="requested_qty[]" class="form-control form-control-sm" placeholder="পরিমাণ" value="20" min="1" required>
                                        </div>
                                    </div>

                                    <div class="row g-2 mb-2">
                                        <div class="col-8">
                                            <select name="product_id[]" class="form-select form-select-sm">
                                                <option value="">-- পণ্য ২ (ঐচ্ছিক) --</option>
                                                @foreach($products as $prod)
                                                    <option value="{{ $prod->id }}">{{ $prod->name }} (SKU: {{ $prod->sku }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <input type="number" name="requested_qty[]" class="form-control form-control-sm" placeholder="পরিমাণ" value="10" min="1">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">ট্রান্সফার নোট / চালক / গাড়ির তথ্য</label>
                                <input type="text" name="notes" class="form-control" placeholder="যেমন: পিকআপ ভ্যান ঢাকা মেট্রো-ন ১২৩৪">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                            <button type="submit" class="btn btn-warning fw-bold text-dark">রিকোয়েস্ট সাবমিট করুন</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

