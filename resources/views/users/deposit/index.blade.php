@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title">টাকা ডিপোজিট করুন (Deposit Money)</h4>
                    <p class="text-muted mb-0">আপনার ওয়ালেট ব্যালেন্স বাড়ানোর জন্য বিকাশ, নগদ বা ব্যাংকের মাধ্যমে ডিপোজিট করুন</p>
                </div>
                <div>
                    <a href="{{ route('user.wallet.ledger') }}" class="btn btn-info btn-sm"><i class="fa fa-list-alt me-1"></i> ওয়ালেট লেজার হিস্ট্রি</a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- Deposit Request Form -->
                <div class="col-xl-4 col-lg-5 mb-4">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-plus-circle me-2 text-success"></i> ডিপোজিট রিকোয়েস্ট পাঠান</h4>
                        </div>
                        <div class="box-body">
                            <!-- Customer Info Header -->
                            <div class="alert alert-primary py-2 mb-3">
                                <small class="d-block text-muted">আপনার আইডি ও কার্ড নাম্বার:</small>
                                <strong>Customer ID: #{{ Auth::id() }}</strong><br>
                                <strong style="font-family: monospace;"><i class="fa fa-credit-card me-1"></i>Card: {{ $user_card }}</strong><br>
                                <span class="badge bg-success mt-1">বর্তমান ব্যালেন্স: ৳{{ number_format(Auth::user()->balance, 2) }}</span>
                            </div>

                            <form method="POST" action="{{ route('user.deposit.post') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ডিপোজিট পরিমাণ (৳) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="deposit_amount" min="10" step="0.01" required placeholder="যেমন: 5000">
                                    @error('deposit_amount') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">পেমেন্ট মেথড নির্বাচন করুন <span class="text-danger">*</span></label>
                                    <select name="payment_method" class="form-control" required>
                                        <option value="">-- পেমেন্ট মেথড বেছে নিন --</option>
                                        <option value="bKash (বিকাশ)">bKash (বিকাশ)</option>
                                        <option value="Nagad (নগদ)">Nagad (নগদ)</option>
                                        <option value="Rocket (রকেট)">Rocket (রকেট)</option>
                                        <option value="Bank Transfer (ব্যাংক)">Bank Transfer (ব্যাংক ট্রান্সফার)</option>
                                    </select>
                                    @error('payment_method') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Payment Number info -->
                                @if(count($payment_systems) > 0)
                                    <div class="alert alert-secondary py-2 mb-3">
                                        <small class="fw-bold d-block text-dark mb-1">পেমেন্ট পাঠানোর অফিশিয়াল নাম্বারসমূহ:</small>
                                        <ul class="mb-0 ps-3 small text-muted">
                                            @foreach($payment_systems as $sys)
                                                <li><strong>{{ $sys->pay_s_name }}:</strong> {{ $sys->pay_s_number }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">যে নাম্বার থেকে পেমেন্ট করেছেন</label>
                                    <input type="text" class="form-control" name="pay_from_number" placeholder="যেমন: 01711******">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ট্রানজেকশন আইডি / রেফারেন্স (Trx ID)</label>
                                    <input type="text" class="form-control" name="trx_number" placeholder="যেমন: 8N76HGTR">
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold">পেমেন্ট প্রুফ স্ক্রিনশট (Screenshot)</label>
                                    <input type="file" class="form-control" name="screenshot">
                                </div>

                                <button type="submit" class="btn btn-success w-100 py-2"><i class="fa fa-paper-plane me-1"></i> ডিপোজিট রিকোয়েস্ট জমা দিন</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Deposit History Table -->
                <div class="col-xl-8 col-lg-7">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-history me-2 text-primary"></i> আমার ডিপোজিট হিস্ট্রি ({{ count($deposits) }})</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>SN.</th>
                                            <th>পরিমাণ (৳)</th>
                                            <th>পেমেন্ট মেথড</th>
                                            <th>প্রেরক নাম্বার & Trx ID</th>
                                            <th class="text-center">স্ক্রিনশট</th>
                                            <th class="text-center">তারিখ</th>
                                            <th class="text-center">স্ট্যাটাস</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($deposits as $key => $item)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td class="fw-bold text-success">৳{{ number_format($item->deposit_amount, 2) }}</td>
                                                <td><span class="badge bg-info">{{ $item->payment_method }}</span></td>
                                                <td>
                                                    <small class="d-block text-dark">From: {{ $item->pay_from_number ?? 'N/A' }}</small>
                                                    <small class="text-muted">Trx: <code>{{ $item->trx_number ?? 'N/A' }}</code></small>
                                                </td>
                                                <td class="text-center">
                                                    @if(!empty($item->screenshot) && file_exists(public_path('upload/payment/'.$item->screenshot)))
                                                        <a href="{{ asset('upload/payment/'.$item->screenshot) }}" target="_blank">
                                                            <img src="{{ asset('upload/payment/'.$item->screenshot) }}" style="width: 45px; height: 45px; object-fit: cover; border-radius: 6px; border: 1px solid #ccc;">
                                                        </a>
                                                    @else
                                                        <span class="text-muted small">No Image</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y h:i A') }}</td>
                                                <td class="text-center">
                                                    @if($item->status == 'pending')
                                                        <span class="badge bg-warning text-dark py-2 px-3"><i class="fa fa-clock-o me-1"></i>Pending</span>
                                                    @elseif($item->status == 'approved')
                                                        <span class="badge bg-success py-2 px-3"><i class="fa fa-check-circle me-1"></i>Approved</span>
                                                    @else
                                                        <span class="badge bg-danger py-2 px-3"><i class="fa fa-times-circle me-1"></i>Rejected</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted">আপনার কোনো ডিপোজিট হিস্ট্রি পাওয়া যায়নি।</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
</div>
@endsection
