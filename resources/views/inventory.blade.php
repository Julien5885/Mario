<x-app-layout>
    <!-- Titre principal de la page -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion de l\'Inventaire') }}
        </h2>
    </x-slot>

    <!-- Section CSS pour personnaliser l'apparence -->
    <style>
        /* Style générique pour tous les boutons */
        .btn-custom {
            @apply inline-block font-bold text-center transition duration-300;
        }

        /* Bouton Modifier */
        .btn-edit {
            background: linear-gradient(135deg, rgb(79, 131, 199), rgb(63, 34, 209));
            border: 2px solid #004BA0;
            color: white;
            @apply rounded-full;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        .btn-edit:hover {
            background: linear-gradient(135deg, rgb(79, 131, 199), rgb(46, 36, 240));
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.3);
        }

        /* Bouton Supprimer */
        .btn-delete {
            background: linear-gradient(135deg, rgb(125, 121, 121), rgb(237, 5, 5));
            border: 2px solid #C9302C;
            color: white;
            @apply rounded-full;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        .btn-delete:hover {
            background: linear-gradient(135deg, #C9302C, rgb(245, 8, 8));
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.3);
        }

        /* Conteneur de la table */
        .table-container {
            @apply bg-white shadow-lg hover:shadow-2xl transition-shadow duration-300 rounded overflow-x-auto;
            margin-left: 2rem;
            margin-right: 2rem;
            padding: 2rem;
        }

        /* Cellules du tableau */
        th, td {
            padding: 0.75rem 1.5rem;
            white-space: nowrap;
        }
    </style>

    <!-- Partie supérieure : bouton Ajouter + champ Recherche -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 flex justify-between items-center space-x-4 py-6">

        <!-- Champ de recherche -->
        <input type="text" id="searchInput" placeholder="Rechercher"
               class="border border-gray-300 rounded-full h-12 w-72 px-6 focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg">
    </div>

    <!-- Conteneur du tableau d'inventaire -->
    <div class="max-w-7xl mx-auto px-8 mb-8">
        <div class="bg-white shadow-lg hover:shadow-2xl transition-shadow duration-300 rounded overflow-x-auto p-6">
            <table class="table-auto w-full divide-y divide-gray-200 text-center">
                <!-- En-tête du tableau -->
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-xs font-medium text-gray-500 uppercase">Film</th>
                        <th class="text-xs font-medium text-gray-500 uppercase">Magasin</th>
                        <th class="text-xs font-medium text-gray-500 uppercase">Dernière mise à jour</th>
                        <th class="text-xs font-medium text-gray-500 uppercase">Disponible</th>
                        <th class="text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>

                <!-- Corps du tableau -->
                <tbody class="divide-y divide-gray-200">
                    @forelse ($inventories as $inventory)
                        <tr>
                            <td>{{ $filmMapping[$inventory['filmId']] ?? 'Titre inconnu' }}</td>
                            <td>{{ $inventory['storeId'] ?? 'N/A' }}</td>
                            <td>{{ $inventory['lastUpdate'] ?? '' }}</td>
                            <td>{{ isset($inventory['existe']) && $inventory['existe'] ? 'Oui' : 'Non' }}</td>

                            <!-- Boutons Modifier et Supprimer -->
                            <td class="flex justify-center items-center space-x-4 mt-2">
                                <!-- Bouton Modifier -->
                                <a href="{{ route('inventory.edit', $inventory['inventoryId']) }}" 
                                   class="btn-custom btn-edit rounded-full text-xs px-4 py-2">
                                    Modifier
                                </a>

                                <!-- Bouton Supprimer -->
                                <div>
                                    <form action="{{ route('inventory.destroy', $inventory['inventoryId']) }}" method="POST" onsubmit="return confirm('Confirmez-vous la suppression ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-custom btn-delete rounded-full text-xs px-4 py-2">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <!-- Message si aucun enregistrement n'est trouvé -->
                        <tr>
                            <td colspan="5" class="text-gray-500">Aucun enregistrement trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Script de filtrage des résultats selon la recherche -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('tbody tr');

            searchInput.addEventListener('keyup', function() {
                const keyword = this.value.toLowerCase();

                tableRows.forEach(row => {
                    const filmCell = row.querySelector('td:first-child'); // Colonne Film
                    if (filmCell) {
                        const filmTitle = filmCell.textContent.toLowerCase();
                        if (filmTitle.includes(keyword)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
            });
        });
    </script>

</x-app-layout>
