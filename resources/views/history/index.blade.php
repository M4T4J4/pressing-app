

@extends('layouts.app')

@section('title','edit')

@section('content')
<div class="content flex-grow-1">
        <div class="container-fluid">
    <div class="container">
        <h2 class="mb-4">Gestion de l'Historique</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h4>Purger les données historiques</h4>
            </div>
            <div class="card-body">
                <p>Cette action est irréversible. Les commandes terminées, annulées et les dépenses seront supprimées de façon permanente.</p>
                <form action="{{ route('history.clear') }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir purger l\'historique ? Cette action est irréversible.');">
                    @csrf
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="delete_option" id="delete_all" value="all" checked>
                        <label class="form-check-label" for="delete_all">
                            Supprimer toutes les commandes terminées/annulées et toutes les dépenses.
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="delete_option" id="delete_older_than" value="older_than">
                        <label class="form-check-label" for="delete_older_than">
                            Supprimer les données plus anciennes que :
                        </label>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <input type="number" name="months" class="form-control" placeholder="Nombre de mois" min="1">
                            </div>
                            <div class="col-md-9">
                                <span class="text-muted">mois.</span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger mt-3">Purger l'Historique</button>
                </form>
            </div>
        </div>
    </div>
        </div>
    </div>
@endsection