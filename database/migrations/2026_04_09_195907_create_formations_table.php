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
    Schema::create('formations', function (Blueprint $table) {
    $table->id();
    $table->string('titre');
    $table->string('image')->nullable();
    $table->text('description')->nullable();
    $table->text('description_courte')->nullable();
    $table->date('date_debut');
    $table->date('date_fin')->nullable();
    $table->time('heure')->nullable();
    $table->string('lieu');
    $table->decimal('prix', 10, 0)->nullable();
    $table->integer('places_max')->nullable();
    $table->string('lien_inscription')->nullable();
    $table->boolean('actif')->default(true);
    $table->timestamps();
});
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formations');
    }
};
