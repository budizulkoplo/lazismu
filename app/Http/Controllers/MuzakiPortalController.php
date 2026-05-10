<?php

namespace App\Http\Controllers;

use App\Models\Setoran;
use App\Models\Program;
use App\Models\TargetSetoranProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MuzakiPortalController extends Controller
{
    public function index(Request $request)
    {
        $muzaki = $request->attributes->get('muzaki_auth');
        $programs = Program::active()->latest()->get();

        return view('muzaki.portal', compact('muzaki', 'programs'));
    }

    public function setoranInfo(Request $request)
    {
        $muzaki = $request->attributes->get('muzaki_auth');
        $programs = Program::active()->latest()->get();

        return view('muzaki.setoran-info', compact('muzaki', 'programs'));
    }

    public function profile(Request $request)
    {
        $muzaki = $request->attributes->get('muzaki_auth');

        return view('muzaki.profile', compact('muzaki'));
    }

    public function updateProfile(Request $request)
    {
        $muzaki = $request->attributes->get('muzaki_auth');

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'tgl_lahir' => [
                Rule::requiredIf(fn () => ($muzaki->jenis_muzaki ?? 'pribadi') !== 'aum'),
                'nullable',
                'date',
            ],
            'jenis_kelamin' => ['nullable', 'string', 'max:50'],
            'no_hp' => ['nullable', 'string', 'max:15'],
            'email' => ['nullable', 'email', 'max:100'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if (($muzaki->jenis_muzaki ?? 'pribadi') === 'aum') {
            $validated['tgl_lahir'] = null;
        }

        if ($request->hasFile('foto')) {
            if ($muzaki->foto) {
                Storage::disk('public')->delete($muzaki->foto);
            }

            $validated['foto'] = $this->storeProfilePhoto($request->file('foto'));
        }

        $muzaki->update($validated);
        $request->session()->put('muzaki_nama', $muzaki->nama);

        return redirect()->route('muzaki.profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function riwayat(Request $request)
    {
        $muzaki = $request->attributes->get('muzaki_auth');

        $ringkasan = Setoran::query()
            ->selectRaw('LOWER(kode_setoran.jenis_setoran) as jenis, SUM(setoran.nominal) as total, COUNT(*) as jumlah')
            ->join('kode_setoran', 'kode_setoran.id', '=', 'setoran.idkode_setoran')
            ->where('setoran.idmuzaki', $muzaki->id)
            ->whereIn('kode_setoran.jenis_setoran', ['zakat', 'infaq'])
            ->groupBy('kode_setoran.jenis_setoran')
            ->get()
            ->keyBy('jenis');

        $programSetorans = Setoran::query()
            ->selectRaw('program.id, program.nama_program, program.target, program.terkumpul, program.banner_path, SUM(setoran.nominal) as total, COUNT(*) as jumlah')
            ->join('kode_setoran', 'kode_setoran.id', '=', 'setoran.idkode_setoran')
            ->join('program', 'program.id', '=', 'setoran.idprogram')
            ->where('setoran.idmuzaki', $muzaki->id)
            ->where('kode_setoran.jenis_setoran', 'program')
            ->groupBy('program.id', 'program.nama_program', 'program.target', 'program.terkumpul', 'program.banner_path')
            ->orderBy('program.nama_program')
            ->get();

        return view('muzaki.riwayat', compact('muzaki', 'ringkasan', 'programSetorans'));
    }

    public function riwayatDetail(Request $request, string $jenis)
    {
        abort_unless(in_array($jenis, ['zakat', 'infaq'], true), 404);

        $muzaki = $request->attributes->get('muzaki_auth');
        $setorans = Setoran::with(['kodeSetoran', 'program'])
            ->where('idmuzaki', $muzaki->id)
            ->whereHas('kodeSetoran', fn ($q) => $q->where('jenis_setoran', $jenis))
            ->latest('created_at')
            ->paginate(10);

        $total = (float) Setoran::query()
            ->join('kode_setoran', 'kode_setoran.id', '=', 'setoran.idkode_setoran')
            ->where('setoran.idmuzaki', $muzaki->id)
            ->where('kode_setoran.jenis_setoran', $jenis)
            ->sum('setoran.nominal');

        return view('muzaki.riwayat-detail', compact('muzaki', 'jenis', 'setorans', 'total'));
    }

    public function programDetail(Request $request, Program $program)
    {
        $muzaki = $request->attributes->get('muzaki_auth');

        $setorans = Setoran::with(['kodeSetoran', 'program'])
            ->where('idmuzaki', $muzaki->id)
            ->where('idprogram', $program->id)
            ->whereHas('kodeSetoran', fn ($q) => $q->where('jenis_setoran', 'program'))
            ->latest('created_at')
            ->paginate(10);

        $totalMuzaki = (float) Setoran::query()
            ->join('kode_setoran', 'kode_setoran.id', '=', 'setoran.idkode_setoran')
            ->where('setoran.idmuzaki', $muzaki->id)
            ->where('setoran.idprogram', $program->id)
            ->where('kode_setoran.jenis_setoran', 'program')
            ->sum('setoran.nominal');

        $rantingChart = $this->programGroupChart($program->id, 'ranting');
        $aumChart = $this->programGroupChart($program->id, 'aum');

        return view('muzaki.program-detail', compact(
            'muzaki',
            'program',
            'setorans',
            'totalMuzaki',
            'rantingChart',
            'aumChart'
        ));
    }

    private function programGroupChart(int $programId, string $group): array
    {
        $isAum = $group === 'aum';
        $labelColumn = $isAum ? 'muzaki.nama' : 'muzaki.ranting';

        $targets = TargetSetoranProgram::query()
            ->selectRaw($labelColumn . ' as label, SUM(target_setoran_program.target) as target')
            ->join('muzaki', 'muzaki.id', '=', 'target_setoran_program.idmuzaki')
            ->where('target_setoran_program.idprogram', $programId)
            ->when($isAum, fn ($query) => $query->where('muzaki.jenis_muzaki', 'aum'))
            ->when(!$isAum, fn ($query) => $query->where('muzaki.jenis_muzaki', '<>', 'aum'))
            ->whereNotNull($isAum ? 'muzaki.nama' : 'muzaki.ranting')
            ->where($isAum ? 'muzaki.nama' : 'muzaki.ranting', '!=', '')
            ->groupBy($isAum ? 'muzaki.nama' : 'muzaki.ranting')
            ->get()
            ->keyBy('label');

        $setorans = Setoran::query()
            ->selectRaw($labelColumn . ' as label, SUM(setoran.nominal) as total')
            ->join('kode_setoran', 'kode_setoran.id', '=', 'setoran.idkode_setoran')
            ->join('muzaki', 'muzaki.id', '=', 'setoran.idmuzaki')
            ->where('setoran.idprogram', $programId)
            ->where('kode_setoran.jenis_setoran', 'program')
            ->when($isAum, fn ($query) => $query->where('muzaki.jenis_muzaki', 'aum'))
            ->when(!$isAum, fn ($query) => $query->where('muzaki.jenis_muzaki', '<>', 'aum'))
            ->whereNotNull($isAum ? 'muzaki.nama' : 'muzaki.ranting')
            ->where($isAum ? 'muzaki.nama' : 'muzaki.ranting', '!=', '')
            ->groupBy($isAum ? 'muzaki.nama' : 'muzaki.ranting')
            ->get()
            ->keyBy('label');

        return $targets->keys()
            ->merge($setorans->keys())
            ->unique()
            ->sort()
            ->values()
            ->map(function ($label) use ($targets, $setorans) {
                $target = (float) optional($targets->get($label))->target;
                $total = (float) optional($setorans->get($label))->total;

                return [
                    'label' => $label ?: '-',
                    'target' => $target,
                    'total' => $total,
                    'percent' => $target > 0 ? min(100, round(($total / $target) * 100, 1)) : 0,
                ];
            })
            ->all();
    }

    private function storeProfilePhoto($file): string
    {
        $directory = 'lazismu/muzaki/foto';
        Storage::disk('public')->makeDirectory($directory);

        $filename = now()->format('YmdHis') . '-' . Str::random(8) . '.' . $file->getClientOriginalExtension();

        return $file->storeAs($directory, $filename, 'public');
    }
}
