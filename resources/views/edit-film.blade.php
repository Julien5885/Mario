<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier le Film : ') . ($film['title'] ?? 'Film inconnu') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6"></h1>

        <!-- Formulaire de modification du film -->
        <form method="POST" action="{{ url('/toad/film/update/' . ($film['film_id'] ?? '')) }}" class="bg-white">
            @csrf
            @method('PUT')

            <!-- Titre -->
            <div class="mb-4">
                <label for="title" class="block font-bold text-gray-800 mb-2">Titre :</label>
                <input type="text" id="title" name="title" value="{{ $film['title'] ?? '' }}" class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block font-bold text-gray-800 mb-2">Description :</label>
                <textarea id="description" name="description" class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $film['description'] ?? '' }}</textarea>
            </div>

            <!-- Année de sortie -->
            <div class="mb-4">
                <label for="releaseYear" class="block font-bold text-gray-800 mb-2">Année de sortie :</label>
                <input type="number" id="releaseYear" name="releaseYear" value="{{ $film['releaseYear'] ?? '' }}" class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Langue -->
            <div class="mb-4">
                <label for="languageId" class="block font-bold text-gray-800 mb-2">Langue :</label>
                <input type="number" id="languageId" name="languageId" value="{{ $film['languageId'] ?? '' }}" class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Langue originale -->
            <div class="mb-4">
                <label for="originalLanguageId" class="block font-bold text-gray-800 mb-2">Langue originale :</label>
                <input type="number" id="originalLanguageId" name="originalLanguageId" value="{{ $film['originalLanguageId'] ?? '' }}" class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Durée de location -->
            <div class="mb-4">
                <label for="rentalDuration" class="block font-bold text-gray-800 mb-2">Durée de location (jours) :</label>
                <input type="number" id="rentalDuration" name="rentalDuration" value="{{ $film['rentalDuration'] ?? '' }}" class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Tarif de location -->
            <div class="mb-4">
                <label for="rentalRate" class="block font-bold text-gray-800 mb-2">Tarif de location (€) :</label>
                <input type="number" step="0.01" id="rentalRate" name="rentalRate" value="{{ $film['rentalRate'] ?? '' }}" class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Durée -->
            <div class="mb-4">
                <label for="length" class="block font-bold text-gray-800 mb-2">Durée (minutes) :</label>
                <input type="number" id="length" name="length" value="{{ $film['length'] ?? '' }}" class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Coût de remplacement -->
            <div class="mb-4">
                <label for="replacementCost" class="block font-bold text-gray-800 mb-2">Coût de remplacement (€) :</label>
                <input type="number" step="0.01" id="replacementCost" name="replacementCost" value="{{ $film['replacementCost'] ?? '' }}" class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Classification -->
            <div class="mb-4">
                <label for="rating" class="block font-bold text-gray-800 mb-2">Classification :</label>
                <input type="text" id="rating" name="rating" value="{{ $film['rating'] ?? '' }}" class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Fonctionnalités spéciales -->
            <div class="mb-4">
                <label for="specialFeatures" class="block font-bold text-gray-800 mb-2">Fonctionnalités spéciales :</label>
                <input type="text" id="specialFeatures" name="specialFeatures" value="{{ $film['specialFeatures'] ?? '' }}" class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Dernière mise à jour -->
            <div class="mb-4">
                <label for="lastUpdate" class="block font-bold text-gray-800 mb-2">Dernière mise à jour :</label>
                <input type="datetime-local" id="lastUpdate" name="lastUpdate" value="{{ isset($film['lastUpdate']) ? \Carbon\Carbon::parse($film['lastUpdate'])->format('Y-m-d\TH:i') : '' }}" class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- ID du réalisateur -->
            <div class="mb-4">
                <label for="idDirector" class="block font-bold text-gray-800 mb-2">ID du réalisateur :</label>
                <input type="number" id="idDirector" name="idDirector" value="{{ $film['idDirector'] ?? '' }}" class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Boutons -->
            <div class="flex items-center justify-between">
                <button type="submit" class="bg--500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Enregistrer les modifications
                </button>
                <a href="{{ url('/toad/film/all') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
