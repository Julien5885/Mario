<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FilmApiController extends Controller
{
    public function showFilms(Request $request)
    { $films = [
        [
            'filmId' => 1,
            'title' => 'Test Film',
            'description' => 'Description du film',
            'releaseYear' => 2024,
            'languageId' => 1,
            'originalLanguageId' => 1,
            'rentalDuration' => 5,
            'rentalRate' => 10.5,
            'length' => 120,
            'replacementCost' => 20.5,
            'rating' => 'PG',
            'specialFeatures' => 'Deleted Scenes',
            'lastUpdate' => '2024-11-21',
            'idDirector' => 2,
        ]
    ];
        // Récupérer le mot-clé de recherche
        $search = $request->input('search');

        // Si un terme est fourni, filtrer le tableau des films
        if ($search) {
            $films = array_filter($films, function($film) use ($search) {
                return stripos($film['title'], $search) !== false ||
                       stripos($film['description'], $search) !== false;
            });
            
        }
    
    return view('films', ['films' => $films]);
    }
    public function editFilm($filmId)
{
    try {
        $response = Http::get("http://localhost:8080/toad/film/getById", ['id' => $filmId]);

        if ($response->successful()) {
            $film = $response->json();

            // Vérification si l'ID du film est bien présent
            if (!isset($film['film_id'])) {
                return view('edit-film', ['film' => null, 'errorMessage' => 'Film non trouvé.']);
            }

            return view('edit-film', ['film' => $film]);
        } else {
            return view('edit-film', ['film' => null, 'errorMessage' => 'Film non trouvé.']);
        }
    } catch (\Exception $e) {
        return view('edit-film', ['film' => null, 'errorMessage' => 'Erreur de connexion au backend.']);
    }
}
public function updateFilm(Request $request, $filmId)
{
    try {
        
        $data = $request->all();
        $data['lastUpdate'] = now()->format('Y-m-d H:i:s');
        dd($data);

        // Envoi des données sous forme de `x-www-form-urlencoded`
        $response = Http::asForm()->put("http://localhost:8080/toad/film/update/{$filmId}", $data);

        if ($response->successful()) {
            return redirect('/toad/film/all')->with('success', 'Le film a été mis à jour avec succès.');
        } else {
            return redirect()->back()->withErrors(['error' => 'Erreur lors de la mise à jour.']);
        }
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Erreur de connexion au backend.']);
    }
}


}
