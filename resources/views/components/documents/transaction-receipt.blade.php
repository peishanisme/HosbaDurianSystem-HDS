<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 20px;
            color: #000;
        }

        .header {
            margin-bottom: 20px;
        }

        .company-name {
            font-weight: bold;
            font-size: 14px;
        }

        .invoice-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        .row {
            width: 100%;
            margin-top: 10px;
            display: table;
        }

        .col {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }

        .meta-table,
        .meta-table td {
            border: none;
            padding: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-end {
            text-align: right;
        }

        .total {
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            font-size: 11px;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
        }
    </style>
</head>

<body>

    <!-- Company Header -->
    <div class="header text-center">
        <div class="company-name">{{ $companyName }}</div> <span>1380608-D</span>
        <div>{{ $companyAddress }}</div>
    </div>

    <!-- Invoice Title -->
    <div class="invoice-title">Invoice</div>

    <!-- Billing & Delivery -->
    <div class="row">
        <div class="col">
            <span>Billing Address</span><br>
            {{ $summary['buyer'] }}<br>
            {{ $summary['billing_address'] ?? '-' }}
        </div>

        <div class="col">
            <span>Delivery Address</span><br>
            {{ $summary['buyer'] }}<br>
            {{ $summary['delivery_address'] ?? '-' }}
        </div>
    </div>

    <!-- Invoice Meta -->
    <div class="row">
        <div class="col">
            <table class="meta-table">
                <tr>
                    <td><strong>Invoice No</strong></td>
                    <td>: {{ $summary['reference_id'] }}</td>
                </tr>
                <tr>
                    <td><strong>Date</strong></td>
                    <td>: {{ \Carbon\Carbon::parse($summary['date'])->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td><strong>Currency</strong></td>
                    <td>: RM</td>
                </tr>
            </table>
        </div>

        <div class="col">
            <table class="meta-table">
                <tr>
                    <td><strong>Payment Method</strong></td>
                    <td>: {{ ucfirst($summary['payment_method']) }}</td>
                </tr>
                <tr>
                    <td><strong>Remark</strong></td>
                    <td>: {{ $summary['remark'] ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- 🔒 TABLE UNCHANGED -->
    <table>
        <thead>
            <tr>
                <th>Species</th>
                <th>Grade</th>
                <th>Count</th>
                <th>Total Weight (kg)</th>
                <th>Price / kg (RM)</th>
                <th>Subtotal (RM)</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($summary['fruit_summary'] as $item)
                <tr>
                    <td>{{ $item['species'] }}</td>
                    <td>{{ $item['grade'] }}</td>
                    <td>{{ $item['count'] }}</td>
                    <td>{{ number_format($item['total_weight'], 2) }}</td>
                    <td>{{ number_format($item['price_per_kg'], 2) }}</td>
                    <td>{{ number_format($item['subtotal'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <td colspan="5" class="text-end">Subtotal</td>
                <td>{{ number_format($summary['subtotal'], 2) }}</td>
            </tr>
            <tr>
                <td colspan="5" class="text-end">Discount</td>
                <td>{{ number_format($summary['discount'], 2) }}</td>
            </tr>
            <tr class="total">
                <td colspan="5" class="text-end">Total Payable</td>
                <td>{{ number_format($summary['total'], 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Footer -->
    <div class="footer">
        <strong>RINGGIT MALAYSIA :</strong>
        {{ \Illuminate\Support\Str::upper($summary['amount_in_words'] ?? '') }} ONLY
        <br><br>
        Notes:
        <ol>
            <li>All cheques should be crossed and made payable to {{ $companyName }}</li>
            <li>Goods sold are neither returnable nor refundable</li>
        </ol>
    </div>

    <div class="signature">
        Authorised Signature<br><br>
        _______________________
    </div>

</body>
</html>
