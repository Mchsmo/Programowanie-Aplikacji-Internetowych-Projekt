<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Sprawdza, czy zalogowany użytkownik posiada wymaganą rolę.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        foreach ($roles as $role) {
            if ($request->user()?->hasRole($role)) {
                return $next($request);
            }
        }

        abort(403, 'Brak uprawnień.');
    }
}