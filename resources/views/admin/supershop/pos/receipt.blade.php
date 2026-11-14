<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Receipt #{{ $sale->invoice_no }}</title>
    <style>
        @page {
            margin: 0;
            size: 80mm auto;
        }
        body {
            font-family: 'Courier New', Courier, monospace, 'Segoe UI', Tahoma, sans-serif;
            font-size: 12px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 10px 8px;
            width: 76mm;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .store-title {
            font-size: 16px;
            font-weight: 900;
            margin-bottom: 2px;
            text-transform: uppercase;
        }
        .store-sub {
            font-size: 11px;
            margin-bottom: 6px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        .double-divider {
            border-top: 2px dashed #000;
            margin: 8px 0;
        }
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        .receipt-table th {
            border-bottom: 1px dashed #000;
            padding: 3px 0;
            text-align: left;
        }
        .receipt-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .totals-table {
            width: 100%;
            font-size: 12px;
        }
        .totals-table td {
            padding: 2px 0;
        }
        .grand-total {
            font-size: 14px;
            font-weight: 900;
        }
        .barcode-box {
            text-align: center;
            margin: 8px 0 4px;
            font-family: monospace;
            font-size: 14px;
            letter-spacing: 2px;
        }
        @media print {
            .no-print { display: none !important; }
            body { padding: 4px; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 10px; background: #f0f0f0; padding: 6px;">
        <button onclick="window.print()" style="padding: 6px 16px; font-weight: bold; cursor: pointer;">🖨️ প্রিন্ট করুন (Print)</button>
        <button onclick="window.close()" style="padding: 6px 12px; cursor: pointer;">বন্ধ করুন</button>
    </div>

    <div class="text-center">
        <div class="store-title">ই-কৃষি সেবা সুপারশপ</div>
        <div class="store-sub">e-Krishi Sheba Multi-Branch Retail</div>
        <div style="font-size: 11px;">
            {{ $sale->branch ? $sale->branch->name : 'Central Hub' }}<br>
            {{ $sale->branch ? $sale->branch->address : '' }}<br>
            হটলাইন: {{ $sale->branch && $sale->branch->phone ? $sale->branch->phone : '09617900200' }}
        </div>
    </div>

    <div class="divider"></div>

    <div style="font-size: 11px; line-height: 1.4;">
        <div><strong>ইনভয়েস:</strong> {{ $sale->invoice_no }}</div>
        <div><strong>তারিখ ও সময়:</strong> {{ \Carbon\Carbon::parse($sale->sale_time)->format('d/m/Y h:i A') }}</div>
        <div><strong>ক্যাশিয়ার:</strong> {{ $sale->cashier ? $sale->cashier->name : 'POS Operator' }}</div>
        <div><strong>কাউন্টার:</strong> {{ $sale->counter ? $sale->counter->counter_name : 'Counter 01' }}</div>
        @if($sale->customer_name && $sale->customer_name !== 'Walk-in Customer')
            <div><strong>কাস্টমার:</strong> {{ $sale->customer_name }} ({{ $sale->customer_phone }})</div>
        @endif
    </div>

    <div class="divider"></div>

    <!-- Items Table -->
    <table class="receipt-table">
        <thead>
            <tr>
                <th style="width: 50%;">আইটেম</th>
                <th class="text-center" style="width: 15%;">পরিমাণ</th>
                <th class="text-end" style="width: 15%;">দর</th>
                <th class="text-end" style="width: 20%;">মোট</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
                <tr>
                    <td>
                        {{ $item->product_name }}
                        @if($item->sku)
                            <div style="font-size: 9px; color: #555;">[{{ $item->sku }}]</div>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-end">{{ number_format($item->unit_price, 0) }}</td>
                    <td class="text-end fw-bold">{{ number_format($item->line_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <!-- Totals Table -->
    <table class="totals-table">
        <tr>
            <td>সাবটোটাল (Subtotal):</td>
            <td class="text-end">৳ {{ number_format($sale->subtotal, 2) }}</td>
        </tr>
        @if($sale->discount_amount > 0)
            <tr>
                <td>ডিসকাউন্ট (Discount):</td>
                <td class="text-end">- ৳ {{ number_format($sale->discount_amount, 2) }}</td>
            </tr>
        @endif
        @if($sale->tax_amount > 0)
            <tr>
                <td>ভ্যাট (VAT):</td>
                <td class="text-end">+ ৳ {{ number_format($sale->tax_amount, 2) }}</td>
            </tr>
        @endif
        <tr class="grand-total">
            <td>সর্বমোট বিল (Net Total):</td>
            <td class="text-end">৳ {{ number_format($sale->grand_total, 2) }}</td>
        </tr>
        <tr style="border-top: 1px dashed #000;">
            <td>পেমেন্ট ({{ strtoupper($sale->payment_method) }}):</td>
            <td class="text-end">৳ {{ number_format($sale->paid_amount, 2) }}</td>
        </tr>
        @if($sale->change_amount > 0)
            <tr class="fw-bold">
                <td>ফেরত (Change Return):</td>
                <td class="text-end">৳ {{ number_format($sale->change_amount, 2) }}</td>
            </tr>
        @endif
    </table>

    <div class="double-divider"></div>

    <div class="barcode-box">
        *{{ $sale->invoice_no }}*
    </div>

    <div class="text-center" style="font-size: 11px; margin-top: 8px; line-height: 1.4;">
        ধন্যবাদ, আবার আসবেন!<br>
        <strong>www.ekrishisheba.com</strong><br>
        সফটওয়্যার পরিচালনা: Antigravity Retail ERP
    </div>

    <script>
        window.addEventListener('load', function() {
            setTimeout(() => {
                window.print();
            }, 400);
        });
    </script>
</body>
</html>

