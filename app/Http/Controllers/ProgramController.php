<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = Program::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_program', 'like', '%' . $search . '%')
                    ->orWhere('lokasi', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $programs = $query->latest()->get();

        return view('lazismu.program.index', compact('programs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_program' => 'required|string|max:100',
            'lokasi' => 'nullable|string|max:100',
            'tgl_mulai' => 'nullable|date',
            'tgl_selesai' => 'nullable|date|after_or_equal:tgl_mulai',
            'target' => 'nullable|numeric|min:0',
            'banner' => 'nullable|image|max:2048',
            'status' => 'required|in:active,nonactive,selesai',
        ]);

        if ($request->hasFile('banner')) {
            $validated['banner_path'] = $request->file('banner')->store('lazismu/program', 'public');
        }

        unset($validated['banner']);
        $validated['target'] = $validated['target'] ?? 0;
        $validated['terkumpul'] = 0;

        Program::create($validated);

        return redirect()->route('lazismu.program.index')->with('success', 'Program berhasil ditambahkan.');
    }

    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'nama_program' => 'required|string|max:100',
            'lokasi' => 'nullable|string|max:100',
            'tgl_mulai' => 'nullable|date',
            'tgl_selesai' => 'nullable|date|after_or_equal:tgl_mulai',
            'target' => 'nullable|numeric|min:0',
            'banner' => 'nullable|image|max:2048',
            'status' => 'required|in:active,nonactive,selesai',
        ]);

        if ($request->hasFile('banner')) {
            if ($program->banner_path) {
                Storage::disk('public')->delete($program->banner_path);
            }

            $validated['banner_path'] = $request->file('banner')->store('lazismu/program', 'public');
        }

        unset($validated['banner']);
        $validated['target'] = $validated['target'] ?? 0;
        $program->update($validated);

        return redirect()->route('lazismu.program.index')->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy(Program $program)
    {
        if ($program->setorans()->exists()) {
            return redirect()->route('lazismu.program.index')->with('error', 'Program sudah memiliki setoran dan tidak bisa dihapus.');
        }

        $program->delete();

        return redirect()->route('lazismu.program.index')->with('success', 'Program berhasil dihapus.');
    }
}
