<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Muzaki</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        :root { --brand: #fc8c04; --brand-dark: #d97706; --ink: #172033; --muted: #64748b; }
        body { background: linear-gradient(180deg, #fff7ed 0%, #ffffff 46%, #f7f9fb 100%); color: var(--ink); }
        .page-shell { max-width: 540px; }
        .top-card { background: linear-gradient(135deg, var(--brand) 0%, #ffb14a 100%); color: #fff; border-radius: 18px; box-shadow: 0 14px 35px rgba(252, 140, 4, .22); }
        .logout-btn { min-height: 42px; border-radius: 12px; font-weight: 700; white-space: nowrap; }
        .action-btn { min-height: 74px; border-radius: 16px; font-size: 1.08rem; font-weight: 800; }
        .btn-orange { background: var(--brand); border-color: var(--brand); color: #fff; }
        .btn-orange:hover { background: var(--brand-dark); border-color: var(--brand-dark); color: #fff; }
        .program-card { border: 0; border-radius: 16px; box-shadow: 0 12px 30px rgba(15, 23, 42, .08); overflow: hidden; }
        .program-banner { aspect-ratio: 16 / 7; object-fit: cover; background: #ffedd5; }
        .progress { height: 10px; background: #ffe4c7; }
        .progress-bar { background: var(--brand); }
        .muted { color: var(--muted); }
    </style>
</head>
<body>
    <div class="container page-shell py-3 py-sm-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="top-card p-3 mb-3">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div class="min-w-0">
                    <div class="small opacity-75">Muzaki</div>
                    <h1 class="h5 fw-bold mb-1 text-truncate">{{ $muzaki->nama }}</h1>
                    <div class="small opacity-75 text-truncate">{{ ucfirst($muzaki->jenis_muzaki ?? 'pribadi') }} | {{ $muzaki->login_code }}</div>
                </div>
                <form action="{{ route('muzaki.logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-light logout-btn">Logout</button>
                </form>
            </div>
        </div>

        <div class="row g-2 mb-4">
            <div class="col-6">
                <a href="{{ route('muzaki.setoran.info') }}" class="btn btn-orange action-btn w-100 d-flex align-items-center justify-content-center">Setoran</a>
            </div>
            <div class="col-6">
                <a href="{{ route('muzaki.riwayat') }}" class="btn btn-primary action-btn w-100 d-flex align-items-center justify-content-center">Riwayat</a>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-end mb-2">
            <div>
                <h2 class="h5 fw-bold mb-0">Program Berjalan</h2>
                <div class="small muted">Target dan dana yang sudah terkumpul</div>
            </div>
        </div>

        <div class="d-grid gap-3">
            @forelse($programs as $program)
                @php
                    $target = (float) ($program->target ?? 0);
                    $terkumpul = (float) ($program->terkumpul ?? 0);
                    $progress = $target > 0 ? min(100, ($terkumpul / $target) * 100) : 0;
                @endphp
                <a href="{{ route('muzaki.program.detail', $program) }}" class="card program-card text-decoration-none text-dark">
                    @if($program->banner_path)
                        <img src="{{ asset('storage/' . $program->banner_path) }}" class="program-banner w-100" alt="{{ $program->nama_program }}">
                    @else
                        <div class="program-banner d-flex align-items-center justify-content-center fw-bold text-warning">Lazismu</div>
                    @endif
                    <div class="card-body p-3">
                        <h3 class="h6 fw-bold mb-1">{{ $program->nama_program }}</h3>
                        <div class="small muted mb-3">{{ $program->lokasi ?: 'Lokasi belum diisi' }}</div>
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Terkumpul</span>
                            <strong>Rp {{ number_format($terkumpul, 0, ',', '.') }}</strong>
                        </div>
                        <div class="progress mb-2">
                            <div class="progress-bar" style="width: {{ $progress }}%"></div>
                        </div>
                        <div class="d-flex justify-content-between small muted">
                            <span>Target</span>
                            <span>Rp {{ number_format($target, 0, ',', '.') }}</span>
                        </div>
                        <div class="small fw-bold mt-2" style="color: var(--brand);">Lihat rincian program</div>
                    </div>
                </a>
            @empty
                <div class="alert alert-info">Belum ada program aktif.</div>
            @endforelse
        </div>
    </div>
</body>
</html>
