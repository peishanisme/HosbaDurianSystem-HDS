<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>

    <div style="text-align: center; margin-bottom: 20px;">
        <img src="{{ public_path('assets/media/logos/system-logo-v2.png') }}" style="height: 150px;">
    </div>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header .date {
            font-size: 11px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
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
        <div class="date">
            Generated on {{ now()->format('d M Y, H:i') }}
        </div>
    </div>

    {{-- Data Table --}}
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
                        <td>
                            {{ data_get($row, $field) }}
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) }}" style="text-align: center;">
                        No records found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p>Total records: {{ $data->count() }}</p>

    {{-- Footer --}}
    <div class="footer">
        Hosba Durian Sdn Bhd | Confidential
    </div>

</body>

</html>
