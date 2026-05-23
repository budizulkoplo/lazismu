<x-app-layout>
    <x-slot name="pagetitle">Laporan per Muzaki</x-slot>

    <div class="app-content">
        <div class="container-fluid py-4">
            <h3 class="mb-1">Laporan per Muzaki</h3>
            <p class="text-muted">Rincian semua transaksi zakat, infaq, dan setoran per program untuk satu muzaki.</p>

            <form method="GET" action="{{ route('lazismu.laporan.muzaki') }}" class="row g-2 align-items-end mb-3">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Awal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Muzaki</label>
                    <select name="muzaki_id" class="form-select js-select2" data-placeholder="Pilih muzaki">
                        <option value=""></option>
                        @foreach($muzakis as $muzaki)
                            <option value="{{ $muzaki->id }}" @selected(optional($selectedMuzaki)->id === $muzaki->id)>
                                {{ $muzaki->nama }} - {{ $muzaki->login_code }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-warning"><i class="bi bi-funnel"></i> Filter</button>
                    <a href="{{ route('lazismu.laporan.muzaki') }}" class="btn btn-light">Reset</a>
                </div>
            </form>

            @if($selectedMuzaki)
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div>
                                <div class="text-muted small">Muzaki</div>
                                <h5 class="mb-1">{{ $selectedMuzaki->nama }}</h5>
                                <div class="text-muted">
                                    ID {{ $selectedMuzaki->login_code }}{{ $selectedMuzaki->nik ? ' | NIK ' . $selectedMuzaki->nik : '' }}
                                </div>
                            </div>
                            <div class="text-muted small">
                                Periode {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>

                @include('lazismu.laporan.partials.summary-cards', ['items' => [
                    ['label' => 'Total Zakat', 'value' => $summary['zakat']],
                    ['label' => 'Total Infaq', 'value' => $summary['infaq']],
                    ['label' => 'Total Program', 'value' => $summary['program']],
                    ['label' => 'Jumlah Transaksi', 'value' => $summary['count'], 'money' => false],
                ]])

                <div class="row g-3 mb-3">
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-0 pb-0">
                                <h5 class="mb-0">Ringkasan Program</h5>
                            </div>
                            <div class="card-body">
                                @forelse($programRows as $row)
                                    <div class="d-flex justify-content-between gap-3 border-bottom py-2">
                                        <div class="min-w-0">
                                            <div class="fw-semibold text-truncate">{{ $row['program'] }}</div>
                                            <div class="small text-muted">{{ $row['count'] }} transaksi</div>
                                        </div>
                                        <div class="fw-bold text-end">@include('lazismu.laporan.partials.money', ['value' => $row['total']])</div>
                                    </div>
                                @empty
                                    <div class="alert alert-info mb-0">Belum ada setoran program pada periode ini.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-0 pb-0">
                                <h5 class="mb-0">Detail Transaksi</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped align-middle js-lazismu-table w-100">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Jenis</th>
                                                <th>Program</th>
                                                <th class="text-end">Nominal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($setorans as $setoran)
                                                <tr>
                                                    <td>{{ optional($setoran->created_at)->format('d/m/Y H:i') }}</td>
                                                    <td>{{ ucfirst($setoran->kodeSetoran->jenis_setoran ?? '-') }}</td>
                                                    <td>{{ $setoran->program?->nama_program ?? '-' }}</td>
                                                    <td class="text-end">@include('lazismu.laporan.partials.money', ['value' => $setoran->nominal])</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info">Pilih muzaki terlebih dahulu untuk melihat detail laporan.</div>
            @endif
        </div>
    </div>

    <x-slot name="jscustom">
        @include('lazismu.partials.datatable-select2')
    </x-slot>
</x-app-layout>
