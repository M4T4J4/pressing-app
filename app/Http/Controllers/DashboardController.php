<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Order;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // N'oubliez pas d'importer la façade DB
use Carbon\Carbon; // N'oubliez pas d'importer Carbon

class DashboardController extends Controller
{
    public function index()
    {
        $currentUser = auth()->user();

        // Statistiques globales (pour l'administrateur)
        $totalClients = Client::count();
        $totalRevenue = Order::sum('total_amount');
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'En attente')->count();

        // Calcule le total des dépenses
        $totalExpenses = Expense::sum('amount');

        // NOUVEAU CODE POUR LE GRAPHIQUE
        $currentYear = now()->year;
        $monthlyExpenses = Expense::select(
                DB::raw('MONTH(expense_date) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->whereYear('expense_date', $currentYear)
            ->groupBy(DB::raw('MONTH(expense_date)'))
            ->pluck('total', 'month')
            ->all();

        // Crée un tableau pour les 12 mois de l'année, en mettant 0 là où il n'y a pas de données
        $monthlyExpensesData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyExpensesData[] = $monthlyExpenses[$i] ?? 0;
        }
        // FIN DU NOUVEAU CODE

        // Statistiques spécifiques à l'employé connecté
        $employeeOrdersCount = Order::where('user_id', $currentUser->id)->count();
        $employeeRevenue = Order::where('user_id', $currentUser->id)->sum('total_amount');

        // Passage de toutes les variables à la vue
        return view('dashboard', compact(
            'totalClients',
            'totalRevenue',
            'totalOrders',
            'pendingOrders',
            'totalExpenses',
            'monthlyExpensesData', // <-- N'oubliez pas d'ajouter cette variable !
            'employeeOrdersCount',
            'employeeRevenue'
        ));
    }
}