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
            'ringkasanJenis',
            'setoranTerbaru'
        ));
    }
}
