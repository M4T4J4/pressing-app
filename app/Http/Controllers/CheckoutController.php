<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Affiche les commandes récentes ou une commande spécifique recherchée.
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $orders = collect();
        $recentOrders = collect();

        // Si une commande est recherchée par son ID
        if ($request->has('order_id') && $request->input('order_id')) {
            $order = Order::with('client', 'services')->find($request->input('order_id'));

            if ($order) {
                // Si la commande est trouvée, on la met dans la collection $orders
                $orders->push($order);
            } else {
                // Si la commande n'est pas trouvée, on redirige avec une erreur
                return back()->with('error', 'Commande non trouvée.');
            }
        } else {
            // Sinon, on récupère les 10 dernières commandes non payées par défaut
            $recentOrders = Order::with('client')
                               ->where('paid', false)
                               ->latest()
                               ->take(10)
                               ->get();
        }

        // On passe à la vue soit la commande unique, soit la liste des commandes récentes
        return view('checkout.index', compact('orders', 'recentOrders'));
    }

    /**
     * Enregistre un paiement pour une commande.
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function pay(Request $request, Order $order)
    {
        // Valide les données (ex: montant payé)
        $request->validate([
            'payment_method' => 'required|string|max:255'
        ]);

        // Met à jour la commande comme payée et change son statut
        $order->update([
            'paid' => true,
            'paid_by_user_id' => auth()->user()->id,
            'status' => 'Prête',
        ]);

        return redirect()->route('checkout.show', $order)->with('success', 'Paiement enregistré avec succès.');
    }

    /**
     * Affiche le reçu d'une commande.
     * @param Order $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order)
    {
        // Charge la commande avec toutes les relations nécessaires pour le reçu
        $order->load(['client', 'services']);

        return view('checkout.show', compact('order'));
    }
}
