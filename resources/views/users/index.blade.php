@extends('layouts.app')

@section('title','index')

@section('content')
<div class="content flex-grow-1">
        <div class="container-fluid">
            {{-- @include('layouts.navigation') Assurez-vous que ce chemin est correct pour votre navbar --}}

    <div class="container">
        <div class="alert-container">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                Gestion des Utilisateurs
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Rôles Actuels</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @forelse ($user->getRoleNames() as $role)
                                            <span class="badge bg-secondary">{{ $role }}</span>
                                        @empty
                                            <span class="badge bg-info">Aucun rôle</span>
                                        @endforelse
                                    </td>
                                    <td>
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary me-2">
                                            <i class="fas fa-edit"></i> Gérer Rôles
                                        </a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucun utilisateur trouvé (à part vous-même).</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
@endsection