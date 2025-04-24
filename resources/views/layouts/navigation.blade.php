<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center">
                <!-- Logo avec marge droite -->
                <div class="shrink-0 flex items-center mr-8">
                    <a href="{{ route('film.list') }}">
                        <img src="{{ asset('images/logo.png') }}" alt="Mon Logo" class="block h-9 w-auto">
                    </a>
                </div>
                <!-- Navigation Links avec marge gauche supplémentaire sur le premier lien -->
                <div class="hidden sm:flex">
                    <x-nav-link :href="route('film.list')" :active="request()->is('toad/film/all')" class="ml-4">
                        {{ __('Liste des films') }}
                    </x-nav-link>
                    <x-nav-link :href="route('inventory')" :active="request()->is('inventory')" class="ml-4">
                        {{ __('Inventaire') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown Aligné à droite -->
            <div class="hidden sm:flex sm:items-center sm:ml-auto">
                @if(session('is_logged_in'))
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ session('first_name') }} {{ session('last_name') }}</div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <!-- Profile Link -->
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout_staff') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout_staff')"
                                                 onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login_staff') }}" class="text-sm text-gray-500 hover:text-gray-700">
                        {{ __('Connexion') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</nav>
