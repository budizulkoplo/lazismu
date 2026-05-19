<?php

namespace App\Http\Controllers;

use App\Models\KodeSetoran;
use App\Models\Muzaki;
use App\Models\Program;
use App\Models\Setoran;
use App\Models\TargetSetoranProgram;
use App\Services\RekeningTransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SetoranController extends Controller
{
    public function __construct(private RekeningTransactionService $rekeningTransactions)
    {
    }

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

        if (strtolower($request->jenis_setoran ?? '') === 'program' && $request->filled('program_id')) {
            $query->where('idprogram', $request->program_id);
        }

        $setorans = $query->latest('created_at')->get();
        $muzakis = Muzaki::orderBy('nama')->get();
        $kodeSetorans = KodeSetoran::orderBy('jenis_setoran')->get();
        $programs = Program::active()->orderBy('nama_program')->get();
        $filterPrograms = Program::orderBy('nama_program')->get();
        $selectedMuzaki = $request->filled('muzaki_id')
            ? Muzaki::find($request->muzaki_id)
            : null;
        $selectedSetoranQuery = $selectedMuzaki
            ? Setoran::with(['kodeSetoran', 'program'])
                ->where('idmuzaki', $selectedMuzaki->id)
            : null;

        if ($selectedSetoranQuery && $request->filled('jenis_setoran')) {
            $selectedSetoranQuery->whereHas('kodeSetoran', function ($q) use ($request) {
                $q->where('jenis_setoran', $request->jenis_setoran);
            });
        }

        if ($selectedSetoranQuery && strtolower($request->jenis_setoran ?? '') === 'program' && $request->filled('program_id')) {
            $selectedSetoranQuery->where('idprogram', $request->program_id);
        }

        $selectedSetorans = $selectedSetoranQuery
            ? $selectedSetoranQuery->latest('created_at')->take(25)->get()
            : collect();
        $kodeByJenis = $kodeSetorans->keyBy(fn ($item) => strtolower(trim($item->jenis_setoran)));
        $programTargets = TargetSetoranProgram::all()
            ->groupBy('idmuzaki')
            ->map(fn ($items) => $items->keyBy('idprogram'));
        $programTotals = Setoran::query()
            ->selectRaw('idmuzaki, idprogram, SUM(nominal) as total')
            ->whereNotNull('idprogram')
            ->groupBy('idmuzaki', 'idprogram')
            ->get()
            ->groupBy('idmuzaki')
            ->map(fn ($items) => $items->keyBy('idprogram'));
        $selectedProgramStats = collect();

        if ($selectedMuzaki) {
            $selectedProgramStats = $programs->map(function ($program) use ($selectedMuzaki, $programTargets, $programTotals) {
                $total = (float) optional(optional($programTotals->get($selectedMuzaki->id))->get($program->id))->total;
                $target = (float) optional(optional($programTargets->get($selectedMuzaki->id))->get($program->id))->target;

                return [
                    'program' => $program,
                    'total' => $total,
                    'target' => $target,
                    'percent' => $target > 0 ? min(100, round(($total / $target) * 100, 1)) : 0,
                ];
            });
        }
        $programStatsForJs = [];

        foreach ($muzakis as $muzaki) {
            foreach ($programs as $program) {
                $total = (float) optional(optional($programTotals->get($muzaki->id))->get($program->id))->total;
                $target = (float) optional(optional($programTargets->get($muzaki->id))->get($program->id))->target;
                $programStatsForJs[$muzaki->id][$program->id] = [
                    'total' => $total,
                    'target' => $target,
                    'is_first' => $total <= 0,
                ];
            }
        }

        return view('lazismu.setoran.index', compact(
            'setorans',
            'muzakis',
            'kodeSetorans',
            'programs',
            'filterPrograms',
            'selectedMuzaki',
            'selectedSetorans',
            'kodeByJenis',
            'programTargets',
            'programTotals',
            'selectedProgramStats',
            'programStatsForJs'
        ));
    }

    public function store(Request $request)
    {
        $validated = $this->validateSetoran($request);
        $targetProgram = $validated['target_program'] ?? null;
        unset($validated['target_program']);

        $setoran = DB::transaction(function () use ($validated, $targetProgram) {
            $kodeSetoran = KodeSetoran::findOrFail($validated['idkode_setoran']);
            $program = !empty($validated['idprogram']) ? Program::find($validated['idprogram']) : null;
            $rekening = $this->rekeningTransactions->rekeningForKelompok($kodeSetoran->jenis_setoran, $program);
            $validated['idrekening'] = $rekening->getKey();

            $setoran = Setoran::create($validated);
            $this->syncTargetSetoranProgram($setoran->idmuzaki, $setoran->idprogram, $targetProgram);
            $this->syncProgramTerkumpul($setoran->idprogram);
            $nominalMasuk = (float) ($setoran->nominal_digunakan ?? $setoran->nominal);
            $this->rekeningTransactions->record(
                $setoran,
                $rekening->getKey(),
                $setoran->created_at->format('Y-m-d'),
                'in',
                $nominalMasuk,
                'Setoran ' . ucfirst($kodeSetoran->jenis_setoran) . ' - ' . ($setoran->muzaki->nama ?? '-') . ' (masuk rekening setelah PDM)'
            );

            return $setoran;
        });

        return redirect()->route('lazismu.setoran.print', $setoran)->with('success', 'Setoran berhasil ditambahkan.');
    }

    public function update(Request $request, Setoran $setoran)
    {
        $validated = $this->validateSetoran($request);
        $targetProgram = $validated['target_program'] ?? null;
        unset($validated['target_program']);
        $oldProgramId = $setoran->idprogram;

        DB::transaction(function () use ($setoran, $validated, $oldProgramId, $targetProgram) {
            $this->rekeningTransactions->reverse($setoran);
            $kodeSetoran = KodeSetoran::findOrFail($validated['idkode_setoran']);
            $program = !empty($validated['idprogram']) ? Program::find($validated['idprogram']) : null;
            $rekening = $this->rekeningTransactions->rekeningForKelompok($kodeSetoran->jenis_setoran, $program);
            $validated['idrekening'] = $rekening->getKey();

            $setoran->update($validated);
            $this->syncTargetSetoranProgram($setoran->idmuzaki, $setoran->idprogram, $targetProgram);
            $this->syncProgramTerkumpul($oldProgramId);
            $this->syncProgramTerkumpul($setoran->idprogram);
            $nominalMasuk = (float) ($setoran->nominal_digunakan ?? $setoran->nominal);
            $this->rekeningTransactions->record(
                $setoran,
                $rekening->getKey(),
                $setoran->created_at->format('Y-m-d'),
                'in',
                $nominalMasuk,
                'Setoran ' . ucfirst($kodeSetoran->jenis_setoran) . ' - ' . ($setoran->muzaki->nama ?? '-') . ' (masuk rekening setelah PDM)'
            );
        });

        return redirect()->route('lazismu.setoran.print', $setoran)->with('success', 'Setoran berhasil diperbarui.');
    }

    public function destroy(Setoran $setoran)
    {
        $programId = $setoran->idprogram;

        DB::transaction(function () use ($setoran, $programId) {
            $this->rekeningTransactions->reverse($setoran);
            $setoran->delete();
            $this->syncProgramTerkumpul($programId);
        });

        return redirect()->route('lazismu.setoran.index')->with('success', 'Setoran berhasil dihapus.');
    }

    public function print(Setoran $setoran)
    {
        $setoran->load(['muzaki', 'kodeSetoran', 'program']);

        return view('lazismu.setoran.print-nota', compact('setoran'));
    }

    private function validateSetoran(Request $request): array
    {
        $validated = $request->validate([
            'idmuzaki' => 'required|exists:muzaki,id',
            'idkode_setoran' => 'required|exists:kode_setoran,id',
            'idprogram' => 'nullable|exists:program,id',
            'nominal' => 'required|numeric|min:1',
            'target_program' => 'nullable|numeric|min:0',
            'created_at' => 'required|date',
        ]);

        $kodeSetoran = KodeSetoran::findOrFail($validated['idkode_setoran']);
        $jenisSetoran = strtolower(trim($kodeSetoran->jenis_setoran));

        if ($jenisSetoran === 'program' && empty($validated['idprogram'])) {
            throw ValidationException::withMessages([
                'idprogram' => 'Program wajib dipilih untuk setoran program.',
            ]);
        }

        if ($jenisSetoran !== 'program') {
            $validated['idprogram'] = null;
            unset($validated['target_program']);
        } else {
            $program = Program::active()->find($validated['idprogram']);
            if (!$program) {
                throw ValidationException::withMessages([
                    'idprogram' => 'Program yang dipilih harus aktif.',
                ]);
            }

            $targetExists = TargetSetoranProgram::where('idmuzaki', $validated['idmuzaki'])
                ->where('idprogram', $validated['idprogram'])
                ->exists();

            if (!$targetExists && blank($validated['target_program'] ?? null)) {
                throw ValidationException::withMessages([
                    'target_program' => 'Target setoran wajib diisi untuk setoran program pertama muzaki.',
                ]);
            }
        }

        [$validated['nominal_digunakan'], $validated['nominal_pdm']] = $this->splitNominal(
            $jenisSetoran,
            (float) $validated['nominal']
        );

        return $validated;
    }

    private function syncTargetSetoranProgram(?int $muzakiId, ?int $programId, mixed $target): void
    {
        if (!$muzakiId || !$programId) {
            return;
        }

        if ($target === null || $target === '') {
            return;
        }

        TargetSetoranProgram::updateOrCreate(
            [
                'idmuzaki' => $muzakiId,
                'idprogram' => $programId,
            ],
            [
                'target' => (float) $target,
            ]
        );
    }

    private function splitNominal(string $jenisSetoran, float $nominal): array
    {
        return match ($jenisSetoran) {
            'zakat' => [$nominal * 0.70, $nominal * 0.30],
            'infaq' => [$nominal * 0.80, $nominal * 0.20],
            'program' => [$nominal, 0],
            default => [$nominal, 0],
        };
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
