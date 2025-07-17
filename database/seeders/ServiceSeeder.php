<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([
            'name' => 'Lavage Simple',
            'description' => 'Lavage de vos vêtements courants avec des détergents écologiques.',
            'price' => 500.00, // Exemple de prix en FCFA
        ]);

        Service::create([
            'name' => 'Nettoyage à Sec',
            'description' => 'Nettoyage professionnel de vos articles délicats (costumes, robes de soirée).',
            'price' => 1500.00,
        ]);

        Service::create([
            'name' => 'Repassage Express',
            'description' => 'Un repassage rapide et soigné pour tous vos textiles.',
            'price' => 300.00,
        ]);

        Service::create([
            'name' => 'Blanchisserie Hôtelière',
            'description' => 'Service spécialisé pour les hôtels et guesthouses (draps, serviettes).',
            'price' => null, // Prix sur devis
        ]);
    }
}
