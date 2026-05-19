<?php

namespace App\Http\Controllers;

use App\Models\Rekening;
use Illuminate\Http\Request;

class RekeningController extends Controller
{
    public function index()
    {
        $rekenings = Rekening::withCount('transaksis')
            ->orderBy('namarek')
            ->get();

        return view('lazismu.rekening.index', compact('rekenings'));
    }

    public function update(Request $request, Rekening $rekening)
    {
        $validated = $request->validate([
            'namarek' => 'required|string|max:100|unique:rekening,namarek,' . $rekening->id,
        ]);

        $rekening->update($validated);

        return redirect()->route('lazismu.rekening.index')->with('success', 'Rekening berhasil diperbarui.');
    }
}
