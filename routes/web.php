<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FilmApiController;
use App\Http\Controllers\UserApiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\DirectorController;
use Illuminate\Http\Request;

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Dashboard protégé par "auth" et "verified"
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/Mario', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Routes protégées par "auth" uniquement
Route::middleware('auth')->group(function () {
    // Gestion des utilisateurs
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // Utilisateurs bloqués
    Route::controller(UserApiController::class)->group(function () {
        Route::get('/users/blocked', 'getBlockedUsers')->name('users.blocked');
        Route::post('/users/unlock/{id}', 'unlockUser')->name('users.unlock');
    });

    // Gestion des films
    Route::prefix('/toad/film')->name('film.')->controller(FilmApiController::class)->group(function () {
        Route::post('/add', 'addFilm')->name('add');
        Route::get('/getById', 'getFilmById')->name('getById');
        Route::get('/all', 'showFilms')->name('list');
        Route::get('/edit/{id}', 'editFilm')->name('edit');
        Route::put('/update/{id}', 'updateFilm')->name('update');
        Route::delete('/delete/{id}', 'deleteFilm')->name('delete');
    });
});

// Route pour récupérer le nombre de films réalisés par un réalisateur
Route::get('/directors/film-count', function (Request $request) {
    try {
        $nom = $request->query('nom');
        $prenom = $request->query('prenom');

        if (!$nom || !$prenom) {
            return response()->json(['error' => 'Paramètres "nom" et "prenom" requis'], 400);
        }

        $response = Http::get('http://localhost:8080/api/directors/film-count', [
            'nom' => $nom,
            'prenom' => $prenom,
        ]);

        if ($response->successful()) {
            return $response->json();
        } else {
            return response()->json([
                'error' => 'Erreur lors de la récupération des données de l\'API',
                'details' => $response->json(),
            ], $response->status());
        }
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});
Route::get('/director/find-by-name', function (Request $request) {
    $nom = $request->query('nom');
    $prenom = $request->query('prenom');

    if (!$nom || !$prenom) {
        return response()->json(['error' => 'Paramètres "nom" et "prenom" requis'], 400);
    }

    $response = Http::get('http://localhost:8080/api/director/find-by-name', [
        'nom' => $nom,
        'prenom' => $prenom,
    ]);

    if ($response->successful()) {
        return $response->json();
    } else {
        return response()->json([
            'error' => 'Erreur lors de la récupération des données de l\'API',
            'details' => $response->body(),
        ], $response->status());
    }
});
    Route::get('/api/directors/find-by-name-with-count', [DirectorController::class, 'findDirectorByNameWithFilmCount']);



// Inclusion des routes d'authentification
require __DIR__.'/auth.php';
