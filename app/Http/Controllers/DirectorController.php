<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class DirectorController extends Controller

    {
        public function getFilmCount()
        {
            $nom = 'Spielberg'; // Nom du réalisateur à rechercher
            $prenom = 'Steven'; // Prénom du réalisateur à rechercher
    
            $response = Http::get('http://localhost:8080/api/director/find-by-name-with-count', [
                'nom' => $nom,
                'prenom' => $prenom
            ]);
    
            if ($response->successful()) {
                $directors = $response->json();
            } else {
                $directors = [];
            }
    
            return view('film-count', compact('directors'));
        }
    }