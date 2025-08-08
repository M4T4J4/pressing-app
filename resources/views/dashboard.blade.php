@extends('layouts.app')

@section('content')
    <div class="content flex-grow-1">
        <div class="container-fluid">
          <div class="container-fluid">
        @role('admin')
            <h1 class="h3 mb-4 text-gray-800">Tableau de Bord Administrateur</h1>

            {{-- Sélecteur de mois et d'année --}}
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <form action="{{ route('dashboard') }}" method="GET" class="d-flex align-items-center">
                                <h5 class="me-3 mb-0">Statistiques par mois :</h5>
                                <select name="month" class="form-control me-2" style="width: auto;">
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                            {{ Carbon\Carbon::createFromDate(null, $m, null)->locale('fr')->monthName }}
                                        </option>
                                    @endforeach
                                </select>
                                <select name="year" class="form-control me-2" style="width: auto;">
                                    @foreach(range(now()->year, 2020) as $y)
                                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary">Filtrer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cartes de totaux mensuels --}}
            <div class="row">
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col me-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Revenus du mois
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($monthlyRevenue, 2, ',', ' ') }} FCFA</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col me-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Dépenses du mois
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($monthlyExpenses, 2, ',', ' ') }} FCFA</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col me-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Bénéfice du mois
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($monthlyProfit, 2, ',', ' ') }} FCFA</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Carte du graphique --}}
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-chart-line"></i> Évolution des Dépenses ({{ $selectedYear }})
                        </div>
                        <div class="card-body">
                            <canvas id="expensesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        @endrole

        @role('employee')
            <h1 class="h3 mb-4 text-gray-800">Tableau de Bord Employé</h1>
            <div class="row">
                </div>
        @endrole
    </div>
   
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('expensesChart');

            const data = {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
                datasets: [{
                    label: 'Dépenses',
                    data: @json($monthlyExpensesData),
                    backgroundColor: 'rgba(231, 74, 59, 0.5)',
                    borderColor: 'rgba(231, 74, 59, 1)',
                    borderWidth: 1
                }]
            };

            new Chart(ctx, {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
        </div>
    </div>
@endsection
