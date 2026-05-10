<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Setoran</title>
    @include('muzaki.partials.pwa-head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-icons-1.13.1/bootstrap-icons.min.css') }}">
    <style>
        :root { --brand: #fc8c04; --brand-dark: #d97706; --ink: #172033; --muted: #64748b; }
        body { background: linear-gradient(180deg, #fff7ed 0%, #ffffff 50%, #f7f9fb 100%); color: var(--ink); }
        .page-shell { max-width: 540px; }
        .top-card { background: linear-gradient(135deg, var(--brand) 0%, #ffb14a 100%); color: #fff; border-radius: 18px; box-shadow: 0 14px 35px rgba(252, 140, 4, .22); }
        .history-link { border: 0; border-radius: 16px; box-shadow: 0 12px 30px rgba(15, 23, 42, .08); text-decoration: none; color: var(--ink); overflow: hidden; }
        .stripe { width: 9px; }
        .muted { color: var(--muted); }
    </style>
</head>
<body>
    @include('muzaki.partials.nav')
    <div class="container page-shell py-3 py-sm-4">
        <div class="top-card p-3 mb-4">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div>
                    <div class="small opacity-75">Riwayat Setoran</div>
                    <h1 class="h5 fw-bold mb-1">{{ $muzaki->nama }}</h1>
                    <div class="small opacity-75">Pilih jenis transaksi</div>
                </div>
                <a href="{{ route('muzaki.mobile') }}" class="btn btn-light fw-bold">Beranda</a>
            </div>
        </div>

        <div class="d-grid gap-3 mb-4">
            @foreach([
                'zakat' => ['label' => 'Zakat', 'color' => '#f59e0b'],
                'infaq' => ['label' => 'Infaq', 'color' => '#0ea5e9'],
            ] as $key => $item)
                @php
                    $data = $ringkasan->get($key);
                @endphp
                <a href="{{ route('muzaki.riwayat.detail', $key) }}" class="card history-link">
                    <div class="d-flex">
                        <div class="stripe" style="background: {{ $item['color'] }}"></div>
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center gap-2">
                                <div>
                                    <div class="h5 fw-bold mb-1">{{ $item['label'] }}</div>
                                    <div class="small muted">{{ number_format((int) ($data->jumlah ?? 0)) }} transaksi</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">Rp {{ number_format((float) ($data->total ?? 0), 0, ',', '.') }}</div>
                                    <div class="small muted">Lihat detail</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <h2 class="h5 fw-bold mb-2">Riwayat Program</h2>
        <div class="d-grid gap-3">
            @if($programSetorans->count() === 0)
                <div class="alert alert-info">Belum ada setoran program.</div>
            @endif

            <?php foreach ($programSetorans as $programItem): ?>
                <?php
                    $target = (float) ($programItem->target ?? 0);
                    $terkumpul = (float) ($programItem->terkumpul ?? 0);
                    $progress = $target > 0 ? min(100, ($terkumpul / $target) * 100) : 0;
                ?>
                <a href="{{ route('muzaki.program.detail', $programItem->id) }}" class="card history-link">
                    <div class="d-flex">
                        <div class="stripe" style="background: #ef4444"></div>
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between gap-2 mb-2">
                                <div>
                                    <div class="h6 fw-bold mb-1">{{ $programItem->nama_program }}</div>
                                    <div class="small muted">{{ number_format((int) $programItem->jumlah) }} transaksi</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">Rp {{ number_format((float) $programItem->total, 0, ',', '.') }}</div>
                                    <div class="small muted">Setoran Anda</div>
                                </div>
                            </div>
                            <div class="progress" style="height: 9px; background: #ffe4c7;">
                                <div class="progress-bar" style="width: {{ $progress }}%; background: #fc8c04;"></div>
                            </div>
                            <div class="d-flex justify-content-between small muted mt-1">
                                <span>Terkumpul Rp {{ number_format($terkumpul, 0, ',', '.') }}</span>
                                <span>Target Rp {{ number_format($target, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    @include('muzaki.partials.pwa-install')
</body>
</html>
