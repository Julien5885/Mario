<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Mail\UnblockMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;



class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
 // Afficher la liste des utilisateurs bloqués
 public function showBlockedUsers()
 {
     $blockedUsers = User::where('compte_bloque', 1)->get();
     return view('blocked-users', compact('blockedUsers'));
 }

 // Débloquer un utilisateur et envoyer un email
 public function unblockUser($id)
 {
    $user = User::find($id);
    if ($user) {
        $user->compte_bloque = 0;
        $user->failed_attempts = 0;
        $user->save();

        // Envoyer un e-mail de déblocage
        Mail::to($user->email)->send(new UnblockMail($user));
    }

    return back()->with('success', 'Utilisateur débloqué avec succès.');
 }
}

