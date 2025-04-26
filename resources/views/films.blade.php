<x-app-layout>
    <!-- En-tête de la page -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Liste des Films') }}
        </h2>
    </x-slot>

    <!-- Styles personnalisés pour cette page -->
    <style>
    /* Bouton Ajouter */
    .btn-create {
        background: linear-gradient(135deg, rgb(93, 170, 211), rgb(63, 34, 209));
        border: 2px solid rgb(63, 34, 209);
        color: white;
        @apply inline-block font-bold text-lg transition duration-300;
        height: 3rem;
        min-width: 12rem;
        padding: 0 2rem;
        border-radius: 9999px;
        box-shadow: 0 4px 8px rgba(155, 146, 146, 0.2);
        text-align: center;
        justify-content: center;
        align-items: center;
        display: flex;
    }
    .btn-create:hover {
        background: linear-gradient(135deg, rgb(79, 131, 199), rgb(46, 36, 240));
        box-shadow: 0 6px 8px rgb(99, 95, 95);
    }

    /* Bouton Rechercher */
    .btn-search {
        background: linear-gradient(135deg, rgb(127, 133, 130), rgb(66, 73, 69)); 
        border: 2px solid rgb(71, 78, 74);
        color: white;
        @apply inline-block font-bold text-lg transition duration-300;
        height: 3rem;
        min-width: 12rem;
        padding: 0 2rem;
        border-radius: 9999px;
        box-shadow: 0 4px 8px rgb(25, 66, 148);
        text-align: center;
        justify-content: center;
        align-items: center;
        display: flex;
    }
    .btn-search:hover {
        background: linear-gradient(135deg, rgb(126, 125, 149), rgb(22, 59, 153));
        box-shadow: 0 6px 8px rgba(163, 143, 143, 0.3);
    }

    /* Champ de recherche */
    .search-input {
        @apply border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg;
        height: 3rem;
        width: 18rem;
        padding-left: 1.5rem;
        border-radius: 9999px; /* <-- ajout ici pour l'arrondir */
    }

    /* Boutons Modifier / Supprimer */
    .btn-custom {
        @apply inline-block font-bold py-2 px-4 transition duration-300;
        border-radius: 9999px;
    }
    .btn-edit {
        background: linear-gradient(135deg, rgb(79, 131, 199), rgb(38, 52, 212));
        border: 2px solid rgb(38, 52, 212);
        color: white;
    }
    .btn-edit:hover {
        background: linear-gradient(135deg, rgb(73, 123, 193), rgb(17, 106, 249));
    }
    .btn-delete {
        background: linear-gradient(135deg, rgb(171, 140, 140), rgb(192, 58, 58));
        border: 2px solid rgb(192, 58, 58);
        color: white;
    }
    .btn-delete:hover {
        background: linear-gradient(135deg, rgb(212, 188, 188), rgb(255, 0, 0));
    }

    /* Carte de présentation d'un film */
    .card {
        @apply shadow-lg p-6 transform transition duration-300;
        border-radius: 1rem;
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

    /* Conteneur "Ajouter + Rechercher" */
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
                             class="rounded-xl h-32 w-24 object-cover mr-6 shadow-md">

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
