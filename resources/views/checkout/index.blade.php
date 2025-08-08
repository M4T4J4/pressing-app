@extends('layouts.app')

@section('title', 'Caisse - Pressing App')

@section('content')
    <div class="content flex-grow-1">
        <div class="container-fluid">
            <div class="container mt-4">
                <h1 class="text-center mb-4 text-primary">Caisse</h1>

                {{-- Affichage des messages de session --}}
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="row">
                    {{-- Colonne de gauche : Recherche et dernières commandes --}}
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">Rechercher une Commande</div>
                            <div class="card-body">
                                <form action="{{ route('checkout.index') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" name="order_id" class="form-control" placeholder="Entrez le numéro de la commande..." value="{{ request('order_id') }}">
                                        <button class="btn btn-primary" type="submit">Rechercher</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">Dernières commandes en attente de paiement</div>
                            <div class="card-body">
                                @if ($recentOrders->isEmpty())
                                    <p class="text-muted text-center">Aucune commande en attente de paiement.</p>
                                @else
                                    <ul class="list-group list-group-flush">
                                        @foreach ($recentOrders as $recentOrder)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>Commande #{{ $recentOrder->id }}</strong>
                                                    <small class="d-block text-muted">
                                                        Client : {{ $recentOrder->client->name }}
                                                    </small>
                                                </div>
                                                <a href="{{ route('checkout.index', ['order_id' => $recentOrder->id]) }}" class="btn btn-sm btn-info">
                                                    Sélectionner
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Colonne de droite : Détails de la commande --}}
                    <div class="col-md-6">
                        @if ($orders->isNotEmpty())
                            @php
                                $order = $orders->first();
                            @endphp
                            <div class="card">
                                <div class="card-header bg-success text-white">Détails de la Commande #{{ $order->id }}</div>
                                <div class="card-body">
                                    <p><strong>Client :</strong> {{ $order->client->name }}</p>
                                    <p><strong>Statut :</strong> {{ $order->status }}</p>
                                    <p><strong>Date de prise en charge :</strong> {{ $order->pickup_date?->format('d/m/Y H:i') ?? 'Non spécifiée' }}</p>
                                    <p><strong>Date de livraison prévue :</strong> {{ $order->delivery_date?->format('d/m/Y H:i') ?? 'Non spécifiée' }}</p>

                                    <hr>

                                    <h5>Services</h5>
                                    <ul class="list-group mb-3">
                                        @foreach ($order->services as $service)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                {{ $service->pivot->quantity }} x {{ $service->name }}
                                                <span class="badge bg-primary rounded-pill">{{ number_format($service->pivot->quantity * $service->price, 2, ',', ' ') }} FCFA</span>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <h4 class="fw-bold">Montant Total : <span class="text-success">{{ number_format($order->total_amount, 2, ',', ' ') }} FCFA</span></h4>

                                    @if ($order->paid)
                                        <div class="alert alert-success">Cette commande a déjà été payée.</div>
                                    @else
                                        <form action="{{ route('checkout.pay', $order->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="payment_method" class="form-label">Méthode de paiement</label>
                                                <select name="payment_method" id="payment_method" class="form-control">
                                                    <option value="Espèces">Espèces</option>
                                                    <option value="Mobile Money">Mobile Money</option>
                                                    <option value="Carte">Carte</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-success w-100">Encaisser le paiement</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info">Veuillez rechercher ou sélectionner une commande pour afficher les détails.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
