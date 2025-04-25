<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class FilmApiController extends Controller
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('SERVEUR') . env('PORT') . '/toad/film';
    }

    public function showFilms(Request $request)
    {
        try {
            $response = Http::get($this->baseUrl . '/all');

            if ($response->successful()) {
                $films = $response->json();

                $search = $request->input('search');
                if ($search) {
                    $films = array_filter($films, function ($film) use ($search) {
                        return stripos($film['title'], $search) !== false ||
                               stripos($film['description'], $search) !== false;
                    });
                }

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

                return view('films', ['films' => $paginatedFilms]);
            } else {
                return view('films', ['films' => collect(), 'errorMessage' => 'Erreur : ' . $response->status()]);
            }
        } catch (\Exception $e) {
            return view('films', ['films' => collect(), 'errorMessage' => $e->getMessage()]);
        }
    }

    public function editFilm($filmId)
    {
        try {
            $response = Http::get($this->baseUrl . "/getById", ['id' => (int) $filmId]);

            if ($response->successful()) {
                $film = $response->json();

                if (empty($film)) {
                    return view('edit-film', ['film' => null, 'errorMessage' => "Film non trouvé"]);
                }

                return view('edit-film', ['film' => $film]);
            } else {
                return view('edit-film', ['film' => null, 'errorMessage' => 'Erreur : ' . $response->status()]);
            }
        } catch (\Exception $e) {
            return view('edit-film', ['film' => null, 'errorMessage' => $e->getMessage()]);
        }
    }

    public function updateFilm(Request $request, $filmId)
    {
        try {
            $data = $request->only([
                'title', 'description', 'releaseYear', 'languageId', 'originalLanguageId',
                'rentalDuration', 'rentalRate', 'length', 'replacementCost', 'rating', 'lastUpdate'
            ]);

            $data['releaseYear'] = (int) ($data['releaseYear'] ?? 0);
            $data['languageId'] = (int) ($data['languageId'] ?? 0);
            $data['originalLanguageId'] = (int) ($data['originalLanguageId'] ?? 0);
            $data['rentalDuration'] = (int) ($data['rentalDuration'] ?? 0);
            $data['rentalRate'] = (float) ($data['rentalRate'] ?? 0);
            $data['replacementCost'] = (float) ($data['replacementCost'] ?? 0);
            $data['length'] = (int) ($data['length'] ?? 0);

            $data['lastUpdate'] = !empty($data['lastUpdate'])
                ? Carbon::parse(str_replace('T', ' ', $data['lastUpdate']))->format('Y-m-d H:i:s')
                : now()->format('Y-m-d H:i:s');

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

    public function addFilm(Request $request)
    {
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

    public function deleteFilm($id)
    {
        try {
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
