<!DOCTYPE html>
<html>
<head>
    <title>Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header img { max-width: 150px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .text-end { text-align: right; }
        .total { font-weight: bold; font-size: 1.1em; }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('assets/media/logos/system-logo-v2.png') }}" alt="Company Logo">
        <h2>{{ $companyName }}</h2>
        <p>{{ $companyAddress }}</p>
    </div>

    <p><strong>Receipt Ref:</strong> {{ $summary['reference_id'] }}</p>
    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($summary['date'])->format('d/m/Y') }}</p>
    <p><strong>Buyer:</strong> {{ $summary['buyer'] }}</p>
    <p><strong>Payment Method:</strong> {{ ucfirst($summary['payment_method']) }}</p>
    <p><strong>Remark:</strong> {{ $summary['remark'] ?? '-' }}</p>

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
                <td colspan="5" class="text-end">Subtotal:</td>
                <td>{{ number_format($summary['subtotal'], 2) }}</td>
            </tr>
            <tr>
                <td colspan="5" class="text-end">Discount:</td>
                <td>{{ number_format($summary['discount'], 2) }}</td>
            </tr>
            <tr class="total">
                <td colspan="5" class="text-end">Final Amount:</td>
                <td>{{ number_format($summary['total'], 2) }}</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
