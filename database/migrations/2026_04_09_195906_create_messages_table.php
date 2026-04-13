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
    Schema::create('messages', function (Blueprint $table) {
        $table->id();
        $table->string('nom_complet', 150);
        $table->string('nom_entreprise', 150)->nullable();
        $table->string('email_client', 150);
        $table->string('telephone', 30)->nullable();
        $table->string('sujet', 200);
        $table->text('message');
        $table->boolean('lu')->default(false);
        $table->boolean('repondu')->default(false);
        $table->text('reponse_admin')->nullable();
        $table->timestamp('date_envoi')->useCurrent();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
