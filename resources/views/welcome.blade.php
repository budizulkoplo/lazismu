<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lazismu</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        body { background: #f7f9fb; color: #18212f; }
        .hero { background: #0f766e; color: #fff; }
        .program-card { border: 0; border-radius: 8px; overflow: hidden; box-shadow: 0 10px 28px rgba(15, 23, 42, .08); }
        .program-banner { aspect-ratio: 16 / 7; object-fit: cover; background: #dbeafe; }
        .btn-login { background: #fc8c04; border-color: #fc8c04; color: #fff; }
        .btn-login:hover { background: #de7900; border-color: #de7900; color: #fff; }
    </style>
</head>
<body>
    <section class="hero py-5">
        <div class="container py-md-4">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <h1 class="display-5 fw-bold mb-3">Lazismu</h1>
                    <p class="fs-5 mb-4">Portal informasi program, setoran zakat, infaq, dan layanan muzaki.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('muzaki.login') }}" class="btn btn-login btn-lg">Login / Scan Barcode Muzaki</a>
                        <a href="{{ route('login') }}" class="btn btn-light btn-lg">Login Admin</a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="bg-white text-dark p-4 rounded-2">
                        <div class="small text-muted mb-1">Program aktif</div>
                        <h2 class="h3 mb-0">{{ number_format($programs->count()) }} program berjalan</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main class="container py-5">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div>
                <h2 class="h3 mb-1">Program Berjalan</h2>
                <p class="text-muted mb-0">Informasi target dan dana terkumpul dari program yang masih aktif.</p>
            </div>
        </div>

        <div class="row g-3">
            @forelse($programs as $program)
                @php
                    $target = (float) ($program->target ?? 0);
                    $terkumpul = (float) ($program->terkumpul ?? 0);
                    $progress = $target > 0 ? min(100, ($terkumpul / $target) * 100) : 0;
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="card program-card h-100">
                        @if($program->banner_path)
                            <img src="{{ asset('storage/' . $program->banner_path) }}" class="program-banner w-100" alt="{{ $program->nama_program }}">
                        @else
                            <div class="program-banner d-flex align-items-center justify-content-center fw-semibold">Lazismu</div>
                        @endif
                        <div class="card-body">
                            <h3 class="h5">{{ $program->nama_program }}</h3>
                            <div class="text-muted mb-3">{{ $program->lokasi ?: 'Lokasi belum diisi' }}</div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span>Terkumpul</span>
                                <strong>Rp {{ number_format($terkumpul, 0, ',', '.') }}</strong>
                            </div>
                            <div class="progress mb-2" style="height: 10px;">
                                <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                            </div>
                            <div class="small text-muted">Target Rp {{ number_format($target, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">Belum ada program aktif.</div>
                </div>
            @endforelse
        </div>
    </main>
</body>
</html>
