@extends('layouts.app')

@section('title', 'Éditer la Commande #' . $order->id . ' - ' . config('app.name'))

@section('content')
    <div class="container mt-4">
        <a href="{{ route('orders.index') }}" class="btn btn-secondary mb-4">Retour à la liste des commandes</a>

        <h1 class="text-center mb-4 text-primary">Éditer la Commande #{{ $order->id }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger mb-4" role="alert">
                <h4 class="alert-heading">Oups !</h4>
                <p>Il y a des erreurs dans votre formulaire :</p>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('orders.update', $order->id) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="client_id" class="form-label fw-bold">Client :</label>
                <select id="client_id" name="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                    <option value="">Sélectionner un client</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}" {{ (old('client_id', $order->client_id) == $client->id) ? 'selected' : '' }}>
                            {{ $client->name }} ({{ $client->phone ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
                @error('client_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="status" class="form-label fw-bold">Statut :</label>
                <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                    @php
                        $statuses = ['En attente', 'En cours', 'Prête', 'Terminée', 'Annulée'];
                    @endphp
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" {{ (old('status', $order->status) == $status) ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="pickup_date" class="form-label fw-bold">Date et heure de prise en charge :</label>
                <input type="datetime-local" id="pickup_date" name="pickup_date" value="{{ old('pickup_date', $order->pickup_date ? $order->pickup_date->format('Y-m-d\TH:i') : '') }}"
                       class="form-control @error('pickup_date') is-invalid @enderror">
                @error('pickup_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="delivery_date" class="form-label fw-bold">Date et heure de livraison prévue :</label>
                <input type="datetime-local" id="delivery_date" name="delivery_date" value="{{ old('delivery_date', $order->delivery_date ? $order->delivery_date->format('Y-m-d\TH:i') : '') }}"
                       class="form-control @error('delivery_date') is-invalid @enderror">
                @error('delivery_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label fw-bold">Notes (optionnel) :</label>
                <textarea id="notes" name="notes" rows="3"
                          class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $order->notes) }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <hr class="my-4">
            <h3 class="mb-3 text-info">Services de la commande</h3>
            <div id="services-container">
                {{-- Les services existants de la commande --}}
                @if (old('services'))
                    @foreach (old('services') as $index => $oldService)
                        @include('orders._service_item', ['index' => $index, 'services' => $services, 'selectedServiceId' => $oldService['service_id'], 'quantity' => $oldService['quantity']])
                    @endforeach
                @elseif (count($orderServices) > 0)
                    @foreach ($orderServices as $index => $item)
                        @include('orders._service_item', ['index' => $index, 'services' => $services, 'selectedServiceId' => $item['service_id'], 'quantity' => $item['quantity']])
                    @endforeach
                @else
                    {{-- Si pas de services et pas de old input, ajouter une ligne vide --}}
                    @include('orders._service_item', ['index' => 0, 'services' => $services])
                @endif
            </div>
            <button type="button" id="add-service-btn" class="btn btn-outline-primary btn-sm mb-4">Ajouter un service</button>
            @error('services')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
            @error('services.*.service_id')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
            @error('services.*.quantity')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary btn-lg">Mettre à jour la Commande</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let serviceIndex = {{ old('services') ? count(old('services')) : (count($orderServices) > 0 ? count($orderServices) : 1) }};

            document.getElementById('add-service-btn').addEventListener('click', function () {
                addServiceRow();
            });

            function addServiceRow(selectedServiceId = '', quantity = '') {
                const container = document.getElementById('services-container');
                const newRow = document.createElement('div');
                newRow.classList.add('row', 'mb-3', 'align-items-end', 'service-item');
                newRow.innerHTML = `
                    <div class="col-md-6">
                        <label for="service_${serviceIndex}_id" class="form-label">Service :</label>
                        <select id="service_${serviceIndex}_id" name="services[${serviceIndex}][service_id]" class="form-select" required>
                            <option value="">Sélectionner un service</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" ${selectedServiceId == {{ $service->id }} ? 'selected' : ''}>
                                    {{ $service->name }} ({{ number_format($service->price, 2, ',', ' ') }} FCFA)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="service_${serviceIndex}_quantity" class="form-label">Quantité :</label>
                        <input type="number" id="service_${serviceIndex}_quantity" name="services[${serviceIndex}][quantity]" value="${quantity || 1}" min="1" class="form-control" required>
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="button" class="btn btn-danger remove-service-btn">Supprimer</button>
                    </div>
                `;
                container.appendChild(newRow);
                serviceIndex++;
                attachRemoveListeners();
            }

            function attachRemoveListeners() {
                document.querySelectorAll('.remove-service-btn').forEach(button => {
                    button.onclick = function () {
                        this.closest('.service-item').remove();
                    };
                });
            }

            attachRemoveListeners(); // Pour les éléments existants au chargement

            // Si des erreurs de validation ont ramené des vieux inputs, s'assurer qu'ils ont des listeners
            @if (old('services'))
                document.querySelectorAll('.service-item').forEach(item => {
                    const select = item.querySelector('select');
                    const input = item.querySelector('input[type="number"]');
                    if (select && input) {
                        const serviceId = select.getAttribute('data-old-service-id');
                        const quantity = input.getAttribute('data-old-quantity');
                        if (serviceId) select.value = serviceId;
                        if (quantity) input.value = quantity;
                    }
                });
            @endif
        });
    </script>
@endsection