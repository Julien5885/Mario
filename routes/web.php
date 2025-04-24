<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FilmApiController;
use App\Http\Controllers\UserApiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\DirectorController;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\FilmInventoryController;



// ----------------------------------------------------------------------
// 1. Page d'accueil : redirection vers la page de connexion personnalisée
// ----------------------------------------------------------------------
Route::get('/', function () {
    return view('auth.login_staff');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::prefix('/toad/film')->name('film.')->controller(FilmApiController::class)->group(function () {
    Route::get('/all', 'showFilms')->name('list');
    // ...
});

Route::post('/logout_staff', [ApiController::class, 'logout'])->name('logout_staff');

// ----------------------------------------------------------------------
// 2. Nouvelle route de connexion
// ----------------------------------------------------------------------
Route::post('/login_staff', [ApiController::class, 'login'])->name('login_staff');



    Route::prefix('/toad/film')->name('film.')->controller(FilmApiController::class)->group(function () {
    // Affichage de tous les films
    Route::get('/all', 'showFilms')->name('list');

    // Affichage du formulaire d'édition d’un film
    Route::get('/edit/{id}', 'editFilm')->name('edit');

    // Mise à jour d’un film (méthode PUT)
    Route::put('/update/{id}', 'updateFilm')->name('update');

    // Ajout d’un film
    Route::post('/add', 'addFilm')->name('add');

    // Suppression d’un film
    Route::delete('/delete/{id}', 'deleteFilm')->name('delete');
});


    Route::get('/inventory', [FilmInventoryController::class, 'index'])->name('inventory');
    Route::get('/inventory/create', [FilmInventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [FilmInventoryController::class, 'store'])->name('inventory.store');
    
/*
// ----------------------------------------------------------------------
// 3. (Optionnel) Anciennes routes protégées par "auth" et "verified"
//    --> On les commente ou on les supprime pour désactiver l'auth Laravel
// ----------------------------------------------------------------------
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/Mario', function () {
        return view('dashboard');
    })->name('dashboard');
});
*/

// ----------------------------------------------------------------------
// 4. Suppression ou adaptation des routes qui étaient protégées par "auth"
//    --> On les rend publiques (ou on les protège autrement) :
// ----------------------------------------------------------------------

/*
// Ancien middleware('auth') — on le commente pour enlever la protection Laravel
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
*/

// ----------------------------------------------------------------------
// 5. On rend ces routes publiques (si souhaité) en les sortant du middleware
// ----------------------------------------------------------------------

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

// ----------------------------------------------------------------------
// 6. Routes directeurs (exemples existants, conservés)
// ----------------------------------------------------------------------
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


// Contrôleur pour la recherche du réalisateur avec le nombre de films
Route::get('/api/directors/find-by-name-with-count', [DirectorController::class, 'findDirectorByNameWithFilmCount']);

Route::prefix('inventory')->group(function(){
    // listing existant
    Route::get('/', [FilmInventoryController::class, 'index'])->name('inventory');

    // création existante
    Route::get('/create', [FilmInventoryController::class, 'create'])->name('inventory.create');
    Route::post('/',     [FilmInventoryController::class, 'store'])->name('inventory.store');

    // **édition**
    Route::get('/{id}/edit',   [FilmInventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('/{id}',        [FilmInventoryController::class, 'update'])->name('inventory.update');

    // suppression (déjà en dur dans votre table)
    Route::delete('/{id}',     [FilmInventoryController::class, 'destroy'])->name('inventory.destroy');
});




