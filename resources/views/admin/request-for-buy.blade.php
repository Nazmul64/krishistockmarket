@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Buy</h4>
                </div>

            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">


                <!-- /.col -->
                <div class="col-xl-12 col-lg-12">
                    <div class="box">
                        <div class="box-header with-border" style="display: flex;justify-content: space-between;">
                            <h3 class="box-title">Buy Request</h3>
                            <h3 class="box-title form-group">
                                <input type="text" class="form-control" placeholder="Serch Product">
                            </h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">

                                <div class="row">


                                    <div class="col-lg-12">
                                        <div class="table-responsive buyorder">
                                            <table class="table no-margin min-pad-table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>SN.</th>
                                                        <th class="text-center">Customer ID & Info</th>
                                                        <th class="text-center">Stock Package</th>
                                                        <th class="text-center">Price & Qty</th>
                                                        <th class="text-center">Payment Info (bKash/Nagad/Bank)</th>
                                                        <th class="text-center">Screenshot</th>
                                                        <th class="text-center">Date</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($all_buy_request as $key => $item)
                                                        @php
                                                            $userInfo = SingleUserInfo($item->user_id);
                                                            $stockInfo = SingleStockInfo($item->stock_id);
                                                            $paySys = SitePaymentSystem($item->payment_id);
                                                        @endphp
                                                        <tr>
                                                            <td>{{ ++$key }}</td>
                                                            <td class="text-center">
                                                                <span class="badge bg-primary mb-1">ID: #{{ $item->user_id }}</span><br>
                                                                <strong>{{ $userInfo->name ?? 'N/A' }}</strong><br>
                                                                <small class="text-muted"><i class="fa fa-phone me-1"></i>{{ $userInfo->phone ?? $userInfo->username ?? 'N/A' }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                <strong>{{ $stockInfo->stock_name ?? 'Stock Item' }}</strong>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="fw-bold text-success">৳{{ number_format($item->buyed_price, 2) }}</span><br>
                                                                <small>Qty: {{ $item->buy_quantiy }}</small>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge bg-info mb-1">{{ $paySys->pay_s_name ?? 'Mobile Banking/Bank' }}</span><br>
                                                                <small>From: <strong>{{ $item->pay_from_number }}</strong></small><br>
                                                                <small>Trx: <code class="text-dark fw-bold">{{ $item->trx_number }}</code></small>
                                                            </td>
                                                            <td class="text-center">
                                                                @if(!empty($item->sceenshort))
                                                                    <a href="{{ asset('upload/payment/'.$item->sceenshort) }}" target="_blank">
                                                                        <img src="{{ asset('upload/payment/'.$item->sceenshort) }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #ccc;">
                                                                    </a>
                                                                @else
                                                                    <span class="text-muted">No Image</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">{{ \Carbon\Carbon::parse($item->buyed_date)->format('d/m/Y h:i A')}}</td>
                                                            <td class="sorting_1 text-center">
                                                                <a href="{{ route('admin.stock.buyrequest.aproved', $item->id) }}"
                                                                    class="waves-effect waves-light btn btn-success mb-5 btn-xs"><i class="fa fa-check me-1"></i>Approve</a>
                                                                <a href="{{ route('admin.stock.buyrequest.rejected', $item->id) }}"
                                                                    class="waves-effect waves-light btn btn-danger mb-5 btn-xs"><i class="fa fa-times me-1"></i>Reject</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- /.col -->


                                </div>
                                <!-- /.row -->
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->

                </div>
                <!-- /.col -->


            </div>
        </section>
        <!-- /.content -->

    </div>


</div>
@endsection
