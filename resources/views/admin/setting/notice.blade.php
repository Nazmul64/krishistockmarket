@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title"><i class="fa fa-bullhorn text-warning me-2"></i>Marquee Notice Management (শীর্ষ স্ক্রোলিং নোটিশ)</h4>
                </div>
                <div>
                    <a href="{{ route('setting.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fa fa-arrow-left me-1"></i> অল সেটিংস
                    </a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xl-8 col-lg-9 col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h3 class="box-title fw-bold text-dark">
                                <i class="fa fa-pencil-square-o text-primary me-2"></i>ওয়েবসাইট শীর্ষ নোটিশ আপডেট ও নিয়ন্ত্রণ
                            </h3>
                        </div>

                        <div class="box-body p-4">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if($current_notice)
                                <div class="alert alert-info d-flex align-items-start gap-3 mb-4">
                                    <div class="fs-3 text-info mt-1">
                                        <i class="fa fa-bullhorn"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold mb-1">বর্তমানে ওয়েবসাইটে চলমান নোটিশ:</h5>
                                        <p class="mb-2 text-dark bg-white p-2 rounded border" style="font-size: 15px;">
                                            {{ $current_notice }}
                                        </p>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('setting.notice.delete') }}" 
                                               onclick="return confirm('আপনি কি নিশ্চিত যে বর্তমান নোটিশটি মুছে ফেলতে চান?');" 
                                               class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash me-1"></i> নোটিশ মুছে ফেলুন (Delete Notice)
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('setting.notice.post') }}">
                                @csrf

                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold text-dark" for="top_notice">
                                        নোটিশ টেক্সট লিখুন (Notice Text) <span class="text-danger">*</span>
                                    </label>
                                    <textarea 
                                        name="top_notice" 
                                        id="top_notice" 
                                        rows="4" 
                                        class="form-control" 
                                        placeholder="ওয়েবসাইটের শীর্ষে স্ক্রোল করার নোটিশ বা জরুরি বার্তা লিখুন..." 
                                        required
                                        style="font-size: 15px; border-radius: 8px;"
                                    >{{ $current_notice }}</textarea>
                                    <small class="text-muted mt-1 d-block">
                                        এই লেখাটি হোমপেজ এবং সকল পাবলিক পেজের একদম উপরে লাইভ নোটিশ বারে অনবরত স্ক্রোল করবে।
                                    </small>
                                    @error('top_notice')
                                        <span class="text-danger d-block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="notice_status" id="notice_status" value="1" {{ $notice_status ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold text-dark ms-2" for="notice_status">
                                            নোটিশ চালু রাখুন (Active)
                                        </label>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 pt-2">
                                    <button type="submit" class="btn btn-success px-4">
                                        <i class="fa fa-save me-1"></i> নোটিশ সেভ করুন (Save Notice)
                                    </button>
                                    @if($current_notice)
                                        <a href="{{ route('setting.notice.delete') }}" 
                                           onclick="return confirm('আপনি কি নিশ্চিত যে নোটিশটি ডিলিট করতে চান?');" 
                                           class="btn btn-outline-danger">
                                            <i class="fa fa-trash me-1"></i> ডিলিট
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Live Preview Guide Column -->
                <div class="col-xl-4 col-lg-3 col-12">
                    <div class="box bg-primary-light">
                        <div class="box-body">
                            <h5 class="fw-bold text-primary mb-3">
                                <i class="fa fa-info-circle me-1"></i> নোটিশ বার সম্পর্কিত তথ্য:
                            </h5>
                            <ul class="text-muted ps-3 mb-0" style="line-height: 1.8; font-size: 14px;">
                                <li>নোটিশটি স্বয়ংক্রিয়ভাবে ওয়েবসাইটের টপ-বারে কনটেইনার বরাবর স্ক্রোল করবে।</li>
                                <li>ইউজার মাউস হভার করলে নোটিশটি সাময়িকভাবে থেমে যাবে এবং মাউস সরালে পুনরায় স্ক্রোল করা শুরু করবে।</li>
                                <li>যেকোনো অফার, জরুরি ঘোষণা বা শুভেচ্ছা বার্তা এখানে দিতে পারেন।</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
