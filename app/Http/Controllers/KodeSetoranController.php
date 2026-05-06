<?php

namespace App\Http\Controllers;

use App\Models\KodeSetoran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KodeSetoranController extends Controller
{
    public function index(Request $request)
    {
        $query = KodeSetoran::query();

        if ($request->filled('search')) {
            $query->where('jenis_setoran', 'like', '%' . $request->search . '%');
        }

        $kodeSetorans = $query->orderBy('jenis_setoran')->get();

        return view('lazismu.kode_setoran.index', compact('kodeSetorans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_setoran' => [
                'required',
                'string',
                'max:100',
                Rule::in(['zakat', 'infaq', 'program']),
                'unique:kode_setoran,jenis_setoran',
            ],
        ]);

        KodeSetoran::create([
            'jenis_setoran' => strtolower($validated['jenis_setoran']),
        ]);

        return redirect()->route('lazismu.kode-setoran.index')->with('success', 'Kode setoran berhasil ditambahkan.');
    }

    public function update(Request $request, KodeSetoran $kodeSetoran)
    {
        $validated = $request->validate([
            'jenis_setoran' => [
                'required',
                'string',
                'max:100',
                Rule::in(['zakat', 'infaq', 'program']),
                'unique:kode_setoran,jenis_setoran,' . $kodeSetoran->id,
            ],
        ]);

        $kodeSetoran->update([
            'jenis_setoran' => strtolower($validated['jenis_setoran']),
        ]);

        return redirect()->route('lazismu.kode-setoran.index')->with('success', 'Kode setoran berhasil diperbarui.');
    }

    public function destroy(KodeSetoran $kodeSetoran)
    {
        if ($kodeSetoran->setorans()->exists()) {
            return redirect()->route('lazismu.kode-setoran.index')->with('error', 'Kode setoran sudah dipakai transaksi dan tidak bisa dihapus.');
        }

        $kodeSetoran->delete();

        return redirect()->route('lazismu.kode-setoran.index')->with('success', 'Kode setoran berhasil dihapus.');
    }
}
