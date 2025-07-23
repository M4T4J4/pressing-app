@extends('layouts.app')

@section('title', 'Gestion des Commandes - ' . config('app.name'))

@section('content')
    <div class="container mt-4">
        <h1 class="text-center mb-4 text-primary">Gestion des Commandes</h1>

        <div class="d-grid gap-2 col-6 mx-auto mb-4">
            <a href="{{ route('orders.create') }}" class="btn btn-success btn-lg">Créer une Nouvelle Commande</a>
        </div>

        @if ($orders->isEmpty())
            <p class="text-center text-secondary fs-5">Aucune commande enregistrée pour le moment.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Commande</th>
                            <th>Client</th>
                            <th>Statut</th>
                            <th>Date Prise en charge</th>
                            <th>Date Livraison</th>
                            <th>Total</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->client->name ?? 'Client Inconnu' }}</td>
                                <td>
                                    <span class="badge {{
                                        $order->status == 'En attente' ? 'bg-secondary' :
                                        ($order->status == 'En cours' ? 'bg-info' :
                                        ($order->status == 'Prête' ? 'bg-warning text-dark' :
                                        ($order->status == 'Terminée' ? 'bg-success' :
                                        ($order->status == 'Annulée' ? 'bg-danger' : 'bg-light text-dark'))))
                                    }}">{{ $order->status }}</span>
                                </td>
                                <td>{{ $order->pickup_date ? $order->pickup_date->format('d/m/Y H:i') : 'N/A' }}</td>
                                <td>{{ $order->delivery_date ? $order->delivery_date->format('d/m/Y H:i') : 'N/A' }}</td>
                                <td>{{ number_format($order->total_amount, 2, ',', ' ') }} FCFA</td>
                                <td class="text-center">
                                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning btn-sm me-2">Éditer</a>
                                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?');">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection