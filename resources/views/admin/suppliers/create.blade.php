@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
<div class="container-full">
    <div class="content-header">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h3 class="page-title">নতুন সাপ্লায়ার অ্যাকাউন্ট তৈরি</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home-outline"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.suppliers.index') }}">সাপ্লায়ার তালিকা</a></li>
                    <li class="breadcrumb-item active">নতুন যোগ করুন</li>
                </ol>
            </div>
            <div>
                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i> ফিরে যান</a>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-md-10 offset-md-1 col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">সাপ্লায়ারের তথ্য প্রদান করুন</h4>
                    </div>
                    <form action="{{ route('admin.suppliers.store') }}" method="POST" class="form">
                        @csrf
                        <div class="box-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="form-label">প্রতিষ্ঠানের নাম <span class="text-danger">*</span></label>
                                        <input type="text" name="company_name" class="form-control" placeholder="যেমন: মেসার্স রহিম ট্রেডার্স" value="{{ old('company_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="form-label">সাপ্লায়ার/মালিকের নাম <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" placeholder="যেমন: মো: রহিম উদ্দিন" value="{{ old('name') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="form-label">মোবাইল নম্বর <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" class="form-control" placeholder="যেমন: 017xxxxxxxx" value="{{ old('phone') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="form-label">ইমেইল ঠিকানা (ঐচ্ছিক)</label>
                                        <input type="email" name="email" class="form-control" placeholder="supplier@gmail.com" value="{{ old('email') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="form-label">জেলা / থানা</label>
                                        <input type="text" name="district_thana" class="form-control" placeholder="যেমন: ধানমন্ডি, ঢাকা" value="{{ old('district_thana') }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="form-label">প্রারম্ভিক জের (Opening Balance ৳)</label>
                                        <input type="number" step="0.01" name="opening_balance" class="form-control" placeholder="0.00" value="{{ old('opening_balance', 0) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="form-label">লগইন পাসওয়ার্ড <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control" placeholder="সর্বনিম্ন ৬ অক্ষর" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="form-label">বিস্তারিত ঠিকানা</label>
                                        <textarea name="address" class="form-control" rows="2" placeholder="রাস্তা, এলাকা বিস্তারিত">{{ old('address') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">অতিরিক্ত নোট / বিবরণ</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="অন্যান্য তথ্য">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="box-footer text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> অ্যাকাউন্ট সেভ করুন
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
</div>
@endsection
