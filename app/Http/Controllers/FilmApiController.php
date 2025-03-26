<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class FilmApiController extends Controller
{
    private $baseUrl = 'http://localhost:8080/toad/film';

    public function addFilm(Request $request)
    {
        $response = Http::post("{$this->baseUrl}/add", [
            'title' => $request->title,
            'description' => $request->description,
            'releaseYear' => $request->releaseYear,
            'languageId' => $request->languageId,
            'originalLanguageId' => $request->originalLanguageId,
            'rentalDuration' => $request->rentalDuration,
            'rentalRate' => $request->rentalRate,
            'length' => $request->length,
            'replacementCost' => $request->replacementCost,
            'rating' => $request->rating,
            'lastUpdate' => $request->lastUpdate,
            'idDirector' => $request->idDirector,
        ]);

        return $response->json();
    }

    public function getFilmById($filmId)
{
    try {
        // Ajout de 'id' comme paramètre dans la requête GET
        $response = Http::get("http://localhost:8080/toad/film/getById", [
            'id' => $filmId
        ]);

        if ($response->successful()) {
            return $response->json();
        } else {
            return response()->json(['error' => 'Film non trouvé'], 404);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => 'Impossible de se connecter au backend'], 500);
    }
}

    public function getAllFilms(Request $request)
    {
       $films = Http::get("{$this->baseUrl}/all")->json();
      
       // Pagination manuelle (si nécessaire)
       $page = $request->get('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        $paginatedFilms = new LengthAwarePaginator(
        array_slice($films, $offset, $perPage),
        count($films),
        $perPage,
        $page,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    // Assurez-vous d'envoyer $paginatedFilms et non $films
    return view('films', ['films' => $paginatedFilms]);
}

    

        public function updateFilm(Request $request, $filmId)
        {
    try {
        // Récupérer les données envoyées par le formulaire
        $data = $request->only([
            'title', 'description', 'releaseYear', 'languageId', 'originalLanguageId',
            'rentalDuration', 'rentalRate', 'length', 'replacementCost', 'rating',
            'specialFeatures', 'lastUpdate', 'idDirector'
        ]);
        //dd($request->all());
        // Correction du format de la date pour 'lastUpdate'
        $data['lastUpdate'] = now()->format('Y-m-d H:i:s');
        $data['idDirector'] = $request->idDirector ?? null;
         // Format compatible avec Java
        //$data['releaseYear'] = (int) $data['releaseYear'];
        //$data['rentalRate'] = (float) $data['rentalRate'];
        //$data['lastUpdate'] = now()->format('Y-m-d H:i:s');
        if ($request->has('lastUpdate')) {
            $data['lastUpdate'] = \Carbon\Carbon::parse($request->input('lastUpdate'))->format('Y-m-d H:i:s');
        }
        

        // Appeler l'API de mise à jour
        $response = Http::put("http://localhost:8080/toad/film/update/{$filmId}", $data);

        // Vérifier si l'API a répondu avec succès
        if ($response->successful()) {
            return redirect('/toad/film/all')->with('success', 'Le film a été mis à jour avec succès.');
        } else {
            return redirect()->back()->withErrors(['error' => 'Erreur lors de la mise à jour. Code : ' . $response->status()]);
        }
    } catch (\Exception $e) {
        // Gérer les erreurs
        return redirect()->back()->withErrors(['error' => 'Erreur de connexion au backend : ' . $e->getMessage()]);
    }
}
    public function deleteFilm($id)
{
    try {
        // Envoi de la requête DELETE à l'API backend
        $response = Http::delete("http://localhost:8080/toad/film/delete/{$id}");

        // Vérification de la réponse du backend
        if ($response->successful()) {
            return redirect('/toad/film/all')->with('success', 'Le film a été supprimé avec succès.');
        } else {
            return redirect()->back()->withErrors(['error' => 'Erreur lors de la suppression du film. Code : ' . $response->status()]);
        }
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Erreur de connexion au backend : ' . $e->getMessage()]);
    }
}

    public function showFilms(Request $request)
    {
        try {
            // Récupération des données depuis le backend Spring Boot
            $response = Http::get('http://localhost:8080/toad/film/all');
            
            if ($response->successful()) {
                $films = $response->json(); // Décodage du JSON
    
                // Vérification du format des données
                if (is_array($films)) {
                    // Recherche stricte par mot-clé
                    $search = $request->input('search');
                    if ($search) {
                        // Crée une regex pour rechercher uniquement des mots entiers
                        $regex = '/\b' . preg_quote($search, '/') . '\b/i';
    
                        $films = array_filter($films, function ($film) use ($regex) {
                            return preg_match($regex, $film['title'] ?? '') ||
                                   preg_match($regex, $film['description'] ?? '');
                        });
                    }
    
                    return view('films', ['films' => $films]);
                } else {
                    $errorMessage = 'Format de données incorrect reçu du backend';
                    return view('films', ['films' => [], 'errorMessage' => $errorMessage]);
                }
            } else {
                $errorMessage = 'Erreur lors de la récupération des données du backend : ' . $response->status();
                return view('films', ['films' => [], 'errorMessage' => $errorMessage]);
            }
        } catch (\Exception $e) {
            $errorMessage = 'Impossible de se connecter au backend. Détails : ' . $e->getMessage();
            return view('films', ['films' => [], 'errorMessage' => $errorMessage]);
        }
    }
    
    
    public function editFilm($filmId)
{
    try {
        // Vérifier si l'ID est bien envoyé
       

        // Envoi de la requête GET avec le paramètre "id" correctement structuré
        $response = Http::get("http://localhost:8080/toad/film/getById", [
            'id' => (int) $filmId  // On force en entier au cas où
        ]);

        // Vérifier la réponse HTTP
        if ($response->successful()) {
            

            // Vérifier si la réponse est bien un film
            $film = $response->json();
            if (empty($film)) {
                return view('edit-film', ['film' => null, 'errorMessage' => "Film non trouvé"]);
            }
            return view('edit-film', ['film' => $film]);
        } else {
            $errorMessage = 'Erreur lors de la récupération des données du film : ' . $response->status();
            
            return view('edit-film', ['film' => null, 'errorMessage' => $errorMessage]);
        }
    } catch (\Exception $e) {
        $errorMessage = 'Impossible de se connecter au backend. Détails : ' . $e->getMessage();
        
        return view('edit-film', ['film' => null, 'errorMessage' => $errorMessage]);
    }
}

}
