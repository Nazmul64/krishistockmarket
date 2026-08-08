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

                <div class="col-xl-4 col-lg-5">
                    <!-- Profile Image -->
                    <div class="box">
                        <div class="box-body box-profile">
                            <img class="rounded img-fluid mx-auto d-block max-w-150"
                                src="{{ asset('upload/userprofile') }}/{{ Auth::user()->avatar }}"
                                alt="User profile picture">

                            <h3 class="profile-username text-center mb-0">{{ Auth::user()->name }}</h3>

                            <h4 class="text-center mt-0">
                                <i class="fa fa-envelope-o me-10"></i>
                                {{ Auth::user()->email }}
                            </h4>

                            <div class="row social-states">
                                <div class="col-6 text-end"><a href="#" class="link text-white"><i
                                            class="ion ion-ios-people-outline"></i> 254</a></div>
                                <div class="col-6 text-start">
                                    <a href="#" class="link text-white"><i class="ion ion-images"></i> 54</a></div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="media-list media-list-hover media-list-divided w-p100 mt-30">
                                        <h4 class="media media-single p-15">
                                            <i class="fa fa-arrow-circle-o-right me-10"></i>
                                            <span class="title">
                                                My Profile
                                            </span>
                                        </h4>
                                    </div>
                                </div>
                            </div>

                            <style>
                                .ref_input{
                                    width: 100%;
                                    margin-top: 15px;
                                    border: 0;
                                    text-align: center;
                                    font-weight: 900;
                                    color: red;
                                }
                                .ref_input:focus{
                                    border: 0;
                                    outline: 0;
                                }
                            </style>

                            @if (Auth::user()->role == "employee")

                                <div class="row">
                                    <div class="col-12">
                                        <div class="media-list media-list-hover media-list-divided w-p100 mt-30" style="display: flex;justify-content: space-around;">
                                            <h4 class="media media-single" style="padding: 0">
                                                <i class="fa fa-arrow-circle-o-right me-10"></i>
                                                <span class="title">
                                                    My Referal Link
                                                </span>
                                            </h4>
                                            <button id="myButton" class="btn btn-sm btn-success">Copy Link</button>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="ref_input" id="myDiv">
                                            {{ route('/') }}/register?ref={{ Auth::user()->username }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>

                <!-- /.col -->
                <div class="col-xl-8 col-lg-7">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Personal details</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <form action="{{ route('profile.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Profile Picture</label>
                                            <div class="col-sm-10">
                                                <input name="avatar" class="form-control" type="file"
                                                    placeholder="Profile Picture">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Name</label>
                                            <div class="col-sm-10">
                                                <input name="name" value="{{ Auth::user()->name }}" class="form-control"
                                                    type="text" placeholder="Name">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">User Name</label>
                                            <div class="col-sm-10">
                                                <input name="username" value="{{ Auth::user()->username }}"
                                                    class="form-control" type="text" placeholder="username">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Email Adress</label>
                                            <div class="col-sm-10">
                                                <input name="email" value="{{ Auth::user()->email }}"
                                                    class="form-control" type="email" placeholder="email">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Phone Number</label>
                                            <div class="col-sm-10">
                                                <input name="phone" value="{{ Auth::user()->phone }}"
                                                    class="form-control" type="tel" placeholder="123 456 7890">
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

                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Change Password</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-12">

                                    <form action="{{ route('profile.password.change') }}" method="POST">
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Old Password</label>
                                            <div class="col-sm-10">
                                                <input name="oldpassword" class="form-control" type="text"
                                                    placeholder="Old Password">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">New Password</label>
                                            <div class="col-sm-10">
                                                <input name="newpassword" class="form-control" type="text"
                                                    placeholder="New Password">
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10">
                                                <button type="submit" class="btn btn-warning">Save Change</button>
                                            </div>
                                        </div>
                                    </form>

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
