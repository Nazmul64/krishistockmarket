@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Payment System</h4>
                </div>

            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="col-xl-12 col-lg-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Payment System Info :</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-lg-6">

                                    <form action="{{ route('admin.payment.post') }}" method="POST">
                                        @csrf

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Select Type</label>
                                        <div class="col-sm-9">
                                            <select name="pay_s_name" class="form-control" required>
                                                    <option value="">Select Payment Method...</option>
                                                    <option value="bKash">bKash (বিকাশ)</option>
                                                    <option value="Nagad">Nagad (নগদ)</option>
                                                    <option value="Rocket">Rocket (রকেট)</option>
                                                    <option value="Bank Transfer">Bank Transfer (ব্যাংক ট্রান্সফার)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Payment Number</label>
                                        <div class="col-sm-9">
                                            <input name="pay_s_number" class="form-control" type="text" placeholder="+8801680******">
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <div class="col-sm-12 text-center">
                                            <button type="submit" class="btn btn-warning">Add</button>
                                        </div>
                                    </div>
                                </form>

                                </div>


                                <!-- /.col -->
                                <div class="col-lg-6">
                                    <div class="table-responsive buyorder">
                                        <table class="table no-margin min-pad-table">
                                            <thead>
                                                <tr>
                                                    <th>SN.</th>
                                                    <th>Name</th>
                                                    <th>Number</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $all_pay_s= App\Models\SitePaymentSystem::all();
                                                @endphp
                                                @foreach ($all_pay_s as $key => $item)

                                                <tr>
                                                    <td>{{ ++$key }}</td>
                                                    <td>{{ $item->pay_s_name }}</td>
                                                    <td>{{ $item->pay_s_number }}</td>
                                                    <td class="sorting_1">
                                                        <a href="{{ route('admin.payment.destroy',$item->id) }}" class="waves-effect waves-light btn btn-danger mb-5 btn-xs">Delete</a>
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
