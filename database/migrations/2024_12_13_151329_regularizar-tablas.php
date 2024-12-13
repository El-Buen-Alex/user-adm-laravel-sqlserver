<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RegularizarTablas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('departamentos', function (Blueprint $table) {
            $table->foreignId('idUsuarioCreacion')->references('id')->on('users');
        });
        Schema::table('cargos', function (Blueprint $table) {
            $table->foreignId('idUsuarioCreacion')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('departamentos', function (Blueprint $table) {
            $table->dropForeign(['idUsuarioCreacion']);
        });
        Schema::table('cargos', function (Blueprint $table) {
            $table->dropForeign(['idUsuarioCreacion']);
        });
    }
}
