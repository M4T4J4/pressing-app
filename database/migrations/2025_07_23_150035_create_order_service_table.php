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
        Schema::create('order_service', function (Blueprint $table) {
            // Clé étrangère vers la table 'orders'
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            // Clé étrangère vers la table 'services'
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            // Quantité de ce service dans cette commande
            $table->integer('quantity')->default(1);
            // Prix du service au moment de la commande (pour historiser le prix)
            $table->decimal('price_at_order', 10, 2);
            // Définir une clé primaire composite pour s'assurer de l'unicité
            $table->primary(['order_id', 'service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_service');
    }
};
