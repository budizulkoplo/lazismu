<?php

namespace App\Http\Controllers;

use App\Models\Muzaki;
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

        return view('lazismu.muzaki.index', compact('muzakis'));
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
            'jenis_muzaki' => ['required', Rule::in(['pribadi', 'kelompok'])],
            'nik' => [
                Rule::requiredIf(fn () => $request->jenis_muzaki === 'pribadi'),
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
            'tgl_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'target_setoran' => 'nullable|numeric|min:0',
        ]);

        if (blank($validated['nomor_induk_muzaki'] ?? null)) {
            $validated['nomor_induk_muzaki'] = $this->generateNomorInduk();
        }

        $validated['jenis_kelamin'] = $validated['jenis_kelamin'] ?? 'L';
        $validated['target_setoran'] = $validated['target_setoran'] ?? 0;

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
