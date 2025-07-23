<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Clé étrangère vers la table 'clients'
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            // Statut de la commande (ex: "En attente", "En cours", "Prête", "Terminée", "Annulée")
            $table->string('status')->default('En attente');
            // Date et heure de la prise en charge/dépôt
            $table->timestamp('pickup_date')->nullable();
            // Date et heure de livraison prévue
            $table->timestamp('delivery_date')->nullable();
            // Total de la commande (calculé en fonction des services)
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->text('notes')->nullable(); // Notes additionnelles
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
