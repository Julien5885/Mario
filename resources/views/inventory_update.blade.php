<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Modifier un exemplaire') }}
    </h2>
  </x-slot>

  <div class="container mx-auto px-4 py-8">
    @if ($errors->any())
      <div class="bg-red-100 text-red-600 p-3 rounded mb-4">
        <ul>
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('inventory.update', $inventory['inventoryId']) }}" class="bg-white p-6 rounded shadow-md">
      @csrf
      @method('PUT')

      <h3 class="text-lg font-bold mb-4">Informations sur l'Inventaire</h3>

      <div class="mb-4">
        <label for="film_id" class="block font-bold text-gray-800 mb-2">Film :</label>
        <select name="film_id" id="film_id"
                class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
          @foreach($filmMapping as $fid => $title)
            <option value="{{ $fid }}"
              {{ $fid == $inventory['filmId'] ? 'selected' : '' }}>
              {{ $title }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="mb-4">
        <label for="store_id" class="block font-bold text-gray-800 mb-2">Magasin (ID) :</label>
        <input type="number" name="store_id" id="store_id"
               value="{{ $inventory['storeId'] }}"
               class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div class="mb-4">
        <label for="last_update" class="block font-bold text-gray-800 mb-2">Dernière mise à jour :</label>
        <input type="datetime-local" name="last_update" id="last_update"
               value="{{ \Carbon\Carbon::parse($inventory['lastUpdate'])->format('Y-m-d\TH:i') }}"
               class="border border-gray-300 rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div class="mb-4 flex items-center">
        <input type="checkbox" name="existe" id="existe"
               class="mr-2" {{ $inventory['existe'] ? 'checked' : '' }}>
        <label for="existe" class="font-bold text-gray-800">Disponible</label>
      </div>

      <div class="flex items-center justify-between mt-6">
        <button type="submit"
                class="btn-custom btn-submit">
          Enregistrer les modifications
        </button>
        <a href="{{ route('inventory') }}" class="btn-custom btn-cancel">
          Annuler
        </a>
      </div>
    </form>
  </div>
</x-app-layout>
