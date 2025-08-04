<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{

    public function index()
    {
        $expenses = Expense::latest()->get();
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'nullable|date',
        ]);

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Dépense ajoutée avec succès.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Dépense supprimée avec succès.');
    }

    // Pour cet exemple, nous n'avons pas besoin des méthodes show(), edit(), update()
    // car la gestion peut se faire directement depuis la vue index.
}