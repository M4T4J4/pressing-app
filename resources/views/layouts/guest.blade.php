<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Liens CSS Bootstrap OFFLINE --}}
    <link href="{{ asset('assets/lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    {{-- Custom CSS si vous en avez un pour centrer le formulaire ou autres --}}
    <style>
        body {
            background-color: #f8f9fa; /* Couleur de fond Bootstrap light */
        }
        .min-h-screen {
            min-height: 100vh;
        }
        .flex-column {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .w-full {
            width: 100%;
        }
        .sm\:max-w-md {
            max-width: 28rem; /* Environ 448px, équivalent à sm:max-w-md de Tailwind */
        }
        .mt-6 {
            margin-top: 1.5rem; /* Équivalent à mt-6 de Tailwind */
        }
        .px-6 {
            padding-left: 1.5rem; /* Équivalent à px-6 de Tailwind */
            padding-right: 1.5rem;
        }
        .py-4 {
            padding-top: 1rem; /* Équivalent à py-4 de Tailwind */
            padding-bottom: 1rem;
        }
        .bg-white {
            background-color: #ffffff;
        }
        .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); /* Équivalent à shadow-md */
        }
        .overflow-hidden {
            overflow: hidden;
        }
        .sm\:rounded-lg {
            border-radius: 0.5rem; /* Équivalent à rounded-lg */
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex-column pt-6 sm:pt-0 bg-gray-100">
        <div>
            <a href="/">
                <h2 class="text-primary">{{ config('app.name', 'PressingApp') }}</h2>
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{-- CETTE LIGNE EST CRUCIALE ! --}}
            {{ $slot }} 
        </div>
    </div>

    {{-- Scripts JavaScript Bootstrap OFFLINE --}}
    <script src="{{ asset('assets/lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>