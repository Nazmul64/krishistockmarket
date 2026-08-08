@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Employee Profile</h4>
                </div>

            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">

                <!-- /.col -->
                <div class="col-xl-12 col-lg-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Profile details</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">

                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Total Referal User</label>
                                            <div class="col-sm-7">
                                                <label class="col-sm-5 col-form-label">
                                                    <a href="{{ route('admin.employee.referaluser', $user_info->id) }}">
                                                        @php
                                                        $all_user= App\Models\User::where('role', 'user')->where('referral_id', $user_info->id)->get();
                                                        @endphp
                                                        View List  ({{ count($all_user) }})
                                                    </a>
                                                </label>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Profile Picture</label>
                                            <div class="col-sm-7">
                                                <div class="col-2" style="padding-right: 0;">
                                                    <div class="popup-gallery">
                                                        <a  href="{{ asset('upload/userprofile') }}/@if (!empty($user_info->avatar)){{ $user_info->avatar }}@endif" title="">
                                                            <img style="width: 100%;" src="{{ asset('upload/userprofile') }}/@if (!empty($user_info->avatar)){{ $user_info->avatar }}@endif" class="w-p20" alt="" />
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Name</label>
                                            <div class="col-sm-7">
                                                <label class="col-sm-5 col-form-label">
                                                    @if (!empty($user_info->name)){{ $user_info->name}}@endif
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">User Name</label>
                                            <div class="col-sm-7">
                                                <label class="col-sm-5 col-form-label">
                                                    @if (!empty($user_info->username)){{ $user_info->username }}@endif
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Email Adress</label>
                                            <div class="col-sm-7">
                                                <label class="col-sm-5 col-form-label">
                                                    @if (!empty($user_info->email)){{ $user_info->email}}@endif
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Phone Number</label>
                                            <div class="col-sm-7">
                                                <label class="col-sm-5 col-form-label">
                                                    @if (!empty($user_info->phone)){{ $user_info->phone}}@endif
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Father Name</label>
                                            <div class="col-sm-7">
                                                <label class="col-sm-5 col-form-label">
                                                    @if (!empty($employeeInfo->e_father_name)){{ $employeeInfo->e_father_name}}@endif
                                                </label>
                                            </div>
                                        </div>





                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Mother Name</label>
                                            <div class="col-sm-7">
                                                <label class="col-sm-5 col-form-label">
                                                    @if (!empty($employeeInfo->e_mother_name)){{ $employeeInfo->e_mother_name}}@endif
                                                </label>
                                            </div>
                                        </div>




                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Gender</label>
                                            <div class="col-sm-7">
                                                <label class="col-sm-5 col-form-label">
                                                    @if (!empty($employeeInfo->e_gender)){{ $employeeInfo->e_gender}}@endif
                                                </label>
                                            </div>
                                        </div>





                                    </div>
                                    <!-- /.col -->


                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Age</label>
                                            <div class="col-sm-7">
                                                <label class="col-sm-5 col-form-label">
                                                    @if (!empty($employeeInfo->e_age)){{ $employeeInfo->e_age}}@endif
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Nid Number</label>
                                            <div class="col-sm-7">
                                                <label class="col-sm-5 col-form-label">
                                                    @if (!empty($employeeInfo->e_nid_number)){{ $employeeInfo->e_nid_number}}@endif
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Nid Image</label>
                                            <div class="col-sm-7">
                                                <label class="col-sm-5 col-form-label">
                                                    <div class="col-2" style="padding-right: 0;">
                                                        <div class="popup-gallery">
                                                            <a  href="{{ asset('upload/employee') }}/@if (!empty($employeeInfo->e_nid_img)){{ $employeeInfo->e_nid_img}}@endif" title="">
                                                                <img style="width: 100%;" src="{{ asset('upload/employee') }}/@if (!empty($employeeInfo->e_nid_img)){{ $employeeInfo->e_nid_img}}@endif" class="w-p20" alt="" />
                                                            </a>
                                                        </div>
                                                    </div>

                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Bath Number</label>
                                            <div class="col-sm-7">
                                                <label class="col-sm-5 col-form-label">
                                                    @if (!empty($employeeInfo->e_bath_number)){{ $employeeInfo->e_bath_number}}@endif
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Bath Image</label>
                                            <div class="col-sm-7">
                                                <label class="col-sm-5 col-form-label">
                                                    <div class="col-2" style="padding-right: 0;">
                                                        <div class="popup-gallery">
                                                            <a  href="{{ asset('upload/employee') }}/@if (!empty($employeeInfo->e_bath_img)){{ $employeeInfo->e_bath_img}}@endif" title="">
                                                                <img style="width: 100%;" src="{{ asset('upload/employee') }}/@if (!empty($employeeInfo->e_bath_img)){{ $employeeInfo->e_bath_img}}@endif" class="w-p20" alt="" />
                                                            </a>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Office Id Number</label>
                                            <div class="col-sm-7">
                                                <label class="col-sm-5 col-form-label">
                                                    @if (!empty($employeeInfo->e_office_id_number)){{ $employeeInfo->e_office_id_number}}@endif
                                                </label>
                                            </div>
                                        </div>






                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Office Id Image</label>
                                            <div class="col-sm-7">
                                                <label class="col-sm-5 col-form-label">
                                                    <div class="col-2" style="padding-right: 0;">
                                                        <div class="popup-gallery">
                                                            <a  href="{{ asset('upload/employee') }}/@if (!empty($employeeInfo->e_office_id_img)){{ $employeeInfo->e_office_id_img}}@endif" title="">
                                                                <img style="width: 100%;" src="{{ asset('upload/employee') }}/@if (!empty($employeeInfo->e_office_id_img)){{ $employeeInfo->e_office_id_img}}@endif" class="w-p20" alt="" />
                                                            </a>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>





                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Signature</label>
                                            <div class="col-sm-7">
                                                <label class="col-sm-5 col-form-label">
                                                    @if (!empty($employeeInfo->e_signature)){{ $employeeInfo->e_signature}}@endif
                                                </label>
                                            </div>
                                        </div>





                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Cv</label>
                                            <div class="col-sm-7">
                                                <label class="col-sm-5 col-form-label">
                                                    <div class="col-2" style="padding-right: 0;">
                                                        <div class="popup-gallery">
                                                            <a  href="{{ asset('upload/employee') }}/@if (!empty($employeeInfo->e_cv)){{ $employeeInfo->e_cv}}@endif" title="">
                                                                <img style="width: 100%;" src="{{ asset('upload/employee') }}/@if (!empty($employeeInfo->e_cv)){{ $employeeInfo->e_cv}}@endif" class="w-p20" alt="" />
                                                            </a>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>









                                    </div>
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


<script>
   const myDiv = document.getElementById('myDiv');
    const myButton = document.getElementById('myButton');

    myButton.addEventListener('click', () => {
    const textToCopy = myDiv.textContent;
    navigator.clipboard.writeText(textToCopy)
        .then(() => {
            alert('Link Copy Successfully');
        })
        .catch((err) => {
            alert('Failed to copy link: ', err);
        });
    });
</script>
@endsection
