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
    Schema::create('devis', function (Blueprint $table) {
        $table->id();
        $table->string('nom_entreprise', 150);
        $table->string('nom_contact', 150);
        $table->string('telephone', 30);
        $table->string('email', 150);
        $table->enum('type_demande', ['formation_catalogue', 'formation_sur_mesure', 'certification', 'audit', 'conseil', 'probleme_interne', 'autre']);
        $table->string('formation_specifique', 100)->nullable();
        $table->text('description_besoin');
        $table->text('message_complementaire')->nullable();
        $table->enum('statut', ['en_attente', 'contacte', 'devis_envoye', 'converti', 'perdu'])->default('en_attente');
        $table->text('reponse_admin')->nullable();
        $table->timestamp('date_soumission')->useCurrent();
        $table->date('date_dernier_contact')->nullable();
        $table->string('source', 50)->default('site_web');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devis');
    }
};
