@extends('layouts.backend.app')

@section('title', 'কার্ড সুবিধাসমূহ - এডমিন প্যানেল')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="page-title fw-bold text-dark"><i class="fa fa-id-card me-2 text-primary"></i> কার্ড সুবিধা ম্যানেজমেন্ট (Card Benefits)</h3>
                    <p class="text-muted mb-0">হোমপেজে প্রদর্শিত কৃষি এসএমই নরমাল (রেড) ও গোল্ডেন কার্ডের বিশেষ সুবিধাসমূহ পরিচালনা করুন</p>
                </div>
                <div>
                    <button type="button" class="btn btn-primary shadow-sm px-4 py-2" data-bs-toggle="modal" data-bs-target="#addCardModal" data-toggle="modal" data-target="#addCardModal">
                        <i class="fa fa-plus-circle me-1"></i> নতুন কার্ড সুবিধা যোগ করুন
                    </button>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            @if(session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" data-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                @forelse($cards as $card)
                <div class="col-lg-6 col-12 mb-4">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden" style="border: 2px solid {{ $card->card_color_theme == 'gold' ? '#eab308' : ($card->card_color_theme == 'red' ? '#ef4444' : '#3b82f6') }} !important;">
                        <!-- Card Top Header -->
                        <div class="card-header d-flex align-items-center justify-content-between text-white py-3" style="background: {{ $card->card_color_theme == 'gold' ? 'linear-gradient(135deg, #d97706, #b45309)' : ($card->card_color_theme == 'red' ? 'linear-gradient(135deg, #dc2626, #991b1b)' : 'linear-gradient(135deg, #2563eb, #1d4ed8)') }};">
                            <div>
                                <span class="badge bg-white text-dark px-3 py-1 mb-1 fw-bold" style="border-radius: 20px;">
                                    <i class="fa fa-tag me-1"></i> {{ $card->badge_text ?: 'মেম্বারশিপ সুবিধা' }}
                                </span>
                                <h4 class="card-title fw-bold mb-0 text-white">{{ $card->card_name }}</h4>
                            </div>
                            <div class="text-end">
                                <span class="badge {{ $card->status == 1 ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                                    {{ $card->status == 1 ? 'সক্রিয় (Active)' : 'নিষ্ক্রিয় (Inactive)' }}
                                </span>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="row align-items-center mb-4">
                                <div class="col-md-5 text-center mb-3 mb-md-0">
                                    @if($card->image && file_exists(public_path($card->image)))
                                        <img src="{{ asset($card->image) }}" alt="{{ $card->card_name }}" class="img-fluid rounded-3 shadow" style="max-height: 140px; object-fit: contain; border: 1px solid #e2e8f0;">
                                    @else
                                        <div class="bg-light rounded-3 p-4 text-center border">
                                            <i class="fa fa-id-card text-muted fa-3x mb-2"></i>
                                            <small class="d-block text-muted">কোনো ছবি নেই</small>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-7">
                                    <ul class="list-unstyled mb-0 small text-secondary">
                                        <li class="mb-1"><strong>কার্ড টাইপ:</strong> <span class="badge bg-light text-dark border">{{ strtoupper($card->card_type) }}</span></li>
                                        <li class="mb-1"><strong>নমুনা নম্বর:</strong> <code class="text-dark">{{ $card->card_number_sample ?: 'N/A' }}</code></li>
                                        <li class="mb-1"><strong>মেয়াদ (Validity):</strong> <span class="text-dark fw-bold">{{ $card->validity ?: '12/2030' }}</span></li>
                                        <li class="mb-1"><strong>মেম্বারশিপ ফি:</strong> <span class="text-primary fw-bold">{{ $card->card_fee ?: 'N/A' }}</span></li>
                                        <li class="mb-1"><strong>বিনিয়োগ সীমা:</strong> <span class="text-success fw-bold">{{ $card->investment_limit ?: 'N/A' }}</span></li>
                                    </ul>
                                </div>
                            </div>

                            @if($card->short_description)
                                <p class="text-muted small mb-3 border-start border-3 ps-2" style="border-color: {{ $card->card_color_theme == 'gold' ? '#eab308' : '#ef4444' }} !important;">
                                    {{ $card->short_description }}
                                </p>
                            @endif

                            <!-- Key Highlights -->
                            <div class="row g-2 mb-3">
                                @if($card->monthly_profit)
                                <div class="col-md-6">
                                    <div class="p-2 rounded bg-light border small">
                                        <strong class="text-dark d-block"><i class="fa fa-money-bill-wave text-success me-1"></i> লভ্যাংশ:</strong>
                                        <span class="text-muted">{{ $card->monthly_profit }}</span>
                                    </div>
                                </div>
                                @endif
                                @if($card->withdrawal_notice)
                                <div class="col-md-6">
                                    <div class="p-2 rounded bg-light border small">
                                        <strong class="text-dark d-block"><i class="fa fa-clock text-warning me-1"></i> উত্তোলন নোটিশ:</strong>
                                        <span class="text-muted">{{ $card->withdrawal_notice }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Facilities List -->
                            <div class="bg-light p-3 rounded-3 mb-3 border">
                                <h6 class="fw-bold text-dark mb-2"><i class="fa fa-list-check me-1 text-primary"></i> নিয়মাবলী ও সুবিধাসমূহ ({{ is_array($card->facilities) ? count($card->facilities) : 0 }} টি পয়েন্ট):</h6>
                                @if(is_array($card->facilities) && count($card->facilities) > 0)
                                    <ul class="mb-0 ps-3 small text-dark">
                                        @foreach($card->facilities as $fac)
                                            <li class="mb-1">{{ $fac }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted small mb-0">কোনো সুবিধা যুক্ত করা হয়নি।</p>
                                @endif
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                <div>
                                    <a href="{{ route('admin.card_benefits.edit', $card->id) }}" class="btn btn-sm btn-info text-white px-3 me-2">
                                        <i class="fa fa-edit me-1"></i> এডিট করুন
                                    </a>
                                    <a href="{{ route('admin.card_benefits.delete', $card->id) }}" onclick="return confirm('আপনি কি নিশ্চিত এই কার্ড সুবিধা মুছে ফেলতে চান?')" class="btn btn-sm btn-danger px-3">
                                        <i class="fa fa-trash me-1"></i> ডিলিট
                                    </a>
                                </div>
                                <span class="small text-muted">ID: #{{ $card->id }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="card p-5 text-center border-0 shadow-sm rounded-4">
                        <i class="fa fa-id-card fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">কোনো কার্ড সুবিধা তৈরি করা নেই</h4>
                        <p class="text-muted mb-4">উপরের 'নতুন কার্ড সুবিধা যোগ করুন' বাটনে ক্লিক করে গোল্ডেন বা রেড কার্ডের সুবিধা যুক্ত করুন।</p>
                    </div>
                </div>
                @endforelse
            </div>
        </section>
    </div>
</div>

<!-- Add Card Benefit Modal -->
<div class="modal fade" id="addCardModal" tabindex="-1" aria-labelledby="addCardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('admin.card_benefits.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="addCardModalLabel"><i class="fa fa-plus-circle me-2"></i> নতুন কার্ড সুবিধা যোগ করুন</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Quick Template Loader Banner -->
                    <div class="alert alert-info d-flex flex-wrap align-items-center justify-content-between py-2 px-3 mb-4 rounded-3 border-info">
                        <div>
                            <strong class="text-dark"><i class="fa fa-magic me-1 text-primary"></i> কুইক টেমপ্লেট লোডার:</strong>
                            <span class="small text-muted d-block d-sm-inline">এক ক্লিকেই সব ফিল্ড পূরণ করতে টেমপ্লেট বেছে নিন</span>
                        </div>
                        <div class="mt-2 mt-sm-0">
                            <button type="button" class="btn btn-sm btn-danger me-2 shadow-sm" onclick="loadCardTemplate('red')">
                                <i class="fa fa-id-card me-1"></i> লাল/নরমাল কার্ড
                            </button>
                            <button type="button" class="btn btn-sm btn-warning text-dark shadow-sm" onclick="loadCardTemplate('gold')">
                                <i class="fa fa-id-card me-1"></i> গোল্ডেন কার্ড
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">কার্ডের নাম / শিরোনাম <span class="text-danger">*</span></label>
                            <input type="text" name="card_name" id="modal_card_name" class="form-control" placeholder="যেমন: কৃষি এসএমই গোল্ড কার্ড" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">কার্ড টাইপ / কোড <span class="text-danger">*</span></label>
                            <select name="card_type" id="modal_card_type" class="form-control" required>
                                <option value="gold">Gold Card (গোল্ডেন কার্ড)</option>
                                <option value="red">Red / Normal Card (লাল / নরমাল কার্ড)</option>
                                <option value="silver">Silver Card (সিলভার কার্ড)</option>
                                <option value="custom">Custom (অন্যান্য)</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">ব্যাজ টেক্সট (Badge Text)</label>
                            <input type="text" name="badge_text" id="modal_badge_text" class="form-control" placeholder="যেমন: প্রিমিয়াম গোল্ডেন মেম্বারশিপ">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">কালার থিম</label>
                            <select name="card_color_theme" id="modal_card_color_theme" class="form-control">
                                <option value="gold">Gold (গোল্ডেন)</option>
                                <option value="red">Red (লাল)</option>
                                <option value="blue">Blue (নীল)</option>
                                <option value="green">Green (সবুজ)</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">নমুনা কার্ড নম্বর (Card No Sample)</label>
                            <input type="text" name="card_number_sample" id="modal_card_number_sample" class="form-control" placeholder="যেমন: Card No : 100001">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">মেয়াদ (Validity)</label>
                            <input type="text" name="validity" id="modal_validity" class="form-control" value="12/2030" placeholder="যেমন: 12/2030">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">মেম্বারশিপ / কার্ড ফি</label>
                            <input type="text" name="card_fee" id="modal_card_fee" class="form-control" placeholder="যেমন: ১০০০/- টাকা (কার্ড ফি) + ৫০/- টাকা (কৃষি সেবা বই)">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">বিনিয়োগ সীমা (Investment Limit)</label>
                            <input type="text" name="investment_limit" id="modal_investment_limit" class="form-control" placeholder="যেমন: সর্বনিম্ন ৫০,০০০/- থেকে সর্বোচ্চ ১,০০,০০০/- টাকা">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">লভ্যাংশ বিবরণ (Monthly Profit)</label>
                            <input type="text" name="monthly_profit" id="modal_monthly_profit" class="form-control" placeholder="যেমন: ৫০,০০০ টাকায় মাসিক ২০০০-২৫০০/- এবং ১০০,০০০ টাকায় ৪০০০-৫০০০/-">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">উত্তোলন নোটিশ (Withdrawal Notice)</label>
                            <input type="text" name="withdrawal_notice" id="modal_withdrawal_notice" class="form-control" placeholder="যেমন: ১ মাস আগে নোটিশ/মেসেজ">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">কার্ডের ছবি আপলোড করুন</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">সম্পূর্ণ ব্রোশিউর / লিফলেট ছবি (ঐচ্ছিক)</label>
                            <input type="file" name="brochure_image" class="form-control" accept="image/*">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">সংক্ষিপ্ত বিবরণ</label>
                            <textarea name="short_description" id="modal_short_description" class="form-control" rows="2" placeholder="কার্ড সম্পর্কে সংক্ষিপ্ত তথ্য..."></textarea>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">নিয়মাবলী ও সুবিধাসমূহ (প্রতি লাইনে একটি করে পয়েন্ট লিখুন) <span class="text-danger">*</span></label>
                            <textarea name="facilities" id="modal_facilities" class="form-control" rows="8" placeholder="১। প্রথমে কৃষি স্টক মার্কেটের সদস্য হতে হবে।&#10;২। সদস্য হওয়ার জন্য নির্ধারিত ফি জমা দিতে হবে।&#10;৩। নাম, মোবাইল নাম্বার ও কার্ড দিয়ে রেজিস্ট্রেশন করতে হবে।" required></textarea>
                            <small class="text-muted">টিপস: প্রতিটি পয়েন্ট নতুন লাইনে এন্টার দিয়ে লিখুন।</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">অ্যাকশন বাটন টেক্সট</label>
                            <input type="text" name="action_button_text" id="modal_action_button_text" class="form-control" value="রেজিস্ট্রেশন করুন">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">অ্যাকশন বাটন লিঙ্ক (URL)</label>
                            <input type="text" name="action_button_url" id="modal_action_button_url" class="form-control" value="/register">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">ডিসপ্লে ক্রম (Order)</label>
                            <input type="number" name="order_num" id="modal_order_num" class="form-control" value="0">
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-center pt-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="addStatusSwitch" checked>
                                <label class="form-check-label fw-bold ms-2" for="addStatusSwitch">স্ট্যাটাস সক্রিয় রাখুন (Active)</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="fa fa-save me-1"></i> সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const cardTemplates = {
        red: {
            name: "কৃষি এসএমই নরমাল কার্ড (Krishi SME Normal Card)",
            type: "red",
            badge: "নরমাল মেম্বারশিপ",
            theme: "red",
            sampleNo: "Card No : 000001",
            validity: "12/2030",
            fee: "৩০০/- টাকা (কার্ড ফি) + ৫০/- টাকা (কৃষি সেবা বই)",
            limit: "সর্বনিম্ন ৫,০০০/- থেকে সর্বোচ্চ ৫০,০০০/- টাকা",
            profit: "ওয়েব সাইটে নিজ পছন্দনীয় প্রতিটি ক্রয় করার সাথে সাথে লভ্যাংশের অংশটি উত্তোলন করতে হবে।",
            notice: "বিনিয়োগ সম্পূর্ণ উত্তোলন করিতে চাইলে ১দিন আগে মেসেজ দিবেন",
            shortDesc: "ক্ষুদ্র উদ্যোক্তাদের বেকারত্ব দূর করার লক্ষ্যে আমরা একটি বিকল্প 'কৃষি স্টক মার্কেট' নামে অনলাইন প্লাটফর্ম তৈরি করেছি। এখান ক্ষুদ্র উদ্যোগ ক্ষুদ্র পুঁজি দিয়ে একজন প্রত্যক্ষ কর্মকর্তা হওয়ার সুযোগ রয়েছে। আপনিও প্রত্যক্ষ শেয়ার বাৎসরিক কার্ড সংগ্রহ করতে পারেন।",
            facilities: "১। প্রথমে কৃষি স্টক মার্কেটের সদস্য হতে হবে। নির্ধারিত এসএমই কার্ড সংগ্রহ করে।\n২। সদস্য হওয়ার জন্য ৩০০/- টাকা দিয়ে একটি কার্ড এবং ৫০/- টাকা দিয়ে কৃষি সেবা বই সংগ্রহ করতে হবে।\n৩। নাম, মোবাইল নাম্বার ও কার্ড দিয়ে রেজিস্ট্রেশন করতে হবে।\n৪। ওয়েব সাইটে নিজ পছন্দনীয় প্রতিটি ক্রয় করার সাথে সাথে লভ্যাংশের অংশটি উত্তোলন করতে হবে।\n৫। বিনিয়োগ সম্পূর্ণ উত্তোলন করিতে চাইলে ১দিন আগে মেসেজ দিবেন।\n৬। কৃষি স্টক মার্কেট কার্ড হোল্ডার ব্যতিত অন্য কেউ বিনিয়োগ করতে পারবে না।\n৭। সদস্যদের মধ্যে যারা ন্যায্যমূল্যে খাদ্যশস্য সরবরাহ করিবে তারা কৃষি স্টক মার্কেট নির্দিষ্ট এলাকা ভিত্তিক বিপণন এজেন্ট থেকে হোম ডেলিভারি গ্রহণ করিতে হবে এবং ক্রেতা হোম ডেলিভারি চাইলে কমপক্ষে ২দিন আগে কর্তৃপক্ষকে অবগত করিতে হবে।\n৮। কৃষি এসএমই স্টক মার্কেট কার্ড হোল্ডার ব্যতিত অন্য কেউ বিনিয়োগ করিতে পারিবে না এবং অন্যান্য সকল সুবিধা গ্রহণ করিতে পারিবে না। সর্বনিম্ন ৫০০০/- থেকে সর্বোচ্চ ৫০,০০০/- টাকার মধ্যে সীমাবদ্ধ থাকবে।",
            btnText: "রেজিস্ট্রেশন করুন",
            btnUrl: "/register",
            order: 1
        },
        gold: {
            name: "কৃষি এসএমই গোল্ড কার্ড (Krishi SME Gold Card)",
            type: "gold",
            badge: "গোল্ডেন মেম্বারশিপ",
            theme: "gold",
            sampleNo: "Card No : 100001",
            validity: "12/2030",
            fee: "১০০০/- টাকা (কার্ড ফি) + ৫০/- টাকা (কৃষি সেবা বই)",
            limit: "সর্বনিম্ন ৫০,০০০/- থেকে সর্বোচ্চ ১,০০,০০০/- টাকা",
            profit: "৫০,০০০ টাকার মাসিক ২০০০-২৫০০/- এবং ১০০,০০০ টাকার ৪০০০-৫০০০/- টাকার মধ্যে সীমাবদ্ধ।",
            notice: "বিনিয়োগ সম্পূর্ণ উত্তোলন করিতে চাইলে ১মাস আগে মেসেজ দিবেন।",
            shortDesc: "আমরা ক্ষুদ্র উদ্যোক্তা তৈরীর নিমিত্তে গোল্ডেন কার্ড মেম্বারশিপ চালু করেছি। যা স্বল্প ও ক্ষুদ্র বিনিয়োগে করে নিজস্ব আইনিভাবে লাভবান হতে পারেন এবং আপনার পরিবারের সুযোগ-সুবিধা গ্রহণ করতে পারবেন এবং স্থায়ী সম্পদে প্রান্তিক হতে পারেন।",
            facilities: "১। প্রথমে কৃষি স্টক মার্কেটের সদস্য হতে হবে। নির্ধারিত এসএমই গোল্ড কার্ড সংগ্রহ করে।\n২। সদস্য হওয়ার জন্য ১০০০/- টাকা দিয়ে একটি কার্ড এবং ৫০/- টাকা দিয়ে কৃষি সেবা বই সংগ্রহ করতে হবে।\n৩। নাম, মোবাইল নাম্বার ও কার্ড দিয়ে রেজিস্ট্রেশন করতে হবে।\n৪। গোল্ডেন মেম্বারশিপ বিনিয়োগ মাসিক লভ্যাংশ ৫০,০০০ টাকায় সর্বনিম্ন ২০০০-২৫০০ টাকার মধ্যে সীমাবদ্ধ। ১০০,০০০ টাকায় বিনিয়োগ ৪০০০-৫০০০ টাকার মধ্যে সীমাবদ্ধ।\n৫। বিনিয়োগ সম্পূর্ণ উত্তোলন করিতে চাইলে ১মাস আগে মেসেজ দিবেন।\n৬। গোল্ডেন মেম্বারশীপ কৃষি পরিবারের ফ্ল্যাট, প্লট, খামার কিস্তিতে বিনিয়োগ করে ক্রয় করতে পারবে।\n৭। সদস্যদের মধ্যে যারা ন্যায্যমূল্যে খাদ্যশস্য সরবরাহ করিবে তারা কৃষি স্টক মার্কেট নির্দিষ্ট এলাকা ভিত্তিক বিপণন এজেন্ট থেকে হোম ডেলিভারি গ্রহণ করা হবে।\n৮। কৃষি এসএমই স্টক মার্কেট কার্ড হোল্ডার ব্যতিত অন্য কেউ বিনিয়োগ করিতে পারিবে না এবং অন্যান্য সকল সুবিধা গ্রহণ করিতে পারিবে না। সর্বনিম্ন ৫০,০০০/- টাকা সর্বোচ্চ ১,০০,০০০/- টাকার মধ্যে বিনিয়োগ সীমাবদ্ধ থাকবে।\n৯। গোল্ড কার্ডধারী মেয়াদান্তে একটি বিনিয়োগ চুক্তিনামা হবে প্রতিষ্ঠানের নিজস্ব প্যাডে।",
            btnText: "গোল্ড কার্ড নিন",
            btnUrl: "/register",
            order: 2
        }
    };

    function loadCardTemplate(type) {
        const tpl = cardTemplates[type];
        if (!tpl) return;
        
        document.getElementById('modal_card_name').value = tpl.name;
        document.getElementById('modal_card_type').value = tpl.type;
        document.getElementById('modal_badge_text').value = tpl.badge;
        document.getElementById('modal_card_color_theme').value = tpl.theme;
        document.getElementById('modal_card_number_sample').value = tpl.sampleNo;
        document.getElementById('modal_validity').value = tpl.validity;
        document.getElementById('modal_card_fee').value = tpl.fee;
        document.getElementById('modal_investment_limit').value = tpl.limit;
        document.getElementById('modal_monthly_profit').value = tpl.profit;
        document.getElementById('modal_withdrawal_notice').value = tpl.notice;
        document.getElementById('modal_short_description').value = tpl.shortDesc;
        document.getElementById('modal_facilities').value = tpl.facilities;
        document.getElementById('modal_action_button_text').value = tpl.btnText;
        document.getElementById('modal_action_button_url').value = tpl.btnUrl;
        document.getElementById('modal_order_num').value = tpl.order;
    }
</script>
@endsection
