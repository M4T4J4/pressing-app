<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Client; // Pour la sélection du client
use App\Models\Service; // Pour la sélection des services
use Illuminate\Validation\ValidationException; // Pour gérer les erreurs de validation
use App\Http\Controllers\Controller; // Assurez-vous que cette ligne est présente

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    // Récupère le statut de la requête, par défaut 'En attente'
    $status = $request->input('status', 'En attente');

    // Initialise la requête
    $query = Order::with(['client', 'services'])->latest();

    // Applique le filtre de statut si un statut est spécifié
    if ($status === 'all') {
        // Affiche toutes les commandes
        $orders = $query->get();
    } elseif ($status === 'Prête') {
        // Affiche seulement les commandes 'Prête'
        $orders = $query->where('status', 'Prête')->get();
    } elseif ($status === 'Terminée' || $status === 'Annulée') {
        // Affiche les commandes terminées et annulées
        $orders = $query->whereIn('status', ['Terminée', 'Annulée'])->get();
    } else {
        // Par défaut, affiche les commandes 'En attente'
        $orders = $query->where('status', 'En attente')->get();
    }

    // Passe la variable de statut actuelle à la vue pour surligner le bouton
    return view('orders.index', compact('orders', 'status'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all(); // Récupère tous les clients pour le sélecteur
        $services = Service::all(); // Récupère tous les services pour la sélection
        return view('orders.create', compact('clients', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    try {
        // Validation des données de la commande principale
        $validatedOrderData = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'pickup_date' => 'nullable|date',
            'delivery_date' => 'nullable|date|after_or_equal:pickup_date',
            'notes' => 'nullable|string',
            'services' => 'required|array|min:1',
            'services.*.service_id' => 'required|exists:services,id',
            'services.*.quantity' => 'required|integer|min:1',
        ], [
            'client_id.required' => 'Veuillez sélectionner un client.',
            'services.required' => 'Veuillez ajouter au moins un service à la commande.',
            'services.min' => 'Veuillez ajouter au moins un service à la commande.',
            'services.*.service_id.required' => 'Un service est manquant.',
            'services.*.quantity.required' => 'La quantité est obligatoire pour chaque service.'
        ]);

        // 2. Calcul du total et préparation des données pour la table pivot
        $totalAmount = 0;
        $orderServices = [];

        foreach ($validatedOrderData['services'] as $item) {
            $service = Service::find($item['service_id']);

            if (!$service) {
                // En cas de service introuvable, on lève une erreur de validation
                throw ValidationException::withMessages(['services' => 'Un service sélectionné n\'existe pas.']);
            }

            $subtotal = $service->price * $item['quantity'];
            $totalAmount += $subtotal;

            $orderServices[$service->id] = [
                'quantity' => $item['quantity'],
                'price_at_order' => $service->price,
            ];
        }

        // 3. Création de la commande principale
        $order = Order::create([
            'client_id' => $validatedOrderData['client_id'],
            'status' => 'En attente',
            'pickup_date' => $validatedOrderData['pickup_date'],
            'delivery_date' => $validatedOrderData['delivery_date'],
            'notes' => $validatedOrderData['notes'],
            'total_amount' => $totalAmount,
            'user_id' => auth()->user()->id,
        ]);

        // 4. Attacher les services à la commande
        $order->services()->attach($orderServices);

        return redirect()->route('orders.index')->with('success', 'Commande ajoutée avec succès !');

    } catch (ValidationException $e) {
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        // Gérer d'autres erreurs inattendues (ex: échec de l'enregistrement en DB)
        // Vous pouvez logguer l'erreur pour la déboguer plus tard
        \Log::error('Erreur lors de la création de la commande : ' . $e->getMessage());
        return redirect()->back()->with('error', 'Une erreur inattendue est survenue. ' . $e->getMessage())->withInput();
    }
}

/**
 * Update the specified resource in storage.
 */
public function update(Request $request, Order $order)
{
    // 1. Validation des données de la commande principale
    $validatedData = $request->validate([
        'client_id' => 'required|exists:clients,id',
        'status' => 'required|in:En attente,En cours,Prête,Terminée,Annulée',
        'pickup_date' => 'nullable|date',
        'delivery_date' => 'nullable|date|after_or_equal:pickup_date',
        'notes' => 'nullable|string',
        'services' => 'required|array|min:1',
        'services.*.service_id' => 'required|exists:services,id',
        'services.*.quantity' => 'required|integer|min:1',
    ]);

    // 2. Calcul du total et synchronisation des services
    $totalAmount = 0;
    $orderServices = [];

    foreach ($validatedData['services'] as $item) {
        $service = Service::find($item['service_id']);

        if (!$service) {
            // Gérer l'erreur si un service n'existe pas
            return redirect()->back()->with('error', 'Un service sélectionné n\'existe pas.')->withInput();
        }

        $subtotal = $service->price * $item['quantity'];
        $totalAmount += $subtotal;

        $orderServices[$service->id] = [
            'quantity' => $item['quantity'],
            'price_at_order' => $service->price,
        ];
    }

    // 3. Mise à jour de la commande principale
    $order->update([
        'client_id' => $validatedData['client_id'],
        'status' => $validatedData['status'],
        'pickup_date' => $validatedData['pickup_date'],
        'delivery_date' => $validatedData['delivery_date'],
        'notes' => $validatedData['notes'],
        'total_amount' => $totalAmount,
    ]);

    // 4. Synchronisation des services (met à jour la table pivot)
    $order->services()->sync($orderServices);

    return redirect()->route('orders.index')->with('success', 'Commande mise à jour avec succès !');
}

public function edit(Order $order)
{
    $clients = Client::all();
    $services = Service::all();

    // Récupère les services de la commande avec leurs quantités de la table pivot
    $orderServices = $order->services()->withPivot('quantity')->get();

    return view('orders.edit', compact('order', 'clients', 'services', 'orderServices'));
}
/**
 * Remove the specified resource from storage.
 */
public function destroy(Order $order)
{
    // Supprime la commande de la base de données
    $order->delete();

    // Redirige avec un message de succès
    return redirect()->route('orders.index')->with('success', 'Commande supprimée avec succès !');
}
}
