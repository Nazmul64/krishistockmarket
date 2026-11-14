@extends(Auth::check() ? 'layouts.backend.app' : 'layouts.frontend.app')

@section('content')
@if(Auth::check())
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">
@endif
<div class="corporate-page-wrap py-5 bg-light">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5">
            <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fw-bold font-size-14 mb-2">
                <i class="fa fa-balance-scale me-1"></i> বিধিমালা ও আইনি ডিসক্লেইমার
            </span>
            <h2 class="fw-bold display-6 text-dark mt-2">বিনিয়োগ নীতিমালা ও শর্তাবলি (Investment Policy)</h2>
            <p class="text-muted max-w-700 mx-auto font-size-16">
                স্বচ্ছতা, জবাবদিহিতা ও হালাল প্রবৃদ্ধির অঙ্গীকারে পরিচালিত যৌথ খামার ও প্রপার্টি বিনিয়োগের আইনি নীতিমালা।
            </p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white mb-4">
                    <!-- Alert Box -->
                    <div class="alert alert-warning p-4 rounded-4 mb-4 border-start border-4 border-warning">
                        <h5 class="fw-bold text-dark mb-2"><i class="fa fa-exclamation-triangle me-2"></i> গুরুত্বপূর্ণ আইনি সতর্কতা</h5>
                        <p class="small text-secondary mb-0" style="line-height: 1.7;">
                            ই-কৃষি সেবা কোনো নির্দিষ্ট সুনির্দিষ্ট সুদের হার বা নিশ্চিত মুনাফার প্রতিশ্রুতি প্রদান করে না। আমাদের প্রতিটি বিনিয়োগ প্রকল্প শরীয়াহসম্মত ও যৌথ অংশীদারিত্ব (মুদারাবা/মুশারাকা) নীতিতে প্রকৃত ব্যবসায়িক ফলাফলের ভিত্তিতে পরিচালিত। লাভ কিংবা লোকসান বাস্তব ফসল/পণ্য বিক্রয় পরবর্তী নিট আয়ের ওপর নির্ভর করবে।
                        </p>
                    </div>

                    <!-- 1. Eligibility & Requirements -->
                    <div class="mb-4">
                        <h4 class="fw-bold text-dark mb-3"><span class="text-success me-2">১.</span> বিনিয়োগের যোগ্যতা ও আবশ্যকীয় শর্তাবলি</h4>
                        <ul class="text-secondary font-size-15 leading-relaxed ps-3">
                            <li class="mb-2">আবেদনকারীকে অবশ্যই ১৮ বছর বা তদূর্ধ্ব বয়সের প্রাপ্তবয়স্ক নাগরিক হতে হবে।</li>
                            <li class="mb-2">বৈধ জাতীয় পরিচয়পত্র (NID) অথবা মেশিন রিডেবল পাসপোর্ট নম্বর প্রদান বাধ্যতামূলক।</li>
                            <li class="mb-2">প্রতিটি বিনিয়োগকারীর জন্য একজন বৈধ উত্তরাধিকারী (Nominee) থাকা বাধ্যতামূলক।</li>
                            <li class="mb-2">বিনিয়োগকৃত অর্থ সম্পূর্ণ বৈধ উৎস থেকে অর্জিত হতে হবে এবং অ্যান্টি-মানি লন্ডারিং আইনের পরিপন্থী হতে পারবে না।</li>
                        </ul>
                    </div>

                    <!-- 2. Limits & Durations -->
                    <div class="mb-4">
                        <h4 class="fw-bold text-dark mb-3"><span class="text-success me-2">২.</span> বিনিয়োগের সীমা ও প্রকল্পভিত্তিক মেয়াদ</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered small">
                                <thead class="table-light">
                                    <tr>
                                        <th>বিনিয়োগ খাত</th>
                                        <th>বিনিয়োগের সীমা</th>
                                        <th>মেয়াদ</th>
                                        <th>লভ্যাংশ বণ্টন সময়কাল</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>কৃষি ও ফসল উৎপাদন</td>
                                        <td>২–১০ লাখ টাকা</td>
                                        <td>কর্তৃপক্ষ নির্ধারিত (মৌসুমভিত্তিক)</td>
                                        <td>ফসল কর্তন ও বিক্রয় শেষে</td>
                                    </tr>
                                    <tr>
                                        <td>মাছ চাষ প্রকল্প</td>
                                        <td>২–১০ লাখ টাকা</td>
                                        <td>নির্ধারিত</td>
                                        <td>মাছ আহরণ ও বাজারজাতকরণে</td>
                                    </tr>
                                    <tr>
                                        <td>পশুপালন ও খামার প্রকল্প</td>
                                        <td>২–১০ লাখ টাকা</td>
                                        <td>১০০ দিন / নির্ধারিত</td>
                                        <td>সাইকেল শেষে বা বাৎসরিক</td>
                                    </tr>
                                    <tr>
                                        <td>গ্রিনহাউস সবজি ও ফলমূল</td>
                                        <td>৫–৫০ লাখ টাকা</td>
                                        <td>নির্ধারিত (১-৩ বছর)</td>
                                        <td>ত্রৈমাসিক নিরীক্ষা সাপেক্ষে</td>
                                    </tr>
                                    <tr>
                                        <td>প্রপার্টি, খামারবাড়ি ও রিসোর্ট</td>
                                        <td>৫ লাখ–১ কোটি টাকা</td>
                                        <td>২-৫ বছর</td>
                                        <td>বাৎসরিক অডিটেড রিটার্ন</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 3. Profit & Loss Distribution Policy -->
                    <div class="mb-4">
                        <h4 class="fw-bold text-dark mb-3"><span class="text-success me-2">৩.</span> লাভ-লোকসান বণ্টন নীতি</h4>
                        <p class="text-secondary font-size-15 leading-relaxed" style="line-height: 1.8;">
                            প্রতিটি প্রকল্পের জন্য আলাদা স্বতন্ত্র হিসাব বই (Sub-ledger) সংরক্ষণ করা হয়। মোট বিক্রয়মূল্য থেকে প্রকল্প বাস্তবায়ন খরচ, শ্রম ব্যয়, রক্ষণাবেক্ষণ ও নির্ধারিত প্রশাসনিক খরচ বাদ দিয়ে নিট মুনাফা নির্ধারিত হয়। এই নিট মুনাফার একটি নির্দিষ্ট অনুপাত (সাধারণত ৫০-৭০%) বিনিয়োগকারীদের মধ্যে তাদের মূলধনের অনুপাতে বণ্টন করা হয় এবং অবশিষ্ট অর্থ প্রতিষ্ঠানটির ব্যবস্থাপনা ও রিজার্ভ ফান্ডে জমা থাকে।
                        </p>
                    </div>

                    <!-- 4. Risk Factors & Mitigation -->
                    <div class="mb-4">
                        <h4 class="fw-bold text-dark mb-3"><span class="text-success me-2">৪.</span> বিনিয়োগের ঝুঁকি ও সম্ভাব্য ক্ষতি নিরসন</h4>
                        <p class="text-secondary font-size-15 leading-relaxed" style="line-height: 1.8;">
                            কৃষি ও জীবজন্তুপালন আবহাওয়া, রোগবালাই এবং বাজারের মূল্য ওঠানামার ওপর নির্ভরশীল। প্রতিষ্ঠান হিসেবে আমরা সর্বোচ্চ সতর্কতা অবলম্বন করি: আধুনিক বায়োসিকিউরিটি, সার্বক্ষণিক পশুচিকিৎসক তত্ত্বাবধান, গ্রিনহাউস ক্লাইমেট কন্ট্রোল এবং প্রয়োজন সাপেক্ষে কৃষি বীমার ব্যবস্থা করা হয় যাতে ঝুঁকি সর্বনিম্ন পর্যায়ে রাখা যায়।
                        </p>
                    </div>

                    <!-- 5. Fund Withdrawal & Capital Safety -->
                    <div class="mb-4">
                        <h4 class="fw-bold text-dark mb-3"><span class="text-success me-2">৫.</span> মূলধন ফেরত ও ফান্ড উত্তোলনের শর্ত</h4>
                        <ul class="text-secondary font-size-15 leading-relaxed ps-3">
                            <li class="mb-2">প্রকল্পের মেয়াদ শেষ হওয়ার পর বিনিয়োগকারী তার মূলধন সম্পূর্ণ ফেরত পাওয়ার জন্য ড্যাশবোর্ড থেকে আবেদন করতে পারবেন।</li>
                            <li class="mb-2">মেয়াদপূর্তির পূর্বে জরুরি প্রয়োজনে মূলধন উত্তোলন করতে চাইলে নির্ধারিত নোটিশ পিরিয়ড (সাধারণত ৩০-৬০ দিন) সাপেক্ষে কর্তৃপক্ষের অনুমোদন গ্রহণ করতে হবে।</li>
                            <li class="mb-2">অর্জিত লভ্যাংশ নির্ধারিত সময়ান্তে সরাসরি মেম্বার ওয়ালেটে জমা হয় এবং যেকোনো সময় ব্যাংক বা মোবাইল ব্যাংকিংয়ের মাধ্যমে উত্তোলনযোগ্য।</li>
                        </ul>
                    </div>

                    <!-- 6. Agreement & Consent -->
                    <div class="p-3 bg-light rounded-3 text-center border">
                        <p class="text-muted small mb-3">
                            আপনি কি আমাদের বিনিয়োগ নীতিমালা মেনে যৌথ খামার ও প্রপার্টি প্রকল্পে অংশ নিতে আগ্রহী?
                        </p>
                        <a href="{{ route('apply.card', ['type' => 'property']) }}" class="btn btn-success rounded-pill px-5 fw-bold">
                            প্রপার্টি কার্ডে আবেদন করুন <i class="fa fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@if(Auth::check())
        </section>
    </div>
</div>
@endif
@endsection

