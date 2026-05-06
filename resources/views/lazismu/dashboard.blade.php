<x-app-layout>
    <x-slot name="pagetitle">Dashboard Lazismu</x-slot>

    <div class="app-content">
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h3 class="mb-1">Dashboard Lazismu</h3>
                    <p class="text-muted mb-0">Kelola data muzaki, program, kode setoran, dan transaksi dana zakat, infaq, serta program.</p>
                </div>
                <a href="{{ route('muzaki.login') }}" class="btn btn-outline-warning">
                    <i class="bi bi-person-badge"></i> Portal Muzaki
                </a>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <small class="text-muted">Total Muzaki</small>
                            <h2 class="mb-0">{{ number_format($totalMuzaki) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <small class="text-muted">Program Aktif</small>
                            <h2 class="mb-0">{{ number_format($programActive) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <small class="text-muted">Jumlah Setoran</small>
                            <h2 class="mb-0">{{ number_format($totalSetoran) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100 bg-warning-subtle">
                        <div class="card-body">
                            <small class="text-muted">Total Dana</small>
                            <h2 class="mb-0">Rp {{ number_format($totalDana, 0, ',', '.') }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100 bg-success-subtle">
                        <div class="card-body">
                            <small class="text-muted">Nominal Bisa Digunakan</small>
                            <h2 class="mb-0">Rp {{ number_format($totalDigunakan, 0, ',', '.') }}</h2>
                            <p class="mb-0 text-muted">Zakat 70%, infaq 80%, program 100% masuk program.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100 bg-info-subtle">
                        <div class="card-body">
                            <small class="text-muted">Bagian PDM</small>
                            <h2 class="mb-0">Rp {{ number_format($totalPdm, 0, ',', '.') }}</h2>
                            <p class="mb-0 text-muted">Zakat 30% dan infaq 20%.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <a href="{{ route('lazismu.muzaki.index') }}" class="card border-0 shadow-sm text-decoration-none h-100">
                        <div class="card-body">
                            <h5 class="mb-1">Muzaki</h5>
                            <p class="text-muted mb-0">CRUD data donatur/muzaki dan identitas dasar.</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('lazismu.program.index') }}" class="card border-0 shadow-sm text-decoration-none h-100">
                        <div class="card-body">
                            <h5 class="mb-1">Program</h5>
                            <p class="text-muted mb-0">Kelola program infaq aktif beserta target lokasi dan periode.</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('lazismu.setoran.index') }}" class="card border-0 shadow-sm text-decoration-none h-100">
                        <div class="card-body">
                            <h5 class="mb-1">Setoran</h5>
                            <p class="text-muted mb-0">Input transaksi zakat, infaq, atau setoran program.</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent">
                            <h5 class="mb-0">Ringkasan Dana</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <span>Zakat</span>
                                <strong>Rp {{ number_format($ringkasanJenis['zakat'] ?? 0, 0, ',', '.') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Infaq</span>
                                <strong>Rp {{ number_format($ringkasanJenis['infaq'] ?? 0, 0, ',', '.') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Program</span>
                                <strong>Rp {{ number_format($ringkasanJenis['program'] ?? 0, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Setoran Terbaru</h5>
                            <a href="{{ route('lazismu.setoran.index') }}" class="btn btn-sm btn-outline-warning">Lihat Semua</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Muzaki</th>
                                        <th>Jenis</th>
                                        <th>Program</th>
                                        <th class="text-end">Nominal</th>
                                        <th class="text-end">Bisa Digunakan</th>
                                        <th class="text-end">PDM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($setoranTerbaru as $item)
                                        <tr>
                                            <td>{{ optional($item->created_at)->format('d/m/Y') }}</td>
                                            <td>{{ $item->muzaki->nama ?? '-' }}</td>
                                            <td>{{ ucfirst($item->kodeSetoran->jenis_setoran ?? '-') }}</td>
                                            <td>{{ $item->program->nama_program ?? '-' }}</td>
                                            <td class="text-end">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                            <td class="text-end">Rp {{ number_format($item->nominal_digunakan_calculated, 0, ',', '.') }}</td>
                                            <td class="text-end">Rp {{ number_format($item->nominal_pdm_calculated, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">Belum ada setoran.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
