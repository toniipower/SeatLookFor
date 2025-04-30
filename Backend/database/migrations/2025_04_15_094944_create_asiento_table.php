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
        Schema::create('asiento', function (Blueprint $table) {
            $table->id('idAsi');
           /*  $table->string('nombreAsiento'); */
            $table->string('estado');//ocupado, libre e inactivo
            $table->string('fila');
            $table->string('columna');

            $table->unsignedBigInteger('idEst');

            $table->foreign('idEst')
            ->references('idEst')
            ->on('establecimiento')->ondelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asiento');
    }
};
