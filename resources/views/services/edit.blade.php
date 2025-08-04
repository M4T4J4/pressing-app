@extends('layouts.app')

@section('content')

    <div class="content flex-grow-1">
        <div class="container-fluid">
            <div class="container mt-5">
                <a href="{{ route('services.index') }}" class="btn btn-secondary mb-4">Retour à la liste des services</a>

                <h1 class="text-center mb-4 text-primary">Éditer le Service : {{ $service->name }}</h1>

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

                <form action="{{ route('services.update', $service->id) }}" method="POST" class="needs-validation"
                    novalidate>
                    @csrf
                    @method('PUT') {{-- Important : simule une requête PUT pour l'update --}}

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nom du service :</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $service->name) }}"
                            required class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Description :</label>
                        <textarea id="description" name="description" rows="3"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description', $service->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label fw-bold">Prix (en FCFA, optionnel) :</label>
                        <input type="number" id="price" name="price" step="0.01"
                            value="{{ old('price', $service->price) }}"
                            class="form-control @error('price') is-invalid @enderror">
                        @error('price')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Mettre à jour le Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
