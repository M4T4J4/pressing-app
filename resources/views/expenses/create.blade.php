

@extends('layouts.app')

@section('title','edit')

@section('content')
<div class="content flex-grow-1">
        <div class="container-fluid">
                <div class="container">
        <h2>Ajouter une Dépense</h2>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('expenses.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="description" name="description" value="{{ old('description') }}" required>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Montant (FCFA)</label>
                        <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" required>
                        @error('amount')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="expense_date" class="form-label">Date de la dépense</label>
                        <input type="date" class="form-control" id="expense_date" name="expense_date" value="{{ old('expense_date', now()->toDateString()) }}">
                        @error('expense_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer la Dépense</button>
                    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Annuler</a>
                </form>
            </div>
        </div>
    </div>
        </div>
    </div>
@endsection