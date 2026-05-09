<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Riwayat {{ ucfirst($jenis) }}</title>
    @include('muzaki.partials.pwa-head')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        :root { --brand: #fc8c04; --brand-dark: #d97706; --ink: #172033; --muted: #64748b; }
        body { background: linear-gradient(180deg, #fff7ed 0%, #ffffff 50%, #f7f9fb 100%); color: var(--ink); }
        .page-shell { max-width: 540px; }
        .top-card { background: linear-gradient(135deg, var(--brand) 0%, #ffb14a 100%); color: #fff; border-radius: 18px; box-shadow: 0 14px 35px rgba(252, 140, 4, .22); }
        .trx-card { border: 0; border-radius: 16px; box-shadow: 0 12px 30px rgba(15, 23, 42, .08); }
        .muted { color: var(--muted); }
    </style>
</head>
<body>
    <div class="container page-shell py-3 py-sm-4">
        <div class="top-card p-3 mb-4">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div>
                    <div class="small opacity-75">Detail Riwayat</div>
                    <h1 class="h5 fw-bold mb-1">{{ ucfirst($jenis) }}</h1>
                    <div class="small opacity-75">Total Rp {{ number_format($total, 0, ',', '.') }}</div>
                </div>
                <a href="{{ route('muzaki.riwayat') }}" class="btn btn-light fw-bold">Kembali</a>
            </div>
        </div>

        <div class="d-grid gap-3">
            @forelse($setorans as $setoran)
                <div class="card trx-card">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between gap-3">
                            <div>
                                <div class="fw-bold">{{ optional($setoran->created_at)->format('d/m/Y') }}</div>
                                <div class="small muted">{{ $setoran->program->nama_program ?? ucfirst($setoran->kodeSetoran->jenis_setoran ?? '-') }}</div>
                            </div>
                            <div class="text-end fw-bold">Rp {{ number_format($setoran->nominal, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">Belum ada transaksi {{ $jenis }}.</div>
            @endforelse
        </div>

        <div class="mt-3">
            {{ $setorans->links() }}
        </div>
    </div>
    @include('muzaki.partials.pwa-install')
</body>
</html>
