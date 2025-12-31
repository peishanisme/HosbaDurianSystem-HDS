<!DOCTYPE html>
<html>

<head>
    <title>Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 150px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
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
            font-size: 1.1em;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('assets/media/logos/system-logo-v2.png') }}" style="height: 150px;">
        <h2>{{ $companyName }}</h2>
        <p>{{ $companyAddress }}</p>
    </div>

    <p><strong>Date:</strong> {{ $transaction->date->format('d/m/Y H:i') }}</p>
    <p><strong>Buyer:</strong> {{ $transaction->buyer->company_name ?? '-' }}</p>
    <p><strong>Payment Method:</strong> {{ ucfirst($transaction->payment_method) ?? '-' }}</p>
    <p><strong>Remark:</strong> {{ $transaction->remark ?? '-' }}</p>

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
            @foreach ($summary as $item)
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
                <td colspan="5" class="text-end">Subtotal:</td>
                <td>{{ number_format($calculatedSubtotal ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td colspan="5" class="text-end">Discount:</td>
                <td>{{ number_format($transaction->discount ?? 0, 2) }}</td>
            </tr>
            <tr class="total">
                <td colspan="5" class="text-end">Final Amount:</td>
                <td>{{ number_format($transaction->total_price ?? 0, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>

</html>
