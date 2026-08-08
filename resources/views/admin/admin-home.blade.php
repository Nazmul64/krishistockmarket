@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content">
            <div class="row">



                <div class="col-xl-12 col-12">
                    <div class="row">


                        <div class="col-12 col-md-6 col-xxxl-3 col-lg-4">
                            <div class="box box-body pull-up">
                                <div class="d-flex justify-content-between">
                                    <div class="media align-items-center p-0">
                                        <div>
                                            <h3 class="no-margin text-bold">Total User's</h3>
                                        </div>
                                    </div>
                                    <div>
                                        <div>
                                            <h3 class="no-margin text-bold">
                                                {{ count($all_user) }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-xxxl-3 col-lg-4">
                            <div class="box box-body pull-up">
                                <div class="d-flex justify-content-between">
                                    <div class="media align-items-center p-0">
                                        <div>
                                            <h3 class="no-margin text-bold">Total Emploeey</h3>
                                        </div>
                                    </div>
                                    <div>
                                        <div>
                                            <h3 class="no-margin text-bold">
                                                {{ count($employee) }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-xxxl-3 col-lg-4">
                            <div class="box box-body pull-up">
                                <div class="d-flex justify-content-between">
                                    <div class="media align-items-center p-0">
                                        <div>
                                            <h3 class="no-margin text-bold">Total Admin</h3>
                                        </div>
                                    </div>
                                    <div>
                                        <div>
                                            <h3 class="no-margin text-bold">
                                                {{ count($admin) }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-12 col-md-6 col-xxxl-3 col-lg-4">
                            <div class="box box-body pull-up">
                                <div class="d-flex justify-content-between">
                                    <div class="media align-items-center p-0">
                                        <div>
                                            <h3 class="no-margin text-bold">Total Buy</h3>
                                        </div>
                                    </div>
                                    <div>
                                        <div>
                                            <h3 class="no-margin text-bold">{{ $all_buy }} TK</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-xxxl-3 col-lg-4">
                            <div class="box box-body pull-up">
                                <div class="d-flex justify-content-between">
                                    <div class="media align-items-center p-0">
                                        <div>
                                            <h3 class="no-margin text-bold">Total Sell</h3>
                                        </div>
                                    </div>
                                    <div>
                                        <div>
                                            <h3 class="no-margin text-bold">{{ $all_sell }} TK</h3>
                                        </div>
                                    </div>
                                </div>
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
