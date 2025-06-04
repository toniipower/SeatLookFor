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
        Schema::create('comentario', function (Blueprint $table) {

            $table->text('opinion');
            $table->decimal('valoracion');
            $table->string('foto');

            $table->unsignedBigInteger('idUsu');
            $table->unsignedBigInteger('idAsi');

            $table->primary(['idUsu', 'idAsi']);

            $table->foreign('idUsu')
            ->references('idUsu')
            ->on('usuario');

          /*   $table->foreign('idCom')
            ->references('idCom')
            ->on('comentario')
            ->restrictOnDelete()
            ->restrictOnUpdate(); */

            $table->foreign('idAsi')
            ->references('idAsi')
            ->on('asiento');
         

        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comentario');
    }
};
