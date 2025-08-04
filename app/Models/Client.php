<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity; // Importez le trait
use Spatie\Activitylog\LogOptions; // Importez les options

class Client extends Model
{
    use HasFactory, LogsActivity; // Ajoutez le trait ici

    protected $fillable = [
        'name',
        'phone',
        'address',
    ];

    // Méthode pour configurer les options du journal
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable() // Enregistre toutes les colonnes de $fillable
            ->logOnlyDirty() // N'enregistre que les modifications
            ->dontSubmitEmptyLogs(); // Ne crée pas de journal pour les modèles non modifiés
    }
}