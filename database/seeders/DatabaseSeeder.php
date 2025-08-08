<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Role;    
use Spatie\Permission\Models\Permission;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ServiceSeeder::class); // Ajoutez cette ligne
        // \App\Models\User::factory(10)->create(); // Vous pouvez laisser ou commenter cette ligne pour les utilisateurs par défaut

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Crée la permission "manage caisse"
    $caissePermission = Permission::firstOrCreate(['name' => 'manage caisse']);

        // Récupère le rôle 'employee' et lui attribue la permission
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);
        $employeeRole->givePermissionTo($caissePermission);
    }
}
