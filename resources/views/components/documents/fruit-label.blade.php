<!DOCTYPE html>
<html>

<head>
    <title>Print QR</title>

    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        .sheet {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
        }

        .label {
            border: 1px solid #000;
            height: 130px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    
</head>

<body onload="window.print()">

    <div class="sheet">
        @for ($i = 0; $i < $qty; $i++)
            <div class="label">
                <div id="qr-{{ $i }}"></div>
                <div style="font-size:12px;margin-top:10px;">
                    {{ $tree->tree_tag }}
                </div>
            </div>
        @endfor
    </div>

    <script>
        @for ($i = 0; $i < $qty; $i++)
            new QRCode(document.getElementById("qr-{{ $i }}"), {
                text: @json($url),
                width: 70,
                height: 70
            });
        @endfor
    </script>

</body>

</html>
