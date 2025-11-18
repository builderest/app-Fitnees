<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPremium
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('pricing');
        }

        if (in_array($user->role, ['admin', 'coach'], true)) {
            return $next($request);
        }

        if ($user->plan !== 'premium' || ($user->premium_until && $user->premium_until->isPast())) {
            return redirect()->route('pricing')->withErrors('Necesitas un plan premium para acceder.');
        }

        return $next($request);
    }
}
