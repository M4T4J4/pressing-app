@extends('layouts.app')

@section('title', 'Gestion des Commandes - ' . config('app.name'))

@section('content')
    <div class="content flex-grow-1">
        <div class="container-fluid">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Gestion des Commandes</h2>
                    <a href="{{ route('orders.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Ajouter une Commande
                    </a>
                </div>

                {{-- Boutons de filtre --}}
                <div class="mb-4">
                    <a href="{{ route('orders.index', ['status' => 'En attente']) }}" class="btn btn-sm {{ $status === 'En attente' ? 'btn-primary' : 'btn-secondary' }}">
                        En attente
                    </a>
                    <a href="{{ route('orders.index', ['status' => 'Prête']) }}" class="btn btn-sm {{ $status === 'Prête' ? 'btn-primary' : 'btn-secondary' }}">
                        Prête
                    </a>
                    <a href="{{ route('orders.index', ['status' => 'Terminée']) }}" class="btn btn-sm {{ $status === 'Terminée' || $status === 'Annulée' ? 'btn-primary' : 'btn-secondary' }}">
                        Terminée / Annulée
                    </a>
                    <a href="{{ route('orders.index', ['status' => 'all']) }}" class="btn btn-sm {{ $status === 'all' ? 'btn-primary' : 'btn-secondary' }}">
                        Toutes
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- Tableau des commandes --}}
                <div class="card">
                    <div class="card-header bg-primary text-white">Liste des commandes</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Client</th>
                                        <th>Services</th>
                                        <th>Total</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->client->name ?? 'N/A' }}</td>
                                            <td>
                                                @foreach ($order->services as $service)
                                                    <span class="badge bg-secondary">{{ $service->pivot->quantity }}x {{ $service->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>{{ number_format($order->total_amount, 2, ',', ' ') }} FCFA</td>
                                            <td>
                                                {{-- Affichage du statut avec le bon badge --}}
                                                @if($order->status === 'En attente')
                                                    <span class="badge bg-warning text-dark">{{ $order->status }}</span>
                                                @elseif($order->status === 'Prête')
                                                    <span class="badge bg-info text-dark">{{ $order->status }}</span>
                                                @elseif($order->status === 'Terminée')
                                                    <span class="badge bg-success">{{ $order->status }}</span>
                                                @elseif($order->status === 'Annulée')
                                                    <span class="badge bg-danger">{{ $order->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-warning me-2" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Aucune commande trouvée.</td>
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