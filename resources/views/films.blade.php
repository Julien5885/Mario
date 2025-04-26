<x-app-layout>
    <!-- En-tête de la page -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Liste des Films') }}
        </h2>
    </x-slot>

    <!-- Styles personnalisés pour cette page -->
    <style>
        /* Boutons génériques */
        .btn-custom {
            @apply inline-block font-bold py-2 px-4 transition duration-300;
        }

        /* Bouton Modifier */
        .btn-edit {
            background: linear-gradient(135deg, rgb(79, 131, 199), rgb(38, 52, 212));
            border: 2px solid rgb(38, 52, 212); /* Correction du 'solidrgb' */
            color: white;
            @apply rounded-full;
        }
        .btn-edit:hover {
            background: linear-gradient(135deg, rgb(73, 123, 193), rgb(17, 106, 249));
        }

        /* Bouton Supprimer */
        .btn-delete {
            background: linear-gradient(135deg, rgb(171, 140, 140), rgb(192, 58, 58));
            border: 2px solid rgb(192, 58, 58); /* Correction du 'solidrgb' */
            color: white;
            @apply rounded-full;
        }
        .btn-delete:hover {
            background: linear-gradient(135deg, rgb(212, 188, 188), rgb(255, 0, 0));
        }

        /* Bouton Rechercher */
        .btn-search {
            background: linear-gradient(135deg, rgb(79, 131, 199), rgb(63, 34, 209));
            border: 2px solid #003F7A;
            color: white;
            @apply font-bold py-2 px-4 rounded-full transition duration-300;
        }
        .btn-search:hover {
            background: linear-gradient(135deg, rgb(105, 158, 208), rgb(24, 60, 169));
        }

        /* Carte de présentation d'un film */
        .card {
            @apply shadow-lg rounded-lg p-6 transform transition duration-300;
            background: linear-gradient(135deg, rgb(235, 236, 238), rgb(77, 102, 178));
        }
        .card:hover {
            transform: scale(1.05); /* Effet d'agrandissement au survol */
        }

        /* Styles pour les textes */
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
        <form method="GET" action="{{ url('/toad/film/all') }}" class="mt-8 mb-6 flex">
            <!-- Champ de recherche -->
            <input type="text" name="search" placeholder="Rechercher un film..."
                   class="border border-gray-300 p-2 w-full rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                   value="{{ request('search') }}">
            <!-- Bouton de recherche -->
            <button type="submit" class="btn-search">
                Rechercher
            </button>
        </form>

        <!-- Affichage des erreurs si elles existent -->
        @if(isset($errorMessage))
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <p>{{ $errorMessage }}</p>
            </div>
        @endif

        @if($films->count() > 0)
            <!-- Grille d'affichage des films -->
            <div class="grid grid-cols-1 gap-6">
                @foreach ($films as $film)
                    <div class="card flex">
                        <!-- Image fictive du film -->
                        <img src="{{ asset('images/affiche_fictive.jpg') }}"
                             alt="Affiche du film fictif"
                             class="rounded-lg h-48 w-36 object-cover mr-8 shadow-md">

                        <!-- Détails du film -->
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

                            <!-- Boutons d'action pour modifier ou supprimer -->
                            <div class="flex flex-col space-y-2 items-start mt-8">
                                <!-- Bouton Modifier -->
                                <a href="{{ url('/toad/film/edit/' . $film['filmId']) }}"
                                   class="btn-custom btn-edit rounded-full">
                                    Modifier
                                </a>

                                <!-- Formulaire pour supprimer -->
                                <form method="POST" action="{{ url('/toad/film/delete/' . $film['filmId']) }}"
                                      onsubmit="return confirm('Voulez-vous vraiment supprimer ce film ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-custom btn-delete rounded-full">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination automatique -->
            <div class="mt-6">
                {{ $films->links() }}
            </div>
        @else
            <!-- Message si aucun film n'est disponible -->
            <p class="text-gray-500">Aucun film disponible.</p>
        @endif
    </div>
</x-app-layout>
