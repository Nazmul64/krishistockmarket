@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">All Referal User'S</h4>
                </div>

            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">

                <!-- /.col -->
                <div class="col-lg-12">
                    <div class="table-responsive buyorder">
                        <table class="table no-margin min-pad-table">
                            <thead>
                                <tr>
                                    <th>SN.</th>
                                    <th>Name</th>
                                    <th>User Name</th>
                                    <th>Phone</th>
                                    <th>Balance</th>
                                    @if (Auth::user()->role == "admin")
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $all_user= App\Models\User::where('role', 'user')->where('referral_id', Auth::user()->id)->get();
                                @endphp
                                @foreach ($all_user as $key => $item)

                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->username}}</td>
                                    <td>{{ $item->phone}}</td>
                                    <td>{{ $item->balance}}</td>
                                    @if (Auth::user()->role == "admin")
                                        <td class="sorting_1">
                                            <a href="{{ route('admin.user.destroy', $item->id) }}" class="waves-effect waves-light btn btn-danger mb-5 btn-xs">Delete</a>
                                        </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.col -->

            </div>
        </section>
        <!-- /.content -->

    </div>


</div>
@endsection
