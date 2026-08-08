@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title">Marketing Agent Live Stock & Ledger (বিপণন এজেন্ট লাইভ স্টক ও লেনদেন হিসাব)</h4>
                    <p class="text-muted mb-0">সকল বিপণন এজেন্টের লাইভ মজুদ স্টক এবং মোট বিক্রির লেনদেন পর্যবেক্ষণ করুন</p>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            @if(session('success') || session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') ?? session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- Stock Allocation Form for Admin -->
                <div class="col-xl-4 col-lg-5 mb-4">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-cubes me-2 text-success"></i> এজেন্টে মালামাল/স্টক বরাদ্দ প্রদান</h4>
                        </div>
                        <div class="box-body">
                            <form method="POST" action="{{ route('admin.agent_ledger.allocate') }}">
                                @csrf

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">বিপণন এজেন্ট সিলেক্ট করুন <span class="text-danger">*</span></label>
                                    <select name="agent_id" class="form-control" required>
                                        <option value="">-- বিপণন এজেন্ট নির্বাচন করুন --</option>
                                        @foreach($agents as $ag)
                                            <option value="{{ $ag->id }}">{{ $ag->name }} ({{ $ag->phone ?? $ag->username }})</option>
                                        @endforeach
                                    </select>
                                    @error('agent_id') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">স্টক/পণ্যের নাম <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="stock_name" required placeholder="যেমন: ধান বীজ স্টক বা ৫০০০ টাকার প্যাকেজ">
                                    @error('stock_name') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label fw-bold">পরিমাণ (টি) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="quantity" min="1" required placeholder="যেমন: 20">
                                        @error('quantity') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label fw-bold">একক মূল্য (৳) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="unit_price" step="0.01" min="0" required placeholder="যেমন: 5000">
                                        @error('unit_price') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold">নোট (ঐচ্ছিক)</label>
                                    <textarea class="form-control" name="notes" rows="3" placeholder="বরাদ্দ সংক্রান্ত কোনো নোট লিখুন..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-success w-100 py-2"><i class="fa fa-check-circle me-1"></i> স্টক বরাদ্দ দিন</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Marketing Agents Live Stock Table -->
                <div class="col-xl-8 col-lg-7">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-users me-2 text-info"></i> সকল বিপণন এজেন্টের লাইভ হিসাব সমারি ({{ count($agent_summaries) }})</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>SN.</th>
                                            <th>এজেন্টের নাম ও ইনফো</th>
                                            <th class="text-center">মোট স্টক বরাদ্দ</th>
                                            <th class="text-center">মোট স্টক বিক্রি</th>
                                            <th class="text-center">লাইভ অবশিষ্ট স্টক</th>
                                            <th class="text-center">মোট লেনদেন (৳)</th>
                                            <th class="text-center" style="width: 140px;">লাইভ হিসাব</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($agent_summaries as $key => $item)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>
                                                    <strong class="text-dark">{{ $item['agent']->name }}</strong><br>
                                                    <small class="text-muted"><i class="fa fa-phone me-1"></i>{{ $item['agent']->phone ?? $item['agent']->username }}</small>
                                                </td>
                                                <td class="text-center fw-bold text-primary">{{ $item['total_allocated'] }} টি</td>
                                                <td class="text-center fw-bold text-warning">{{ $item['total_sold'] }} টি</td>
                                                <td class="text-center">
                                                    <span class="badge bg-success fs-6 py-2 px-3">{{ $item['available_stock'] }} টি</span>
                                                </td>
                                                <td class="text-center fw-bold text-success">৳{{ number_format($item['total_revenue'], 2) }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('admin.agent_ledger.show', $item['agent']->id) }}" class="btn btn-sm btn-info px-3">
                                                        <i class="fa fa-line-chart me-1"></i> লাইভ হিসাব
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted">কোনো বিপণন এজেন্ট পাওয়া যায়নি।</td>
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
