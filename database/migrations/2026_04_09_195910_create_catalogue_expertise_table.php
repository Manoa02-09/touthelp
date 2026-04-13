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
    Schema::create('catalogue_expertise', function (Blueprint $table) {
        $table->id();
        $table->foreignId('catalogue_id')->constrained()->onDelete('cascade');
        $table->foreignId('expertise_id')->constrained()->onDelete('cascade');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogue_expertise');
    }
};
