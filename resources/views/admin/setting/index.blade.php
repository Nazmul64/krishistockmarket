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
                            <h3 class="box-title">Site Setting</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <form class="form-horizontal form-bordered" method="POST"
                                action="{{ route('setting.update') }}" enctype="multipart/form-data">

                                @csrf

                                <div class="row">


                                    <div class="col-xl-6">


                                        <div class="form-group pb-4">

                                            <div class="row ">
                                                <label class="col-lg-6 control-label text-lg-start pt-2"
                                                    for="title">Title</label>
                                                <div class="col-lg-6">
                                                    <input type="text" class="form-control" id="title" name="title"
                                                        title="Enter Site Title" value="{{ setting('title') }}">
                                                    <span class="text-danger">
                                                        @error('title')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>

                                        </div>


                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-3 control-label text-lg-start pt-2"
                                                    for="logo">Logo</label>
                                                <div class="col-lg-5">
                                                    <input type="file" class="form-control" id="logo" name="logo">
                                                    <span class="text-danger">
                                                        @error('logo')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                                <div class="col-lg-4">
                                                    <img src="{{ asset('upload/images/backend/logo') }}/{{ setting('logo') }}"
                                                        alt="" style="width:30%; margin-top:5px">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-3 control-label text-lg-start pt-2"
                                                    for="favicon">Favicon</label>
                                                <div class="col-lg-5">
                                                    <input type="file" class="form-control" id="favicon" name="favicon">
                                                    <span class="text-danger">
                                                        @error('favicon')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                                <div class="col-lg-4">
                                                    <img src="{{ asset('upload/images/backend/logo') }}/{{ setting('favicon') }}"
                                                        alt="" style="width:30%; margin-top:5px">
                                                </div>
                                            </div>


                                        </div>









                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-5 control-label text-lg-start pt-2"
                                                    for="address1">Address Line one</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="address1"
                                                        name="address1" title="Enter Address"
                                                        value="{{ setting('address1') }}">
                                                    <span class="text-danger">
                                                        @error('address1')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>




                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-5 control-label text-lg-start pt-2"
                                                    for="address2">Address Line tow</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="address2"
                                                        name="address2" title="Enter Address"
                                                        value="{{ setting('address2') }}">
                                                    <span class="text-danger">
                                                        @error('address2')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>




                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-5 control-label text-lg-start pt-2"
                                                    for="phone1">Phone one</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="phone1"
                                                        name="phone1" title="Enter Phone one"
                                                        value="{{ setting('phone1') }}">
                                                    <span class="text-danger">
                                                        @error('phone1')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>




                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-5 control-label text-lg-start pt-2"
                                                    for="facbook_link">Facebook Link</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="facbook_link"
                                                        name="facbook_link" title="Facebook Link"
                                                        value="{{ setting('facbook_link') }}">
                                                    <span class="text-danger">
                                                        @error('facbook_link')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-5 control-label text-lg-start pt-2"
                                                    for="linkedin_link">Linkedin Link</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="linkedin_link"
                                                        name="linkedin_link" title="Enter Phone one"
                                                        value="{{ setting('linkedin_link') }}">
                                                    <span class="text-danger">
                                                        @error('linkedin_link')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-5 control-label text-lg-start pt-2"
                                                    for="twitter_link">Twitter Link</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="twitter_link"
                                                        name="twitter_link" title="Enter Phone one"
                                                        value="{{ setting('twitter_link') }}">
                                                    <span class="text-danger">
                                                        @error('twitter_link')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <!-- /.col -->









                                    <div class="col-xl-6">
                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="phone2">Phone tow</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="phone2"
                                                        name="phone2" title="Enter Phone tow"
                                                        value="{{ setting('phone2') }}">
                                                    <span class="text-danger">
                                                        @error('phone2')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>








                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="email1">Email Address one</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="email1"
                                                        name="email1" title="Enter Email Address"
                                                        value="{{ setting('email1') }}">
                                                    <span class="text-danger">
                                                        @error('email1')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>






                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="email2">Enter Address</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="email2"
                                                        name="email2" title="Enter eMAIL Address"
                                                        value="{{ setting('email2') }}">
                                                    <span class="text-danger">
                                                        @error('email2')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="about_us_text">About text</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="about_us_text"
                                                        name="about_us_text" title="About text"
                                                        value="{{ setting('about_us_text') }}">
                                                    <span class="text-danger">
                                                        @error('about_us_text')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="about_us_text2">About text tow</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="about_us_text2"
                                                        name="about_us_text2" title="About text tow"
                                                        value="{{ setting('about_us_text2') }}">
                                                    <span class="text-danger">
                                                        @error('about_us_text2')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>


                                    <div class="col-12 my-3">
                                        <hr>
                                        <h4 class="text-primary fw-bold mb-3"><i class="fa fa-file-text-o me-2"></i> Page Content Settings (এবাউট, প্রাইভেসি ও টার্মস সেটিংস)</h4>
                                    </div>

                                    <div class="col-xl-12 mb-3">
                                        <div class="form-group pb-3">
                                            <label class="form-label fw-bold" for="about_us_full_text">About Us Details (আমাদের সম্পর্কে বিস্তারিত)</label>
                                            <textarea class="form-control editor" id="about_us_full_text" name="about_us_full_text" rows="5" placeholder="Enter About Us details content...">{{ setting('about_us_full_text') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-xl-12 mb-3">
                                        <div class="form-group pb-3">
                                            <label class="form-label fw-bold" for="terms_conditions">Terms & Conditions (টার্মস এন্ড কন্ডিশনস)</label>
                                            <textarea class="form-control editor" id="terms_conditions" name="terms_conditions" rows="6" placeholder="Enter Terms & Conditions content...">{{ setting('terms_conditions') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-xl-12 mb-3">
                                        <div class="form-group pb-3">
                                            <label class="form-label fw-bold" for="privacy_policy">Privacy Policy (প্রাইভেসি পলিসি)</label>
                                            <textarea class="form-control editor" id="privacy_policy" name="privacy_policy" rows="6" placeholder="Enter Privacy Policy content...">{{ setting('privacy_policy') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group pb-4 text-center">
                                            <button type="submit" class="btn btn-success btn-lg px-5"><i class="fa fa-save me-2"></i> Update Settings</button>
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

@section('script')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.editor').summernote({
            height: 220,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'italic', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });
</script>
@endsection
