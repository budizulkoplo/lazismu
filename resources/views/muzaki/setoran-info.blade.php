<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cara Setoran</title>
    @include('muzaki.partials.pwa-head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        :root { --brand: #fc8c04; --brand-dark: #d97706; --ink: #172033; --muted: #64748b; }
        body { background: linear-gradient(180deg, #fff7ed 0%, #ffffff 50%, #f7f9fb 100%); color: var(--ink); }
        .page-shell { max-width: 540px; }
        .top-card { background: linear-gradient(135deg, var(--brand) 0%, #ffb14a 100%); color: #fff; border-radius: 18px; box-shadow: 0 14px 35px rgba(252, 140, 4, .22); }
        .info-card, .program-card { border: 0; border-radius: 16px; box-shadow: 0 12px 30px rgba(15, 23, 42, .08); overflow: hidden; }
        .step { width: 34px; height: 34px; border-radius: 50%; background: var(--brand); color: #fff; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; flex: 0 0 auto; }
        .btn-orange { background: var(--brand); border-color: var(--brand); color: #fff; font-weight: 800; min-height: 50px; border-radius: 14px; }
        .btn-orange:hover { background: var(--brand-dark); border-color: var(--brand-dark); color: #fff; }
        .muted { color: var(--muted); }
        .program-banner { aspect-ratio: 16 / 7; object-fit: cover; background: #ffedd5; }
    </style>
</head>
<body>
    <div class="container page-shell py-3 py-sm-4">
        <div class="top-card p-3 mb-3">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div>
                    <div class="small opacity-75">Informasi Setoran</div>
                    <h1 class="h5 fw-bold mb-1">{{ $muzaki->nama }}</h1>
                    <div class="small opacity-75">{{ $muzaki->login_code }}</div>
                </div>
                <a href="{{ route('muzaki.mobile') }}" class="btn btn-light fw-bold">Beranda</a>
            </div>
        </div>

        <div class="card info-card mb-4">
            <div class="card-body p-3">
                <h2 class="h5 fw-bold mb-3">Cara Melakukan Setoran</h2>
                <div class="d-grid gap-3">
                    <div class="d-flex gap-3">
                        <span class="step">1</span>
                        <div>
                            <div class="fw-bold">Datang ke petugas/admin Lazismu</div>
                            <div class="muted">Sampaikan jenis setoran: zakat, infaq, atau program.</div>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <span class="step">2</span>
                        <div>
                            <div class="fw-bold">Tunjukkan kartu atau nomor muzaki</div>
                            <div class="muted">Admin akan scan barcode kartu atau mencari data Anda.</div>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <span class="step">3</span>
                        <div>
                            <div class="fw-bold">Serahkan nominal setoran</div>
                            <div class="muted">Admin mencatat transaksi dan mencetak nota setoran.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="h5 fw-bold mb-2">Program yang Bisa Dipilih</h2>
        <div class="d-grid gap-3 mb-4">
            @forelse($programs as $program)
                @php
                    $target = (float) ($program->target ?? 0);
                    $terkumpul = (float) ($program->terkumpul ?? 0);
                    $progress = $target > 0 ? min(100, ($terkumpul / $target) * 100) : 0;
                @endphp
                <div class="card program-card">
                    @if($program->banner_path)
                        <img src="{{ asset('storage/' . $program->banner_path) }}" class="program-banner w-100" alt="{{ $program->nama_program }}">
                    @endif
                    <div class="card-body p-3">
                        <div class="fw-bold">{{ $program->nama_program }}</div>
                        <div class="small muted mb-2">Terkumpul Rp {{ number_format($terkumpul, 0, ',', '.') }} dari Rp {{ number_format($target, 0, ',', '.') }}</div>
                        <div class="progress" style="height: 10px; background: #ffe4c7;">
                            <div class="progress-bar" style="width: {{ $progress }}%; background: #fc8c04;"></div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">Belum ada program aktif.</div>
            @endforelse
        </div>

        <a href="{{ route('muzaki.mobile') }}" class="btn btn-orange w-100">Kembali ke Dashboard</a>
    </div>
    @include('muzaki.partials.pwa-install')
</body>
</html>
