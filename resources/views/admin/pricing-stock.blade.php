@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Pricing</h4>
                </div>

            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">


                <!-- /.col -->
                <div class="col-xl-12 col-lg-12">
                    <div class="box">
                        <div class="box-header with-border" style="display: flex;justify-content: space-between;">
                            <h3 class="box-title">Add Price</h3>
                            <h3 class="box-title">Pricing List</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <form class="form-horizontal form-bordered" method="POST"
                                action="{{ route('admin.stock.pricing.post') }}">

                                @csrf

                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-6 control-label text-lg-start pt-2"
                                                    for="buying_price">Buying Price</label>
                                                <div class="col-lg-6">
                                                    <input type="number" class="form-control" id="buying_price" name="buying_price"
                                                        title="Enter Stock Buying Price" value="">
                                                    <span class="text-danger">
                                                        @error('buying_price')
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
                                                    <input type="number" class="form-control" id="selling_price" name="selling_price"
                                                        title="Enter Stock selling_price" value="">
                                                    <span class="text-danger">
                                                        @error('selling_price')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" value="{{ $id }}" name="stock_id">


                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-5 control-label text-lg-end pt-2"
                                                    for="email"></label>
                                                <div class="col-lg-7">
                                                    <button type="submit" class="btn btn-success">Add Price</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col -->



                                    <div class="col-lg-6">
                                        <div class="table-responsive buyorder">
                                            <table class="table no-margin min-pad-table">
                                                <thead>
                                                    <tr>
                                                        <th>SN.</th>
                                                        <th class="text-center">Date</th>
                                                        <th class="text-center">Buying Price</th>
                                                        <th class="text-center">Selling Price</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach (StockAllPricing($id) as $key => $item)
                                                    <tr>
                                                        <td>{{ ++$key }}</td>
                                                        <td class="text-center">{{ \Carbon\Carbon::parse($item->pricing_date)->format('d/m/Y')}}</td>
                                                        <td class="text-center">
                                                            @if (!empty($item->buying_price))
                                                                {{ $item->buying_price}}
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if (!empty($item->selling_price))
                                                                {{ $item->selling_price}}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach


                                                </tbody>
                                            </table>
                                        </div>

                                        {{ StockAllPricing($id)->links('pagination.index') }}
                                    </div>
                                    <!-- /.col -->


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
