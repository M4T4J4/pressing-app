@extends('layouts.app')

@section('title', 'Nouveau service')

@section('content')

    <div class="content flex-grow-1">
        <div class="container-fluid">


            {{-- Lien de retour vers la liste des services --}}
            <a href="{{ route('services.index') }}" class="btn btn-secondary mb-4">Retour à la liste des services</a>

            <h1 class="text-center mb-4 text-primary">Ajouter un Nouveau Service</h1>

            {{-- Message de succès si le service a été ajouté --}}
            {{-- @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif --}}

            {{-- Affichage des erreurs de validation --}}
            @if ($errors->any())
                <div class="alert alert-danger mb-4" role="alert">
                    <h4 class="alert-heading">Oups !</h4>
                    <p>Il y a des erreurs dans votre formulaire :</p>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('services.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf {{-- C'est très important pour la sécurité dans Laravel ! --}}

                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Nom du Service :</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-bold">Description :</label>
                    <textarea id="description" name="description" value="{{ old('name') }}"
                        class="form-control @error('description') is-invalid @enderror"></textarea>
                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label fw-bold">Prix (en FCFA, optionnel) :</label>
                    <input type="number" id="price" name="price" step="0.01" value="{{ old('price') }}"
                        class="form-control @error('price') is-invalid @enderror">
                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Ajouter le Service</button>
                </div>
            </form>
        </div>
    </div>

@endsection
