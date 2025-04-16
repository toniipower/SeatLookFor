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
        Schema::create('reserva', function (Blueprint $table) {
            $table->id('idRes');
            $table->date('fechaReserva');
            $table->decimal('precio');
            $table->decimal('descuento');
            $table->decimal('total');

            $table->unsignedSmallInteger('idEst');
            
            $table->foreign('idEst')->references('idEve')->on('establecimiento')->onDelete('cascade');
            $table->unsignedSmallInteger('idUsu');
            
            $table->foreign('idUsu')->references('idUsu')->on('usuario');

            $table->unsignedSmallInteger('idAsi');
            
            $table->foreign('idAsi')->references('idAsi')->on('asiento');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserva');
    }
};
