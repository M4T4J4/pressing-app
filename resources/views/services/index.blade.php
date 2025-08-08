@extends('layouts.app')

@section('content')
    <div class="content flex-grow-1">
        <div class="container-fluid">
            <div class="container mt-5">
                {{-- Titre principal de la page --}}
                <h1 class="text-center mb-4 text-primary">Découvrez Nos Services de Pressing</h1>

                {{-- Bouton d'ajout de service (visible uniquement pour les utilisateurs autorisés) --}}
                @can('manage services')
                    <div class="d-grid gap-2 col-6 mx-auto mb-4">
                        <a href="{{ route('services.create') }}" class="btn btn-success btn-lg">
                            <i class="fas fa-plus-circle me-2"></i> Ajouter un Nouveau Service
                        </a>
                    </div>
                @endcan

                {{-- Message de succès affiché après une opération réussie --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Boucle pour afficher chaque service sous forme de carte --}}
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @forelse ($services as $service)
                        <div class="col">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body d-flex flex-column">
                                    <h2 class="card-title text-info">{{ $service->name }}</h2>
                                    <p class="card-text text-muted flex-grow-1">{{ $service->description }}</p>
                                    <div class="mt-auto text-end">
                                        @if ($service->price)
                                            <span
                                                class="fw-bold text-danger fs-5">{{ number_format($service->price, 2, ',', ' ') }}
                                                FCFA</span>
                                        @else
                                            <span class="text-secondary fs-6">Sur devis</span>
                                        @endif
                                        
                                        {{-- Boutons d'action (visibles uniquement pour les utilisateurs autorisés) --}}
                                        @can('manage services')
                                        <div class="mt-3">
                                            {{-- Bouton Éditer --}}
                                            <a href="{{ route('services.edit', $service->id) }}"
                                                class="btn btn-warning btn-sm me-2">
                                                <i class="fas fa-edit"></i> Éditer
                                            </a>

                                            {{-- Bouton Supprimer (déclenche la modale de confirmation) --}}
                                            <button type="button" class="btn btn-danger btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#deleteServiceModal"
                                                data-service-id="{{ $service->id }}">
                                                <i class="fas fa-trash-alt"></i> Supprimer
                                            </button>
                                        </div>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        {{-- Message si aucun service n'est disponible --}}
                        <div class="col-12">
                            <p class="text-center text-secondary fs-5">Aucun service n'est disponible pour le moment.
                                Veuillez en ajouter un pour commencer.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Modale de Confirmation de Suppression --}}
    <div class="modal fade" id="deleteServiceModal" tabindex="-1" aria-labelledby="deleteServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteServiceModalLabel">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Êtes-vous sûr de vouloir supprimer ce service ? Cette action est irréversible.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form id="deleteForm" action="" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script pour gérer l'action de la modale de suppression --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteModal = document.getElementById('deleteServiceModal');
            if (deleteModal) {
                deleteModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget; // Bouton qui a déclenché la modale
                    const serviceId = button.getAttribute('data-service-id');
                    const form = document.getElementById('deleteForm');

                    // Mettre à jour l'action du formulaire avec l'ID du service
                    form.action = `/services/${serviceId}`;
                });
            }
        });
    </script>
@endsection
