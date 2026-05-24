<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModeratorRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Sprawdzamy, czy użytkownik jest zalogowany i ma rolę moderatora
        if (!auth()->check() || !auth()->user()->hasRole('moderator')) {
            abort(403, 'Brak uprawnień do panelu moderacji.');
        }

        return $next($request);
    }
}