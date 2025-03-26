<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Liste des Films</h1>

        <!-- Barre de recherche -->
        <form method="GET" action="{{ url('/toad/film/all') }}" class="mb-6 flex">
            <input type="text" name="search" placeholder="Rechercher un film..." class="border p-2 w-full rounded-l-md" value="{{ request('search') }}">
            <button type="submit" class="bg-gray-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r-md">
                Rechercher
            </button>
        </form>

        <!-- Affichage des erreurs -->
        @if(isset($errorMessage))
            <div class="alert alert-danger mb-4">
                <p>{{ $errorMessage }}</p>
            </div>
        @endif

        @if(isset($films) && is_array($films) && count($films) > 0)
            <!-- Grille des films -->
            <div class="grid grid-cols-1 gap-6">
                @foreach ($films as $film)
                    <div class="film-card bg-white shadow-md rounded-lg p-4 flex">
                        <img src="https://via.placeholder.com/150x200?text=Affiche+Fictive" alt="Affiche du film" class="rounded-lg h-48 w-36 object-cover mr-4">

                        <div class="film-details flex-1">
                            <h2 class="film-title font-semibold text-xl text-gray-800">{{ $film['title'] ?? 'Titre inconnu' }}</h2>
                            <p class="text-gray-600">
                                <span class="font-bold">Description :</span> {{ $film['description'] ?? 'Non spécifiée' }}
                            </p>
                            <p class="text-gray-600">
                                <span class="font-bold">Année de sortie :</span> {{ $film['releaseYear'] ?? 'Non spécifiée' }}
                            </p>

                            <!-- Boutons -->
                            <div class="mt-4 flex space-x-2">
                                <!-- Bouton Modifier (couleur primaire pour meilleure visibilité) -->
                                <a href="{{ url('/toad/film/edit/' . $film['filmId']) }}" style="display: inline-block; background-color: #004e98; color: white; padding: 10px 20px; border-radius: 5px; text-align: center; text-decoration: none; font-weight: bold;">
                                    Modifier
                                </a>
                                 <!-- Bouton Supprimer -->
                            <form method="POST" action="{{ url('/toad/film/delete/' . $film['filmId']) }}" onsubmit="return confirm('Voulez-vous vraiment supprimer ce film ?');" style="display: inline;">
                            @csrf
                             @method('DELETE')
                             <button type="submit" style="background-color: #d9534f; color: white; padding: 10px 20px; border-radius: 5px; border: none; font-weight: bold;">
                              Supprimer
                            </button>
                            </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">Aucun film disponible.</p>
        @endif
    </div>
</x-app-layout>
