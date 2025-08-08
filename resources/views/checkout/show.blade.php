@extends('layouts.app')

@section('title', 'Reçu de Paiement')

@section('content')
<div class="content flex-grow-1">
    <div class="container-fluid">
        <div class="container mt-4">
            <h1 class="text-center mb-4 text-success">Reçu de Paiement</h1>

            <div class="card mb-4" id="receipt-card">
                <div class="card-header bg-success text-white text-center">
                    <h4>Pressing</h4>
                    <p>Reçu de Commande #{{ $order->id }}</p>
                </div>
                <div class="card-body">
                    <p><strong>Client :</strong> {{ $order->client->name }}</p>
                    {{-- Correction de l'erreur ici : on utilise le null-safe operator --}}
                    <p><strong>Date de paiement :</strong> {{ $order->paid_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
                    <p><strong>Payé par :</strong> {{ $order->paidByUser->name ?? 'N/A' }}</p>
                    <hr>
                    <h5>Détails des services :</h5>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Quantité</th>
                                <th class="text-end">Prix unitaire</th>
                                <th class="text-end">Sous-total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->services as $service)
                                <tr>
                                    <td>{{ $service->name }}</td>
                                    <td>{{ $service->pivot->quantity }}</td>
                                    <td class="text-end">{{ number_format($service->pivot->price_at_order, 2, ',', ' ') }} FCFA</td>
                                    <td class="text-end">{{ number_format($service->pivot->quantity * $service->pivot->price_at_order, 2, ',', ' ') }} FCFA</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Montant Total :</th>
                                <th class="text-end">{{ number_format($order->total_amount, 2, ',', ' ') }} FCFA</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer d-print-none text-center">
                    <button onclick="window.print()" class="btn btn-success"><i class="fas fa-print"></i> Imprimer le reçu</button>
                    <a href="{{ route('checkout.index') }}" class="btn btn-secondary ms-2"><i class="fas fa-arrow-left"></i> Retour à la Caisse</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .content, .content * {
            visibility: hidden;
        }
        #receipt-card, #receipt-card * {
            visibility: visible;
        }
        #receipt-card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 20px;
        }
        /* Cache les boutons spécifiquement pour l'impression */
        .d-print-none {
            display: none !important;
        }
        .table-striped > tbody > tr:nth-child(odd) > td,
        .table-striped > tbody > tr:nth-child(odd) > th {
            background-color: #f2f2f2;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #dee2e6;
        }
    }
</style>
@endsection
