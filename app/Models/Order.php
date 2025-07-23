<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importer BelongsTo
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Importer BelongsToMany


class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'status',
        'pickup_date',
        'delivery_date',
        'total_amount',
        'notes',
    ];

    // Ces attributs seront automatiquement castés en instances Carbon
    protected $casts = [
        'pickup_date' => 'datetime',
        'delivery_date' => 'datetime',
    ];

    /**
     * Get the client that owns the order.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the services for the order.
     */
    public function services(): BelongsToMany
    {
        // Le deuxième paramètre est le nom de la table pivot
        // Les troisième et quatrième paramètres sont les noms des clés étrangères sur la table pivot
        return $this->belongsToMany(Service::class, 'order_service')
                    ->withPivot('quantity', 'price_at_order'); // Inclure les colonnes de la table pivot
    }
}
