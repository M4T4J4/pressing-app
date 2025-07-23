<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    public function orders(): BelongsToMany
    {
        // Le deuxième paramètre est le nom de la table pivot
        // Les troisième et quatrième paramètres sont les noms des clés étrangères sur la table pivot
        return $this->belongsToMany(Order::class, 'order_service')
                    ->withPivot('quantity', 'price_at_order'); // Inclure les colonnes de la table pivot
    }
}
