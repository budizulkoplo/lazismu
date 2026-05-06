<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Nota Setoran</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; color: #111827; }
        .nota { width: 360px; border: 1px solid #d1d5db; padding: 18px; border-radius: 8px; }
        .row { display: flex; justify-content: space-between; gap: 16px; margin: 8px 0; }
        .total { font-size: 20px; font-weight: 800; border-top: 1px dashed #9ca3af; padding-top: 12px; margin-top: 12px; }
        .center { text-align: center; }
        @media print { button, a { display: none; } body { margin: 0; } }
    </style>
</head>
<body>
    <button onclick="window.print()">Cetak Nota</button>
    <a href="{{ route('lazismu.setoran.index') }}">Kembali</a>
    <div class="nota">
        <div class="center">
            <h2>Nota Setoran</h2>
            <div>{{ optional($setoran->created_at)->format('d/m/Y') }}</div>
        </div>
        <div class="row"><span>Muzaki</span><strong>{{ $setoran->muzaki->nama ?? '-' }}</strong></div>
        <div class="row"><span>ID</span><strong>{{ $setoran->muzaki->login_code ?? '-' }}</strong></div>
        <div class="row"><span>Jenis</span><strong>{{ ucfirst($setoran->kodeSetoran->jenis_setoran ?? '-') }}</strong></div>
        <div class="row"><span>Program</span><strong>{{ $setoran->program->nama_program ?? '-' }}</strong></div>
        <div class="row total"><span>Total</span><strong>Rp {{ number_format($setoran->nominal, 0, ',', '.') }}</strong></div>
        <div class="row"><span>Bisa digunakan</span><strong>Rp {{ number_format($setoran->nominal_digunakan_calculated, 0, ',', '.') }}</strong></div>
        <div class="row"><span>PDM</span><strong>Rp {{ number_format($setoran->nominal_pdm_calculated, 0, ',', '.') }}</strong></div>
        <p class="center">Terima kasih.</p>
    </div>
    <script>window.addEventListener('load', () => window.print());</script>
</body>
</html>
