<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Order;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = auth()->user();

        // Récupère l'année et le mois depuis la requête, par défaut l'année et le mois en cours
        $selectedYear = $request->input('year', now()->year);
        $selectedMonth = $request->input('month', now()->month);

        // Statistiques globales (pour l'administrateur)
        $totalClients = Client::count();
        $totalRevenue = Order::sum('total_amount');
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'En attente')->count();
        $totalExpenses = Expense::sum('amount');

        // NOUVEAU CODE : Statistiques pour le mois sélectionné
        $monthlyRevenue = Order::whereYear('created_at', $selectedYear)
                                ->whereMonth('created_at', $selectedMonth)
                                ->sum('total_amount');

        $monthlyExpenses = Expense::whereYear('expense_date', $selectedYear)
                                ->whereMonth('expense_date', $selectedMonth)
                                ->sum('amount');

        // Calcul du bénéfice pour le mois
        $monthlyProfit = $monthlyRevenue - $monthlyExpenses;

        // Ancien code pour le graphique, mis à jour pour être basé sur l'année sélectionnée
        $monthlyExpensesGraphData = Expense::select(
                DB::raw('MONTH(expense_date) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->whereYear('expense_date', $selectedYear)
            ->groupBy(DB::raw('MONTH(expense_date)'))
            ->pluck('total', 'month')
            ->all();

        $monthlyExpensesData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyExpensesData[] = $monthlyExpensesGraphData[$i] ?? 0;
        }
        // FIN DU NOUVEAU CODE

        // Statistiques spécifiques à l'employé connecté
        $employeeOrdersCount = Order::where('user_id', $currentUser->id)->count();
        $employeeRevenue = Order::where('user_id', $currentUser->id)->sum('total_amount');

        return view('dashboard', compact(
            'totalClients',
            'totalRevenue',
            'totalOrders',
            'pendingOrders',
            'totalExpenses',
            'monthlyExpensesData',
            'employeeOrdersCount',
            'employeeRevenue',
            'monthlyRevenue', // <-- Nouveaux totaux mensuels
            'monthlyExpenses',
            'monthlyProfit',
            'selectedMonth', // <-- Pour surligner le mois
            'selectedYear' // <-- Pour surligner l'année
        ));
    }
}