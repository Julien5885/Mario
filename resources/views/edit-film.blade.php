<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier le Film : ') . ($film['title'] ?? 'Film inconnu') }}
        </h2>
    </x-slot>

    <!-- Styles personnalisés avec Tailwind et CSS3 -->
    <style>
        /* Boutons personnalisés avec dégradé, arrondi et effet relief */
        .btn-custom {
            @apply inline-block font-bold py-2 px-4 transition duration-300;
        }
        .btn-edit {
            background: linear-gradient(135deg,rgb(79, 131, 199), rgb(63, 34, 209));
            border: 2px solid #004BA0;
            color: white;
            @apply rounded-full;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        .btn-edit:hover {
            background: linear-gradient(135deg,rgb(79, 131, 199), rgb(46, 36, 240));
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.3);
        }
        .btn-cancel {
            background: linear-gradient(135deg,rgb(125, 121, 121), rgb(237, 5, 5));
            border: 2px solid #4B5563;
            color: white;
            @apply rounded-full;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        .btn-cancel:hover {
            background: linear-gradient(135deg, #C9302C,rgb(245, 8, 8));
            box-shadow: 0 6px 8px rgba(176, 101, 101, 0.3);
        }
        /* Optionnel : ajustement du relief sur le bouton Rechercher, si nécessaire */
        .btn-search {
            background: linear-gradient(135deg, #004E98, #006BB3);
            border: 2px solid #003F7A;
            color: white;
            @apply font-bold py-2 px-4 rounded-full transition duration-300;
        }
        .btn-search:hover {
            background: linear-gradient(135deg, #003F7A, #005AA8);
        }
        /* Styles pour améliorer la lisibilité du texte */
        .film-title {
            @apply text-2xl font-semibold text-gray-800 mb-4 leading-tight;
        }
        .film-description {
            @apply text-gray-700 mb-4 leading-relaxed text-base;
        }
        .film-year {
            @apply text-gray-700 mb-4 text-sm;
        }
    </style>

    <div class="container mx-auto px-4 py-8">
        <!-- Affichage des erreurs -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm">
                {{ $errors->first('error') }}
            </div>
        @endif

        <!-- Formulaire de modification du film -->
        <form method="POST" action="{{ url('/toad/film/update/' . ($film['filmId'] ?? '')) }}" class="bg-white p-6 rounded shadow">
            @csrf
            @method('PUT')

            <!-- Titre -->
            <div class="mb-6">
                <label for="title" class="block font-bold text-gray-800 mb-2">Titre :</label>
                <input type="text" id="title" name="title" 
                       value="{{ $film['title'] ?? '' }}" 
                       class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block font-bold text-gray-800 mb-2">Description :</label>
                <textarea id="description" name="description" 
                          class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $film['description'] ?? '' }}</textarea>
            </div>

            <!-- Année de sortie -->
            <div class="mb-6">
                <label for="releaseYear" class="block font-bold text-gray-800 mb-2">Année de sortie :</label>
                <input type="number" id="releaseYear" name="releaseYear" 
                       value="{{ $film['releaseYear'] ?? '' }}" 
                       class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Langue -->
            <div class="mb-6">
                <label for="languageId" class="block font-bold text-gray-800 mb-2">Langue :</label>
                <input type="number" id="languageId" name="languageId" 
                       value="{{ $film['languageId'] ?? '' }}" 
                       class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Langue originale -->
            <div class="mb-6">
                <label for="originalLanguageId" class="block font-bold text-gray-800 mb-2">Langue originale :</label>
                <input type="number" id="originalLanguageId" name="originalLanguageId" 
                       value="{{ $film['originalLanguageId'] ?? '' }}" 
                       class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Durée de location -->
            <div class="mb-6">
                <label for="rentalDuration" class="block font-bold text-gray-800 mb-2">Durée de location (jours) :</label>
                <input type="number" id="rentalDuration" name="rentalDuration" 
                       value="{{ $film['rentalDuration'] ?? '' }}" 
                       class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Tarif de location -->
            <div class="mb-6">
                <label for="rentalRate" class="block font-bold text-gray-800 mb-2">Tarif de location (€) :</label>
                <input type="number" step="0.01" id="rentalRate" name="rentalRate" 
                       value="{{ $film['rentalRate'] ?? '' }}" 
                       class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Durée -->
            <div class="mb-6">
                <label for="length" class="block font-bold text-gray-800 mb-2">Durée (minutes) :</label>
                <input type="number" id="length" name="length" 
                       value="{{ $film['length'] ?? '' }}" 
                       class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Coût de remplacement -->
            <div class="mb-6">
                <label for="replacementCost" class="block font-bold text-gray-800 mb-2">Coût de remplacement (€) :</label>
                <input type="number" step="0.01" id="replacementCost" name="replacementCost" 
                       value="{{ $film['replacementCost'] ?? '' }}" 
                       class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Classification -->
            <div class="mb-6">
                <label for="rating" class="block font-bold text-gray-800 mb-2">Classification :</label>
                <input type="text" id="rating" name="rating" 
                       value="{{ $film['rating'] ?? '' }}" 
                       class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Dernière mise à jour -->
            <div class="mb-6">
                <label for="lastUpdate" class="block font-bold text-gray-800 mb-2">Dernière mise à jour :</label>
                <input type="datetime-local" id="lastUpdate" name="lastUpdate" 
                       value="{{ isset($film['lastUpdate']) ? \Carbon\Carbon::parse($film['lastUpdate'])->format('Y-m-d\TH:i') : '' }}" 
                       class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Boutons -->
            <div class="flex items-center justify-between mt-4">
            <button type="submit" class="btn-edit rounded-full py-2 px-6 font-bold transition duration-300">
    Enregistrer les modifications
</button>
<a href="{{ url('/toad/film/all') }}" class="btn-cancel rounded-full py-2 px-6 font-bold transition duration-300">
    Annuler
</a>
            </div>
        </form>
    </div>
</x-app-layout>
