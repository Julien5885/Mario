<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Liste des Films') }}
        </h2>
    </x-slot>

    <!-- Styles personnalisés avec Tailwind et CSS3 -->
    <style>
        /* Boutons personnalisés avec dégradé et arrondi personnalisé */
        .btn-custom {
            @apply inline-block font-bold py-2 px-4 transition duration-300;
        }
        .btn-edit {
            background: linear-gradient(135deg, rgb(146, 163, 179), rgb(59, 89, 209));
            border: 2px solid #004BA0;
            color: white;
            @apply rounded-full;
        }
        .btn-edit:hover {
            background: linear-gradient(135deg, #0052C4, #2790E0);
        }
        .btn-delete {
            background: linear-gradient(135deg, rgb(171, 140, 140), rgb(196, 112, 112));
            border: 2px solid #C9302C;
            color: white;
            @apply rounded-full;
        }
        .btn-delete:hover {
            background: linear-gradient(135deg, rgb(125, 121, 121), rgb(237, 5, 5));
        }
        /* Bouton Rechercher personnalisé */
        .btn-search {
            background: linear-gradient(135deg,rgb(162, 175, 186), rgb(73, 75, 174));
            border: 2px solid #003F7A;
            color: white;
            @apply font-bold py-2 px-4 rounded-full transition duration-300;
        }
        .btn-search:hover {
            background: linear-gradient(135deg, #003F7A, #005AA8);
        }
        /* Carte de film avec dégradé et effet de survol */
        .card {
            @apply shadow-lg rounded-lg p-6 transform transition duration-300;
            background: linear-gradient(135deg, rgb(235, 236, 238), rgb(77, 102, 178));
        }
        .card:hover {
            transform: scale(1.05);
        }
        /* Styles pour améliorer la lisibilité du texte */
        .film-title {
            @apply text-2xl font-semibold text-gray-800 leading-tight;
        }
        .film-description {
            @apply text-gray-700 leading-relaxed text-base;
        }
        .film-year {
            @apply text-gray-700 text-sm;
        }
    </style>

    <div class="container mx-auto px-4 py-8">
        <!-- Barre de recherche -->
        <form method="GET" action="{{ url('/toad/film/all') }}" class="mb-6 flex">
            <input type="text" name="search" placeholder="Rechercher un film..."
                   class="border border-gray-300 p-2 w-full rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                   value="{{ request('search') }}">
            <button type="submit" class="btn-search">
                Rechercher
            </button>
        </form>

        <!-- Affichage des erreurs -->
        @if(isset($errorMessage))
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <p>{{ $errorMessage }}</p>
            </div>
        @endif

        @if($films->count() > 0)
            <!-- Grille des films (chaque film sur une ligne) -->
            <div class="grid grid-cols-1 gap-6">
                @foreach ($films as $film)
                    <div class="card flex">
                        <!-- Image fictive avec marge à droite plus importante -->
                        <img src="{{ asset('images/affiche_fictive.jpg') }}"
                             alt="Affiche du film fictif"
                             class="rounded-lg h-48 w-36 object-cover mr-8 shadow-md">
                        <!-- Conteneur texte + marge interne -->
                        <div class="film-details flex-1 p-4 space-y-4">
                            <h2 class="film-title">
                                {{ $film['title'] ?? 'Titre inconnu' }}
                            </h2>
                            <p class="film-description">
                                <span class="font-bold">Description :</span> {{ $film['description'] ?? 'Non spécifiée' }}
                            </p>
                            <p class="film-year mb-16">
                                <span class="font-bold">Année de sortie :</span> {{ $film['releaseYear'] ?? 'Non spécifiée' }}
                            </p>
<!-- Boutons d'action en colonne -->
<div class="flex flex-col space-y-2 items-start mt-8">
    <a href="{{ url('/toad/film/edit/' . $film['filmId']) }}"
       class="btn-custom btn-edit">
        Modifier
    </a>
    <form method="POST" action="{{ url('/toad/film/delete/' . $film['filmId']) }}"
          onsubmit="return confirm('Voulez-vous vraiment supprimer ce film ?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-custom btn-delete">
            Supprimer
        </button>
    </form>
</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Liens de pagination -->
            <div class="mt-6">
                {{ $films->links() }}
            </div>
        @else
            <p class="text-gray-500">Aucun film disponible.</p>
        @endif
    </div>
</x-app-layout>
