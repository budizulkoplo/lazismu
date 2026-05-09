<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Program {{ $program->nama_program }}</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        :root { --brand: #fc8c04; --brand-dark: #d97706; --ink: #172033; --muted: #64748b; }
        body { background: linear-gradient(180deg, #fff7ed 0%, #ffffff 50%, #f7f9fb 100%); color: var(--ink); }
        .page-shell { max-width: 540px; }
        .top-card { background: linear-gradient(135deg, var(--brand) 0%, #ffb14a 100%); color: #fff; border-radius: 18px; box-shadow: 0 14px 35px rgba(252, 140, 4, .22); overflow: hidden; }
        .program-banner { aspect-ratio: 16 / 7; object-fit: cover; background: #ffedd5; }
        .stat-card, .trx-card, .chart-card { border: 0; border-radius: 16px; box-shadow: 0 12px 30px rgba(15, 23, 42, .08); }
        .muted { color: var(--muted); }
        .chart-row + .chart-row { border-top: 1px solid #edf0f3; padding-top: .75rem; margin-top: .75rem; }
        .chart-track { height: 10px; border-radius: 999px; background: #ffe4c7; overflow: hidden; }
        .chart-fill { height: 100%; border-radius: inherit; background: var(--brand); }
        .chart-label { min-width: 0; }
        .chart-label strong { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    </style>
</head>
<body>
    <div class="container page-shell py-3 py-sm-4">
        <div class="top-card mb-3">
            @if($program->banner_path)
                <img src="{{ asset('storage/' . $program->banner_path) }}" class="program-banner w-100" alt="{{ $program->nama_program }}">
            @endif
            <div class="p-3">
                <div class="d-flex align-items-start justify-content-between gap-2">
                    <div>
                        <div class="small opacity-75">Detail Program</div>
                        <h1 class="h5 fw-bold mb-1">{{ $program->nama_program }}</h1>
                        <div class="small opacity-75">{{ $program->lokasi ?: 'Lokasi belum diisi' }}</div>
                    </div>
                    <a href="{{ route('muzaki.riwayat') }}" class="btn btn-light fw-bold">Kembali</a>
                </div>
            </div>
        </div>

        @php
            $target = (float) ($program->target ?? 0);
            $terkumpul = (float) ($program->terkumpul ?? 0);
            $progress = $target > 0 ? min(100, ($terkumpul / $target) * 100) : 0;
        @endphp

        <div class="row g-2 mb-3">
            <div class="col-6">
                <div class="card stat-card h-100">
                    <div class="card-body p-3">
                        <div class="small muted">Target</div>
                        <div class="fw-bold">Rp {{ number_format($target, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card stat-card h-100">
                    <div class="card-body p-3">
                        <div class="small muted">Terkumpul</div>
                        <div class="fw-bold">Rp {{ number_format($terkumpul, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card stat-card">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small muted">Progress</span>
                            <strong>{{ number_format($progress, 1, ',', '.') }}%</strong>
                        </div>
                        <div class="progress" style="height: 10px; background: #ffe4c7;">
                            <div class="progress-bar" style="width: {{ $progress }}%; background: #fc8c04;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card stat-card">
                    <div class="card-body p-3">
                        <div class="small muted">Setoran Anda di Program Ini</div>
                        <div class="h4 fw-bold mb-0">Rp {{ number_format($totalMuzaki, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card chart-card mb-3">
            <div class="card-body p-3">
                <h2 class="h6 fw-bold mb-3">Grafik Setoran per Ranting</h2>
                @forelse($rantingChart as $item)
                    <div class="chart-row">
                        <div class="d-flex justify-content-between gap-2 mb-1">
                            <div class="chart-label">
                                <strong>{{ $item['label'] }}</strong>
                                <div class="small muted">
                                    Setor Rp {{ number_format($item['total'], 0, ',', '.') }}
                                    / Target Rp {{ number_format($item['target'], 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="fw-bold small">{{ number_format($item['percent'], 1, ',', '.') }}%</div>
                        </div>
                        <div class="chart-track">
                            <div class="chart-fill" style="width: {{ $item['percent'] }}%;"></div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info mb-0">Belum ada data target atau setoran per ranting.</div>
                @endforelse
            </div>
        </div>

        <div class="card chart-card mb-3">
            <div class="card-body p-3">
                <h2 class="h6 fw-bold mb-3">Grafik Setoran per AUM</h2>
                @forelse($aumChart as $item)
                    <div class="chart-row">
                        <div class="d-flex justify-content-between gap-2 mb-1">
                            <div class="chart-label">
                                <strong>{{ $item['label'] }}</strong>
                                <div class="small muted">
                                    Setor Rp {{ number_format($item['total'], 0, ',', '.') }}
                                    / Target Rp {{ number_format($item['target'], 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="fw-bold small">{{ number_format($item['percent'], 1, ',', '.') }}%</div>
                        </div>
                        <div class="chart-track">
                            <div class="chart-fill" style="width: {{ $item['percent'] }}%;"></div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info mb-0">Belum ada data target atau setoran per AUM.</div>
                @endforelse
            </div>
        </div>

        <h2 class="h5 fw-bold mb-2">Rincian Transaksi Anda</h2>
        <div class="d-grid gap-3">
            @forelse($setorans as $setoran)
                <div class="card trx-card">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between gap-3">
                            <div>
                                <div class="fw-bold">{{ optional($setoran->created_at)->format('d/m/Y') }}</div>
                                <div class="small muted">Setoran program</div>
                            </div>
                            <div class="text-end fw-bold">Rp {{ number_format($setoran->nominal, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">Anda belum pernah setor ke program ini.</div>
            @endforelse
        </div>

        <div class="mt-3">
            {{ $setorans->links() }}
        </div>
    </div>
</body>
</html>
