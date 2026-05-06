<?php

namespace App\Http\Controllers;

use App\Models\Muzaki;
use Illuminate\Http\Request;

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
                    ->orWhere('no_hp', 'like', '%' . $search . '%');
            });
        }

        $muzakis = $query->latest()->get();

        return view('lazismu.muzaki.index', compact('muzakis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|size:16|unique:muzaki,nik',
            'nama' => 'required|string|max:100',
            'tgl_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
        ]);

        Muzaki::create($validated);

        return redirect()->route('lazismu.muzaki.index')->with('success', 'Data muzaki berhasil ditambahkan.');
    }

    public function update(Request $request, Muzaki $muzaki)
    {
        $validated = $request->validate([
            'nik' => 'required|string|size:16|unique:muzaki,nik,' . $muzaki->id,
            'nama' => 'required|string|max:100',
            'tgl_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
        ]);

        $muzaki->update($validated);

        return redirect()->route('lazismu.muzaki.index')->with('success', 'Data muzaki berhasil diperbarui.');
    }

    public function destroy(Muzaki $muzaki)
    {
        $muzaki->delete();

        return redirect()->route('lazismu.muzaki.index')->with('success', 'Data muzaki berhasil dihapus.');
    }
}
