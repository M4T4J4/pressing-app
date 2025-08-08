<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Pressing App</title>

    <link href="{{ asset('assets/lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/lib/fontawesome/css/all.min.css') }}" rel="stylesheet">

    @vite('resources/js/app.js')

    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #007bff; /* Couleur primaire de Bootstrap */
        }
        .navbar-brand, .nav-link {
            color: white !important;
        }
        .sidebar {
            background-color: #343a40; /* Gris foncé pour la sidebar */
            color: white;
            padding-top: 20px;
            height: 100vh; /* Prend toute la hauteur de la vue */
            position: fixed;
            top: 0;
            left: 0;
            width: 250px; /* Largeur de la sidebar */
            overflow-y: auto; /* Permet le défilement si le contenu est trop long */
        }
        .sidebar .nav-link {
            color: #adb5bd; /* Couleur des liens de la sidebar */
            padding: 10px 15px;
            display: block;
        }
        .sidebar .nav-link:hover {
            background-color: #007bff;
            color: white;
        }
        .content {
            margin-left: 250px; /* Décaler le contenu de la largeur de la sidebar */
            padding: 20px;
        }
        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            border-bottom: none;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }
        /* Pour les petits écrans, la sidebar se cache */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }
            .content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand me-auto" href="{{ route('dashboard') }}">Pressing App</a>

            <div class="collapse navbar-collapse" id="sidebarMenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link text-white">
                                <i class="fas fa-sign-out-alt"></i> Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex">
        <div class="sidebar collapse d-md-block" id="sidebarMenu">
            <h4 class="text-white text-center mb-4">Navigation</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i> Tableau de Bord
                    </a>
                </li>

                {{-- Liens accessibles aux employés et aux administrateurs --}}
                @canany(['manage orders', 'manage clients', 'manage caisse'])
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('orders.index') }}">
                            <i class="fas fa-clipboard-list"></i> Gérer les Commandes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('clients.index') }}">
                            <i class="fas fa-users"></i> Gérer les Clients
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('checkout.index') }}">
                            <i class="fas fa-cash-register"></i> Caisse
                        </a>
                    </li>
                @endcanany

                {{-- Liens accessibles uniquement aux administrateurs --}}
                @can('manage users')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users.index') }}">
                            <i class="fas fa-user-cog"></i> Gérer les Utilisateurs
                        </a>
                    </li>
                @endcan

                @can('manage activity')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('activity-log.index') }}">
                            <i class="fas fa-history"></i> Journal d'Activité
                        </a>
                    </li>
                @endcan

                @can('manage services')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('services.index') }}">
                            <i class="fas fa-tshirt"></i> Gérer les Services
                        </a>
                    </li>
                @endcan

                @can('manage expenses')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('expenses.index') }}">
                            <i class="fas fa-coins"></i> Gérer les Dépenses
                        </a>
                    </li>
                @endcan

                @can('manage history')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('history.index') }}">
                            <i class="fas fa-trash-alt"></i> Purger l'Historique
                        </a>
                    </li>
                @endcan
            </ul>
        </div>

        {{-- contenu --}}
        @yield('content')
    </div>
    <script src="{{ asset('assets/lib/bootstrap/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/lib/fontawesome/js/all.min.js') }}"></script>
</body>
</html>
