@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title">আমার মাসিক বাজার চাহিদাসমূহ (My Monthly Market Requests)</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home-outline"></i> ড্যাশবোর্ড</a></li>
                        <li class="breadcrumb-item active">মাসিক বাজার হিস্ট্রি</li>
                    </ol>
                </div>
                <div>
                    <a href="{{ route('user.monthly_market.request') }}" class="btn btn-success btn-sm">
                        <i class="fa fa-plus-circle me-1"></i> নতুন মাসিক চাহিদা সাবমিট করুন
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

            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">জমা দেওয়া মাসিক বাজারের আবেদন ও ডেলিভারি স্ট্যাটাস</h4>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="example1">
                                    <thead class="bg-dark text-white text-center">
                                        <tr>
                                            <th>রিকোয়েস্ট নং</th>
                                            <th>মাস ও বছর</th>
                                            <th>এলাকা (Area)</th>
                                            <th>নির্ধারিত এজেন্ট পয়েন্ট (Collection Point)</th>
                                            <th>চাহিদাকৃত পণ্য ও পরিমাণ</th>
                                            <th>আবেদন স্ট্যাটাস</th>
                                            <th>সংগ্রহের স্ট্যাটাস</th>
                                            <th>অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($requests as $req)
                                            <tr>
                                                <td class="align-middle fw-bold text-center">
                                                    <span class="text-primary">{{ $req->request_no }}</span><br>
                                                    <small class="text-muted">{{ $req->request_date }}</small>
                                                </td>
                                                <td class="align-middle text-center fw-bold">
                                                    {{ $req->month }} {{ $req->year }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="badge bg-info">{{ $req->request_area }}</span>
                                                </td>
                                                <td class="align-middle">
                                                    <strong class="text-dark">{{ $req->agent_point_name ?? ($req->agentPoint->name ?? 'N/A') }}</strong><br>
                                                    <small class="text-muted"><i class="fa fa-map-marker text-danger"></i> {{ $req->agentPoint->address ?? 'ঠিকানা সংরক্ষিত' }}</small>
                                                    @if($req->collection_instruction)
                                                        <div class="mt-1 p-1 bg-light rounded text-success" style="font-size: 11px;">
                                                            <i class="fa fa-bullhorn me-1"></i> {{ $req->collection_instruction }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="align-middle">
                                                    <ul class="list-unstyled mb-0" style="font-size: 12px;">
                                                        @foreach($req->items as $item)
                                                            <li>
                                                                <i class="fa fa-check-square-o text-success me-1"></i>
                                                                <strong>{{ $item->product_name }}:</strong> 
                                                                {{ floatval($item->requested_quantity) }} {{ $item->unit }}
                                                                @if($item->approved_quantity > 0 && $item->approved_quantity != $item->requested_quantity)
                                                                    <span class="badge bg-warning text-dark">বরাদ্দ: {{ floatval($item->approved_quantity) }}</span>
                                                                @endif
                                                                @if($item->collected_quantity > 0)
                                                                    <span class="badge bg-success">সংগৃহীত: {{ floatval($item->collected_quantity) }}</span>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td class="align-middle text-center">
                                                    @if($req->status == 'approved')
                                                        <span class="badge bg-success">অনুমোদিত (Approved)</span>
                                                    @elseif($req->status == 'pending')
                                                        <span class="badge bg-warning text-dark">পেন্ডিং (Pending)</span>
                                                    @elseif($req->status == 'rejected')
                                                        <span class="badge bg-danger">প্রত্যাখ্যাত (Rejected)</span>
                                                        @if($req->rejection_reason)
                                                            <br><small class="text-danger">{{ $req->rejection_reason }}</small>
                                                        @endif
                                                    @elseif($req->status == 'ready_for_collection')
                                                        <span class="badge bg-primary">সংগ্রহের জন্য প্রস্তুত</span>
                                                    @elseif(in_array($req->status, ['distributed', 'completed']))
                                                        <span class="badge bg-info">সম্পন্ন (Delivered)</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($req->status) }}</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-center">
                                                    @if($req->collection_status == 'fully_collected')
                                                        <span class="badge bg-success"><i class="fa fa-check"></i> সম্পূর্ণ সংগৃহীত</span>
                                                    @elseif($req->collection_status == 'partially_collected')
                                                        <span class="badge bg-warning text-dark"><i class="fa fa-clock-o"></i> আংশিক সংগৃহীত</span>
                                                    @else
                                                        <span class="badge bg-light text-dark border">পেন্ডিং সংগ্রহ</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-center">
                                                    <a href="{{ route('user.monthly_market.slip', $req->id) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="সংগ্রহের টোকেন ও রসিদ প্রিন্ট করুন">
                                                        <i class="fa fa-print me-1"></i> টোকেন স্লিপ
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">
                                                    <i class="fa fa-shopping-basket fa-3x mb-3 text-secondary"></i><br>
                                                    আপনি এখনো কোনো মাসিক বাজার চাহিদা রিকোয়েস্ট জমা দেননি।<br>
                                                    <a href="{{ route('user.monthly_market.request') }}" class="btn btn-success btn-sm mt-2">
                                                        <i class="fa fa-plus me-1"></i> এখন আবেদন করুন
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
