<x-app-layout>
    <x-slot name="pagetitle">Laporan Program</x-slot>

    <x-slot name="csscustom">
        <style>
            .chart-row + .chart-row { border-top: 1px solid #edf0f3; padding-top: .85rem; margin-top: .85rem; }
            .chart-track { height: 12px; border-radius: 999px; background: #ffe4c7; overflow: hidden; }
            .chart-fill { height: 100%; border-radius: inherit; background: #fc8c04; }
            .chart-track-entity { background: #dbeafe; }
            .chart-fill-entity { background: #38bdf8; }
            .chart-label { min-width: 0; }
            .chart-label strong { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        </style>
    </x-slot>

    <div class="app-content">
        <div class="container-fluid py-4">
            <h3 class="mb-1">Laporan Program</h3>
            <p class="text-muted">Grafik dan rincian setoran per program.</p>

            <form method="GET" action="{{ route('lazismu.laporan.program') }}" class="row g-2 align-items-end mb-3">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Awal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Program</label>
                    <select name="program_id" class="form-select js-select2">
                        @foreach($programs as $program)
                            <option value="{{ $program->id }}" @selected(optional($selectedProgram)->id === $program->id)>{{ $program->nama_program }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-warning"><i class="bi bi-funnel"></i> Filter</button>
                </div>
            </form>

            @include('lazismu.laporan.partials.summary-cards', ['items' => [
                ['label' => 'Target Program', 'value' => $programTarget],
                ['label' => 'Total Setoran', 'value' => $summary['nominal']],
                ['label' => 'Pemasukan Program', 'value' => $summary['pemasukan']],
                ['label' => 'Pengeluaran Program', 'value' => $summary['pengeluaran']],
                ['label' => 'Saldo Program', 'value' => $summary['saldo']],
                ['label' => 'Jumlah Transaksi', 'value' => $summary['count'], 'money' => false],
            ]])

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between gap-2 mb-2">
                        <div>
                            <h5 class="mb-1">Progress Target Program</h5>
                            <div class="text-muted">{{ $selectedProgram?->nama_program ?? 'Belum ada program' }}</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">{{ number_format($programProgress, 1, ',', '.') }}%</div>
                            <div class="small text-muted">
                                @include('lazismu.laporan.partials.money', ['value' => $summary['nominal']])
                                /
                                @include('lazismu.laporan.partials.money', ['value' => $programTarget])
                            </div>
                        </div>
                    </div>
                    <div class="chart-track">
                        <div class="chart-fill" style="width: {{ $programProgress }}%;"></div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="mb-3">Grafik Setoran per Ranting</h5>
                            @forelse($rantingChart as $row)
                                <div class="chart-row">
                                    <div class="d-flex justify-content-between gap-2 mb-1">
                                        <div class="chart-label">
                                            <strong>{{ $row['label'] }}</strong>
                                            <div class="small text-muted">
                                                Setor @include('lazismu.laporan.partials.money', ['value' => $row['total']])
                                                / Target @include('lazismu.laporan.partials.money', ['value' => $row['target']])
                                            </div>
                                        </div>
                                        <div class="fw-bold small">{{ number_format($row['percent'], 1, ',', '.') }}%</div>
                                    </div>
                                    <div class="chart-track">
                                        <div class="chart-fill" style="width: {{ $row['percent'] }}%;"></div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-info mb-0">Belum ada data target atau setoran per ranting.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="mb-3">Grafik Setoran per Entitas</h5>
                            @forelse($entityChart as $row)
                                <div class="chart-row">
                                    <div class="d-flex justify-content-between gap-2 mb-1">
                                        <div class="chart-label">
                                            <strong>{{ $row['label'] }}</strong>
                                            <div class="small text-muted">
                                                Setor @include('lazismu.laporan.partials.money', ['value' => $row['total']])
                                                / Target @include('lazismu.laporan.partials.money', ['value' => $row['target']])
                                            </div>
                                        </div>
                                        <div class="fw-bold small">{{ number_format($row['percent'], 1, ',', '.') }}%</div>
                                    </div>
                                    <div class="chart-track chart-track-entity">
                                        <div class="chart-fill chart-fill-entity" style="width: {{ $row['percent'] }}%;"></div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-info mb-0">Belum ada data target atau setoran per entitas.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0">Rincian Setoran Program</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped align-middle js-lazismu-table w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Muzaki</th>
                                    <th>Ranting</th>
                                    <th>Program</th>
                                    <th class="text-end">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($setorans as $setoran)
                                    <tr>
                                        <td>{{ optional($setoran->created_at)->format('d/m/Y H:i') }}</td>
                                        <td>{{ $setoran->muzaki?->nama ?? '-' }}</td>
                                        <td>{{ $setoran->muzaki?->ranting ?: '-' }}</td>
                                        <td>{{ $setoran->program?->nama_program ?? '-' }}</td>
                                        <td class="text-end">@include('lazismu.laporan.partials.money', ['value' => $setoran->nominal])</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0">Rincian Pengeluaran Program</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped align-middle js-lazismu-table w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>No Nota</th>
                                    <th>Nama Transaksi</th>
                                    <th>Kode Transaksi</th>
                                    <th>User</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengeluarans as $nota)
                                    @php
                                        $kodeTransaksi = trim(
                                            ($nota->kodeTransaksi?->header?->keterangan ? $nota->kodeTransaksi->header->keterangan . ' / ' : '') .
                                            ($nota->kodeTransaksi?->kodetransaksi ? $nota->kodeTransaksi->kodetransaksi . ' - ' : '') .
                                            ($nota->kodeTransaksi?->transaksi ?? '')
                                        );
                                    @endphp
                                    <tr>
                                        <td>{{ optional($nota->tanggal)->format('d/m/Y') }}</td>
                                        <td>{{ $nota->nota_no ?: '-' }}</td>
                                        <td>{{ $nota->namatransaksi }}</td>
                                        <td>{{ $kodeTransaksi ?: '-' }}</td>
                                        <td>{{ $nota->namauser ?: '-' }}</td>
                                        <td class="text-end">@include('lazismu.laporan.partials.money', ['value' => $nota->total])</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="5" class="text-end">Total Pengeluaran</th>
                                    <th class="text-end">@include('lazismu.laporan.partials.money', ['value' => $summary['pengeluaran']])</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="jscustom">
        @include('lazismu.partials.datatable-select2')
    </x-slot>
</x-app-layout>
