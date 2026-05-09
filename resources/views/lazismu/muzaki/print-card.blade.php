<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kartu Muzaki</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; color: #111827; }
        .card {
            width: 520px;
            height: 310px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 20px 22px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: #fffaf3;
            position: relative;
        }
        .header { display: flex; align-items: flex-start; }
        .logo { width: 168px; max-height: 64px; object-fit: contain; }
        .title { color: #fc8c04; font-size: 20px; font-weight: 800; line-height: 1.1; margin-bottom: 6px; }
        .content { display: flex; justify-content: space-between; gap: 20px; align-items: flex-end; }
        .name { font-size: 24px; font-weight: 800; margin-bottom: 8px; }
        .meta { font-size: 15px; margin-bottom: 6px; }
        .qr { text-align: center; background: #fff; padding: 12px; border-radius: 8px; border: 1px solid #f6d8b2; }
        @media print { button, a { display: none; } body { margin: 0; } }
    </style>
</head>
<body>
    <button onclick="window.print()">Cetak</button>
    <a href="{{ route('lazismu.muzaki.index') }}">Kembali</a>

    <div class="card">
        <div class="header">
            <img src="{{ asset('logopt/1776842680_lazismu.png') }}" class="logo" alt="Lazismu">
        </div>

        <div class="content">
            <div>
                <div class="title">Kartu Muzaki</div>
                <div class="name">{{ $muzaki->nama }}</div>
                <div class="meta">Jenis: {{ ucfirst($muzaki->jenis_muzaki ?? 'pribadi') }}</div>
                <div class="meta">ID: {{ $muzaki->login_code }}</div>
                <div class="meta">NIK: {{ $muzaki->nik ?: '-' }}</div>
                <div class="meta">No HP: {{ $muzaki->no_hp ?: '-' }}</div>
            </div>
            <div class="qr">
                {!! QrCode::size(130)->generate($muzaki->login_code) !!}
                <div style="margin-top:8px;font-weight:700;">{{ $muzaki->login_code }}</div>
            </div>
        </div>
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
