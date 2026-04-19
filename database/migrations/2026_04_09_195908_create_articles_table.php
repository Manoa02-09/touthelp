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
    $table->string('titre');
    $table->string('slug')->unique();
    $table->text('contenu');
    $table->text('extrait')->nullable();
    $table->string('image_une')->nullable();
    $table->date('date_publication');
    $table->enum('type', ['blog', 'reussite', 'partenariat'])->default('blog');
    $table->boolean('publie')->default(false);
    $table->integer('vu_compteur')->default(0);
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
