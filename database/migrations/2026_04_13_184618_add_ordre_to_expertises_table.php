<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    if (!Schema::hasColumn('expertises', 'ordre')) {
        Schema::table('expertises', function (Blueprint $table) {
            $table->integer('ordre')->default(0);
        });
    }
}

public function down(): void
{
    Schema::table('expertises', function (Blueprint $table) {
        $table->dropColumn('ordre');
    });
}
};
