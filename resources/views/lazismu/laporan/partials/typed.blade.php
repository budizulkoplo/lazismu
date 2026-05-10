<x-app-layout>
    <x-slot name="pagetitle">{{ $title }}</x-slot>

    <div class="app-content">
        <div class="container-fluid py-4">
            <h3 class="mb-1">{{ $title }}</h3>
            <p class="text-muted">{{ $subtitle }}</p>

            @include('lazismu.laporan.partials.date-filter', [
                'action' => $action,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ])

            @include('lazismu.laporan.partials.summary-cards', ['items' => [
                ['label' => 'Total Setoran', 'value' => $summary['nominal']],
                ['label' => 'Pemasukan', 'value' => $summary['pemasukan']],
                ['label' => 'PDM', 'value' => $summary['pdm']],
                ['label' => 'Pengeluaran', 'value' => $summary['pengeluaran']],
            ]])

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0">Rincian Setoran</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped align-middle js-lazismu-table w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Muzaki</th>
                                    <th class="text-end">Nominal</th>
                                    <th class="text-end">Pemasukan</th>
                                    <th class="text-end">PDM</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($setorans as $setoran)
                                    <tr>
                                        <td>{{ optional($setoran->created_at)->format('d/m/Y H:i') }}</td>
                                        <td>{{ $setoran->muzaki?->nama ?? '-' }}</td>
                                        <td class="text-end">@include('lazismu.laporan.partials.money', ['value' => $setoran->nominal])</td>
                                        <td class="text-end">@include('lazismu.laporan.partials.money', ['value' => $setoran->nominal_digunakan ?? $setoran->nominal_digunakan_calculated])</td>
                                        <td class="text-end">@include('lazismu.laporan.partials.money', ['value' => $setoran->nominal_pdm ?? $setoran->nominal_pdm_calculated])</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0">Rincian Pengeluaran</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped align-middle js-lazismu-table w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Transaksi</th>
                                    <th>Kode Transaksi</th>
                                    <th class="text-end">Nominal</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengeluarans as $nota)
                                    <tr>
                                        <td>{{ optional($nota->tanggal)->format('d/m/Y') }}</td>
                                        <td>{{ $nota->namatransaksi }}</td>
                                        <td>
                                            {{ $nota->kodeTransaksi?->header?->keterangan ? $nota->kodeTransaksi->header->keterangan . ' / ' : '' }}
                                            {{ $nota->kodeTransaksi?->kodetransaksi }} {{ $nota->kodeTransaksi?->transaksi ? '- ' . $nota->kodeTransaksi->transaksi : '' }}
                                        </td>
                                        <td class="text-end">@include('lazismu.laporan.partials.money', ['value' => $nota->total])</td>
                                        <td>{{ $nota->namauser ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td colspan="3" class="text-end">Saldo</td>
                                    <td colspan="2">@include('lazismu.laporan.partials.money', ['value' => $summary['saldo']])</td>
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
