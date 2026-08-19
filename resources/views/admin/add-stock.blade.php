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

        <!-- Main content -->
        <section class="content">
            <div class="row">


                <!-- /.col -->
                <div class="col-xl-12 col-lg-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add Stock</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <form class="form-horizontal form-bordered" method="POST"
                                action="{{ route('admin.stock.post') }}" enctype="multipart/form-data">

                                @csrf

                                <div class="row">
                                    <div class="col-xl-6">

                                        <!-- Quick Stock Package Presets -->
                                        <div class="form-group pb-4">
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <label class="form-label fw-bold mb-0">Quick Stock Package Presets (দ্রুত স্টক প্যাকেজ সিলেক্ট করুন):</label>
                                                <a href="{{ route('admin.stock_preset.index') }}" class="btn btn-outline-info btn-xs" title="Manage Stock Presets"><i class="fa fa-cog me-1"></i> ম্যানেজ প্রিসেট</a>
                                            </div>
                                            <div class="d-flex flex-wrap gap-2 mt-2">
                                                @forelse($presets as $preset)
                                                    <button type="button" class="btn btn-outline-success btn-sm preset-btn"
                                                            data-name="{{ $preset->package_name }}"
                                                            data-price="{{ (float)$preset->price }}"
                                                            data-quantity="{{ $preset->quantity ?? 10 }}">
                                                        {{ $preset->title }}
                                                    </button>
                                                @empty
                                                    <span class="text-muted small">কোনো প্রিসেট প্যাকেজ নেই। <a href="{{ route('admin.stock_preset.index') }}">প্রিসেট যোগ করুন</a></span>
                                                @endforelse
                                            </div>
                                        </div>

                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-6 control-label text-lg-start pt-2"
                                                    for="stock_name">Stock Name</label>
                                                <div class="col-lg-6">
                                                    <input type="text" class="form-control" required id="stock_name" name="stock_name"
                                                        title="Enter Stock name" value="">
                                                    <span class="text-danger">
                                                        @error('stock_name')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group pb-4">
                                            <div class="row">
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
                                        </div>

                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-6 control-label text-lg-start pt-2"
                                                    for="selling_price">Selling Price</label>
                                                <div class="col-lg-6">
                                                    <input type="text" class="form-control" min="1" id="selling_price" name="selling_price"
                                                        title="Enter Stock selling Price" value="">
                                                    <span class="text-danger">
                                                        @error('selling_price')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-6 control-label text-lg-start pt-2"
                                                    for="buying_price">Buying Price</label>
                                                <div class="col-lg-6">
                                                    <input type="text" class="form-control" min="1" required id="buying_price" name="buying_price"
                                                        title="Enter buying_price" value="">
                                                    <span class="text-danger">
                                                        @error('buying_price')
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
                                                <label class="col-lg-6 control-label text-lg-start pt-2"
                                                    for="stock_quantity">Stock Quantity</label>
                                                <div class="col-lg-6">
                                                    <input type="text" class="form-control" min="1" required id="stock_quantity" name="stock_quantity"
                                                        title="Enter Stock Quantity" value="">
                                                    
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox" id="is_unlimited" name="is_unlimited" value="1">
                                                        <label class="form-check-label fw-bold text-success" for="is_unlimited" style="cursor: pointer;">
                                                            <i class="fa fa-infinity me-1"></i> Unlimited Stock (আনলিমিটেড স্টক)
                                                        </label>
                                                    </div>

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
                                                    <textarea name="stock_description" id="stock_description" class="form-control editor" rows="5"></textarea>
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
                                            <button type="submit" class="btn btn-success btn-lg px-5"><i class="fa fa-plus me-2"></i> Add Stock</button>
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
        function toggleUnlimited() {
            if ($('#is_unlimited').is(':checked')) {
                $('#stock_quantity').val('').prop('disabled', true).removeAttr('required');
            } else {
                $('#stock_quantity').prop('disabled', false).attr('required', 'required');
            }
        }

        $('#is_unlimited').on('change', toggleUnlimited);
        toggleUnlimited();

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
        $('.preset-btn').on('click', function() {
            var name = $(this).data('name');
            var price = $(this).data('price');
            var qty = $(this).data('quantity') || 10;
            $('#stock_name').val(name);
            $('#selling_price').val(price);
            $('#buying_price').val(price);
            if (!$('#is_unlimited').is(':checked')) {
                $('#stock_quantity').val(qty);
            }
        });
    });
</script>
@endsection
