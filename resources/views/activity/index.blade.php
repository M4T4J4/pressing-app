@extends('layouts.app')

@section('title', 'Journal d\'Activité')

@section('content')
<div class="content flex-grow-1">
    <div class="container-fluid">
        <div class="container mt-4">
            <h1 class="text-center mb-4 text-primary">Journal d'Activité</h1>

            <div class="card">
                <div class="card-header bg-info text-white">
                    Historique des actions
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Date & Heure</th>
                                    <th>Utilisateur</th>
                                    <th>Action</th>
                                    <th>Objet</th>
                                    <th>Détails</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($activities as $activity)
                                    <tr>
                                        <td>{{ $activity->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td>{{ $activity->causer->name ?? 'Système' }}</td>
                                        <td>{{ $activity->description }}</td>
                                        <td>
                                            @if ($activity->subject)
                                                {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if ($activity->properties->count() > 0)
                                                @foreach ($activity->properties as $key => $value)
                                                    @if ($key === 'attributes')
                                                        @foreach ($value as $attrKey => $attrValue)
                                                            <strong>{{ ucfirst($attrKey) }}</strong>: {{ $attrValue }}<br>
                                                        @endforeach
                                                    @elseif ($key === 'old')
                                                        @endif
                                                @endforeach
                                            @else
                                                Aucun détail
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Aucune activité enregistrée.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center">
                        {{ $activities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection