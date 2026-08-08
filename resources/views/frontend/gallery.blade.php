@extends('layouts.frontend.app')

@section('content')
<div class="main-container">

    <!-- Page Banner -->
    <div class="container-fluid no-left-padding no-right-padding page-banner">
        <!-- Container -->
        <div class="container">
            <h3>Compuny Gallery</h3>
            <i class="fa fa-image"></i>
        </div><!-- Container /- -->
    </div><!-- Page Banner /- -->

    <main class="site-main">

        <!-- Page Content -->
        <div class="container-fluid no-left-padding no-right-padding page-content portfolio-4-col">
            <!-- Container -->
            <div class="container">

                <!-- Gallery List -->
                <div class="row gallery-list">

                    @php
                    $gallery_images = App\Models\StockGallery::paginate(9);
                    @endphp

                    @foreach ($gallery_images as $image)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-6 gallery-box technology">
                            <div class="gallery-content">
                                <i>
                                    <img src="{{ asset('upload/stock_images') }}/{{ $image->image }}" alt="Gallery" />
                                </i>
                                <div class="gallery-detail">
                                    <a class="zoom" href="{{ asset('upload/stock_images') }}/{{ $image->image }}">
                                        <i class="fa fa-search"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div><!-- Gallery List /- -->


                {{ $gallery_images->links('frontend.pagination.custom-pagination') }}

            </div><!-- Container /- -->
        </div><!-- Page Content /- -->

    </main>

</div>
@endsection
