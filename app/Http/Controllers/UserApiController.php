<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class UserApiController extends Controller
{
    /**
     * Récupérer les utilisateurs bloqués (failed_attempts >= 3)
     */
    public function getBlockedUsers()
    {
        // Appel de l'API backend pour récupérer les utilisateurs bloqués
        $response = Http::get('http://localhost:8080/toad/user/failed-attempts', ['attempts' => 3]);

        if ($response->successful()) {
            $users = $response->json();
            return view('blocked-users', compact('users'));
        }

        // Gestion des erreurs si l'appel échoue
        return view('blocked-users', [
            'users' => [],
            'error' => 'Impossible de récupérer les utilisateurs bloqués. Veuillez vérifier l\'API backend.'
        ]);
    }

    /**
     * Débloquer un utilisateur
     */
    public function unlockUser($id)
{
    $response = Http::post("http://localhost:8080/toad/user/unlock/$id");

    if ($response->successful()) {
        return redirect()->route('users.blocked')->with('success', 'Utilisateur débloqué avec succès.');
    } else {
        dd($response->body()); // Debug : affiche le message d'erreur de l'API backend
        return redirect()->route('users.blocked')->with('error', 'Erreur lors du déblocage de l\'utilisateur.');
    }
}
}
