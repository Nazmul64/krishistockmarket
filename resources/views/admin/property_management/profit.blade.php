@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <h3 class="page-title text-dark fw-bold mb-1">
                <i class="fa fa-percent text-success me-2"></i> মুনাফা হিসাব ও বণ্টন ইঞ্জিন (Profit & Loss Engine)
            </h3>
        </div>

        <section class="content">
            <div class="box bg-white border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3">প্রকল্প আর্থিক রিকনসিলিয়েশন ও লভ্যাংশ বণ্টন শিট</h5>
                <p class="text-muted small">এসআরএস অনুচ্ছেদ ১০ অনুযায়ী গ্রস সেলস থেকে অনুমোদিত খরচ, ১০% রিজার্ভ এবং ৫% ট্যাক্স কর্তনের পর বণ্টনযোগ্য মুনাফা প্রস্তুত করা হয়।</p>
                
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>বিবরণ (Financial Item)</th>
                                <th class="text-end">পরিমাণ (BDT ৳)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>মোট বিক্রয় রাজস্ব (Total Sales Revenue)</td>
                                <td class="text-end font-monospace fw-bold text-success">৳ ১,০০,০০,০০০</td>
                            </tr>
                            <tr>
                                <td>অনুমোদিত নির্মাণ ও উন্নয়ন খরচ (Construction Cost)</td>
                                <td class="text-end font-monospace text-danger">- ৳ ৪০,০০,০০০</td>
                            </tr>
                            <tr>
                                <td>জমি ক্রয় বাবদ খরচ (Land Purchase Cost)</td>
                                <td class="text-end font-monospace text-danger">- ৳ ২৫,০০,০০০</td>
                            </tr>
                            <tr>
                                <td>মার্কেটিং ও প্রশাসনিক খরচ</td>
                                <td class="text-end font-monospace text-danger">- ৳ ৫,০০,০০০</td>
                            </tr>
                            <tr class="table-light">
                                <td><strong>সমন্বয়ের পূর্বে মোট লাভ</strong></td>
                                <td class="text-end font-monospace fw-bold text-dark">৳ ৩০,০০,০০০</td>
                            </tr>
                            <tr>
                                <td>জরুরি ঝুঁকি তহবিল রিজার্ভ (Contingency Reserve 10%)</td>
                                <td class="text-end font-monospace text-warning">- ৳ ৩,০০,০০০</td>
                            </tr>
                            <tr>
                                <td>সরকারি কর প্রভিশন (Tax Provision 5%)</td>
                                <td class="text-end font-monospace text-warning">- ৳ ১,৫০,০০০</td>
                            </tr>
                            <tr class="table-success">
                                <td><strong class="text-success">চূড়ান্ত বণ্টনযোগ্য নিট মুনাফা (Distributable Profit)</strong></td>
                                <td class="text-end font-monospace fw-bold text-success font-size-16">৳ ২৫,৫০,০০০</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <div>
                        <span class="badge bg-warning text-dark p-2 me-2">মেকার-চেকার ডুয়েল স্ট্যাটাস: ফিন্যান্স ম্যানেজার অনুমোদিত</span>
                        <span class="badge bg-success text-white p-2">ডিরেক্টর অনুমোদন প্রস্তুত</span>
                    </div>
                    <button class="btn btn-success fw-bold px-4" onclick="alert('মুনাফা বণ্টন সফলভাবে কার্যকর ও ইনভেস্টর ওয়ালেটে স্থানান্তর সম্পন্ন হয়েছে!');">
                        <i class="fa fa-check me-1"></i> লভ্যাংশ বণ্টন অনুমোদন ও পে-আউট
                    </button>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
