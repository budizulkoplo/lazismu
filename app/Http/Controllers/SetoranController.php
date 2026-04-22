<?php

namespace App\Http\Controllers;

use App\Models\KodeSetoran;
use App\Models\Muzaki;
use App\Models\Program;
use App\Models\Setoran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SetoranController extends Controller
{
    public function index(Request $request)
    {
        $query = Setoran::with(['muzaki', 'kodeSetoran', 'program']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('muzaki', function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('nik', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('jenis_setoran')) {
            $query->whereHas('kodeSetoran', function ($q) use ($request) {
                $q->where('jenis_setoran', $request->jenis_setoran);
            });
        }

        if ($request->filled('program_id')) {
            $query->where('idprogram', $request->program_id);
        }

        $setorans = $query->latest('created_at')->paginate(10)->withQueryString();
        $muzakis = Muzaki::orderBy('nama')->get();
        $kodeSetorans = KodeSetoran::orderBy('jenis_setoran')->get();
        $programs = Program::active()->orderBy('nama_program')->get();

        return view('lazismu.setoran.index', compact('setorans', 'muzakis', 'kodeSetorans', 'programs'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateSetoran($request);

        DB::transaction(function () use ($validated) {
            $setoran = Setoran::create($validated);
            $this->syncProgramTerkumpul($setoran->idprogram);
        });

        return redirect()->route('lazismu.setoran.index')->with('success', 'Setoran berhasil ditambahkan.');
    }

    public function update(Request $request, Setoran $setoran)
    {
        $validated = $this->validateSetoran($request);
        $oldProgramId = $setoran->idprogram;

        DB::transaction(function () use ($setoran, $validated, $oldProgramId) {
            $setoran->update($validated);
            $this->syncProgramTerkumpul($oldProgramId);
            $this->syncProgramTerkumpul($setoran->idprogram);
        });

        return redirect()->route('lazismu.setoran.index')->with('success', 'Setoran berhasil diperbarui.');
    }

    public function destroy(Setoran $setoran)
    {
        $programId = $setoran->idprogram;

        DB::transaction(function () use ($setoran, $programId) {
            $setoran->delete();
            $this->syncProgramTerkumpul($programId);
        });

        return redirect()->route('lazismu.setoran.index')->with('success', 'Setoran berhasil dihapus.');
    }

    private function validateSetoran(Request $request): array
    {
        $validated = $request->validate([
            'idmuzaki' => 'required|exists:muzaki,id',
            'idkode_setoran' => 'required|exists:kode_setoran,id',
            'idprogram' => 'nullable|exists:program,id',
            'nominal' => 'required|numeric|min:1',
            'created_at' => 'required|date',
        ]);

        $kodeSetoran = KodeSetoran::findOrFail($validated['idkode_setoran']);
        $jenisSetoran = strtolower($kodeSetoran->jenis_setoran);

        if ($jenisSetoran === 'program' && empty($validated['idprogram'])) {
            throw ValidationException::withMessages([
                'idprogram' => 'Program wajib dipilih untuk setoran program.',
            ]);
        }

        if ($jenisSetoran !== 'program') {
            $validated['idprogram'] = null;
        } else {
            $program = Program::active()->find($validated['idprogram']);
            if (!$program) {
                throw ValidationException::withMessages([
                    'idprogram' => 'Program yang dipilih harus aktif.',
                ]);
            }
        }

        return $validated;
    }

    private function syncProgramTerkumpul($programId): void
    {
        if (!$programId) {
            return;
        }

        $program = Program::find($programId);

        if (!$program) {
            return;
        }

        $total = Setoran::where('idprogram', $programId)->sum('nominal');
        $program->update(['terkumpul' => $total]);
    }
}
