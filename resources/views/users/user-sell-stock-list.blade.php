@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content">
            <div class="row">



                <div class="col-xl-12 col-12">


					<div class="box">
						<div class="box-header with-border">
						  <h4 class="box-title">Sell Stock List</h4>
							<div class="box-controls pull-right">
								Total 512
							</div>
						</div>
						<div class="box-body">
							<div class="table-responsive buyorder">
								<table class="table no-margin min-pad-table">
									<thead>
										<tr>
											<th>SN.</th>
											<th>Stock</th>
											<th>Selling Price</th>
											<th>Sell Date</th>
										</tr>
									</thead>
									<tbody>
                                        @foreach ($all_sell_stock as $key => $item)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>
                                                    {{ SingleStockInfo($item->stock_id)->stock_name }}
                                                </td>

                                                <td> {{ $item->selled_price }} TK</td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y')}}
                                                </td>
                                            </tr>
                                        @endforeach

									</tbody>
								</table>
							</div>
						</div>
					</div>


				</div>



            </div>
        </section>
        <!-- /.content -->
    </div>
</div>
@endsection
