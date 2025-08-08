<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    

    /**
     * Affiche la page de gestion de l'historique.
     */
    public function index()
    {
        return view('history.index');
    }

    /**
     * Gère la suppression de l'historique.
     */
    public function clear(Request $request)
    {
        $request->validate([
            'delete_option' => 'required|in:all,older_than',
            'months' => 'required_if:delete_option,older_than|integer|min:1'
        ]);

        DB::beginTransaction();
        try {
            if ($request->delete_option === 'all') {
                // Supprime toutes les commandes et dépenses terminées/annulées
                $ordersDeleted = Order::whereIn('status', ['Terminée', 'Annulée'])->delete();
                $expensesDeleted = Expense::whereNotNull('amount')->delete(); // Supprime toutes les dépenses
            } elseif ($request->delete_option === 'older_than') {
                $dateLimit = now()->subMonths($request->months);
                // Supprime les commandes terminées/annulées plus anciennes que la date limite
                $ordersDeleted = Order::whereIn('status', ['Terminée', 'Annulée'])
                                        ->where('updated_at', '<', $dateLimit)
                                        ->delete();
                // Supprime les dépenses plus anciennes que la date limite
                $expensesDeleted = Expense::where('expense_date', '<', $dateLimit)
                                        ->delete();
            }

            DB::commit();

            return redirect()->route('history.index')->with('success', 'Historique purgé avec succès ! ' . $ordersDeleted . ' commandes et ' . $expensesDeleted . ' dépenses ont été supprimées.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la purge de l\'historique. ' . $e->getMessage());
        }
    }
}