

@extends('layouts.app')

@section('title','edit')

@section('content')
<div class="content flex-grow-1">
        <div class="container-fluid">
            <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gestion des Dépenses</h2>
            <a href="{{ route('expenses.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter une Dépense
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header">Liste des Dépenses</div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Montant</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expenses as $expense)
                            <tr>
                                <td>{{ $expense->description }}</td>
                                <td>{{ number_format($expense->amount, 2, ',', ' ') }} FCFA</td>
                                <td>{{ $expense->expense_date }}</td>
                                <td>
                                    <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette dépense ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Aucune dépense enregistrée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
        </div>
    </div>
@endsection