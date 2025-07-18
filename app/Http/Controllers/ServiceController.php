<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all(); // Récupère tous les services de la base de données
        return view('services.index', compact('services')); // Passe les services à la vue
    }

    public function create()
    {
        return view('services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation des données
        $request->validate([
            'name' => 'required|string|max:255', // Nom requis, chaîne de caractères, max 255 caractères
            'description' => 'nullable|string', // Description optionnelle, chaîne de caractères
            'price' => 'nullable|numeric|min:0', // Prix optionnel, numérique, doit être >= 0
        ],
        [
            'name.required' => 'Le nom du service est obligatoire.',
            'name.string' => 'Le nom doit être une chaîne de caractères.',
            'name.max' => 'Le nom ne doit pas dépasser 255 caractères.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.min' => 'Le prix ne peut pas être négatif.',
        ]);


        // 2. Création du service
        Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        // 3. Redirection avec un message de succès
        return redirect()->route('services.index')->with('success', 'Service ajouté avec succès !');
        // Ou si vous voulez rediriger vers le formulaire pour ajouter un autre service:
        // return redirect()->route('services.create')->with('success', 'Service ajouté avec succès !');
    }
}
