<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Bouton personnalisé avec dégradé, arrondi et effet relief */
    .btn-custom {
      @apply : w-full py-full rounded-lg font-bold transition duration-300;
      background: linear-gradient(135deg,rgb(131, 129, 138),rgb(105, 135, 181));
      border: 4px solidrgb(5, 5, 5);
      color: white;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    }
    .btn-custom:hover {
      background: linear-gradient(135deg, #003F7A, #005AA8);
      box-shadow: 0 6px 8px rgba(0, 0, 0, 0.3);
    }
  </style>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
  <div class="bg-white p-8 rounded-lg shadow-md w-96">
    <h2 class="text-2xl font-semibold text-center mb-6">Connexion</h2>

    @if(session('error'))
      <div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm">
        {{ session('error') }}
      </div>
    @endif

    @if(session('success'))
      <div class="bg-green-100 text-green-600 p-3 rounded mb-4 text-sm">
        {{ session('success') }}
      </div>
    @endif

    <form action="{{ route('login_staff') }}" method="POST">
      @csrf

      <div class="mb-4">
        <label for="email" class="block text-gray-700 font-medium">Email</label>
        <input type="email" id="email" name="email" required
               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('email')
          <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div class="mb-4">
        <label for="password" class="block text-gray-700 font-medium">Mot de passe</label>
        <input type="password" id="password" name="password" required
               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('password')
          <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <button type="submit" class="btn-custom">
        Se connecter
      </button>
    </form>
  </div>
</body>
</html>
