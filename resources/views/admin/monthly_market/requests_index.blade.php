@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title">মাসিক বাজার চাহিদা রিকোয়েস্টসমূহ (Monthly Market Requests)</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home-outline"></i> ড্যাশবোর্ড</a></li>
                        <li class="breadcrumb-item active">বাজার চাহিদা ব্যবস্থাপনা</li>
                    </ol>
                </div>
                <div>
                    <a href="{{ route('admin.monthly_market.demand_analysis') }}" class="btn btn-primary btn-sm me-2">
                        <i class="fa fa-bar-chart me-1"></i> ডিমান্ড অ্যানালাইসিস
                    </a>
                    <a href="{{ route('admin.monthly_market.dispatches') }}" class="btn btn-success btn-sm">
                        <i class="fa fa-truck me-1"></i> সেন্ট্রাল ডিসপ্যাচ ও চালান
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

            <!-- Stats Overview Cards -->
            <div class="row">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box bg-primary-light">
                        <div class="box-body d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-primary fw-bold mb-0">{{ $totalRequestsCount }}</h3>
                                <p class="text-muted mb-0">মোট বাজার রিকোয়েস্ট</p>
                            </div>
                            <div class="bg-primary text-white p-3 rounded-circle">
                                <i class="fa fa-shopping-basket fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box bg-warning-light">
                        <div class="box-body d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-warning fw-bold mb-0">{{ $pendingCount }}</h3>
                                <p class="text-muted mb-0">পেন্ডিং যাচাইকরণ</p>
                            </div>
                            <div class="bg-warning text-white p-3 rounded-circle">
                                <i class="fa fa-clock-o fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box bg-success-light">
                        <div class="box-body d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-success fw-bold mb-0">{{ $approvedCount }}</h3>
                                <p class="text-muted mb-0">অনুমোদিত চাহিদা</p>
                            </div>
                            <div class="bg-success text-white p-3 rounded-circle">
                                <i class="fa fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box bg-info-light">
                        <div class="box-body d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-info fw-bold mb-0">{{ $distributedCount }}</h3>
                                <p class="text-muted mb-0">বিতরণ সম্পন্ন</p>
                            </div>
                            <div class="bg-info text-white p-3 rounded-circle">
                                <i class="fa fa-handshake-o fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title"><i class="fa fa-filter text-primary me-2"></i> ফিল্টারিং অপশন (Area & Agent Point Filters)</h4>
                </div>
                <div class="box-body">
                    <form method="GET" action="{{ route('admin.monthly_market.requests') }}" class="row g-3">
                        <div class="col-md-2 col-6">
                            <label class="form-label">মাস (Month)</label>
                            <select name="month" class="form-select">
                                <option value="">সকল মাস</option>
                                @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $m)
                                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label">বছর (Year)</label>
                            <select name="year" class="form-select">
                                <option value="">সকল বছর</option>
                                @foreach([date('Y'), date('Y')+1, date('Y')-1] as $y)
                                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label">এলাকা (Area)</label>
                            <select name="request_area" class="form-select">
                                <option value="">সকল এলাকা</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area }}" {{ request('request_area') == $area ? 'selected' : '' }}>{{ $area }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label">এজেন্ট পয়েন্ট</label>
                            <select name="agent_point_id" class="form-select">
                                <option value="">সকল এজেন্ট পয়েন্ট</option>
                                @foreach($agentPoints as $point)
                                    <option value="{{ $point->id }}" {{ request('agent_point_id') == $point->id ? 'selected' : '' }}>{{ $point->name }} ({{ $point->area }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label">স্ট্যাটাস</label>
                            <select name="status" class="form-select">
                                <option value="">সকল স্ট্যাটাস</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-1 col-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Card -->
            <div class="box">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title">কার্ড হোল্ডারদের মাসিক বাজার চাহিদা রিকোয়েস্ট তালিকা</h4>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0 text-center" id="requestsTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>রিকোয়েস্ট নং</th>
                                    <th>কার্ড সদস্যের নাম ও আইডি</th>
                                    <th>মাস ও বছর</th>
                                    <th>এলাকা (Area)</th>
                                    <th>এজেন্ট পয়েন্ট (Agent Point)</th>
                                    <th>চাহিদাকৃত পণ্যসমূহ</th>
                                    <th>আবেদন স্ট্যাটাস</th>
                                    <th>সংগ্রহ স্ট্যাটাস</th>
                                    <th>অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $req)
                                    <tr>
                                        <td class="align-middle fw-bold">
                                            <a href="{{ route('admin.monthly_market.request_details', $req->id) }}" class="text-primary">{{ $req->request_no }}</a><br>
                                            <small class="text-muted">{{ $req->request_date }}</small>
                                        </td>
                                        <td class="align-middle text-start">
                                            <strong class="text-dark">{{ $req->user->name ?? 'N/A' }}</strong><br>
                                            <span class="badge bg-secondary">কার্ড: {{ $req->card_number ?? ('CRD-' . $req->user_id) }}</span><br>
                                            <small class="text-muted"><i class="fa fa-phone"></i> {{ $req->user->phone ?? '-' }}</small>
                                        </td>
                                        <td class="align-middle fw-bold">
                                            {{ $req->month }} {{ $req->year }}
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-info fs-13">{{ $req->request_area }}</span>
                                        </td>
                                        <td class="align-middle text-start">
                                            <strong>{{ $req->agent_point_name ?? ($req->agentPoint->name ?? 'N/A') }}</strong><br>
                                            <small class="text-muted">{{ $req->agentPoint->address ?? '' }}</small>
                                        </td>
                                        <td class="align-middle text-start">
                                            <span class="badge bg-success">{{ $req->items->count() }} টি পণ্য</span>
                                            <ul class="list-unstyled mb-0 mt-1" style="font-size: 11px;">
                                                @foreach($req->items->take(3) as $it)
                                                    <li>• {{ $it->product_name }}: <strong>{{ floatval($it->requested_quantity) }} {{ $it->unit }}</strong></li>
                                                @endforeach
                                                @if($req->items->count() > 3)
                                                    <li class="text-muted">+ আরও {{ $req->items->count() - 3 }} টি</li>
                                                @endif
                                            </ul>
                                        </td>
                                        <td class="align-middle">
                                            @if($req->status == 'approved')
                                                <span class="badge bg-success">অনুমোদিত</span>
                                            @elseif($req->status == 'pending')
                                                <span class="badge bg-warning text-dark">পেন্ডিং</span>
                                            @elseif($req->status == 'rejected')
                                                <span class="badge bg-danger">প্রত্যাখ্যাত</span>
                                            @elseif($req->status == 'ready_for_collection')
                                                <span class="badge bg-primary">সংগ্রহের জন্য প্রস্তুত</span>
                                            @elseif(in_array($req->status, ['distributed', 'completed']))
                                                <span class="badge bg-info">সম্পন্ন</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($req->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @if($req->collection_status == 'fully_collected')
                                                <span class="badge bg-success">সম্পূর্ণ সংগৃহীত</span>
                                            @elseif($req->collection_status == 'partially_collected')
                                                <span class="badge bg-warning text-dark">আংশিক</span>
                                            @else
                                                <span class="badge bg-light text-dark border">পেন্ডিং</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <div class="btn-group-vertical btn-group-sm">
                                                <a href="{{ route('admin.monthly_market.request_details', $req->id) }}" class="btn btn-primary mb-1">
                                                    <i class="fa fa-eye"></i> বিস্তারিত ও বরাদ্দ
                                                </a>
                                                @if($req->status == 'pending')
                                                    <a href="{{ route('admin.monthly_market.approve', $req->id) }}" class="btn btn-success mb-1" onclick="return confirm('এই রিকোয়েস্টটি অনুমোদন করতে চান?');">
                                                        <i class="fa fa-check"></i> দ্রুত অনুমোদন
                                                    </a>
                                                @endif
                                                <a href="{{ route('user.monthly_market.slip', $req->id) }}" target="_blank" class="btn btn-outline-secondary">
                                                    <i class="fa fa-print"></i> স্লিপ
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">
                                            কোনো বাজার চাহিদা রিকোয়েস্ট পাওয়া যায়নি।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>মোট রিকোয়েস্ট: {{ $requests->total() }} টি</div>
                        <div>{{ $requests->links() }}</div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
