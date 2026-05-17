<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckArtisan
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isActive()) {
            abort(403, 'Acesso negado.');
        }

        if (! $user->isAdmin() && ! $user->isArtisan()) {
            abort(403, 'Acesso restrito a artesãos.');
        }

        if (! $user->isAdmin() && $user->isArtisan()) {
            $profile = $user->artisanProfile;
            if (! $profile || ! $profile->isApproved()) {
                return redirect()->route('user.perfil');
            }
        }

        return $next($request);
    }
}
