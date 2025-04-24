<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class FilmApiController extends Controller
{
    private $baseUrl = 'http://localhost:8080/toad/film';

    public function showFilms(Request $request)
    {
        try {
            $response = Http::get("{$this->baseUrl}/all");
            if ($response->successful()) {
                $films = $response->json(); // tableau des films
    
                // Appliquer le filtre de recherche sur l'ensemble des films
                $search = $request->input('search');
                if ($search) {
                    $films = array_filter($films, function($film) use ($search) {
                        return stripos($film['title'], $search) !== false ||
                               stripos($film['description'], $search) !== false;
                    });
                }
    
                // Pagination manuelle sur le tableau filtré
                $currentPage = LengthAwarePaginator::resolveCurrentPage();
                $perPage = 10; // films par page (à ajuster)
                $currentItems = array_slice($films, ($currentPage - 1) * $perPage, $perPage);
                $paginatedFilms = new LengthAwarePaginator(
                    $currentItems,
                    count($films),
                    $perPage,
                    $currentPage,
                    ['path' => LengthAwarePaginator::resolveCurrentPath()]
                );
    
                return view('films', ['films' => $paginatedFilms]);
            } else {
                $errorMessage = 'Erreur lors de la récupération des données : ' . $response->status();
                return view('films', ['films' => collect(), 'errorMessage' => $errorMessage]);
            }
        } catch (\Exception $e) {
            $errorMessage = 'Impossible de se connecter au backend. Détails : ' . $e->getMessage();
            return view('films', ['films' => collect(), 'errorMessage' => $errorMessage]);
        }
    }
    

    /**
     * 2) Afficher le formulaire d'édition pour un film
     */
    public function editFilm($filmId)
    {
        try {
            // Récupérer le film par ID via le paramètre "id" attendu par le backend
            $response = Http::get("{$this->baseUrl}/getById", [
                'id' => (int) $filmId
            ]);

            if ($response->successful()) {
                $film = $response->json();
                if (empty($film)) {
                    return view('edit-film', [
                        'film' => null,
                        'errorMessage' => "Film non trouvé"
                    ]);
                }
                return view('edit-film', ['film' => $film]);
            } else {
                $errorMessage = 'Erreur lors de la récupération du film : ' . $response->status();
                return view('edit-film', ['film' => null, 'errorMessage' => $errorMessage]);
            }
        } catch (\Exception $e) {
            $errorMessage = 'Impossible de se connecter au backend. Détails : ' . $e->getMessage();
            return view('edit-film', ['film' => null, 'errorMessage' => $errorMessage]);
        }
    }

    /**
     * 3) Mettre à jour un film
     * Le backend attend @RequestParam :
     *   title, description, releaseYear, languageId, originalLanguageId,
     *   rentalDuration, rentalRate, length, replacementCost, rating,
     *   lastUpdate
     */
    public function updateFilm(Request $request, $filmId)
    {
        try {
            // On récupère seulement les champs que le backend attend
            $data = $request->only([
                'title', 'description', 'releaseYear', 'languageId', 'originalLanguageId',
                'rentalDuration', 'rentalRate', 'length', 'replacementCost', 'rating', 'lastUpdate'
            ]);

            // Convertir releaseYear, languageId, originalLanguageId, rentalDuration en int
            $data['releaseYear'] = (int) ($data['releaseYear'] ?? 0);
            $data['languageId'] = (int) ($data['languageId'] ?? 0);
            $data['originalLanguageId'] = (int) ($data['originalLanguageId'] ?? 0);
            $data['rentalDuration'] = (int) ($data['rentalDuration'] ?? 0);

            // Convertir rentalRate, replacementCost en float
            $data['rentalRate'] = (float) ($data['rentalRate'] ?? 0);
            $data['replacementCost'] = (float) ($data['replacementCost'] ?? 0);

            // length en int
            $data['length'] = (int) ($data['length'] ?? 0);

            // Gérer le champ lastUpdate (datetime-local => "2023-03-25T14:30" => "2023-03-25 14:30:00")
            if (!empty($data['lastUpdate'])) {
                $data['lastUpdate'] = str_replace('T', ' ', $data['lastUpdate']);
                $data['lastUpdate'] = Carbon::parse($data['lastUpdate'])->format('Y-m-d H:i:s');
            } else {
                // Si aucune date envoyée, on met la date du jour ou on peut laisser le backend gérer
                $data['lastUpdate'] = now()->format('Y-m-d H:i:s');
            }

            // On envoie la requête PUT en format x-www-form-urlencoded
            $response = Http::asForm()->put("{$this->baseUrl}/update/{$filmId}", $data);

            if ($response->successful()) {
                // Le backend renvoie "Film Mis à jour" si tout va bien
                return redirect('/toad/film/all')->with('success', 'Le film a été mis à jour avec succès.');
            } else {
                // Afficher le code et le body de la réponse pour aider au débogage
                return redirect()->back()->withErrors([
                    'error' => 'Erreur lors de la mise à jour. Code : ' 
                               . $response->status() . ' - ' . $response->body()
                ]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Erreur de connexion au backend : ' . $e->getMessage()
            ]);
        }
    }

    /**
     * 4) Ajouter un film (exemple)
     */
    public function addFilm(Request $request)
    {
        $response = Http::asForm()->post("{$this->baseUrl}/add", [
            'title' => $request->title,
            'description' => $request->description,
            'releaseYear' => (int) $request->releaseYear,
            'languageId' => (int) $request->languageId,
            'originalLanguageId' => (int) $request->originalLanguageId,
            'rentalDuration' => (int) $request->rentalDuration,
            'rentalRate' => (float) $request->rentalRate,
            'length' => (int) $request->length,
            'replacementCost' => (float) $request->replacementCost,
            'rating' => $request->rating,
            'lastUpdate' => now()->format('Y-m-d H:i:s'),
        ]);

        return $response->json();
    }

    /**
     * 5) Supprimer un film (exemple)
     */
    public function deleteFilm($id)
    {
        try {
            $response = Http::delete("{$this->baseUrl}/delete/{$id}");
            if ($response->successful()) {
                return redirect('/toad/film/all')->with('success', 'Film supprimé avec succès.');
            } else {
                return redirect()->back()->withErrors([
                    'error' => 'Erreur lors de la suppression. Code : ' . $response->status()
                ]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Erreur de connexion au backend : ' . $e->getMessage()
            ]);
        }
    }
}
