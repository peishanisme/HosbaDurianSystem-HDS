<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>

    <div style="text-align: center; margin-bottom: 20px;">
        <img src="{{ public_path('assets/media/logos/system-logo-v2.png') }}" style="height: 150px;">
    </div>

    <style>
        @page {
            size: A4;
            margin: 20mm 15mm;
        }

        body {
            margin: 0;
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        /* === TABLE WRAPPER === */
        .table-wrapper {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            /* Center horizontally */
            overflow: hidden;
            /* Prevent overflow */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            /* IMPORTANT */
            page-break-inside: auto;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;

            /* === PREVENT OVERFLOW === */
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .date {
            font-size: 11px;
            color: #666;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: right;
            color: #666;
        }
    </style>

</head>

<body>

    {{-- Header --}}
    <div class="header">
        <h1>{{ $title }}</h1>
        <h5 class="date">
            Period:
            {{ \Carbon\Carbon::parse($from)->format('d M Y') }}
            –
            {{ \Carbon\Carbon::parse($to)->format('d M Y') }}
        </h5>
        <div class="date">
            Generated on {{ now()->format('d M Y, H:i') }}
        </div>
    </div>

    {{-- Data Table --}}
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    @foreach ($columns as $header => $field)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                    <tr>
                        @foreach ($columns as $field)
                            <td>{{ data_get($row, $field) }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) }}" style="text-align:center;">
                            No records found
                        </td>
                    </tr>
                @endforelse

                @if (isset($totalAmount))
                    <tr style="background-color:#f2f2f2;">
                        <td colspan="{{ count($columns) - 1 }}" style="text-align:right; font-weight:bold;">
                            Total Transaction (RM)
                        </td>
                        <td style="font-weight:bold;">
                            RM {{ number_format($totalAmount, 2) }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <p>Total records: {{ $data->count() }}</p>

    {{-- Footer --}}
    <div class="footer">
        Hosba Durian Sdn Bhd | Confidential
    </div>

</body>

</html>
