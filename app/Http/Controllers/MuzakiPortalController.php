<?php

namespace App\Http\Controllers;

use App\Models\Setoran;
use App\Models\Program;
use Illuminate\Http\Request;

class MuzakiPortalController extends Controller
{
    public function index(Request $request)
    {
        $muzaki = $request->attributes->get('muzaki_auth');

        $jenis = $request->query('jenis');
        $setorans = Setoran::with(['kodeSetoran', 'program'])
            ->where('idmuzaki', $muzaki->id)
            ->when($jenis, function ($query) use ($jenis) {
                $query->whereHas('kodeSetoran', fn ($q) => $q->where('jenis_setoran', $jenis));
            })
            ->when($request->filled('program_id'), function ($query) use ($request) {
                $query->where('idprogram', $request->program_id);
            })
            ->latest('created_at')
            ->paginate(10);

        $ringkasan = Setoran::query()
            ->selectRaw('LOWER(kode_setoran.jenis_setoran) as jenis, SUM(setoran.nominal) as total')
            ->join('kode_setoran', 'kode_setoran.id', '=', 'setoran.idkode_setoran')
            ->where('setoran.idmuzaki', $muzaki->id)
            ->groupBy('kode_setoran.jenis_setoran')
            ->pluck('total', 'jenis');

        $programs = Program::active()->latest()->get();
        $programDetail = $request->filled('program_id')
            ? Program::withSum('setorans as total_setoran', 'nominal')->find($request->program_id)
            : null;

        return view('muzaki.portal', compact('muzaki', 'setorans', 'ringkasan', 'programs', 'jenis', 'programDetail'));
    }
}
