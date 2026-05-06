<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kartu Muzaki</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; color: #111827; }
        .card { width: 520px; height: 310px; border: 1px solid #d1d5db; border-radius: 8px; padding: 24px; display: flex; justify-content: space-between; gap: 20px; background: #f8fafc; }
        .brand { color: #0f766e; font-size: 22px; font-weight: 800; margin-bottom: 22px; }
        .name { font-size: 24px; font-weight: 800; margin-bottom: 8px; }
        .meta { font-size: 15px; margin-bottom: 6px; }
        .qr { text-align: center; background: #fff; padding: 12px; border-radius: 8px; }
        @media print { button { display: none; } }
    </style>
</head>
<body>
    <button onclick="window.print()">Cetak</button>
    <div class="card">
        <div>
            <div class="brand">Kartu Anggota Muzaki</div>
            <div class="name">{{ $muzaki->nama }}</div>
            <div class="meta">Jenis: {{ ucfirst($muzaki->jenis_muzaki ?? 'pribadi') }}</div>
            <div class="meta">ID: {{ $muzaki->login_code }}</div>
            <div class="meta">NIK: {{ $muzaki->nik ?: '-' }}</div>
            <div class="meta">No HP: {{ $muzaki->no_hp ?: '-' }}</div>
        </div>
        <div class="qr">
            {!! QrCode::size(145)->generate($muzaki->login_code) !!}
            <div style="margin-top:8px;font-weight:700;">{{ $muzaki->login_code }}</div>
        </div>
    </div>
</body>
</html>
