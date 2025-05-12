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
            box-shadow: 0 6px 8px rgb(8, 75, 244);
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
            box-shadow: 0 6px 8px rgb(237, 6, 6);
        }

        /* Conteneur de la table */
        .table-container {
            @apply bg-white shadow-lg hover:shadow-2xl transition-shadow duration-300 rounded overflow-x-auto;
            margin-left: 2rem;
            margin-right: 2rem;
            padding: 2rem;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.3);

        }

        /* Cellules du tableau */
        th, td {
            padding: 0.75rem 1.5rem;
            white-space: nowrap;
        }
    </style>

    <!-- Conteneur du tableau d'inventaire -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 flex justify-between items-center py-6">
    <input id="searchInput" type="text" placeholder="Rechercher"
           class="border rounded-full h-12 w-72 px-6 focus:ring-2 focus:ring-blue-500">
  </div>

  <div class="table-container max-w-7xl mx-auto px-8 mb-8">
    <div class="bg-white shadow-lg rounded overflow-x-auto p-6">
      <table class="table-auto w-full divide-y divide-gray-200 text-center">
        <thead class="bg-gray-50">
          <tr>
            <th>Film</th>
            <th>Quantité</th>
            <th>Adresse</th>
            <th>District</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 odd:bg-white even:bg-gray-50">
          @forelse($inventories as $inv)
            <tr>
              <td class="py-2">{{ $inv['title'] }}</td>
              <td>{{ $inv['quantity'] }}</td>
              <td>{{ $inv['address'] }}</td>
              <td>{{ $inv['district'] }}</td>
              <td>
                {{-- On fait un DELETE sur /toad/inventory/deleteDVD/{filmId} --}}
                <form action="{{ route('inventory.destroy', $inv['filmId']) }}" method="POST"
                      onsubmit="return confirm('Supprimer tout le stock disponible ?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                          class="btn-custom btn-delete text-xs px-4 py-2">
                    Supprimer
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="py-4 text-gray-500">Aucun enregistrement trouvé.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Script de recherche --}}
  <script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
      let kw = this.value.toLowerCase();
      document.querySelectorAll('tbody tr').forEach(tr => {
        let title = tr.children[0]?.textContent.toLowerCase() || '';
        tr.style.display = title.includes(kw) ? '' : 'none';
      });
    });
  </script>

</x-app-layout>