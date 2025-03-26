@extends('layouts.simple')

@section('title', 'Utilisateurs Bloqués')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-xl font-bold mb-4">Utilisateurs Bloqués</h1>

        @if(empty($users))
            <p class="text-gray-500">Aucun utilisateur bloqué trouvé.</p>
        @else
            <table class="table-auto border-collapse border border-gray-300 w-full text-left">
                <thead>
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">Nom</th>
                        <th class="border border-gray-300 px-4 py-2">Email</th>
                        <th class="border border-gray-300 px-4 py-2">Tentatives</th>
                        <th class="border border-gray-300 px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">{{ $user['name'] }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $user['email'] }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $user['failedAttempts'] }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <form method="POST" action="{{ route('users.unlock', $user['id']) }}">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-gray font-bold py-2 px-4 rounded">
                                        Débloquer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
