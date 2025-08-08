

@extends('layouts.app')

@section('title','edit')

@section('content')
<div class="content flex-grow-1">
        <div class="container-fluid">
         
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h2>{{ __('Informations du Profil') }}</h2>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h2>{{ __('Mettre à Jour le Mot de Passe') }}</h2>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2>{{ __('Supprimer le Compte') }}</h2>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>

        </div>
    </div>
@endsection