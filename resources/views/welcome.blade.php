@extends('layouts.auth.app')

@section('content')

    <div class="container h-p100">
        <div class="row align-items-center justify-content-md-center h-p100">

            <div class="col-12">
                <div class="row justify-content-center g-0" style="margin-top: 25px;">

                    <div class="col-lg-5 col-md-5 col-12">
                        <div class="bg-white rounded10 shadow-lg">
                            <div class="content-top-agile p-20 pb-0">
                                <h2 class="text-primary">Let's Get Started</h2>
                                <p class="mb-0">Sign in to continue to WebkitX.</p>
                            </div>
                            <div class="p-40">

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-text bg-transparent">
                                                <i class="ti-user"></i>
                                            </span>
                                            <input type="text" name="username" class="form-control ps-15 bg-transparent"
                                                placeholder="Username">
                                        </div>

                                        @error('username')
                                        <div class="form-control-feedback">
                                            <code>{{ $message }}</code>
                                        </div>
                                    @enderror
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-text  bg-transparent">
                                                <i class="ti-lock"></i>
                                            </span>
                                            <input name="password" type="password"
                                                class="form-control ps-15 bg-transparent" placeholder="Password">
                                        </div>
                                        @error('password')
                                            <div class="form-control-feedback">
                                                <code>{{ $message }}</code>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="checkbox">
                                                <input name="remember" type="checkbox" id="basic_checkbox_1">
                                                <label for="basic_checkbox_1">Remember Me</label>
                                            </div>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-6">
                                            <div class="fog-pwd text-end">

                                                @if (Route::has('password.request'))
                                                <a href="{{ route('password.request') }}" class="hover-warning">
                                                    <i class="ion ion-locked"></i>
                                                    Forgot pwd?
                                                </a>
                                                @endif

                                                <br>
                                            </div>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-danger mt-10">SIGN IN</button>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                </form>


                                <div class="text-center">
                                    <p class="mt-15 mb-0">Don't have an account?
                                        <a href="{{ route('register') }}" class="text-warning ms-5">Sign Up</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="mt-20 text-white">- Sign With -</p>
                            <p class="gap-items-2 mb-20">
                                <a class="btn btn-social-icon btn-round btn-facebook" href="#">
                                    <i class="fa fa-facebook"></i>
                                </a>
                                <a class="btn btn-social-icon btn-round btn-twitter" href="#">
                                    <i class="fa fa-twitter"></i>
                                </a>
                                <a class="btn btn-social-icon btn-round btn-instagram" href="#">
                                    <i class="fa fa-instagram"></i>
                                </a>
                            </p>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection
