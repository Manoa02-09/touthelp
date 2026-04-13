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
    Schema::create('expertises', function (Blueprint $table) {
        $table->id();
        $table->string('nom', 100);           // "Formations inter-entreprises"
        $table->string('slug', 100)->unique(); // "inter-entreprises"
        $table->text('description');           // Le texte long du client
        $table->string('icone', 50)->nullable(); // FontAwesome ou emoji
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
        Schema::dropIfExists('expertises');
    }
};
