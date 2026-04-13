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
    Schema::create('avis', function (Blueprint $table) {
        $table->id();
        $table->string('entreprise_nom', 150);
        $table->string('contact_nom', 150)->nullable();
        $table->string('contact_fonction', 100)->nullable();
        $table->string('logo_entreprise', 255)->nullable();
        $table->integer('note')->between(1, 5);
        $table->text('contenu');
        $table->enum('statut', ['en_attente', 'publie', 'rejete'])->default('en_attente');
        $table->boolean('mis_en_avant')->default(false);
        $table->timestamp('date_soumission')->useCurrent();
        $table->date('date_publication')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avis');
    }
};
