@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Stock</h4>
                </div>

            </div>
        </div>
        <style>
            .popup-gallery{
                position: relative;
                display: initial;
            }
            .delete_btn {
                position: absolute;
                width: 30px;
                height: 30px;
                top: -65px;
                right: 9px;
                color: #fff;
                background: #00000094;
                line-height: 30px;
                text-align: center;
                border-radius: 50%;
            }

        </style>
        <!-- Main content -->
        <section class="content">
            <div class="row">


                <!-- /.col -->
                <div class="col-xl-12 col-lg-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Update Stock</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <form class="form-horizontal form-bordered" method="POST"
                                action="{{ route('admin.stock.edit.post', $stock_info->id) }}" enctype="multipart/form-data">

                                @csrf

                                <div class="row">
                                    <div class="col-xl-6">

                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-6 control-label text-lg-start pt-2"
                                                    for="stock_name">Stock Name</label>
                                                <div class="col-lg-6">
                                                    <input type="text" class="form-control" required id="stock_name" name="stock_name"
                                                        title="Enter Stock name" value="{{ $stock_info->stock_name }}">
                                                    <span class="text-danger">
                                                        @error('stock_name')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        {{--
                                        images --}}
                                        <div class="form-group pb-4">
                                            <div class="row" style="margin-bottom: 15px">
                                                <label class="col-lg-6 control-label text-lg-start pt-2"
                                                    for="Images">Stock Images(multiple)</label>
                                                <div class="col-lg-6">
                                                    <input multiple type="file" class="form-control" id="Images" name="stock_images[]">
                                                    <span class="text-danger">
                                                        @error('stock_images')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col" style="margin-bottom: 5px;">
                                                @foreach ($images as $image)
                                                    <div class="popup-gallery" href="{{ asset('upload/stock_images') }}/{{ $image->image }}"
                                                        title="{{ $stock_info->stock_name }}">
                                                        <img style="margin-right: 5px;cursor: pointer;" src="{{ asset('upload/stock_images') }}/{{ $image->image }}" class="w-p20 hover_close_btn" alt="" />
                                                        <a class="delete_btn" href="{{ route('admin.stock.image.delete', $image->id) }}">X</a>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.col -->





                                    <div class="col-xl-6">

                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-6 control-label text-lg-start pt-2"
                                                    for="stock_quantity">Stock Quantiy</label>
                                                <div class="col-lg-6">
                                                    <input type="text" class="form-control" min="1" required id="stock_quantity" name="stock_quantity"
                                                        title="Enter Stock Quantity" value="{{ $stock_info->stock_quantity }}">
                                                    <span class="text-danger">
                                                        @error('stock_quantity')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group pb-4">
                                            <div class="row">
                                                <label class="col-lg-12 control-label text-lg-start pt-2 fw-bold"
                                                    for="stock_description">Stock Description (স্টকের বিবরণী)</label>
                                                <div class="col-lg-12 mt-2">
                                                    <textarea name="stock_description" id="stock_description" class="form-control editor" rows="5">{{ $stock_info->description }}</textarea>
                                                     <span class="text-danger">
                                                         @error('stock_description')
                                                         {{ $message }}
                                                         @enderror
                                                     </span>
                                                 </div>
                                             </div>
                                         </div>


                                    </div>
                                    <!-- /.col -->

                                    <div class="col-12">

                                        <div class="form-group pb-4 text-center">
                                            <button type="submit" class="btn btn-success btn-lg px-5"><i class="fa fa-save me-2"></i> Update Stock</button>
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
            height: 200,
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
