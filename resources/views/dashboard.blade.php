@extends('layouts.app')

@section('content')
    <div class="content flex-grow-1">
        <div class="container-fluid">
            <h2 class="mb-4">Bienvenue, {{ Auth::user()->name }} !</h2>

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            Messages et Notifications
                        </div>
                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <p>Ceci est votre tableau de bord. Utilisez le menu latéral pour naviguer.</p>

                            @role('admin')
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Vous êtes connecté en tant qu'administrateur. Vous avez
                                    accès à toutes les fonctionnalités de gestion.
                                </div>
                            @else
                                <div class="alert alert-secondary">
                                    <i class="fas fa-info-circle"></i> Bienvenue ! Votre rôle actuel ne vous donne pas accès à
                                    des fonctionnalités de gestion spécifiques.
                                </div>
                            @endrole
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section Admin (Contenu Spécifique pour les Admins) --}}
            @role('admin')
             {{-- Section des statistiques clés --}}
<div class="row mb-4">
    {{-- Carte : Nombre de Clients --}}
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">
                <i class="fas fa-users"></i> Clients Enregistrés
            </div>
            <div class="card-body">
                <h1 class="card-title">{{ $totalClients }}</h1>
                <p class="card-text">Nombre total de clients dans votre base de données.</p>
            </div>
        </div>
    </div>

    {{-- Carte : Revenu Total des Commandes --}}
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">
                <i class="fas fa-money-bill-wave"></i> Revenu Total des Commandes
            </div>
            <div class="card-body">
                <h1 class="card-title">{{ number_format($totalRevenue, 2, ',', ' ') }} FCFA</h1>
                <p class="card-text">Somme de tous les montants des commandes.</p>
            </div>
        </div>
    </div>

    {{-- Carte : Nombre Total de Commandes --}}
    <div class="col-md-4">
        <div class="card text-white bg-info mb-3">
            <div class="card-header">
                <i class="fas fa-clipboard-list"></i> Commandes Totales
            </div>
            <div class="card-body">
                <h1 class="card-title">{{ $totalOrders }}</h1>
                <p class="card-text">Nombre total de commandes enregistrées.</p>
            </div>
        </div>
    </div>

    {{-- Carte : Commandes en Attente (Exemple) --}}
    <div class="col-md-4">
        <div class="card text-white bg-warning mb-3">
            <div class="card-header">
                <i class="fas fa-hourglass-half"></i> Commandes en Attente
            </div>
            <div class="card-body">
                <h1 class="card-title">{{ $pendingOrders }}</h1>
                <p class="card-text">Commandes dont le statut est 'En attente'.</p>
            </div>
        </div>
    </div>

    {{-- Espace pour les Dépenses (Explication) --}}
    <div class="col-md-8">
        <div class="card text-dark bg-light mb-3">
            <div class="card-header">
                <i class="fas fa-chart-line"></i> Suivi des Dépenses
            </div>
            <div class="card-body">
                <h4 class="card-title">Dépenses Effectuées : Non Disponible</h4>
                <p class="card-text">
                    Pour afficher les dépenses, vous devez d'abord créer un système de gestion des dépenses dans votre application (par exemple, un modèle `Expense` avec une table `expenses` dans la base de données).
                    Cela vous permettrait d'enregistrer et de sommer vos coûts d'opération (produits, salaires, loyer, etc.).
                </p>
                <p class="card-text">
                    Une fois implémenté, vous pourriez récupérer `Expense::sum('amount')` dans votre `DashboardController` et l'afficher ici.
                </p>
            </div>
        </div>
    </div>
</div>
        </div>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card text-center">
                            <div class="card-header">
                                <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                                <h3>Commandes</h3>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Gérez toutes les commandes de pressing.</p>
                                <a href="{{ route('orders.index') }}" class="btn btn-primary">Voir les Commandes</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card text-center">
                            <div class="card-header">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <h3>Clients</h3>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Consultez et gérez les informations des clients.</p>
                                <a href="{{ route('clients.index') }}" class="btn btn-primary">Voir les Clients</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card text-center">
                            <div class="card-header">
                                <i class="fas fa-tshirt fa-2x mb-2"></i>
                                <h3>Services</h3>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Configurez les services de votre pressing.</p>
                                <a href="{{ route('services.index') }}" class="btn btn-primary">Voir les Services</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endrole
            {{-- Section des statistiques clés --}}
<div class="row mb-4">
    {{-- Cette section est pour l'administrateur --}}
    @role('admin')
        {{-- ... Le code de vos cartes d'admin ... --}}
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">
                    <i class="fas fa-users"></i> Clients Enregistrés
                </div>
                <div class="card-body">
                    <h1 class="card-title">{{ $totalClients }}</h1>
                    <p class="card-text">Nombre total de clients dans votre base de données.</p>
                </div>
            </div>
        </div>
        {{-- ... et les autres cartes d'admin... --}}
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">
                    <i class="fas fa-clipboard-list"></i> Commandes Totales
                </div>
                <div class="card-body">
                    <h1 class="card-title">{{ $totalOrders }}</h1>
                    <p class="card-text">Nombre total de commandes enregistrées.</p>
                </div>
            </div>
        </div>

        {{-- Nouvelle carte pour les dépenses --}}
    <div class="col-md-4">
        <div class="card text-white bg-danger mb-3">
            <div class="card-header">
                <i class="fas fa-coins"></i> Dépenses Totales
            </div>
            <div class="card-body">
                <h1 class="card-title">{{ number_format($totalExpenses, 2, ',', ' ') }} FCFA</h1>
                <p class="card-text">Total des dépenses enregistrées.</p>
            </div>
        </div>
    </div>
    @endrole

    {{-- Section du récapitulatif pour l'employé --}}
    @role('employé')
        <div class="col-md-6">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">
                    <i class="fas fa-money-bill-wave"></i> Votre Contribution (Revenu)
                </div>
                <div class="card-body">
                    <h1 class="card-title">{{ number_format($employeeRevenue, 2, ',', ' ') }} FCFA</h1>
                    <p class="card-text">Revenu total généré par vos commandes.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">
                    <i class="fas fa-clipboard-check"></i> Vos Commandes Traitées
                </div>
                <div class="card-body">
                    <h1 class="card-title">{{ $employeeOrdersCount }}</h1>
                    <p class="card-text">Nombre total de commandes que vous avez traitées.</p>
                </div>
            </div>
        </div>
    @endrole
</div>

            {{-- Vous pouvez ajouter d'autres sections de contenu ici --}}
 
    </div>
@endsection
