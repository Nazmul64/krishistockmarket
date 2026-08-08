@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Withdraw List</h4>
                </div>

            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">

                <!-- /.col -->
                <div class="col-lg-12">
                    <div class="table-responsive buyorder">
                        <table class="table no-margin min-pad-table">
                            <thead>
                                <tr>
                                    <th>SN.</th>
                                    <th>Method Name</th>
                                    <th>Recive Number</th>
                                    <th>Withdraw Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $all_withdraw= App\Models\Withdraw::all();
                                @endphp
                                @foreach ($all_withdraw as $key => $item)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>
                                            @if (!empty(WithdrawMethod($item->method_id)))
                                                {{ WithdrawMethod($item->method_id)->pay_s_name }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (!empty(WithdrawMethod($item->method_id)))
                                                {{ WithdrawMethod($item->method_id)->pay_s_number }}
                                            @endif
                                        </td>
                                        <td>{{ $item->amount}}</td>
                                        <td>{{ $item->status}}</td>

                                        @if ($item->status == "pending")
                                            <td class="sorting_1">
                                                <a href="{{ route('admin.all.withdraw.reject', $item->id) }}"
                                                    class="waves-effect waves-light btn btn-denger mb-5 btn-xs">Reject</a>

                                                    <a href="{{ route('admin.all.withdraw.aprove', $item->id) }}"
                                                    class="waves-effect waves-light btn btn-info mb-5 btn-xs">Aprove</a>
                                            </td>
                                        @endif

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.col -->

            </div>
        </section>
        <!-- /.content -->

    </div>


</div>
@endsection


