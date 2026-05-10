@include('lazismu.laporan.partials.typed', [
    'title' => 'Laporan Zakat',
    'subtitle' => 'Rincian pemasukan, PDM, dan pengeluaran zakat.',
    'action' => route('lazismu.laporan.zakat'),
    'setorans' => $setorans,
    'pengeluarans' => $pengeluarans,
    'summary' => $summary,
    'startDate' => $startDate,
    'endDate' => $endDate,
])
