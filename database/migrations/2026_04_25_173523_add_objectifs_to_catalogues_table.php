<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('catalogues', function (Blueprint $table) {
            $table->text('objectifs')->nullable()->after('description');
            $table->text('public_vise')->nullable()->after('objectifs');
        });
    }

    public function down()
    {
        Schema::table('catalogues', function (Blueprint $table) {
            $table->dropColumn(['objectifs', 'public_vise']);
        });
    }
};