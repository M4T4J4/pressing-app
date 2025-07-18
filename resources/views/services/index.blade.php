<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Services de Pressing - {{ config('app.name') }}</title>
    <link href="{{ asset('app.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center mb-4 text-primary">Découvrez Nos Services de Pressing</h1>

        <div class="d-grid gap-2 col-6 mx-auto mb-4">
            <a href="{{ route('services.create') }}" class="btn btn-success btn-lg">Ajouter un Nouveau Service</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @forelse ($services as $service)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h2 class="card-title text-info">{{ $service->name }}</h2>
                            <p class="card-text text-muted flex-grow-1">{{ $service->description }}</p>
                            <div class="mt-auto text-end">
                                @if ($service->price)
                                    <span class="fw-bold text-danger fs-5">{{ number_format($service->price, 2, ',', ' ') }} FCFA</span>
                                @else
                                    <span class="text-secondary fs-6">Sur devis</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center text-secondary fs-5">Aucun service n'est disponible pour le moment. Veuillez revenir plus tard !</p>
                </div>
            @endforelse
        </div>
    </div>

    <script src="{{ asset('./lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>