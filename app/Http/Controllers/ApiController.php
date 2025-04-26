<?php

namespace App\Http\Controllers;

// On importe la classe Request qui nous permet de récupérer les données envoyées par le formulaire
use Illuminate\Http\Request;

// Déclaration de la classe ApiController qui hérite de la classe Controller de Laravel
class ApiController extends Controller
{
    // Variable privée pour stocker l'URL de base de l'API
    private $baseUrl;

    // Constructeur appelé automatiquement lors de la création de l'objet
    public function __construct()
    {
        // On récupère l'URL de base et le port depuis les variables d'environnement (.env)
        $this->baseUrl = env('SERVEUR') . env('PORT');
    }

    // Méthode pour gérer la connexion d'un membre du staff
    public function login(Request $request)
    {
        // Création d'un nouveau client HTTP grâce à GuzzleHttp pour envoyer des requêtes API
        $client = new \GuzzleHttp\Client();

        // Validation des données envoyées par l'utilisateur
        // On s'assure que l'email et le mot de passe sont bien fournis et sous forme de chaîne
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // On récupère les données saisies dans le formulaire
        $email = $request->input('email');
        $password = $request->input('password');

        // Construction de l'URL de l'API pour rechercher un membre du staff par son email
        $apiUrl = $this->baseUrl . "/toad/staff/getByEmail?email=" . urlencode($email);

        try {
            // Envoi d'une requête GET à l'API
            $response = $client->request('GET', $apiUrl);

            // Décodage du corps de la réponse JSON en tableau PHP
            $staff = json_decode($response->getBody()->getContents(), true);

            // Vérification si aucun membre du staff n'a été trouvé
            if (!$staff) {
                // Redirection vers la page précédente avec un message d'erreur
                return back()->with('error', 'Utilisateur non trouvé.');
            }

            // Vérification si le mot de passe entré est incorrect
            if ($staff['pasword'] !== $password) { 
                // Redirection avec un message d'erreur si mot de passe incorrect
                return back()->with('error', 'Mot de passe incorrect.');
            }

            // Si tout est bon, on enregistre les informations du staff dans la session
            session([
                'staff_id' => $staff['staffId'],
                'first_name' => $staff['firstName'],
                'last_name' => $staff['lastName'],
                'email' => $staff['email'],
                'store_id' => $staff['storeId'],
                'role_id' => $staff['roleId'],
                'is_logged_in' => true,
            ]);

            // Redirection vers le tableau de bord avec un message de succès
            return redirect()->route('dashboard')->with('success', 'Connexion réussie.');

        } catch (\Exception $e) {
            // En cas d'erreur (ex : serveur indisponible), on retourne à la page précédente avec l'erreur affichée
            return back()->with('error', $e->getMessage());
        }
    }
}
