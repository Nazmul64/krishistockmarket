@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Employee</h4>
                </div>

            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">

                <!-- /.col -->
                <div class="col-xl-6 col-lg-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add Employee</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <form action="{{ route('admin.employee.post') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-12">

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Name</label>
                                            <div class="col-sm-10">
                                                <input name="name" value="" class="form-control"
                                                    type="text" placeholder="Name">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">User Name</label>
                                            <div class="col-sm-10">
                                                <input name="username" value=""
                                                    class="form-control" type="text" placeholder="username">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Phone Number</label>
                                            <div class="col-sm-10">
                                                <input name="phone" value=""
                                                    class="form-control" type="tel" placeholder="123 456 7890">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Password</label>
                                            <div class="col-sm-10">
                                                <input name="password" class="form-control" type="text"
                                                    placeholder="Password">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10">
                                                <button type="submit" class="btn btn-warning">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                </div>
                            </form>
                            <!-- /.row -->

                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->


                </div>
                <!-- /.col -->


                <!-- /.col -->
                <div class="col-lg-6">
                    <div class="table-responsive buyorder">
                        <table class="table no-margin min-pad-table">
                            <thead>
                                <tr>
                                    <th>SN.</th>
                                    <th>Name</th>
                                    <th>User Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $all_employee= App\Models\User::where('role', 'employee')->get();
                                @endphp
                                @foreach ($all_employee as $key => $item)

                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->username}}</td>
                                    <td class="sorting_1">
                                        <a href="{{ route('admin.employee.destroy', $item->id) }}"
                                            class="waves-effect waves-light btn btn-danger mb-5 btn-xs">Delete</a>



                                        <a href="{{ route('admin.employee.permissions', $item->id) }}"
                                            class="waves-effect waves-light btn btn-warning mb-5 btn-xs"><i class="ti-key me-1"></i> Permissions</a>

                                        <a href="{{ route('admin.employee.edit', $item->id) }}"
                                            class="waves-effect waves-light btn btn-info mb-5 btn-xs">Edit</a>

                                        <a href="{{ route('admin.employee.view', $item->id) }}"
                                            class="waves-effect waves-light btn btn-info mb-5 btn-xs">View</a>
                                    </td>
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
