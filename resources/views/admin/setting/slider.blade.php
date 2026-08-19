@extends('layouts.backend.app')

@section('content')
<!-- Custom Styles to prevent any form card overlap or breaking -->
<style>
    .slider-nav-btn {
        font-weight: 600;
        padding: 8px 18px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .slider-edit-card {
        background: #ffffff;
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04) !important;
        margin-bottom: 24px !important;
        overflow: hidden;
    }
    .slider-edit-card .card-header {
        background: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 14px 20px !important;
    }
    .slider-edit-card .card-body {
        padding: 22px !important;
    }
    .slider-edit-card label {
        font-weight: 600 !important;
        margin-bottom: 8px !important;
        color: #1e293b !important;
        display: block !important;
    }
    .slider-edit-card input.form-control,
    .slider-edit-card textarea.form-control {
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        padding: 10px 14px !important;
        font-size: 14px !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .slider-edit-card input.form-control:focus,
    .slider-edit-card textarea.form-control:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
    }
</style>

<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header mb-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title fw-bold text-dark mb-1"><i class="fa fa-sliders me-2 text-primary"></i> স্লাইডার ম্যানেজমেন্ট (Slider Management)</h4>
                    <p class="text-muted mb-0">হোমপেজের স্লাইডারসমূহ পরিচালনা, এডিট, আপডেট ও ডিলিট করুন</p>
                </div>
                <!-- Requested Buttons: Slider List & Create Slider -->
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary slider-nav-btn shadow-sm" id="btn_show_list" onclick="switchSliderView('list')">
                        <i class="fa fa-list me-1"></i> Slider List (স্লাইডার তালিকা)
                    </button>
                    <button type="button" class="btn btn-outline-success slider-nav-btn shadow-sm" id="btn_show_form" onclick="switchSliderView('form')">
                        <i class="fa fa-plus-circle me-1"></i> Create / Edit Slider (স্লাইডার যুক্ত ও এডিট)
                    </button>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- 1. Slider List Table Section -->
            <div id="slider_list_section" class="row mb-4">
                <div class="col-12">
                    <div class="box shadow-sm border-0 rounded-3">
                        <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                            <h4 class="box-title fw-bold text-dark mb-0">
                                <i class="fa fa-list me-2 text-info"></i> স্লাইডার তালিকা (Slider List)
                            </h4>
                            <button type="button" class="btn btn-sm btn-success" onclick="switchSliderView('form')">
                                <i class="fa fa-plus-circle me-1"></i> নতুন স্লাইডার এড / এডিট করুন
                            </button>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th style="width: 80px;" class="text-center"># ID</th>
                                            <th style="width: 180px;">স্লাইডার ছবি (Image)</th>
                                            <th>হেডিং (Heading)</th>
                                            <th>বিবরণ (Description)</th>
                                            <th class="text-center" style="width: 160px;">অ্যাকশন (Actions)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(array_filter($sliders, function($s) { return !empty($s['img']) || !empty($s['text']) || !empty($s['description']); }) as $id => $slider)
                                            <tr>
                                                <td class="text-center fw-bold">
                                                    <span class="badge bg-secondary">Slider #{{ $id }}</span>
                                                </td>
                                                <td>
                                                    @if(!empty($slider['img']) && file_exists(public_path('upload/slider/' . $slider['img'])))
                                                        <img src="{{ asset('upload/slider/' . $slider['img']) }}" alt="Slider {{ $id }}" class="img-thumbnail rounded shadow-sm" style="max-height: 65px; max-width: 150px; object-fit: cover;">
                                                    @else
                                                        <span class="badge bg-light text-muted border py-2 px-3">No Image</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong class="text-dark">{{ $slider['text'] ?: '— (খালি)' }}</strong>
                                                </td>
                                                <td>
                                                    <small class="text-muted d-block" style="max-width: 350px;">
                                                        {{ $slider['description'] ? Str::limit($slider['description'], 100) : '— (খালি)' }}
                                                    </small>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" onclick="editSingleSlider({{ $id }})" class="btn btn-sm btn-info me-1" title="Edit Slider {{ $id }}">
                                                        <i class="fa fa-edit me-1"></i> এডিট
                                                    </button>
                                                    <a href="{{ route('setting.slider.delete', $id) }}" class="btn btn-sm btn-danger" onclick="return confirm('আপনি কি নিশ্চিত যে Slider #{{ $id }} মুছে ফেলতে চান?');" title="Delete Slider {{ $id }}">
                                                        <i class="fa fa-trash me-1"></i> ডিলিট
                                                    </a>
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

            <!-- 2. Add / Edit Sliders Form Section -->
            <div id="slider_form_section" class="row">
                <div class="col-12">
                    <div class="box shadow-sm border-0 rounded-3">
                        <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                            <h4 class="box-title fw-bold text-dark mb-0">
                                <i class="fa fa-edit me-2 text-success"></i> স্লাইডার যুক্ত ও এডিট করুন (Create / Edit Sliders)
                            </h4>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="switchSliderView('list')">
                                <i class="fa fa-arrow-left me-1"></i> তালিকায় ফিরে যান
                            </button>
                        </div>
                        <div class="box-body">
                            <form method="POST" action="{{ route('setting.slider.post') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="row g-4">
                                    @for($i = 1; $i <= 4; $i++)
                                        <div class="col-lg-6 col-12" id="slider_card_{{ $i }}">
                                            <div class="card slider-edit-card">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <h5 class="fw-bold text-dark mb-0">
                                                        <i class="fa fa-picture-o text-primary me-2"></i> Slider #{{ $i }}
                                                    </h5>
                                                    @if(!empty(setting('slider'.$i.'_img')))
                                                        <span class="badge bg-success"><i class="fa fa-check me-1"></i> Image Set</span>
                                                    @else
                                                        <span class="badge bg-secondary">No Image</span>
                                                    @endif
                                                </div>
                                                <div class="card-body">
                                                    <!-- Heading -->
                                                    <div class="mb-3">
                                                        <label for="slider{{ $i }}_text">
                                                            Slider {{ $i }} Heading (শিরোনাম)
                                                        </label>
                                                        <input type="text" class="form-control" id="slider{{ $i }}_text" name="slider{{ $i }}_text" value="{{ setting('slider'.$i.'_text') }}" placeholder="স্লাইডারের হেডিং লিখুন">
                                                    </div>

                                                    <!-- Description -->
                                                    <div class="mb-3">
                                                        <label for="slider{{ $i }}_description">
                                                            Slider {{ $i }} Description (বিবরণ)
                                                        </label>
                                                        <textarea class="form-control" name="slider{{ $i }}_description" id="slider{{ $i }}_description" rows="3" placeholder="স্লাইডারের বিবরণ লিখুন">{{ setting('slider'.$i.'_description') }}</textarea>
                                                    </div>

                                                    <!-- Image -->
                                                    <div class="mb-2">
                                                        <label for="slider{{ $i }}_img">
                                                            Slider {{ $i }} Image <small class="text-muted fw-normal">(Recommended: 1920 x 820 px)</small>
                                                        </label>
                                                        <input type="file" class="form-control" id="slider{{ $i }}_img" name="slider{{ $i }}_img" accept="image/*">
                                                        @if(!empty(setting('slider'.$i.'_img')) && file_exists(public_path('upload/slider/' . setting('slider'.$i.'_img'))))
                                                            <div class="mt-3 p-2 bg-light rounded border text-center">
                                                                <small class="text-muted d-block mb-1">বর্তমান ছবি:</small>
                                                                <img src="{{ asset('upload/slider/' . setting('slider'.$i.'_img')) }}" alt="Slider {{ $i }}" class="img-fluid rounded" style="max-height: 90px; object-fit: cover;">
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>

                                <div class="text-end mt-4 pt-3 border-top">
                                    <button type="submit" class="btn btn-success btn-lg px-5 shadow">
                                        <i class="fa fa-save me-2"></i> সকল স্লাইডার সেভ ও আপডেট করুন (Save Sliders)
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
</div>

<script>
    function switchSliderView(view) {
        const listSec = document.getElementById('slider_list_section');
        const formSec = document.getElementById('slider_form_section');
        const btnList = document.getElementById('btn_show_list');
        const btnForm = document.getElementById('btn_show_form');

        if (view === 'list') {
            listSec.style.display = 'flex';
            formSec.style.display = 'none';

            btnList.classList.remove('btn-outline-primary');
            btnList.classList.add('btn-primary');

            btnForm.classList.remove('btn-success');
            btnForm.classList.add('btn-outline-success');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            listSec.style.display = 'none';
            formSec.style.display = 'flex';

            btnForm.classList.remove('btn-outline-success');
            btnForm.classList.add('btn-success');

            btnList.classList.remove('btn-primary');
            btnList.classList.add('btn-outline-primary');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }

    function editSingleSlider(id) {
        switchSliderView('form');
        setTimeout(() => {
            const el = document.getElementById('slider_card_' + id);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                el.classList.add('border-primary');
                setTimeout(() => el.classList.remove('border-primary'), 2000);
            }
        }, 150);
    }
</script>
@endsection
