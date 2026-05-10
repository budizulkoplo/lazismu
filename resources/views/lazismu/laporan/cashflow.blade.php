<x-app-layout>
    <x-slot name="pagetitle">Laporan Cashflow</x-slot>

    <div class="app-content">
        <div class="container-fluid py-4">
            <h3 class="mb-1">Laporan Cashflow</h3>
            <p class="text-muted">Uang masuk dari setoran dan uang keluar dari pengeluaran.</p>

            @include('lazismu.laporan.partials.date-filter', [
                'action' => route('lazismu.laporan.cashflow'),
                'startDate' => $startDate,
                'endDate' => $endDate,
            ])

            @include('lazismu.laporan.partials.summary-cards', ['items' => [
                ['label' => 'Total Nominal', 'value' => $summary['nominal']],
                ['label' => 'Pemasukan', 'value' => $summary['pemasukan']],
                ['label' => 'PDM', 'value' => $summary['pdm']],
                ['label' => 'Pengeluaran', 'value' => $summary['pengeluaran']],
            ]])

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped align-middle js-lazismu-table w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama</th>
                                    <th>Type</th>
                                    <th>Program/Kode</th>
                                    <th class="text-end">Nominal</th>
                                    <th class="text-end">Pemasukan</th>
                                    <th class="text-end">PDM</th>
                                    <th class="text-end">Pengeluaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $row)
                                    <tr>
                                        <td>{{ optional($row['tanggal'])->format('d/m/Y') }}</td>
                                        <td>{{ $row['nama'] }}</td>
                                        <td>{{ ucfirst($row['type']) }}</td>
                                        <td>{{ $row['program'] ?: $row['kode'] }}</td>
                                        <td class="text-end">@include('lazismu.laporan.partials.money', ['value' => $row['nominal']])</td>
                                        <td class="text-end">@include('lazismu.laporan.partials.money', ['value' => $row['pemasukan']])</td>
                                        <td class="text-end">@include('lazismu.laporan.partials.money', ['value' => $row['pdm']])</td>
                                        <td class="text-end">@include('lazismu.laporan.partials.money', ['value' => $row['pengeluaran']])</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td colspan="5" class="text-end">Saldo Pemasukan - Pengeluaran</td>
                                    <td colspan="3">@include('lazismu.laporan.partials.money', ['value' => $summary['saldo']])</td>
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
