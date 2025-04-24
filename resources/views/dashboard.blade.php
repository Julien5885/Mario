<x-app-layout>
    <x-slot name="header">
        <!-- Titre avec dégradé personnalisé -->
        <h2 class="title-gradient text-left">
            {{ __('MaRi0') }}
        </h2>
    </x-slot>

    <!-- Styles personnalisés -->
    <style>
        /* Titre avec dégradé et relief */
        .title-gradient {
            @apply text-3xl font-extrabold tracking-wide italic;
            font-family: 'Orbitron', sans-serif;
            background: linear-gradient(to right, rgb(18, 18, 19), rgb(12, 45, 97));
            -webkit-background-clip: text;
            color: transparent;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        /* Effet relief sur le conteneur principal */
        .card-relief {
            @apply bg-white overflow-hidden shadow-sm sm:rounded-lg;
            transition: box-shadow 0.3s ease;
        }
        .card-relief:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card-relief">
                <div class="p-6 text-gray-900">
                    {{ __("Bienvenue sur l'application Web Mario !!
                           ") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
