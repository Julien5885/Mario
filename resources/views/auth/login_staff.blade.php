<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion</title>

  <!-- Importation de TailwindCSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Style personnalisé pour le bouton de connexion -->
  <style>
    .btn-custom {
      width: 100%; /* Le bouton prend toute la largeur */
      padding-top: 0.75rem; /* Espace intérieur haut (py-3) */
      padding-bottom: 0.75rem; /* Espace intérieur bas */
      padding-left: 1rem; /* Espace intérieur gauche (px-4) */
      padding-right: 1rem; /* Espace intérieur droite */
      border-radius: 9999px; /* Bords totalement arrondis */
      font-weight: bold; /* Texte en gras */
      background: linear-gradient(135deg, rgb(79, 131, 199), rgb(63, 34, 209)); /* Dégradé bleu-violet */
      border: 4px solid rgb(255, 252, 252); /* Bordure blanche */
      color: white; /* Texte blanc */
      transition: all 0.3s ease; /* Transition douce au survol */
      box-shadow: 0 4px 6px rgb(231, 5, 5); /* Ombre rouge légère */
    }

    .btn-custom:hover {
      background: linear-gradient(135deg, rgb(79, 131, 199), rgb(248, 4, 4)); /* Changement de dégradé au survol */
      box-shadow: 0 6px 8px rgb(63, 34, 209); /* Ombre bleue renforcée au survol */
    }
  </style>

</head>

<body class="bg-gray-100 flex justify-center items-center h-screen">
  
  <!-- Conteneur principal -->
  <div class="bg-white p-8 rounded-lg shadow-md w-96">
    
    <!-- Titre de la page -->
    <h2 class="text-2xl font-semibold text-center mb-6">Connexion</h2>

    <!-- Affichage d'un message d'erreur s'il existe en session -->
    @if(session('error'))
      <div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm">
        {{ session('error') }}
      </div>
    @endif

    <!-- Affichage d'un message de succès s'il existe en session -->
    @if(session('success'))
      <div class="bg-green-100 text-green-600 p-3 rounded mb-4 text-sm">
        {{ session('success') }}
      </div>
    @endif

    <!-- Formulaire de connexion -->
    <form action="{{ route('login_staff') }}" method="POST">
      @csrf <!-- Protection CSRF obligatoire pour sécuriser les formulaires -->

      <!-- Champ Email -->
      <div class="mb-4">
        <label for="email" class="block text-gray-700 font-medium">Email</label>
        <input type="email" id="email" name="email" required
               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('email')
          <p class="text-red-500 text-xs mt-1">{{ $message }}</p> <!-- Affichage de l'erreur sur le champ email -->
        @enderror
      </div>

      <!-- Champ Mot de passe -->
      <div class="mb-4">
        <label for="password" class="block text-gray-700 font-medium">Mot de passe</label>
        <input type="password" id="password" name="password" required
               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('password')
          <p class="text-red-500 text-xs mt-1">{{ $message }}</p> <!-- Affichage de l'erreur sur le champ mot de passe -->
        @enderror
      </div>

      <!-- Bouton pour valider le formulaire -->
      <button type="submit" class="btn-custom">
        Se connecter
      </button>

    </form>
  </div>

</body>
</html>
