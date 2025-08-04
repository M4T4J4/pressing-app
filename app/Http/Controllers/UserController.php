<?php

namespace App\Http\Controllers;

use App\Models\User; // Assurez-vous d'importer le modèle User
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role; // Pour l'utilisation des rôles Spatie

class UserController extends Controller
{

    /**
     * Affiche la liste des utilisateurs.
     */
    public function index()
    {
        // Récupère tous les utilisateurs sauf l'utilisateur actuellement connecté (l'admin lui-même)
        // C'est une bonne pratique pour éviter que l'admin ne se supprime ou ne se dé-permissionne accidentellement
        $users = User::where('id', '!=', auth()->user()->id)->get();
        return view('users.index', compact('users'));
    }

    /**
     * Affiche le formulaire pour gérer les rôles et permissions d'un utilisateur.
     */
    public function edit(User $user)
    {
        // Récupère tous les rôles disponibles pour les assigner
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Met à jour les rôles d'un utilisateur.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'array', // Doit être un tableau
            'roles.*' => 'exists:roles,name', // Chaque rôle sélectionné doit exister dans la table des rôles
        ]);

        // Synchronise les rôles de l'utilisateur avec ceux sélectionnés dans le formulaire
        // 'syncRoles' est une méthode de Spatie qui ajoute/supprime les rôles pour correspondre au tableau donné
        $user->syncRoles($request->input('roles', [])); // Utilise un tableau vide si aucun rôle n'est sélectionné

        return redirect()->route('users.index')->with('success', 'Rôles de l\'utilisateur mis à jour avec succès !');
    }

    /**
     * Supprime un utilisateur.
     */
    public function destroy(User $user)
    {
        // Sécurité: Empêcher l'admin de se supprimer lui-même
        if ($user->id === auth()->user()->id) {
            return redirect()->route('users.index')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès !');
    }
}