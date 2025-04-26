<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

// Import des contrôleurs
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserApiController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\FilmApiController;
use App\Http\Controllers\FilmInventoryController;
use App\Http\Controllers\DirectorController;

/*
|--------------------------------------------------------------------------
| Routes principales de l'application
|--------------------------------------------------------------------------
*/

// -------------------------------------------------------------
// 1. Page d'accueil : redirection vers page de connexion Staff
// -------------------------------------------------------------
Route::get('/', function () {
    return view('auth.login_staff');
});

// Dashboard après connexion
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// -------------------------------------------------------------
// 2. Connexion / Déconnexion pour Staff
// -------------------------------------------------------------
Route::post('/login_staff', [ApiController::class, 'login'])->name('login_staff');
Route::post('/logout_staff', [ApiController::class, 'logout'])->name('logout_staff');

// -------------------------------------------------------------
// 3. Gestion des Films (FilmApiController)
// -------------------------------------------------------------
Route::prefix('/toad/film')->name('film.')->controller(FilmApiController::class)->group(function () {
    Route::get('/all', 'showFilms')->name('list');
    Route::get('/edit/{id}', 'editFilm')->name('edit');
    Route::put('/update/{id}', 'updateFilm')->name('update');
    Route::post('/add', 'addFilm')->name('add');
    Route::delete('/delete/{id}', 'deleteFilm')->name('delete');
    Route::get('/getById', 'getFilmById')->name('getById'); // Optionnel
});

// -------------------------------------------------------------
// 4. Gestion de l'Inventaire (FilmInventoryController)
// -------------------------------------------------------------
Route::prefix('/inventory')->controller(FilmInventoryController::class)->group(function () {
    Route::get('/', 'index')->name('inventory');
    Route::get('/create', 'create')->name('inventory.create');
    Route::post('/', 'store')->name('inventory.store');
    Route::get('/{id}/edit', 'edit')->name('inventory.edit');
    Route::put('/{id}', 'update')->name('inventory.update');
    Route::delete('/{id}', 'destroy')->name('inventory.destroy');
});

// -------------------------------------------------------------
// 5. Gestion du Profil Utilisateur (ProfileController)
// -------------------------------------------------------------
Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'edit')->name('profile.edit');
    Route::patch('/profile', 'update')->name('profile.update');
    Route::delete('/profile', 'destroy')->name('profile.destroy');
});

// -------------------------------------------------------------
// 6. Gestion des Utilisateurs Bloqués (UserApiController)
// -------------------------------------------------------------
Route::controller(UserApiController::class)->group(function () {
    Route::get('/users/blocked', 'getBlockedUsers')->name('users.blocked');
    Route::post('/users/unlock/{id}', 'unlockUser')->name('users.unlock');
});

// -------------------------------------------------------------
// 7. Gestion des Réalisateurs (via API externe et Controller)
// -------------------------------------------------------------

// Variables serveur API
$serverUrl = env('SERVEUR') . env('PORT');

// Rechercher nombre de films par réalisateur (appel externe)
Route::get('/directors/film-count', function (Request $request) use ($serverUrl) {
    try {
        $nom = $request->query('nom');
        $prenom = $request->query('prenom');

        if (!$nom || !$prenom) {
            return response()->json(['error' => 'Paramètres "nom" et "prenom" requis'], 400);
        }

        $response = Http::get($serverUrl . '/api/directors/film-count', [
            'nom' => $nom,
            'prenom' => $prenom,
        ]);

        return $response->successful()
            ? $response->json()
            : response()->json(['error' => 'Erreur API', 'details' => $response->json()], $response->status());

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

// Rechercher un réalisateur par nom (appel externe)
Route::get('/director/find-by-name', function (Request $request) use ($serverUrl) {
    $nom = $request->query('nom');
    $prenom = $request->query('prenom');

    if (!$nom || !$prenom) {
        return response()->json(['error' => 'Paramètres "nom" et "prenom" requis'], 400);
    }

    $response = Http::get($serverUrl . '/api/director/find-by-name', [
        'nom' => $nom,
        'prenom' => $prenom,
    ]);

    return $response->successful()
        ? $response->json()
        : response()->json(['error' => 'Erreur API', 'details' => $response->body()], $response->status());
});

// Utiliser ton propre Controller interne (DirectorController)
Route::get('/api/directors/find-by-name-with-count', [DirectorController::class, 'findDirectorByNameWithFilmCount']);
