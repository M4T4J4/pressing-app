<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Services de Pressing - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
        }

        .service-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .service-card {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 20px;
            background: #fff;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
        }

        .service-card h2 {
            color: #3498db;
            margin-top: 0;
        }

        .service-card p {
            font-size: 0.9em;
            line-height: 1.6;
            color: #666;
        }

        .service-card .price {
            font-weight: bold;
            color: #e74c3c;
            font-size: 1.1em;
            margin-top: 10px;
        }

        .service-card .price::before {
            content: "À partir de ";
        }

        .service-card .price:empty::before {
            content: "";
        }

        /* Ne pas afficher "À partir de" si pas de prix */
    </style>
</head>

<body>
    <div class="container">
        <h1>Découvrez Nos Services de Pressing</h1>

        {{-- Lien pour ajouter un nouveau service --}}
        <a href="{{ route('services.create') }}" class="nav-link">Ajouter un Nouveau Service</a>
        <div class="service-grid">
            @forelse ($services as $service)
                <div class="service-card">
                    <h2>{{ $service->name }}</h2>
                    <p>{{ $service->description }}</p>
                    @if ($service->price)
                        <div class="price">{{ number_format($service->price, 2, ',', ' ') }} FCFA</div>
                    @else
                        <div class="price">Sur devis</div>
                    @endif
                </div>
            @empty
                <p>Aucun service n'est disponible pour le moment. Veuillez revenir plus tard !</p>
            @endforelse
        </div>
    </div>
</body>

</html>
