<?php

namespace App\Http\Controllers;

use App\Models\Kodetransaksi;
use App\Models\KodetransaksiHdr;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KodetransaksiController extends Controller
{
    public function index()
    {
        $headers = KodetransaksiHdr::withCount('kodeTransaksis')
            ->orderBy('keterangan')
            ->get();

        $kodeTransaksis = Kodetransaksi::with('header')
            ->orderBy('kodetransaksi')
            ->get();

        return view('lazismu.kodetransaksi.index', compact('headers', 'kodeTransaksis'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateKodeTransaksi($request);

        Kodetransaksi::create($validated);

        return redirect()->route('lazismu.kodetransaksi.index')->with('success', 'Kode transaksi berhasil ditambahkan.');
    }

    public function update(Request $request, Kodetransaksi $kodetransaksi)
    {
        $validated = $this->validateKodeTransaksi($request, $kodetransaksi);

        $kodetransaksi->update($validated);

        return redirect()->route('lazismu.kodetransaksi.index')->with('success', 'Kode transaksi berhasil diperbarui.');
    }

    public function destroy(Kodetransaksi $kodetransaksi)
    {
        $kodetransaksi->delete();

        return redirect()->route('lazismu.kodetransaksi.index')->with('success', 'Kode transaksi berhasil dihapus.');
    }

    public function storeHeader(Request $request)
    {
        $validated = $request->validate([
            'keterangan' => ['required', 'string', 'max:255', 'unique:kodetransaksi_hdr,keterangan'],
        ]);

        KodetransaksiHdr::create($validated);

        return redirect()->route('lazismu.kodetransaksi.index')->with('success', 'Header kode transaksi berhasil ditambahkan.');
    }

    public function updateHeader(Request $request, KodetransaksiHdr $header)
    {
        $validated = $request->validate([
            'keterangan' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kodetransaksi_hdr', 'keterangan')->ignore($header->id),
            ],
        ]);

        $header->update($validated);

        return redirect()->route('lazismu.kodetransaksi.index')->with('success', 'Header kode transaksi berhasil diperbarui.');
    }

    public function destroyHeader(KodetransaksiHdr $header)
    {
        if ($header->kodeTransaksis()->exists()) {
            return redirect()->route('lazismu.kodetransaksi.index')->with('error', 'Header masih dipakai kode transaksi dan tidak bisa dihapus.');
        }

        $header->delete();

        return redirect()->route('lazismu.kodetransaksi.index')->with('success', 'Header kode transaksi berhasil dihapus.');
    }

    private function validateKodeTransaksi(Request $request, ?Kodetransaksi $kodetransaksi = null): array
    {
        return $request->validate([
            'kodetransaksi' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kodetransaksi', 'kodetransaksi')->ignore($kodetransaksi?->id),
            ],
            'transaksi' => ['required', 'string', 'max:255'],
            'idheader' => [
                'nullable',
                'integer',
                Rule::exists('kodetransaksi_hdr', 'id')->whereNull('deleted_at'),
            ],
        ]);
    }
}
