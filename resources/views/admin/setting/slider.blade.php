@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Setting</h4>
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
                            <h3 class="box-title">Slider Setting</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <form class="form-horizontal form-bordered" method="POST"
                                action="{{ route('setting.slider.post') }}" enctype="multipart/form-data">

                                @csrf

                                <div class="row">




                                    <div class="col-xl-4">

                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="slider1_text">Slider heading</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="slider1_text"
                                                        name="slider1_text" title="Enter slider1_text"
                                                        value="{{ setting('slider1_text') }}">
                                                    <span class="text-danger">
                                                        @error('slider1_text')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="slider2_text">Slider heading</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="slider2_text"
                                                        name="slider2_text" title="slider2_text"
                                                        value="{{ setting('slider2_text') }}">
                                                    <span class="text-danger">
                                                        @error('slider2_text')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>


{{--
                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="slider3_text">Slider heading</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="slider3_text"
                                                        name="slider3_text" title="slider3_text"
                                                        value="{{ setting('slider3_text') }}">
                                                    <span class="text-danger">
                                                        @error('slider3_text')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="slider4_text">Slider heading</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="slider4_text"
                                                        name="slider4_text" title="slider4_text"
                                                        value="{{ setting('slider4_text') }}">
                                                    <span class="text-danger">
                                                        @error('slider4_text')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div> --}}

                                    </div>
                                    <!-- /.col -->

                                    <div class="col-xl-4">

                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="slider1_text">Slider Description</label>
                                                <div class="col-lg-7">

                                                        <textarea  class="form-control" name="slider1_description" id="slider1_description" cols="30" rows="1">
                                                            {{ preg_replace('/\s+/', '', setting('slider1_description')) }}
                                                        </textarea>


                                                    <span class="text-danger">
                                                        @error('slider1_description')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="slider1_text">Slider Description</label>
                                                <div class="col-lg-7">


                                                        <textarea  class="form-control" name="slider2_description" id="slider2_description" cols="30" rows="1">
                                                            {{ setting('slider2_description') }}
                                                        </textarea>
                                                    <span class="text-danger">
                                                        @error('slider2_description')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

{{--
                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="slider1_text">Slider Description</label>
                                                <div class="col-lg-7">

                                                        <textarea  class="form-control" name="slider3_description" id="slider3_description" cols="30" rows="1">
                                                            {{ setting('slider3_description') }}
                                                        </textarea>
                                                    <span class="text-danger">
                                                        @error('slider3_description')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="slider1_text">Slider Description</label>
                                                <div class="col-lg-7">

                                                    <textarea  class="form-control" name="slider4_description" id="slider4_description" cols="30" rows="1">
                                                        {{ setting('slider4_description') }}
                                                    </textarea>
                                                    <span class="text-danger">
                                                        @error('slider4_description')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div> --}}



                                    </div>
                                    <!-- /.col -->

                                    <div class="col-xl-4">



                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="slider1_img">Slider Img</label>
                                                <div class="col-lg-7">
                                                    <input type="file" class="form-control" id="slider1_img"
                                                        name="slider1_img" title="Enter slider1_img"
                                                        value="">
                                                        Images size : 1920 x 820
                                                    <span class="text-danger">
                                                        @error('slider1_img')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="slider2_img">Slider Img</label>
                                                <div class="col-lg-7">
                                                    <input type="file" class="form-control" id="slider2_img"
                                                        name="slider2_img" title="Enter slider2_img"
                                                        value="">
                                                        Images size : 1920 x 820
                                                    <span class="text-danger">
                                                        @error('slider2_img')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

{{--
                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="slider3_img">Slider Img</label>
                                                <div class="col-lg-7">
                                                    <input type="file" class="form-control" id="slider3_img"
                                                        name="slider3_img" title="Enter slider3_img"
                                                        value="">
                                                        Images size : 1920 x 820
                                                    <span class="text-danger">
                                                        @error('slider3_img')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="slider4_img">Slider Img</label>
                                                <div class="col-lg-7">
                                                    <input type="file" class="form-control" id="slider4_img"
                                                        name="slider4_img" title="Enter slider4_img"
                                                        value="">
                                                        Images size : 1920 x 820
                                                    <span class="text-danger">
                                                        @error('slider4_img')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div> --}}


                                    </div>
                                    <!-- /.col -->

                                    <div class="col-12">

                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-5 control-label text-lg-end pt-2"
                                                    for="email"></label>
                                                <div class="col-lg-7">
                                                    <button type="submit" class="btn btn-success">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <!-- /.row -->
                            </form>
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
