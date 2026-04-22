<?php

namespace App\Http\Middleware;

use App\Models\Muzaki;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMuzakiAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        $muzakiId = $request->session()->get('muzaki_id');

        if (!$muzakiId) {
            return redirect()->route('muzaki.login');
        }

        $muzaki = Muzaki::find($muzakiId);

        if (!$muzaki) {
            $request->session()->forget(['muzaki_id', 'muzaki_nama']);
            return redirect()->route('muzaki.login');
        }

        $request->attributes->set('muzaki_auth', $muzaki);

        return $next($request);
    }
}
