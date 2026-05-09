<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Barcode Muzaki</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; color: #111827; }
        .sheet { width: 320px; border: 1px solid #d1d5db; padding: 18px; text-align: center; border-radius: 8px; }
        .code { font-size: 18px; font-weight: 700; margin-top: 10px; }
        @media print { button { display: none; } }
    </style>
</head>
<body>
    <button onclick="window.print()">Cetak</button>
    <a href="{{ route('lazismu.muzaki.index') }}">Kembali</a>
    <div class="sheet">
        <h2>Barcode Muzaki</h2>
        <div>{!! QrCode::size(190)->generate($muzaki->login_code) !!}</div>
        <div class="code">{{ $muzaki->login_code }}</div>
        <div>{{ $muzaki->nama }}</div>
    </div>
    <script>
        window.addEventListener('load', function() {
            window.print();
        });

        window.addEventListener('afterprint', function() {
            window.location.href = @json(route('lazismu.muzaki.index'));
        });
    </script>
</body>
</html>
