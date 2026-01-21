<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPosAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $posId = $request->route('pos') ?? $request->input('pos_id');
        
        if ($posId && !$user->canAccessPos($posId)) {
            abort(403, 'Anda tidak memiliki akses ke pos ini.');
        }

        return $next($request);
    }
}