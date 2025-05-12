<?php

namespace App\Http\Controllers;

// Importation des classes nécessaires
use Illuminate\Http\Request;
use GuzzleHttp\Client;

// Déclaration du contrôleur FilmInventoryController
class FilmInventoryController extends Controller
{
    // URL de base pour l'API
    private $baseUrl;

    // Constructeur du contrôleur
    public function __construct()
    {
        // Initialisation de l'URL de base depuis les variables d'environnement
        $this->baseUrl = env('SERVEUR') . env('PORT');
    }

    // Méthode pour afficher la liste de tous les inventaires
    public function index()
    {
        $client = new Client();

        // 1) On récupère le stock groupé (titre, qty, adresse, district…)
        $resp = $client->get($this->baseUrl . '/toad/inventory/getStockByStore');
        $inventories = json_decode($resp->getBody()->getContents(), true);

        // 2) Pour chaque film, on récupère un ID d’inventaire libre
        foreach ($inventories as &$inv) {
            $availResp = $client->get($this->baseUrl . '/toad/inventory/available/getById', [
                'query' => ['id' => $inv['filmId']],
            ]);

            $body   = trim($availResp->getBody()->getContents());
            $freeId = is_numeric($body) ? (int) $body : null;

            $inv['inventoryId'] = $freeId;
            $inv['available']   = $freeId !== null;
        }
        unset($inv);

        return view('inventory', compact('inventories'));
    }


    // Méthode pour afficher le formulaire de création d'un nouvel inventaire
    public function create()
    {
        return view('inventory_create');
    }

    // Méthode pour enregistrer un nouvel inventaire
    public function store(Request $request)
    {
        $client = new Client();

        // Préparation des données pour créer un nouveau film
        $filmData = [
            'title'              => $request->input('title'),
            'description'        => $request->input('description'),
            'releaseYear'        => $request->input('releaseYear'),
            'languageId'         => $request->input('languageId', 1),
            'originalLanguageId' => $request->input('originalLanguageId', 1),
            'rentalDuration'     => $request->input('rentalDuration', 3),
            'rentalRate'         => $request->input('rentalRate', 4.99),
            'length'             => $request->input('length', 120),
            'replacementCost'    => $request->input('replacementCost', 19.99),
            'rating'             => $request->input('rating', 'G'),
            'lastUpdate'         => now()->toDateTimeString(),
        ];

        // Requête pour ajouter le film
        $filmResponse = $client->post($this->baseUrl . '/toad/film/add', [
            'form_params' => $filmData
        ]);

        // On récupère la réponse de l'API
        $filmResponseData = json_decode($filmResponse->getBody(), true);
        $filmId = $filmResponseData['filmId'] ?? null;

        // Vérification de la création du film
        if (!$filmId) {
            return redirect()->back()->with('error', 'Erreur lors de la création du film.');
        }

        // Préparation des données pour créer un nouvel inventaire lié au film
        $inventoryData = [
            'film_id'     => $filmId,
            'store_id'    => $request->input('store_id'),
            'last_update' => now()->toDateTimeString(),
            'existe'      => true, // Par défaut l'inventaire est disponible
        ];

        // Requête pour ajouter l'inventaire
        $client->post($this->baseUrl . '/toad/inventory/add', [
            'form_params' => $inventoryData
        ]);

        return redirect()->route('inventory')->with('success', 'Film et inventaire créés avec succès.');
    }

    // Méthode pour afficher le formulaire d'édition d'un inventaire existant
    public function edit($id)
    {
        $client = new Client();

        // Récupération des détails de l'inventaire par son ID
        $resp = $client->get($this->baseUrl . '/toad/inventory/getById', [
            'query' => ['id' => $id]
        ]);
        $inventory = json_decode($resp->getBody(), true);

        // Récupération de tous les films pour pouvoir changer le film associé
        $filmsResp = $client->get($this->baseUrl . '/toad/film/all');
        $films = json_decode($filmsResp->getBody(), true);

        // Construction d'un tableau filmId => titre
        $filmMapping = [];
        foreach ($films as $f) {
            $filmMapping[$f['filmId']] = $f['title'];
        }

        // Affichage de la vue "inventory_update" avec l'inventaire et les films disponibles
        return view('inventory_update', compact('inventory', 'filmMapping'));
    }

    // Méthode pour mettre à jour un inventaire existant
    public function update(Request $request, $id)
    {
        $client = new Client();

        // Formatage correct de la date de dernière mise à jour
        $lastUpdateInput = $request->input('last_update');
        if ($lastUpdateInput) {
            $lastUpdate = str_replace('T', ' ', $lastUpdateInput) . ':00.0';
        } else {
            $lastUpdate = now()->format('Y-m-d H:i:s') . '.0';
        }

        // Préparation des données pour la mise à jour
        $payload = [
            'film_id'     => (int) $request->input('film_id'),
            'store_id'    => (int) $request->input('store_id'),
            'last_update' => $lastUpdate,
            'existe'      => $request->has('existe') ? true : false,
        ];

        // Requête pour mettre à jour l'inventaire
        $client->put($this->baseUrl . "/toad/inventory/update/{$id}", [
            'form_params' => $payload,
        ]);

        return redirect()->route('inventory')->with('success', 'Inventaire mis à jour.');
    }

    // Méthode pour supprimer un inventaire
    public function destroy($id)
    {
        $client = new Client();

        // Requête DELETE pour supprimer un inventaire par ID
        $client->delete($this->baseUrl . "/toad/inventory/delete/{$id}");

        return redirect()->route('inventory')->with('success', 'Inventaire supprimé.');
    }
}
