@extends('layouts.backend.app')

@section('content')
<div class="container-full">
    <div class="content-header">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h3 class="page-title">আমার অ্যাকাউন্ট স্টেটমেন্ট ও ট্রানজেকশন হিস্ট্রি</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('supplier.dashboard') }}"><i class="mdi mdi-home-outline"></i></a></li>
                    <li class="breadcrumb-item active">অ্যাকাউন্ট স্টেটমেন্ট</li>
                </ol>
            </div>
            <div>
                <a href="{{ route('supplier.invoice.print', $supplier->id) }}" target="_blank" class="btn btn-primary btn-sm me-2">
                    <i class="fa fa-print me-1"></i> প্রিন্ট / PDF স্টেটমেন্ট
                </a>
            </div>
        </div>
    </div>

    <section class="content">
        <!-- Summary Cards -->
        <div class="row">
            <div class="col-md-3 col-12">
                <div class="box bg-secondary-light">
                    <div class="box-body text-center">
                        <h5 class="text-muted">প্রারম্ভিক জের (Opening)</h5>
                        <h3 class="fw-bold">৳{{ number_format($openingBalance, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-12">
                <div class="box bg-info-light">
                    <div class="box-body text-center">
                        <h5 class="text-muted">মোট অনুমোদিত সরবরাহ (Supply)</h5>
                        <h3 class="fw-bold text-info">৳{{ number_format($totalSupply, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-12">
                <div class="box bg-success-light">
                    <div class="box-body text-center">
                        <h5 class="text-muted">মোট প্রাপ্ত পরিশোধ (Total Paid)</h5>
                        <h3 class="fw-bold text-success">৳{{ number_format($totalPaid, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-12">
                <div class="box bg-danger-light">
                    <div class="box-body text-center">
                        <h5 class="text-muted">বর্তমান পাওনা / বকেয়া (Current Due)</h5>
                        <h3 class="fw-bold text-danger">৳{{ number_format($currentBalance, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ledger Table -->
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title"><i class="fa fa-file-text-o me-2"></i> ট্রানজেকশন লেজার রেজিস্টার (Transaction Ledger)</h4>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th>তারিখ</th>
                                        <th>বিবরণ (Description)</th>
                                        <th>রেফারেন্স / ইনভয়েস</th>
                                        <th class="text-end">ডেবিট (+Supply ৳)</th>
                                        <th class="text-end">ক্রেডিট (-Paid ৳)</th>
                                        <th class="text-end">ব্যালেন্স (Balance ৳)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ledger as $row)
                                        <tr>
                                            <td>{{ $row['date'] }}</td>
                                            <td>{{ $row['description'] }}</td>
                                            <td>{{ $row['ref'] }}</td>
                                            <td class="text-end text-danger fw-bold">
                                                {{ $row['debit'] > 0 ? '৳' . number_format($row['debit'], 2) : '-' }}
                                            </td>
                                            <td class="text-end text-success fw-bold">
                                                {{ $row['credit'] > 0 ? '৳' . number_format($row['credit'], 2) : '-' }}
                                            </td>
                                            <td class="text-end fw-bold">৳{{ number_format($row['balance'], 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">কোন ট্রানজেকশন বিবরণী পাওয়া যায়নি</td>
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
@endsection
