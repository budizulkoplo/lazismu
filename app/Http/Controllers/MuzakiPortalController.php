<?php

namespace App\Http\Controllers;

use App\Models\Setoran;
use Illuminate\Http\Request;

class MuzakiPortalController extends Controller
{
    public function index(Request $request)
    {
        $muzaki = $request->attributes->get('muzaki_auth');

        $setorans = Setoran::with(['kodeSetoran', 'program'])
            ->where('idmuzaki', $muzaki->id)
            ->latest('created_at')
            ->paginate(10);

        $ringkasan = Setoran::query()
            ->selectRaw('LOWER(kode_setoran.jenis_setoran) as jenis, SUM(setoran.nominal) as total')
            ->join('kode_setoran', 'kode_setoran.id', '=', 'setoran.idkode_setoran')
            ->where('setoran.idmuzaki', $muzaki->id)
            ->groupBy('kode_setoran.jenis_setoran')
            ->pluck('total', 'jenis');

        return view('muzaki.portal', compact('muzaki', 'setorans', 'ringkasan'));
    }
}
