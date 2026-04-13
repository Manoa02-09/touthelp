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
    Schema::create('catalogues', function (Blueprint $table) {
        $table->id();
        $table->string('titre', 200);
        $table->text('description')->nullable();
        $table->string('fichier_pdf', 255)->nullable(); // chemin du fichier
        $table->string('image_couverture', 255)->nullable();
        $table->integer('ordre')->default(0);
        $table->boolean('actif')->default(true);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogues');
    }
};
