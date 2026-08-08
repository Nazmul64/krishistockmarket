@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">User</h4>
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
                            <h3 class="box-title">Edit User</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <form action="{{ route('admin.employee.edit.post') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-12">

                                        <input type="hidden" value="{{ $employee_info->id }}" name="user_id">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Name</label>
                                            <div class="col-sm-10">
                                                <input name="name" value="{{ $employee_info->name }}" class="form-control"
                                                    type="text" placeholder="Name">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">User Name</label>
                                            <div class="col-sm-10">
                                                <input name="username" value="{{ $employee_info->username }}"
                                                    class="form-control" type="text" placeholder="username">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Phone Number</label>
                                            <div class="col-sm-10">
                                                <input name="phone" value="{{ $employee_info->phone }}"
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
                                                <button type="submit" class="btn btn-warning">Update</button>
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


            </div>
        </section>
        <!-- /.content -->

    </div>


</div>
@endsection
