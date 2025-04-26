<x-app-layout>
    <!-- En-tête de la page -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Liste des Films') }}
        </h2>
    </x-slot>

    <!-- Styles personnalisés pour cette page -->
    <style>
        /* Bouton Ajouter + Rechercher */
        .btn-create, .btn-search {
            background: linear-gradient(135deg, rgb(79, 131, 199), rgb(63, 34, 209));
            border: 2px solid rgb(63, 34, 209);
            color: white;
            @apply inline-block font-bold text-lg transition duration-300;
            height: 3rem; /* h-12 */
            min-width: 12rem; /* w-48 */
            padding: 0 2rem; /* px-8 */
            border-radius: 9999px; /* rounded-full */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .btn-create:hover, .btn-search:hover {
            background: linear-gradient(135deg, rgb(79, 131, 199), rgb(46, 36, 240));
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.3);
        }

        /* Champ de recherche */
        .search-input {
            @apply border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg;
            height: 3rem; /* h-12 */
            width: 18rem; /* w-72 */
            padding-left: 1.5rem; /* px-6 */
        }

        /* Boutons Modifier / Supprimer */
        .btn-custom {
            @apply inline-block font-bold py-2 px-4 transition duration-300;
        }
        .btn-edit {
            background: linear-gradient(135deg, rgb(79, 131, 199), rgb(38, 52, 212));
            border: 2px solid rgb(38, 52, 212);
            color: white;
            @apply rounded-full;
        }
        .btn-edit:hover {
            background: linear-gradient(135deg, rgb(73, 123, 193), rgb(17, 106, 249));
        }
        .btn-delete {
            background: linear-gradient(135deg, rgb(171, 140, 140), rgb(192, 58, 58));
            border: 2px solid rgb(192, 58, 58);
            color: white;
            @apply rounded-full;
        }
        .btn-delete:hover {
            background: linear-gradient(135deg, rgb(212, 188, 188), rgb(255, 0, 0));
        }

        /* Carte de présentation d'un film */
        .card {
            @apply shadow-lg rounded-lg p-6 transform transition duration-300;
            background: linear-gradient(135deg, rgb(235, 236, 238), rgb(77, 102, 178));
        }
        .card:hover {
            transform: scale(1.05);
        }

        /* Texte */
        .film-title {
            @apply text-2xl font-semibold text-gray-800 leading-tight;
        }
        .film-description {
            @apply text-gray-700 leading-relaxed text-base;
        }
        .film-year {
            @apply text-gray-700 text-sm;
        }

        /* Conteneur de la barre "Ajouter + Rechercher" */
        .toolbar {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
    </style>

    <div class="container mx-auto px-4 py-12">
        
        <!-- Ligne bouton Ajouter un film + recherche -->
        <div class="toolbar flex flex-wrap gap-6 justify-center md:justify-between items-center mb-10">
            <a href="{{ url('/toad/film/create') }}" class="btn-create">
                Ajouter un film
            </a>

            <form method="GET" action="{{ url('/toad/film/all') }}" class="flex flex-wrap gap-2 items-center">
                <input type="text" name="search" placeholder="Rechercher un film..." value="{{ request('search') }}"
                       class="search-input">
                <button type="submit" class="btn-search">
                    Rechercher
                </button>
            </form>
        </div>

        <!-- Affichage des erreurs -->
        @if(isset($errorMessage))
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <p>{{ $errorMessage }}</p>
            </div>
        @endif

        <!-- Liste des films -->
        @if($films->count() > 0)
            <div class="grid grid-cols-1 gap-6">
                @foreach ($films as $film)
                    <div class="card flex">
                        <img src="{{ asset('images/affiche_fictive.jpg') }}" alt="Affiche du film fictif"
                             class="rounded-lg h-48 w-36 object-cover mr-8 shadow-md">

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

                            <div class="flex flex-col space-y-2 items-start mt-8">
                                <a href="{{ url('/toad/film/edit/' . $film['filmId']) }}" class="btn-custom btn-edit">
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

            <!-- Pagination -->
            <div class="mt-6">
                {{ $films->links() }}
            </div>
        @else
            <p class="text-gray-500 text-center">Aucun film disponible.</p>
        @endif
    </div>
</x-app-layout>
