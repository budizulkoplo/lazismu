<?php

namespace App\Http\Controllers;

use App\Models\Muzaki;
use App\Models\Program;
use App\Models\Setoran;

class LazismuDashboardController extends Controller
{
    public function index()
    {
        $totalMuzaki = Muzaki::count();
        $programActive = Program::active()->count();
        $totalSetoran = Setoran::count();
        $totalDana = Setoran::sum('nominal');
        $totalDigunakan = Setoran::query()
            ->join('kode_setoran', 'kode_setoran.id', '=', 'setoran.idkode_setoran')
            ->selectRaw("
                SUM(CASE
                    WHEN LOWER(kode_setoran.jenis_setoran) = 'zakat' THEN setoran.nominal * 0.70
                    WHEN LOWER(kode_setoran.jenis_setoran) = 'infaq' THEN setoran.nominal * 0.80
                    ELSE setoran.nominal
                END) as total
            ")
            ->value('total') ?? 0;
        $totalPdm = Setoran::query()
            ->join('kode_setoran', 'kode_setoran.id', '=', 'setoran.idkode_setoran')
            ->selectRaw("
                SUM(CASE
                    WHEN LOWER(kode_setoran.jenis_setoran) = 'zakat' THEN setoran.nominal * 0.30
                    WHEN LOWER(kode_setoran.jenis_setoran) = 'infaq' THEN setoran.nominal * 0.20
                    ELSE 0
                END) as total
            ")
            ->value('total') ?? 0;

        $ringkasanJenis = Setoran::query()
            ->selectRaw('LOWER(kode_setoran.jenis_setoran) as jenis, SUM(setoran.nominal) as total')
            ->join('kode_setoran', 'kode_setoran.id', '=', 'setoran.idkode_setoran')
            ->groupBy('kode_setoran.jenis_setoran')
            ->pluck('total', 'jenis');

        $setoranTerbaru = Setoran::with(['muzaki', 'kodeSetoran', 'program'])
            ->latest('created_at')
            ->take(8)
            ->get();

        return view('lazismu.dashboard', compact(
            'totalMuzaki',
            'programActive',
            'totalSetoran',
            'totalDana',
            'totalDigunakan',
            'totalPdm',
            'ringkasanJenis',
            'setoranTerbaru'
        ));
    }
}
