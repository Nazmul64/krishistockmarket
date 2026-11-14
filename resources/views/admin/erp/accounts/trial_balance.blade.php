@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 no-print">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-balance-scale text-primary me-2"></i> রেওয়ামিল বিবরণী (Trial Balance Report)</h4>
                    <p class="text-muted small mb-0">প্রতিষ্ঠানের সকল ডেবিট ও ক্রেডিট লেজার অ্যাকাউন্টের গাণিতিক শুদ্ধতা ও ব্যালেন্স যাচাইকরণ প্রতিবেদন।</p>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-sm btn-dark rounded-pill px-3 shadow-sm">
                        <i class="fa fa-print me-1"></i> প্রতিবেদন প্রিন্ট
                    </button>
                    <a href="{{ route('admin.erp.accounts.profit_and_loss') }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                        <i class="fa fa-line-chart me-1"></i> লাভ-ক্ষতি বিবরণী
                    </a>
                    <a href="{{ route('admin.erp.accounts.balance_sheet') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                        <i class="fa fa-pie-chart me-1"></i> ব্যালেন্স শীট
                    </a>
                </div>
            </div>

            <!-- Balanced vs Unbalanced Alert -->
            @php $isBalanced = (abs($totalDebit - $totalCredit) < 0.01); @endphp
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4 border-start border-4 {{ $isBalanced ? 'border-success' : 'border-danger' }}">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <span class="badge {{ $isBalanced ? 'bg-success' : 'bg-danger' }} text-uppercase fw-bold mb-1">
                            {{ $isBalanced ? 'সিস্টেম ব্যালেন্সড (Balanced)' : 'অসামঞ্জস্য (Discrepancy)' }}
                        </span>
                        <h4 class="fw-bold text-dark mb-0">
                            {{ $isBalanced ? 'সকল খতিয়ান গাণিতিকভাবে নির্ভুল ও সমতায় রয়েছে' : 'ডেবিট ও ক্রেডিটের মধ্যে পার্থক্য বিদ্যমান' }}
                        </h4>
                        <small class="text-muted">প্রতিবেদন প্রকাশের তারিখ: {{ date('d F, Y (h:i A)') }}</small>
                    </div>
                    <div class="d-flex gap-4">
                        <div class="text-end">
                            <small class="text-muted text-uppercase fw-bold">সর্বমোট ডেবিট (Total Debit)</small>
                            <h3 class="fw-bold text-primary mb-0">৳ {{ number_format($totalDebit, 2) }}</h3>
                        </div>
                        <div class="text-end">
                            <small class="text-muted text-uppercase fw-bold">সর্বমোট ক্রেডিট (Total Credit)</small>
                            <h3 class="fw-bold text-success mb-0">৳ {{ number_format($totalCredit, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trial Balance Table -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="text-muted small text-uppercase">
                                <th class="ps-4" style="width: 15%;">কোড (Code)</th>
                                <th style="width: 45%;">হিসাব খাতের বিবরণ (Account Head)</th>
                                <th style="width: 15%;">শ্রেণি (Type)</th>
                                <th style="width: 15%;" class="text-end">ডেবিট (Debit ৳)</th>
                                <th style="width: 15%;" class="text-end pe-4">ক্রেডিট (Credit ৳)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($accounts as $acc)
                                <tr>
                                    <td class="ps-4 font-monospace fw-bold text-primary">{{ $acc->account_code }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $acc->account_name }}</div>
                                        @if($acc->account_name_bn)
                                            <small class="text-muted">{{ $acc->account_name_bn }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-muted border text-uppercase">{{ $acc->root_type }}</span>
                                    </td>
                                    <td class="text-end fw-bold text-primary font-size-14">
                                        @if($acc->normal_balance == 'debit')
                                            ৳ {{ number_format($acc->current_balance, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-end pe-4 fw-bold text-success font-size-14">
                                        @if($acc->normal_balance == 'credit')
                                            ৳ {{ number_format($acc->current_balance, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold bg-light font-size-15">
                                <td colspan="3" class="text-end ps-4">সর্বমোট যোগফল (Grand Total):</td>
                                <td class="text-end text-primary">৳ {{ number_format($totalDebit, 2) }}</td>
                                <td class="text-end pe-4 text-success">৳ {{ number_format($totalCredit, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </section>
    </div>
</div>
@endsection

