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
    public function index()
    {
        // Charge les commandes avec leurs clients et services associés pour éviter les requêtes N+1
        $orders = Order::with(['client', 'services'])->latest()->get();
        return view('orders.index', compact('orders'));
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
            // 1. Validation des données de la commande principale
            $validatedOrderData = $request->validate([
                'client_id' => 'required|exists:clients,id',
                'pickup_date' => 'nullable|date',
                'delivery_date' => 'nullable|date|after_or_equal:pickup_date',
                'notes' => 'nullable|string',
                'services' => 'required|array|min:1', // Doit avoir au moins un service
                'services.*.service_id' => 'required|exists:services,id', // Chaque service_id doit exister
                'services.*.quantity' => 'required|integer|min:1', // Chaque quantité doit être un entier > 0
            ], [
                'client_id.required' => 'Veuillez sélectionner un client.',
                'client_id.exists' => 'Le client sélectionné n\'existe pas.',
                'delivery_date.after_or_equal' => 'La date de livraison ne peut pas être antérieure à la date de prise en charge.',
                'services.required' => 'Veuillez ajouter au moins un service à la commande.',
                'services.min' => 'Veuillez ajouter au moins un service à la commande.',
                'services.*.service_id.required' => 'Le service est obligatoire.',
                'services.*.service_id.exists' => 'Le service sélectionné n\'existe pas.',
                'services.*.quantity.required' => 'La quantité est obligatoire pour chaque service.',
                'services.*.quantity.integer' => 'La quantité doit être un nombre entier.',
                'services.*.quantity.min' => 'La quantité doit être d\'au moins 1.',
            ]);

            // 2. Calcul du total de la commande et préparation des données pour la table pivot
            $totalAmount = 0;
            $orderServices = [];

            foreach ($validatedOrderData['services'] as $item) {
                $service = Service::find($item['service_id']);
                if (!$service) {
                    // Ceci ne devrait pas arriver grâce à 'exists:services,id', mais c'est une sécurité
                    throw ValidationException::withMessages(['services' => 'Un service sélectionné n\'existe pas.']);
                }

                $subtotal = $service->price * $item['quantity'];
                $totalAmount += $subtotal;

                $orderServices[$service->id] = [
                    'quantity' => $item['quantity'],
                    'price_at_order' => $service->price, // Enregistre le prix du service au moment de la commande
                ];
            }

            // 3. Création de la commande principale
            $order = Order::create([
                'client_id' => $validatedOrderData['client_id'],
                'status' => 'En attente', // Statut par défaut
                'pickup_date' => $validatedOrderData['pickup_date'],
                'delivery_date' => $validatedOrderData['delivery_date'],
                'notes' => $validatedOrderData['notes'],
                'total_amount' => $totalAmount, // Le total calculé
            ]);

            // 4. Attacher les services à la commande via la table pivot
            $order->services()->attach($orderServices);

            // 5. Redirection avec un message de succès
            return redirect()->route('orders.index')->with('success', 'Commande ajoutée avec succès !');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Gérer d'autres erreurs inattendues
            return redirect()->back()->with('error', 'Une erreur inattendue est survenue lors de l\'ajout de la commande.')->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        // Pour l'instant, nous n'avons pas de vue 'show' dédiée pour une seule commande,
        // mais la méthode est là si besoin plus tard.
        // return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $clients = Client::all();
        $services = Service::all();
        // Récupère les services déjà attachés à cette commande avec leurs pivots
        $orderServices = $order->services->keyBy('id')->map(function ($service) {
            return [
                'service_id' => $service->id,
                'quantity' => $service->pivot->quantity,
                'price_at_order' => $service->pivot->price_at_order,
            ];
        })->toArray();

        return view('orders.edit', compact('order', 'clients', 'services', 'orderServices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        try {
            // 1. Validation des données de la commande principale
            $validatedOrderData = $request->validate([
                'client_id' => 'required|exists:clients,id',
                'status' => 'required|string|in:En attente,En cours,Prête,Terminée,Annulée', // Valider le statut
                'pickup_date' => 'nullable|date',
                'delivery_date' => 'nullable|date|after_or_equal:pickup_date',
                'notes' => 'nullable|string',
                'services' => 'required|array|min:1',
                'services.*.service_id' => 'required|exists:services,id',
                'services.*.quantity' => 'required|integer|min:1',
            ], [
                'client_id.required' => 'Veuillez sélectionner un client.',
                'client_id.exists' => 'Le client sélectionné n\'existe pas.',
                'status.required' => 'Le statut de la commande est obligatoire.',
                'status.in' => 'Le statut de la commande n\'est pas valide.',
                'delivery_date.after_or_equal' => 'La date de livraison ne peut pas être antérieure à la date de prise en charge.',
                'services.required' => 'Veuillez ajouter au moins un service à la commande.',
                'services.min' => 'Veuillez ajouter au moins un service à la commande.',
                'services.*.service_id.required' => 'Le service est obligatoire.',
                'services.*.service_id.exists' => 'Le service sélectionné n\'existe pas.',
                'services.*.quantity.required' => 'La quantité est obligatoire pour chaque service.',
                'services.*.quantity.integer' => 'La quantité doit être un nombre entier.',
                'services.*.quantity.min' => 'La quantité doit être d\'au moins 1.',
            ]);

            // 2. Calcul du total de la commande et préparation des données pour la table pivot
            $totalAmount = 0;
            $orderServices = [];

            foreach ($validatedOrderData['services'] as $item) {
                $service = Service::find($item['service_id']);
                if (!$service) {
                    throw ValidationException::withMessages(['services' => 'Un service sélectionné n\'existe pas.']);
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
                'client_id' => $validatedOrderData['client_id'],
                'status' => $validatedOrderData['status'],
                'pickup_date' => $validatedOrderData['pickup_date'],
                'delivery_date' => $validatedOrderData['delivery_date'],
                'notes' => $validatedOrderData['notes'],
                'total_amount' => $totalAmount,
            ]);

            // 4. Synchroniser les services attachés à la commande
            // 'sync' va détacher les services qui ne sont plus dans la liste et attacher/mettre à jour ceux qui le sont.
            $order->services()->sync($orderServices);

            // 5. Redirection avec un message de succès
            return redirect()->route('orders.index')->with('success', 'Commande mise à jour avec succès !');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur inattendue est survenue lors de la mise à jour de la commande.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Commande supprimée avec succès !');
    }
}
