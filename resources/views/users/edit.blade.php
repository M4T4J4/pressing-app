@extends('layouts.app')

@section('title','edit')

@section('content')
<div class="content flex-grow-1">
        <div class="container-fluid">
                {{-- @include('layouts.navigation') Assurez-vous que ce chemin est correct --}}

    <div class="container">
        <div class="alert-container">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <div class="card">
            <div class="card-header">
                Gérer les Rôles de l'Utilisateur : {{ $user->name }}
            </div>
            <div class="card-body">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nom de l'utilisateur:</label>
                        <input type="text" class="form-control" id="name" value="{{ $user->name }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rôles :</label>
                        @forelse ($roles as $role)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}" id="role_{{ $role->id }}"
                                    {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                <label class="form-check-label" for="role_{{ $role->id }}">
                                    {{ $role->name }}
                                </label>
                            </div>
                        @empty
                            <p>Aucun rôle disponible. Veuillez créer des rôles d'abord.</p>
                        @endforelse
                    </div>

                    <button type="submit" class="btn btn-primary">Mettre à jour les Rôles</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Annuler</a>
                </form>
            </div>
        </div>
    </div>

        </div>
    </div>
@endsection