@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 no-print">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-list-alt text-primary me-2"></i> জেনারেল লেজার ও খতিয়ান (General Ledger)</h4>
                    <p class="text-muted small mb-0">প্রতিটি হিসাব খাতের পুঙ্খানুপুঙ্খ লেনদেন বিবরণী, ডেবিট-ক্রেডিট হিসাব এবং রানিং জের (Running Balance)।</p>
                </div>
                <div class="d-flex gap-2">
                    @if($selectedAccount)
                        <button onclick="window.print()" class="btn btn-sm btn-dark rounded-pill px-3 shadow-sm">
                            <i class="fa fa-print me-1"></i> লেজার প্রিন্ট
                        </button>
                    @endif
                    <a href="{{ route('admin.erp.accounts.chart_of_accounts') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="fa fa-sitemap me-1"></i> চার্ট অব অ্যাকাউন্টস
                    </a>
                </div>
            </div>

            <!-- Filter & Account Selector Form -->
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4 no-print">
                <form action="{{ route('admin.erp.accounts.general_ledger') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-muted mb-1">লেজার অ্যাকাউন্ট নির্বাচন করুন <span class="text-danger">*</span></label>
                        <select name="account_id" class="form-select form-select-sm" required>
                            <option value="">-- অ্যাকাউন্ট বেছে নিন --</option>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}" {{ (request('account_id') == $acc->id || ($selectedAccount && $selectedAccount->id == $acc->id)) ? 'selected' : '' }}>
                                    [{{ $acc->account_code }}] {{ $acc->account_name }} ({{ strtoupper($acc->root_type) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted mb-1">শুরুর তারিখ</label>
                        <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted mb-1">শেষের তারিখ</label>
                        <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-sm btn-primary w-100 rounded-3 mt-4"><i class="fa fa-filter"></i></button>
                    </div>
                </form>
            </div>

            @if($selectedAccount)
                <!-- Ledger Header Information -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4 border-start border-4 border-primary">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <span class="badge bg-light text-dark border font-monospace mb-1">ACCOUNT CODE: {{ $selectedAccount->account_code }}</span>
                            <h4 class="fw-bold text-dark mb-1">{{ $selectedAccount->account_name }}</h4>
                            @if($selectedAccount->account_name_bn)
                                <div class="text-muted">{{ $selectedAccount->account_name_bn }}</div>
                            @endif
                            <div class="small text-muted mt-1">
                                মূল শ্রেণি: <strong class="text-primary text-uppercase">{{ $selectedAccount->root_type }}</strong> | 
                                স্বাভাবিক ব্যালেন্স: <strong class="text-dark text-uppercase">{{ $selectedAccount->normal_balance }}</strong>
                            </div>
                        </div>
                        <div class="col-md-5 text-md-end mt-3 mt-md-0">
                            <div class="d-inline-block text-start p-3 bg-light rounded-3">
                                <div class="small text-muted">প্রারম্ভিক জের (Opening): <strong>৳ {{ number_format($openingBalance, 2) }}</strong></div>
                                <div class="font-size-16 fw-bold text-dark mt-1">
                                    বর্তমান সমাপনী জের: <span class="text-success">৳ {{ number_format($selectedAccount->current_balance, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ledger Entries Table -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="text-muted small text-uppercase">
                                    <th class="ps-4">তারিখ</th>
                                    <th>রেফারেন্স / ভাউচার নং</th>
                                    <th>খাতের বিবরণ (Particulars)</th>
                                    <th class="text-end">ডেবিট (Debit ৳)</th>
                                    <th class="text-end">ক্রেডিট (Credit ৳)</th>
                                    <th class="text-end pe-4">রানিং জের (Balance ৳)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-light fw-bold">
                                    <td class="ps-4" colspan="3">প্রারম্ভিক জের (Opening Balance)</td>
                                    <td class="text-end">-</td>
                                    <td class="text-end">-</td>
                                    <td class="text-end pe-4">৳ {{ number_format($openingBalance, 2) }}</td>
                                </tr>

                                @php
                                    $running = $openingBalance;
                                    $totDeb = 0;
                                    $totCred = 0;
                                    $isDebitNormal = ($selectedAccount->normal_balance === 'debit');
                                @endphp

                                @forelse($ledgerEntries as $entry)
                                    @php
                                        $totDeb += $entry->debit;
                                        $totCred += $entry->credit;
                                        if ($isDebitNormal) {
                                            $running += ($entry->debit - $entry->credit);
                                        } else {
                                            $running += ($entry->credit - $entry->debit);
                                        }
                                    @endphp
                                    <tr>
                                        <td class="ps-4 text-muted font-size-13">
                                            {{ $entry->transaction_date ? \Carbon\Carbon::parse($entry->transaction_date)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="font-monospace fw-bold text-primary">
                                            {{ $entry->reference_number ?? '-' }}
                                        </td>
                                        <td>
                                            <div class="text-dark font-size-13">{{ $entry->description ?? 'Transaction' }}</div>
                                            <small class="text-muted">{{ strtoupper(str_replace('_', ' ', $entry->reference_type)) }}</small>
                                        </td>
                                        <td class="text-end fw-bold text-primary">
                                            {{ $entry->debit > 0 ? number_format($entry->debit, 2) : '-' }}
                                        </td>
                                        <td class="text-end fw-bold text-success">
                                            {{ $entry->credit > 0 ? number_format($entry->credit, 2) : '-' }}
                                        </td>
                                        <td class="text-end pe-4 fw-bold font-size-14 {{ $running < 0 ? 'text-danger' : 'text-dark' }}">
                                            ৳ {{ number_format($running, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            নির্বাচিত সময়সীমায় এই অ্যাকাউন্টে কোনো অতিরিক্ত লেনদেন হয়নি।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold bg-light font-size-14">
                                    <td colspan="3" class="text-end ps-4">মোট লেনদেন যোগফল:</td>
                                    <td class="text-end text-primary">৳ {{ number_format($totDeb, 2) }}</td>
                                    <td class="text-end text-success">৳ {{ number_format($totCred, 2) }}</td>
                                    <td class="text-end pe-4 text-dark font-size-15">৳ {{ number_format($running, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                    <i class="fa fa-folder-open-o fa-4x text-muted opacity-50 mb-3"></i>
                    <h5 class="fw-bold text-dark">কোনো অ্যাকাউন্ট নির্বাচন করা হয়নি</h5>
                    <p class="text-muted mb-0">উপরের ড্রপডাউন থেকে যেকোনো লেজার অ্যাকাউন্ট বেছে নিয়ে তার বিস্তারিত ডেবিট, ক্রেডিট ও রানিং জের পর্যালোচনা করুন।</p>
                </div>
            @endif

        </section>
    </div>
</div>
@endsection

