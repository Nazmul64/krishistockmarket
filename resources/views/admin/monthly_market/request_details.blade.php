@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title">মাসিক বাজার রিকোয়েস্ট বিস্তারিত ও বরাদ্দ ব্যবস্থাপনা</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.monthly_market.requests') }}">রিকোয়েস্ট তালিকা</a></li>
                        <li class="breadcrumb-item active">{{ $marketRequest->request_no }}</li>
                    </ol>
                </div>
                <div>
                    <a href="{{ route('admin.monthly_market.requests') }}" class="btn btn-secondary btn-sm me-2">
                        <i class="fa fa-arrow-left"></i> ফিরে যান
                    </a>
                    <a href="{{ route('user.monthly_market.slip', $marketRequest->id) }}" target="_blank" class="btn btn-primary btn-sm">
                        <i class="fa fa-print"></i> টোকেন স্লিপ প্রিন্ট
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>সফল!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>নোটিশ:</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- Member & Request Profile -->
                <div class="col-md-4 col-12">
                    <div class="box">
                        <div class="box-header with-border bg-primary">
                            <h4 class="box-title text-white"><i class="fa fa-user me-2"></i> কার্ড সদস্যের বিবরণ</h4>
                        </div>
                        <div class="box-body">
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 40%;">সদস্যের নাম:</th>
                                    <td><strong>{{ $marketRequest->user->name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>কার্ড আইডি:</th>
                                    <td><span class="badge bg-success">{{ $marketRequest->card_number ?? ('CRD-' . $marketRequest->user_id) }}</span></td>
                                </tr>
                                <tr>
                                    <th>মোবাইল নম্বর:</th>
                                    <td>{{ $marketRequest->user->phone }}</td>
                                </tr>
                                <tr>
                                    <th>আবেদনকৃত মাস:</th>
                                    <td><strong>{{ $marketRequest->month }} {{ $marketRequest->year }}</strong></td>
                                </tr>
                                <tr>
                                    <th>আবেদনের তারিখ:</th>
                                    <td>{{ $marketRequest->request_date }}</td>
                                </tr>
                                <tr>
                                    <th>আবেদন স্ট্যাটাস:</th>
                                    <td>
                                        @if($marketRequest->status == 'approved')
                                            <span class="badge bg-success">অনুমোদিত (Approved)</span>
                                        @elseif($marketRequest->status == 'pending')
                                            <span class="badge bg-warning text-dark">পেন্ডিং (Pending)</span>
                                        @elseif($marketRequest->status == 'rejected')
                                            <span class="badge bg-danger">প্রত্যাখ্যাত</span>
                                        @else
                                            <span class="badge bg-info">{{ ucfirst($marketRequest->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>সংগ্রহ স্ট্যাটাস:</th>
                                    <td>
                                        <span class="badge bg-info">{{ strtoupper($marketRequest->collection_status) }}</span>
                                    </td>
                                </tr>
                            </table>

                            <hr>
                            <h5 class="fw-bold text-dark mb-2"><i class="fa fa-map-marker text-danger me-1"></i> এরিয়া ও এজেন্ট পয়েন্ট</h5>
                            <div class="p-3 bg-light rounded border">
                                <p class="mb-1"><strong>রিকোয়েস্ট এরিয়া:</strong> <span class="badge bg-primary fs-14">{{ $marketRequest->request_area }}</span></p>
                                <p class="mb-1"><strong>এজেন্ট পয়েন্ট:</strong> {{ $marketRequest->agent_point_name ?? ($marketRequest->agentPoint->name ?? 'N/A') }}</p>
                                <p class="mb-1 text-muted"><strong>ঠিকানা:</strong> {{ $marketRequest->agentPoint->address ?? 'ঠিকানা সংরক্ষিত' }}</p>
                                <p class="mb-0 text-muted"><strong>এজেন্ট মোবাইল:</strong> {{ $marketRequest->agentPoint->contact_number ?? 'N/A' }}</p>
                            </div>

                            @if($marketRequest->rejection_reason)
                                <div class="mt-3 p-2 bg-danger-light rounded text-danger">
                                    <strong>বাতিলের কারণ:</strong> {{ $marketRequest->rejection_reason }}
                                </div>
                            @endif

                            <div class="mt-3 d-flex flex-wrap gap-2">
                                @if($marketRequest->status == 'pending')
                                    <a href="{{ route('admin.monthly_market.approve', $marketRequest->id) }}" class="btn btn-success flex-grow-1" onclick="return confirm('এই রিকোয়েস্টটি অনুমোদন করতে চান?');">
                                        <i class="fa fa-check me-1"></i> অনুমোদন করুন (Approve)
                                    </a>
                                    <button type="button" class="btn btn-danger flex-grow-1" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                        <i class="fa fa-times me-1"></i> রিজেক্ট
                                    </button>
                                @endif
                                <button type="button" class="btn btn-warning flex-grow-1" data-bs-toggle="modal" data-bs-target="#collectionModal">
                                    <i class="fa fa-handshake-o me-1"></i> পণ্য বিতরণ নিশ্চিতকরণ
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Table & Allocation Adjustment -->
                <div class="col-md-8 col-12">
                    <form action="{{ route('admin.monthly_market.update_items', $marketRequest->id) }}" method="POST">
                        @csrf
                        <div class="box">
                            <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                                <h4 class="box-title text-dark"><i class="fa fa-cubes me-2 text-primary"></i> চাহিদাকৃত পণ্য ও অনুমোদন/বরাদ্দ সমন্বয়</h4>
                                <span class="badge bg-primary">{{ $marketRequest->items->count() }} টি পণ্য</span>
                            </div>
                            <div class="box-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mb-0 text-center">
                                        <thead class="table-dark">
                                            <tr>
                                                <th style="width: 5%;">#</th>
                                                <th style="width: 25%; text-align: left;">পণ্যের নাম</th>
                                                <th style="width: 15%;">চাহিদাকৃত পরিমাণ</th>
                                                <th style="width: 15%;">অনুমোদিত পরিমাণ</th>
                                                <th style="width: 15%;">বিতরণকৃত (Collected)</th>
                                                <th style="width: 15%;">অবশিষ্ট (Remaining)</th>
                                                <th style="width: 10%;">একক</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($marketRequest->items as $idx => $item)
                                                <tr>
                                                    <td class="align-middle fw-bold">{{ $idx + 1 }}</td>
                                                    <td class="align-middle text-start fw-bold text-dark">
                                                        {{ $item->product_name }}
                                                    </td>
                                                    <td class="align-middle fs-15 text-primary fw-bold">
                                                        {{ floatval($item->requested_quantity) }}
                                                    </td>
                                                    <td class="align-middle">
                                                        <input type="number" step="0.1" name="items[{{ $item->id }}][approved_quantity]" class="form-control form-control-sm text-end fw-bold" value="{{ floatval($item->approved_quantity > 0 ? $item->approved_quantity : $item->requested_quantity) }}">
                                                    </td>
                                                    <td class="align-middle fs-15 text-success fw-bold">
                                                        {{ floatval($item->collected_quantity) }}
                                                    </td>
                                                    <td class="align-middle fs-15 text-danger fw-bold">
                                                        {{ floatval($item->remaining_quantity) }}
                                                    </td>
                                                    <td class="align-middle text-muted">
                                                        {{ $item->unit }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-8 col-12 mb-2">
                                        <label class="form-label fw-bold">পণ্য সংগ্রহের নির্দেশনা (Collection Instruction for Cardholder)</label>
                                        <input type="text" name="collection_instruction" class="form-control" value="{{ $marketRequest->collection_instruction ?? ('আপনার মাসিক বাজার ' . ($marketRequest->agent_point_name ?? 'নির্ধারিত এজেন্ট পয়েন্ট') . ' থেকে সংগ্রহ করতে হবে।') }}" required>
                                    </div>
                                    <div class="col-md-4 col-12 mb-2">
                                        <label class="form-label fw-bold">স্ট্যাটাস পরিবর্তন</label>
                                        <select name="status" class="form-select">
                                            <option value="pending" {{ $marketRequest->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" {{ $marketRequest->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="ready_for_collection" {{ $marketRequest->status == 'ready_for_collection' ? 'selected' : '' }}>Ready for Collection</option>
                                            <option value="completed" {{ $marketRequest->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="rejected" {{ $marketRequest->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save me-1"></i> পরিমাণ ও নির্দেশনা সংরক্ষণ করুন
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.monthly_market.reject', $marketRequest->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">আবেদন প্রত্যাখান / রিজেক্ট</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">প্রত্যাখানের কারণ (Rejection Reason) <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control" rows="3" placeholder="কেন এই বাজার রিকোয়েস্ট রিজেক্ট করা হলো তা লিখুন" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-danger">রিজেক্ট নিশ্চিত করুন</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Collection / Delivery Modal -->
<div class="modal fade" id="collectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('admin.monthly_market.collection.confirm') }}" method="POST">
            @csrf
            <input type="hidden" name="request_id" value="{{ $marketRequest->id }}">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white"><i class="fa fa-handshake-o me-2"></i> কার্ড সদস্যকে পণ্য বিতরণ নিশ্চিতকরণ (Delivery Confirmation)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold">বিতরণের তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="collection_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold">বিতরণকারী কর্মকর্তা / এজেন্টের নাম <span class="text-danger">*</span></label>
                            <input type="text" name="delivered_by" class="form-control" value="{{ Auth::user()->name }}" required>
                        </div>
                    </div>

                    <h6 class="fw-bold text-dark mb-2">বিতরণকৃত পণ্যের পরিমাণ প্রদান করুন (আংশিক বা সম্পূর্ণ বিতরণ):</h6>
                    <table class="table table-bordered table-sm text-center">
                        <thead class="table-light">
                            <tr>
                                <th>পণ্য</th>
                                <th>বরাদ্দকৃত</th>
                                <th>ইতিমধ্যে সংগৃহীত</th>
                                <th>অবশিষ্ট</th>
                                <th style="width: 25%;">এখন সরবরাহকৃত পরিমাণ (Delivered Qty)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($marketRequest->items as $it)
                                <tr>
                                    <td class="text-start fw-bold">{{ $it->product_name }}</td>
                                    <td>{{ floatval($it->approved_quantity) }} {{ $it->unit }}</td>
                                    <td>{{ floatval($it->collected_quantity) }} {{ $it->unit }}</td>
                                    <td class="text-danger fw-bold">{{ floatval($it->remaining_quantity) }} {{ $it->unit }}</td>
                                    <td>
                                        <input type="number" step="0.1" name="items[{{ $it->id }}][delivered_quantity]" class="form-control form-control-sm text-end fw-bold" value="{{ floatval($it->remaining_quantity) }}" max="{{ floatval($it->remaining_quantity) }}" min="0">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mb-3">
                        <label class="form-label">নোট / মন্তব্য</label>
                        <input type="text" name="notes" class="form-control" placeholder="পণ্য বিতরণের বিবরণ বা বিশেষ মন্তব্য">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check me-1"></i> ডেলিভারি নিশ্চিত করুন</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
