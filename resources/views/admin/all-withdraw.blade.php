@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Withdraw</h4>
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
                            <h3 class="box-title">All Withdraw</h3>
                            <h3 class="box-title form-group">
                                <input type="text" class="form-control" placeholder="Serch Product">
                            </h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                                <div class="row">


                                    <div class="col-lg-12">
                                        <div class="table-responsive buyorder">
                                            <table class="table no-margin min-pad-table">
                                                <thead>
                                                    <tr>
                                                        <th>SN.</th>
                                                        <th class="text-center">Product</th>
                                                        <th class="text-center">User Name</th>
                                                        <th class="text-center">Request Date</th>
                                                        <th class="text-center">Quantity</th>
                                                        <th class="text-center">Buying Price</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($all_withdraw_list as $key => $item)
                                                        <tr>
                                                            <td>{{ ++$key }}</td>
                                                            <td class="text-center">{{ SingleStockInfo($item->stock_id)->stock_name }}</td>
                                                            <td class="text-center">{{ SingleUserInfo($item->user_id)->username }}</td>
                                                            <td class="text-center">{{ \Carbon\Carbon::parse($item->selled_date)->format('d/m/Y')}}</td>
                                                            <td class="text-center">{{ $item->selled_quantiy }}</td>
                                                            <td class="text-center">{{ $item->selled_price }} TK</td>
                                                            <td class="sorting_1 text-center">
                                                                <a href="{{ route('admin.stock.sellrequest.rejected', $item->id) }}"
                                                                    class="waves-effect waves-light btn btn-denger mb-5 btn-xs">Reject</a>
                                                                <a href="{{ route('admin.stock.sellrequest.aproved', $item->id) }}"
                                                                    class="waves-effect waves-light btn btn-info mb-5 btn-xs">Aprove</a>
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
