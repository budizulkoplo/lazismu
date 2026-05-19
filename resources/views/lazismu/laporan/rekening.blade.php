<x-app-layout>
    <x-slot name="pagetitle">Laporan Rekening</x-slot>

    <div class="app-content">
        <div class="container-fluid py-4">
            <h3 class="mb-1">Laporan Rekening</h3>
            <p class="text-muted">Cashflow berdasarkan mutasi pada rekening.</p>

            <form method="GET" action="{{ route('lazismu.laporan.rekening') }}" class="row g-2 align-items-end mb-3">
                <div class="col-md-3">
                    <label class="form-label">Rekening</label>
                    <select name="rekening_id" class="form-select js-select2">
                        <option value="">Semua rekening</option>
                        @foreach($rekenings as $rekening)
                            <option value="{{ $rekening->id }}" @selected((string) request('rekening_id') === (string) $rekening->id)>
                                {{ $rekening->namarek }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Awal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-warning">Filter</button>
                    <a href="{{ route('lazismu.laporan.rekening') }}" class="btn btn-light">Reset</a>
                </div>
            </form>

            @include('lazismu.laporan.partials.summary-cards', ['items' => [
                ['label' => 'Pemasukan', 'value' => $summary['in']],
                ['label' => 'Pengeluaran', 'value' => $summary['out']],
                ['label' => 'Net', 'value' => $summary['net']],
                ['label' => 'Saldo Akhir', 'value' => $summary['saldo_akhir']],
            ]])

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped align-middle js-lazismu-table w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Rekening</th>
                                    <th>Keterangan</th>
                                    <th class="text-end">Masuk</th>
                                    <th class="text-end">Keluar</th>
                                    <th class="text-end">Saldo Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $row)
                                    <tr>
                                        <td>{{ optional($row->tanggal)->format('d/m/Y') }}</td>
                                        <td>{{ $row->rekening->namarek ?? '-' }}</td>
                                        <td>{{ $row->keterangan ?: '-' }}</td>
                                        <td class="text-end">@include('lazismu.laporan.partials.money', ['value' => $row->jenis === 'in' ? $row->nominal : 0])</td>
                                        <td class="text-end">@include('lazismu.laporan.partials.money', ['value' => $row->jenis === 'out' ? $row->nominal : 0])</td>
                                        <td class="text-end">@include('lazismu.laporan.partials.money', ['value' => $row->saldo_akhir])</td>
                                    </tr>
                                @endforeach
                            </tbody>
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
