@extends('layouts.app')


@section('content')

    <div class="content flex-grow-1">
        <div class="container-fluid">
            <div class="container mt-5">
                <a href="{{ route('clients.index') }}" class="btn btn-secondary mb-4">Retour à la liste des clients</a>

                <h1 class="text-center mb-4 text-primary">Éditer le Client : {{ $client->name }}</h1>

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

                <form action="{{ route('clients.update', $client->id) }}" method="POST" class="needs-validation"
                    novalidate>
                    @csrf
                    @method('PUT') {{-- Important : simule une requête PUT pour l'update --}}

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nom du client :</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $client->name) }}"
                            required class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label fw-bold">Téléphone :</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $client->phone) }}"
                            class="form-control @error('phone') is-invalid @enderror">
                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label fw-bold">Adresse :</label>
                        <textarea id="address" name="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $client->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email :</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $client->email) }}"
                            class="form-control @error('email') is-invalid @enderror">
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Mettre à jour le Client</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@endsection
