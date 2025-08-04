

@extends('layouts.app')

@section('title','edit')

@section('content')
<div class="content flex-grow-1">
        <div class="container-fluid">
            <div class="container">
        <h2>Journal d'Activité</h2>
        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
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
                                <td>{{ $activity->created_at->format('d/m/Y H:i') }}</td>
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
                                    @if ($activity->changes)
                                        @foreach ($activity->changes['attributes'] ?? [] as $key => $value)
                                            <strong>{{ $key }}</strong>: {{ $value }}<br>
                                        @endforeach
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
                {{ $activities->links() }}
            </div>
        </div>
    </div>
        </div>
    </div>
@endsection