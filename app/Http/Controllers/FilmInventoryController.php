<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class FilmInventoryController extends Controller
{
    public function index()
    {
        $client = new Client();

        // Récupérer la liste des inventaires depuis le backend
        $responseInventory = $client->get('http://localhost:8080/toad/inventory/all');
        $inventories = json_decode($responseInventory->getBody(), true);

        // Récupérer la liste des films depuis le backend
        $responseFilms = $client->get('http://localhost:8080/toad/film/all');
        $films = json_decode($responseFilms->getBody(), true);

        // Construire un mapping filmId => titre
        $filmMapping = [];
        foreach ($films as $film) {
            if (isset($film['filmId']) && isset($film['title'])) {
                $filmMapping[$film['filmId']] = $film['title'];
            }
        }

        // Retourner la vue avec les inventaires et le mapping
        return view('inventory', compact('inventories', 'filmMapping'));
    }

    public function create()
    {
        return view('inventory_create');
    }

    public function store(Request $request)
    {
        $client = new Client();

        // Préparation des données pour créer le film
        $filmData = [
            'title'             => $request->input('title'),
            'description'       => $request->input('description'),
            'releaseYear'       => $request->input('releaseYear'),
            'languageId'        => $request->input('languageId', 1),
            'originalLanguageId'=> $request->input('originalLanguageId', 1),
            'rentalDuration'    => $request->input('rentalDuration', 3),
            'rentalRate'        => $request->input('rentalRate', 4.99),
            'length'            => $request->input('length', 120),
            'replacementCost'   => $request->input('replacementCost', 19.99),
            'rating'            => $request->input('rating', 'G'),
            'lastUpdate'        => now()->toDateTimeString(),
        ];

        // Appel pour créer le film via Spring Boot
        $filmResponse = $client->post('http://localhost:8080/toad/film/add', [
            'form_params' => $filmData
        ]);

        $filmResponseData = json_decode($filmResponse->getBody(), true);
        $filmId = $filmResponseData['filmId'] ?? null;

        if (!$filmId) {
            return redirect()->back()->with('error', 'Erreur lors de la création du film.');
        }

        // Préparation des données pour créer l'inventaire (exemplaire)
        $inventoryData = [
            'film_id'    => $filmId,
            'store_id'   => $request->input('store_id'),
            'last_update'=> now()->toDateTimeString(),
        ];

        $inventoryResponse = $client->post('http://localhost:8080/toad/inventory/add', [
            'form_params' => $inventoryData
        ]);

        $inventoryResponseData = json_decode($inventoryResponse->getBody(), true);

        return redirect()->route('inventory')->with('success', 'Film et inventaire créés avec succès.');
    }
    public function edit($id)
    {
        $client = new Client();

        // 1) Récupérer l'inventaire à modifier
        $resp = $client->get('http://localhost:8080/toad/inventory/getById', [
            'query' => ['id' => $id]
        ]);
        $inventory = json_decode($resp->getBody(), true);

        // 2) Récupérer la liste des films pour le select
        $filmsResp = $client->get('http://localhost:8080/toad/film/all');
        $films = json_decode($filmsResp->getBody(), true);
        $filmMapping = [];
        foreach ($films as $f) {
            $filmMapping[$f['filmId']] = $f['title'];
        }

        return view('inventory_update', compact('inventory', 'filmMapping'));
    }

    /**
     * Reçoit la soumission du formulaire d'édition
     */
    public function update(Request $request, $id)
    {
        $client = new Client();

        // préparer les données à envoyer au backend SpringBoot
        $payload = [
            'film_id'     => $request->input('film_id'),
            'store_id'    => $request->input('store_id'),
            'last_update' => $request->input('last_update'),
            'existe'      => $request->has('existe') ? true : false,
        ];

        // appel PUT pour modifier l'inventaire
        $client->put("http://localhost:8080/toad/inventory/update/{$id}", [
            'form_params' => $payload
        ]);

        return redirect()->route('inventory')
                         ->with('success', 'Inventaire mis à jour.');
    }

    /**
     * (Optionnel) si vous voulez un destroy() pour supprimer via méthode DELETE
     */
    public function destroy($id)
    {
        $client = new Client();
        $client->delete("http://localhost:8080/toad/inventory/delete/{$id}");
        return redirect()->route('inventory')
                         ->with('success', 'Inventaire supprimé.');
    }
}