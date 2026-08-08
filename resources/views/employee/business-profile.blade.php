@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Profile</h4>
                </div>

            </div>
        </div>


        <!-- Main content -->
        <section class="content">
            <div class="row">



                <!-- /.col -->
                <div class="col-xl-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Personal details</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <form action="{{ route('profile.business.submit') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row">

                                    <div class="col-6">


                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">NID Number</label>
                                            <div class="col-sm-8">
                                                <input name="e_nid_number" value="@if (!empty($user_business_info->e_nid_number)){{ $user_business_info->e_nid_number }}@endif" class="form-control"
                                                    type="text" placeholder="NID Number">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Nid Image</label>
                                            <div class="col-sm-8">
                                                <div class="row">
                                                    <div class="col-2" style="padding-right: 0;">
                                                        <div class="popup-gallery">
                                                            <a href="{{ asset('upload/employee') }}/@if (!empty($user_business_info->e_nid_img)){{ $user_business_info->e_nid_img }}@endif" title="">
                                                                <img  style="width: 100%;"src="{{ asset('upload/employee') }}/@if (!empty($user_business_info->e_nid_img)){{ $user_business_info->e_nid_img }}@endif" class="w-p20" alt="" />
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-10">
                                                        <input name="e_nid_img" class="form-control" type="file"
                                                        placeholder="e_nid_img">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Bath Re. Number</label>
                                            <div class="col-sm-8">
                                                <input name="e_bath_number" value="@if (!empty($user_business_info->e_bath_number)){{ $user_business_info->e_bath_number }}@endif"
                                                class="form-control" type="text" placeholder="Bath Re. Number">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Bath Re. Image</label>
                                            <div class="col-sm-8">
                                                <div class="row">
                                                    <div class="col-2" style="padding-right: 0;">
                                                        <div class="popup-gallery">
                                                            <a href="{{ asset('upload/employee') }}/@if (!empty($user_business_info->e_bath_img)){{ $user_business_info->e_bath_img }}@endif" title="">
                                                                <img style="width: 100%;" src="{{ asset('upload/employee') }}/@if (!empty($user_business_info->e_bath_img)){{ $user_business_info->e_bath_img }}@endif" class="w-p20" alt="" />
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-10">
                                                        <input name="e_bath_img" class="form-control" type="file"
                                                        placeholder="Bath Re. Image">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Office ID Number</label>
                                            <div class="col-sm-8">
                                                <input name="e_office_id_number" value="@if (!empty($user_business_info->e_office_id_number)){{ $user_business_info->e_office_id_number }}@endif"
                                                class="form-control" type="tel" placeholder="Office ID Number">
                                            </div>

                                        </div>


                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Office ID Image</label>
                                            <div class="col-sm-8">
                                                <div class="row">
                                                    <div class="col-2" style="padding-right: 0;">
                                                        <div class="popup-gallery">
                                                            <a href="{{ asset('upload/employee') }}/@if (!empty($user_business_info->e_office_id_img)){{ $user_business_info->e_office_id_img }}@endif" title="">
                                                                <img style="width: 100%;" src="{{ asset('upload/employee') }}/@if (!empty($user_business_info->e_office_id_img)){{ $user_business_info->e_office_id_img }}@endif" class="w-p20" alt="" />
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-10">
                                                        <input name="e_office_id_img" class="form-control" type="file"
                                                        placeholder="Office ID Image">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Signature</label>
                                            <div class="col-sm-8">
                                                <input name="e_signature" value="@if (!empty($user_business_info->e_signature)){{ $user_business_info->e_signature }}@endif"
                                                class="form-control" type="tel" placeholder="Signature">
                                            </div>
                                        </div>


                                    </div>
                                    <!-- /.col -->

                                    <div class="col-6">

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">CV</label>
                                            <div class="col-sm-8">
                                                <div class="row">
                                                    <div class="col-2" style="padding-right: 0;">
                                                        <div class="popup-gallery">
                                                            <a  href="{{ asset('upload/employee') }}/@if (!empty($user_business_info->e_cv)){{ $user_business_info->e_cv }}@endif" title="">
                                                                <img style="width: 100%;" src="{{ asset('upload/employee') }}/@if (!empty($user_business_info->e_cv)){{ $user_business_info->e_cv }}@endif" class="w-p20" alt="" />
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-10">
                                                        <input name="e_cv" class="form-control" type="file"
                                                        placeholder="CV">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Father Name</label>
                                            <div class="col-sm-8">
                                                <input name="e_father_name" value="@if (!empty($user_business_info->e_father_name)){{ $user_business_info->e_father_name }}@endif" class="form-control"
                                                    type="text" placeholder="Father Name">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Mother Name</label>
                                            <div class="col-sm-8">
                                                <input name="e_mother_name" value="@if (!empty($user_business_info->e_mother_name)){{ $user_business_info->e_mother_name }}@endif"
                                                    class="form-control" type="text" placeholder="Mother Name">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Gender</label>
                                            <div class="col-sm-8">
                                                <select name="e_gender" id="" class="form-control">
                                                    <option value="">Select Gender..</option>
                                                    <option  @if (!empty($user_business_info->e_gender))
                                                            @if ($user_business_info->e_gender == "Male")
                                                                selected
                                                            @endif
                                                        @endif
                                                        value="Male">Male</option>
                                                    <option
                                                    @if (!empty($user_business_info->e_gender))
                                                            @if ($user_business_info->e_gender == "Female")
                                                                selected
                                                            @endif
                                                        @endif
                                                    value="Female"
                                                    >Female</option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Age</label>
                                            <div class="col-sm-8">
                                                <input name="e_age" value="@if (!empty($user_business_info->e_age)) {{ $user_business_info->e_age }} @endif"
                                                    class="form-control" type="tel" placeholder="Age">
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.col -->

                                    <div class="col-12">
                                        <div class="form-group row text-center">
                                            <label class="col-sm-4 col-form-label"></label>
                                            <div class="col-sm-12">
                                                <button type="submit" class="btn btn-warning">Submit</button>
                                            </div>
                                        </div>
                                    </div>

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
