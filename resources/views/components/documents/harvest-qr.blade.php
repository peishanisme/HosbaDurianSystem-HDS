<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: sans-serif;
        }

        .grid {
            display: flex;
            flex-wrap: wrap;
        }

        .qr-item {
            width: 25%;
            /* 4 per row */
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="grid">
        @for ($i = 0; $i < $quantity; $i++)
            <div class="qr-item">
                {!! QrCode::size(100)->generate($tree->uuid) !!}
                <div>{{ $tree->tree_tag }}</div>
            </div>
        @endfor
    </div>

</body>

</html>
