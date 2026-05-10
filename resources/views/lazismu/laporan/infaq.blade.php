@include('lazismu.laporan.partials.typed', [
    'title' => 'Laporan Infaq',
    'subtitle' => 'Rincian pemasukan dan pengeluaran infaq.',
    'action' => route('lazismu.laporan.infaq'),
    'setorans' => $setorans,
    'pengeluarans' => $pengeluarans,
    'summary' => $summary,
    'startDate' => $startDate,
    'endDate' => $endDate,
])
