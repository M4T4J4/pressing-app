<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller; // Assurez-vous que cette ligne est présente
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::all(); // Récupère tous les clients
        return view('clients.index', compact('clients')); // Passe les clients à la vue
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clients.create'); // Affiche le formulaire de création
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données du formulaire
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ], [
            'name.required' => 'Le nom du client est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
        ]);

        // Utilisez uniquement les données validées pour créer le client
        // Nous spécifions explicitement les champs que nous voulons insérer
        Client::create($request->only(['name', 'phone', 'address', 'email']));

        // Redirection avec un message de succès
        return redirect()->route('clients.index')->with('success', 'Client ajouté avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        // Pour l'instant, nous n'avons pas de vue 'show' dédiée pour un seul client,
        // mais la méthode est là si besoin plus tard.
        // return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client')); // Affiche le formulaire d'édition
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        // Validation des données du formulaire
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ], [
            'name.required' => 'Le nom du client est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
        ]);

        // Utilisez uniquement les données validées pour mettre à jour le client
        // Nous spécifions explicitement les champs que nous voulons mettre à jour
        $client->update($request->only(['name', 'phone', 'address', 'email']));

        // Redirection avec un message de succès
        return redirect()->route('clients.index')->with('success', 'Client mis à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        // Suppression du client
        $client->delete();

        // Redirection avec un message de succès
        return redirect()->route('clients.index')->with('success', 'Client supprimé avec succès !');
    }
}
