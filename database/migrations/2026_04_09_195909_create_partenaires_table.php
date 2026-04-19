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
    Schema::create('partenaires', function (Blueprint $table) {
    $table->id();
    $table->string('nom_entreprise', 150);
    $table->string('logo')->nullable();
    $table->string('site_web')->nullable();
    $table->string('description')->nullable();
    $table->integer('ordre_affichage')->default(0);
    $table->boolean('actif')->default(true);
    $table->timestamps();
});
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partenaires');
    }
};
