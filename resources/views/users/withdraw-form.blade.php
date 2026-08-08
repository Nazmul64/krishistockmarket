@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Cart</h4>
                </div>

            </div>
        </div>

        <!-- Main content -->
        <section class="content">

            <!-- Step wizard -->
            <div class="box">
                <div class="box-header with-border text-center">
                    <h4 class="box-title">Your Cart</h4>


                </div>

                    @if($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="form-control-feedback" >
                                <code>{{ $error }}</code>
                            </div>
                        @endforeach
                    @endif

                    @if (\Session::has('error'))
                        <div class="form-control-feedback">
                            <code>{!! \Session::get('error') !!}</code>
                        </div>
                    @endif

                <!-- /.box-header -->
                <div class="box-body wizard-content">
                    <form action="{{ route('withdraw.post') }}" method="POST" enctype="multipart/form-data" class="tab-wizard wizard-circle">
                        @csrf
                        <h6>GET Method Setup</h6>
                        <section>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group row" style="margin: 25px 0;">
                                        <label class="col-sm-3 col-form-label" style="padding-left: 0;">Get By Type</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="payment_system_id" required>
                                                <option value="">Select ....</option>
                                                @php
                                                    $all_pay_s= App\Models\UserPaymentSystem::where('user_id', Auth::user()->id)->get();
                                                @endphp

                                                @foreach ($all_pay_s as $key => $item)
                                                    <option value="{{ $item->id }}">{{ $item->pay_s_name }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <label for="">Pay to:</label>
                                    @foreach ($all_pay_s as $key => $item)
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label"> <i style="color: red">{{ $item->pay_s_name }}</i></label>
                                            <div class="col-sm-9 col-form-label">
                                                <div class="c-inputs-stacked">
                                                    <label for="checkbox_123" class="d-block">{{ $item->pay_s_number }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </section>
                        <!-- Step 3 -->
                        <h6>Amount & Submit</h6>
                        <section>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group row" style="margin: 25px 0;">
                                        <label class="col-sm-3 col-form-label" style="padding-left: 0;">Your Balance</label>
                                        <div class="col-sm-9">
                                            <span>{{ Auth::user()->balance }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row" style="margin-bottom: 20px;">
                                        <label class="col-sm-3 col-form-label">Withdraw Amount:</label>
                                        <div class="col-sm-9">
                                           <input required type="number" name="withdraw_amount" min="50" max="{{ Auth::user()->balance }}" class="form-control">
                                           <small>Min:50, Max:100 </small>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </section>
                        <!-- Step 4 -->

                    </form>

                </div>
                <!-- /.box-body -->

            </div>
            <!-- /.box -->

        </section>
        <!-- /.content -->

    </div>

    {{-- @php
        // Convert user_cart_list array to a collection
        $userCartCollection = collect($user_cart_list);

        // Check if the collection contains any value less than zero
        $submitDisabled = $userCartCollection->contains(function ($value) {
            return $value < 0;
        });
    @endphp --}}

</div>


@endsection

@section('script')
<script type="text/javascript">


    $(".tab-wizard").steps({
        headerTag: "h6"
        , bodyTag: "section"
        , transitionEffect: "none"
        , titleTemplate: '<span class="step">#index#</span> #title#'
        , labels: {
            finish: "Submit"
        }
        , onFinished: function (event, currentIndex) {

            var form = $(".tab-wizard").closest("form");
            // Submit the form
            form.submit();

        }
    });

</script>
@endsection
