<?php

namespace App\Http\Controllers;

use App\Models\Muzaki;
use App\Models\Ranting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MuzakiController extends Controller
{
    public function index(Request $request)
    {
        $query = Muzaki::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('nik', 'like', '%' . $search . '%')
                    ->orWhere('nomor_induk_muzaki', 'like', '%' . $search . '%')
                    ->orWhere('no_hp', 'like', '%' . $search . '%');
            });
        }

        $muzakis = $query->latest()->get();
        $rantings = Ranting::query()
            ->orderBy('nama_ranting')
            ->pluck('nama_ranting');

        return view('lazismu.muzaki.index', compact('muzakis', 'rantings'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateMuzaki($request);

        Muzaki::create($validated);

        return redirect()->route('lazismu.muzaki.index')->with('success', 'Data muzaki berhasil ditambahkan.');
    }

    public function update(Request $request, Muzaki $muzaki)
    {
        $validated = $this->validateMuzaki($request, $muzaki);

        $muzaki->update($validated);

        return redirect()->route('lazismu.muzaki.index')->with('success', 'Data muzaki berhasil diperbarui.');
    }

    public function barcode(Muzaki $muzaki)
    {
        return view('lazismu.muzaki.print-barcode', compact('muzaki'));
    }

    public function card(Muzaki $muzaki)
    {
        return view('lazismu.muzaki.print-card', compact('muzaki'));
    }

    public function destroy(Muzaki $muzaki)
    {
        $muzaki->delete();

        return redirect()->route('lazismu.muzaki.index')->with('success', 'Data muzaki berhasil dihapus.');
    }

    private function validateMuzaki(Request $request, ?Muzaki $muzaki = null): array
    {
        $id = $muzaki?->id;

        $validated = $request->validate([
            'jenis_muzaki' => ['required', Rule::in(['pribadi', 'kelompok', 'aum'])],
            'nik' => [
                Rule::requiredIf(fn () => $request->jenis_muzaki === 'pribadi'),
                Rule::excludeIf(fn () => $request->jenis_muzaki !== 'pribadi'),
                'nullable',
                'string',
                'size:16',
                Rule::unique('muzaki', 'nik')->ignore($id),
            ],
            'nomor_induk_muzaki' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('muzaki', 'nomor_induk_muzaki')->ignore($id),
            ],
            'nama' => 'required|string|max:100',
            'tgl_lahir' => [
                Rule::excludeIf(fn () => $request->jenis_muzaki === 'aum'),
                'nullable',
                'date',
            ],
            'ranting' => [
                Rule::requiredIf(fn () => $request->jenis_muzaki !== 'aum'),
                Rule::excludeIf(fn () => $request->jenis_muzaki === 'aum'),
                'nullable',
                'string',
                'max:255',
                Rule::exists('ranting', 'nama_ranting'),
            ],
            'alamat' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
        ]);

        if (blank($validated['nomor_induk_muzaki'] ?? null) && blank($muzaki?->nomor_induk_muzaki)) {
            $validated['nomor_induk_muzaki'] = $this->generateNomorInduk();
        } elseif (blank($validated['nomor_induk_muzaki'] ?? null)) {
            unset($validated['nomor_induk_muzaki']);
        }

        $validated['jenis_kelamin'] = $validated['jenis_kelamin'] ?? 'L';
        if ($validated['jenis_muzaki'] !== 'pribadi') {
            $validated['nik'] = null;
        }
        if ($validated['jenis_muzaki'] === 'aum') {
            $validated['ranting'] = null;
            $validated['tgl_lahir'] = null;
            $validated['aum'] = null;
        } else {
            $validated['aum'] = null;
        }

        return $validated;
    }

    private function generateNomorInduk(): string
    {
        do {
            $code = 'MZK-' . now()->format('ym') . '-' . Str::upper(Str::random(5));
        } while (Muzaki::where('nomor_induk_muzaki', $code)->exists());

        return $code;
    }
}
