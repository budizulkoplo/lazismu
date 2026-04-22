<?php

namespace App\Http\Controllers;

use App\Models\Muzaki;
use App\Models\Setting;
use Illuminate\Http\Request;

class MuzakiAuthController extends Controller
{
    public function create()
    {
        if (session()->has('muzaki_id')) {
            return redirect()->route('muzaki.mobile');
        }

        $setting = Setting::first();

        return view('muzaki.login', compact('setting'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|size:16',
        ]);

        $muzaki = Muzaki::where('nik', $validated['nik'])->first();

        if (!$muzaki) {
            return back()->withErrors(['nik' => 'NIK tidak ditemukan.'])->onlyInput('nik');
        }

        $request->session()->put([
            'muzaki_id' => $muzaki->id,
            'muzaki_nama' => $muzaki->nama,
        ]);

        return redirect()->route('muzaki.mobile');
    }

    public function destroy(Request $request)
    {
        $request->session()->forget(['muzaki_id', 'muzaki_nama']);

        return redirect()->route('muzaki.login');
    }
}
