@extends('layouts.backend.app')

@section('title', 'কার্ড সুবিধা এডিট করুন - এডমিন প্যানেল')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title fw-bold text-dark"><i class="fa fa-edit me-2 text-primary"></i> কার্ড সুবিধা সম্পাদনা (Edit Card Benefits)</h3>
                    <p class="text-muted mb-0">{{ $card->card_name }} এর তথ্য ও সুবিধাসমূহ আপডেট করুন</p>
                </div>
                <div>
                    <a href="{{ route('admin.card_benefits.index') }}" class="btn btn-secondary shadow-sm px-4">
                        <i class="fa fa-arrow-left me-1"></i> তালিকায় ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-lg-9 col-12 mx-auto">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-primary text-white py-3 rounded-top-4">
                            <h4 class="card-title fw-bold mb-0"><i class="fa fa-id-card me-2"></i> কার্ড তথ্য, নিয়মাবলী ও সুবিধাসমূহ</h4>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('admin.card_benefits.update', $card->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">কার্ডের নাম / শিরোনাম <span class="text-danger">*</span></label>
                                        <input type="text" name="card_name" class="form-control" value="{{ old('card_name', $card->card_name) }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">কার্ড টাইপ / কোড <span class="text-danger">*</span></label>
                                        <select name="card_type" class="form-control" required>
                                            <option value="gold" {{ $card->card_type == 'gold' ? 'selected' : '' }}>Gold Card (গোল্ডেন কার্ড)</option>
                                            <option value="red" {{ $card->card_type == 'red' ? 'selected' : '' }}>Red / Normal Card (লাল / নরমাল কার্ড)</option>
                                            <option value="silver" {{ $card->card_type == 'silver' ? 'selected' : '' }}>Silver Card (সিলভার কার্ড)</option>
                                            <option value="custom" {{ $card->card_type == 'custom' ? 'selected' : '' }}>Custom (অন্যান্য)</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">ব্যাজ টেক্সট (Badge Text)</label>
                                        <input type="text" name="badge_text" class="form-control" value="{{ old('badge_text', $card->badge_text) }}" placeholder="যেমন: প্রিমিয়াম গোল্ডেন মেম্বারশিপ">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">কালার থিম</label>
                                        <select name="card_color_theme" class="form-control">
                                            <option value="gold" {{ $card->card_color_theme == 'gold' ? 'selected' : '' }}>Gold (গোল্ডেন)</option>
                                            <option value="red" {{ $card->card_color_theme == 'red' ? 'selected' : '' }}>Red (লাল)</option>
                                            <option value="blue" {{ $card->card_color_theme == 'blue' ? 'selected' : '' }}>Blue (নীল)</option>
                                            <option value="green" {{ $card->card_color_theme == 'green' ? 'selected' : '' }}>Green (সবুজ)</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">নমুনা কার্ড নম্বর (Card No Sample)</label>
                                        <input type="text" name="card_number_sample" class="form-control" value="{{ old('card_number_sample', $card->card_number_sample) }}" placeholder="যেমন: Card No : 100001">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">মেয়াদ (Validity)</label>
                                        <input type="text" name="validity" class="form-control" value="{{ old('validity', $card->validity) }}" placeholder="যেমন: 12/2030">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">মেম্বারশিপ / কার্ড ফি</label>
                                        <input type="text" name="card_fee" class="form-control" value="{{ old('card_fee', $card->card_fee) }}" placeholder="যেমন: ১০০০/- টাকা (কার্ড ফি) + ৫০/- টাকা (কৃষি সেবা বই)">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">বিনিয়োগ সীমা (Investment Limit)</label>
                                        <input type="text" name="investment_limit" class="form-control" value="{{ old('investment_limit', $card->investment_limit) }}" placeholder="যেমন: সর্বনিম্ন ৫০,০০০/- থেকে সর্বোচ্চ ১,০০,০০০/- টাকা">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">লভ্যাংশ বিবরণ (Monthly Profit)</label>
                                        <input type="text" name="monthly_profit" class="form-control" value="{{ old('monthly_profit', $card->monthly_profit) }}" placeholder="যেমন: ৫০,০০০ টাকায় মাসিক ২০০০-২৫০০/- এবং ১০০,০০০ টাকায় ৪০০০-৫০০০/-">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">উত্তোলন নোটিশ (Withdrawal Notice)</label>
                                        <input type="text" name="withdrawal_notice" class="form-control" value="{{ old('withdrawal_notice', $card->withdrawal_notice) }}" placeholder="যেমন: ১ মাস আগে নোটিশ/মেসেজ">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">কার্ডের ছবি পরিবর্তন করুন</label>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                        @if($card->image && file_exists(public_path($card->image)))
                                            <div class="mt-2 p-2 bg-light rounded border d-inline-block">
                                                <small class="d-block text-muted mb-1">বর্তমান ছবি:</small>
                                                <img src="{{ asset($card->image) }}" alt="{{ $card->card_name }}" style="max-height: 80px; border-radius: 6px;">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">ব্রোশিউর / লিফলেট ছবি পরিবর্তন</label>
                                        <input type="file" name="brochure_image" class="form-control" accept="image/*">
                                        @if($card->brochure_image && file_exists(public_path($card->brochure_image)))
                                            <div class="mt-2 p-2 bg-light rounded border d-inline-block">
                                                <small class="d-block text-muted mb-1">বর্তমান ব্রোশিউর:</small>
                                                <img src="{{ asset($card->brochure_image) }}" alt="Brochure" style="max-height: 80px; border-radius: 6px;">
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-bold">সংক্ষিপ্ত বিবরণ</label>
                                        <textarea name="short_description" class="form-control" rows="2" placeholder="কার্ড সম্পর্কে সংক্ষিপ্ত তথ্য...">{{ old('short_description', $card->short_description) }}</textarea>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-bold">নিয়মাবলী ও সুবিধাসমূহ (প্রতি লাইনে একটি করে পয়েন্ট লিখুন) <span class="text-danger">*</span></label>
                                        @php
                                            $facText = is_array($card->facilities) ? implode("\n", $card->facilities) : '';
                                        @endphp
                                        <textarea name="facilities" class="form-control" rows="8" placeholder="প্রতি লাইনে একটি সুবিধা বা নিয়ম লিখুন..." required>{{ old('facilities', $facText) }}</textarea>
                                        <small class="text-muted">টিপস: প্রতিটি সুবিধা বা নিয়মাবলী নতুন লাইনে লিখুন, হোমপেজে সুন্দর বুলেটেড পয়েন্ট আকারে ক্রমানুসারে শো করবে।</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">অ্যাকশন বাটন টেক্সট</label>
                                        <input type="text" name="action_button_text" class="form-control" value="{{ old('action_button_text', $card->action_button_text) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">অ্যাকশন বাটন লিঙ্ক (URL)</label>
                                        <input type="text" name="action_button_url" class="form-control" value="{{ old('action_button_url', $card->action_button_url) }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">ডিসপ্লে ক্রম (Order)</label>
                                        <input type="number" name="order_num" class="form-control" value="{{ old('order_num', $card->order_num) }}">
                                    </div>
                                    <div class="col-md-6 mb-3 d-flex align-items-center pt-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status" id="editStatusSwitch" {{ $card->status == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold ms-2" for="editStatusSwitch">স্ট্যাটাস সক্রিয় রাখুন (Active)</label>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-3 text-end">
                                        <a href="{{ route('admin.card_benefits.index') }}" class="btn btn-secondary px-4 me-2">বাতিল</a>
                                        <button type="submit" class="btn btn-primary px-5"><i class="fa fa-save me-1"></i> আপডেট সংরক্ষণ করুন</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
