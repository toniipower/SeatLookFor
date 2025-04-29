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
        Schema::create('establecimiento', function (Blueprint $table) {
            $table->id('idEst');
            $table->string('ubicacion');
            $table->string('nombre');

            $table->unsignedSmallInteger('idEst');
            
            $table->foreign('idEst')->references('idEst')->on('establecimiento')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('establecimiento');
    }
};
