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
                            <h3 class="box-title">Pricing Tabel</h3>
                            <h3 class="box-title form-group">
                                <input type="text" class="form-control" placeholder="Serch Product">
                            </h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">

                                <div class="row">


                                    <div class="col-lg-12">
                                        <div class="table-responsive buyorder">
                                            <table class="table no-margin min-pad-table">
                                                <thead>
                                                    <tr>
                                                        <th>SN.</th>
                                                        <th class="text-center">Product</th>
                                                        <th class="text-center">Total Pricing</th>
                                                        <th class="text-center">Last Buying Price</th>
                                                        <th class="text-center">Last Selling Price</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($all_stock as $key => $item)
                                                        <tr>
                                                            <td>{{ ++$key }}</td>
                                                            <td class="text-center">{{ $item->stock_name }}</td>
                                                            <td class="text-center">
                                                                @if (!empty(count(StockAllPricing($item->id))))
                                                                    {{ count(StockAllPricing($item->id)) }}
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                @if (!empty(StockLastPricing($item->id)->buying_price))
                                                                {{ StockLastPricing($item->id)->buying_price }}
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                @if (!empty(StockLastPricing($item->id)->selling_price))
                                                                    {{ StockLastPricing($item->id)->selling_price }}
                                                                @endif
                                                            </td>
                                                            <td class="sorting_1 text-center">
                                                                <a href="{{ route('admin.stock.add.price', $item->id) }}"
                                                                    class="waves-effect waves-light btn btn-info mb-5 btn-xs">Add Price</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- /.col -->


                                </div>
                                <!-- /.row -->

                                {{ $all_stock->links('pagination.index') }}
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
