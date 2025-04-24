<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion de l\'Inventaire') }}
        </h2>
    </x-slot>

    <!-- Styles personnalisés -->
    <style>
        /* Boutons personnalisés avec dégradé, arrondi et effet relief */
        .btn-custom {
            @apply inline-block font-bold py-2 px-4 transition duration-300;
        }
        .btn-edit {
            background: linear-gradient(135deg,rgb(146, 163, 179), rgb(59, 89, 209));
            border: 2px solid #004BA0;
            color: white;
            @apply rounded-full;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        .btn-edit:hover {
            background: linear-gradient(135deg,rgb(156, 159, 163),rgb(63, 34, 209));
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.3);
        }
        .btn-delete {
            background: linear-gradient(135deg, rgb(125, 121, 121), rgb(237, 5, 5));
            border: 2px solid #C9302C;
            color: white;
            @apply rounded-full;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        .btn-delete:hover {
            background: linear-gradient(135deg, #C9302C, #E84343);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.3);
        }
        /* Pour centrer le tableau */
        table { 
            margin: 0 auto;
        }
        /* Réduire le padding interne des cellules */
        th, td {
            padding: 0.5rem 1rem;
        }
    </style>

     <!-- Styles personnalisés avec Tailwind et CSS3 -->
     <style>
        /* Bouton Ajouter personnalisé */
        .btn-create {
            background: linear-gradient(135deg,rgb(197, 204, 229),rgb(55, 46, 209));
            border: 2px solidrgb(10, 10, 10);
            color: white;
            @apply inline-block font-bold py-2 px-4 rounded-full transition duration-300;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .btn-create:hover {
            background: linear-gradient(135deg,rgb(240, 240, 243),rgb(55, 46, 209));
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.3);
        }
    </style>

    <div class="container mx-auto px-8 py-10">
        <!-- Bouton pour accéder à la vue d'ajout d'un film / exemplaire -->
        <div class="mb-6">
            <a href="{{ route('inventory.create') }}" class="btn-create">
                Ajouter un film / exemplaire
            </a>
        </div>
        <div class="bg-white shadow-lg hover:shadow-2xl transition-shadow duration-300 rounded overflow-x-auto w-full max-w-4xl mx-auto">
            <table class="table-auto divide-y divide-gray-200 text-center">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-xs font-medium text-gray-500 uppercase">Film</th>
                        <th class="text-xs font-medium text-gray-500 uppercase">Magasin</th>
                        <th class="text-xs font-medium text-gray-500 uppercase">Dernière mise à jour</th>
                        <th class="text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($inventories as $inventory)
                        <tr>
                            <td>{{ $filmMapping[$inventory['filmId']] ?? 'Titre inconnu' }}</td>
                            <td>{{ $inventory['storeId'] ?? 'N/A' }}</td>
                            <td>{{ $inventory['lastUpdate'] ?? '' }}</td>
                            <td>
                            <a href="{{ route('inventory.edit', $inventory['inventoryId']) }}"
   class="btn-custom btn-edit">
    Modifier
</a>
<form action="{{ route('inventory.destroy', $inventory['inventoryId']) }}"
      method="POST" style="display:inline">
  @csrf
  @method('DELETE')
  <button class="btn-custom btn-delete"
          onclick="return confirm('Confirmez-vous la suppression ?');">
    Supprimer
  </button>
</form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-gray-500">Aucun enregistrement trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div> 
</x-app-layout>
