@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Nombre de films réalisés par les réalisateurs</h1>
    @if (!empty($directors))
        <table class="table">
            <thead>
                <tr>
                    <th>ID Réalisateur</th>
                    <th>Nom</th>
                    <th>Nombre de Films</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($directors as $director)
                    <tr>
                        <td>{{ $director['director_id'] }}</td>
                        <td>{{ $director['nom'] }}</td>
                        <td>{{ $director['nombre_films'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Aucun réalisateur trouvé.</p>
    @endif
</div>
@endsection
