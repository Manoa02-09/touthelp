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
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('titre', 200);
        $table->string('slug', 200)->unique();
        $table->enum('type', ['blog', 'reussite', 'partenariat']);
        $table->longText('contenu');
        $table->text('extrait')->nullable();
        $table->string('image_une', 255)->nullable();
        $table->string('auteur', 100)->nullable();
        $table->date('date_publication');
        $table->boolean('mis_en_avant')->default(false);
        $table->integer('vu_compteur')->default(0);
        $table->boolean('publie')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
