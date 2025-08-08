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
    Schema::table('orders', function (Blueprint $table) {
        $table->boolean('paid')->default(false); // Statut de paiement (true/false)
        $table->foreignId('paid_by_user_id')
              ->nullable() // L'ID peut être nul si la commande n'est pas encore payée
              ->constrained('users')
              ->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropForeign(['paid_by_user_id']);
        $table->dropColumn(['paid', 'paid_by_user_id']);
    });
}
};
