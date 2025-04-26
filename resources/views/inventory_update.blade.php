<x-app-layout>
    <!-- En-tête de la page -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des magasins') }}
        </h2>
    </x-slot>

    <!-- Section CSS interne pour personnaliser les styles -->
    <style>
        /* Style général pour tous les boutons personnalisés */
        .btn-custom {
            @apply inline-block font-bold py-2 px-4 transition duration-300;
        }

        /* Style spécifique pour le bouton Enregistrer */
        .btn-submit {
            background: linear-gradient(135deg, rgb(79, 131, 199), rgb(63, 34, 209));
            border: 2px solid rgb(63, 34, 209); /* Correction : espace ajouté après 'solid' */
            color: white;
            border-radius: 9999px; /* arrondi complet */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        .btn-submit:hover {
            background: linear-gradient(135deg, rgb(79, 131, 199), rgb(46, 36, 240));
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.3);
        }

        /* Style spécifique pour le bouton Annuler */
        .btn-cancel {
            background: linear-gradient(135deg, rgb(171, 140, 140), rgb(241, 19, 19));
            border: 2px rgb(241, 19, 19);
            color: white;
            border-radius: 9999px; /* arrondi complet */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        .btn-cancel:hover {
            background: linear-gradient(135deg, rgb(212, 188, 188), rgb(255, 0, 0));
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

        <!-- Formulaire de modification du film -->
        <form method="POST" action="{{ route('inventory.update', $inventory['inventoryId']) }}" class="border border-gray-300 bg-white p-6 rounded shadow-md">
            @csrf
            @method('PUT')

            <!-- Titre de la section -->
            <h3 class="text-lg font-bold mb-4">Informations sur l'Inventaire</h3>

            <!-- Champ de sélection du film -->
            <div class="mb-4">
                <label for="film_id" class="block font-bold text-gray-800 mb-2">Film :</label>
                <select name="film_id" id="film_id"
                        class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($filmMapping as $fid => $title)
                        <option value="{{ $fid }}" {{ $fid == $inventory['filmId'] ? 'selected' : '' }}>
                            {{ $title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Champ ID du magasin -->
            <div class="mb-4">
                <label for="store_id" class="block font-bold text-gray-800 mb-2">Magasin (ID) :</label>
                <input type="number" name="store_id" id="store_id"
                       value="{{ $inventory['storeId'] }}"
                       class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Champ Dernière mise à jour -->
            <div class="mb-4">
                <label for="last_update" class="block font-bold text-gray-800 mb-2">Dernière mise à jour :</label>
                <input type="datetime-local" name="last_update" id="last_update"
                       value="{{ \Carbon\Carbon::parse($inventory['lastUpdate'])->format('Y-m-d\TH:i') }}"
                       class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Case à cocher pour Disponibilité -->
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="existe" id="existe" class="mr-2" {{ $inventory['existe'] ? 'checked' : '' }}>
                <label for="existe" class="font-bold text-gray-800">Disponible</label>
            </div>

            <!-- Boutons de validation et d'annulation -->
            <div class="flex items-center justify-between mt-6">
                <button type="submit" class="btn-custom btn-submit">
                    Enregistrer les modifications
                </button>
                <a href="{{ route('inventory') }}" class="btn-custom btn-cancel">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
