<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mobile Muzaki</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        body {
            background: #fffaf5;
        }
        .page-shell {
            max-width: 920px;
        }
        .hero {
            background: linear-gradient(135deg, #fc8c04 0%, #ffb14a 100%);
            color: #fff;
            border-radius: 24px;
        }
        .info-card,
        .summary-card,
        .history-card {
            border: 0;
            border-radius: 20px;
            box-shadow: 0 14px 35px rgba(15, 23, 42, 0.08);
        }
        .label-text {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="container page-shell py-4 py-md-5">
        <div class="hero p-4 p-md-5 mb-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <p class="mb-1 opacity-75">Mobile Muzaki</p>
                    <h2 class="mb-2">{{ $muzaki->nama }}</h2>
                    <p class="mb-0">NIK {{ $muzaki->nik }}{{ $muzaki->no_hp ? ' | ' . $muzaki->no_hp : '' }}</p>
                </div>
                <form action="{{ route('muzaki.logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-light">Keluar</button>
                </form>
            </div>
        </div>

        <div class="card info-card mb-4">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-sm-6 col-lg-3">
                        <div class="label-text mb-1">Nama</div>
                        <div class="fw-semibold">{{ $muzaki->nama }}</div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="label-text mb-1">NIK</div>
                        <div class="fw-semibold">{{ $muzaki->nik }}</div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="label-text mb-1">No HP</div>
                        <div class="fw-semibold">{{ $muzaki->no_hp ?: '-' }}</div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="label-text mb-1">Email</div>
                        <div class="fw-semibold text-break">{{ $muzaki->email ?: '-' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="label-text mb-1">Alamat</div>
                        <div class="fw-semibold">{{ $muzaki->alamat ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card summary-card">
                    <div class="card-body">
                        <small class="text-muted">Zakat</small>
                        <h4 class="mb-0">Rp {{ number_format($ringkasan['zakat'] ?? 0, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card summary-card">
                    <div class="card-body">
                        <small class="text-muted">Infaq</small>
                        <h4 class="mb-0">Rp {{ number_format($ringkasan['infaq'] ?? 0, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card summary-card">
                    <div class="card-body">
                        <small class="text-muted">Program</small>
                        <h4 class="mb-0">Rp {{ number_format($ringkasan['program'] ?? 0, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card history-card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Riwayat Transaksi Personal</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
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
                {{ $setorans->links() }}
            </div>
        </div>
    </div>
</body>
</html>
