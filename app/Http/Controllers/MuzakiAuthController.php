<?php

namespace App\Http\Controllers;

use App\Models\Muzaki;
use App\Models\Program;
use App\Models\Ranting;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MuzakiAuthController extends Controller
{
    public function create()
    {
        if (session()->has('muzaki_id')) {
            return redirect()->route('muzaki.mobile');
        }

        $setting = Setting::first();
        $programs = Program::active()->latest()->take(8)->get();

        return view('muzaki.login', compact('setting', 'programs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'login_id' => 'required|string|max:30',
        ]);

        $loginId = trim($validated['login_id']);
        $muzaki = Muzaki::where('nik', $loginId)
            ->orWhere('nomor_induk_muzaki', $loginId)
            ->first();

        if (!$muzaki) {
            return back()->withErrors(['login_id' => 'NIK atau Nomor Induk Muzaki tidak ditemukan.'])->onlyInput('login_id');
        }

        $request->session()->put([
            'muzaki_id' => $muzaki->id,
            'muzaki_nama' => $muzaki->nama,
        ]);

        return redirect()->route('muzaki.mobile');
    }

    public function register()
    {
        if (session()->has('muzaki_id')) {
            return redirect()->route('muzaki.mobile');
        }

        $setting = Setting::first();
        $rantings = Ranting::query()
            ->orderBy('id')
            ->pluck('nama_ranting');

        return view('muzaki.register', compact('setting', 'rantings'));
    }

    public function storeRegistration(Request $request)
    {
        $validated = $request->validate([
            'jenis_muzaki' => ['required', Rule::in(['pribadi', 'kelompok'])],
            'nik' => [
                Rule::requiredIf(fn () => $request->jenis_muzaki === 'pribadi'),
                Rule::excludeIf(fn () => $request->jenis_muzaki === 'kelompok'),
                'nullable',
                'string',
                'size:16',
                'unique:muzaki,nik',
            ],
            'nama' => 'required|string|max:100',
            'tgl_lahir' => [
                Rule::excludeIf(fn () => $request->jenis_muzaki === 'kelompok'),
                'nullable',
                'date',
            ],
            'ranting' => [
                Rule::requiredIf(fn () => $request->jenis_muzaki === 'pribadi'),
                Rule::excludeIf(fn () => $request->jenis_muzaki === 'kelompok'),
                'nullable',
                'string',
                'max:255',
                Rule::exists('ranting', 'nama_ranting'),
            ],
            'alamat' => 'nullable|string|max:255',
            'jenis_kelamin' => [
                Rule::requiredIf(fn () => $request->jenis_muzaki === 'pribadi'),
                Rule::excludeIf(fn () => $request->jenis_muzaki === 'kelompok'),
                'nullable',
                'in:L,P',
            ],
            'no_hp' => 'nullable|string|max:20',
        ]);

        $validated['nomor_induk_muzaki'] = $this->generateNomorInduk();
        $validated['aum'] = null;

        if ($validated['jenis_muzaki'] === 'kelompok') {
            $validated['nik'] = null;
            $validated['tgl_lahir'] = null;
            $validated['ranting'] = null;
            $validated['jenis_kelamin'] = null;
        }

        $muzaki = Muzaki::create($validated);

        $request->session()->put([
            'muzaki_id' => $muzaki->id,
            'muzaki_nama' => $muzaki->nama,
        ]);

        return redirect()->route('muzaki.mobile')->with('success', 'Registrasi berhasil. Nomor Induk Muzaki Anda: ' . $muzaki->nomor_induk_muzaki);
    }

    public function destroy(Request $request)
    {
        $request->session()->forget(['muzaki_id', 'muzaki_nama']);

        return redirect()->route('muzaki.login');
    }

    private function generateNomorInduk(): string
    {
        do {
            $code = 'MZK-' . now()->format('ym') . '-' . Str::upper(Str::random(5));
        } while (Muzaki::where('nomor_induk_muzaki', $code)->exists());

        return $code;
    }
}
