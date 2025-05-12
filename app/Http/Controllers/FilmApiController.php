<?php

namespace App\Http\Controllers;

// Importation des classes nécessaires
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Pour envoyer des requêtes HTTP facilement
use Illuminate\Pagination\LengthAwarePaginator; // Pour paginer manuellement
use Carbon\Carbon; // Pour manipuler les dates

// Déclaration du contrôleur
class FilmApiController extends Controller
{
    // Variable pour stocker l'URL de base de l'API
    private $baseUrl;

    // Constructeur du contrôleur
    public function __construct()
    {
        // On construit l'URL de base à partir des variables d'environnement
        $this->baseUrl = env('SERVEUR') . env('PORT') . '/toad/film';
    }

    // Fonction pour afficher la liste des films
    public function showFilms(Request $request)
    {
        try {
            // Requête GET vers l'API pour récupérer tous les films
            $response = Http::get($this->baseUrl . '/all');

            if ($response->successful()) {
                $films = $response->json();

                // Si un mot-clé de recherche est fourni, on filtre les films
                $search = $request->input('search');
                if ($search) {
                    $films = array_filter($films, function ($film) use ($search) {
                        return stripos($film['title'], $search) !== false ||
                               stripos($film['description'], $search) !== false;
                    });
                }

                // Mise en place de la pagination manuelle
                $currentPage = LengthAwarePaginator::resolveCurrentPage();
                $perPage = 10;
                $currentItems = array_slice($films, ($currentPage - 1) * $perPage, $perPage);

                $paginatedFilms = new LengthAwarePaginator(
                    $currentItems,
                    count($films),
                    $perPage,
                    $currentPage,
                    ['path' => LengthAwarePaginator::resolveCurrentPath()]
                );

                // Affichage de la vue 'films' avec les films paginés
                return view('films', ['films' => $paginatedFilms]);
            } else {
                // En cas d'erreur de réponse API
                return view('films', ['films' => collect(), 'errorMessage' => 'Erreur : ' . $response->status()]);
            }
        } catch (\Exception $e) {
            // En cas d'exception (erreur serveur, connexion, etc.)
            return view('films', ['films' => collect(), 'errorMessage' => $e->getMessage()]);
        }
    }

    // Fonction pour afficher le formulaire d'édition d'un film
    public function editFilm($filmId)
    {
        try {
            // Requête pour récupérer les informations du film à modifier
            $response = Http::get($this->baseUrl . "/getById", ['id' => (int) $filmId]);

            if ($response->successful()) {
                $film = $response->json();

                if (empty($film)) {
                    // Si le film n'est pas trouvé
                    return view('edit-film', ['film' => null, 'errorMessage' => "Film non trouvé"]);
                }

                // Sinon, afficher les informations du film
                return view('edit-film', ['film' => $film]);
            } else {
                return view('edit-film', ['film' => null, 'errorMessage' => 'Erreur : ' . $response->status()]);
            }
        } catch (\Exception $e) {
            return view('edit-film', ['film' => null, 'errorMessage' => $e->getMessage()]);
        }
    }

    // Fonction pour mettre à jour les informations d'un film
    public function updateFilm(Request $request, $filmId)
    {
        try {
            // On récupère uniquement les champs utiles
            $data = $request->only([
                'title', 'description', 'releaseYear', 'languageId', 'originalLanguageId',
                'rentalDuration', 'rentalRate', 'length', 'replacementCost', 'rating', 'lastUpdate'
            ]);

            // Conversion des champs aux bons formats
            $data['releaseYear'] = (int) ($data['releaseYear'] ?? 0);
            $data['languageId'] = (int) ($data['languageId'] ?? 0);
            $data['originalLanguageId'] = (int) ($data['originalLanguageId'] ?? 0);
            $data['rentalDuration'] = (int) ($data['rentalDuration'] ?? 0);
            $data['rentalRate'] = (float) ($data['rentalRate'] ?? 0);
            $data['replacementCost'] = (float) ($data['replacementCost'] ?? 0);
            $data['length'] = (int) ($data['length'] ?? 0);

            // Formatage de la date de dernière mise à jour
            $data['lastUpdate'] = !empty($data['lastUpdate'])
                ? Carbon::parse(str_replace('T', ' ', $data['lastUpdate']))->format('Y-m-d H:i:s')
                : now()->format('Y-m-d H:i:s');

            // Requête PUT pour envoyer les modifications à l'API
            $response = Http::asForm()->put($this->baseUrl . "/update/{$filmId}", $data);

            if ($response->successful()) {
                return redirect('/toad/film/all')->with('success', 'Le film a été mis à jour avec succès.');
            } else {
                return redirect()->back()->withErrors(['error' => 'Erreur : ' . $response->status() . ' - ' . $response->body()]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // Fonction pour ajouter un nouveau film
    public function addFilm(Request $request)
    {
        // Requête POST pour créer un film
        $response = Http::asForm()->post($this->baseUrl . '/add', [
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

    // Fonction pour supprimer un film
    public function deleteFilm($id)
    {
        try {
            // Requête DELETE pour supprimer un film
            $response = Http::delete($this->baseUrl . "/delete/{$id}");

            if ($response->successful()) {
                return redirect('/toad/film/all')->with('success', 'Film supprimé avec succès.');
            } else {
                return redirect()->back()->withErrors(['error' => 'Erreur : ' . $response->status()]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
