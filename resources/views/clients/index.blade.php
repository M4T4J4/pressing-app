@extends('layouts.app')

@section('content')

<div class="container mt-5">
        <h1 class="text-center mb-4 text-primary">Gestion des Clients</h1>

        <div class="d-grid gap-2 col-6 mx-auto mb-4">
            <a href="{{ route('clients.create') }}" class="btn btn-success btn-lg">Ajouter un Nouveau Client</a>
        </div>

        {{-- @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif --}}

        @if ($clients->isEmpty())
            <p class="text-center text-secondary fs-5">Aucun client enregistré pour le moment.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover shadow-sm" id="table">
                    <thead class="table-dark">
                        <tr>
                            <th>Nom</th>
                            <th>Téléphone</th>
                            <th>Adresse</th>
                            <th>Email</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clients as $client)
                            <tr>
                                <td>{{ $client->name }}</td>
                                <td>{{ $client->phone ?? 'N/A' }}</td>
                                <td>{{ $client->address ?? 'N/A' }}</td>
                                <td>{{ $client->email ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning btn-sm me-2">Éditer</a>
                                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?');">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
<script>
    $('#table').Datatable();
</script>
@endsection