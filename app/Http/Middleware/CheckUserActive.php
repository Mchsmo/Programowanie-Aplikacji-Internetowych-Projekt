<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActive
{
    /**
     * Obsługa żądania – wyrzuca użytkownika, jeśli jego konto jest nieaktywne.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Sprawdzamy, czy użytkownik jest zalogowany i czy jego konto jest NIEAKTYWNE
        if (Auth::check() && !Auth::user()->is_active) {
            
            // Wylogowujemy użytkownika
            Auth::logout();

            // Bezpiecznie czyścimy sesję i regenerujemy token CSRF
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Przekierowujemy na stronę logowania z błędem
            return redirect()->route('login')->withErrors([
                'email' => 'Twoje konto zostało zablokowane przez moderatora.',
            ]);
        }

        return $next($request);
    }
}