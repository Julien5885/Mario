<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $user = User::where('email', $request->email)->first();

        // Vérifier si l'utilisateur existe et si le compte est bloqué
        if ($user && $user->compte_bloque) {
            return back()->withErrors([
                'email' => 'Votre compte est bloqué. Veuillez contacter l\'administrateur.',
            ]);
        }

        // Vérifier l'authentification
        if (!Auth::attempt($request->only('email', 'password'))) {
            // Incrémentation du compteur de tentatives
            if ($user) {
                $user->increment('failed_attempts');
                if ($user->failed_attempts >= 3) {
                    // Bloquer le compte
                    $user->compte_bloque = true;
                    $user->save();
                    return back()->withErrors([
                        'email' => 'Votre compte a été bloqué après 3 tentatives échouées.',
                    ]);
                }
            }
            return back()->withErrors([
                'email' => 'Les informations d\'identification ne sont pas correctes.',
            ]);
        }

        // Réinitialiser le compteur de tentatives en cas de réussite
        if ($user) {
            $user->failed_attempts = 0;
            $user->save();
        }

        // Régénérer la session
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
