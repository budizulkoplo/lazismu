<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Muzaki</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        :root { --brand: #fc8c04; --brand-dark: #d97706; --ink: #172033; --muted: #64748b; }
        body { background: linear-gradient(180deg, #fff7ed 0%, #ffffff 45%, #f7f9fb 100%); color: var(--ink); font-size: 17px; }
        .page-shell { max-width: 540px; }
        .hero { background: linear-gradient(135deg, var(--brand) 0%, #ffb14a 100%); color: #fff; border-radius: 18px; box-shadow: 0 14px 35px rgba(252, 140, 4, .22); }
        .action-btn { min-height: 78px; border-radius: 14px; font-size: 1.08rem; font-weight: 800; white-space: normal; }
        .program-card, .history-card, .summary-card { border: 0; border-radius: 14px; box-shadow: 0 12px 30px rgba(15, 23, 42, .08); overflow: hidden; }
        .program-banner { aspect-ratio: 16 / 7; object-fit: cover; background: #dbeafe; }
        .label-text { font-size: .78rem; text-transform: uppercase; color: var(--muted); }
        .btn-orange { background: var(--brand); border-color: var(--brand); color: #fff; }
        .btn-orange:hover { background: var(--brand-dark); border-color: var(--brand-dark); color: #fff; }
        .table { font-size: .9rem; }
    </style>
</head>
<body>
    <div class="container page-shell py-4 py-md-5">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="hero p-4 mb-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="opacity-75 mb-1">Assalamu'alaikum</div>
                    <h1 class="h2 mb-2">{{ $muzaki->nama }}</h1>
                    <div>{{ ucfirst($muzaki->jenis_muzaki ?? 'pribadi') }} | ID {{ $muzaki->login_code }}</div>
                    @if($muzaki->target_setoran > 0)
                        <div class="mt-2">Target setoran: Rp {{ number_format($muzaki->target_setoran, 0, ',', '.') }}</div>
                    @endif
                </div>
                <form action="{{ route('muzaki.logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-light btn-lg">Keluar</button>
                </form>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6">
                <a href="#program" class="btn btn-orange action-btn w-100 d-flex align-items-center justify-content-center">Setor</a>
            </div>
            <div class="col-6">
                <a href="#riwayat" class="btn btn-primary action-btn w-100 d-flex align-items-center justify-content-center">Lihat Riwayat</a>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <a href="{{ route('muzaki.mobile', ['jenis' => 'zakat']) }}" class="btn btn-warning action-btn w-100 d-flex align-items-center justify-content-center">Zakat</a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('muzaki.mobile', ['jenis' => 'infaq']) }}" class="btn btn-info action-btn w-100 d-flex align-items-center justify-content-center">Infaq</a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('muzaki.mobile', ['jenis' => 'program']) }}" class="btn btn-danger action-btn w-100 d-flex align-items-center justify-content-center">Program Berjalan</a>
            </div>
        </div>

        <div class="row g-3 mb-4">
            @foreach(['zakat' => 'Zakat', 'infaq' => 'Infaq', 'program' => 'Program'] as $key => $label)
                <div class="col-md-4">
                    <div class="card summary-card">
                        <div class="card-body">
                            <div class="label-text mb-1">{{ $label }}</div>
                            <h2 class="h4 mb-0">Rp {{ number_format($ringkasan[$key] ?? 0, 0, ',', '.') }}</h2>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <h2 class="h4 mb-3" id="program">Program Aktif</h2>
        <div class="row g-3 mb-4">
            @foreach($programs as $program)
                @php
                    $target = (float) ($program->target ?? 0);
                    $terkumpul = (float) ($program->terkumpul ?? 0);
                    $progress = $target > 0 ? min(100, ($terkumpul / $target) * 100) : 0;
                @endphp
                <div class="col-md-6">
                    <a href="{{ route('muzaki.mobile', ['jenis' => 'program', 'program_id' => $program->id]) }}" class="card program-card h-100 text-decoration-none text-dark">
                        @if($program->banner_path)
                            <img src="{{ asset('storage/' . $program->banner_path) }}" class="program-banner w-100" alt="{{ $program->nama_program }}">
                        @else
                            <div class="program-banner d-flex align-items-center justify-content-center fw-bold">Lazismu</div>
                        @endif
                        <div class="card-body">
                            <h3 class="h5">{{ $program->nama_program }}</h3>
                            <div class="d-flex justify-content-between small mb-1">
                                <span>Terkumpul</span>
                                <strong>Rp {{ number_format($terkumpul, 0, ',', '.') }}</strong>
                            </div>
                            <div class="progress mb-2" style="height: 10px;">
                                <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                            </div>
                            <div class="small text-muted">Target Rp {{ number_format($target, 0, ',', '.') }}</div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        @if($programDetail)
            <div class="alert alert-success">
                <strong>{{ $programDetail->nama_program }}</strong><br>
                Terkumpul Rp {{ number_format($programDetail->terkumpul, 0, ',', '.') }} dari target Rp {{ number_format($programDetail->target, 0, ',', '.') }}.
            </div>
        @endif

        <div class="card history-card" id="riwayat">
            <div class="card-header bg-white">
                <h2 class="h5 mb-0">Riwayat Transaksi {{ $jenis ? ucfirst($jenis) : 'Personal' }}</h2>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Program</th>
                            <th class="text-end">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($setorans as $setoran)
                            <tr>
                                <td>{{ optional($setoran->created_at)->format('d/m/Y') }}</td>
                                <td>{{ ucfirst($setoran->kodeSetoran->jenis_setoran ?? '-') }}</td>
                                <td>{{ $setoran->program->nama_program ?? '-' }}</td>
                                <td class="text-end">Rp {{ number_format($setoran->nominal, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Belum ada riwayat transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-body">
                {{ $setorans->withQueryString()->links() }}
            </div>
        </div>
    </div>
</body>
</html>
