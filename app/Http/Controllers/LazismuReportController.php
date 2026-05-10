<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Models\Program;
use App\Models\Setoran;
use App\Models\TargetSetoranProgram;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LazismuReportController extends Controller
{
    public function cashflow(Request $request)
    {
        [$startDate, $endDate] = $this->dateRange($request);

        $setoranRows = Setoran::with(['muzaki', 'kodeSetoran', 'program'])
            ->whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->get()
            ->map(function (Setoran $setoran) {
                $jenis = strtolower($setoran->kodeSetoran->jenis_setoran ?? '-');

                return [
                    'tanggal' => $setoran->created_at,
                    'nama' => $setoran->muzaki?->nama ?? '-',
                    'type' => $jenis,
                    'program' => $setoran->program?->nama_program,
                    'kode' => '-',
                    'nominal' => (float) $setoran->nominal,
                    'pemasukan' => (float) ($setoran->nominal_digunakan ?? $setoran->nominal_digunakan_calculated),
                    'pdm' => (float) ($setoran->nominal_pdm ?? $setoran->nominal_pdm_calculated),
                    'pengeluaran' => 0,
                    'user' => '-',
                ];
            });

        $pengeluaranRows = Nota::with(['kodeTransaksi.header', 'program'])
            ->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()])
            ->get()
            ->map(function (Nota $nota) {
                $kode = trim(($nota->kodeTransaksi?->header?->keterangan ? $nota->kodeTransaksi->header->keterangan . ' / ' : '') .
                    ($nota->kodeTransaksi?->kodetransaksi ? $nota->kodeTransaksi->kodetransaksi . ' - ' : '') .
                    ($nota->kodeTransaksi?->transaksi ?? ''));

                return [
                    'tanggal' => $nota->tanggal,
                    'nama' => $nota->namatransaksi,
                    'type' => $nota->kelompok,
                    'program' => $nota->program?->nama_program,
                    'kode' => $kode ?: '-',
                    'nominal' => (float) $nota->total,
                    'pemasukan' => 0,
                    'pdm' => 0,
                    'pengeluaran' => (float) $nota->total,
                    'user' => $nota->namauser ?? '-',
                ];
            });

        $rows = $setoranRows
            ->concat($pengeluaranRows)
            ->sortBy(fn ($row) => optional($row['tanggal'])->format('Y-m-d H:i:s'))
            ->values();

        $summary = $this->summary($rows);

        return view('lazismu.laporan.cashflow', compact('rows', 'summary', 'startDate', 'endDate'));
    }

    public function program(Request $request)
    {
        [$month, $startDate, $endDate] = $this->monthRange($request);
        $programs = Program::orderBy('nama_program')->get();
        $selectedProgram = $request->filled('program_id')
            ? Program::find($request->program_id)
            : $programs->first();

        $query = Setoran::with(['muzaki', 'program'])
            ->whereHas('kodeSetoran', fn ($q) => $q->where('jenis_setoran', 'program'))
            ->whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()]);

        if ($selectedProgram) {
            $query->where('idprogram', $selectedProgram->id);
        }

        $setorans = $query->latest('created_at')->get();
        $rantingChart = $selectedProgram
            ? $this->programGroupChart($selectedProgram->id, 'ranting', $startDate, $endDate)
            : [];
        $aumChart = $selectedProgram
            ? $this->programGroupChart($selectedProgram->id, 'aum', $startDate, $endDate)
            : [];
        $summary = $this->setoranSummary($setorans);
        $programTarget = (float) ($selectedProgram?->target ?? 0);
        $programProgress = $programTarget > 0
            ? min(100, round(($summary['nominal'] / $programTarget) * 100, 1))
            : 0;

        return view('lazismu.laporan.program', compact(
            'programs',
            'selectedProgram',
            'setorans',
            'rantingChart',
            'aumChart',
            'summary',
            'month',
            'programTarget',
            'programProgress'
        ));
    }

    public function infaq(Request $request)
    {
        [$startDate, $endDate] = $this->dateRange($request);
        $setorans = $this->typedSetorans('infaq', $startDate, $endDate);
        $pengeluarans = $this->typedPengeluarans('infaq', $startDate, $endDate);
        $summary = $this->typedSummary($setorans, $pengeluarans);

        return view('lazismu.laporan.infaq', compact('setorans', 'pengeluarans', 'summary', 'startDate', 'endDate'));
    }

    public function zakat(Request $request)
    {
        [$startDate, $endDate] = $this->dateRange($request);
        $setorans = $this->typedSetorans('zakat', $startDate, $endDate);
        $pengeluarans = $this->typedPengeluarans('zakat', $startDate, $endDate);
        $summary = $this->typedSummary($setorans, $pengeluarans);

        return view('lazismu.laporan.zakat', compact('setorans', 'pengeluarans', 'summary', 'startDate', 'endDate'));
    }

    private function dateRange(Request $request): array
    {
        $start = $request->filled('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $end = $request->filled('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();

        return [$start, $end];
    }

    private function monthRange(Request $request): array
    {
        $month = $request->input('bulan', now()->format('Y-m'));
        $start = Carbon::parse($month . '-01')->startOfMonth();

        return [$month, $start, $start->copy()->endOfMonth()];
    }

    private function typedSetorans(string $type, Carbon $startDate, Carbon $endDate)
    {
        return Setoran::with(['muzaki', 'kodeSetoran'])
            ->whereHas('kodeSetoran', fn ($q) => $q->where('jenis_setoran', $type))
            ->whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->latest('created_at')
            ->get();
    }

    private function typedPengeluarans(string $type, Carbon $startDate, Carbon $endDate)
    {
        return Nota::with(['kodeTransaksi.header'])
            ->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()])
            ->when($type === 'zakat', fn ($q) => $q->where('is_zakat', '1'))
            ->when($type === 'infaq', fn ($q) => $q->where('id_infaq', '1'))
            ->latest('tanggal')
            ->get();
    }

    private function programGroupChart(int $programId, string $group, Carbon $startDate, Carbon $endDate): array
    {
        $isAum = $group === 'aum';
        $labelColumn = $isAum ? 'muzaki.nama' : 'muzaki.ranting';

        $targets = TargetSetoranProgram::query()
            ->selectRaw($labelColumn . ' as label, SUM(target_setoran_program.target) as target')
            ->join('muzaki', 'muzaki.id', '=', 'target_setoran_program.idmuzaki')
            ->where('target_setoran_program.idprogram', $programId)
            ->when($isAum, fn ($query) => $query->where('muzaki.jenis_muzaki', 'aum'))
            ->when(!$isAum, fn ($query) => $query->where('muzaki.jenis_muzaki', '<>', 'aum'))
            ->whereNotNull($isAum ? 'muzaki.nama' : 'muzaki.ranting')
            ->where($isAum ? 'muzaki.nama' : 'muzaki.ranting', '!=', '')
            ->groupBy($isAum ? 'muzaki.nama' : 'muzaki.ranting')
            ->get()
            ->keyBy('label');

        $setorans = Setoran::query()
            ->selectRaw($labelColumn . ' as label, SUM(setoran.nominal) as total')
            ->join('kode_setoran', 'kode_setoran.id', '=', 'setoran.idkode_setoran')
            ->join('muzaki', 'muzaki.id', '=', 'setoran.idmuzaki')
            ->where('setoran.idprogram', $programId)
            ->where('kode_setoran.jenis_setoran', 'program')
            ->whereBetween('setoran.created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->when($isAum, fn ($query) => $query->where('muzaki.jenis_muzaki', 'aum'))
            ->when(!$isAum, fn ($query) => $query->where('muzaki.jenis_muzaki', '<>', 'aum'))
            ->whereNotNull($isAum ? 'muzaki.nama' : 'muzaki.ranting')
            ->where($isAum ? 'muzaki.nama' : 'muzaki.ranting', '!=', '')
            ->groupBy($isAum ? 'muzaki.nama' : 'muzaki.ranting')
            ->get()
            ->keyBy('label');

        return $targets->keys()
            ->merge($setorans->keys())
            ->unique()
            ->sort()
            ->values()
            ->map(function ($label) use ($targets, $setorans) {
                $target = (float) optional($targets->get($label))->target;
                $total = (float) optional($setorans->get($label))->total;

                return [
                    'label' => $label ?: '-',
                    'target' => $target,
                    'total' => $total,
                    'percent' => $target > 0 ? min(100, round(($total / $target) * 100, 1)) : 0,
                ];
            })
            ->all();
    }

    private function setoranSummary($setorans): array
    {
        return [
            'nominal' => (float) $setorans->sum('nominal'),
            'pemasukan' => (float) $setorans->sum(fn ($setoran) => $setoran->nominal_digunakan ?? $setoran->nominal_digunakan_calculated),
            'pdm' => (float) $setorans->sum(fn ($setoran) => $setoran->nominal_pdm ?? $setoran->nominal_pdm_calculated),
            'count' => $setorans->count(),
        ];
    }

    private function typedSummary($setorans, $pengeluarans): array
    {
        $setoranSummary = $this->setoranSummary($setorans);
        $pengeluaran = (float) $pengeluarans->sum('total');

        return [
            ...$setoranSummary,
            'pengeluaran' => $pengeluaran,
            'saldo' => $setoranSummary['pemasukan'] - $pengeluaran,
        ];
    }

    private function summary($rows): array
    {
        return [
            'nominal' => (float) $rows->sum('nominal'),
            'pemasukan' => (float) $rows->sum('pemasukan'),
            'pdm' => (float) $rows->sum('pdm'),
            'pengeluaran' => (float) $rows->sum('pengeluaran'),
            'saldo' => (float) $rows->sum('pemasukan') - (float) $rows->sum('pengeluaran'),
        ];
    }
}
