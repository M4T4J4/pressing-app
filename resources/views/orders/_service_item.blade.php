{{-- Ce fichier est inclus par create.blade.php et edit.blade.php --}}
<div class="row mb-3 align-items-end service-item">
    <div class="col-md-6">
        <label for="service_{{ $index }}_id" class="form-label">Service :</label>
        <select id="service_{{ $index }}_id" name="services[{{ $index }}][service_id]" class="form-select @error('services.' . $index . '.service_id') is-invalid @enderror" required
                data-old-service-id="{{ old('services.' . $index . '.service_id', $selectedServiceId ?? '') }}">
            <option value="">Sélectionner un service</option>
            @foreach ($services as $service)
                <option value="{{ $service->id }}" {{ (old('services.' . $index . '.service_id', $selectedServiceId ?? '') == $service->id) ? 'selected' : '' }}>
                    {{ $service->name }} ({{ number_format($service->price, 2, ',', ' ') }} FCFA)
                </option>
            @endforeach
        </select>
        @error('services.' . $index . '.service_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="service_{{ $index }}_quantity" class="form-label">Quantité :</label>
        <input type="number" id="service_{{ $index }}_quantity" name="services[{{ $index }}][quantity]" value="{{ old('services.' . $index . '.quantity', $quantity ?? 1) }}" min="1" class="form-control @error('services.' . $index . '.quantity') is-invalid @enderror" required
               data-old-quantity="{{ old('services.' . $index . '.quantity', $quantity ?? 1) }}">
        @error('services.' . $index . '.quantity')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-2 text-end">
        <button type="button" class="btn btn-danger remove-service-btn">Supprimer</button>
    </div>
</div>