<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajouter un Film à la liste') }}
        </h2>
    </x-slot>

    <!-- Styles personnalisés avec Tailwind et CSS3 -->
    <style>
        /* Boutons personnalisés avec dégradé, arrondi et effet relief */
        .btn-custom {
            @apply inline-block font-bold py-2 px-4 transition duration-300;
        }
        .btn-submit {
            background: linear-gradient(135deg,rgb(129, 125, 152),rgb(55, 38, 164));
            border: 2px solidrgb(246, 6, 6);
            color: white;
            @apply rounded-full;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        .btn-submit:hover {
            background: linear-gradient(135deg,rgb(78, 78, 126),rgb(55, 38, 164));
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.3);
        }
        .btn-cancel {
            background: linear-gradient(135deg,rgb(101, 82, 132),rgb(146, 51, 51));
            border: 2px solid #5a6268;
            color: white;
            @apply rounded-full;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        .btn-cancel:hover {
            background: linear-gradient(135deg,rgb(94, 90, 104),rgb(44, 46, 198));
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.3);
        }
    </style>

    <div class="container mx-auto px-4 py-8">
        <!-- Affichage des erreurs de validation -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-600 p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulaire d'ajout -->
        <form method="POST" action="{{ route('inventory.store') }}" class="bg-white p-6 rounded shadow-md">
            @csrf

            <h3 class="text-lg font-bold mb-4">Informations sur le Film</h3>
            <div class="mb-4">
                <label for="title" class="block font-bold text-gray-800 mb-2">Titre :</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" 
                       class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="description" class="block font-bold text-gray-800 mb-2">Description :</label>
                <textarea id="description" name="description" 
                          class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
            </div>

            <div class="mb-4">
                <label for="releaseYear" class="block font-bold text-gray-800 mb-2">Année de sortie :</label>
                <input type="number" id="releaseYear" name="releaseYear" value="{{ old('releaseYear') }}" 
                       class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Vous pouvez ajouter ici les autres champs requis par votre entité Film -->
            <div class="mb-4">
                <label for="languageId" class="block font-bold text-gray-800 mb-2">Langue :</label>
                <input type="number" id="languageId" name="languageId" value="{{ old('languageId', 1) }}" 
                       class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="originalLanguageId" class="block font-bold text-gray-800 mb-2">Langue originale :</label>
                <input type="number" id="originalLanguageId" name="originalLanguageId" value="{{ old('originalLanguageId', 1) }}" 
                       class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="rentalDuration" class="block font-bold text-gray-800 mb-2">Durée de location (jours) :</label>
                <input type="number" id="rentalDuration" name="rentalDuration" value="{{ old('rentalDuration', 3) }}" 
                       class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="rentalRate" class="block font-bold text-gray-800 mb-2">Tarif de location (€) :</label>
                <input type="number" step="0.01" id="rentalRate" name="rentalRate" value="{{ old('rentalRate', 4.99) }}" 
                       class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="length" class="block font-bold text-gray-800 mb-2">Durée (minutes) :</label>
                <input type="number" id="length" name="length" value="{{ old('length', 120) }}" 
                       class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="replacementCost" class="block font-bold text-gray-800 mb-2">Coût de remplacement (€) :</label>
                <input type="number" step="0.01" id="replacementCost" name="replacementCost" value="{{ old('replacementCost', 19.99) }}" 
                       class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="rating" class="block font-bold text-gray-800 mb-2">Classification :</label>
                <input type="text" id="rating" name="rating" value="{{ old('rating', 'G') }}" 
                       class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label for="lastUpdate" class="block font-bold text-gray-800 mb-2">Dernière mise à jour :</label>
                <input type="datetime-local" id="lastUpdate" name="lastUpdate"
                       value="{{ old('lastUpdate', now()->format('Y-m-d\TH:i')) }}"
                       class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <h3 class="text-lg font-bold mb-4">Informations sur l'Inventaire</h3>
            <div class="mb-4">
                <label for="store_id" class="block font-bold text-gray-800 mb-2">ID du magasin :</label>
                <input type="number" id="store_id" name="store_id" value="{{ old('store_id') }}" 
                       class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Boutons -->
            <div class="flex items-center justify-between mt-6">
                <button type="submit" class="btn-custom btn-submit">
                    Enregistrer
                </button>
                <a href="{{ route('inventory') }}" class="btn-custom btn-cancel">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
